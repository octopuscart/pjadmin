<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CMS extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->curd = $this->load->model('Curd_model');
        $session_user = $this->session->userdata('logged_in');
        if ($session_user) {
            $this->user_id = $session_user['login_id'];
        } else {
            $this->user_id = 0;
        }
        $this->user_id = $this->session->userdata('logged_in')['login_id'];
        $this->user_type = $this->session->logged_in['user_type'];
    }

    ####################################

    public function newLookbook() {
        $data = array();

        $data['categories'] = array();

        $config['upload_path'] = 'assets/look_books';
        $config['allowed_types'] = '*';
        if (isset($_POST['submit_data'])) {
            $picture = '';

            if (!empty($_FILES['picture']['name'])) {
                $temp1 = rand(100, 1000000);
                $config['overwrite'] = TRUE;
                $ext1 = explode('.', $_FILES['picture']['name']);
                $ext = strtolower(end($ext1));
                $file_newname = $temp1 . "$userid." . $ext;
                $picture = $file_newname;
                $config['file_name'] = $file_newname;
                //Load upload library and initialize configuration
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('picture')) {
                    $uploadData = $this->upload->data();
                    $picture = $uploadData['file_name'];
                } else {
                    $picture = '';
                }
            }




            $blogArray = array(
                "look_book_file" => "",
                "look_book_image" => $picture,
                "look_book_title" => $this->input->post("title"),
            );

            $this->Curd_model->insert('look_books', $blogArray);
            redirect("CMS/lookbook");
        }

        $this->load->view('CMS/lookbook/new_lookbook', $data);
    }

    function lookbook() {
        $blog_data = $this->Curd_model->get('look_books', 'desc');
        $data['blog_data'] = $blog_data;
        $this->load->view('CMS/lookbook/lookbook_list', $data);
    }

    function lookbookDelete($lookid) {
        $this->db->where("id", $lookid)->delete("look_books");
        redirect("CMS/lookbook");
    }

    public function socialLink() {
        $data = array();
        $data['title'] = "Social Link";
        $data['description'] = "Social Link";
        $data['form_title'] = "Add Social Link";
        $data['table_name'] = 'conf_social_link';
        $form_attr = array(
            "title" => array("title" => "Title", "required" => true, "place_holder" => "Title", "type" => "text", "default" => ""),
            "link_url" => array("title" => "URL", "required" => false, "place_holder" => "Link", "type" => "text", "default" => ""),
            "display_index" => array("title" => "Index", "required" => false, "place_holder" => "Display Index", "type" => "text", "default" => ""),
        );

        if (isset($_POST['submitData'])) {
            $postarray = array();
            foreach ($form_attr as $key => $value) {
                $postarray[$key] = $this->input->post($key);
            }
            $this->Curd_model->insert('conf_social_link', $postarray);
            redirect("CMS/socialLink");
        }


        $categories_data = $this->Curd_model->get('conf_social_link');

        $socialLinks = array(
            "Facebook" => array("title" => "Facebook", "icon" => "", "display_index" => 1),
            "Twitter" => array("title" => "Twitter", "icon" => "", "display_index" => 2),
            "Instagram" => array("title" => "Instagram", "icon" => "", "display_index" => 3),
            "TripAdvisor" => array("title" => "TripAdvisor", "icon" => "", "display_index" => 4),
            "Pinterest" => array("title" => "Pinterest", "icon" => "", "display_index" => 5),
            "YouTube" => array("title" => "YouTube", "icon" => "", "display_index" => 6),
            "Tumblr" => array("title" => "Tumblr", "icon" => "", "display_index" => 7),
            "LinkedIn" => array("title" => "LinkedIn", "icon" => "", "display_index" => 8),
        );
        foreach ($socialLinks as $sskey => $ssvalue) {

            $this->db->where('title', $sskey);
            $query = $this->db->get('conf_social_link');
            $systemlog = $query->result();
            if ($systemlog) {
                
            } else {

                unset($ssvalue["icon"]);
                $this->Curd_model->insert('conf_social_link', $ssvalue);
            }
        }


        $data['list_data'] = $categories_data;

        $fields = array(
            "title" => array("title" => "Social Account", "width" => "200px", "edit" => 0),
            "link_url" => array("title" => "URL", "width" => "300px", "edit" => 1),
        );

        $data['fields'] = $fields;
        $data['form_attr'] = $form_attr;
        $this->load->view('configuration/socialLinks', $data);
    }

    public function seoPageSetting() {
        $data = array();
        $data['title'] = "Set The Page wise SEO Attributes";
        $data['description'] = "SEO";
        $data['form_title'] = "SEO";
        $data['table_name'] = 'seo_settings';
        $form_attr = array(
            "seo_title" => array("title" => "Title", "required" => true, "place_holder" => "Title", "type" => "text", "default" => ""),
            "seo_description" => array("title" => "Description", "required" => true, "place_holder" => "Description", "type" => "textarea", "default" => ""),
            "seo_keywords" => array("title" => "Keywords", "required" => true, "place_holder" => "Keywords", "type" => "textarea", "default" => ""),
            "seo_url" => array("title" => "Page URL", "required" => false, "place_holder" => "Link", "type" => "text", "default" => ""),
        );

        if (isset($_POST['submitData'])) {
            $postarray = array();
            foreach ($form_attr as $key => $value) {
                $postarray[$key] = $this->input->post($key);
            }
            $this->Curd_model->insert('seo_settings', $postarray);
            redirect("CMS/seoPageSetting");
        }


        $categories_data = $this->Curd_model->get('seo_settings');
        $data['list_data'] = $categories_data;

        $fields = array(
            "id" => array("title" => "ID#", "width" => "100px"),
            "seo_title" => array("title" => "Title", "width" => "200px"),
            "seo_description" => array("title" => "Description", "width" => "200px"),
            "seo_keywords" => array("title" => "Keywords", "width" => "200px"),
            "seo_url" => array("title" => "URL", "width" => "200px"),
        );

        $data['fields'] = $fields;
        $data['form_attr'] = $form_attr;
        $this->load->view('layout/curd', $data);
    }

    public function siteSEOConfigUpdate() {
        $data = array();
        $blog_data = $this->Curd_model->get_single('configuration_site', 1);
        $data['site_data'] = $blog_data;
        if (isset($_POST['update_data'])) {
            $blogArray = array(
                "site_name" => $this->input->post("site_name"),
                "seo_keywords" => $this->input->post("keyword"),
                "seo_title" => $this->input->post("title"),
                "seo_desc" => $this->input->post("description"),
            );

            $this->db->where('id', 1);
            $this->db->update('configuration_site', $blogArray);
            redirect("CMS/siteConfigUpdate");
        }

        $this->load->view('configuration/site_update', $data);
    }

    public function faqSetting() {
        $data = array();
        $data['title'] = "Set FAQ's For Website";
        $data['description'] = "FAQ's";
        $data['form_title'] = "FAQ's";
        $data['table_name'] = "content_faq";
        $data["link"] = "CMS/faqSetting";
        $form_attr = array(
            "question" => array("title" => "Question", "width" => "250px", "required" => true, "place_holder" => "Question", "type" => "textarea", "default" => ""),
            "answer" => array("title" => "Answer", "width" => "300px", "required" => true, "place_holder" => "Answer", "type" => "textarea", "default" => ""),
            "display_index" => array("title" => "Display Index", "required" => false, "place_holder" => "Display Index", "type" => "text", "default" => ""),
        );
        $data['form_attr'] = $form_attr;
        $rdata = $this->Curd_model->curdForm($data);

        $this->load->view('layout/curd', $rdata);
    }

    public function testimonialSetting() {
        $data = array();
        $data['title'] = "Set Testimonial For Website";
        $data['description'] = "Testimonial";
        $data['form_title'] = "Testimonial";
        $data['table_name'] = "content_testimonial";
        $data["link"] = "CMS/testimonialSetting";
        $form_attr = array(
            "review" => array("title" => "Review", "width" => "250px", "required" => true, "place_holder" => "Review", "type" => "textarea", "default" => ""),
            "name" => array("title" => "Name", "width" => "300px", "required" => true, "place_holder" => "Name", "type" => "text", "default" => ""),
            "source" => array("title" => "Source", "required" => false, "place_holder" => "Source", "type" => "text", "default" => ""),
            "city_country" => array("title" => "City/Country", "width" => "300px", "required" => true, "place_holder" => "City/Country", "type" => "text", "default" => ""),
            "r_date" => array("title" => "Date", "required" => false, "place_holder" => "Date", "type" => "text", "default" => ""),
        );
        $data['form_attr'] = $form_attr;
        $rdata = $this->Curd_model->curdForm($data);

        $this->load->view('layout/curd', $rdata);
    }

}

?>
