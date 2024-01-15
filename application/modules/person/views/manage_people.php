<div class="subheader">
    <h1 class="subheader-title">

    </h1>
</div>

<div class="col-md-12">

    <?php echo form_open_multipart(base_url('person/manage_people'), array('id' => 'person', 'class' => 'person','method'=>'get')); ?>
    <div class="row">

        <div class="form-group col-md-4">
            <label for="Facility">Facility:</label>
            <select class="form-control select2" name="facility">
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

    <br>
<button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>

</div>
<div class="form-group col-md-4">
      <br>
    <!-- <a href="<?php echo base_url() ?>person/enroll_facility/<?php if(isset($_GET['facility'])){ echo $_GET['facility']; }else{ echo $_SESSION['facility_id']; } ?>"
    class="btn btn-primary">Enroll Employees</a> -->

 </div>
    </div>
    <?php echo $links ?>

    <table class="table table-responsive">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Job</th>
                <th>Facility</th>
                <th>Supervisor</th>
                <th>Email</th>
                <th>Telephone</th>
                <th>Enroll</th>

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
                        <?=supervisor($employee->supervisor_id) ?>
                            
                    </td>


                        <td>
                        <?= $employee->email ?>

                    </td>

                    <td>
                        <?= $employee->mobile ?>

                    </td>
                    <td>
                    

                         <a href="#" data-toggle="modal" data-target="#supervisor">Add Supervisor & Enroll</a>
                         

                    </td>

            </form>


                    <!-- Modal center -->
                    <?php echo form_open_multipart(base_url('person/add_supervisor'), array('id' => 'person_details', 'class' => 'person_details', 'method' => 'post')); ?>
                                            <div class="modal fade" id="supervisor" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">
                                                                Edit & Enroll Employee - <?= $employee->surname . ' ' . $employee->firstname . ' ' . $employee->othername ?>

                                                                   </h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true"><i class="fal fa-times"></i></span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            
                                                          
                                                            <label for="supervisor"> Supervisor:(*)</label>
                                                            <select class="form-control" name="supervisor_id" required>
                                                                <option value="" >Select Supervisor</option>
                                                                <?php
                                                                    $supervisors = $this->db->query("SELECT * from ihrisdata_staging WHERE district_id='$employee->district_id' AND ihris_pid!='$employee->ihris_pid' order by surname ASC")->result();

                                                                    foreach ($supervisors as $supervisor){?>
                                                                <option value="<?=$supervisor->ihris_pid?>" <?php if($supervisor->ihris_pid==$employee->supervisor_id){ echo "selected";}?>    ><?=$supervisor->surname . ' ' . $supervisor->firstname.'- ('.$supervisor->facility.')'.'-'. 'Job';?></option>
                                                              <?php  }
                                                            
                                                                ?>
                                                            </select>
                                                       
                                                            <label for="email"> Staff Email:(*)</label>
                                                            <input type="email" name="email" class="form-control" required  value="<?php echo $employee->email; ?>">
                                                              <input type="hidden" name="ihris_pid" class="form-control" value="<?php echo $employee->ihris_pid; ?>">
                                                     
                                                           <label for="mobile"> Staff Phone Number:(*)</label>
                                                            <input type="text" name="mobile" class="form-control" required value="<?php echo $employee->mobile; ?>">

                                                            <label for="role"> Data Entry Role</label>
                                                        
                                                            <select class="form-control" name="data_role">
                                                            <option value="0" <?php if ($employee->data_role==0){ echo "selected";}?>>Deny</option>
                                                            <option value="1" <?php if ($employee->data_role == 1) {echo "selected"; } ?>>Allow</option>    
                                                             </select>
                                                    
                                                        </div>
                                                       
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
    </form>



                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


</div>


