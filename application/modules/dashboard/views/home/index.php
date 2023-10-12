
                        


<style>
.highcharts-figure{
    background:#FEFFFF;
    
}
</style>

<?php 

if(($this->uri->segment(1)=="dashboard")||($this->uri->segment(1)=="")){
    
    $col="col-md-".$setting->dash_rows;
    }
    else{
        $col="col-md-".$setting->kpi_rows;
    }
                                
?>

<!--gauge-->
   <?php foreach($jobs as $job):?>
   <div class="<?php echo $col ?>" style="text-align:center;  padding:4px; margin-bottom:40px;">
    <a href="<?php echo base_url().'person/'.$job->job_id?>"> <?php echo $job->job; ?>
   </div>
   <?php endforeach;?>
       


  








      