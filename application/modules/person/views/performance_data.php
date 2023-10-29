<style>
    input text {
        border: 1px solid #000;
        border-radius: 4px;
    }
</style>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4>
                        <?php echo (!empty($title) ? $title : null) ?>
                    </h4>
                    <p style="float:right; margin-right: 4px;">
                        <a href="<?php echo base_url(); ?>person/performance" class="btn btn-sucess">
                    Performance Data
                        </a>
                    <p>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">





                            <div class="card-content">
                                <div class="col-md-12">
                                 
                                    <?php echo form_open_multipart(base_url('person/save'), array('id' => 'get_performance', 'class' => 'person', 'method'=>'get')); ?>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="financial_year">Financial Year:</label>
                                            <select class="form-control" name="financial_year"
                                                onchange="this.form.submit()">

                                                <?php

                                                $startdate = "2022";
                                                $enddate = intval(date('Y') + 1);
                                                $years = range($startdate, $enddate);
                                                //print years
                                                //print years
                                                foreach ($years as $year) {
                                                    if ((substr($year, 0) + 1) <= substr($enddate, 0)) { ?>



                                                        <?php $fy = $year . '-' . (substr($year, 0) + 1); ?>
                                                        <option value="<?php echo $fy ?>" <?php if ($setting->financial_year == $fy) {
                                                               echo "selected";
                                                           } ?>>
                                                            <?php echo $fy; ?>
                                                        </option>

                                                        <?php
                                                    }
                                                } ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="period">Period:</label>
                                            <select class="form-control" name="period" onchange="this.form.submit()">
                                                <option value="Q4">Q4
                                                </option>
                                                <option value="Q3">Q3
                                                </option>
                                                <option value="Q2">Q2
                                                </option>
                                                <option value="Q1">Q1
                                                </option>
                                            </select>
                                        </div>

                                        <button type="submit" class="btn btn-primary">Filter</button>

                                    </div>
                                            </form>

                                    <table class="table table-responsive">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Indicator</th>
                                                 <th>Financial Year</th>
                                                 <th>Quater</th>
                                                <th>Numerator</th>
                                                <th>Denominator</th>
                                                <th>Comments</th>
                                                 <th>Target</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            foreach ($kpidatas as $kpi):

                                                //dd($kpidatas);

                                                ?>
                                                <tr>
                                                    <td>
                                                        <?= $i++ ?>

                                                    </td>

                                                    <td>
                                                        <?= $kpi->short_name ?>

                                                    </td>
                                                    
                                                    <td>
                                                        <?= $kpi->financial_year ?>

                                                    </td>
                                                    
                                                    <td>
                                                        <?= $kpi->period ?>

                                                    </td>
                                                    <td>
                                                
                                                        <?= $kpi->numerator ?>
                                                            
                                                    </td>
                                                    <td>
                                                  
                                                        <?= $kpi->denominator ?>
                                                           
                                                       
                                                    </td>

                                                    <td>
                                                       <?= $kpi->comments?>
                                                    </td>
                                                      <td>
                                                       <?= $kpi->target ?>
                                                     </td>


                                                    </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>


                                </div>


                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#person').submit(function(e) {
        e.preventDefault(); // Prevent the default form submission
        
        // Serialize the form data
        var formData = $('#person').serialize();
        
        // Send an AJAX request to the server
        $.ajax({
            type: 'POST', // Use the appropriate HTTP method
            url: '<?php echo base_url('person/save'); ?>', // Set the URL to your controller method
                data: formData, // Pass the serialized form data
                success: function (response) {
                    // Handle the response from the server (e.g., show a success message)
                   $.notify("Scheduled Saved", "success");
                },
                error: function (error) {
                    // Handle any errors (e.g., show an error message)
                   $.notify("Failed to save", "warning");
                }
            } );
            console.log(formData);

        });
    });
</script>