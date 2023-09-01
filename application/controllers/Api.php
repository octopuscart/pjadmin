<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH . 'libraries/REST_Controller.php');

class Api extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Curd_model');
        $this->load->model('Service_model');
        $session_user = $this->session->userdata('logged_in');
        if ($session_user) {
            $this->user_id = $session_user['login_id'];
            $this->user_type = isset($session_user['user_type']) ? $session_user['user_type'] : "";
        } else {
            $this->user_id = 0;
            $this->user_type = "";
        }
    }

    public function index() {
        $this->load->view('welcome_message');
    }

    public function tableStructure_get($tablename) {
        $fields = $this->db->field_data($tablename);
        $this->response($fields);
    }

    function lifeChangeingVideos_get($language_id) {
        $finaldata = [];
        $model_data = $this->Curd_model->getCondition("life_changing_videos",
                array("language" => "$language_id"), "desc");
        foreach ($model_data as $key => $value) {
            array_push($finaldata, $value);
        }
        $this->response($finaldata);
    }

    function lyricsData_get($lyrics_id) {
        $finaldata = [];
        $model_data = $this->Curd_model->getCondition("lyrics_tracks",
                array("lyricsid" => "$lyrics_id"), "desc");
        foreach ($model_data as $key => $value) {
            $value["image"] = base_url("assets/uploadata/worship_songs/thumbs/" . $value["image"]);
            array_push($finaldata, $value);
        }
        $this->response($finaldata);
    }

    function albumData_get($album_id) {
        $finaldata = [];
        $model_data = $this->Curd_model->getCondition("worship_songs",
                array("albumId" => "$album_id"), "desc");
        foreach ($model_data as $key => $value) {
            $value["image"] = base_url("assets/uploadata/worship_songs/thumbs/" . $value["image"]);
            $value["song"] = base_url("assets/uploadata/worship_songs/" . $value["song"]);
            array_push($finaldata, $value);
        }
        $this->response($finaldata);
    }

    function charityWorkImages_get($charity_id) {
        $finaldata = [];
        $model_data = $this->Curd_model->getCondition("charity_images",
                array("charityWorkId" => "$charity_id"), "desc");
        foreach ($model_data as $key => $value) {
            $value["image"] = base_url("assets/uploadata/charity/" . $value["image"]);
            array_push($finaldata, $value);
        }
        $this->response($finaldata);
    }

    function listApiData_get($apipath, $isDataTableCall = "no") {

        $meta_data = $this->input->get();
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
        $search = $this->input->get("search");

        $apiSet = APISET;
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
        header('Access-Control-Allow-Origin: *');
        $finaldata = [];
        if (isset($apiSet[$apipath])) {
            $tablename = $apiSet[$apipath]["table"];
            $imagepath = $apiSet[$apipath]["imagefolder"];
            $fields = $this->db->field_data($tablename);
            $tableFieldsName = array();

            foreach ($fields as $fik => $fiv) {
                array_push($tableFieldsName, $fiv->name);
            }

            $model_data = $this->Curd_model->get($tablename);
            foreach ($model_data as $key => $value) {
                if ($isDataTableCall == "no") {
                    $value["image"] = base_url("assets/uploadata/$imagepath/" . $value["image"]);
                } else {
                    $value["image"] = "<img class='shortImageTableData' src='" . base_url("assets/uploadata/$imagepath/" . $value["image"]) . "'>";
                }
                array_push($finaldata, $value);
            }
        }
        if ($isDataTableCall == "yes") {
            $output = array(
                "draw" => $draw,
                "recordsTotal" => $this->db->count_all("$tablename"),
                "recordsFiltered" => 10,
                "data" => $finaldata
            );
            $this->response($output);
        } else {
            $this->response($finaldata);
        }
    }

    function dataTableApi_get($apipath, $parent_id = 0) {
        $apiSet = APISET;
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
        header('Access-Control-Allow-Origin: *');
        $finaldata = [];
        $post_data = $this->input->get();
        if (isset($apiSet[$apipath])) {
            $apiObj = $apiSet[$apipath];
            $tablename = $apiObj["table"];
            $imagepath = isset($apiObj["imagefolder"]) ? $apiObj["imagefolder"] : "";
            $image_field = isset($apiObj["image_field"]) ? $apiObj["image_field"] : "";
            $child_table = isset($apiObj["child_api"]) ? $apiObj["child_api"] : array();
            $config = array();

            if ($imagepath) {
                $backgroundImage = "background-image:url(' " . base_url("assets/uploadata/$imagepath/") . "{field_value}')";
                $config["field_config"] = array(
                    "$image_field" => array(
                        "field_value" => $image_field,
                        "template" => '<div class="shortImageTableData" style="' . $backgroundImage . '"></div><p><a target="_blank" href="' . base_url("assets/uploadata/$imagepath/") . '{field_value}">View Image</a></p>'
                    ),
                );
            }

            if ($child_table) {
                $config["link"] = array(
                    "field_value" => $child_table["pk"],
                    "field_title" => $child_table["connect_button"],
                    "template" => ''
                    . '<a class="btn btn-primary btn-sm" href="' . site_url("Services/tableReport/" . $child_table["child_api"]) . '/{field_value}">{field_title}</a>'
                );
            }


            if (isset($apiObj["writable"])) {
                $config["operations"] = '<a class="btn btn-warning btn-sm" href="' . site_url("Services/updateData/$apipath") . '/{pk}"><i class="fa fa-edit"></i></a>&nbsp;'
                        . '<button class="btn btn-danger btn-sm button deleterow"  href="' . site_url("Api/deleteRecord/$apipath") . '/{pk}"><i class="fa fa-trash"></i></button>';
            }
            else{
                      $config["operations"] = '<button class="btn btn-danger btn-sm button deleterow"  href="' . site_url("Api/deleteRecord/$apipath") . '/{pk}"><i class="fa fa-trash"></i></button>';
           
            }

            $this->Service_model->init($tablename, $post_data, $config, $apiObj["pk"], $apiObj["foreign_key"], $child_table, $parent_id);
            $response = $this->Service_model->getOutput();
            $this->response($response);
        }
    }

    function ajax_upload_image_post($apipath) {
        $apiConfig = $this->Curd_model->getApiConfig($apipath);
        $serviceObj = $apiConfig["serviceObj"];
        $imageFolder = (isset($serviceObj["imagefolder"]) ? $serviceObj["imagefolder"] : "");
        $response = array("file_path" => "", "file_name" => "", "error" => "");
        $temp1 = rand(100, 1000000);
        $randomefilename = $this->Curd_model->generateRandomString(25, $apipath);

        if (isset($_FILES["image_file"]["name"])) {
            $ext1 = explode('.', $_FILES['image_file']['name']);
            $ext = strtolower(end($ext1));
            $file_newname = $randomefilename . "." . $ext;
            $config['file_name'] = $file_newname;
            $config['upload_path'] = 'assets/uploadata/' . $imageFolder;
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('image_file')) {
                $response["error"] = $this->upload->display_errors();
            } else {
                $data = $this->upload->data();
                $response["file_path"] = base_url("assets/uploadata/$imageFolder/" . $data["file_name"]);
                $response["file_name"] = $data["file_name"];
            }
        }
        $this->response($response);
    }

    function deleteRecord_get($apipath, $id) {
        if ($this->user_id) {
            $this->Curd_model->deleteRecord($apipath, $id);
        }
    }

    function ajax_upload_file_post() {
        $apiConfig = $this->Curd_model->getApiConfig($apipath);
        $serviceObj = $apiConfig["serviceObj"];
        $imageFolder = (isset($serviceObj["imagefolder"]) ? $serviceObj["imagefolder"] : "");
        $response = array("image_path" => "", "image_name" => "");
        if (isset($_FILES["image_file"]["name"])) {
            $config['upload_path'] = 'assets/uploadata/' . $imageFolder;
            $config['allowed_types'] = 'mp3|pdf|doc|docx';
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('image_file')) {
                echo $this->upload->display_errors();
            } else {
                $data = $this->upload->data();
                $response = array();
                $this->response($response);
                echo '<img src="' . base_url("assets/uploadata/") . '' . $data["file_name"] . '" width="300" height="225" class="img-thumbnail" />';
            }
        }
    }
}

?>