<?php

$subjects = Modules::run('person/focus_areas', get_field($this->session->userdata('ihris_pid'), 'job_id'));
foreach ($subjects as $subject):

  ?>
  <li class="treeview <?php if ($subject->id == $this->uri->segment(3) || $subject->id == $this->uri->segment(4))  { echo "active";} ?>">
    <?php
    $url = base_url() . "data/subject/" . $subject->id . "/" . str_replace(',', ' ', str_replace("'", " ", str_replace('&', 'and', str_replace("+", "_", urlencode($subject->subject_area))))); ?>
    <a href="<?= $url ?>" title="<?php echo $subject->subject_area; ?>" target="_self"><i class="fa fa-<?php echo $subject->icon; ?>"></i><span><?php echo ellipsize($subject->subject_area, 28, 1); ?></span></a>

  </li>

<?php endforeach;
?>
