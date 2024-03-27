



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
                    <a class="btn btn-success" href="<?php echo base_url("dashboard/language/phrase") ?>"> <i class="fa fa-plus"></i> Add Phrase</a>
                    <a class="btn btn-primary" href="<?php echo base_url("dashboard/language") ?>"> <i class="fa fa-list"></i>  Language List </a> 
                        <br>
                        <br>
                    </div>

                    <!-- datatable start -->
                    <?php echo  form_open('dashboard/language/addlebel') ?>
                <table class="table table-striped">
                    <thead> 
                        <tr>
                            <td colspan="2"> 
                                <button type="reset" class="btn btn-danger">Reset</button>
                                <button type="submit" class="btn btn-success">Save</button>
                            </td>
                            <td><?php echo $links; ?></td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-th-list"></i></th>
                            <th>Phrase</th>
                            <th>Label</th> 
                        </tr>
                    </thead>

                    <tbody>
                        <?php echo  form_hidden('language', $language) ?>
                            <?php if (!empty($phrases)) {?>
                                <?php $sl = 1 ?>
                                <?php foreach ($phrases as $value) {?>
                                <tr <?php echo  (empty($value->$language)?"":null) ?>>
                                
                                    <td><?php echo  $sl++ ?></td>
                                    <td><input type="text" name="phrase[]" value="<?php echo  $value->phrase ?>" class="form-control" readonly></td>
                                    <td><input type="text" name="lang[]" value="<?php echo  $value->$language ?>" class="form-control"></td> 
                                </tr>
                                <?php } ?>
                            <?php } ?> 
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"> 
                                <button type="reset" class="btn btn-danger">Reset</button>
                                <button type="submit" class="btn btn-success">Save</button>
                            </td>
                            <td><?php echo $links; ?></td>
                        </tr>
                    </tfoot>
                    <?php echo  form_close() ?>
                </table>
                <?php echo form_close() ?>
                    <!-- datatable end -->
                </div>
            </div>
        </div>
    </div>
</div>