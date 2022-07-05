<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }

    function edit_table_information($tableName, $id) {
        $this->User_model->tracking_data_insert($tableName, $id, 'update');
        $this->db->update($tableName, $id);
    }

    public function query_exe($query) {
        $query = $this->db->query($query);
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
            return $data; //format the array into json data
        }
    }

    function delete_table_information($tableName, $columnName, $id) {
        $this->db->where($columnName, $id);
        $this->db->delete($tableName);
    }

    ///*******  Get data for deepth of the array  ********///

    function get_children($id, $container) {
        $this->db->where('id', $id);
        $query = $this->db->get('category');
        $category = $query->result_array()[0];
        $this->db->where('parent_id', $id);
        $query = $this->db->get('category');
        if ($query->num_rows() > 0) {
            $childrens = $query->result_array();

            $category['children'] = $query->result_array();

            foreach ($query->result_array() as $row) {
                $pid = $row['id'];
                $this->get_children($pid, $container);
            }

            print_r($category);
            return $category;
        } else {
            
        }
    }

    function getparent($id, $texts) {
        $this->db->where('id', $id);
        $query = $this->db->get('category');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                array_push($texts, $row);
                $texts = $this->getparent($row['parent_id'], $texts);
            }
            return $texts;
        } else {
            return $texts; //format the array into json data
        }
    }

    function parent_get($id) {
        $catarray = $this->getparent($id, []);
        array_reverse($catarray);
        $catarray = array_reverse($catarray, $preserve_keys = FALSE);
        $catcontain = array();
        foreach ($catarray as $key => $value) {
            array_push($catcontain, $value['category_name']);
        }
        $catstring = implode("->", $catcontain);
        return array('category_string' => $catstring, "category_array" => $catarray);
    }

    function child($id) {
        $this->db->where('parent_id', $id);
        $query = $this->db->get('category');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {

                $cat[] = $row;
                $cat[$row['id']] = $this->child($row['id']);
                $cat[] = $row;
            }

            return $cat; //format the array into json data
        }
    }

    function singleProductAttrs($product_id) {
        $query = "SELECT pa.attribute, pa.product_id, pa.attribute_value_id, cav.attribute_value FROM product_attribute as pa 
join category_attribute_value as cav on cav.id = pa.attribute_value_id
where pa.product_id = $product_id group by attribute_value_id";
        $product_attr_value = $this->query_exe($query);
        $arrayattr = [];
        foreach ($product_attr_value as $key => $value) {
            $attrk = $value['attribute'];
            $attrv = $value['attribute_value'];
            array_push($arrayattr, $attrk . '-' . $attrv);
        }
        return implode(", ", $arrayattr);
    }

    function product_attribute_list($product_id) {
        $this->db->where('product_id', $product_id);
        $this->db->group_by('attribute_value_id');
        $query = $this->db->get('product_attribute');
        $atterarray = array();
        if ($query->num_rows() > 0) {
            $attrs = $query->result_array();
            foreach ($attrs as $key => $value) {
                $atterarray[$value['attribute_id']] = $value;
            }
            return $atterarray;
        } else {
            return array();
        }
    }

    function productAttributes($product_id) {
        $pquery = "SELECT pa.attribute, cav.attribute_value, cav.additional_value FROM product_attribute as pa
      join category_attribute_value as cav on cav.id = pa.attribute_value_id
      where pa.product_id = $product_id";
        $attr_products = $this->query_exe($pquery);
        return $attr_products;
    }

    function variant_product_attr($product_id) {
        $queryr = "SELECT pa.attribute_id, pa.attribute, pa.product_id, pa.attribute_value_id, cav.attribute_value FROM product_attribute as pa 
join category_attribute_value as cav on cav.id = pa.attribute_value_id 
where pa.product_id=$product_id ";
        $query = $this->db->query($queryr);
        return $query->result_array();
    }

    function category_attribute_list($id) {
        $this->db->where('attribute_id', $id);
        $this->db->group_by('attribute_value');
        $query = $this->db->get('category_attribute_value');
        if ($query->num_rows() > 0) {
            $attrs = $query->result_array();
            return $attrs;
        } else {
            return array();
        }
    }

    function category_items_prices_id($category_items_id) {

        $queryr = "SELECT cip.price, ci.item_name, cip.id FROM custome_items_price as cip
                       join custome_items as ci on ci.id = cip.item_id
                       where cip.category_items_id = $category_items_id";
        if ($category_items_id) {
            $query = $this->db->query($queryr);
            $category_items_price_array = $query->result();
            return $category_items_price_array;
        } else {
            return array();
        }
    }

    function category_items_prices() {
        $query = $this->db->get('category_items');
        $category_items = $query->result();
        $category_items_return = array();
        foreach ($category_items as $citkey => $citvalue) {
            $category_items_id = $citvalue->id;
            $category_items_price_array = $this->category_items_prices_id($category_items_id);
            $citvalue->prices = $category_items_price_array;
            array_push($category_items_return, $citvalue);
        }
        return $category_items_return;
    }

///udpate after 16-02-2019
    function stringCategories($category_id) {
        $this->db->where('parent_id', $category_id);
        $query = $this->db->get('category');
        $category = $query->result_array();
        $container = "";
        foreach ($category as $ckey => $cvalue) {
            $container .= $this->stringCategories($cvalue['id']);
            $container .= ", " . $cvalue['id'];
        }
        return $container;
    }

    //previouse customization
    public function selectPreviouseProfiles($user_id, $item_id) {
        $itemquery = "";
        if ($item_id) {
            $itemquery = " and  item_id = $item_id";
        }
        $row_query = "SELECT id, item_name, item_id, status,  op_date_time, order_id FROM `cart` where user_id = $user_id $itemquery   and attrs ='Custom' and status!='d' and id in (select cart_id from cart_customization) order by status desc;";
        $query = $this->db->query($row_query);
        $data = [];
        $preitemdata = array("has_pre_design" => false, "designs" => array());
        if ($query) {
            $customdatadata = $query->result_array();
            foreach ($customdatadata as $key => $value) {
                $order_no = $this->db->where("id", $value["order_id"])->get("user_order")->row_array();
                $profilename = $value["item_name"] . " " . $value["id"];
                $customdata = $this->db->select("style_key, style_value")->where("cart_id", $value["id"])->get("cart_customization")->result_array();

                $customdatadict = array();
                foreach ($customdata as $ckey => $cvalue) {
                    $customdatadictp[$cvalue["style_key"]] = $cvalue["style_value"];
                }
                $tempdata = array(
                    "name" => $profilename,
                    "order_no" => $order_no ? $order_no["order_no"] : "RF" . str_replace("-", "/", $value["id"]),
                    "cart_data" => $value,
                    "style" => $customdata
                );
                array_push($preitemdata["designs"], $tempdata);
                $preitemdata["has_pre_design"] = true;
            }
        }
        return $preitemdata;
    }

    //previouse measurements
    public function selectPreviousMeasurements($user_id, $items_array = "") {

        $row_query = "SELECT id, profile, order_id, user_id, status, datetime FROM `custom_measurement_profile` where user_id = $user_id and status!='d'  order by status desc;";
        $query = $this->db->query($row_query);

        $preitemdata = array("has_pre_measurement" => false, "measurement" => array());
        if ($query) {
            $customdatadata = $query->result_array();
            foreach ($customdatadata as $key => $value) {
                $order_no = $this->db->where("measurement_id", $value["id"])->order_by("id desc")->get("user_order")->row_array();
                
                $customdata = $this->db->select("measurement_key, measurement_value")->where("custom_measurement_profile", $value["id"])->get("custom_measurement")->result_array();

                $customdatadict = array();
                foreach ($customdata as $ckey => $cvalue) {
                    $customdatadictp[$cvalue["measurement_key"]] = $cvalue["measurement_value"];
                }
                $tempdata = array(
                    "name" => $value["profile"],
                    "order_no" => $order_no ? $order_no["order_no"] : "No Order",
                    "meausrement_data" => $value,
                    "measurements" => $customdata
                );
                array_push($preitemdata["measurement"], $tempdata);
                $preitemdata["has_pre_measurement"] = true;
            }
        }
        return $preitemdata;
    }

}
