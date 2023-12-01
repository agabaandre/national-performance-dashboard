<!-- Modal -->

<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false"
  aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="staticBackdropLabel">Add KPI</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php echo form_open_multipart(base_url('kpi/addkpi'), array('id' => 'kpi', 'class' => 'kpi')); ?>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group row">

              <label for="kpiid" class="col-sm-3 col-form-label">
                Indicator Identifier(KPI ID)</label>
              <div class="col-sm-9">
                <input type="text" name="kpi_id" placeholder="KPI-0" value="<?= generate_kpi_id($_SESSION['id']); ?>"
                  class=" form-control" required readonly>
              </div>

            </div>
            <div class="form-group row">

              <label for="shortname" class="col-sm-3 col-form-label">
                Short Name</label>
              <div class="col-sm-9">
                <input type="text" name="short_name" placeholder="KPI Short Name" class=" form-control" required>

              </div>

            </div>
            <div class="form-group row">

              <label for="indiactor_statement" class="col-sm-3 col-form-label">
                Indicator Statement</label>
              <div class="col-sm-9">
                <textarea name="indicator_statement" class="form-control" id=""></textarea required>   
                            </div>
                           
                        </div>
                           <div class="form-group row">
                           
                          <label for="frequency" class="col-sm-3 col-form-label">
                            Frequency</label>
                            <div class="col-sm-9">
                           <select name="frequency" class="form-control codeigniterselect">
                            <option value="Quarterly" selected>Quarterly</option>
                            </select>  
                            </div>
                           
                        </div>
                    
                        <div class="form-group row">
                           
                          <label for="cumulative" class="col-sm-3 col-form-label">
                            Indicator Type</label>
                            <div class="col-sm-9">
                           <select name="indicator_type_id" class="form-control codeigniterselect">
                           <option value="1">Output</option>
                           <option value="2">Outcome</option>
            
                            </select>  
                            </div>
                        </div>

                          <div class="form-group row">
                           
                          <label for="cumulative" class="col-sm-3 col-form-label">
                            Computation Category</label>
                            <div class="col-sm-9">
                           <select name="computation_category" class="form-control codeigniterselect" required>
                           <option value="Ratio">Ratio</option>
                           <option value="Value">Value</option>
            
                            </select>  
                            </div>
                        </div>
                        
              <div class="form-group row">
                           
                  <label for="description" class="col-sm-3 col-form-label">
                  Numerator</label>
                  <div class="col-sm-9">
                  <textarea name="numerator" col="6" rows="3" class="form-control" id="" required></textarea>

              </div>

            </div>
            <div class="form-group row">

              <label for="description" class="col-sm-3 col-form-label">
                Denominator</label>
              <div class="col-sm-9">
                <textarea name="denominator" col="6" rows="3" class="form-control" id=""></textarea>

              </div>

            </div>

          </div>
          <!--End divider -->











          <div class="col-md-6">

            <div class="form-group row">

              <label for="description" class="col-sm-3 col-form-label">
                Current Target</label>
              <div class="col-sm-9">
                <input type="number" name="current_target" class="form-control" id="">

              </div>

            </div>

            <div class="form-group row">

              <label for="description" class="col-sm-3 col-form-label">
                Indicator description</label>
              <div class="col-sm-9">
                <textarea name="description" col="10" rows="5" class="form-control" id="" required></textarea>

              </div>

            </div>

            <div class="form-group row">

              <label for="description" class="col-sm-3 col-form-label">
                Data Sources</label>
              <div class="col-sm-9">
                <textarea name="data_sources" class="form-control" id="" required></textarea>

              </div>


            </div>
            <div class="form-group row">
              <label for="subject" class="col-sm-3 col-form-label">
                Focus Area</label>
              <div class="col-sm-9">
                <select name="subject_area" class="form-control codeigniterselect"
                  onchange="get_catgories($(this).val())" required>
                  <?php $elements = Modules::run('Kpi/subjectData');
                  foreach ($elements as $element): ?>
                    <option value="<?php echo $element->id ?>">
                      <?php echo $element->name ?>
                    </option>
                  <?php endforeach; ?>

                </select>
              </div>
            </div>

            <div class="form-group row">

              <label for="subject" class="col-sm-3 col-form-label">
                Output </label>
              <div class="col-sm-9">
                <select name="category_two_id" class="form-control" id="addsubcat" required>
                </select>
              </div>

            </div>

            <div class="form-group row">

              <label for="subject" class="col-sm-3 col-form-label">
                Job/Function</label>
              <div class="col-sm-9">
                <select name="job_id" class="form-control codeigniterselect">
                  <option value="">SELECT JOB</option>
                  <?php 
                 
                  $elements = $this->db->get('job')->result();
                  foreach ($elements as $element): ?>
                    <option value="<?php echo $element->job_id ?>">
                      <?php echo $element->job ?>
                    </option>
                  <?php endforeach; ?>

                </select>
              </div>


            </div>

          </div>
          <!---End sub2-->
          <div class="form-group text-right">
            <button type="reset" class="btn btn-primary w-md m-b-5">Reset</button>
            <button type="submit" class="btn btn-success w-md m-b-5">Save</button>
          </div>
          </form>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

      </div>
    </div>
  </div>
</div>
<script>
  function get_catgories(val) {
    $.ajax({
      method: "GET",
      url: "<?php echo base_url(); ?>kpi/get_subcatgories",
      data: 'sub_data=' + val,
      success: function (data) {
        $("#subcat").html(data);
         $("#addsubcat").html(data);
        console.log(data);
      }
    });

  }
</script>