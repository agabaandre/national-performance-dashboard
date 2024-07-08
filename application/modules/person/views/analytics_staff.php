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
          <div class="form-group col-md-6">
            <label for="Facility">Staff:</label>
        
            <select class="form-control enroll_staff select2" name="ihris_pid" required style="width:100%">
              
            </select>
        </div>
<div class="form-group col-md-2">

    <br>
<button type="submit"   class="btn btn-info waves-effect waves-themed"><i class="fa fa-eye-open"></i>View</button>

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

                        

                        <br> <a href="<?php echo base_url() ?>person?ihris_pid=<?=urlencode($employee->ihris_pid); ?>&facility_id=<?=urlencode($employee->facility_id)?>&job_id=<?= urlencode($employee->kpi_group_id) ?>&supervisor_id=<?= urlencode($employee->supervisor_id) ?>&supervisor_id_2=<?= urlencode($employee->supervisor_id_2) ?>">Add Performance</a>
                      
                        <?php if ($this->session->userdata('user_type') == 'admin') { ?>   
                               <hr>
                        <a href="<?php echo base_url() ?>person/delete/<?php echo $employee->ihris_pid; ?>" style="color: pink">Delete Records</a>
                        <?php }?>

                    </td>

                
   



                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    </form>

</div>