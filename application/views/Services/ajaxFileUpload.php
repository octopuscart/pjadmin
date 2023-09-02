<script>
    $(document).ready(function () {
        $('#<?php echo $input_field_id; ?>').parent().hide();
        $('#<?php echo $input_field_id; ?>').prop("required", false);
        $('#<?php echo $form_id; ?>').on('submit', function (e) {
            e.preventDefault();
            $("#<?php echo $form_id; ?>").prop("disabled", true);
            if ($('#<?php echo $file_name; ?>').val() == '')
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
                                $("#<?php echo $progress_bar_id; ?>").html(percentComplete);

                                if (percentComplete === 100) {
                                    $("#<?php echo $button_id; ?>").prop("disabled", false);
                                    $("#<?php echo $progress_bar_id; ?>").html("");
                                }

                            }
                        }, false);

                        return xhr;
                    },
                    url: "<?php echo site_url("Api/ajax_upload/$input_field_id"); ?>",
                    method: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data)
                    {
                        console.log(data);
                        $('#<?php echo $input_field_id; ?>').val(data.file_name);
                        $('#<?php echo $file_name_tag; ?>').html(data.file_name);


                    }
                });
            }
        });
    });
</script> 