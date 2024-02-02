<div class="subheader">
    <h1 class="subheader-title">

    </h1>
</div>

<div class="col-md-12">

    <?php echo form_open_multipart(base_url('person/manage_people'), array('id' => 'search_person', 'class' => 'search_person', 'method' => 'get')); ?>
    <div class="row">

        <div class="form-group col-md-4">
            <label for="Facility">Facility:</label>
            <select class="form-control selectize" name="facility" onchange="getFacStaff(this.value)">
                <option value=""> Search Facility</option>
                <?php
                foreach ($facilities as $facility) { ?>
                    <option value="<?php echo $facility->facility_id; ?>" <?php if ($this->input->get('facility') == $facility->facility_id) {
                           echo "selected";
                       } ?>>
                        <?php echo $facility->facility ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="Facility">Staff:</label>

            <select class="form-control facility_staff select2" name="ihris_pid" required style="width:100%">

            </select>

        </div>
        <div class="form-group col-md-4">

            <br>
            <button type="submit" class="btn btn-primary">Manage</button>

        </div>
        <?php echo form_close(); ?>
        <div class="form-group col-md-4">
            <br>
            <!-- <a href="<?php echo base_url() ?>person/enroll_facility/<?php if (isset($_GET['facility'])) {
                   echo $_GET['facility'];
               } else {
                   echo $_SESSION['facility_id'];
               } ?>"
    class="btn btn-primary">Enroll Employees</a> -->

        </div>
    </div>
    <?php echo $links ?>

    <table class="table table-striped table-bordered dataTable no-footer dtr-inline">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Job</th>
                <th>Facility</th>
                <th>#</th>

            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            foreach ($employees as $employee):

                // dd($employee);
            
                ?>
                <tr>
                    <td>
                        <?= $i++ ?>

                    </td>

                    <td>
                        <?= $employee->surname . ' ' . $employee->firstname . ' ' . $employee->othername ?>

                    </td>

                    <td>
                        <?= $employee->job ?>

                    </td>

                    <td>
                        <?= $employee->facility ?>

                    </td>
                    <td>
                        <?php
                        $this->db->where('ihris_pid', "$employee->ihris_pid");
                        $res = $this->db->get('ihrisdata')->num_rows();
                        //dd($res);
                        if ($res == 0) {
                            $action = 'Enroll';
                        } else {
                            $action = "Edit Enrollment";
                        }
                        ?>

                        <a href="#" data-toggle="modal" data-target="#supervisor<?= $employee->id; ?>">
                            <?php echo $action ?>
                        </a>


                    </td>



                    <!-- Modal center -->
                    <?php echo form_open_multipart(base_url('person/add_supervisor'), array('id' => 'update_employee', 'class' => 'update_employee', 'method' => 'get')); ?>
                    <div class="modal fade" id="supervisor<?= $employee->id; ?>" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">
                                        Edit & Enroll Employee -
                                        <?= $employee->surname . ' ' . $employee->firstname . ' ' . $employee->othername ?>

                                    </h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <?php 
                                    $ihris_pid = $employee->ihris_pid;
                                    $otherfields=$this->db->query("SELECT * from ihrisdata where ihris_pid='$ihris_pid'")->row();
                                    ?>


                                    <label for="supervisor"> Supervisor 1:(*)</label>
                                    <input type="hidden" name="supervisor_id" class="form-control" id="supervisor_id"
                                        value="<?php $otherfields->supervisor_id ?>">


                                    <select class="form-control selectize" id="supervisor_name"
                                        onchange="supervisor(this.value)" style="width:100%;" required>

                                        <option value=""> Search Supervisor</option>
                                        <?php
                                        $supervisors = $this->db->query("SELECT id,ihris_pid,supervisor_id,facility,surname,firstname,othername,job from ihrisdata_staging WHERE district_id='$employee->district_id' AND ihris_pid!='$employee->ihris_pid' AND (surname!='' OR firstname!='' OR othername!='') OR (	
                                                                    institutiontype_name LIKE 'Ministry' OR institutiontype_name LIKE 'Regional Referral Hospital%' OR institutiontype_name LIKE 'National Referral Hospital%')  order by surname ASC")->result();
                                        foreach ($supervisors as $supervisor) {
                                            // dd($supervisor);
                                            ?>


                                            <option value="<?= $supervisor->ihris_pid ?>" <?php if ($supervisor->ihris_pid == $otherfields->supervisor_id) {
                                                echo "selected";
                                            } ?>>
                                                <?php echo $supervisor->surname . ' ' . $supervisor->firstname . '- (' . $supervisor->facility . ')' . '-' . $supervisor->job; ?>
                                            </option>
                                        <?php }

                                        ?>
                                    </select>


                                    <label for="supervisor"> Supervisor 2 : (Optional)</label>
                                    <input type="hidden" name="supervisor_id_2" class="form-control" id="supervisor_2"
                                        value="<?php $otherfields->supervisor_id_2 ?>">

                                    <select class="form-control selectize" id="supervisor_name2"
                                        onchange="supervisor_2(this.value)" style="width:100%;">

                                        <option value=""> Search Supervisor</option>
                                        <?php
                                        $supervisors = $this->db->query("SELECT id,ihris_pid,supervisor_id,facility,surname,firstname,othername,job from ihrisdata_staging WHERE district_id='$employee->district_id' AND ihris_pid!='$employee->ihris_pid' AND (surname!='' OR firstname!='' OR othername!='') OR (	
                                                                    institutiontype_name LIKE 'Ministry' OR institutiontype_name LIKE 'Regional Referral Hospital%' OR institutiontype_name LIKE 'National Referral Hospital%')  order by surname ASC")->result();
                                        foreach ($supervisors as $supervisor) {
                                            // dd($supervisor);
                                            ?>


                                            <option value="<?= $supervisor->ihris_pid ?>" <?php if ($supervisor->ihris_pid == $otherfields->supervisor_id_2) {
                                                echo "selected";
                                            } ?>>
                                                <?php echo $supervisor->surname . ' ' . $supervisor->firstname . '- (' . $supervisor->facility . ')' . '-' . $supervisor->job; ?>
                                            </option>
                                        <?php }

                                        ?>
                                    </select>


                                    <label for="email"> Staff Email:(*)</label>
                                    <input type="email" name="email" class="form-control" required
                                        value="<?php  if(!empty($otherfields->email)){ echo $otherfields->email;} else{ echo $employee->email;  }; ?>">
                                    <input type="hidden" name="ihris_pid" class="form-control"
                                        value="<?php echo $employee->ihris_pid; ?>">

                                    <label for="mobile"> Staff Phone Number:(*)</label>
                                    <input type="text" name="mobile" class="form-control" required
                                        value="<?php if (!empty($otherfields->mobile)) {
                                                echo $otherfields->mobile;
                                            } else {
                                                echo $employee->mobile;
                                            }
                                            ; ?>">

                                    <label for="role">Allow Data Capture for Others</label>

                                    <select class="form-control" name="data_role">
                                        <option value="0" <?php
                                         if ($otherfields->data_role == 0) {
                                            echo "selected";
                                        } ?>>No
                                        </option>
                                        <option value="1" <?php if ($otherfields->data_role == 1) {
                                            echo "selected";
                                        } ?>>Yes
                                        </option>
                                    </select>
                                    <label for="supervisor"> KPI Group:</label>
                                    <select class="form-control" name="kpi_group_id">
                                        <option value="">Select KPI Group</option>
                                        <?php
                                        $kpigroups = $this->db->query("SELECT job_id, job from kpi_job_category")->result();

                                        foreach ($kpigroups as $kgroup) { ?>
                                            <option value="<?= $kgroup->job_id; ?>" <?php if ($kgroup->job_id == $otherfields->kpi_group_id) {
                                                 echo "selected";
                                             } ?>>
                                                <?= $kgroup->job; ?>
                                            </option>
                                        <?php }

                                        ?>
                                    </select>

                                    <br/>
                                        <?php
                
                                     if ($res == 0) { ?>
                                     
                                    <input type="hidden" id="changePassword" name="changepassword" value="on" readonly>
                                       <?php  }
                                        else { ?>
                                     <label for="changePassword">Click to Reset  Password a Previous Enrollment:</label>
        
                                     <input type="checkbox" id="changePassword" name="changepassword">
                                       <?php }
                                     ?>

                                    

                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php echo form_close(); ?>



                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


</div>