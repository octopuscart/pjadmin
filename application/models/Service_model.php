<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Service_model extends CI_Model {

    public string $tablename;
    public array $column_order;
    public array $column_search;
    public array $default_order;
    public array $post_data;
    public array $config;
    public array $child_table;
    public string $pk;
    public array $fk;
    public string $parent_id;

    function __construct() {
        // Set table name
        parent::__construct();
        $this->load->database();
        $this->load->library('parser');
    }

    public function init($tablename, $post_data, $config, $pk, $fk, $child_table = array(), $parent_id = "") {
        $columns = $this->db->list_fields($tablename);
        $this->tablename = $tablename;
        // Set orderable column fields
        $this->column_order = $columns;
        // Set searchable column fields
        $this->column_search = $columns;
        // Set default order
        $this->default_order = array($pk => 'desc');
        $this->post_data = $post_data;
        $this->config = $config;
        $this->child_table = $child_table;
        $this->pk = $pk;
        $this->fk = $fk;
        $this->parent_id = $parent_id;
    }

    /*
     * Fetch members data from the database
     * @param $_POST filter data based on the posted parameters
     */

    public function getRows() {
        $this->_get_datatables_query($this->post_data);
        if ($this->post_data['length'] != -1) {
            $this->db->limit($this->post_data['length'], $this->post_data['start']);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    /*
     * Count all records
     */

    public function countAll() {
        $this->db->from($this->tablename);
        return $this->db->count_all_results();
    }

    public function getOutput() {
        $data = $this->getRows();
        $formatedRow = [];
        foreach ($data as $ind => $row_data) {
            if ($this->child_table) {
                $child_config = $this->config["link"];
                $row_data["link"] = $this->parser->parse_string($child_config["template"], array("field_value" => $row_data[$this->pk], "field_title" => $child_config["field_title"]));
            }
            $row_data["operations"] = $this->parser->parse_string($this->config["operations"], array("pk" => $row_data[$this->pk]));
            $formatedRow[$ind] = $row_data;
            foreach ($row_data as $kd => $vd) {
                if (isset($this->config["field_config"][$kd])) {
                    $fieldConfig = $this->config["field_config"][$kd];
                    $formatedRow[$ind][$kd] = $this->parser->parse_string($fieldConfig["template"], array("field_value" => $vd));
                }
            }
        }
        $output = array(
            "draw" => $this->post_data['draw'],
            "recordsTotal" => $this->countAll(),
            "recordsFiltered" => $this->countFiltered($this->post_data),
            "data" => $formatedRow,
        );
        return $output;
    }

    /*
     * Count records based on the filter params
     * @param $_POST filter data based on the posted parameters
     */

    public function countFiltered($postData) {
        $this->_get_datatables_query($postData);
        $query = $this->db->get();
        return $query->num_rows();
    }

    /*
     * Perform the SQL queries needed for an server-side processing requested
     * @param $_POST filter data based on the posted parameters
     */

    private function _get_datatables_query($postData) {
        if ($this->fk) {
            $this->db->where($this->fk["foreign_key"], $this->parent_id);
        }
        $this->db->from($this->tablename);
        $i = 0;
        // loop searchable columns 
        foreach ($this->column_search as $item) {
            // if datatable send POST for search
            if ($postData['search']['value']) {
                // first loop
                if ($i === 0) {
                    // open bracket
                    $this->db->group_start();
                    $this->db->like($item, $postData['search']['value']);
                } else {
                    $this->db->or_like($item, $postData['search']['value']);
                }
                // last loop
                if (count($this->column_search) - 1 === $i) {
                    // close bracket
                    $this->db->group_end();
                }
            }
            $i++;
        }

        if (isset($postData['order'])) {
            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        } else if (isset($this->default_order)) {
            $order = $this->default_order;
            print_r($order);
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
}
