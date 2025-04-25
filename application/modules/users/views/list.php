


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
                
             <a href="<?php echo base_url() ?>person/all_users" class="btn btn-primary">Automatically Render Accounts</a>
                <div class="panel-content">

                    <!-- datatable start -->
                    <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                        <thead>
                            <tr>
                                <th><?php echo display('sl_no') ?></th>
                                <th><?php echo display('image') ?></th>
                                <th><?php echo display('username') ?></th>
                                <th><?php echo display('email') ?></th>
                                <th><?php echo display('last_login') ?></th>
                                <th>User type</th>
                                <th><?php echo display('ip_address') ?></th>
                                <th><?php echo display('status') ?></th>
                                <th><?php echo display('action') ?></th> 
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($user)) ?>
                            <?php $sl = 1; ?>
                            <?php foreach ($user as $value) { ?>
                            <tr>
                                <td><?php echo $sl++; ?></td>
                                <td><img src="<?php echo base_url(!empty($value->image)?$value->image:'assets/img/icons/default.jpg'); ?>" alt="Image" height="50" ></td>
                                <td><?php echo $value->fullname; ?></td>
                                <td><?php echo $value->email; ?></td>
                                <td><?php echo $value->last_login; ?></td>
                                <td><?php echo $value->user_type; ?></td>
                                <td><?php echo $value->ip_address; ?></td>
                                <td><?php echo (($value->status==1)?display('active'):display('inactive')); ?></td>
                                <td>
                                  
                                    <a href="<?php echo base_url("users/form/$value->id") ?>?facility_id=<?=urlencode($value->facility_id) ?>" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Update"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                    <?php if ($value->is_admin == 1) { ?>
                                    <button class="btn btn-info btn-sm" title="<?php echo display('admin') ?>"><?php echo display('admin') ?></button>
                                    <?php }else{ ?>
                                    <a href="<?php echo base_url("users/delete/$value->id") ?>" onclick="return confirm('Are you sure ?')" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right" title="Delete "><i class="fa fa-trash" aria-hidden="true"></i></a>
                                 
                                   <?php  }
                                    
                                    ?>
                                
                                </td>
                            </tr>
                            <?php } ?> 
                        </tbody>
                    </table>
                    <!-- datatable end -->
                </div>
            </div>
        </div>
    </div>
</div>