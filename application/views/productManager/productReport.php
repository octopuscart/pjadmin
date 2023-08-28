<?php
$this->load->view('layout/header');
$this->load->view('layout/topmenu');
?>
<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
<link href="<?php echo base_url(); ?>assets/plugins/DataTables/css/data-table.css" rel="stylesheet" />
<!-- ================== END PAGE LEVEL STYLE ================== -->
<!-- Main content -->
<section class="content">
    <div class="">

        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title">Product Reports</h3>
            </div>
            <div class="panel-body">
                <table id="tableData" class="table table-bordered ">
                    <thead>
                        <tr>
                            <th style="width: 20px;">S.N.</th>
                            <th style="width:50px;">Image</th>
                            <th style="width:150px;">Category</th>
                            <th style="width:50px;">SKU</th>
                            <th style="width:100px;">Title</th>
                            <th style="width:100px;">Color</th>
                            <th style="width:200px;">Short Description</th>
                            <th >Items Prices</th>
                            <th style="width: 75px;">Edit</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>


    </div>
</section>
<!-- end col-6 -->
</div>




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
                                    url: "<?php echo site_url("ProductManager/productReportApi/" . $condition) ?>",
                                    type: 'GET'
                                },
                                dom: 'Blfrtip',
                                buttons: [
                                    'excel', 'pdf', 'csv', 'print'
                                ],
                                "columns": [
                                    {"data": "s_n"},
                                    {"data": "image"},
                                    {"data": "category"},
                                    {"data": "sku"},
                                    {"data": "title"},
                                    {"data": "color"},
                                    {"data": 'short_description'},
                                    {"data": "items_prices"},
                                    {"data": "edit"}]
                            })
                        });

</script>