


<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                <?php echo (!empty($title)?$title:null) ?>
                </h2>
                <div class="panel-toolbar">
                    <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
                    <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
                    <!-- <button class="btn btn-panel" data-action="panel-close" data-toggle="tooltip" data-offset="0,10" data-original-title="Close"></button> -->
                </div>
            </div>
            <div class="panel-container show">
                
             <a href="<?php echo base_url() ?>person/all_users" class="btn btn-primary">Automatically Render Accounts</a>
                <div class="panel-content">

                    <!-- datatable start -->
                    <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                        <thead>
                            <tr>
                                <th><?php echo display('sl_no') ?></th>
                                <th><?php echo display('image') ?></th>
                                <th><?php echo display('username') ?></th>
                                <th><?php echo display('email') ?></th>
                                <th><?php echo display('last_login') ?></th>
                                <th>User type</th>
                                <th><?php echo display('ip_address') ?></th>
                                <th><?php echo display('status') ?></th>
                                <th><?php echo display('action') ?></th> 
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded via AJAX -->
                        </tbody>
                    </table>
                    <!-- datatable end -->
                    
                    <script>
                    $(document).ready(function() {
                        // Destroy existing DataTable if it exists
                        if ($.fn.DataTable.isDataTable('#dt-basic-example')) {
                            $('#dt-basic-example').DataTable().destroy();
                        }
                        
                        // Initialize DataTable
                        var table = $('#dt-basic-example').DataTable({
                            "processing": true,
                            "serverSide": true,
                            "ajax": {
                                "url": "<?php echo base_url('users/datatables'); ?>",
                                "type": "POST",
                                "data": function(d) {
                                    d.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>";
                                }
                            },
                            "pageLength": 20,
                            "lengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "All"]],
                            "columns": [
                                { "data": 0, "orderable": false },
                                { "data": 1, "orderable": false },
                                { "data": 2 },
                                { "data": 3 },
                                { "data": 4 },
                                { "data": 5 },
                                { "data": 6 },
                                { "data": 7 },
                                { "data": 8, "orderable": false }
                            ],
                            "order": [[2, "asc"]],
                            "language": {
                                "processing": "Loading users...",
                                "emptyTable": "No users found",
                                "zeroRecords": "No matching users found"
                            },
                            "responsive": true,
                            "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                                   "<'row'<'col-sm-12'tr>>" +
                                   "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
                        });
                    });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>