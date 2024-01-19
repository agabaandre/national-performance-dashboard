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
                                <select class="form-control selectize" name="focus_area">
                                    <option value="0">Pending</option>
                                    <option value="1">Approved</option>
                                    <option value="2">Rejected</option>

                                </select>
                            </div>
                            <div class="form-group" style="margin-top: 23px !important;">
                                <button type="submit" class="btn btn-warning"><i class="fa fa-eye"></i>Preview</button>
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
                                            <?php $status=$report->approved;?>
                                            <?= ($status == 0) ? 'Pending' : (($status == 1) ? 'Approved' : 'Rejected'); ?>

                                        </td>
                                        <td>

                                            <div>
                            
                                                <?php echo form_open_multipart(base_url('person/data'), array('id' => 'get_performance', 'class' => 'person form-horizontal', 'method' => 'get')); ?>

                                                <!-- Approval Button -->
                                                <div class="d-flex">
                                                <!-- Preview Button -->
                                                <a href="<?php echo base_url() ?>person?ihris_pid=<?=urlencode($report->ihris_pid); ?>&facility_id=<?=urlencode($report->facility_id) ?>&job_id=<?=urlencode($report->kpi_group) ?>&financial_year=<?=urlencode($report->financial_year) ?>&period=<?=urlencode($report->period) ?>&handshake=<?php echo urlencode(md5('readonly')).'726yhsa'?>"
                                                    class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>Review
                                                </a>
                                            </div>
                                            </div>

                                            <?php echo form_close(); ?>

                                          
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


<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel">Confirm Approval</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to approve this report?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" form="get_performance" name="action" value="approve" class="btn btn-success">Approve</button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Confirm Rejection</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to reject this report?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" form="get_performance" name="action" value="reject" class="btn btn-danger">Reject</button>
            </div>
        </div>
    </div>
</div>

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