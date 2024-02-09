<div class="subheader">
    <h1 class="subheader-title">

    </h1>
</div>

<div class="col-md-12">

    <?php echo form_open_multipart(base_url('person/ihrislink'), array('id' => 'person', 'class' => 'person','method'=>'get')); ?>
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
                <th>Surname </th>
                <th>iHRIS Surname </th>
                <th>Firstname </th>
                <th>iHRIS Firstname </th>
                <th>Job</th>
                <th>iHRIS Job</th>
                <th>NIN</th>
                <th>iHRIS NIN</th>
                <th>Facility</th>
                <th>iHRIS Facility</th>
                <th>Email</th>
                <th>iHRIS Email</th>
                <th>Telephone</th>
                <th>iHRIS Telephone</th>
                <th>Actions</th>



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
                        <?= $employee->surname ?>

                    </td>
                    
                    <td>
                        <?= $employee->ihris_surname ?>
                    
                    </td>
                    <td>
                        <?= $employee->firstname ?>
                    
                     </td>
                    <td>
                        <?= $employee->ihris_firstname ?>
                        
                     </td>

                    <td>
                        <?= $employee->job ?>

                    </td>
                    <td>
                        <?= $employee->ihris_job ?>
                    
                    </td>

                    <td>
                        <?= $employee->nin ?>
                    </td>

                    <td>
                        <?= $employee->ihris_nin ?>
                    </td>


                    <td>
                        <?= $employee->facility ?>

                    </td>

                    <td>
                        <?= $employee->ihris_facility ?>
                        
                     </td>

                    <td>
                        <?= $employee->email ?>

                    </td>
                    <td>
                        <?= $employee->ihris_email ?>
                    
                    </td>

                    <td>
                        <?= $employee->telephone ?>
                    
                    </td>
                    <td>
                        <?= $employee->ihris_telephone ?>
                    
                    </td>

                    <td>

                    <button class="btn btn-outline-secondary" >Match</button>
                    <button class="btn btn-flickr" >Break Match</button>

                    </td>
                      
                    



                            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    </form>

</div>