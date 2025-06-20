<?php
// Fetch distinct financial years
$financial_years = $this->db->query("
    SELECT DISTINCT financial_year 
    FROM performanace_data 
    WHERE financial_year IS NOT NULL 
    ORDER BY financial_year DESC
")->result();

// Fetch periods
$periods = ['Q1', 'Q2', 'Q3', 'Q4'];

// Fetch distinct employees
$employees = $this->db->query("
    SELECT DISTINCT ihris_pid, CONCAT(surname, ' ', firstname) AS employee_name 
    FROM performanace_data 
    WHERE surname IS NOT NULL AND firstname IS NOT NULL
    ORDER BY surname ASC
")->result();

// Fetch facilities only if admin
$facilities = [];
if ($this->session->userdata('user_type') == 'admin') {
    $facilities = $this->db->query("
        SELECT DISTINCT facility 
        FROM performanace_data 
        WHERE facility IS NOT NULL 
        ORDER BY facility ASC
    ")->result();
}
?>

<style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
    }
    th {
        background-color: #f8f9fa;
    }
    .employee-name {
        font-weight: bold;
        background-color: #e2e3e5;
        text-align: center;
    }
    .filter-form {
        background: #f8f9fa;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 5px;
    }
</style>

<!-- Filter Form -->
<form class="filter-form" method="get" action="<?= base_url('person/employee_reporting') ?>">
<div class="row">
    <div class="col-md-3 mb-3">
        <label>Financial Year</label>
        <select name="financial_year" class="form-control w-100">
            <option value="">-- All Years --</option>
            <?php foreach ($financial_years as $year): ?>
                <option value="<?= htmlspecialchars($year->financial_year) ?>" <?= ($this->input->get('financial_year') == $year->financial_year) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($year->financial_year) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-2 mb-3">
        <label>Period</label>
        <select name="period" class="form-control w-100">
            <option value="">-- All Periods --</option>
            <?php foreach ($periods as $period): ?>
                <option value="<?= $period ?>" <?= ($this->input->get('period') == $period) ? 'selected' : '' ?>>
                    <?= $period ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label>Person (Employee)</label>
        <select name="ihris_pid" class="form-control select2 w-100">
            <option value="">-- All Employees --</option>
            <?php foreach ($employees as $emp): ?>
                <option value="<?= htmlspecialchars($emp->ihris_pid) ?>" <?= ($this->input->get('ihris_pid') == $emp->ihris_pid) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($emp->employee_name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <?php if ($this->session->userdata('user_type') == 'admin'): ?>
        <div class="col-md-3 mb-3">
            <label for="facility_id">Facility:</label>
            <select class="form-control select2 w-100" name="facility_id">
                <option value="">-- Select Facility --</option>
                <?php
                $facs = $this->db->query("
                    SELECT DISTINCT d.facility_id, d.facility AS facility_name
                    FROM ihrisdata d
                    WHERE d.facility_id IN (
                        SELECT DISTINCT facility FROM new_data
                    )
                    ORDER BY d.facility ASC
                ")->result();

                foreach ($facs as $f): ?>
                    <option value="<?= $f->facility_id ?>" <?= ($this->input->get('facility_id') == $f->facility_id) ? 'selected' : '' ?>>
                        <?= $f->facility_name ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php endif; ?>
</div>


    <div class="row mt-3">
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary btn-block">Apply Filters</button>
        </div>
        <?php if (!empty($performance_data)): ?>
        <div class="col-md-2">
            <button type="submit" name="export" value="1" class="btn btn-success btn-block">Export to Excel</button>
        </div>
        <?php endif; ?>
    </div>
</form>

<!-- Performance Table -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Employee Name</th>
            <th>KPI</th>
            <th>Definition</th>
            <th>Report</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($performance_data)): ?>
            <?php
            $current_employee = '';
            $employee_data = [];
            $rowspan = 0;
            foreach ($performance_data as $row):
                if ($current_employee != $row['employee_name']):
                    if ($current_employee != ''):
                        echo '<tr><td class="employee-name" rowspan="' . $rowspan . '">' . htmlspecialchars($current_employee) . '</td>';
                        foreach ($employee_data as $index => $data):
                            if ($index > 0) echo '<tr>';
                            echo '<td>' . htmlspecialchars($data['kpi_name']) . '</td>';
                            echo '<td><strong>Numerator:</strong> ' . htmlspecialchars($data['numerator_description']) . '<br><strong>Denominator:</strong> ' . htmlspecialchars($data['denominator_description']) . '</td>';
                            echo '<td style="color:#FFF; background-color:' . getColorBasedOnPerformance($data['score'], $data['data_target']) . '">
                                <strong>Financial Year:</strong> ' . htmlspecialchars($data['financial_year']) . '<br>
                                <strong>Period:</strong> ' . htmlspecialchars($data['period']) . '<br>
                                <strong>Numerator:</strong> ' . htmlspecialchars($data['numerator']) . '<br>
                                <strong>Denominator:</strong> ' . htmlspecialchars($data['denominator']) . '<br>
                                <strong>Score:</strong> ' . round($data['score']) . '<br>
                                <strong>Comment:</strong> ' . htmlspecialchars($data['comment']) . '
                            </td>';
                            echo '</tr>';
                        endforeach;
                    endif;
                    $current_employee = $row['employee_name'];
                    $employee_data = [];
                    $rowspan = 0;
                endif;
                $employee_data[] = $row;
                $rowspan++;
            endforeach;

            // Print last employee
            if ($current_employee != ''):
                echo '<tr><td class="employee-name" rowspan="' . $rowspan . '">' . htmlspecialchars($current_employee) . '</td>';
                foreach ($employee_data as $index => $data):
                    if ($index > 0) echo '<tr>';
                    echo '<td>' . htmlspecialchars($data['kpi_name']) . '</td>';
                    echo '<td><strong>Numerator:</strong> ' . htmlspecialchars($data['numerator_description']) . '<br><strong>Denominator:</strong> ' . htmlspecialchars($data['denominator_description']) . '</td>';
                    echo '<td style="color:#FFF; background-color:' . getColorBasedOnPerformance($data['score'], $data['data_target']) . '">
                        <strong>Financial Year:</strong> ' . htmlspecialchars($data['financial_year']) . '<br>
                        <strong>Period:</strong> ' . htmlspecialchars($data['period']) . '<br>
                        <strong>Numerator:</strong> ' . htmlspecialchars($data['numerator']) . '<br>
                        <strong>Denominator:</strong> ' . htmlspecialchars($data['denominator']) . '<br>
                        <strong>Score:</strong> ' . round($data['score']) . '<br>
                        <strong>Comment:</strong> ' . htmlspecialchars($data['comment']) . '
                    </td>';
                    echo '</tr>';
                endforeach;
            endif;
            ?>
        <?php else: ?>
            <tr><td colspan="4" class="text-center">No performance data found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php
// Handle export if requested
if ($this->input->get('export') == 1 && !empty($performance_data)) {
    render_csv_data($performance_data, 'employee_report_' . date('Y_m_d_His') . '.csv');
}
?>