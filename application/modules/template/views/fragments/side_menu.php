<ul id="js-nav-menu" class="nav-menu">
 <?php if ($this->session->userdata('user_type') == 'admin') { ?>   
<li>
        <a href="<?php echo base_url();?>dashboard/home" title="Form Stuff" data-filter-tags="form stuff">
            <i class="fal fa-edit"></i>
            <span class="nav-link-text" data-i18n="nav.form_stuff">Main Dashboard</span>
        </a>
    </li>
      <li class="treeview <?php echo (($this->uri->segment(2) == "person") ? "active" : null) ?>">
        <a href="<?php echo base_url(); ?>person/manage_people">
            <i class="fa fa-user"></i>
            <span>Mange iHRIS Staff </span>
        </a>
    </li>
         <li class="treeview <?php echo (($this->uri->segment(2) == "person") ? "active" : null) ?>">
                <a href="<?php echo base_url(); ?>person/performance_list">
                    <i class="fa fa-user"></i>
                    <span>Staff List for Analysis</span>
                </a>
            </li>
    <?php }?>
 <?php if (!empty($_SESSION['ihris_pid'])) { ?>  
        <li class="treeview <?php echo (($this->uri->segment(2) == "person") ? "active" : null) ?>">
            <a href="<?php echo base_url(); ?>person">
                <i class="fa fa-user"></i>
                <span>Add Performance Data </span>
            </a>
        </li>
 <?php }?>

        <li class="treeview <?php echo (($this->uri->segment(3) == "department_reporting") ? "active" : null) ?>">
            <a href="<?php echo base_url(); ?>dashboard/slider/department_reporting">
                <i class="fa fa-list"></i>
                <span>Reporting Rates</span>
            </a>
        </li>
    <!-- <li>
        <a href="<?php echo base_url(); ?>person"> <i class="fa fa-user"></i><span class="nav-link-text" data-i18n="<?php echo "Reporting Rates"; ?>"><?php echo "Add Performance Data"; ?></span></a>
    </li>
    <li>
        <a href="<?php echo base_url(); ?>dashboard/slider/department_reporting"> <i class="fa fa-list"></i><span class="nav-link-text" data-i18n="<?php echo "Reporting Rates"; ?>"><?php echo "Reporting Rates"; ?></span></a>
    </li> -->

    <?php

    $subjects = Modules::run('person/focus_areas', get_field($this->session->userdata('ihris_pid'), 'job_id'));
    foreach ($subjects as $subject):

        ?>

<?php
    $url = base_url() . "data/subject/" . $subject->id . "/" . str_replace(',', ' ', str_replace("'", " ", str_replace('&', 'and', str_replace("+", "_", urlencode($subject->subject_area))))); ?>
    <li>
        <a href="<?= $url ?>" title="<?php echo $subject->subject_area; ?>" data-filter-tags="<?php echo $subject->subject_area; ?>" class="<?php if ($subject->id == $this->uri->segment(3) || $subject->id == $this->uri->segment(4)) {
                        echo "active";
                    } ?>">
            <i class="fa fa-<?php echo $subject->icon; ?>"></i>
            <span class="nav-link-text" data-i18n="<?php echo $subject->subject_area; ?>"><?php echo ellipsize($subject->subject_area, 28, 1); ?></span>
        </a>
    </li>
    
    
    <?php endforeach; ?>







</ul>
