


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
                    <div class="container">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop" style="margin-bottom:3px;"><i class="fa fa-plus">
                            </i>Add Subject
                        </button>
                        <br>
                        <br>
                    </div>

                    <!-- datatable start -->
                    <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Subject Area</th>
                                <th>Display Index</th>
                                <th>Institution Category</th>
                                <th>Description</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $i=1;
                            $elements=Modules::run('kpi/subjectData');
                                foreach($elements as $element):?>

                            <tr class="table-row tbrow content strow">
                                <td><?php echo $i ?></td>
                                <td><?php echo $element->name; ?></td>
                                <td><?php echo  $element->display_index; ?></td>
                                    <td><?php echo  Modules::run('kpi/info_category_name',$element->info_category); ?></td>
                                <td><?php echo $element->sub_description; ?></td>
                                
                                
                                
                            </tr>
                                <?php 
                                    $i++;
                                endforeach; 

                                if(count($elements)==0){

                                    echo "<tr><td colspan='8'><center><h3 class='text-warning'>Please Add Indicators</h3></center></td></tr>";
                                }
                                    ?>
                        </tr>
                                            
                        </tbody>
                    </table>
                    <!-- datatable end -->
                </div>
            </div>
        </div>
    </div>
</div>




<?php $this->load->view('add_subject');?>

<!-- From old -- Hakim Comment -->
<!-- <script>
$(document).ready(function() {
$('#subject').DataTable( {
    dom: 'Bfrtip',
    buttons: [
        'copyHtml5',
        'excelHtml5',
        'csvHtml5',
        'pdfHtml5'
    ],
    lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
    responsive: true,
    displayLength: 25,
    lengthChange: true
} );
} );
</script>  -->