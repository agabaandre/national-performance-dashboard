<?php include('fragments/cssfiles.php');?>

    <body class="mod-bg-1 mod-nav-link ">
        <?php include('fragments/js_settings.php'); ?>

        <!-- BEGIN Page Wrapper -->
        <div class="page-wrapper">
            <div class="page-inner">
          <?php include('fragments/aside.php')?>
          
                <div class="page-content-wrapper">
                    <!-- BEGIN Page Header -->
                <?php include('fragments/header.php') ?>
                    <!-- END Page Header -->
                    <!-- BEGIN Page Content -->
                    <!-- the #js-page-content id is needed for some plugins to initialize -->
        <main id="js-page-content" role="main" class="page-content">

                      <?php include('fragments/breadcrumb.php');?>
                    <?php include('fragments/messages.php'); 
               
                    ?>
                            <?php
                          // print_r($this->session->userdata());
                            echo $this->load->view($module . '/' . $page);
                            ?>
        </main>
                    <!-- this overlay is activated only when mobile menu is triggered -->
                    <div class="page-content-overlay" data-action="toggle" data-class="mobile-nav-on"></div> <!-- END Page Content -->
                    <!-- BEGIN Page Footer -->
                    <?php include('fragments/footer.php'); ?>
                    <!-- END Page Footer -->
                    <!-- BEGIN Shortcuts -->
                    <?php include('fragments/shortcuts.php'); ?>
                    <!-- END Shortcuts -->
                    <!-- BEGIN Color profile -->
                    <!-- this area is hidden and will not be seen on screens or screen readers -->
                    <!-- we use this only for CSS color refernce for JS stuff -->
                    <?php include('fragments/css_reference_staff.php'); ?>
                    <!-- END Color profile -->
                </div>
            </div>
        </div>
        <!-- END Page Wrapper -->
        <!-- BEGIN Quick Menu -->
        <!-- to add more items, please make sure to change the variable '$menu-items: number;' in your _page-components-shortcut.scss -->
        <?php include('fragments/quick_menu.php'); ?>
        <!-- END Quick Menu -->
        <!-- BEGIN Messenger -->
        <div class="modal fade js-modal-messenger modal-backdrop-transparent" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-right">
                <div class="modal-content h-100">
                    <!-- Dropdown header Hakim commentent this -->
                    <?php include('fragments/dropdown_header.php'); ?>
                    <!-- Dropdown header Hakim commentent this -->
                    <div class="modal-body p-0 h-100 d-flex">
                        <!-- BEGIN msgr-list -->
                        <?php include('fragments/msgr_list.php'); ?>
                        <!-- END msgr-list -->
                        <!-- BEGIN msgr -->
                        <div class="msgr d-flex h-100 flex-column bg-white">
                            <!-- BEGIN custom-scroll -->
                            <?php include('fragments/custom_scroll.php'); ?>
                            <!-- END custom-scroll  -->
                            <!-- BEGIN msgr__chatinput -->
                            <?php include('fragments/msgr_chat_input.php'); ?>
                            <!-- END msgr__chatinput -->
                        </div>
                        <!-- END msgr -->
                    </div>
                </div>
            </div>
        </div>
        <!-- END Messenger -->
        <!-- BEGIN Page Settings -->
        <?php include('fragments/page_settings.php'); ?>
        <!-- END Page Settings -->
        <?php include('fragments/page_settings_footer_js.php'); ?>

            <!-- Modal -->
    <div class="modal fade" id="definition" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Defintions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-6"></div>
                    <h3>Gauge Color Themes</h3>
                    <table class="table tabble-reposnive">
                        <thead>
                            <tr>
                                <th>Color</th>
                                <th>definition</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style=" background-color:gray;"></td>
                                <td>Above Performance</td>
                            </tr>
                            <tr>
                                <td style=" background-color:green;"></td>
                                <td>Good Performance</td>
                            </tr>
                            <tr>
                                <td style=" background-color:orange;"></td>
                                <td>Average Performance</td>
                            </tr>
                            <tr>
                                <td style=" background-color:red;"></td>
                                <td>Bad Performance</td>
                            </tr>


                        </tbody>
                    </table>
                    <div class="col-md-6"></div>
                    <h3>Arrows</h3>
                    <p><i class="fa fa-arrow-down" style="color:red;"></i> A decline in the current period value
                        compared to the previous period value </p>
                    <p><i class="fa fa-arrow-up" style="color:green;"></i> A improvement in the current period value
                        compared to the previous period value </p>
                    <p><i class="fa fa-arrow-right" style="color:orange;"></i> No change in the current period value
                        compared to the previous period value </p>
                </div>

                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>

    
    </body>
    <!-- END Body -->

<!-- Mirrored from www.gotbootstrap.com/themes/smartadmin/4.5.1/docs_project_structure.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 30 Oct 2023 13:08:04 GMT -->
</html>
