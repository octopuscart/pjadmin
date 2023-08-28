<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH . 'libraries/REST_Controller.php');

class Api extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Curd_model');
    }

    public function index() {
        $this->load->view('welcome_message');
    }

    public function tableStructure_get($tablename) {
        echo $tablename;
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

}

?>