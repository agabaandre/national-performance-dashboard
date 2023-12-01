



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
                <div class="panel-content">
                <?php //echo form_open_multipart(base_url('kpi/updateKpi'), array('id' => 'kpi', 'class' => 'kpi')); ?>
                    <div class="container">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop" style="margin-bottom:3px; width:150px;">
                            <i class="fa fa-plus"></i>Add KPI
                        </button>
                        <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#updateKpi" style="margin-bottom:3px; width:150px;">
                            <i class="fa fa-circle"></i>Update KPI
                        </button> -->
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
                                    <th>Frequency</th>
                                    <th>Target</th>
                                    <th>Options</th>
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
                                        <td><?php echo $element->short_name; ?></td>
                                        <td><?php echo $element->indicator_statement; ?></td>
                                            <td> 
                                                <?php if ($element->job_id == $job->job_id) {
                                                        echo $element->job;
                                                        } 
                                                ?>
                                            </td>
                                        <td><?php echo $element->data_sources; ?></td>
                                        <td><?php echo $element->numerator; ?></td>
                                        <td><?php echo $element->denominator; ?></td>
                                        <td><?php echo $element->computation_category; ?></td>
                                        <td><?php echo $period; ?></td>
                                        <td><?php echo $element->current_target; ?></td>
                                        <td>
                                            <p>
                                                <a href="<?php echo base_url();?>" data-toggle="modal" data-target="#updateKpi<?php echo $element->sid; ?>" class="btn btn-primary btn-xs"><i class="fa fa-pen"></i></a>
                                                <a href="<?php echo base_url();?>" data-toggle="modal" data-target="#updateKpi" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                                            </p>
                                        </td>
                                    </tr>




                                        <!-- Modal -->
                                        <div class="modal fade" id="updateKpi<?php echo $element->sid; ?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="staticBackdropLabel">Update KPI</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                            <?php echo form_open_multipart(base_url('kpi/updateKpi'), array('id' => 'kpi', 'class' => 'kpi')); ?>

                                            <div class="row">
                                                <div class="col-md-6">
                                                <div class="form-group row">

                                                    <input type="hidden" class="form-control" name="kpi_id[]" value="<?php echo $element->kpi_id; ?>" style="border:#000 none; width:70%;" readonly>
                                                    <input type="hidden" name="subject_area[]" value="<?php echo $element->sid; ?>">
                                                    <input type="hidden" name="is_cumulative[]" value="<?php echo $element->is_cumulative; ?>">

                                                    <label for="kpiid" class="col-sm-3 col-form-label">
                                                    Indicator Identifier(KPI ID)</label>
                                                    <div class="col-sm-9">
                                                    <input type="text" name="kpi_id" placeholder="KPI-0"  value="<?= generate_kpi_id($_SESSION['id']); ?>"
                                                        class=" form-control" required readonly>
                                                    </div>

                                                </div>
                                                <div class="form-group row">

                                                    <label for="shortname" class="col-sm-3 col-form-label">
                                                    Short Name</label>
                                                    <div class="col-sm-9">
                                                    <input type="text" name="short_name" placeholder="KPI Short Name" value="<?php echo $element->short_name; ?>" class=" form-control" required>

                                                    </div>

                                                </div>
                                                <div class="form-group row">

                                                    <label for="indiactor_statement" class="col-sm-3 col-form-label">
                                                    Indicator Statement</label>
                                                    <div class="col-sm-9">
                                                    <textarea name="indicator_statement" class="form-control" id=""><?php echo $element->indicator_statement; ?></textarea required>   
                                                                    </div>
                                                                
                                                                </div>
                                                                <div class="form-group row">
                                                                
                                                                <label for="frequency" class="col-sm-3 col-form-label">
                                                                    Frequency</label>
                                                                    <div class="col-sm-9">
                                                                <select name="frequency" class="form-control codeigniterselect">
                                                                    <option value="Quarterly" selected>Quarterly</option>
                                                                    </select>  
                                                                    </div>
                                                                
                                                                </div>
                                                            
                                                                <div class="form-group row">
                                                                
                                                                <label for="cumulative" class="col-sm-3 col-form-label">
                                                                    Indicator Type</label>
                                                                    <div class="col-sm-9">
                                                                <select name="indicator_type_id" class="form-control codeigniterselect">
                                                                <option value="1">Output</option>
                                                                <option value="2">Outcome</option>
                                                    
                                                                    </select>  
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                
                                                                <label for="cumulative" class="col-sm-3 col-form-label">
                                                                    Computation Category</label>
                                                                    <div class="col-sm-9">
                                                                <select name="computation_category" class="form-control codeigniterselect" required>
                                                                <option value="Ratio">Ratio</option>
                                                                <option value="Value">Value</option>
                                                    
                                                                    </select>  
                                                                    </div>
                                                                </div>
                                                                
                                                    <div class="form-group row">
                                                                
                                                        <label for="description" class="col-sm-3 col-form-label">
                                                        Numerator</label>
                                                        <div class="col-sm-9">
                                                        <textarea name="numerator" col="6" rows="3" class="form-control" id="" required><?php echo $element->numerator; ?></textarea>

                                                    </div>

                                                </div>
                                                <div class="form-group row">

                                                    <label for="description" class="col-sm-3 col-form-label">
                                                    Denominator</label>
                                                    <div class="col-sm-9">
                                                    <textarea name="denominator" col="6" rows="3" class="form-control" id=""><?php echo $element->denominator; ?></textarea>

                                                    </div>

                                                </div>

                                                </div>
                                                <!--End divider -->




                                                <div class="col-md-6">

                                                <div class="form-group row">

                                                    <label for="description" class="col-sm-3 col-form-label">
                                                    Current Target</label>
                                                    <div class="col-sm-9">
                                                    <input type="number" name="current_target" class="form-control" value="<?php echo $element->current_target; ?>" id="">

                                                    </div>

                                                </div>

                                                <div class="form-group row">

                                                    <label for="description" class="col-sm-3 col-form-label">
                                                    Indicator description</label>
                                                    <div class="col-sm-9">
                                                    <textarea name="description" col="10" rows="5" class="form-control" id="" required><?php echo $element->description; ?></textarea>

                                                    </div>

                                                </div>

                                                <div class="form-group row">

                                                    <label for="description" class="col-sm-3 col-form-label">
                                                    Data Sources</label>
                                                    <div class="col-sm-9">
                                                    <textarea name="data_sources" class="form-control" id="" required><?php echo $element->data_sources; ?></textarea>

                                                    </div>


                                                </div>
                                                <div class="form-group row">
                                                    <label for="subject" class="col-sm-3 col-form-label">
                                                    Focus Area</label>
                                                    <div class="col-sm-9">
                                                    <select name="subject_area" class="form-control codeigniterselect"
                                                        onchange="get_catgories($(this).val())" required>
                                                        <?php $elements = Modules::run('Kpi/subjectData');
                                                        foreach ($elements as $element): ?>
                                                        <option value="<?php echo $element->id ?>">
                                                            <?php echo $element->name ?>
                                                        </option>
                                                        <?php endforeach; ?>

                                                    </select>
                                                    </div>
                                                </div>

                                                <div class="form-group row">

                                                    <label for="subject" class="col-sm-3 col-form-label">
                                                    Output </label>
                                                    <div class="col-sm-9">
                                                    <select name="category_two_id" class="form-control" id="subcat" required>
                                                    </select>
                                                    </div>

                                                </div>

                                                <div class="form-group row">

                                                    <label for="subject" class="col-sm-3 col-form-label">
                                                    Job/Function</label>
                                                    <div class="col-sm-9">
                                                    <select name="job_id" class="form-control select2 codeigniterselect">
                                                        <option value="">SELECT JOB</option>
                                                        <?php $elements = $this->db->get('job')->result();
                                                        foreach ($elements as $element): ?>
                                                        <option value="<?php echo $element->job_id ?>">
                                                            <?php echo $element->job ?>
                                                        </option>
                                                        <?php endforeach; ?>

                                                    </select>
                                                    </div>


                                                </div>

                                                </div>
                                                <!---End sub2-->
                                                <div class="form-group text-right">
                                                    <button type="reset" class="btn btn-primary w-md m-b-5">Reset</button>
                                                    <button type="submit" class="btn btn-success w-md m-b-5">Save</button>
                                                </div>
                                                </form>
                                            </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                                            </div>
                                            </div>
                                        </div>
                                        </div>
                               
                               
                               
                               
                               

























                               
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











<?php $this->load->view('add_kpi'); ?>


<!-- From old -- Hakim Comment -->
<!-- <script>
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
</script> -->