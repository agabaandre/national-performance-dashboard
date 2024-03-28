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

    function getColorBasedOnPerformance($value, $target)
    {
        //ratios -
        $performance = (($value/$target)*100);
        if(!empty($performance)){
        if ($performance < 50) {
            return 'red';
        } elseif ($performance >= 50 && $performance < 75) {
            return 'orange';
        } 
        elseif ($performance >= 75)  {
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

    if (!function_exists('ci_paginate')) {
        function ci_paginate($route, $totals, $perPage = 20, $segment = 2)
        {
            $ci = &get_instance();
            $config = array();

            //get_search_links gets us all data from search form as flashed

            $config["base_url"] = base_url() . $route;
            $config["total_rows"] = $totals;
            $config["per_page"] = $perPage;
            $config["uri_segment"] = $segment;
            $config['reuse_query_string'] = true;
            $config['full_tag_open'] = '<br> <nav><ul class="pagination">';
            $config['full_tag_close'] = '</ul></nav>';
            $config['first_link'] = 'first';
            $config['attributes'] = ['class' => 'page-link'];
            $config['last_link'] = 'last';
            $config['first_tag_open'] = '<li class="page-item">';
            $config['first_tag_close'] = '</li>';
            $config['prev_link'] = '&laquo';
            $config['prev_tag_open'] = '<li class="page-item prev">';
            $config['prev_tag_close'] = '</li>';
            $config['next_link'] = '&raquo';
            $config['next_tag_open'] = '<li class="page-item">';
            $config['next_tag_close'] = '</li>';
            $config['last_tag_open'] = '<li class="page-item">';
            $config['last_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="active page-item"><a class="page-link" href="#">';
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li class="page-item">';
            $config['num_tag_close'] = '</li>';

            $ci->pagination->initialize($config);

            return $ci->pagination->create_links();
        }
    }
    function getkpi_info($kpi_id)
    {
        $ci =& get_instance();
        $ci->load->database();

       if(!empty($kpi_id)){
        $query = $ci->db->query("SELECT * from kpi where kpi_id='$kpi_id'");
     return @$row = $query->row();
       }
       else
    return "";

       

    }

    

}
