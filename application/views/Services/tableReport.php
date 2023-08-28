<?php
$this->load->view('layout/header');
$this->load->view('layout/topmenu');
?>
<style>
    .shortImageTableData{
        height:100px;
        weight:100px;
    }
    #tableData{
        font-size: 12px;
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
                <h3 class="panel-title"><?php echo $title;?></h3>
            </div>
            <div class="panel-body overflow-auto">
                <table id="tableData" class="table table-bordered ">
                    <thead>
                        <tr>
                            <?php
                            foreach ($fieldsName as $fkey => $fvalue) {
                                echo "<th>$fvalue</th>";
                            }
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
    $(function () {
        $('#tableData').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: "<?php echo site_url("Api/listApiData/$apipath/yes") ?>",
                type: 'GET'
            },
            dom: 'Blfrtip',
            buttons: [
                'excel', 'pdf', 'csv', 'print'
            ],
            "columns": [
<?php
foreach ($fieldsName as $fkey => $fvalue) {
    echo '{"data": "' . $fvalue . '"},';
}
?>
            ]
        });
    });
</script>