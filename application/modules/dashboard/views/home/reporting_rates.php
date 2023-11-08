

                <div class="panel panel-bd lobidrag col-md-12 row">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h2>KPI Reporting Rates</h2>
                        </div>
                    </div>
                    <div class="panel-body">  
                    <div class="text-align-center"><h4>Financial Year: <?php echo $this->session->userdata('financial_year'); ?></h4>   </div>
                     
                               
                                <table id="subject" class="table table-responsive table-striped table-bordered">
                                
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


                                            <?php endforeach;?>

                                                    
                                           </tbody>
                                       
                                    </table>
                        </div>
                  
                    <div class="panel-footer">
                     
                    </div>
              </div>
   