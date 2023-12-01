<div class="subheader">
    <h1 class="subheader-title">

    </h1>
</div>

<div class="col-md-12">

    <?php echo form_open_multipart(base_url('person/manage_people'), array('id' => 'person', 'class' => 'person')); ?>
    <div class="row">
        <div class="form-group col-md-6">
            <label for="NAME">Name:</label>
            <input type="text" class="form-control" name="name" placeholder="Health Worker Name" <?php echo $this->input->post('name'); ?> required>
        </div>

        <div class="form-group col-md-6">
            <label for="Facility">Facility:</label>
            <select class="form-control select2" name="facility">
                <option value=""> SELECT Facility</option> >
                <?php
                foreach ($facilities as $facility) { ?>
                    <option value="<?php echo $facility->facility_id; ?>" <?php if ($this->input->post('facility') == $facility->facility_id) {
                           echo "selected";
                       } ?>>
                        <?php echo $facility->facility ?>
                    </option>
                <?php } ?>
            </select>
        </div>


        <button type="submit" class="btn btn-primary">Submit</button>

    </div>

    <table class="table table-responsive">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Job</th>
                <th>Facility</th>
                <th>Email</th>
                <th>Telephone</th>
                <th>Select</th>

            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            foreach ($employees as $employee):

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
                        <?= $employee->telephone ?>

                    </td>
                    <td>
                        <a href="<?php echo base_url() ?>person/evaluation/<?php echo $employee->ihris_pid; ?>">Confirm</a>

                    </td>



                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    </form>

</div>

</div>