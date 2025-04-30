<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo (!empty($title) ? $title : null) ?>
                </h2>
                <div class="panel-toolbar">
                    <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10"
                        data-original-title="Collapse"></button>
                    <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip"
                        data-offset="0,10" data-original-title="Fullscreen"></button>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <?php echo form_open_multipart(base_url('kpi/updateKpi'), array('id' => 'kpi', 'class' => 'kpi')); ?>
                    <div class="container">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop"
                            style="margin-bottom:3px; width:150px;">
                            <i class="fa fa-plus"></i>Add KPI
                        </button>
                        <button type="submit" class="btn btn-primary" style="margin-bottom:3px; width:150px;">
                            <i class="fa fa-circle"></i>Update KPI
                        </button>
                        <br>
                        <br>
                    </div>
                    <!-- datatable start -->
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
                                <th>Target</th>
                                <th>Frequency</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            $elements = Modules::run('Kpi/kpiData');

                            foreach ($elements as $element):
                                ?>
                                <tr class="table-row tbrow content strow">
                                    <input type="hidden" class="form-control" name="kpi_id[]"
                                        value="<?php echo $element->kpi_id; ?>" style="border:#000 none; width:70%;"
                                        readonly>
                                    <td><?php echo $i ?></td>
                                    <td><?php echo $element->name; ?></td>
                                    <input type="hidden" name="subject_area[]" value="<?php echo $element->sid; ?>">
                                    <input type="hidden" name="is_cumulative[]"
                                        value="<?php echo $element->is_cumulative; ?>">
                                    <td style="width:20%;"><textarea name="short_name[]" rows=4 class="form-control"
                                            style="border:#000 none; width:90%;"><?php echo $element->short_name; ?></textarea>
                                    </td>
                                    <td style="width:20%;"><textarea name="indicator_statement[]" rows=4
                                            class="form-control"
                                            style="border:#000 none; width:90%;"><?php echo $element->indicator_statement; ?></textarea>
                                    </td>
                                    <td style="width:35%;">
                                        <select name="job_id[]" class="form-control codeigniterselect">
                                            <?php $jobs = $this->db->get('kpi_job_category')->result();
                                            foreach ($jobs as $job): ?>
                                                <option value="<?php echo $job->job_id ?>" <?php if ($element->job_id == $job->job_id) {
                                                       echo "selected";
                                                   } ?>><?php echo $job->job; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td style="width:15%;"><textarea name="data_sources[]" rows=4 class="form-control"
                                            style="border:#000 none; width:80%;"><?php echo $element->data_sources; ?></textarea>
                                    </td>
                                    <td style="width:25%;"><textarea name="numerator[]" rows=5 class="form-control"
                                            style="border:#000 none; width:82%;"><?php echo $element->numerator; ?></textarea>
                                    </td>
                                    <td style="width:25%;"><textarea name="denominator[]" rows=5 class="form-control"
                                            style="border:#000 none; width:82%;"><?php echo $element->denominator; ?></textarea>
                                    </td>
                                    <td>
                                        <select name="computation_category[]" class="form-control codeigniterselect">
                                            <?php $cps = array("Ratio", "Value");
                                            foreach ($cps as $cp): ?>
                                                <option value="<?php echo $cp; ?>" <?php if ($cp == $element->computation_category) {
                                                       echo "selected";
                                                   } ?>><?php echo $cp; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td style="width:25%;"><textarea name="current_target[]" rows=5 class="form-control"
                                            style="border:#000 none; width:82%;"><?php echo $element->current_target; ?></textarea>
                                    </td>
                                    <td>
                                        <select name="frequency[]" class="form-control codeigniterselect">
                                            <?php $periods = array("Quarterly", "Monthly", "Weekly", "Annually");
                                            foreach ($periods as $period): ?>
                                                <option value="<?php echo $period; ?>" <?php if ($period == $element->frequency) {
                                                       echo "selected";
                                                   } ?>><?php echo $period; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                       <a href="<?php echo base_url()?>/kpi/delete/<?=$element->id?>" class="btn btn-danger"> Delete KPI</a>
                                    </td>
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
                    <!-- datatable end -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="updateKpi" data-backdrop="static" data-keyboard="false"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="staticBackdropLabel">Update KPI</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo form_open_multipart(base_url('kpi/addkpi'), array('id' => 'kpi', 'class' => 'kpi')); ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="kpiid" class="col-sm-3 col-form-label">Indicator Identifier(KPI ID)</label>
                            <div class="col-sm-9">
                                <input type="text" name="kpi_id" placeholder="KPI-0"
                                    value="<?= generate_kpi_id($_SESSION['id']); ?>" class=" form-control" required
                                    readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="shortname" class="col-sm-3 col-form-label">Short Name</label>
                            <div class="col-sm-9">
                                <input type="text" name="short_name" placeholder="KPI Short Name" class=" form-control"
                                    required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="indicator_statement" class="col-sm-3 col-form-label">Indicator Statement</label>
                            <div class="col-sm-9">
                                <textarea name="indicator_statement" class="form-control" required></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="frequency" class="col-sm-3 col-form-label">Frequency</label>
                            <div class="col-sm-9">
                                <select name="frequency" class="form-control codeigniterselect">
                                    <option value="Quarterly" selected>Quarterly</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="cumulative" class="col-sm-3 col-form-label">Indicator Type</label>
                            <div class="col-sm-9">
                                <select name="indicator_type_id" class="form-control codeigniterselect">
                                    <option value="1">Output</option>
                                    <option value="2">Outcome</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="cumulative" class="col-sm-3 col-form-label">Computation Category</label>
                            <div class="col-sm-9">
                                <select name="computation_category" class="form-control codeigniterselect" required>
                                    <option value="Ratio">Ratio</option>
                                    <option value="Value">Value</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="description" class="col-sm-3 col-form-label">Numerator</label>
                            <div class="col-sm-9">
                                <textarea name="numerator" col="6" rows="3" class="form-control" required></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="description" class="col-sm-3 col-form-label">Denominator</label>
                            <div class="col-sm-9">
                                <textarea name="denominator" col="6" rows="3" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="description" class="col-sm-3 col-form-label">Current Target</label>
                            <div class="col-sm-9">
                                <input type="number" name="current_target" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="description" class="col-sm-3 col-form-label">Indicator description</label>
                            <div class="col-sm-9">
                                <textarea name="description" col="10" rows="5" class="form-control" required></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="description" class="col-sm-3 col-form-label">Data Sources</label>
                            <div class="col-sm-9">
                                <textarea name="data_sources" class="form-control" required></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="subject" class="col-sm-3 col-form-label">Focus Area</label>
                            <div class="col-sm-9">
                                <select name="subject_area" class="form-control codeigniterselect"
                                    onchange="get_categories($(this).val())" required>
                                    <?php $elements = Modules::run('Kpi/subjectData');
                                    foreach ($elements as $element): ?>
                                        <option value="<?php echo $element->id ?>"><?php echo $element->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="subject" class="col-sm-3 col-form-label">Output</label>
                            <div class="col-sm-9">
                                <select name="category_two_id" class="form-control" id="subcat" required></select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="subject" class="col-sm-3 col-form-label">Job/Function</label>
                            <div class="col-sm-9">
                                <select name="job_id" class="form-control codeigniterselect">
                                    <option value="">SELECT JOB</option>
                                    <?php $elements = $this->db->get('kpi_job_category')->result();
                                    foreach ($elements as $element): ?>
                                        <option value="<?php echo $element->job_id ?>"><?php echo $element->job ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <button type="reset" class="btn btn-primary w-md m-b-5">Reset</button>
                        <button type="submit" class="btn btn-success w-md m-b-5">Save</button>
                    </div>
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
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