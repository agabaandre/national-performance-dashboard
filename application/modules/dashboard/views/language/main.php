



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
                            <a class="btn btn-primary" href="<?php echo base_url("dashboard/language/phrase") ?>"> <i class="fa fa-plus"></i> Add Phrase</a> 
                        <br>
                        <br>
                    </div>

                    <!-- datatable start -->
                    <table class="table table-striped">
                    <thead>
                        <tr>
                            <td colspan="3">
                                <?php echo  form_open('dashboard/language/addlanguage', ' class="form-inline" ') ?> 
                                    <div class="form-group">
                                        <label class="sr-only" for="addLanguage"> Language Name</label>
                                        <input name="language" type="text" class="form-control" id="addLanguage" placeholder="Language Name">
                                    </div>
                                      
                                    <button type="submit" class="btn btn-primary">Save</button>
                                <?php echo  form_close(); ?>
                            </td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-th-list"></i></th>
                            <th>Language</th>
                            <th><i class="fa fa-cogs"></i></th>
                        </tr>
                    </thead>


                    <tbody>
                        <?php if (!empty($languages)) {?>
                            <?php $sl = 1 ?>
                            <?php foreach ($languages as $key => $language) {?>
                            <tr>
                                <td><?php echo  $sl++ ?></td>
                                <td><?php echo  $language ?></td>
                                <td><a href="<?php echo  base_url("dashboard/language/editPhrase/$key") ?>" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>  
                                </td> 
                            </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody> 
                </table> 
                    <!-- datatable end -->
                </div>
            </div>
        </div>
    </div>
</div>