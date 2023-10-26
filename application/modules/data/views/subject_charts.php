
<div class="row col-md-12">
<form class="form-horizontal" method="post" id="switchCategoryTwo">

<div class="col-md-4"> 
<div class="form-group">
    <label>Job: </label>
    <select class="form-control" name="category_two" onchange="$('#switchCategoryTwo').submit()">

        <option value="0">All</option>

          <?php 
            foreach($category_twos as $obj):
                  $selected = ($category_two == $obj->id)?'selected':'';
          ?>
            <option <?php echo $selected; ?> value="<?php echo $obj->id; ?>">
                      <?php echo $obj->cat_name; ?>
            </option>
          <?php endforeach; ?>
      </select>
  </div>
 </div>
  <div class="col-md-4 mr-2">
    <div class="form-group">
    <label>Employee: </label>
    <select class="form-control" name="category_two" onchange="$('#switchCategoryTwo').submit()">

        <option value="0">All</option>

         <?php
        foreach ($category_twos as $obj):
          $selected = ($category_two == $obj->id) ? 'selected' : '';
          ?>
          <option <?php echo $selected; ?> value="<?php echo $obj->id; ?>">
            <?php echo $obj->cat_name; ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
      </div>
       <div class="col-md-4 mr-2">
      <div class="form-group">
    <label>Indicator: </label>
    <select class="form-control" name="category_two" onchange="$('#switchCategoryTwo').submit()">

        <option value="0">All</option>

         <?php
          foreach ($category_twos as $obj):
            $selected = ($category_two == $obj->id) ? 'selected' : '';
            ?>
            <option <?php echo $selected; ?> value="<?php echo $obj->id; ?>">
              <?php echo $obj->cat_name; ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
          </div>
</form>
 </div>
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
</h2>

<?php endif; ?>
