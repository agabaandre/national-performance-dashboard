
    
    <style>
    .vertical {
      border-left: 6px solid blue;
      height: 200px;
      position:absolute;
    }
    .table {
    border-collapse: collapse;
    }

    .table th,
    .table td {
        padding: 0.3em; /* Adjust padding as needed */
        border: 1px solid #E1DDDC; /* Add borders */
        text-align: left; /* Center align text */
    }

    .table th {
        background-color: #f2f2f2; /* Background color for table headers */
    }

    .table th:first-child,
    .table td:first-child {
        text-align: left; /* Left align first column */
    }

    .table th[colspan],
    .table td[colspan] {
        background-color: #d9d9d9; /* Background color for colspan cells */
    }

    .table th[rowspan],
    .table td[rowspan] {
        vertical-align: middle; /* Vertically center rowspan cells */
    }

    /* Optional: Highlight even rows */
    .table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
        padding: 0.5rem !important;
    }

  
     
    
  </style>
    <div class=" mt-4">
        <div id="employee_data">
        <h2>Performance Report</h2>

        <?php 
       // dd($this->session->userdata());
        $this->load->view('dashboard/home/partials/filters')?>

        <?php if(!empty($this->input->get('kpi_group'))&&!empty($this->input->get('kpi_id'))){ ?>

        <?php $facilities = Modules::run('dashboard/home/get_facilities');
             // dd($facilities);

          foreach($facilities as $facility):
        ?>
        <div class="row mt-4">
            <div class="col">
                 <h3><table><tr><td col-span=7><?php echo $facility->facility; ?> - <?= $this->input->get('financial_year') ?? $sfy; ?>
                            <td>
                        </tr>
                    </table>
                </h3>
                 <table class="table table-bordered">
               
                    <tr>
                        <th colspan="2"><?php if(!empty($this->input->get('kpi_id'))) { echo @getkpi_info($this->input->get('kpi_id'))->short_name; }
                        
                        ?></th>
                        
                        <td colspan="3">Q1</td>
                      
                       
                        <td colspan="3">Q2</td>
                       
                        
                        <td colspan="3">Q3</td>
                       
                       
                        <td colspan="3">Q4</td>
                    </tr>
                    <tr>
                        <th>Staff </th>
                        <th>Numerator(N)/Denominator(D)</th>
                        <td>Data</td>
                        <td>Score</td>
                        <td>Target</td>
                        
                        <td>Data</td>
                        <td>Score</td>
                        <td>Target</td>
                      
                        <td>Data</td>
                        <td>Score</td>
                        <td>Target</td>
        
                        <td>Data</td>
                        <td>Score</td>
                        <td>Target</td>
                    </tr>
                   <?php 
                   $i=1;
                   foreach ($facility->staff as $staff): ?>
                    <tr> 
                        
                        <th rowspan="2">
                    
                        <?php echo $i++. '. '. $staff->surname . ' ' . $staff->firstname;
                            $kpi_id = $this->input->get('kpi_id');
                            $financial_year = $this->input->get('financial_year');
                            $ihris_id = $staff->ihris_pid;
                            $job_id = $staff->kpi_group_id;
                       
                            $q1_vals = Modules::run('dashboard/home/staff_performance', $ihris_id,$financial_year, 'Q1', $job_id);
                            $q2_vals = Modules::run('dashboard/home/staff_performance', $ihris_id,$financial_year,'Q2', $job_id);
                            $q3_vals = Modules::run('dashboard/home/staff_performance', $ihris_id,$financial_year,'Q3',$job_id);
                            $q4_vals = Modules::run('dashboard/home/staff_performance', $ihris_id,$financial_year,'Q4',$job_id);
                         
                    
                        
                        ?>
                    
                        </th>
                        <td><?php if(!empty($this->input->get('kpi_id'))) { echo "N: ". @getkpi_info($this->input->get('kpi_id'))->numerator; }?></td>
                        <td><?=$q1_vals->numerator?></td>
                       <td rowspan="2" <?php if(!empty($q1_vals->score)){  echo "style='font-weight:bold; color:#FFF; background:".getColorBasedOnPerformance($q1_vals->score,$q1_vals->data_target)."'";}?> >
                        <?= round($q1_vals->score, 0) ?>
                        </td>
                        <td rowspan=2><?= $q1_vals->data_target ?></td>
                        <td><?= $q2_vals->numerator ?>
                        </td>
                        <td rowspan="2" <?php if (!empty ($q2_vals->score)) {
                            echo "style='font-weight:bold; color:#FFF; background:" . getColorBasedOnPerformance($q2_vals->score, $q2_vals->data_target) . "'";
                        } ?>>
                            <?php if (!empty ($q2_vals->score)) {
                                echo round($q2_vals->score, 0);
                            } ?>
                        </td>
                        <td rowspan="2">
                            <?= $q2_vals->data_target ?>
                        </td>
                        <td>
                            <?= $q3_vals->numerator ?>
                        </td>
                        <td rowspan="2" <?php if (!empty ($q3_vals->score)) {
                            echo "style='font-weight:bold; color:#FFF; background:" . getColorBasedOnPerformance($q3_vals->score, $q3_vals->data_target) . "'";
                        } ?>>
                            <?php if (!empty ($q3_vals->score)) {
                                echo round($q3_vals->score, 0);
                            } ?>
                        </td>
                        <td rowspan="2">
                            <?= $q3_vals->data_target ?>
                        </td>
                        <td>
                            <?= $q4_vals->numerator ?>
                        </td>
                        <td rowspan="2" <?php if (!empty ($q4_vals->score)) {
                            echo "style='font-weight:bold; color:#FFF; background:" . getColorBasedOnPerformance($q4_vals->score, $q4_vals->data_target) . "'";
                        } ?>>
                            <?php if (!empty ($q4_vals->score)) {
                                echo round($q4_vals->score, 0);
                            } ?>
                        </td>
                        <td rowspan="2">
                            <?= $q4_vals->data_target ?>
                        </td>

                    </tr>
                    <tr style="border-bottom:2px solid #FDE693; !important">
                        
                        <td><?php if (!empty($this->input->get('kpi_id'))) {
                                    echo "D: ". @getkpi_info($this->input->get('kpi_id'))->denominator;
                                } ?>
                        </td>
                        <td><?= $q1_vals->denominator ?></td>
                        
                        <td><?= $q2_vals->denominator ?></td>
                        
                        <td><?= $q3_vals->denominator ?></td>
        
                        <td><?= $q4_vals->denominator ?></td>
                      
                       
                    </tr>
                  
                    <?php endforeach; ?>
                  
                </table>
                
            
            </div>
        </div>
    <?php endforeach; ?>

 </div>
</div>
 <?php } else{ ?>
<table class="table table-bordered mt-5 justify-content-between">
    <th><div class='m-auto color-danger-100 justify-content-between'>Please Select Job Category and KPI</div></th>
 </table>
 <?php } ?>



    <?php    $this->load->view('dashboard/home/partials/excel_util')?>