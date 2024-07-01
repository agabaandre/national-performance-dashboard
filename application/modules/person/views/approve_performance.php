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

                        <?php echo form_open_multipart('', array('id' => 'filter-form', 'class' => 'preview', 'method' => 'get')); ?>
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

                                <label for="focus_areas">Supervisor 1 Status:</label>
                                <select class="form-control selectize" name="approved">
                                    <option value="" selected>Select Status</option>
                                    <option value="0">Pending</option>
                                    <option value="1">Approved</option>
                                    <option value="2">Rejected</option>

                                </select>
                            </div>
                            <div class="form-group col-md-3">

                                <label for="focus_areas">Supervisor 2 Status :</label>
                                <select class="form-control selectize" name="approved2">
                                    <option value="" selected>Select Status</option>
                                    <option value="0">Pending</option>
                                    <option value="1">Approved</option>
                                    <option value="2">Rejected</option>

                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="Facility">Staff:</label>
                            
                                <select class="form-control selectize" name="person_pid" style="width:100%">
                                <option value="" selected>Select Staff</option>
                                <?php 
                                $facility_id = $this->session->userdata('facility_id');
                                $facility ="";
                                if(!empty($facility_id)){
                                $facility = "WHERE facility_id='$facility_id'";
                                }
                        
                                $facilities = $this->db->query("SELECT * FROM ihrisdata $facility ORDER BY surname ASC")->result();
                                //dd($facilities);
                                ?>

                                
                                    <?php 
                                    foreach($facilities as $facility): ?>
                                    <option  value="<?=$facility->ihris_pid?>"><?=$facility->surname.' '.$facility->firstname;?></option>

                                   <?php endforeach;
                                    ?>
                                
                                </select>
                            </div>
                            <div class="form-group" style="margin-top: 23px !important; margin-left:15px;">
                                <button type="submit" class="btn btn-info waves-effect waves-themed"><i
                                        class="fa fa-eye"></i>View</button>
                            </div>

                        </div>
                        <input type="hidden" class="form-control" id="ihris_pid" name="ihris_pid"
                            value="<?php echo @urldecode($this->input->get('ihris_pid')); ?>">
                        <input type="hidden" class="form-control" id="facility_id" name="facility_id"
                            value="<?php echo @urldecode($this->input->get('facility_id')); ?>">
                
                        <hr>
                        <div class="row col-md-12 justify-content-between">
                            <span id="loading-indicator"></span>
                        </div>

                        </form>

                        <table id="dataTable" class="table table-bordered table-hover table-striped w-100 table-responsive">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Facility</th>
                                    <th>Job</th>
                                    <th>Reporting Period</th>
                                    <th>Submission Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>













                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Initialize DataTable
        // console.log('<?= $this->db->last_query() ?>');
        var table = $('#dataTable').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "ajax": {
                "url": "<?php echo base_url('person/approve'); ?>?ajax=1", // Append the GET parameter
                "type": "GET",
                "data": function (d) {
                    
                    // Add filter parameters from form
                    d.financial_year = $('select[name="financial_year"]').val();
                    d.period = $('select[name="period"]').val();
                    d.approved = $('select[name="approved"]').val();
                    d.approved2 = $('select[name="approved2"]').val();
                    d.person_pid = $('select[name="person_pid"]').val();
                    d.facility_id = $('#facility_id').val();
                
                }
                 
            },
            "paging": true,
            "pageLength": 20,
            "lengthMenu": [20, 25, 50, 100],
            "searching": false, // Enable searching
        
            "columns": [
                {
                    "data": null, "render": function (data, type, row, meta) {
                        return meta.row + 1; // Generate row numbers starting from 1
                    }
                },
                {
                    "data": null, "render": function (data, type, row) {
                        var fullName = row.surname + ' ' + row.firstname;
                        if (row.othername) {
                            fullName += ' ' + row.othername;
                        }
                        return fullName;
                    }
                },
                { "data": "facility" },
                { "data": "job" },
                {
                    "data": null, "render": function (data, type, row) {
                        return row.financial_year + ' - ' + row.period;
                    }
                },
                { "data": "upload_date" },
                {
                    "data": null,
                    "render": function (data, type, row) {
                       
                        // Define status text and color based on the approved status
                        var status1Text = (row.approved == 0) ? 'Pending' : ((row.approved == 1) ? 'Approved' : 'Rejected');
                        var status1Color = getStatusColor(row.approved);

                        // Construct the status HTML for Supervisor One
                        var status1HTML = '<p style="color:' + status1Color + ';">' + status1Text + ' - Supervisor One</p>';

                        // If Supervisor Two exists, define its status HTML
                        var status2HTML = '';
                        if (row.supervisor_id_2) {
                            var status2Text = (row.approved2 == 0) ? 'Pending' : ((row.approved2 == 1) ? 'Approved' : 'Rejected');
                            var status2Color = getStatusColor(row.approved2);
                            status2HTML = '<hr><p style="color:' + status2Color + ';">' + status2Text + ' - Supervisor Two</p>';
                        }

                        // Return concatenated status HTML
                        return status1HTML + status2HTML;
                    }
                },
                {
                    "data": null, "render": function (data, type, row) {
                        var url = '<?php echo base_url(); ?>person?ihris_pid=' + row.ihris_pid + '&facility_id=' + row.facility_id + '&job_id=' + row.kpi_group + '&financial_year=' + row.financial_year + '&period=' + row.period + '&handshake=<?php echo urlencode(md5('readonly')) . '726yhsa' ?>&approval=' + row.approved;
                        return '<a href="' + url + '" class="btn btn-sm btn-info"><i class="fas fa-eye"></i>View</a><br> ';
                    }
                }
               
            ]
        });

        function getStatusColor(status) {
            if (status == 0) {
                return '#F79500'; // Pending status
            } else if (status == 1) {
                return 'green'; // Approved status
            } else {
                return 'red'; // Rejected status
            }
        }

        // Intercept form submission
        $('#filter-form').submit(function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Reload DataTable with new data
            table.ajax.reload();
        });
     
            });
</script>
