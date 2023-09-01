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

                        <div class="thumbnail">
                            <form method="post" id="upload_form" align="center" enctype="multipart/form-data">  

                                <img src="<?php echo base_url(); ?>assets/default2.png" style="width:50%">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="control-label col-form-label">Set Image</label>
                                        <div class="input-group">



                                            <div class="btn-group" role="group" aria-label="..." >
                                                <span class="btn btn-success col fileinput-button" style="width:200px;">
                                                    <i class="fa fa-plus"></i>
                                                    <span>Add files...</span>
                                                    <input type="file" name="image_file" id="image_file" file-model="filename" required=""/>
                                                </span>
                                                <button type="submit" name="upload" id="upload" value="Upload" class="btn btn-info" class="btn btn-info" ><i class="fa fa-upload"></i> Upload Image</button>

                                            </div>
                                            <br/>

                                        </div>
                                        <p id="image_upload_proress"></p>
                                        <p>W:600px X H:800px</p>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                    <div class="col-md-7"> 
                        <form method="post" action="">


                            <?php
                            $foreign_key = isset($serviceObj["foreign_key"]) ? $serviceObj["foreign_key"] : array("foreign_key" => "");
                            foreach ($fieldsName as $fkey => $fvalue) {
                                if (!in_array($fvalue, $ignore_field) && !($fvalue == $pk) && !($fvalue == $foreign_key["foreign_key"])) {
                                    $recordname = $fvalue;
                                    $recordvalue = "";
                                    ?>
                                    <div class="col-md-12"> 
                                        <div class="form-group">
                                            <label class="control-label" style=""><?php echo ucwords($recordname); ?></label>
                                            <input type="text" class=" form-control" required id="<?php echo $recordname; ?>" name="<?php echo $recordname; ?>"   />

                                        </div>
                                    </div> 
                                    <?php
                                }
                            }
                            if ($foreign_key) {
                                ?>
                                <input type="hidden" class=" form-control" required id="<?php echo $foreign_key["foreign_key"]; ?>" name="<?php echo $foreign_key["foreign_key"]; ?>" value="<?php echo $parent_id;?>"  />
                            <?php } ?>
                            <div class="col-md-12"> 
                                <div class="form-group">
                                    <label class="control-label" style="">File Name</label>
                                    <div class="" id="filenamedata" name=""></div>

                                </div>
                            </div> 
                    </div>
                    <div style="clear:both"></div><br/>
                    <div class="col-md-12">

                        <button name="submit" type="submit" name="submit" class="btn btn-info" style="float: right">Submit</button>

                    </div>

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

<script>
    $(document).ready(function () {
        $('#<?php echo $imageField; ?>').parent().hide();
        $('#upload_form').on('submit', function (e) {
            e.preventDefault();
            $("#upload").prop("disabled", true);
            if ($('#image_file').val() == '')
            {
                alert("Please Select the File");
            } else
            {
                $.ajax({
                    xhr: function () {
                        var xhr = new window.XMLHttpRequest();

                        xhr.upload.addEventListener("progress", function (evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                percentComplete = parseInt(percentComplete * 100);
                                $("#image_upload_proress").html(percentComplete);

                                if (percentComplete === 100) {
                                    $("#upload").prop("disabled", false);
                                    $("#image_upload_proress").html("");
                                }

                            }
                        }, false);

                        return xhr;
                    },
                    url: "<?php echo site_url("Api/ajax_upload_image/$apipath"); ?>",
                    method: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data)
                    {
                        console.log(data);
<?php
if ($imageField) {
    ?>
                            $('#<?php echo $imageField; ?>').val(data.file_name);

                            $('#filenamedata').html(data.file_name);
    <?php
}
?>

                    }
                });
            }
        });
    });
</script> 