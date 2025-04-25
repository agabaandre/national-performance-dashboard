<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Slider extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->db->query('SET SESSION sql_mode = ""');

        $this->load->model('slider_model');
    }

    function index()
    {
        $data['module'] = "dashboard";
        $data['page'] = "home/slider";
        $data['uptitle'] = "Performance Over View";
        $data['title'] = "Perfomance";
        echo Modules::run('template/layout', $data);
    }

    public function getsummaries($kpi)
    {

        return $this->slider_model->slider_data($kpi);
    }
    public function getsubjects()
    {
        $data = $this->slider_model->get_subjects();
        //print_r($data);
        return $data;
    }
    public function getkpi($subject)
    {
        $data = $this->slider_model->getkpis($subject);
        //print_r($data);
        return $data;
    }

    public function reporting_rate($subject)
    {
        $query = $this->db->query("SELECT distinct new_data.kpi_id from new_data join kpi on kpi.kpi_id=new_data.kpi_id WHERE kpi.subject_area='$subject'")->num_rows();
        return $query;
    }

    function facility_reporting()
    {
        $data['module'] = "dashboard";
        $data['page'] = "home/reporting_rates";
        $data['uptitle'] = "KPI Reporting Rates";
        $data['title'] = "Reporting by Job";
        if (!empty($this->session->userdata('ihris_pid')) && ($this->session->userdata('user_type') == 'staff')) {
            $ihris_pid = $this->session->userdata('ihris_pid');
            $data['kpigroups'] = $this->db->query("SELECT job_id, job FROM kpi_job_category WHERE CONVERT(job_id USING utf8) IN (SELECT DISTINCT CONVERT(job_category_id USING utf8)  FROM  performanace_data WHERE ihris_pid ='$ihris_pid')")->result();
        } else {
            $data['kpigroups'] = $this->db->query("SELECT job_id, job FROM kpi_job_category WHERE CONVERT(job_id USING utf8) IN (SELECT DISTINCT CONVERT(job_id USING utf8) FROM kpi)")->result();
        }

        echo Modules::run('template/layout', $data);
    }

    // function person_reporting_rate()
    // {
    //     $data['module'] = "dashboard";
    //     $data['page'] = "home/person_reporting_rate";
    //     $data['uptitle'] = "Employee Reporting Rates";
    //     $data['title'] = "Reporting Rates";
    //     if (!empty($this->session->userdata('ihris_pid')) && ($this->session->userdata('user_type') == 'staff')) {
    //         $ihris_pid = $this->session->userdata('ihris_pid');
    //         $data['kpigroups'] = $this->db->query("SELECT job_id, job FROM kpi_job_category WHERE CONVERT(job_id USING utf8) IN (SELECT DISTINCT CONVERT(job_category_id USING utf8)  FROM  performanace_data WHERE ihris_pid ='$ihris_pid')")->result();
    //     } else {
    //         $data['kpigroups'] = $this->db->query("SELECT job_id, job FROM kpi_job_category WHERE CONVERT(job_id USING utf8) IN (SELECT DISTINCT CONVERT(job_id USING utf8) FROM kpi)")->result();

    //     }



    //     echo Modules::run('template/layout', $data);
    // }

    public function person_reporting_rate()
    {
        $this->load->library('pagination');
        $this->load->helper('url');
    
        $data['module'] = "dashboard";
        $data['page'] = "home/person_reporting_rate";
        $data['uptitle'] = "Employee Reporting Rates";
        $data['title'] = "Reporting Rates";
    
        $job_cat = $this->input->get('kpi_group', TRUE); // Correct field name here
        $search = $this->input->get('search', TRUE);
        $user_facility_id = $this->session->userdata('facility_id'); // Facility in session
        $selected_facility_id = $this->input->get('facility_id', TRUE);
    
        // If no facility selected in filter, fallback to user's session facility
        $facility_id_filter = !empty($selected_facility_id) ? $selected_facility_id : $user_facility_id;
    
        // KPI groups
        if (!empty($this->session->userdata('ihris_pid')) && ($this->session->userdata('user_type') == 'staff')) {
            $ihris_pid = $this->session->userdata('ihris_pid');
            $data['kpigroups'] = $this->db->query("
                SELECT job_id, job 
                FROM kpi_job_category 
                WHERE CONVERT(job_id USING utf8) IN (
                    SELECT DISTINCT CONVERT(job_category_id USING utf8) 
                    FROM performanace_data 
                    WHERE ihris_pid = '$ihris_pid'
                )
            ")->result();
        } else {
            $data['kpigroups'] = $this->db->query("
                SELECT job_id, job 
                FROM kpi_job_category 
                WHERE CONVERT(job_id USING utf8) IN (
                    SELECT DISTINCT CONVERT(job_id USING utf8) 
                    FROM kpi
                )
            ")->result();
        }
    
        // Build WHERE clause
        $where = "WHERE 1=1";
    
        if (!empty($facility_id_filter)) {
            $where .= " AND new_data.facility = " . $this->db->escape($facility_id_filter);
        }
    
        if (!empty($search)) {
            $where .= " AND ihrisdata.facility LIKE " . $this->db->escape("%$search%");
        }
    
        // Count total facilities for pagination
        $total_rows = $this->db->query("
            SELECT COUNT(*) AS total FROM (
                SELECT new_data.facility
                FROM new_data 
                JOIN ihrisdata ON new_data.facility = ihrisdata.facility_id
                $where
                GROUP BY new_data.facility
            ) AS grouped
        ")->row()->total;
    
        // Pagination vars
        $per_page = 2;
        $uri_segment = 4;
        $page = (int) $this->uri->segment($uri_segment, 0);
        $offset = ($page > 0) ? $page : 0;
    
        // Fetch paginated facilities
        $facilities = $this->db->query("
            SELECT new_data.facility AS facility_id, ihrisdata.facility
            FROM new_data 
            JOIN ihrisdata ON new_data.facility = ihrisdata.facility_id
            $where
            GROUP BY new_data.facility, ihrisdata.facility
            ORDER BY ihrisdata.facility ASC
            LIMIT $per_page OFFSET $offset
        ")->result();
    
        foreach ($facilities as $facility) {
            $facility->staff = $this->get_staff($facility->facility_id, $job_cat);
        }
    
        $data['facilities'] = $facilities;
        $data['pagination'] = ci_paginate('dashboard/slider/person_reporting_rate', $total_rows, $per_page, $uri_segment);
        $data['search'] = $search;
        $data['job_cat'] = $job_cat;
        $data['facility_id'] = $facility_id_filter; // Pass selected facility to view
    
        echo Modules::run('template/layout', $data);
    }
    


    public function get_staff($facility_id, $job_c = FALSE)
    {
        if (!empty($job_c)) {
            $job_cat = $job_c;
        } else {
            $job_cat = $this->input->get('kpi_group');
        }

        if (!empty($job_cat)) {
            $job = "and kpi_group_id=$job_cat";
        }
        if (!empty($this->session->userdata('ihris_pid')) && ($this->session->userdata('user_type') == 'staff')) {
            $ihris_pid = $this->session->userdata('ihris_pid');

            return $this->db->query("SELECT DISTINCT ihris_pid, surname,firstname,kpi_group_id as job_category_id  from ihrisdata where facility_id='$facility_id' $job and ihris_pid='$ihris_pid' and kpi_group_id!=''")->result();
        } else {
            return $this->db->query("SELECT DISTINCT ihris_pid, surname,firstname,kpi_group_id as job_category_id from ihrisdata where facility_id='$facility_id' $job and kpi_group_id!=''")->result();
        }
    }

    public function get_reporting_rate($ihris_pid, $qtr, $fy, $job)
    {
        // Get the number of distinct KPIs with data that match the subject area, quarter, and financial year
        // $kpis_with_data = $this->db->query("SELECT COUNT(DISTINCT new_data.kpi_id) as kpis_with_data FROM new_data JOIN kpi ON kpi.kpi_id = new_data.kpi_id WHERE new_data.ihris_pid = '$ihris_pid' AND new_data.period = '$qtr' AND new_data.financial_year = '$fy' and (new_data.numerator IS NOT NULL or new_data.numerator!='')")->row()->kpis_with_data;
        $query = $this->db->query("
                    SELECT COUNT(DISTINCT new_data.kpi_id) as kpis_with_data
                    FROM new_data
                    JOIN kpi ON kpi.kpi_id = new_data.kpi_id
                    WHERE new_data.ihris_pid = ?
                    AND new_data.period = ?
                    AND new_data.financial_year = ? AND draft_status=1
                    AND (new_data.numerator IS NOT NULL OR new_data.numerator != '')
                ", array($ihris_pid, $qtr, $fy));

        $kpis_with_data = $query->row()->kpis_with_data;

        // Get the total number of KPIs that match the subject area
        $total_kpis = $this->db->query("SELECT COUNT(kpi_id) as total_kpis FROM kpi WHERE job_id='$job'")->row()->total_kpis;

        // Get the number of distinct quarters that match the financial year and quarter
        $qtrs = $this->db->query("SELECT COUNT(DISTINCT period) as total_qtrs FROM new_data WHERE financial_year = '$fy' AND period = '$qtr'")->row()->total_qtrs;

        // Initialize the color variable
        $color = "";

        // Calculate the reporting rate as a percentage
        if ($total_kpis > 0) {
            $reporting_rate = ($kpis_with_data / $total_kpis) * 100;
        } else {
            $reporting_rate = null;
        }

        // Set the color based on the reporting ratio

        if ($qtrs > 0) {
            if ($reporting_rate === null) {
                $color = "style='background-color:#FF0000; color:#FFF;'";
            } elseif ($reporting_rate < 75) {
                $color = "style='background-color:#de1a1a; color:#FFF;'";
            } elseif ($reporting_rate >= 75 && $reporting_rate < 95) {
                $color = "style='background-color:#FFA500; color:#FFF;'";
            } elseif ($reporting_rate >= 95) {
                $color = "style='background-color:#008000; color:#FFF;'";
            }
        } else {
            $color = "style='background-color:grey; color:grey;'";
        }
        $status = $kpis_with_data . '/' . $total_kpis;
        return (object) ['report_status' => "$status", 'color' => "$color"];
    }
}
