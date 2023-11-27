

                <div class="row">
                    <div class="col-xl-12">
                        <div id="panel-1" class="panel">
                            <div class="panel-hdr">
                                <h2>
                                  Performance - <?=$this->uri->segment(4);?>
                                </h2>
                                <div class="panel-toolbar">
                                    <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
                                    <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
                                    <!-- <button class="btn btn-panel" data-action="panel-close" data-toggle="tooltip" data-offset="0,10" data-original-title="Close"></button> -->
                                </div>
                            </div>
                            <div class="panel-container show">
                                <div class="panel-content">
                                    <!-- <div class="container">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop" style="margin-bottom:3px;"><i class="fa fa-plus">
                                            </i>Add Institution Category
                                        </button>
                                        <br>
                                        <br>
                                    </div> -->

                                    <!-- datatable start -->
                                    <?php
                                  $subdata = explode('-', $_GET['subject_area']);
                                  // print_r($subdata);

                                  $sub = $this->uri->segment(3);
                                  $subn = $this->uri->segment(4);

                                  echo form_open_multipart(base_url('data/subject/'.$sub.'/'.$subn), array('id' => 'filter', 'class' => 'form-horizontal','method'=>'get')); ?>

                                  <div class="col-md-12"> 
                                  <div class="form-group">
                                      <label>Job: </label>
                                      <select class="form-control" name="job_id" <?php if (($this->session->userdata('user_type') != 'admin')) { echo 'disabled';} ?>>


                              <?php 
                              $jobs = $this->db->query('SELECT distinct job_id, job from ihrisdata')->result();
                                foreach($jobs as $job):
                                      $selected = ((get_field($this->session->userdata('ihris_pid'), 'job_id')) == $job->job_id)?'selected':'';
                              ?>
                                <option <?php echo $selected; ?> value="<?php echo $job->job_id; ?>" >
                                          <?php echo $job->job; ?>
                                </option>
                              <?php endforeach; ?>
                          </select>
                      </div>
                    </div>

                    <div class="col-md-12"> 
                    <div class="form-group">
                        <label>Focus Area: </label>
                        <select class="form-control" name="subject_area" <?php if (($this->session->userdata('user_type') != 'admin')) { echo 'disabled';} ?>>


                    <?php 
                    $subjects = Modules::run('person/focus_areas', get_field($this->session->userdata('ihris_pid'), 'job_id'));

                    foreach ($subjects as $subject):          
                                $selected = ($sub == $subject->id)?'selected':'';
                              ?>
                                <option <?php echo $selected; ?> value="<?php echo $subject->id.'-'.$subject->subject_area ?>" >
                                          <?php echo $subject->subject_area; ?>
                                </option>
                              <?php endforeach; ?>
                          </select>
                      </div>
                    </div>
                      <div class="col-md-12">
                          <div class="form-group">
                        <label>Output: </label>
                        <select class="form-control" name="category_two_id" onchange="get_indicators($(this).val())" >
                            
                            <?php
                            $res = $this->db->query("SELECT * from category_two where subject_area_id='$sub'")->result();
                            foreach ($res as $objs):
                              $selected = ($category_two == $objs->id) ? 'selected' : '';
                              ?>
                              <option <?php echo $selected; ?> value="<?php echo $objs->id; ?>">
                                <?php echo $objs->cat_name; ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>
                      
                      <!-- <div class="col-md-3">
                          <div class="form-group">
                        <label>Indicator: </label>
                        <select class="form-control" name="kpi_id" id="indicators">
                            
                            
                          </select>
                          </div>
                        </div> -->
                          <div class="col-md-4" style="margin-top:25px !important;">
                          <div class="form-group mr-2">
                          <button type="submit" class="btn btn-warning"><i class="fa fa-recycle"></i>Apply Filters</button>
                          </div>
                        </div>
                    </form>

                    <!-- datatable end -->
                </div>
            </div>
        </div>
    </div>
</div>














<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                <?php echo (!empty($title)?$title:null) ?>
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
                    <table class="table  table-bordered" id="kpiTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>KPI</th>
                                <th>Quarter 1</th>
                                <th>Quarter 2</th>
                                <th>Quarter 3</th>
                                <th>Quarter 4</th>
                            </tr>
                        </thead>
                        <tbody>
                      
                <?php
                $i=1;
                $q1_sums =array();
                $q2_sums = array();
                $q3_sums = array();
                $q4_sums = array();
                foreach ($subdash as $subd)

                {?>
                  <tr>
                        <td><?php echo $i++?></td>
                        <td><?=$subd->indicator_statement?></td>
                          <td style="color: #FFF; background-color:<?php echo getColorBasedOnPerformance($performance_value = get_performance($subd->kpi_id, 'Q1', $this->session->userdata('financial_year'), $this->session->userdata('ihris_pid'))->performance, get_performance($subd->kpi_id, 'Q1', $this->session->userdata('financial_year'), $this->session->userdata('ihris_pid'))->target_value) ?>"> 
                              <?php echo ($performance_value==0)?'':$performance_value;
                                if($performance_value!=0):
                                array_push($q1_sums,$performance_value);  
                                endif;       
                              ?></td>
                        <td style="color: #FFF; background-color:<?php echo getColorBasedOnPerformance($performance_value = get_performance($subd->kpi_id, 'Q2', $this->session->userdata('financial_year'), $this->session->userdata('ihris_pid'))->performance, get_performance($subd->kpi_id, 'Q1', $this->session->userdata('financial_year'), $this->session->userdata('ihris_pid'))->target_value) ?>">
                              <?php echo ($performance_value == 0) ? '' : $performance_value;
                                if ($performance_value != 0):
                                  array_push($q2_sums, $performance_value);
                                endif;
                                ?>
                        </td>
                        <td style="color: #FFF; background-color:<?php echo getColorBasedOnPerformance($performance_value = get_performance($subd->kpi_id, 'Q3', $this->session->userdata('financial_year'), $this->session->userdata('ihris_pid'))->performance, get_performance($subd->kpi_id, 'Q1', $this->session->userdata('financial_year'), $this->session->userdata('ihris_pid'))->target_value) ?>">
                              <?php echo($performance_value == 0) ? '' : $performance_value;
                                if ($performance_value != 0):
                                  array_push($q3_sums, $performance_value);
                                endif;
                                ?>
                        </td>
                        <td style="color: #FFF; background-color:<?php echo getColorBasedOnPerformance($performance_value = get_performance($subd->kpi_id, 'Q4', $this->session->userdata('financial_year'), $this->session->userdata('ihris_pid'))->performance, get_performance($subd->kpi_id, 'Q1', $this->session->userdata('financial_year'), $this->session->userdata('ihris_pid'))->target_value) ?>">
                              <?php echo ($performance_value == 0) ? '' : $performance_value;
                                if ($performance_value != 0):
                                  array_push($q4_sums, $performance_value);
                                endif;
                                ?>
                        </td>
                      
                      </tr>  
                      
                <?php }?>

                  
                    <!-- Add more rows as needed -->
                    </tbody>
                    <tfoot>
                      <tr>
                        <td>#</td>
                        <td><b>Average Performance</td>
                        <td><?= (count($q1_sums)>0)?array_sum($q1_sums)/ count($q1_sums):'';?></td>
                        <td><?= (count($q2_sums) > 0) ? array_sum($q2_sums) / count($q2_sums) : ''; ?></td>
                        <td><?= (count($q3_sums) > 0) ? array_sum($q3_sums) / count($q3_sums) : ''; ?></td>
                        <td><?= (count($q4_sums) > 0) ? array_sum($q4_sums) / count($q4_sums) : '';?></b></td>
                      </tr>
                    </tfoot>
                    </table>
                    <?php

                    if (count($subdash) == 0):

                    ?>


                    <h2 class="text-muted text-center"> 
                          <i class="fa fa-file"></i>
                          <br>
                          No data found
                      
                    </h2>

                    <?php endif;?>
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