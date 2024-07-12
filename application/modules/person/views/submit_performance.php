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

                    </ul>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">


                    <div class="col-md-12">
                        <h4 style="text-align:left; padding-bottom:1em; text-weight:bold;">Staff KPI Data
                            Capture Form
                        </h4>
                        <h5>
                            <p> Staff Name:
                                <?php
                                $pid = urldecode($_GET['ihris_pid']);
                                echo get_field($pid, 'surname') . ' ' . get_field($pid, 'firstname') . ' - ' . get_field($pid, 'job');
                                ?>
                            </p>
                        </h5>
                        <?php echo form_open_multipart(base_url('person/index'), array('id' => 'preview', 'class' => 'preview', 'method' => 'get')); ?>
                        <div class="row">
                            <div class="form-group col-md-3">


                                <label for="financial_year">Financial Year:(*)</label>

                                <?php if ($readonly) { ?>
                                    <input type="text" class="form-control"
                                        value="<?= $this->input->get('financial_year') ?>" name="financial_year" readonly>

                                <?php } else { ?>

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
                                <?php } ?>

                            <?php
                            $supervisor1 = get_field($pid, 'supervisor_id');
                            if (empty($supervisor1)) {
                                $supervisor1 = urldecode($this->input->get('supervisor_id'));
                            }

                            $supervisor2 = get_field($pid, 'supervisor_id_2');
                            if (empty($supervisor2)) {
                                $supervisor2 = urldecode($this->input->get('supervisor_id_2'));
                            }
                            ?>



                            </div>

                            <div class="form-group col-md-3">
                                <label for="period">Period:(*)</label>



                                <?php if ($readonly) { ?>
                                    <input type="text" class="form-control" value="<?= $this->input->get('period'); ?>"
                                        name="period" readonly>


                                <?php } else { ?>


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


                                <?php } ?>
                            </div>
                            <div class="form-group col-md-3">

                                <label for="focus_areas">Focus Areas:</label>
                                <select class="form-control select2" name="focus_area">
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
                            <input type="hidden" class="form-control" id="ihris_pid" name="ihris_pid"
                                value="<?php echo @urldecode($this->input->get('ihris_pid')); ?>">
                            <input type="hidden" class="form-control" id="facility_id" name="facility_id"
                                value="<?php echo @urldecode($this->input->get('facility_id')); ?>">
                            <input type="hidden" class="form-control" name="job_id"
                                value="<?php echo @urldecode($this->input->get('job_id')); ?>">
                            <input type="hidden" class="form-control" id="supervisor_id" name="supervisor_id"
                                value="<?php echo $supervisor1; ?>">
                            <input type="hidden" class="form-control" id="supervisor_id_2" name="supervisor_id_2"
                                value="<?php echo $supervisor2; ?>">
                            <input type="hidden" class="form-control" id="handshake" name="handshake"
                                value="<?php echo @urldecode($this->input->get('handshake')); ?>">
                            <div class="form-group" style="margin-top: 23px !important;">

                                <button type="submit" class="btn btn-info waves-effect waves-themed"><i class="fa fa-eye"></i>Preview</button>
                            </div>

                        </div>

                        <hr>




                        </form>

                        <div class="row col-md-12 justify-content-between">
                            <span id="loading-indicator"></span>
                        </div>
                        <?php

                        // Check conditions
                        $isReadonly = (lockedfield($readonly) == 'readonly');
                        $currentUserId = $this->session->userdata('ihris_pid');
                        $isSupervisor = ($currentUserId == $supervisor1) || ($currentUserId == $supervisor2);
                        $isAdmin = ($this->session->userdata('user_type') == 'admin');
                        $approval = $this->input->get('approval');

                        if ($isReadonly && $approval<1 && ($isSupervisor || $isAdmin)) {
                
                            ?>

                            <div class="d-flex mt-2">
                                <?php if (empty($this->input->get('page'))) { ?>
                                    <?php echo form_open_multipart(base_url('person/data'), array('id' => 'get_performance', 'class' => 'person form-horizontal', 'method' => 'get')); ?>


                                    <button type="submit" name="action" value="approve" class="btn btn-sm btn-success">
                                        <i class="fas fa-check"></i> Approve
                                    </button>

                                    <!-- Reject Button -->
                                    <button type="submit" name="action" value="reject" class="btn btn-sm btn-danger">
                                        <i class="fas fa-times"></i> Reject
                                    </button>

                            
                                 
                                    </form>
                                <?php } ?>
                            </div>

                        <?php } else if(!$isReadonly) { ?>

                            <?php echo form_open_multipart(base_url('person/save'), array('id' => 'person', 'class' => 'person', 'method' => 'post')); ?>

                            <div class="row">

                                <?php @$draft = data_value(urldecode($this->input->get('ihris_pid')), $kpi->kpi_id, $this->input->get('financial_year'), $this->input->get('period'))->draft_status;
//   echo "Iam";
//                                echo $readonly;
                                ?>
                              <div class="alert col-md-12 d-flex alert-dismissible fade" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                                                    </button>
                                                    <strong id="message"></strong> 
                                    </div>
                                    <br>

                                <div class="row">
                                     
                                   
                              
                                 
                                        <div class="form-group col-md-6 d-flex" id="save_data_btn">

                                        <div class="dropdown">
                                            <button class="btn btn-info waves-effect waves-themed dropdown-toggle" type="button"
                                                id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false"><i class="fa fa-file"></i>
                                                Save Data
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                                <button class="dropdown-item" type="submit" id="save_as_draft">Save as
                                                    Draft</button>
                                                <button type="button"  class="dropdown-item" data-toggle="modal" data-target="#finalassessment">
                                                    Submit for
                                                    Assessment
                                                </button>
                                                
                                            </div>
                                        </div>



                                        <!-- <button type="submit" class="btn btn-success"><i class="fa fa-file" style="margin-bottom:5px; !important"></i>Save</button> -->
                                    </div>
                                </div>
                            <?php } ?>
                            <?php
                            //dd($show);
                            if ($show == 1) { ?>
                                <table class="table table-striped table-bordered dataTable no-footer dtr-inline">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Indicator</th>
                                            <th>Numerator</th>
                                            <th>Denominator</th>
                                            <th>Target</th>
                                            <th>Comment</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <input type="hidden" class="form-control" id="financial_year" name="financial_year"
                                            value="<?php echo @$this->input->get('financial_year'); ?>">
                                        <input type="hidden" class="form-control" id="ihris_pid" name="ihris_pid"
                                            value="<?php echo @urldecode($this->input->get('ihris_pid')); ?>">
                                        <input type="hidden" class="form-control" id="supervisor_id" name="supervisor_id"
                                            value="<?php echo $supervisor1; ?>">

                                        <input type="hidden" class="form-control" id="supervisor_id_2"
                                            name="supervisor_id_2"
                                            value="<?php echo $supervisor2; ?>">
                                        <input type="hidden" class="form-control" id="facility_id" name="facility_id"
                                            value="<?php echo @urldecode($this->input->get('facility_id')); ?>">
                                        <input type="hidden" class="form-control" name="job_id"
                                            value="<?php echo @urldecode($this->input->get('job_id')); ?>">
                                        <input type="hidden" class="form-control" id="period" name="period"
                                            value="<?php echo @$this->input->get('period'); ?>">
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
                                                        <input type="number" class="form-control" id="numerator" class="numerator"
                                                            name="numerator[<?= $kpi->kpi_id ?>][]"
                                                            value="<?php echo @data_value(urldecode($this->input->get('ihris_pid')), $kpi->kpi_id, $this->input->get('financial_year'), $this->input->get('period'))->numerator; ?>"
                                                            <?= lockedfield($readonly) ?> min=0>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php if ($kpi->computation_category == 'Ratio') { ?>
                                                        <div class="form-group">
                                                            <label>
                                                                <?= $kpi->denominator ?>
                                                            </label>
                                                            <input type="number" class="form-control" id="denominator" class="denominator"
                                                                name="denominator[<?= $kpi->kpi_id ?>][]"
                                                                value="<?php echo @data_value(urldecode($this->input->get('ihris_pid')), $kpi->kpi_id, $this->input->get('financial_year'), $this->input->get('period'))->denominator;  ?>"
                                                                <?= lockedfield($readonly) ?> min=0>
                                                        </div>
                                                    <?php } ?>

                                                </td>

                                                <td>
                                                     <label style="margin-top:4px;">Target

                                                     </label>
                                                <?php $target = data_value(urldecode($this->input->get('ihris_pid')), $kpi->kpi_id, $this->input->get('financial_year'), $this->input->get('period'))->data_target; ?>
                                                  <input type="number" class="form-control" id="data_target" class="data_target"
                                                    name="data_target[<?= $kpi->kpi_id ?>][]"
                                                         value="<?php if($target>0){ echo $target;} else{ $kpi->current_target;} ?>"  <?= lockedfield($readonly) ?> min=0>
                                                        <!-- <label>Score</label>
                                                     <input type="text" class="form-control" class="score"  readonly> -->
                                                                                        
                                                 </td>

                                                        <td>
                                                            <label>Comment</label>
                                                            <input type="text" class="form-control" id="comment" class="comment"
                                                                name="comment[<?= $kpi->kpi_id ?>][]"
                                                        value="<?php echo @data_value(urldecode($this->input->get('ihris_pid')), $kpi->kpi_id, $this->input->get('financial_year'), $this->input->get('period'))->comment; ?>"
                                                        <?= lockedfield($readonly) ?> title="<?php echo @data_value(urldecode($this->input->get('ihris_pid')), $kpi->kpi_id, $this->input->get('financial_year'), $this->input->get('period'))->comment; ?>">
                                                </td>
        


                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <!-- <div class="row">
                                    <div class="form-group col-md-6">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-file"></i> Save Data </button>
                                </div>
                            </div> -->
                            <?php } ?>

                            <!-- Final Modal -->
                           <div class="modal fade" id="finalassessment" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true" data-backdrop="static">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header justify-content-center">
                                            <h5 class="modal-title " id="finalassessment"> Are you sure you want to
                                                submit this report for Approval? This action is irreversible</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">No</button>
                                            <button class="btn btn-success" type="submit" id="save_as_final">Yes</button>


                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Final Modal -->



                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header justify-content-center">
                    <h5 class="modal-title " id="approveModalLabel"> Are you sure you want to approve this report?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php echo form_open_multipart(base_url('person/update_data_status'), array('id' => 'update_report_statuses', 'class' => 'update_report_status', 'method' => 'get')); ?>
                <div class="modal-body">

                    <?php if ($this->input->get('approval') > 0) { ?>
                        <input type="hidden" class="form-control" name="approved2" value="1">
                    <?php } else { ?>
                        <input type="hidden" class="form-control" name="approved" value="1">
                    <?php } ?>
                    <input type="hidden" class="form-control" name="period" value="<?= $this->input->get('period') ?>">
                    <input type="hidden" class="form-control" name="financial_year"
                        value="<?= $this->input->get('financial_year') ?>">
                    <input type="hidden" class="form-control" name="ihris_pid"
                        value="<?= $this->input->get('ihris_pid') ?>">
                    <input type="hidden" name="redirect"
                        value="person?ihris_pid=<?= urlencode($this->input->get('ihris_pid')) ?>&facility_id=<?= urlencode($this->input->get('facility_id')) ?>&job_id=<?= urlencode($this->input->get('job_id')) ?>&financial_year=<?= $this->input->get('financial_year') ?>&period=<?= $this->input->get('period') ?>&handshake=<?php echo urlencode(md5('readonly')) . '726yhsa' ?>">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>

                    <button type="submit" class="btn btn-success">Approve</a>
                        </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel"> Are you sure you want to reject this report?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">


                    <?php echo form_open_multipart(base_url('person/update_data_status'), array('id' => 'update_report_status', 'class' => 'update_report_status', 'method' => 'get')); ?>
                    <label>Reason for Rejection</label>

                    <?php if ($this->input->get('approval') > 0) { ?>
                        <textarea class="form-control" name="reject_reason2"></textarea>
                        <input type="hidden" class="form-control" name="approved2" value="2">
                    <?php } else { ?>
                        <textarea class="form-control" name="reject_reason"></textarea>
                        <input type="hidden" class="form-control" name="approved" value="2">
                    <?php } ?>
                    <input type="hidden" class="form-control" name="period" value="<?= $this->input->get('period') ?>">
                    <input type="hidden" class="form-control" name="financial_year"
                        value="<?= $this->input->get('financial_year') ?>">
                    <input type="hidden" class="form-control" name="ihris_pid"
                        value="<?= $this->input->get('ihris_pid') ?>">
                    <input type="hidden" name="redirect"
                        value="person?ihris_pid=<?= urlencode($this->input->get('ihris_pid')) ?>&facility_id=<?= urlencode($this->input->get('facility_id')) ?>&job_id=<?= urlencode($this->input->get('job_id')) ?>&financial_year=<?= $this->input->get('financial_year') ?>&period=<?= $this->input->get('period') ?>&handshake=<?php echo urlencode(md5('readonly')) . '726yhsa' ?>">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>

                    </form>

                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            var draftStatus = 0; // Default value for "Save as Draft"

            // Click event handler for "Save as Draft" button
            $('#save_as_draft').click(function () {
                draftStatus = 0;
                $('#save_as_final').data('clicked', false);
            });

            // Click event handler for "Submit for Approval" button
            $('#save_as_final').click(function () {
                draftStatus = 1;
                 $('#save_as_draft').data('clicked', false);
                 $('#finalassessment').modal('hide');
                 
            });

            $('#person').submit(function (e) {
                e.preventDefault(); // Prevent the default form submission

                // Append the draft_status field to the form data
                var formData = $('#person').serialize() + '&draft_status=' + draftStatus;

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
                        $('#message').text(response);
                            // Display the alert for 3 seconds and then hide it
                        $('.alert').addClass('show alert-success'); 
                            setTimeout(function() {
                            $('.alert').removeClass('show'); // Hide the alert after 3 seconds
                        }, 5000);
                    },
                    error: function (error) {
                        // Hide the loading spinner or text
                        $('#loading-indicator').html(''); // Remove the loading spinner or text

                          $('#message').text(error);
                            // Display the alert for 3 seconds and then hide it
                        $('.alert').addClass('show'); 
                            setTimeout(function() {
                            $('.alert').addClass('show alert-danger'); // Hide the alert after 3 seconds
                        }, 5000);
                    }
                });
            });
        });


    </script>


    <script>
        // Trigger modals on button click
        $(document).ready(function () {
            $('#get_performance').submit(function (e) {
                e.preventDefault(); // Prevent the default form submission

                // Determine the action (approve or reject)
                var action = $('button[name="action"]:focus').val();

                // Open the corresponding modal
                if (action === 'approve') {
                    $('#approveModal').modal('show');
                } else if (action === 'reject') {
                    $('#rejectModal').modal('show');
                }
            });
        });
    </script>


<script>
    $(document).ready(function() {
        // Event handler for numeric fields
       $('.numerator, .denominator, .data_target').on('keyup', function() {
            // Loop through each row
            $('tr').each(function() {
                var $row = $(this);
                var numerator = parseFloat($row.find('.numerator').val());
                var denominator = parseFloat($row.find('.denominator').val());
                var target = parseFloat($row.find('.data_target').val());
                var score;

                console.log(numerator)

                // Check if numerator and target are numeric
                if (!isNaN(numerator) && !isNaN(target)) {
                    // If denominator is provided and numeric, calculate score as (numerator/denominator)*100
                    if (!isNaN(denominator) && denominator > 0) {
                        score = (numerator / denominator) * 100;
                    } else {
                        // If denominator is empty or not numeric, calculate score as numerator
                        score = numerator;
                    }

                    // Update score field in the current row
                    $row.find('.score').val(score.toFixed(2));
                }
            });
        });

        // Event handler for comment field
        $('.comment').on('input', function() {
            var $row = $(this).closest('tr'); // Get the closest row
            var comment = $row.find('.comment').val();
            var words = comment.trim().split(/\s+/).length;

            // Check if comment has at least 3 words
            if (words < 3) {
                alert('Please enter at least 3 words in the comment field.');
            }
        });
    });
</script>
