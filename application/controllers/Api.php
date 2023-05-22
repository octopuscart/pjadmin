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

    public function tableStructure_get($tablename){
        echo $tablename;
        $fields = $this->db->field_data($tablename);
        $this->response($fields);

    }

    function lifeChangeingVideos_get($language_id){
        $finaldata = [];
        $model_data = $this->Curd_model->getCondition("life_changing_videos", 
        array("language"=>"$language_id"), "desc");
        foreach ($model_data as $key => $value) {
                array_push($finaldata, $value);
        }
        $this->response($finaldata);
    }

    function lyricsData_get($lyrics_id){
        $finaldata = [];
        $model_data = $this->Curd_model->getCondition("lyrics_tracks", 
        array("lyricsid"=>"$lyrics_id"), "desc");
        foreach ($model_data as $key => $value) {
            $value["image"] = base_url("assets/uploadata/worship_songs/thumbs/" . $value["image"]);
            array_push($finaldata, $value);
        }
        $this->response($finaldata);
    }

    function albumData_get($album_id){
        $finaldata = [];
        $model_data = $this->Curd_model->getCondition("worship_songs", 
        array("albumId"=>"$album_id"), "desc");
        foreach ($model_data as $key => $value) {
            $value["image"] = base_url("assets/uploadata/worship_songs/thumbs/" . $value["image"]);
            $value["song"] = base_url("assets/uploadata/worship_songs/" . $value["song"]);
            array_push($finaldata, $value);
        }
        $this->response($finaldata);
    }

    function charityWorkImages_get($charity_id){
        $finaldata = [];
        $model_data = $this->Curd_model->getCondition("charity_images", 
        array("charityWorkId"=>"$charity_id"), "desc");
        foreach ($model_data as $key => $value) {
            $value["image"] = base_url("assets/uploadata/charity/" . $value["image"]);
            array_push($finaldata, $value);
        }
        $this->response($finaldata);
    }

    function listApiData_get($apipath) {
        $meta_data = $this->input->get();
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
        $search = $this->input->get("search");

        print_r($meta_data);
        $apiSet = array(
            "ourChurchese" => array(
                "table" => "our_churches",
                "imagefolder" => "our_churches",
            ),
            "tvPrograms" => array(
                "table" => "tv_program",
                "imagefolder" => "tv_programs",
            ),
            "bibleCollege" => array(
                "table" => "paul_bible",
                "imagefolder" => "bible_collage",
            ),
            "pastorsLeaders" => array(
                "table" => "pastors_images",
                "imagefolder" => "pastors",
            ),
            "aboutUs" => array(
                "table" => "paul_about_us",
                "imagefolder" => "about_us",
            ),
            "todaysBlessings"=>array(
                "table"=>"todays_blessings",
                "imagefolder"=>"blessings",
            ),
            "donateImages"=>array(
                "table"=>"donate_images",
                "imagefolder"=>"donate_support",
            ),
            "paulEvent"=>array(
                "table"=>"paul_event",
                "imagefolder"=>"events",
            ),
            "amazoneBooks"=>array(
                "table"=>"amazone_books",
                "imagefolder"=>"amazone_books",
            ),
            "lyrics"=>array(
                "table"=>"lyrics",
                "imagefolder"=>"worship_songs/thumbs"
            ),
            "lyrics"=>array(
                "table"=>"lyrics",
                "imagefolder"=>"worship_songs/thumbs"
            ),
            "worshipSongsAlbum"=>array(
                "table"=>"paul_audio_album",
                "imagefolder"=>"worship_songs/thumbs"
            ),
            "charityWorks"=>array(
                "table"=>"charity_work",
                "imagefolder"=>"charity"
            )
        );
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
        header('Access-Control-Allow-Origin: *');
        $finaldata = [];
        if (isset($apiSet[$apipath])) {
           echo  $tablename = $apiSet[$apipath]["table"];
            $imagepath = $apiSet[$apipath]["imagefolder"];
            $fields = $this->db->field_data($tablename);
            $tableFieldsName = array();
        
            foreach ($fields as $fik => $fiv) {
                echo $fiv->name;
                array_push($tableFieldsName, $fiv->name);
            }
            
            $model_data = $this->Curd_model->get($tablename);
            foreach ($model_data as $key => $value) {
                $value["image"] = base_url("assets/uploadata/$imagepath/" . $value["image"]);
                array_push($finaldata, $value);
            }
        }
        $this->response($finaldata);
    }

}

?>