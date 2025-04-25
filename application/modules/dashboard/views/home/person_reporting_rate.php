<style>
    .vertical {
        border-left: 6px solid blue;
        height: 200px;
        position: absolute;
    }

    .table {
        border-collapse: collapse;
    }

    .table th,
    .table td {
        padding: 0.3em;
        /* Adjust padding as needed */
        border: 1px solid #E1DDDC;
        /* Add borders */
        text-align: left;
        /* Center align text */
    }

    .table th {
        background-color: #f2f2f2;
        /* Background color for table headers */
    }

    .table th:first-child,
    .table td:first-child {
        text-align: left;
        /* Left align first column */
    }

    .table th[colspan],
    .table td[colspan] {
        background-color: #d9d9d9;
        /* Background color for colspan cells */
    }

    .table th[rowspan],
    .table td[rowspan] {
        vertical-align: middle;
        /* Vertically center rowspan cells */
    }

    /* Optional: Highlight even rows */
    .table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
        padding: 0.5rem !important;
    }
</style>
<div class=" mt-4">

    <div id="employee_data">
        <h2>Reporting Rates</h2>
        <?php
        // dd($this->session->userdata());
        $this->load->view('dashboard/home/partials/filters_person_rates') ?>

        <div class="col-md-12 text-align-center">
            <h4>Financial Year: <?php
            $sfy = $this->session->userdata('financial_year');
            echo $this->input->get('financial_year') ?? $sfy; ?></h4>
        </div>
        <?php echo $pagination ?>

        <?php foreach ($facilities as $facility): ?>
    <h3><?= $facility->facility ?> - <?= $financial_year ?></h3>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Employee</th>
                <th>KPI Group</th>
                <th>Position</th>
                <th>Quarter 1</th>
                <th>Quarter 2</th>
                <th>Quarter 3</th>
                <th>Quarter 4</th>
            </tr>
        </thead>
        <tbody>
        <?php $i = 1; ?>
        <?php foreach ($facility->staff as $staff): ?>
            <?php
                $pid = $staff->ihris_pid;
                $job_id = $staff->job_category_id;
                $total_kpis = $job_totals[$job_id] ?? 0;
            ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= $staff->surname . ' ' . $staff->firstname ?></td>
                <td><?= get_employee_cadre($job_id)->job ?? '-' ?></td>
                <td><?= get_employee_details($pid)->job ?? '-' ?></td>

                <?php foreach (['Q1', 'Q2', 'Q3', 'Q4'] as $quarter): ?>
                    <?php
                        $kpis_with_data = $reporting_rates[$pid][$quarter] ?? 0;
                        $rate = ($total_kpis > 0) ? ($kpis_with_data / $total_kpis) * 100 : null;

                        $color = "background-color: grey; color: white;";
                        if ($rate !== null) {
                            if ($rate < 75) $color = "background-color: #de1a1a; color: #FFF;";
                            elseif ($rate < 95) $color = "background-color: #FFA500; color: #FFF;";
                            elseif ($rate >= 95) $color = "background-color: #008000; color: #FFF;";
                        }
                    ?>
                    <td style="<?= $color ?>"><?= $kpis_with_data ?>/<?= $total_kpis ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endforeach; ?>


        
    </div>
</div>

<?php $this->load->view('dashboard/home/partials/excel_util') ?>