<?php
$this->load->view('layout/header');
$this->load->view('layout/topmenu');
?>
<link href="<?php echo base_url(); ?>assets/plugins/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet" />
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap3-editable/js/bootstrap-editable.min.js"></script>

<link href="<?php echo base_url(); ?>assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet"  />

<link href="<?php echo base_url(); ?>assets/plugins/jquery-file-upload/css/jquery.fileupload.css" rel="stylesheet" />
<link href="<?php echo base_url(); ?>assets/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet" />

<section class="content">
    <div class="row">

        <!-- /.col -->
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"> <i class="fa fa-edit"></i> Add New <?php echo $title; ?></h3>


                </div>
                <div class="panel-body ">
                    <div class="col-md-5"> 


                        <?php
                        $fields_config_js_object = array();
                        $field_config = isset($serviceObj["field_config"]) ? $serviceObj["field_config"] : array();
                       
                        if ($field_config) {
                            foreach ($field_config as $fkey => $fvalue) {
                                if ($fvalue["type"] === "file") {
                                    $input_field_id = $fkey;
                                    $form_id = "file_upload_form_$fkey";
                                    $button_id = "file_upload_$fkey";
                                    $file_progress_id = "file_upload_proress_$fkey";
                                    $mime_type = $fvalue["mime_type"];
                                    $file_name = "file_name_$fkey";
                                    $file_name_tag = "file_name_tag_$fkey";
                                    $upload_folder = $fvalue["upload_folder"];
                                    $upload_folder_field = "upload_folder_field_$fkey";
                                    $widget = $fvalue["widget"];
                                    $allowed_types_field = "allowed_types_field_$fkey";
                                    $allowed_types = $fvalue["allowed_types"];
                                    $fields_config_js_object[$form_id] = array(
                                        "input_field_id" => $input_field_id,
                                        "form_id" => $form_id,
                                        "button_id" => $button_id,
                                        "progress_bar_id" => $file_progress_id,
                                        "file_name" => "file_name_$fkey",
                                        "file_name_tag" => $file_name_tag,
                                        "apipath" => $apipath,
                                        "upload_folder" => $upload_folder,
                                        "upload_folder_field" => $upload_folder_field,
                                        "allowed_types" => $allowed_types,
                                        "allowed_types_field" => $allowed_types_field,
                                    );
                                    ?>
                                    <form method="post" id="<?php echo $form_id; ?>" align="center" enctype="multipart/form-data">  
                                        <div class="col-12 well well-sm <?php echo $widget == "imageuploaer" ? "thumbnail" : ""; ?> ">
                                            <?php
                                            if ($widget == "imageuploaer") {
                                                ?>
                                                <img src="<?php echo base_url(); ?>assets/default2.png" style="width:50%"/>
                                            <?php }
                                            ?>
                                            <div class="form-group">
                                                <label class="control-label col-form-label text-left col-md-12">Select <?php echo ucwords($fkey); ?></label>
                                                <div class="input-group">
                                                    <div class="btn-group" role="group" aria-label="..." >
                                                        <span class="btn btn-success col fileinput-button" style="width:200px;">
                                                            <i class="fa fa-plus"></i>
                                                            <span>Add files...</span>
                                                            <input type="file" accept="<?php echo $mime_type; ?>"  name="<?php echo $file_name; ?>" id="<?php echo $file_name; ?>" file-model="filename" required=""/>
                                                        </span>
                                                        <input type="hidden" name="apipath_<?php echo $input_field_id; ?>" value="<?php echo $apipath; ?>"/>
                                                        <input type="hidden" name="<?php echo $upload_folder_field; ?>" value="<?php echo $upload_folder; ?>"/>
                                                        <input type="hidden" name="<?php echo $allowed_types_field; ?>" value="<?php echo $allowed_types; ?>"/>
                                                        <button type="submit" name="<?php echo $button_id; ?>" id="<?php echo $button_id; ?>" value="Upload" class="btn btn-info" class="btn btn-info" ><i class="fa fa-upload"></i> Upload File</button>
                                                    </div>
                                                    <div class="fileattribures">
                                                        <br/>
                                                    </div>
                                                    <p id="<?php echo $file_progress_id; ?>"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <?php
                                }
                            }
                        }
                        ?>


                    </div>
                    <div class="col-md-7"> 
                        <form method="post" action="">


                            <?php
                            $field_config = isset($serviceObj["field_config"]) ? $serviceObj["field_config"] : array();

                            $foreign_key = (isset($serviceObj["foreign_key"]) && $serviceObj["foreign_key"]) ? $serviceObj["foreign_key"] : array("foreign_key" => "");
                            foreach ($fieldsName as $fkey => $fvalue) {
                                if (!in_array($fvalue, $ignore_field) && !($fvalue == $pk) && !($fvalue == $foreign_key["foreign_key"])) {
                                    $recordname = $fvalue;
                                    $recordvalue = "";
                                    ?>
                                    <div class="col-md-12"> 
                                        <div class="form-group">
                                            <label class="control-label" style=""><?php echo ucwords($recordname); ?></label>
                                            <?php
                                            if (isset($field_config[$recordname])) {
                                                $field_config_list = $field_config[$recordname];
                                                if (isset($field_config_list["type"])) {

                                                    switch ($field_config_list["type"]) {
                                                        case "text":
                                                            ?>
                                                            <textarea class=" form-control" required id="<?php echo $recordname; ?>" name="<?php echo $recordname; ?>" style="height: 200px;"></textarea>
                                                            <?php
                                                            break;
                                                        case "input":
                                                            ?>
                                                            <input type="text" class=" form-control" required id="<?php echo $recordname; ?>" name="<?php echo $recordname; ?>"   />
                                                            <?php
                                                            break;

                                                        default:
                                                            ?>
                                                            <input type="text" class=" form-control" required id="<?php echo $recordname; ?>" name="<?php echo $recordname; ?>"   />
                                                        <?php
                                                    }
                                                }
                                            } else {
                                                ?>
                                                <input type="text" class=" form-control" required id="<?php echo $recordname; ?>" name="<?php echo $recordname; ?>"   />
                                                <?php
                                            }
                                            ?>

                                        </div>
                                    </div> 
                                    <?php
                                }
                            }
                            if ($foreign_key) {
                                ?>
                                <input type="hidden" class=" form-control" required id="<?php echo $foreign_key["foreign_key"]; ?>" name="<?php echo $foreign_key["foreign_key"]; ?>" value="<?php echo $parent_id; ?>"  />
                                <?php
                            }
                            foreach ($fields_config_js_object as $key => $value) {
                                ?>
                                <div class="col-md-12"> 

                                    <div class="form-group">
                                        <label class="control-label" style=""><?php echo $value["input_field_id"]; ?> File Name</label>
                                        <div class="" id="<?php echo $value["file_name_tag"]; ?>" name="">Uploaded File ID#</div>
                                         ̰
                                    </div>
                                </div> 
                                <?php
                            }
                            ?>
                            <div class="col-md-12">

                                <button name="submit" type="submit" name="submit" class="btn btn-warning" style="float: right">Submit</button>

                            </div>
                    </div>
                    <div style="clear:both"></div><br/>


                    </form>

                </div>
            </div>
            <!-- /.col -->
        </div>

        <!-- /.row -->
</section>
<!-- /.content -->

<div style="clear:both"></div>
<?php
$this->load->view('layout/footer');
?>

<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>




<script>
    $(function () {
        $('.edit_detail').hide();
        $("#edit_toggle").click(function () {
            $('.edit_detail').hide();
            if (this.checked) {
                $('.edit_detail').show();
            }
        })

        $('.edit_detail').click(function (e) {
            e.stopPropagation();
            e.preventDefault();
            $($(this).prev()).editable('toggle');
        });

        $('#gender').editable({
            source: {
                'Male': 'Male',
                'Female': 'Female'
            }
        });


        $('#profession').editable({
            source: {
                'Academic': 'Academic',
                'Medicine': 'Medicine',
                'Law': 'Law',
                'Banking': 'Banking',
                'IT': 'IT',
                'Entrepreneur': 'Entrepreneur',
                'Sales/Marketing': 'Sales/Marketing',
                'Other': 'Other',
            }
        });





    })
</script>
<?php
foreach ($fields_config_js_object as $configkey => $configvalue) {
    $this->load->view('Services/ajaxFileUpload', $configvalue);
}
?>


