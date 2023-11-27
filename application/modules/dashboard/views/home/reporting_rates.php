







              

    <div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                KPI Reporting Rates
                </h2>
                <div class="panel-toolbar">
                    <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
                    <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
                    <!-- <button class="btn btn-panel" data-action="panel-close" data-toggle="tooltip" data-offset="0,10" data-original-title="Close"></button> -->
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">

                    <!-- datatable start -->
                    <table id="kpiTable" class="table table-striped table-bordered">
                                
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Subject Area</th>
                                    <th>Quater 1</th>
                                    <th>Quater 2</th>
                                    <th>Quater 3</th>
                                    <th>Qauter 4</th>
        
                                </tr>
                                </thead>
                                <tbody>
                                  <?php   
                                 $user_id = $this->session->userdata('ihris_pid');
                                 $job_id = get_field($user_id,'job_id');
                                  $subs = Modules::run('person/focus_areas',$job_id);
                                    $i = 1;
                                    ///anameties
                                  foreach ($subs as $sub):
                                    //dd($subs);
                                      $fy = $this->session->userdata('financial_year');
                                      $q1_val = Modules::run('dashboard/slider/get_reporting_rate',$sub->id,'Q1',$fy, $user_id, $job_id);
                                      $q2_val = Modules::run('dashboard/slider/get_reporting_rate',$sub->id, 'Q2',$fy, $user_id, $job_id);
                                      $q3_val = Modules::run('dashboard/slider/get_reporting_rate', $sub->id, 'Q3',$fy, $user_id, $job_id);
                                      $q4_val = Modules::run('dashboard/slider/get_reporting_rate', $sub->id, 'Q4',$fy, $user_id, $job_id);
                                      ?>
                                
                                <tr>
                                    
                                    <td><?php echo $i++;?></td>
                                    <td><a href="<?php echo base_url().'data/subject/'.$sub->id.'/'. $sub->subject_area?>"><?php echo $sub->subject_area;?></a></td>
                                    
                                    <td <?php echo $q1_val->color; ?>><?php echo $q1_val->report_status; ?></td>
                                    <td <?php echo $q2_val->color; ?>><?php echo $q2_val->report_status; ?></td>
                                    <td <?php echo $q3_val->color; ?>><?php echo $q3_val->report_status;  ?></td>
                                    <td <?php echo $q4_val->color; ?>><?php echo $q4_val->report_status; ?></td>

                                </tr>
                                <?php endforeach;?>

                                        
                               </tbody>
                           
                        </table>
                    <!-- datatable end -->
                </div>
            </div>
        </div>
    </div>
</div>






   <script>
  function get_indicators(val) {
    $.ajax({
      method: "GET",
      url: "<?php echo base_url(); ?>kpi/get_indicators",
            data: 'cat_data=' + val,
            success: function (data) {
                $("#indicators").html(data);
                console.log(data);
            }
        });

    }
</script>
<script>
    $(document).ready(function () {
        $('#kpiTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copyHtml5',
                    customize: function (doc) {
                        doc.defaultStyle = {
                            orientation: 'landscape'
                        };
                    }
                },
                {
                    extend: 'excelHtml5',
                    customize: function (xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        // Add style to cells to include background colors
                        $('row c', sheet).each(function () {
                            $(this).attr('s', '50'); // Add a custom style reference, e.g., 50
                        });
                    }
                },
                'csvHtml5',
                {
                    extend: 'pdfHtml5',
                    customize: function (doc) {
                        doc.defaultStyle = {
                            orientation: 'landscape'
                        };
                    }
                }
            ],
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            responsive: true,
            displayLength: 25,
            lengthChange: true
        });
    });
</script>