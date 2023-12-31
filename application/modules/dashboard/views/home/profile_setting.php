


<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                <?php echo (!empty($title)?$title:null); ?>
                </h2>
                <div class="panel-toolbar">
                    <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
                    <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
                    <!-- <button class="btn btn-panel" data-action="panel-close" data-toggle="tooltip" data-offset="0,10" data-original-title="Close"></button> -->
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">

                <?php echo form_open_multipart("dashboard/home/setting") ?>
                    
                    <?php echo form_hidden('id',$user->id) ?>
                    
                    <div class="form-group row">
                        <label for="firstname" class="col-sm-3 col-form-label">First Name *</label>
                        <div class="col-sm-9">
                            <input name="firstname" class="form-control" type="text" placeholder="First Name" id="firstname"  value="<?php echo $user->firstname ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="lastname" class="col-sm-3 col-form-label">Last Name *</label>
                        <div class="col-sm-9">
                            <input name="lastname" class="form-control" type="text" placeholder="Last Name" id="lastname" value="<?php echo $user->lastname ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-sm-3 col-form-label">Email Address *</label>
                        <div class="col-sm-9">
                            <input name="email" class="form-control" type="text" placeholder="Email Address" id="email" value="<?php echo $user->email ?>">
                        </div>
                    </div> 

                    <div class="form-group row">
                        <label for="password" class="col-sm-3 col-form-label">Password *</label>
                        <div class="col-sm-9">
                            <input name="password" class="form-control" type="password" placeholder="Password" id="password">
                            <input name="oldpassword" class="form-control" type="hidden" placeholder="Password" value="<?php echo $user->password ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="about" class="col-sm-3 col-form-label">About</label>
                        <div class="col-sm-9">
                            <textarea name="about" placeholder="About" class="form-control" id="about"><?php echo $user->about ?></textarea>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="preview" class="col-sm-3 col-form-label">Preview</label>
                        <div class="col-sm-9">
                            <img src="<?php echo base_url(!empty($user->image)?$user->image: "./assets/img/icons/default.jpg") ?>" class="img-thumbnail" width="125" height="100">
                        </div>
                        <input type="hidden" name="old_image" value="<?php echo $user->image ?>">
                    </div> 

                    <div class="form-group row">
                        <label for="image" class="col-sm-3 col-form-label">Image</label>
                        <div class="col-sm-9">
                            <input type="file" name="image" id="image" aria-describedby="fileHelp">
                            <small id="fileHelp" class="text-muted"></small>
                        </div>
                    </div> 
         
                    <div class="form-group text-right">
                        <button type="reset" class="btn btn-primary w-md m-b-5">Reset</button>
                        <button type="submit" class="btn btn-success w-md m-b-5">Save</button>
                    </div>
                <?php echo form_close() ?>
                    <!-- datatable end -->
                </div>
            </div>
        </div>
    </div>
</div>