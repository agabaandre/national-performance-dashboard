
<!DOCTYPE html>

<html lang="en">
<head>
        <meta charset="utf-8">
      <title>
        <?php echo (!empty($setting->title) ? $setting->title : null) ?> ::
        <?php echo (!empty($title) ? $title : null) ?>
    </title>
    
    <!-- Favicon and touch icons -->
    <link rel="shortcut icon"
        href="<?php echo base_url((!empty($setting->favicon) ? $setting->favicon : 'assets_old/img/icons/favicon.png')) ?>"
        type="image/x-icon">
         <meta name="description" content="Login">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="msapplication-tap-highlight" content="no">
        <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="<?php echo base_url() ?>assets/css/vendors.bundle.css">
        <link id="appbundle" rel="stylesheet" media="screen, print" href="<?php echo base_url()?>assets/css/app.bundle.css">
        <link id="mytheme" rel="stylesheet" media="screen, print" href="#">
        <link id="myskin" rel="stylesheet" media="screen, print" href="<?php echo base_url() ?>assets/css/skins/skin-master.css">
         <link rel="shortcut icon" href="<?php echo (!empty($setting->favicon) ? $setting->favicon : null) ?>">
        <link rel="stylesheet" media="screen, print" href="<?php echo base_url() ?>assets/css/fa-brands.css">
    </head>

    <body>
        <script>
    
            'use strict';

            var classHolder = document.getElementsByTagName("BODY")[0],
                /** 
                 * Load from localstorage
                 **/
                themeSettings = (localStorage.getItem('themeSettings')) ? JSON.parse(localStorage.getItem('themeSettings')) :
                {},
                themeURL = themeSettings.themeURL || '',
                themeOptions = themeSettings.themeOptions || '';
            /** 
             * Load theme options
             **/
            if (themeSettings.themeOptions)
            {
                classHolder.className = themeSettings.themeOptions;
                console.log("%c✔ Theme settings loaded", "color: #148f32");
            }
            else
            {
                console.log("%c✔ Heads up! Theme settings is empty or does not exist, loading default settings...", "color: #ed1c24");
            }
            if (themeSettings.themeURL && !document.getElementById('mytheme'))
            {
                var cssfile = document.createElement('link');
                cssfile.id = 'mytheme';
                cssfile.rel = 'stylesheet';
                cssfile.href = themeURL;
                document.getElementsByTagName('head')[0].appendChild(cssfile);

            }
            else if (themeSettings.themeURL && document.getElementById('mytheme'))
            {
                document.getElementById('mytheme').href = themeSettings.themeURL;
            }
            /** 
             * Save to localstorage 
             **/
            var saveSettings = function()
            {
                themeSettings.themeOptions = String(classHolder.className).split(/[^\w-]+/).filter(function(item)
                {
                    return /^(nav|header|footer|mod|display)-/i.test(item);
                }).join(' ');
                if (document.getElementById('mytheme'))
                {
                    themeSettings.themeURL = document.getElementById('mytheme').getAttribute("href");
                };
                localStorage.setItem('themeSettings', JSON.stringify(themeSettings));
            }
            var resetSettings = function()
            {
                localStorage.setItem("themeSettings", "");
            }

        </script>
        <div class="page-wrapper auth">
            <div class="page-inner bg-brand-gradient">
                <div class="page-content-wrapper bg-transparent m-0">
                    <div class="height-10 w-100 shadow-lg px-4 bg-brand-gradient">
                        <div class="d-flex align-items-center container p-0">
                            <div class="page-logo width-mobile-auto m-0 align-items-center justify-content-center p-0 bg-transparent bg-img-none shadow-0 height-9 border-0">
                                <a href="javascript:void(0)" class="page-logo-link press-scale-down d-flex align-items-center">
                                
                                            
                          
                                </a>
                            </div>
                       
                        </div>
                    </div>
                    <div class="flex-1" style="background: url(img/svg/pattern-1.svg) no-repeat center bottom fixed; background-size: cover;">
                        <div class="container py-4 py-lg-5 my-lg-5 px-4 px-sm-0">
                            <div class="row">
                                <div class="col col-md-6 col-lg-7 hidden-sm-down">
                                    <h2 class="fs-xxl fw-500 mt-4 text-white">
                                                 National Health Workers Performance Management Dashboard
                                                 
                                        <small class="h3 fw-300 mt-3 mb-5 text-white opacity-60">
                                            The Uganda National Health Workers Performance Management Dashboard represents a pivotal advancement in healthcare management, offering a comprehensive platform designed to track, analyze, and optimize the performance of health workers across the nation. Rooted in the principles of data-driven decision-making and continuous improvement, this innovative dashboard serves as a cornerstone in enhancing the efficiency and effectiveness of healthcare delivery systems in Uganda.
                                        </small>
                                    </h2>
                                   
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-5 col-xl-4 ml-auto">


                                  

                                        <!-- alert message -->
                        <?php if ($this->session->flashdata('message') != null) { ?>
                                            <div class="alert alert-info alert-dismissable">
                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                <?php echo $this->session->flashdata('message');
                                                $this->session->unset_userdata('message'); ?>
                                            </div>
                                        <?php } ?>
                                        
                                        <?php if ($this->session->flashdata('exception') != null) { ?>
                                            <div class="alert alert-danger alert-dismissable">
                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                <?php echo $this->session->flashdata('exception');
                                                $this->session->unset_userdata('exception');
                                                ?>
                                            </div>
                                        <?php } ?>
                                        
                                        <?php if (validation_errors()) { ?>
                                            <div class="alert alert-danger alert-dismissable">
                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                <?php echo validation_errors(); ?>
                                            </div>
                                        <?php } ?>
                                      <div class="card p-4 rounded-plus bg-faded">
                                        <div class="d-flex justify-content-center">
                                            <img src="<?php echo base_url() ?>assets_old/images/moh.png" width=130 aria-roledescription="logo">
                                            
                                        </div>
                                        <div class="d-flex justify-content-center">
                                                                      
                                                                            <h2 class="text-black fw-300 mt-10">
                                                                                LOGIN
                                                                            </h2>
                                        </div>
                                        <?php echo form_open('login', 'id="loginForm" novalidate'); ?>
                                            <div class="form-group">
                                                <label class="form-label" for="username">Email</label>
                                                <input type="email" id="username" class="form-control form-control-lg" name="email" placeholder="your id or email" value="" required>
                                              
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="password">Password</label>
                                                <input type="password" id="password" class="form-control form-control-lg" name="password" placeholder="password" value="" required>
                                              
                                            </div>
                                            <div class="form-group text-left">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="rememberme">
                                                    <label class="custom-control-label" for="rememberme"> Remember me </label>
                                                </div>
                                            </div>
                                            <div class="row no-gutters">
                                            
                                                <div class="col-lg-6 pl-lg-1 my-2">
                                                    <button id="js-login-btn" type="submit" class="btn btn-danger btn-block btn-lg"><?php echo display('login') ?></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="position-absolute pos-bottom pos-left pos-right p-3 text-center text-white">
                               <a href='https://health.go.ug' class='text-white opacity-40 fw-500' title='' target='_blank'> Copyright ©  Ministry of Health, All Rights Reserved</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
  
        <p id="js-color-profile" class="d-none">
            <span class="color-primary-50"></span>
            <span class="color-primary-100"></span>
            <span class="color-primary-200"></span>
            <span class="color-primary-300"></span>
            <span class="color-primary-400"></span>
            <span class="color-primary-500"></span>
            <span class="color-primary-600"></span>
            <span class="color-primary-700"></span>
            <span class="color-primary-800"></span>
            <span class="color-primary-900"></span>
            <span class="color-info-50"></span>
            <span class="color-info-100"></span>
            <span class="color-info-200"></span>
            <span class="color-info-300"></span>
            <span class="color-info-400"></span>
            <span class="color-info-500"></span>
            <span class="color-info-600"></span>
            <span class="color-info-700"></span>
            <span class="color-info-800"></span>
            <span class="color-info-900"></span>
            <span class="color-danger-50"></span>
            <span class="color-danger-100"></span>
            <span class="color-danger-200"></span>
            <span class="color-danger-300"></span>
            <span class="color-danger-400"></span>
            <span class="color-danger-500"></span>
            <span class="color-danger-600"></span>
            <span class="color-danger-700"></span>
            <span class="color-danger-800"></span>
            <span class="color-danger-900"></span>
            <span class="color-warning-50"></span>
            <span class="color-warning-100"></span>
            <span class="color-warning-200"></span>
            <span class="color-warning-300"></span>
            <span class="color-warning-400"></span>
            <span class="color-warning-500"></span>
            <span class="color-warning-600"></span>
            <span class="color-warning-700"></span>
            <span class="color-warning-800"></span>
            <span class="color-warning-900"></span>
            <span class="color-success-50"></span>
            <span class="color-success-100"></span>
            <span class="color-success-200"></span>
            <span class="color-success-300"></span>
            <span class="color-success-400"></span>
            <span class="color-success-500"></span>
            <span class="color-success-600"></span>
            <span class="color-success-700"></span>
            <span class="color-success-800"></span>
            <span class="color-success-900"></span>
            <span class="color-fusion-50"></span>
            <span class="color-fusion-100"></span>
            <span class="color-fusion-200"></span>
            <span class="color-fusion-300"></span>
            <span class="color-fusion-400"></span>
            <span class="color-fusion-500"></span>
            <span class="color-fusion-600"></span>
            <span class="color-fusion-700"></span>
            <span class="color-fusion-800"></span>
            <span class="color-fusion-900"></span>
        </p>
  
        <script src="<?php echo base_url()?>assets/js/vendors.bundle.js"></script>
        <script src="<?php echo base_url() ?>assets/js/app.bundle.js"></script>
        <script type="text/javascript">
        setTimeout(function () {

            // Closing the alert
            $('alert').alert('close');
        }, 5000);
</script>
    
    </body>
  
</html>
