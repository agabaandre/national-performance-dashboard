<?php echo form_open_multipart(base_url('dashboard/slider/facility_reporting'), array('id' => 'preview', 'class' => 'preview', 'method' => 'get')); ?>
<div class="row">

    <div class="form-group col-md-3 col-sm-12">

        <label for="focus_areas">Focus Areas:</label>
        <select class="form-control select2" name="kpi_group" onchange="getkpis(this.value)">
            <option value="">Select Focus Area</option>
            <?php foreach ($kpigroups as $list) {

                ?>
                <option value="<?php echo $list->job_id; ?>" <?php if ($this->input->get('kpi_group') == $list->job_id) {
                       echo "selected";
                   } ?>><?php echo $list->job; ?>
                </option>
            <?php } ?>

        </select>
    </div>
       <div class="form-group col-md-6 col-sm-12">

        <label for="focus_areas">KPI:</label>
        <select class="form-control select2 performance_kpis" name="kpi_id" id="">
             <option value="<?php echo $this->input->get('kpi_id') ?>">
                <?=getkpiName($this->input->get('kpi_id')) ?>
            </option>
          
           
    
        </select>
    </div>
    <div class="form-group col-md-3 col-sm-12">
        <label for="financial_year">Financial Year:(*)</label>

            <select class="form-control selectize" name="financial_year" required>
    <option value="">Select Financial Year</option>
    <?php
            
                $current_date = date('Y-m-d');
                $current_year = date('Y', strtotime($current_date));
                $next_year = $current_year + 1;
                if (date('m-d', strtotime($current_date)) < '06-30') {
        
                    $current_year -= 1;
                    $next_year -= 1;
                }
                $current_financial_year = $current_year . '-' . $next_year;
                $startdate = "2022"; // Start of available financial years
                $enddate = intval(date('Y') + 1); // End of available financial years
                $years = range($startdate, $enddate);

                foreach ($years as $year) {
                    $financial_year = $year . '-' . ($year + 1);
                    ?>
                    <option value="<?php echo $financial_year; ?>" <?php if ($current_financial_year === $financial_year) {
                           echo "selected";
                       } ?>>
                        <?php echo $financial_year; ?>
                    </option>
                <?php } ?>
            </select>

  
    </div>

</div>

  <button type="submit" class="btn btn-info waves-effect waves-themed"><i class=""></i>Submit</button>

<?php echo form_close();?>