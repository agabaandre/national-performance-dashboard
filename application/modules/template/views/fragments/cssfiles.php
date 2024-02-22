<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>
        <?php echo "NHWPD-" . @$title; ?>
    </title>
    <meta name="description" content="Project Structure">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
    <!-- Call App Mode on ios devices -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <!-- Remove Tap Highlight on Windows Phone IE -->
    <meta name="msapplication-tap-highlight" content="no">
    <!-- base css -->
    <link href="<?php echo base_url(); ?>assets/css/select2.min.css" rel="stylesheet" type="text/css" />


    <link id="mytheme" rel="stylesheet" media="screen, print" href="#">
    <link id="myskin" rel="stylesheet" media="screen, print" href="<?= base_url() ?>assets/css/skins/skin-master.css">
    <!-- Place favicon.ico in the root directory -->
    <link id="vendorsbundle" rel="stylesheet" media="screen, print"
        href="<?= base_url() ?>assets/css/vendors.bundle.css">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url() ?>assets/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url() ?>assets/img/favicon/favicon-32x32.png">
    <link rel="mask-icon" href="<?= base_url() ?>assets/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="stylesheet" media="screen, print" href="<?= base_url() ?>assets/css/fa-solid.css">
    <link rel="stylesheet" media="screen, print"
     href="<?= base_url() ?>assets/css/json-path-picker/json-path-picker.css">
    <link rel="stylesheet" media="screen, print"
    href="<?= base_url() ?>assets/css/datagrid/datatables/datatables.bundle.css">
    <script src="<?php echo base_url('assets/js/jquery-3.4.1.min.js?v=3.4.1') ?>" type="text/javascript"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
        <link id="appbundle" rel="stylesheet" media="screen, print" href="<?= base_url() ?>assets/css/app.bundle.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.bootstrap4.min.css" integrity="sha512-ht3CSPjgWsxdbLti7wtKNEk5hLoGtP2J8C40muB5/PCWwNw9M/NMJpyvHdeko7ADC60SEOiCenU5pg+kJiG9lg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
 <style>
    .select2-container--default .select2-selection--single {
    background-color: #fff;
    border: 1px solid #ced4da !important;
    border-radius: 4px;
    padding: 16px;
 
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #444;
    line-height: 6px;
    overflow: revert;
   text-overflow: ellipsis !important;
  
}
select2-container .select2-selection--single .select2-selection__rendered {
    display: block;
    padding-left: 8px;
    padding-right: 20px;
    text-overflow: ellipsis;
    white-space: nowrap;
}

</style>
  

</head>