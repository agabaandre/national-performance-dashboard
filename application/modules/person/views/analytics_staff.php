<div class="subheader">
    <h1 class="subheader-title"></h1>
</div>

<div class="col-md-12">
    <?php echo form_open_multipart(base_url('person/performance_list'), array('id' => 'person', 'class' => 'person', 'method' => 'get')); ?>
    <div class="row">
        <div class="form-group col-md-4">
            <label for="Facility">Facility:</label>
            <select class="form-control select2" name="facility" onchange="getEnrollStaff(this.value)" style="width:100% !important;">
                <option value="">SELECT Facility</option>
                <?php foreach ($facilities as $facility) { ?>
                    <option value="<?php echo $facility->facility_id; ?>" <?php if ($this->input->get('facility') == $facility->facility_id) { echo "selected"; } ?>>
                        <?php echo $facility->facility ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group col-md-6">
            <label for="Staff">Staff:</label>
            <select class="form-control enroll_staff select2" name="ihris_pid" required style="width:100% !important"></select>
        </div>
        <div class="form-group col-md-2">
            <br>
            <button type="submit" class="btn btn-info waves-effect waves-themed"><i class="fa fa-eye-open"></i>View</button>
        </div>
    </div>
    <?php echo $links; ?>

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
            <?php $i = 1; foreach ($employees as $employee): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= $employee->surname . ' ' . $employee->firstname . ' ' . $employee->othername ?></td>
                    <td><?= $employee->job ?></td>
                    <td><?= $employee->facility ?></td>
                    <td><?= $employee->email ?></td>
                    <td><?= $employee->mobile ?></td>
                    <td>
                        <a href="<?php echo base_url() ?>person?ihris_pid=<?= urlencode($employee->ihris_pid); ?>&facility_id=<?= urlencode($employee->facility_id) ?>&job_id=<?= urlencode($employee->kpi_group_id) ?>&supervisor_id=<?= urlencode($employee->supervisor_id) ?>&supervisor_id_2=<?= urlencode($employee->supervisor_id_2) ?>">Add Performance</a>
                        <?php if ($this->session->userdata('user_type') == 'admin') { ?>   
                            <hr>
                            <a href="javascript:void(0);" class="text-danger delete-link" data-toggle="modal" data-target="#deleteModal" data-url="<?php echo base_url() ?>person/delete/<?php echo urlencode($employee->ihris_pid); ?>?facility_id=<?= urlencode($employee->facility_id) ?>">Delete Records</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </form>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this record?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a href="" id="confirmDelete" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#deleteModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var url = button.data('url');
            var modal = $(this);
            modal.find('#confirmDelete').attr('href', url);
        });
    });
</script>
