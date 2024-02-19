<div class="subheader">
    <h1 class="subheader-title">

    </h1>
</div>

<div class="col-md-12">

    <?php echo form_open_multipart(base_url('person/performance_list'), array('id' => 'person', 'class' => 'person','method'=>'get')); ?>
    <div class="row">

        <div class="form-group col-md-4">
            <label for="Facility">Facility:</label>
            <select class="form-control selectize" name="facility" onchange="getEnrollStaff(this.value)">
                <option value=""> SELECT Facility</option> >
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
        
            <select class="form-control enroll_staff select2" name="ihris_pid" required style="width:100%">
              
            </select>
        </div>
<div class="form-group col-md-4">

    <br>
<button type="submit" name="submit" value="submit" class="btn btn-primary"><i class="fa fa-eye-open"></i>View</button>

</div>

    </div>
    <?php echo $links;?>

      <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Job</th>
                <th>Facility</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Actions</th>

            </tr>
        </thead>
        <tbody>
            <?php
            //
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
                        <?= $employee->email ?>

                    </td>

                    <td>
                        <?php echo $employee->mobile ?>

                    </td>
                    <td>

                            <?php if ($this->session->userdata('user_type') == 'admin') { 
                            $res = $this->db->where('ihris_pid', $employee->ihris_pid)->get('ihrisdata')->num_rows();
                            $action = ($res == 0) ? 'Enroll' : 'Edit Enrollment';
                            ?>
                            <a href="#" data-toggle="modal" data-target="#supervisor<?= $employee->id; ?>">
                                <?= $action ?>
                            </a>
                            <?php }?>

                           <br> <a href="<?php echo base_url() ?>person?ihris_pid=<?=urlencode($employee->ihris_pid); ?>&facility_id=<?=urlencode($employee->facility_id)?>&job_id=<?= urlencode($employee->kpi_group_id) ?>&supervisor_id=<?= urlencode($employee->supervisor_id) ?>&supervisor_id_2=<?= urlencode($employee->supervisor_id_2) ?>">Add Performance</a>

                        <!-- <a href="<?php echo base_url() ?>person/evaluation/<?php echo $employee->ihris_pid; ?>">Delete Record</a> -->

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


                                    <label for="supervisor"> Supervisor 1:(*)</label>
                                    <input type="hidden" name="supervisor_id" class="form-control" id="supervisor_id"
                                        value="<?php $otherfields->supervisor_id ?>">


                                    <select class="form-control selectize" id="supervisor_name"
                                        onchange="supervisor(this.value)" style="width:100%;" required>

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
                                    <input type="hidden" name="supervisor_id_2" class="form-control" id="supervisor_2"
                                        value="<?php $otherfields->supervisor_id_2 ?>">

                                    <select class="form-control selectize" id="supervisor_name2"
                                        onchange="supervisor_2(this.value)" style="width:100%;">

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

                                     <label for="supervisor"> Job</label>
                                    <input type="hidden" name="job_id" class="form-control" id="job_id_2">
                                    
                                    <select class="form-control selectize" onchange="appendjob(this.value)" style="width:100%;">
                                    
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


                                    <label for="supervisor"> Facility</label>
                                    <input type="hidden" name="facility_id" class="form-control" id="facility_id_2">
                                    
                                    <select class="form-control selectize" onchange="appendfacility(this.value)" style="width:100%;">
                                    
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

    </form>

</div>