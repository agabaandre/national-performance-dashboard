<?php 
// Calculate previous financial year as default
$current_date = date('Y-m-d');
$current_year = date('Y', strtotime($current_date));
$next_year = $current_year + 1;
if (date('m-d', strtotime($current_date)) < '07-01') {
    // Before July 1st, previous FY is (current_year-2)-(current_year-1)
    $default_financial_year = ($current_year - 2) . '-' . ($current_year - 1);
} else {
    // After July 1st, previous FY is (current_year-1)-current_year
    $default_financial_year = ($current_year - 1) . '-' . $current_year;
}

// Get financial year from URL or use default
$selected_financial_year = $this->input->get("financial_year");
if (empty($selected_financial_year)) {
    $selected_financial_year = $default_financial_year;
}

// Get KPI group and KPI from URL
$selected_kpi_group = $this->input->get('kpi_group');
$selected_kpi_id = $this->input->get('kpi_id');
?>
<?php echo form_open_multipart(base_url('dashboard/slider/facility_reporting'), array('id' => 'preview', 'class' => 'preview', 'method' => 'get')); ?>
<div class="row">
    <!-- Financial Year - First (Most Important Filter) -->
    <div class="form-group col-md-4 col-sm-12">
        <label for="financial_year">Financial Year <span class="text-danger">(*)</span>:</label><br>
        <select class="form-control" name="financial_year" id="financial_year_filter" required style="height: 45px; font-size: 16px; padding: 8px 12px;">
            <option value="">Select Financial Year</option>
            <?php
            $startdate = "2022"; // Start of available financial years
            $enddate = intval(date('Y') + 1); // End of available financial years
            $years = range($startdate, $enddate);

            foreach ($years as $year) {
                $financial_year = $year . '-' . ($year + 1);
                $is_selected = ($selected_financial_year === $financial_year) ? 'selected' : '';
                ?>
                <option value="<?php echo htmlspecialchars($financial_year, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $is_selected; ?>>
                    <?php echo htmlspecialchars($financial_year, ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <!-- KPI Job Group / Cadre - Second -->
    <div class="form-group col-md-4 col-sm-12">
        <label for="kpi_group">KPI Job Group / Cadre:</label><br>
        <select class="form-control" name="kpi_group" id="kpi_group_filter" onchange="getkpis(this.value)" style="height: 45px; font-size: 16px; padding: 8px 12px;">
            <option value="">-- Select KPI Group --</option>
            <?php foreach ($kpigroups as $list) { 
                if (isset($list->job_id) && isset($list->job)) {
                    $is_selected = ($selected_kpi_group == $list->job_id) ? 'selected' : '';
                    ?>
                    <option value="<?php echo htmlspecialchars($list->job_id, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $is_selected; ?>>
                        <?php echo htmlspecialchars($list->job, ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                <?php }
            } ?>
        </select>
    </div>

    <!-- KPI - Third (Depends on KPI Group) -->
    <div class="form-group col-md-4 col-sm-12">
        <label for="kpi_id">KPI:</label><br>
        <select class="form-control performance_kpis select2" name="kpi_id" id="kpi_id_filter" style="width: 100% !important; height: 45px; font-size: 16px;">
            <option value="">-- Select KPI --</option>
            <?php if (!empty($selected_kpi_group)) {
                $kpis = getkpis_by_group($selected_kpi_group);
                if (!empty($kpis) && is_array($kpis)) {
                    foreach ($kpis as $kpi) {
                        if (isset($kpi->kpi_id) && isset($kpi->short_name)) {
                            $is_selected = ($selected_kpi_id == $kpi->kpi_id) ? 'selected' : '';
                            ?>
                            <option value="<?php echo htmlspecialchars($kpi->kpi_id, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $is_selected; ?>>
                                <?php echo htmlspecialchars($kpi->short_name, ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php }
                    }
                }
            } ?>
        </select>
    </div>
</div>

<!-- Action Buttons Row - Below Select Fields -->
<div class="row mt-3">
    <div class="form-group col-md-12 col-sm-12">
        <button type="submit" class="btn btn-primary waves-effect waves-themed" style="padding: 10px 20px; font-size: 16px;">
            <i class="fa fa-search"></i> Submit
        </button>
        <a href="<?php echo base_url() ?>dashboard/slider/facility_reporting" class="btn btn-secondary waves-effect waves-themed" style="padding: 10px 20px; font-size: 16px;">
            <i class="fa fa-refresh"></i> Reset
        </a>
        <button type="button" id="export_button" class="btn btn-info waves-effect waves-themed" style="padding: 10px 20px; font-size: 16px;">
            <i class="fa fa-download"></i> Export
        </button>
    </div>
</div>
<?php echo form_close(); ?>

<script>
    function getkpis(val) {
        $.ajax({
            method: "GET",
            url: "<?php echo base_url(); ?>person/getkpis",
            data: { kpi_group: val },
            success: function (data) {
                console.log(data);
                $(".performance_kpis").html('<option value="">Select KPI</option>' + data);
            }
        });
    }

    $(document).ready(function () {
        var kpiGroup = '<?php echo $this->input->get('kpi_group'); ?>';
        if (kpiGroup) {
            getkpis(kpiGroup);
        }
        
        // Initialize Select2 for KPI dropdown with larger size
        if ($('.performance_kpis.select2').length) {
            $('.performance_kpis.select2').select2({
                width: '100%',
                dropdownAutoWidth: true,
                placeholder: '-- Select KPI --',
                allowClear: true
            });
        }
    });
</script>