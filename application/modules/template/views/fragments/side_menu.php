<ul id="js-nav-menu" class="nav-menu">
 <?php if ($this->session->userdata('user_type') == 'admin') { ?>   
<!-- <li>
        <a href="<?php echo base_url();?>dashboard/home" title="Form Stuff" data-filter-tags="form stuff">
            <i class="fal fa-edit"></i>
            <span class="nav-link-text" data-i18n="nav.form_stuff">Main Dashboard</span>
        </a>
    </li> -->
    
 
        <li class="treeview <?php echo (($this->uri->segment(2) == "manage_people") ? "active" : null) ?>">
            <a href="<?php echo base_url(); ?>person/manage_people">
                <i class="fa fa-users"></i>
                <span>Manage Staff </span>
            </a>
        </li>

     <?php } ?>
    <?php if (($this->session->userdata('user_type') == 'staff')||($this->session->userdata('user_type') == 'data')||($this->session->userdata('user_type') == 'admin')|| (get_field($this->session->userdata('ihris_pid'), 'data_role')==1)) { ?>
         <li class="treeview <?php echo (($this->uri->segment(2) == "performance_list") ? "active" : null) ?>">
                <a href="<?php echo base_url(); ?>person/performance_list">
                    <i class="fal fa-chart-pie"></i>
                    <span>Add Performance</span>
                </a>
            </li>
    <?php }?>
    
            <li class="treeview <?php echo (($this->uri->segment(2) == "approve") ? "active" : null) ?>">
        <a href="<?php echo base_url(); ?>person/approve?facility_id=<?=urlencode($this->session->userdata('facility_id'))?>">
            <i class="fa fa-check-circle"></i>
            <span>Approve Performance </span>
        </a>
    </li>

    <li class="treeview <?php echo (($this->uri->segment(2) == "mydata") ? "active" : null) ?>">
            <a href="<?php echo base_url(); ?>person/mydata/1">
                <i class="fa fa-list"></i>
                <span>My Data</span>
            </a>
    </li>
 <?php if ($this->session->userdata('user_type') == 'admin') { ?>  
      <li class="treeview <?php echo (($this->uri->segment(2) == "ihrisconnect") ? "active" : null) ?>">
            <a href="<?php echo base_url(); ?>person/ihrisconnect">
                <i class="fa fa-globe"></i>
                <span>iHRIS Connect</span>
            </a>
    </li>

    <li>
        <a href="<?php echo base_url(); ?>dashboard/slider/department_reporting"> <i class="fa fa-th"></i><span class="nav-link-text"
                data-i18n="<?php echo "Reporting Rates"; ?>">
                <?php echo "Reporting Rates"; ?>
            </span></a>
    </li>
    <?php } ?>



<!-- 
        <li class="treeview <?php echo (($this->uri->segment(3) == "department_reporting") ? "active" : null) ?>">
            <a href="<?php echo base_url(); ?>dashboard/slider/department_reporting">
                <i class="fa fa-list"></i>
                <span>Reporting Rates</span>
            </a>
        </li> -->
  
   
    <!-- 
    <li>
        <a href="<?php echo base_url(); ?>dashboard/slider/department_reporting"> <i class="fa fa-list"></i><span class="nav-link-text" data-i18n="<?php echo "Reporting Rates"; ?>"><?php echo "Reporting Rates"; ?></span></a>
    </li> -->

    <?php

     
   // $subjects = Modules::run('person/focus_areas', get_field($this->session->userdata('ihris_pid'), 'kpi_group_id'));
   // foreach ($subjects as $subject):

        ?>

<?php
   // $url = base_url() . "data/subject/" . $subject->id . "/" . str_replace(',', ' ', str_replace("'", " ", str_replace('&', 'and', str_replace("+", "_", urlencode($subject->subject_area))))); ?>
    <!-- <li>
        <a href="<?php //$url ?>" title="<?php //echo $subject->subject_area; ?>" data-filter-tags="<?php //echo $subject->subject_area; ?>" class="<?php //if ($subject->id == $this->uri->segment(3) || $subject->id == $this->uri->segment(4)) {
                        //echo "active";
                    //} ?>">
            <i class="fa fa-<?php //echo $subject->icon; ?>"></i>
            <span class="nav-link-text" data-i18n="<?php //echo $subject->subject_area; ?>"><?php //echo ellipsize($subject->subject_area, 28, 1); ?></span>
        </a>
    </li> -->
    
    
    <?php //endforeach; ?>







</ul>
