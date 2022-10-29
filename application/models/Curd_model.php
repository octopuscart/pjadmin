<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Curd_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function query($query) {
        $query = $this->db->query($query);
        $data = $query->result_array();
        return $data; //format the array into json data
    }

    public function insert($table, $data) {
        $this->db->insert($table, $data);
        return $insert_id = $this->db->insert_id();
    }

    public function get($table, $order_by = 'asc') {
        $this->db->order_by('id', $order_by);
        $query = $this->db->get($table);
        $data = $query ? $query->result_array():[];
        return $data;
    }

    public function get_single($table, $id) {
        $this->db->where('id', $id);
        $query = $this->db->get($table);
        $data = $query->row();
        return $data;
    }

    function curdForm($data) {
        $table_name = $data["table_name"];
        $form_attr =  $data['form_attr'];
        if (isset($_POST['submitData'])) {
            $postarray = array();
            foreach ($form_attr as $key => $value) {
                $postarray[$key] = $this->input->post($key);
            }
            $this->Curd_model->insert($table_name, $postarray);
            redirect($data["link"]);
        }
        $categories_data = $this->Curd_model->get($table_name);
        $data['list_data'] = $categories_data;
        $fields = array();
        $fields["id"] = array("title" => "ID#", "width" => "100px");
       $fields = array_merge($fields, $form_attr);
        $data['fields'] = $fields;
        $data['form_attr'] = $form_attr;
        return $data;
    }

}

?>