<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        <?php echo (!empty($setting->title) ? $setting->title : null) ?> ::
        <?php echo (!empty($title) ? $title : null) ?>
    </title>

    <!-- Favicon and touch icons -->
    <link rel="shortcut icon"
        href="<?php echo base_url((!empty($setting->favicon) ? $setting->favicon : 'assets_old/img/icons/favicon.png')) ?>"
        type="image/x-icon">

    <!-- Start Global Mandatory Style -->
    <!-- Bootstrap -->
    <!-- Favicon and touch icons -->
    <link rel="shortcut icon" href="<?php echo (!empty($setting->favicon) ? $setting->favicon : null) ?>">

    <!-- Bootstrap -->
    <link href="<?php echo base_url(); ?>assets_old/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <?php if (!empty($setting->site_align) && $setting->site_align == "RTL") { ?>
        <!-- THEME RTL -->
        <link href="<?php echo base_url(); ?>assets_old/css/bootstrap-rtl.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets_old/css/custom-rtl.css') ?>" rel="stylesheet" type="text/css" />
    <?php } ?>
    <!-- 7 stroke css -->
    <link href="<?php echo base_url(); ?>assets_old/css/pe-icon-7-stroke.css" rel="stylesheet" type="text/css" />
    <!-- style css -->
    <link href="<?php echo base_url(); ?>assets_old/css/custom.css" rel="stylesheet" type="text/css" />
    <!-- Theme style rtl -->
</head>

<body>
    <!-- Content Wrapper -->
    <div class="login-wrapper">
        <div class="container-center">
            <div class="panel panel-bd">
                <div class="panel-heading">
                    <div class="">
                        <div class="row">
                  
                        <div style="text-align:center;">
                        <img src="<?php echo base_url()?>assets_old/images/moh.png" width=130>

                        
                                <h4>National Health Workers Performance Management Dashboard</h4>
                            <small class="mt-4">
                    
                            </small>
                        </div>
                    </div>
                    
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
                    </div>
                </div>


                <div class="panel-body">
                    <?php echo form_open('login', 'id="loginForm" novalidate'); ?>
                    <div class="form-group">
                        <label class="control-label" for="email">
                            <?php echo display('email') ?>
                        </label>
                        <input type="text" placeholder="<?php echo display('email') ?>" name="email" id="email"
                            class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="password">
                            <?php echo display('password') ?>
                        </label>
                        <input type="password" placeholder="<?php echo display('password') ?>" name="password"
                            id="password" class="form-control">
                    </div>



                    <div>
                        <div style="text-align:center;">
                
                        <button type="submit" class="btn btn-success"> <i class="pe-7s-unlock"></i>
                            <?php echo display('login') ?>
                        </button>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>
<!-- Bootstrap -->
<script src="<?php echo base_url('assets_old/js/jquery-3.4.1.min.js?v=3.4.1') ?>" type="text/javascript"></script>

<script src="<?php echo base_url() ?>assets_old/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script type="text/javascript">
        setTimeout(function () {

            // Closing the alert
            $('alert').alert('close');
        }, 5000);
    </script>

</html>