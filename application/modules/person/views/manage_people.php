<div class="col-md-12">
    <?php echo form_open_multipart(base_url('person/manage_people'), array('id' => 'search_person', 'class' => 'search_person', 'method' => 'get')); ?>
    <div class="row">
        <!-- Facility Dropdown -->
        <div class="form-group col-md-4">
            <label for="Facility">Facility:</label>
            <select class="form-control selectize" name="facility" onchange="getFacStaff(this.value)">
                <option value=""> Search Facility</option>
                <?php foreach ($facilities as $facility): ?>
                    <option value="<?= $facility->facility_id ?>" <?= ($this->input->get('facility') == $facility->facility_id) ? "selected" : "" ?>>
                        <?= $facility->facility ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <!-- Staff Dropdown -->
        <div class="form-group col-md-6">
            <label for="Facility">Staff:</label>
            <select class="form-control facility_staff select2" name="ihris_pid"  style="width:100%" required></select>
        </div>
        <!-- Manage Button and Add New Employee Button -->
        <div class="form-group col-md-2">
            <br>
            <button type="submit" class="btn btn-info waves-effect waves-themed">Manage</button>
            <?php if (!empty($this->input->get('facility'))): ?>
            <button type="button" class="btn btn-info waves-effect waves-themed mt-2" data-toggle="modal" data-target="#addsupervisor">
               Add New Employee
            </button>
            <?php endif; ?>
        </div>
    </div>
    <?php echo form_close(); ?>

    <!-- Employee Table -->
    <?php echo $links ?>
    <table class="table">
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
               $i=1;
            foreach ($employees as $employee): ?>
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
                        $res = $this->db->where('ihris_pid', $employee->ihris_pid)->get('ihrisdata')->num_rows();
                        $action = ($res == 0) ? 'Enroll' : 'Edit Enrollment';
                        ?>
                        <a href="#" data-toggle="modal" data-target="#supervisor<?= $employee->id; ?>">
                            <?= $action ?>
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
                                    @$ihris_pid = $employee->ihris_pid;
                                    @$otherfields=$this->db->query("SELECT * from ihrisdata where ihris_pid='$ihris_pid'")->row();
                                    ?>
                                    <?php
                                        $res = $this->db->where('ihris_pid', $employee->ihris_pid)->get('ihrisdata')->num_rows();
                                        $action = ($res == 0) ? 'Enroll' : 'Edit Enrollment';
                                        ?>
                                        <a href="#" data-toggle="modal" data-target="#supervisor<?= $employee->id; ?>">
                                            <?= $action ?>
                                        </a>


                                        <label for="supervisor"> Supervisor 1:(*)</label>
                                        <!-- <input type="hidden" name="supervisor_id" class="form-control" id="supervisor_id"
                                            value="<?php $otherfields->supervisor_id ?>"> -->


                                    <select class="form-control select2" name="supervisor_id"  id="supervisor_name"
                                       style="width:100%;" required>

                                        <option value=""> Search Supervisor</option>
                                        <?php
                                        foreach ($supervisors as $supervisor) {
    
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
                                    <!-- <input type="hidden"  class="form-control" id="supervisor_2"
                                        value="<?php $otherfields->supervisor_id_2 ?>"> -->

                                    <select class="form-control select2" name="supervisor_id_2" id="supervisor_name2"
                                        style="width:100%;">

                                        <option value=""> Search Supervisor</option>
                                        <?php

                                        foreach ($supervisors as $supervisor) {
                                            ?>
                                            <option value="<?= $supervisor->ihris_pid ?>" <?php if ($supervisor->ihris_pid == $otherfields->supervisor_id_2) {
                                                echo "selected";
                                            } ?>>
                                                <?php echo $supervisor->surname . ' ' . $supervisor->firstname . '- (' . $supervisor->facility . ')' . '-' . $supervisor->job; ?>
                                            </option>
                                        <?php }

                                        ?>
                                    </select>

                                     <label for=""> Job</label>
                                         <div style="display:none !important;">
                                    <!-- <input type="text"  class="form-control" id="job_id_2" style="display;none" value="<?= $job->job_id ?>"> -->
                                        </div>
                                    <select class="form-control select2"  name="job_id" style="width:100%;">
                                    
                                        <option value="">Select Job</option>
                                        <?php

                                        foreach ($jobs  as $job) {
                                            ?>
                                            <option value="<?=$job->job_id ?>" <?php if ($job->job_id == $employee->job_id) {
                                                         echo "selected";
                                                     } ?>>
                                                <?php echo $job->job; ?>
                                            </option>
                                        <?php }

                                        ?>
                                    </select>


                                    <label for=""> Facility</label>
                                    <div style="display:none !important;">
                                    <!-- <input type="text"  class="form-control" id="facility_id_2"> -->
                                    </div>
                                    
                                    <select class="form-control select2" name="facility_id" style="width:100%;">
                                    
                                        <option value="">Select Facility</option>
                                        <?php

                                        foreach ($facilities  as $facility) {
                                           // dd($otherfields);
                                            ?>
                                            <option value="<?= $facility->facility_id ?>" <?php if ($facility->facility_id == $employee->facility_id) {
                                            echo "selected"; } ?>>
                                                <?php echo $facility->facility; ?>
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
                                    <label for="isactive" class="form-label">Is Active?</label>
                                  <input type="checkbox" id="isactive" name="is_active" value="1" <?=$employee->is_active == 1 ? 'checked' : ''; ?>>

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

<!-- Add new employee -->
<?php echo form_open_multipart(base_url('person/add_supervisor'), array('id' => 'update_employee', 'class' => 'update_employee', 'method' => 'get')); ?>
<div class="modal fade" id="addsupervisor" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add & Enroll Employee</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
               <div class="modal-body">
                                    <label for="email"> Surname:(*)</label>
                                    <input type="text" name="surname" class="form-control" required value="">
                                          <label for="text"> Firstname:(*)</label>
                                    <input type="text" name="firstname" class="form-control" required value="">
                                       <label for="text"> National ID (NIN):(*)</label>
                                    <input type="text" name="nin" class="form-control" required value="">
                                    <?php
                                    ?>
                                    <label for="supervisor"> Supervisor 1:(*)</label>
                                    <input type="hidden" name="add_new" class="form-control" value="add_new">


                                    <select class="form-control select2" id="supervisor_id"style="width:100%;" required>

                                        <option value=""> Search Supervisor</option>
                                        <?php
                                        foreach ($supervisors as $supervisor) {
                                            ?>
                                            <option value="<?= $supervisor->ihris_pid ?>">
                                                <?php echo $supervisor->surname . ' ' . $supervisor->firstname . '- (' . $supervisor->facility . ')' . '-' . $supervisor->job; ?>
                                            </option>
                                        <?php }

                                        ?>
                                    </select>


                                    <label for="supervisor"> Supervisor 2 : (Optional)</label>
                                    <select class="form-control select2" id="supervisor_id_2"
                                       style="width:100%;">

                                        <option value=""> Search Supervisor</option>
                                        <?php

                                        foreach ($supervisors as $supervisor) {
                                            ?>
                                            <option value="<?= $supervisor->ihris_pid ?>">
                                                <?php echo $supervisor->surname . ' ' . $supervisor->firstname . '- (' . $supervisor->facility . ')' . '-' . $supervisor->job; ?>
                                            </option>
                                        <?php }

                                        ?>
                                    </select>

                                    <label for="supervisor"> Job</label>
                        
                                    
                                    <select class="form-control selectize" name="job_id" style="width:100%;">
                                    
                                        <option value="">Select Job</option>
                                        <?php

                                        foreach ($jobs  as $job) {
                                            ?>
                                            <option value="<?=$job->job_id ?>">
                                                <?php echo $job->job; ?>
                                            </option>
                                        <?php }

                                        ?>
                                    </select>


                                    <label for="supervisor"> Facility</label>
                                  
                                    
                                    <select class="form-control select2" name="facility_id" onchange="appendfacility_2(this.value)" style="width:100%;">
                                    
                                        <option value="">Select Facility</option>
                                        <?php

                                        foreach ($facilities  as $facility) {
                                            ?>
                                            <option value="<?= $facility->facility_id ?>">
                                                <?php echo $facility->facility; ?>
                                            </option>
                                        <?php }

                                        ?>
                                    </select>


                                    <label for="email"> Staff Email:(*)</label>
                                    <input type="email" name="email" class="form-control" required
                                        value="">
                                    <input type="hidden" name="ihris_pid" class="form-control"
                                        value="<?php echo md5(date('Y-m-d H:i:s'));?>">

                                    <label for="mobile"> Staff Phone Number:(*)</label>
                                    <input type="text" name="mobile" class="form-control" required value="">
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
                    
                                        foreach ($kpigroups as $kgroup) { ?>
                                            <option value="<?= $kgroup->job_id; ?>" <?php if ($kgroup->job_id == $otherfields->kpi_group_id) {
                                                 echo "selected";
                                                  } ?>>
                                                <?= $kgroup->job; ?>
                                            </option>
                                        <?php }

                                        ?>
                                    </select>
                                    <input type="hidden" id="changePassword" name="changepassword" value="on" readonly>
                               </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>




