
<div class="row col-md-12">
<form class="form-horizontal" method="post" id="switchCategoryTwo">

<div class="col-md-4"> 
<div class="form-group">
    <label>Job: </label>
    <select class="form-control" name="job" onchange="$('#switchCategoryTwo').submit()">

        <option value="0">All</option>

          <?php 
          $jobs = $this->db->query('SELECT distinct job_id, job from ihrisdata')->result();
            foreach($jobs as $job):
                  $selected = ($job == $job->job_id)?'selected':'';
          ?>
            <option <?php echo $selected; ?> value="<?php echo $job->job_id; ?>">
                      <?php echo $job->job; ?>
            </option>
          <?php endforeach; ?>
      </select>
  </div>
 </div>
  <div class="col-md-4 mr-2">
      <div class="form-group">
    <label>Output: </label>
    <select class="form-control" name="" onchange="get_indicators($(this).val())">

        <option value="0">All</option>

         <?php

         $sub = $this->uri->segment(3);
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
   
  <div class="col-md-4 mr-2">
      <div class="form-group">
    <label>Indicator: </label>
    <select class="form-control" name="kpi" id="indicators">
        
        </select>
      </div>
     </div>
</form>
 </div>
 <div class="row col-md-12">
<?php 

foreach ($subdash as $subd) {       
       echo @Modules::run('data/kpi',$subd->kpi_id,'on');             
 }

 if(count($subdash) == 0):

 ?>
 

 <h2 class="text-muted text-center"> 
       <i class="fa fa-file"></i>
       <br>
      No data found
   <?php  echo  $this->uri->segment(3)?>
</h2>

<?php endif; ?>
 </div>
<script>
  function get_indicators(val) {
    $.ajax({
      method: "GET",
      url: "<?php echo base_url(); ?>kpi/get_indicators",
      data: 'cat_data=' + val,
      success: function (data) {
        $("#indicators").html(data);
        //console.log(data);
      }
    });

  }
</script>