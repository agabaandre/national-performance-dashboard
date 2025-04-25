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

        <?php //$facilities = Modules::run('dashboard/home/get_facilities');
        //  dd($facilities);
        
        foreach ($facilities as $facility):
            ?>
            <h3><table><tr><td col-span=7><?php echo $facility->facility; ?> - <?= $this->input->get('financial_year') ?? $sfy; ?><td></tr></table></h3>

            <table class="table table-bordered">
                

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>KPI Group</th>
                        <th>Position</th>
                        <th>Quater 1</th>
                        <th>Quater 2</th>
                        <th>Quater 3</th>
                        <th>Quater 4</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($facility->staff as $staff):


                        $kpi_id = $this->input->get('kpi_id');
                        if (!empty($this->input->get('financial_year'))) {

                            $fy = $this->input->get('financial_year');

                        } else {
                            $fy = $sfy;

                        }
                        // print_r($fy);
                        $ihris_id = $staff->ihris_pid;
                        $job_id = $staff->job_category_id;
                        $q1_val = Modules::run('dashboard/slider/get_reporting_rate', $ihris_id, 'Q1', $fy, $job_id);
                        $q2_val = Modules::run('dashboard/slider/get_reporting_rate', $ihris_id, 'Q2', $fy, $job_id);
                        $q3_val = Modules::run('dashboard/slider/get_reporting_rate', $ihris_id, 'Q3', $fy, $job_id);
                        $q4_val = Modules::run('dashboard/slider/get_reporting_rate', $ihris_id, 'Q4', $fy, $job_id);
                        ?>

                        <tr>

                            <td><?php echo $i++; ?></td>
                            <td><a
                                    href="<?php echo base_url() . 'data/subject/' . $ihris_id . '/' . $staff->surname . ' ' . $staff->firstname ?>"><?php echo $staff->surname . ' ' . $staff->firstname; ?></a>
                            </td>
                            <td><?= get_employee_cadre($job_id)->job ?></a>
                            </td>
                            <td><?= get_employee_details($ihris_id)->job ?></a>
                            </td>
                            <td <?php echo $q1_val->color; ?>><?php echo $q1_val->report_status; ?></td>
                            <td <?php echo $q2_val->color; ?>><?php echo $q2_val->report_status; ?></td>
                            <td <?php echo $q3_val->color; ?>><?php echo $q3_val->report_status; ?></td>
                            <td <?php echo $q4_val->color; ?>><?php echo $q4_val->report_status; ?></td>


                        <?php endforeach; ?>


                </tbody>

            </table>
        <?php endforeach; ?>
    </div>
</div>

<?php $this->load->view('dashboard/home/partials/excel_util') ?>