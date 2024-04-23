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
        <a href="<?php echo base_url(); ?>person/approve">
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

  
    <?php } ?>
      <li>
        <a href="<?php echo base_url(); ?>dashboard/slider/facility_reporting"> <i class="fa fa-th"></i><span
                class="nav-link-text" data-i18n="<?php echo "Performance By Facility "; ?>">
                <?php echo "Performance Report"; ?>
            </span></a>
    </li>




        <li class="treeview <?php echo (($this->uri->segment(3) == "department_reporting") ? "active" : null) ?>">
            <a href="<?php echo base_url(); ?>dashboard/slider/person_reporting_rate">
                <i class="fa fa-list"></i>
                <span>Reporting Rates</span>
            </a>
        </li> 
  
   

</ul>
