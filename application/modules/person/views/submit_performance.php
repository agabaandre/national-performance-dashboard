<style>
    input text {
        border: 1px solid #000;
        border-radius: 4px;
    }
</style>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4>
                        <?php echo (!empty($title) ? $title : null) ?>
                    </h4>
                    <p style="float:right; margin-right: 4px;">
                        <a href="<?php echo base_url(); ?>person/performance" class="btn btn-sucess">
                            My Performance
                        </a>
                    <p>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">





                            <div class="card-content">
                                <div class="col-md-12">
                                    <h5 style="text-align:left; padding-bottom:1em; text-weight:bold;">Staff KPI Data
                                        Capture Form
                                    </h5>
                                    <?php echo form_open_multipart(base_url('person/index'), array('id' => 'person', 'class' => 'person', 'method'=>'get')); ?>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="financial_year">Financial Year:</label>
                                            <select class="form-control" name="financial_year" required>
                                                <option value="" >Select Finanacial_year</option>
                                                <?php

                                                $startdate = "2022";
                                                $enddate = intval(date('Y') + 1);
                                                $years = range($startdate, $enddate);
                                            
                                                foreach ($years as $year) {
                                                    if ((substr($year, 0) + 1) <= substr($enddate, 0)) { ?>



                                                        <?php $fy = $year . '-' . (substr($year, 0) + 1); ?>
                                                        <option value="<?php echo $fy ?>" <?php if ($this->input->get('financial_year') == $fy) {
                                                               echo "selected";
                                                           } ?>>
                                                            <?php echo $fy; ?>
                                                        </option>

                                                        <?php
                                                    }
                                                } ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="period">Period:</label>
                                            <?php $quaters =array("Q1","Q2","Q3","Q4");?>
                                            <select class="form-control" name="period" required>
                                                <option value="" >Select Period</option>
                                                 <?php foreach ($quaters as $quater) { ?>
                                                
                                                <option value="<?php echo $quater; ?>" <?php if ($this->input->get('period') == $quater) {
                                                              echo "selected";
                                                          } ?>><?php echo $quater; ?>
                                                </option>
                                                <?php }?>
                                    
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                        <button type="submit" name="preview_data" class="btn btn-primary">Preview Data</button>
                                     
                                         <button type="submit" name="save_data" class="btn btn-primary">Save Data</button>
                                        </div>

                                    </div>

                                    <?php 
                                    //dd($show);
                                    if ($show==1){?>
                                    <table class="table table-responsive">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Indicator</th>
                                                <th>Numerator</th>
                                                <th>Denominator</th>
                                                <th>Comments</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            foreach ($kpidatas as $kpi):

                                                //dd($kpidatas);

                                                ?>
                                                <tr>
                                                    <td>
                                                        <?= $i++ ?>

                                                    </td>

                                                    <td>
                                                        <?= $kpi->short_name ?>

                                                    </td>
                                                  
                                                    <td>
                                                        <div class="form-group">
                                                            <label>
                                                                <?= $kpi->numerator ?>
                                                            </label>
                                                            <input type="number" class="form-control" id="numerator"
                                                                name="numerator[<?= $kpi->kpi_id ?>][]" value="<?php echo @data_value($this->session->userdata('ihris_pid'), $kpi->kpi_id, $this->input->get('financial_year'), $this->input->get('period'))->numerator;?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                    <?php if($kpi->computation_category=='Ratio'){ ?>
                                                        <div class="form-group">
                                                            <label>
                                                                <?= $kpi->denominator ?>
                                                            </label>
                                                            <input type="number" class="form-control" id="denominator"
                                                                name="denominator[<?= $kpi->kpi_id ?>][]" value="<?php echo @data_value($this->session->userdata('ihris_pid'), $kpi->kpi_id, $this->input->get('financial_year'), $this->input->get('period'))->denominator; ?>">
                                                        </div>
                                                        <?php }?>
                                                       
                                                    </td>

                                                    <td>
                                                        <label>Comment on the values</label>
                                                        <input type="text" class="form-control" id="comment"
                                                            name="comment[<?= $kpi->kpi_id ?>][]" value="<?php echo @data_value($this->session->userdata('ihris_pid'), $kpi->kpi_id, $this->input->get('financial_year'), $this->input->get('period'))->comment; ?>">
                                                    </td>
                                                      <input type="hidden" class="form-control" id="comment"
                                                            name="data_target[<?= $kpi->kpi_id ?>][]" value="<?php echo @data_value($this->session->userdata('ihris_pid'), $kpi->kpi_id, $this->input->get('financial_year'), $this->input->get('period'))->data_target; ?>">

                                                


                                                        </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <?php }?>

                                    </form>

                                </div>


                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // $('#person').submit(function(e) {
    //     e.preventDefault(); // Prevent the default form submission
        
    //     // Serialize the form data
    //     var formData = $('#person').serialize();
        
    //     // Send an AJAX request to the server
    //     $.ajax({
    //         type: 'POST', // Use the appropriate HTTP method
    //         url: '<?php echo base_url('person/save'); ?>', // Set the URL to your controller method
    //             data: formData, // Pass the serialized form data
    //             success: function (response) {
    //                 // Handle the response from the server (e.g., show a success message)
    //                $.notify("Scheduled Saved", "success");
    //             },
    //             error: function (error) {
    //                 // Handle any errors (e.g., show an error message)
    //                $.notify("Failed to save", "warning");
    //             }
    //         } );
    //         console.log(formData);

        });
    });
</script>