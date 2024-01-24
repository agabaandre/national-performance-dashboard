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
            </div>
            <div class="panel-container show">
                <div class="panel-content">

                    <div class="col-md-12">
                        <h4 style="text-align:left; padding-bottom:1em; text-weight:bold;">Approve Staff KPI Data
                        </h4>
                   
                        <?php echo form_open_multipart(base_url('person/approve'), array('id' => 'preview', 'class' => 'preview', 'method' => 'get')); ?>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="financial_year">Financial Year:(*)</label>
                                <select class="form-control selectize" name="financial_year" required>
                                    <option value="">Select Finanacial_year</option>
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
                                <?php $quaters = array("Q1", "Q2", "Q3", "Q4"); ?>
                                <select class="form-control selectize" name="period" required>
                                    <option value="">Select Period</option>
                                    <?php foreach ($quaters as $quater) { ?>

                                        <option value="<?php echo $quater; ?>" <?php if ($this->input->get('period') == $quater) {
                                               echo "selected";
                                           } ?>><?php echo $quater; ?>
                                        </option>
                                    <?php } ?>

                                </select>
                            </div>
                            <div class="form-group col-md-3">

                                <label for="focus_areas">Status:</label>
                                <select class="form-control selectize" name="approved">
                                    <option value="0">Pending</option>
                                    <option value="1">Approved</option>
                                    <option value="2">Rejected</option>

                                </select>
                            </div>
                            <div class="form-group col-md-3">

                                <label for="focus_areas">Status:</label>
                                <select class="form-control selectize" name="approved2">
                                    <option value="0">Pending</option>
                                    <option value="1">Approved</option>
                                    <option value="2">Rejected</option>

                                </select>
                            </div>
                            <div class="form-group" style="margin-top: 23px !important;">
                                <button type="submit" class="btn btn-warning"><i class="fa fa-eye"></i>View</button>
                            </div>

                        </div>
                        <input type="hidden" class="form-control" id="ihris_pid" name="ihris_pid"
                            value="<?php echo @urldecode($this->input->get('ihris_pid')); ?>">
                        <input type="hidden" class="form-control" id="facility_id" name="facility_id"
                            value="<?php echo @urldecode($this->input->get('facility_id')); ?>">
                        </form>
                        <hr>
                        <div class="row col-md-12 justify-content-between">
                            <span id="loading-indicator"></span>
                        </div>
                       
                                        </form>

                        <table class="table table-striped table-bordered dataTable no-footer dtr-inline">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name </th>
                                    <th>Facility </th>
                                    <th>Job </th>
                                    <th>Reporting Period</th>
                                    <th>Submission Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>

                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                $i = 1;
                                foreach ($reports as $report):

                                    //dd($kpidatas);
                                
                                    ?>
                                    <tr>
                                        <td>
                                            <?= $i++ ?>

                                        </td>

                                        <td>
                                            <?= $report->surname . ' ' . $report->firstname . ' ' . $report->othername; ?>

                                        </td>
                                         <td>
                                            <?= $report->facility?>
                                                                    
                                        </td>
                                        <td>
                                            <?= $report->job ?>
                                        
                                        </td>


                                                <td>
                                            <?= $report->financial_year . ' - ' . $report->period ?>
                                            <input type="hidden" class="form-control" id="ihris_pid" name="ihris_pid"
                                                value='<?= $report->surname ?>'>
                                            <input type="hidden" class="form-control" id="financial_year"
                                                name="financial_year" <?= $report->financial_year ?>>
                                            <input type="hidden" class="form-control" id="period" name="period"
                                                <?= $report->period ?>>
                                        </td>
                                    

                                        <td>
                                            <?= $report->upload_date; ?>

                                        </td>

                                        <td>
                                        <?php $status1=$report->approved;?>
                                         <p style="color:<?php echo rowcolor($report->approved) ?>;"><?= ($status1 == 0) ? 'Pending' : (($status1 == 1) ? 'Approved' : 'Rejected'); ?> - Supervisor One </p>

                                         
                                         <?php 
                                            if(!empty($report->supervisor_id_2)){ ?>
                                            <?php $status2 = $report->approved2; ?>
                                            <hr>
                                            <p style="color:<?php echo rowcolor($report->approved2) ?>;">
                                                <?= ($status2 == 0) ? 'Pending' : (($status2 == 1) ? 'Approved' : 'Rejected'); ?> - Supervisor Two
                                            </p>

                                              <?php }
                                             ?>



                                            </td>
                                            <td>

                                                <div>
                            
                                            
                                                    <!-- Approval Button -->
                                                    <div class="d-flex">
                                                    <!-- Preview Button 
                                                    if supervisor one has approved, then allow supervisor two to approve-->
                                                    <?php if((($status1==0)&&(($this->session->userdata('ihris_pid')==$report->supervisor_id)))|| (($status1 == 1) && ($status2 == 0) && (($this->session->userdata('ihris_pid') == $report->supervisor_id_2)) && !empty($this->session->userdata('ihris_pid'))) || ($this->session->userdata('user_type')=='admin')){?>
                                                    <a href="<?php echo base_url() ?>person?ihris_pid=<?=urlencode($report->ihris_pid); ?>&facility_id=<?=urlencode($report->facility_id) ?>&job_id=<?=urlencode($report->kpi_group) ?>&financial_year=<?=urlencode($report->financial_year) ?>&period=<?=urlencode($report->period) ?>&handshake=<?php echo urlencode(md5('readonly')).'726yhsa'?>&approval=<?=$report->approved?>"
                                                    class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>View
                                                   </a>
                                                <?php } 

                                                
                                                
                                                if(($report->draft_status == 0)&&($this->session->userdata('ihris_pid')== $report->ihris_pid)){?>

                                           

                                                   <a href="<?php echo base_url() ?>person?ihris_pid=<?= urlencode($report->ihris_pid); ?>&facility_id=<?= urlencode($report->facility_id) ?>&job_id=<?= urlencode($report->kpi_group) ?>&financial_year=<?= urlencode($report->financial_year) ?>&period=<?= urlencode($report->period) ?>&handshake=<?php echo urlencode(md5('readonly')) . '726yhsa' ?>"
                                                            class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye"></i>Edit
                                                    </a>

                                            <?php } ?>


                                                </div>
                                                </div>

                                         

                                          
                                            </td>


                                        </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>




                    </div>

                </div>
            </div>
        </div>
    </div>
</div>






<script>
    $(document).ready(function () {
        $('#person').submit(function (e) {
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