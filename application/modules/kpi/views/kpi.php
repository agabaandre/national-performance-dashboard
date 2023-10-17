<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo (!empty($title) ? $title : null) ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-content">
                                <form action="<?php echo base_url('kpi/updateKpi'); ?>" method="post" id="kpi" class="kpi">
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#staticBackdrop" style="margin-bottom:3px; width:150px;">
                                        <i class="fa fa-plus"></i>Add KPI
                                    </button>
                                    <button type="submit" class="btn btn-success" style="margin-bottom:3px; width:150px;">
                                        <i class="fa fa-circle"></i>Update KPI
                                    </button>

                                    <table id="kpiTable" class="table table-responsive table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>KPI ID</th>
                                                <th>Subject Area</th>
                                                <th>Short Name</th>
                                                <th>Indicator Statement</th>
                                                <th>Job</th>
                                                <th>Data Sources</th>
                                                <th>Numerator</th>
                                                <th>Denominator</th>
                                                <th>Frequency</th>
                                                <th>Target</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            $elements = Modules::run('Kpi/kpiData');
                                            foreach ($elements as $element) : ?>
                                                <tr class="table-row tbrow content strow">
                                                    <!-- ... (rest of your table rows) ... -->
                                                </tr>
                                            <?php
                                                $i++;
                                            endforeach;

                                            if (count($elements) == 0) {
                                                echo "<tr><td colspan='8'><center><h3 class='text-warning'>Please Add Indicators</h3></center></td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('add_kpi'); ?>

<script>
    $(document).ready(function() {
        $('#kpiTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            responsive: true,
            displayLength: 25,
            lengthChange: true
        });
    });
</script>