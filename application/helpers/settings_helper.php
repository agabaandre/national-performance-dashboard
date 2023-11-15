<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/*
*
  Retrieves and avails app settings
*
*/


if (!function_exists('settings')) {

    function settings($text = FALSE)
    {
        $ci =& get_instance();
       // $ci->load->database();
        $table  = 'setting';
  
        $settings = $ci->db->get($table)->row();
        $menu = $settings->use_category_two;
        if($menu==0):
        return $menu='traditional_menu.php';
        endif;
        if($menu==1):
          return $menu='general_kpi_menu.php';
        endif;
        if($menu==2):
          return $menu='category_two_menu.php';
         endif;
      
    }
 
}
if (!function_exists('generate_kpi_id')) {


function generate_kpi_id($user_id)
{
    $ci =& get_instance();
    $ci->load->database();

    // Generate an initial KPI ID
    $newKPIId = 'KPI-' . ($user_id . date('s'));

    // Check if the KPI ID already exists in the database
    $is_duplicate = true;
    $attempt = 0;

    while ($is_duplicate && $attempt < 10) {
        $query = $ci->db->query("SELECT * from kpi where kpi_id='$newKPIId'");
        $row = $query->row();

        if (!$row) {
            $is_duplicate = false;
        } else {
            // Regenerate the KPI ID and try again
            $attempt++;
            $newKPIId = 'KPI-' . ($user_id . date('s') . $attempt);
        }
    }

    return $newKPIId;

}

    function getColorBasedOnPerformance($performance, $target, $kpi_type=FALSE)
    {
        //ratios -
        if(!empty($performance)){
        if ($performance < 50) {
            return 'red';
        } elseif ($performance >= 50 && $performance < 75) {
            return 'orange';
        } 
        elseif ($performance >= 75 && $performance <=100)  {
            return 'green';
        }
        else{
            //if there is no target
            return '#088F8F';	
        }
      }
      else{
        return "";
      }

    }

 function get_performance($kpi_id, $quarter, $financial_year, $person)
{
    $ci = &get_instance();
   // $ci->load->database();

    $query = $ci->db
        ->select('current_value as performance, target_value, period as quarter, financial_year, ihris_pid')
        ->from('report_kpi_trend')
        ->where('period', $quarter)
        ->where('financial_year', $financial_year)
        ->where('kpi_id', $kpi_id)
        ->where('ihris_pid', $person)
        ->get();
    

    return $query->row();
}

    

}
