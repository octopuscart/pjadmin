<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Services extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $session_user = $this->session->userdata('logged_in');
        if ($session_user) {
            $this->user_id = $session_user['login_id'];
        } else {
            $this->user_id = 0;
        }
    }

    public function tableReport($apipath) {
        $serviceObj = APISET[$apipath];
        $fieldsName = $this->db->list_fields($serviceObj["table"]);

        $data = array("apipath" => $apipath, "fieldsName" => $fieldsName, "title"=>$serviceObj["title"]);
        $this->load->view('Services/tableReport', $data);
    }

}

?>
