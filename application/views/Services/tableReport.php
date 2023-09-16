<?php
$this->load->view('layout/header');
$this->load->view('layout/topmenu');
?>
<style>
.shortImageTableData {
    height: 70px;
    width: 100px;
    background-position: center;
    background-size: cover;
}

.shortImageTableData:parent(th) {
    width: 100px;
}

#tableData {
    font-size: 12px;
}

.text-default {
    color: white;
}
</style>
<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
<link href="<?php echo base_url(); ?>assets/plugins/DataTables/css/data-table.css" rel="stylesheet" />
<!-- ================== END PAGE LEVEL STYLE ================== -->
<!-- Main content -->
<section class="content">
    <div class="">

        <div class="panel panel-danger">
            <div class="panel-heading">
                <div class="panel-title text-default">
                    <h3 class="text-default"><?php echo ucwords($title); ?></h3>
                    <div class="block-display row">
                        <?php
                        if ($parent_link) {
                            ?>
                        <div class="col-md-3 pull-right" style="width: fit-content;">
                            <a href="<?php echo $parent_link ?>" class="btn btn-sm  btn-success"><i
                                    class="fa fa-arrow-left"></i>  Back</a>
                        </div>
                        <?php
                        }
                        ?>
                        <?php
                        if ($writable) {
                            ?>
                        <div class="col-md-3 pull-left" style="width: fit-content;">
                            <a href="<?php echo $writelink ?>" class="btn btn-sm  btn-success"><i
                                    class="fa fa-plus"></i>  Add New <?php echo ucwords($title); ?></a>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="panel-body overflow-auto">
                <table id="tableData" class="table table-bordered ">
                    <thead>
                        <tr>
                            <?php
                            foreach ($fieldsName as $fkey => $fvalue) {
                                if (!in_array($fvalue, $ignore_field)) {
                                    echo "<th>" . ucwords($fvalue) . "</th>";
                                }
                            }
                            if ($has_link) {
                                echo "<th>LINK</th>";
                            }

                            echo "<th>OPERATION</th>";
                            ?>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>


    </div>
</section>
<!-- end col-6 -->

<script src="<?php echo base_url(); ?>assets/plugins/DataTables/js/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/js/table-manage-default.demo.min.js"></script>
<?php
$this->load->view('layout/footer');
?>
<script>
$(function() {
    var dataTableObj = $('#tableData').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "<?php echo site_url("Api/dataTableApi/$apipath/$parent_id") ?>",
            type: 'GET'
        },
        dom: 'Blfrtip',
        buttons: [
            'excel', 'pdf', 'csv', 'print'
        ],
        "order": [
            [0, 'desc']
        ],
        "drawCallback": function(settings) {
            $('#tableData tbody').on('click', 'button.deleterow', function() {
                confirmation($(this).attr("href"), this, dataTableObj);

            });
        },
        "columns": [
            <?php
foreach ($fieldsName as $fkey => $fvalue) {
    if (!in_array($fvalue, $ignore_field)) {
        echo '{"data": "' . $fvalue . '"},';
    }
}
if ($has_link) {
    echo '{"data": "link"},';
}
echo '{"data": "operations"},';
?>
        ]
    });







});

function confirmation(executableLink, deleteObject, dataTableObj) {
    var result = confirm("Are you sure to delete?");
    if (result) {
        $.get(executableLink).then(function() {
            var row = dataTableObj.row($(deleteObject).parents('tr')).remove().draw(false);
        });
    }
}
</script>