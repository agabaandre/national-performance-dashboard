
<div class="row col-md-12">
<?php 
$sub = $this->uri->segment(3);
$subn = $this->uri->segment(4);
echo form_open_multipart(base_url('data/subject/'.$sub.'/'.$subn), array('id' => 'filter', 'class' => 'form-horizontal','method'=>'get')); ?>

<div class="col-md-3"> 
<div class="form-group">
    <label>Job: </label>
    <select class="form-control" name="job_id" <?php if (($this->session->userdata('user_type') != 'admin')) { echo 'disabled';} ?>>

        <option value="0">All</option>

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
  <div class="col-md-3 mr-2">
      <div class="form-group">
    <label>Output: </label>
    <select class="form-control" name="category_two_id" onchange="get_indicators($(this).val())">

        <option value="0">All</option>

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
   
  <div class="col-md-3">
      <div class="form-group">
    <label>Indicator: </label>
    <select class="form-control" name="kpi_id" id="indicators">
        
      </select>
      </div>
     </div>
      <div class="col-md-3 ml-10" style="margin-top:25px !important;">
      <div class="form-group mr-2">
       <button type="submit" class="btn btn-warning"><i class="fa fa-recycle"></i>Apply Filters</button>
      </div>
     </div>
</form>
 </div>
 <div class="row col-md-12">
  <table class="table table-bordered">
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
foreach ($subdash as $subd)

{?>
  <tr>
        <td><?php echo $i++?></td>
        <td><?=$subd->indicator_statement?></td>
        <td style="color: #FFF; background-color:<?php echo getColorBasedOnPerformance($performance_value = get_performance($subd->kpi_id, 'Q1', $this->session->userdata('financial_year'), $this->session->userdata('ihris_pid'))->performance, get_performance($subd->kpi_id, 'Q1', $this->session->userdata('financial_year'), $this->session->userdata('ihris_pid'))->target_value) ?>"> 
              <?php echo ($performance_value==0)?'':$performance_value  ?></td>
        <td style="color: #FFF; background-color:<?php echo getColorBasedOnPerformance($performance_value = get_performance($subd->kpi_id, 'Q2', $this->session->userdata('financial_year'), $this->session->userdata('ihris_pid'))->performance, get_performance($subd->kpi_id, 'Q1', $this->session->userdata('financial_year'), $this->session->userdata('ihris_pid'))->target_value) ?>">
              <?php echo ($performance_value == 0) ? '' : $performance_value ?>
        </td>
        <td style="color: #FFF; background-color:<?php echo getColorBasedOnPerformance($performance_value = get_performance($subd->kpi_id, 'Q3', $this->session->userdata('financial_year'), $this->session->userdata('ihris_pid'))->performance, get_performance($subd->kpi_id, 'Q1', $this->session->userdata('financial_year'), $this->session->userdata('ihris_pid'))->target_value) ?>">
              <?phpecho($performance_value == 0) ? '' : $performance_value ?>
        </td>
        <td style="color: #FFF; background-color:<?php echo getColorBasedOnPerformance($performance_value = get_performance($subd->kpi_id, 'Q4', $this->session->userdata('financial_year'), $this->session->userdata('ihris_pid'))->performance, get_performance($subd->kpi_id, 'Q1', $this->session->userdata('financial_year'), $this->session->userdata('ihris_pid'))->target_value) ?>">
              <?php echo ($performance_value == 0) ? '' : $performance_value ?>
        </td>
      
      </tr>  
       
 <?php }?>

  
    <!-- Add more rows as needed -->
    </tbody>
    <tfoot>
      <tr>
        <td>#</td>
        <td></td>
        <td>Total Q1</td>
        <td>Total Q2</td>
        <td>Total Q3</td>
        <td>Total Q4</td>
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

<?php endif; ?>
<?php //print_r($this->session->userdata()); ?>
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