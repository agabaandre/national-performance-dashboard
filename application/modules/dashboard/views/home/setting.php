

                                <!-- Background & borders -->
                                <div id="panel-5" class="panel">
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
                                        <?php echo form_open_multipart('dashboard/setting/create','class="form-inner"') ?>
                                            <?php echo form_hidden('id',$setting->id) ?>
                                            <!-- demo controls -->
                                            <div class="mb-g">
                                                <div class="row">
                                                    <div class="col-sm-6 col-xl-4">
                                                        <div class="form-group">
                                                            <label for="title" class="col-xs-3 col-form-label"><?php echo display('application_title') ?> <i class="text-danger">*</i></label>
                                                            <input name="title" type="text" class="form-control" id="title" placeholder="<?php echo display('application_title') ?>" value="<?php echo $setting->title ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-xl-4">
                                                        <div class="form-group">
                                                        <label for="address" class="col-xs-3 col-form-label"><?php echo display('address') ?></label>
                                                        <input name="address" type="text" class="form-control" id="address" placeholder="<?php echo display('address') ?>"  value="<?php echo $setting->address ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-xl-4">
                                                        <div class="form-group">
                                                            <label for="email" class="col-xs-3 col-form-label"><?php echo display('email')?></label>
                                                            <input name="email" type="text" class="form-control" id="email" placeholder="<?php echo display('email')?>"  value="<?php echo $setting->email ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-xl-4">
                                                        <div class="form-group">
                                                            <label for="phone" class="col-xs-3 col-form-label"><?php echo display('phone') ?></label>
                                                            <input name="phone" type="text" class="form-control" id="phone" placeholder="<?php echo display('phone') ?>"  value="<?php echo $setting->phone ?>" >
                                                        </div>
                                                    </div>


                                                    <div class="col-sm-6 col-xl-4">
                                                        <br>
                                                        <div class="row">

                                                            <div class="col-sm-4">
                                                                <?php if(!empty($setting->favicon)) {  ?>
                                                                <div class="form-group">
                                                                    <label for="faviconPreview" class="col-xs-3 col-form-label"></label>
                                                                    <div class="col-xs-9">
                                                                        <img src="<?php echo base_url($setting->favicon) ?>" alt="Favicon" class="img-thumbnail" />
                                                                    </div>
                                                                </div>
                                                                <?php } ?>
                                                            </div>

                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <label for="favicon" class="col-xs-3 col-form-label"><?php echo display('favicon') ?> </label>
                                                                    <div class="col-xs-9">
                                                                        <input type="file" name="favicon" id="favicon">
                                                                        <input type="hidden" name="old_favicon" value="<?php echo $setting->favicon ?>">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <br>
                                                    </div>


                                                    <div class="col-sm-6 col-xl-4">
                                                        <br>
                                                        <div class="row">

                                                            <div class="col-sm-3">
                                                                <?php if(!empty($setting->logo)) {  ?>
                                                                <div class="form-group row">
                                                                    <label for="logoPreview" class="col-xs-3 col-form-label"></label>
                                                                    <div class="col-xs-9">
                                                                        <img src="<?php echo base_url($setting->logo) ?>" alt="Picture" class="img-thumbnail" />
                                                                    </div>
                                                                </div>
                                                                <?php } ?>
                                                            </div>


                                                            <div class="col-sm-9">
                                                                <div class="form-group">
                                                                    <label for="logo" class="col-xs-3 col-form-label"><?php echo display('logo') ?></label>
                                                                    <div class="col-xs-9">
                                                                        <input type="file" name="logo" id="logo">
                                                                        <input type="hidden" name="old_logo" value="<?php echo $setting->logo ?>">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <br>
                                                    </div>


                                                    <div class="col-sm-6 col-xl-4">
                                                        <div class="form-group">
                                                            <label for="gauge_config" class="col-xs-3 col-form-label"><?php echo display('gauge_config') ?></label>
                                                            <textarea name="gauge_config" class="form-control"  placeholder="">
                                                             <?php echo $setting->gauge_config; ?></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-6 col-xl-4">
                                                        <div class="form-group">
                                                            <label for="footer_text" class="col-xs-3 col-form-label"><?php echo display('language') ?></label>
                                                            <?php echo  form_dropdown('language',$languageList,$setting->language, 'class="form-control"') ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-xl-4">
                                                        <div class="form-group">
                                                            <label for="footer_text" class="col-xs-3 col-form-label"><?php echo display('time_zone') ?></label>
                                                            <?php echo form_dropdown('timezone', $timezonelist, $setting->timezone , array('class'=>'form-control')); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-xl-4">
                                                        <div class="form-group">
                                                            <label for="footer_text" class="col-xs-3 col-form-label"><?php echo display('dash_display') ?></label>
                                                            <select class="form-control custom-select">
                                                            <?php $cols=array("1 Column"=>"12","2 Columns"=>"6","3 Columns - Preferred"=>"4","4 columns"=>"3"); 
                                                                foreach($cols as $key => $value){
                                                                ?>
                                                                <option value="<?php echo $value ?>" <?php if ($setting->dash_rows==$value){ echo "selected"; } ?>><?php echo $key; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-xl-4">
                                                        <div class="form-group">
                                                            <label for="footer_text" class="col-xs-3 col-form-label"><?php echo display('Financial_Year') ?></label>
                                                            <select class="form-control custom-select">
                                                                <?php 
                              
                                                                    $startdate="2020";
                                                                    $enddate=intval(date('Y')+1);
                                                                    $years = range($startdate, $enddate);
                                                                    //print years
                                                                        //print years
                                                                        foreach ($years as $year) {
                                                                            if ((substr($year, 0) + 1) <= substr($enddate, 0)) {?>



                                                                            <?php  $fy=$year . '-' . (substr($year, 0) + 1); ?>
                                                                                <option value="<?php echo $fy ?>" <?php if ($setting->financial_year==$fy){ echo "selected"; } ?>><?php echo $fy; ?></option>
                                                                        
                                                                        <?php
                                                                            }
                                                                        }

                                                                        
                                                                    ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-xl-4">
                                                        <div class="form-group">
                                                            <label for="footer_text" class="col-xs-3 col-form-label"><?php echo display('kpi_display') ?></label>
                                                            <select class="form-control custom-select">
                                                            <?php $cols=array("1 Column"=>"12","2 Columns - Preferred"=>"6","3 Columns"=>"4","4 columns"=>"3"); 
                                                                foreach($cols as $key => $value){
                                                                ?>
                                                                <option value="<?php echo $value ?>" <?php if ($setting->kpi_rows==$value){ echo "selected"; } ?>><?php echo $key; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-xl-4">
                                                        <div class="form-group">
                                                            <label for="footer_text" class="col-xs-3 col-form-label">Dimension Chart</label>
                                                                <?php echo form_dropdown('dimension_chart', $dimension_chart, $setting->dimension_chart , array('class'=>'form-control')); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-xl-4">
                                                        <div class="form-group">
                                                            <label for="footer_text" class="col-xs-3 col-form-label"><?php echo display('menu_type') ?></label>
                                                            <select class="form-control custom-select">
                                                                <option <?php echo ($setting->use_category_two==0)?'selected':'' ?> value="0">Subject Area>>Indictors (MOH)</option>
                                                                <option <?php echo ($setting->use_category_two==1)?'selected':'' ?> value="1">Department>>Subject Area>>Indictors(MOH)</option>
                                                                <option <?php echo ($setting->use_category_two==2)?'selected':'' ?> value="1">Inicator Type>>Subject Area>>Indictors(CPHL)</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-xl-4">
                                                        <div class="form-group">
                                                            <label for="footer_text" class="col-xs-3 col-form-label"><?php echo display('site_align') ?></label>
                                                            <?php echo  form_dropdown('site_align', array('LTR' => display('left_to_right'), 'RTL' => display('right_to_left')) ,$setting->site_align, 'class="form-control"') ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-xl-4">
                                                        <div class="form-group">
                                                            <label for="footer_text" class="col-xs-3 col-form-label"><?php echo display('font_awesome') ?></label>
                                                            <textarea name="font_awesome" class="form-control"  placeholder="font awesome"><?php echo $setting->font_awesome ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-xl-4">
                                                        <div class="form-group">
                                                            <label for="footer_text" class="col-xs-3 col-form-label">Slider Interval (Millseconds)</label>
                                                            <input type="text" name="slider_timer" class="form-control"  placeholder="slider timer" value="<?php echo $setting->slider_timer ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-xl-4">
                                                        <div class="form-group">
                                                            <label for="footer_text" class="col-xs-3 col-form-label"><?php echo display('footer_text') ?></label>
                                                            <textarea name="footer_text" class="form-control"  placeholder="Footer Text"><?php echo $setting->footer_text ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-xl-4">
                                                        <div class="container">
                                                            <hr>
                                                        </div>
                                                        <div class="form-group text text-center">
                                                            <button type="reset" class="btn btn-primary w-md m-b-5"><?php echo display('reset') ?></button>
                                                            <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('save') ?></button>
                                                        </div>
                                                    </div>




                                                </div>
                                            </div>
                                        </div>
                                        <?php echo form_close() ?>
                                    </div>
                                </div>