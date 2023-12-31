<style>
    input text {
        border: 1px solid #000;
        border-radius: 4px;
    }
</style>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div id="panel-13" class="panel">
            <div class="panel-hdr border-faded border-top-0 border-right-0 border-left-0 shadow-0">
                <h2>
                    <?php echo (!empty($title) ? $title : null) ?>
                </h2>
                <div class="panel-toolbar pr-3">
                    <ul class="nav nav-pills border-bottom-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#pill_default-1" role="tab">My Performance</a>
                        </li>
                    </ul>
                </div>
            </div>
        <div class="panel-container show">
        <div class="panel-content">
            
                        <div class="col-md-12">
                            <h5 style="text-align:left; padding-bottom:1em; text-weight:bold;">Staff KPI Data
                                Capture Form
                            </h5>
                            <?php echo form_open_multipart(base_url('person/index'), array('id' => 'preview', 'class' => 'preview', 'method' => 'get')); ?>
                                <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="financial_year">Financial Year:(*)</label>
                                    <select class="form-control select2" name="financial_year" required>
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

                                <div class="form-group col-md-3">
                                    <label for="period">Period:(*)</label>
                                    <?php $quaters =array("Q1","Q2","Q3","Q4");?>
                                    <select class="form-control select2" name="period" required>
                                        <option value="" >Select Period</option>
                                            <?php foreach ($quaters as $quater) { ?>
                                        
                                        <option value="<?php echo $quater; ?>" <?php if ($this->input->get('period') == $quater) {
                                                        echo "selected";
                                                    } ?>><?php echo $quater; ?>
                                        </option>
                                        <?php }?>
                            
                                    </select>
                                </div>
                                    <div class="form-group col-md-3">
                                    
                                    <label for="focus_areas">Focus Areas:</label>
                                        <select class="form-control select2" name="focus_area" >
                                            <option value="">Select Focus Area</option>
                                            <?php foreach ($focus_areas as $list) { 
            
                                                ?>
                                                <option value="<?php echo $list->id; ?>" <?php if ($this->input->get('focus_area') == $list->id) {
                                                        echo "selected";
                                                    } ?>><?php echo $list->subject_area; ?>
                                                </option>
                                            <?php } ?>
                                    
                                        </select>
                                    </div>
                                <div class="form-group" style="margin-top: 23px !important;">
                                        <button type="submit" class="btn btn-warning"><i class="fa fa-eye"></i>Preview</button>
                                </div>

                            </div>
                            </form>
                            <hr>
                            <div class="row col-md-12 justify-content-between">
                                <span id="loading-indicator"></span>
                                </div>
                            <?php echo form_open_multipart(base_url('person/save'), array('id' => 'person', 'class' => 'person', 'method'=>'post')); ?>
                            <div class="row">
                                    <div class="form-group col-md-6">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-file"></i>Save Data</button>
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
                                        <th>Comment</th>

                                    </tr>
                                </thead>
                                <tbody>
                                                    <input type="hidden" class="form-control" id="financial_year"
                                                    name="financial_year" value="<?php echo @ $this->input->get('financial_year');?>">

                                                        <input type="hidden" class="form-control" id="period"
                                                    name="period" value="<?php echo @$this->input->get('period'); ?>">
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
                                                <label>Comment</label>
                                                <input type="text" class="form-control" id="comment"
                                                    name="comment[<?= $kpi->kpi_id ?>][]" value="<?php echo @data_value($this->session->userdata('ihris_pid'), $kpi->kpi_id, $this->input->get('financial_year'), $this->input->get('period'))->comment; ?>">
                                            </td>
                                            <input type="hidden" class="form-control" id="data_target"
                                                    name="data_target[<?= $kpi->kpi_id ?>][]" value="<?php echo @data_value($this->session->userdata('ihris_pid'), $kpi->kpi_id, $this->input->get('financial_year'), $this->input->get('period'))->data_target; ?>">
                            

                                            </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <!-- <div class="row">
                                    <div class="form-group col-md-6">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-file"></i> Save Data </button>
                                </div>
                            </div> -->
                            <?php }?>

                            

                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>
</div>

<script>
$(document).ready(function() {
    $('#person').submit(function(e) {
        e.preventDefault(); // Prevent the default form submission
        
        // Serialize the form data
        var formData = $('#person').serialize();
        
        // Show a loading spinner or text
        $('#loading-indicator').html('Saving...'); // You can use a loading spinner or any text

        // Send an AJAX request to the server
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('person/save'); ?>',
                data: formData,
                success: function (response) {
                    // Hide the loading spinner or text
                    $('#loading-indicator').html(''); // Remove the loading spinner or text

                    // Notify success
                    $.notify(response, "success");
                },
                error: function (error) {
                    // Hide the loading spinner or text
                    $('#loading-indicator').html(''); // Remove the loading spinner or text

                    // Handle any errors
                    $.notify(error, "warning");
                }
            });
        });
    });
</script>