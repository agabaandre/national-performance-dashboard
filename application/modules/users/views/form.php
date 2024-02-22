<style>
    input text {
        border: 1px solid #000;
        border-radius: 4px;
    }
</style>

<div class="row">
    <div class="col-sm-12 col-md-12">
        <div id="panel-13" class="panel">
            <div class="panel-hdr border-faded border-top-0 border-right-0 border-left-0 shadow-0">
                <h2>
                    <?php echo (!empty($title) ? $title : null) ?>
                </h2>
                <div class="panel-toolbar pr-3">
                    <ul class="nav nav-pills border-bottom-0" role="tablist">

                    </ul>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">


                        <!-- <a href="<?php echo base_url() ?>person/all_users" class="btn btn-primary">Automatically Render Accounts</a> -->
                <?php echo form_open_multipart(base_url('users/form'), array('id' => 'users', 'class' => 'users')); ?>


                         <?php echo form_hidden('id', $user->id) ?>
                    <div class="form-group row">
                        <label for="lastname" class="col-sm-3 col-form-label"><?php echo display('firstname') ?> *</label>
                        <div class="col-sm-9">
                            <input name="firstname" class="form-control" type="text" placeholder="<?php echo display('firstname') ?>" id="lastname" value="<?php echo $user->firstname ?>">
                        </div>
                    </div>
                    <?php if (!empty($_SESSION['subject_area'])) {
                        echo @$id = implode(",", json_decode($_SESSION['subject_area']));
                    } ?>
                    <div class="form-group row">
                        <label for="lastname" class="col-sm-3 col-form-label"><?php echo display('lastname') ?> *</label>
                        <div class="col-sm-9">
                            <input name="lastname" class="form-control" type="text" placeholder="<?php echo display('lastname') ?>" id="lastname" value="<?php echo $user->lastname ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-sm-3 col-form-label"><?php echo display('email') ?> *</label>
                        <div class="col-sm-9">
                            <input name="email" class="form-control" type="text" placeholder="<?php echo display('email') ?>" id="email_id" value="<?php echo $user->email ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-sm-3 col-form-label"><?php echo display('password') ?> : Resets to default</label>
                        <div class="col-sm-9">
                            <input name="password" class="form-control" type="text" value="<?php echo $setting->dp; ?>" placeholder="<?php echo display('password') ?>" id="password" readonly>
                            <input name="oldpassword" class="form-control" type="hidden" value="<?php echo $setting->dp; ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                     
                        <label for="cumulative" class="col-sm-3 col-form-label">District</label>
                        <div class="col-sm-9">
                            <select class="select2 form-control" name="district_id" class="form-control" onchange="getFacs(this.value)" required>

                             <option value=""> SELECT DISTRICT </option>

                                <?php
                                $districts = $this->db->query('SELECT distinct district_id, district from ihrisdata_staging order by district ASC')->result();
                              
                                foreach ($districts as $district) :

                                ?>
                                    <option value="<?php echo $district->district_id; ?>" <?php if ($district->district_id==$user->district_id) {
                                                                                    echo "selected";
                                                                                } ?>>
                                        <?php echo $district->district; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                         <div class="form-group row">
                    <label for="cumulative" class="col-sm-3 col-form-label">Facility</label>
                        <div class="col-sm-9">
                     <select class="select2 form-control facilities" name="facility_id" class="form-control" id="facilities" required>
                       
                     </select>
                   </div>
                                                                            </div>

                    <div class="form-group row">
                        <?php $years = array("data" => "Data Entry", "admin" => "Admistrator"); ?>
                        <label for="cumulative" class="col-sm-3 col-form-label">User Type</label>
                        <div class="col-sm-9">
                            <select name="user_type" class="form-control codeigniterselect">

                                <?php $usertype = $user->user_type;


                                foreach ($years as $key => $value) : ?>
                                    <option value="<?php echo $key; ?>" <?php if ($usertype == $key) {
                                                                            echo "selected";
                                                                        } ?>>
                                        <?php echo $value; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>



                    <div class="form-group row">

                        <div class="col-sm-9">
                            <input type="hidden" name="image" id="image" aria-describedby="fileHelp">
                            <small id="fileHelp" class="text-muted"></small>
                            <input type="hidden" name="old_image" value="<?php echo $user->image ?>">
                        </div>
                    </div>
                    <!-- <div class="form-group row">
                        <label for="status" class="col-sm-3 col-form-label">Allow Browse Categories *</label>
                        <div class="col-sm-9">
                            <label class="radio-inline">
                                <?php echo form_radio('allow_all_categories', '1', (($user->allow_all_categories == 1 || $user->allow_all_categories == null) ? true : false), 'id="allow_all_categories"'); ?>Allow
                            </label>
                            <label class="radio-inline">
                                <?php echo form_radio('allow_all_categories', '0', (($user->allow_all_categories == "0") ? true : false), 'id="allow_all_categories"'); ?>Deny
                            </label>
                        </div>
                    </div> -->
                    <div class="form-group row">
                        <label for="status" class="col-sm-3 col-form-label">Status *</label>
                        <div class="col-sm-9">
                            <label class="radio-inline">
                                <?php echo form_radio('status', '1', (($user->status == 1 || $user->status == null) ? true : false), 'id="status"'); ?>Active
                            </label>
                            <label class="radio-inline">
                                <?php echo form_radio('status', '0', (($user->status == "0") ? true : false), 'id="status"'); ?>Inactive
                            </label>
                        </div>
                    </div>



                    <div class="form-group text-right">
                        <button type="reset" class="btn btn-primary w-md m-b-5"><?php echo display('reset') ?></button>
                        <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('save') ?></button>
                    </div>
                </form>

                </div>
            </div>
        </div>
    </div>
</div>
