



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
                    <a class="btn btn-primary" href="<?php echo base_url("dashboard/language") ?>"> <i class="fa fa-list"></i>  Language List </a> 
                        <br>
                        <br>
                    </div>

                    <!-- datatable start -->
                    <table class="table table-striped">
                    <thead>
                        <tr>
                            <td></td>
                            <td><?php echo $links; ?></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <?php echo  form_open('dashboard/language/addPhrase', ' class="form-inline" ') ?> 
                                    <div class="form-group">
                                        <label class="sr-only" for="addphrase"> Phrase Name</label>
                                        <input name="phrase[]" type="text" class="form-control" id="addphrase" placeholder="Phrase Name">
                                    </div>
                                      
                                    <button type="submit" class="btn btn-primary">Save</button>
                                <?php echo  form_close(); ?>
                            </td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-th-list"></i></th>
                            <th>Phrase</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($phrases)) {?>
                            <?php $sl = 1 ?>
                            <?php foreach ($phrases as $value) {?>
                            <tr>
                                <td><?php echo  $sl++ ?></td>
                                <td><?php echo  $value->phrase ?></td>
                            </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td><?php echo $links; ?></td>
                        </tr>
                    </tfoot>
                  </table> 
                    <!-- datatable end -->
                </div>
            </div>
        </div>
    </div>
</div>