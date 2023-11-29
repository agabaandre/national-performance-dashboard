<ol class="breadcrumb page-breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
    <li class="breadcrumb-item"> <?php if (!empty($uptitle)) {
        echo ' - ' . urldecode($uptitle); }else{  echo $title; }?></li>
    <li class="breadcrumb-item active"><?php echo  'Finacial Year-' . $_SESSION['financial_year'];
    ?></li>
    <li class="position-absolute pos-top pos-right d-none d-sm-block"> <p> <b>Job: <?php echo @get_field($this->session->userdata('ihris_pid'), 'job'); ?>
        </b><b>(Facility:
            <?php echo @get_field($this->session->userdata('ihris_pid'), 'facility'); ?>)
    </b></p></li>
</ol>