<style>
    input {
        clear:both;
        padding:4px;
        border:0px #FFF;

    }
</style>

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
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <!-- datatable start -->
                    <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                        <thead>
                            <tr>
                                <th>KPI ID</th>
                                <th>Dimension 1</th>
                    
                                <th>Dimension 2</th>
                            
                                <th>Dimension 3 </th>
                                
                                <th>Financial Year</th>
                            
                                <th>Period</th>
                                <th>Denominator</th>
                                <th>Numerator</th>
                                <th>Data Target</th>
                            
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $row) { ?>
                                <tr>
                                    <td>
                                        <?php echo $row->kpi_id; ?>
                                    </td>
                                    <td>
                                        <?php echo $row->dimension1; ?>
                                    </td>
                                
                                    <td>
                                        <?php echo $row->dimension2; ?>
                                    </td>
                                
                                    <td>
                                        <?php echo $row->dimension3; ?>
                                    </td>
                                
                                    <td>
                                        <?php echo $row->financial_year; ?>
                                    </td>
                                
                                    <td>
                                        <?php echo $row->period; ?>
                                    </td>
                                    <td>
                                        <?php echo $row->denominator; ?>
                                    </td>
                                    <td>
                                        <?php echo $row->numerator; ?>
                                    </td>
                                    <td>
                                        <?php echo $row->data_target; ?>
                                    </td>
                                
                                
                                </tr>
                            <?php } ?>
                        </tbody>
                        </tbody>
                    </table>
                    <!-- datatable end -->
                </div>
            </div>
        </div>
    </div>
</div>