
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

                                     <input type="hidden" class="form-control" name="kpi_id[]" value="<?php echo $element->kpi_id; ?>"
                                        style="border:#000 none; width:70%;" readonly>
                                        
                                    <table id="kpiTable" class="table table-responsive table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Subject Area</th>
                                                <th>Short Name</th>
                                                <th>Indicator Statement</th>
                                                <th>Job</th>
                                                <th>Data Sources</th>
                                                <th>Numerator</th>
                                                <th>Denominator</th>
                                                 <th>Category</th>
                                                <th>Frequency</th>
                                                <th>Target</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            $elements = Modules::run('Kpi/kpiData');
                                            
                                            foreach ($elements as $element) :
                                               // dd($element);
                                            ?>
                                                <tr class="table-row tbrow content strow">
                                                    <td><?php echo $i ?></td>
                                                    <td><?php echo  $element->name; ?></td>
                                                    <input type="hidden" name="subject_area[]" value="<?php echo $element->sid; ?>">
                                                    <input type="hidden" name="is_cumulative[]" value="<?php echo $element->is_cumulative; ?>">
                                                    <td style="width:20%;"><textarea name="short_name[]" rows=4 class="form-control" style="border:#000  none; width:90%;"><?php echo $element->short_name; ?></textarea></td>
                                                    <td style="width:20%;"><textarea name="indicator_statement[]" rows=4 class="form-control" style="border:#000  none; width:90%;"><?php echo $element->indicator_statement; ?></textarea></td>
                                                    <td style="width:35%;">

                                                        <select name="job_id[]" class="form-control codeigniterselect">
                                                            <?php $jobs = $this->db->get('job')->result();
                                                            foreach ($jobs as $job) : ?>
                                                                <option value="<?php echo $job->job_id ?>" <?php if ($element->job_id == $job->job_id) {
                                                                    echo "selected";
                                                                     } ?>><?php echo $job->job; ?></option>
                                                            <?php endforeach; ?>

                                                        </select>

                                                    </td>
                                                    <td style="width:15%;"><textarea name="data_sources[]" rows=4 class="form-control" style="border:#000  none; width:80%;"><?php echo $element->data_sources; ?></textarea></td>
                                                    <td style="width:25%;"><textarea name="numerator[]" rows=5 class="form-control" style="border:#000  none; width:82%;"><?php echo $element->numerator; ?></textarea></td>
                                                    <td style="width:25%;"><textarea name="denominator[]" rows=5 class="form-control" style="border:#000  none; width:82%;"><?php echo $element->denominator; ?></textarea></td>
                                                    <td>
                                                        <select name="computation_category[]" class="form-control codeigniterselect">
                                                            <?php $cps = array("Ratio","Value");


                                                        foreach ($cps as $cp) :
                                                            ?>
                                                                <option value="<?php echo $cp; ?>" <?php if ($cp == $element->computation_category) {
                                                                                                            echo "selected";
                                                                                                        } ?>><?php echo $cp; ?></option>
                                                            <?php
                                                         endforeach; ?>
                                                        </select>
                                                    </td>

                                                    <td>
                                                        <select name="frequency[]" class="form-control codeigniterselect">
                                                            <?php $periods = array("Quarterly", "Monthly", "Weekly", "Annualy");


                                                        foreach ($periods as $period) :
                                                            ?>
                                                                <option value="<?php echo $period; ?>" <?php if ($period == $element->frequency) {
                                                                                                            echo "selected";
                                                                                                        } ?>><?php echo $period; ?></option>
                                                            <?php
                                                         endforeach; ?>
                                                        </select>
                                                    </td>
                                                    <td style="width:10%;"><input type="text" class="form-control" name="current_target[]" value="<?php echo $element->current_target; ?>" style="border:#000 none; width:70%;"></td>

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