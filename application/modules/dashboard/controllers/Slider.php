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
        $this->load->driver('cache'); // Load caching system
    
        $data['module'] = "dashboard";
        $data['page'] = "home/person_reporting_rate";
        $data['uptitle'] = "Employee Reporting Rates";
        $data['title'] = "Reporting Rates";
    
        // Filters
        $job_cat = $this->input->get('kpi_group', TRUE);
        $search = $this->input->get('search', TRUE);
        $selected_facility_id = $this->input->get('facility_id', TRUE);
        $user_facility_id = $this->session->userdata('facility_id');
        $financial_year = $this->input->get('financial_year') ?? $this->session->userdata('financial_year');
    
        // Final facility filter
        $final_facility_id = !empty($selected_facility_id) ? $selected_facility_id : $user_facility_id;
    
        // Pagination Setup
        $per_page = 2;
        $uri_segment = 4;
        $page = (int) $this->uri->segment($uri_segment, 0);
        $offset = ($page > 0) ? $page : 0;
    
        // --- Build Cache Key ---
        $cache_key = 'person_reporting_rate_' . md5($financial_year . '_' . $final_facility_id . '_' . $job_cat . '_' . $search . '_page_' . $page);
    
        // --- Try to Load Cached Data ---
        $cached_data = $this->cache->file->get($cache_key);
    
        if ($cached_data !== FALSE) {
            echo Modules::run('template/layout', $cached_data);
            return;
        }
    
        // --- No Cache: Fetch Fresh Data ---
    
        // Load KPI Job Groups
        $ihris_pid = $this->session->userdata('ihris_pid');
        $user_type = $this->session->userdata('user_type');
    
        if (!empty($ihris_pid) && $user_type == 'staff') {
            $data['kpigroups'] = $this->db->query("
                SELECT job_id, job 
                FROM kpi_job_category 
                WHERE job_id IN (
                    SELECT DISTINCT job_category_id 
                    FROM performanace_data 
                    WHERE ihris_pid = " . $this->db->escape($ihris_pid) . "
                )
            ")->result();
        } else {
            $data['kpigroups'] = $this->db->query("
                SELECT job_id, job 
                FROM kpi_job_category
                WHERE job_id IN (SELECT DISTINCT job_id FROM kpi)
            ")->result();
        }
    
        // --- Build WHERE ---
        $where = [];
        if (!empty($final_facility_id)) {
            $where[] = "new_data.facility = " . $this->db->escape($final_facility_id);
        }
        if (!empty($search)) {
            $where[] = "ihrisdata.facility LIKE " . $this->db->escape("%$search%");
        }
        $where_clause = (!empty($where)) ? 'WHERE ' . implode(' AND ', $where) : '';
    
        // --- Total Facilities (Optimized) ---
        $total_rows = $this->db->query("
            SELECT COUNT(DISTINCT new_data.facility) AS total
            FROM new_data
            JOIN ihrisdata ON new_data.facility = ihrisdata.facility_id
            $where_clause
        ")->row()->total;
    
        // --- Fetch Facilities ---
        $facilities = $this->db->query("
            SELECT DISTINCT new_data.facility AS facility_id, ihrisdata.facility
            FROM new_data
            JOIN ihrisdata ON new_data.facility = ihrisdata.facility_id
            $where_clause
            ORDER BY ihrisdata.facility ASC
            LIMIT $per_page OFFSET $offset
        ")->result();
    
        // --- Batch Staff Loading ---
        $facility_ids = array_column($facilities, 'facility_id');
        $staff_map = [];
        if (!empty($facility_ids)) {
            $staff_where = "WHERE facility_id IN (" . implode(',', array_map('intval', $facility_ids)) . ") AND kpi_group_id != ''";
    
            if (!empty($ihris_pid) && $user_type == 'staff') {
                $staff_where .= " AND ihris_pid = " . $this->db->escape($ihris_pid);
            }
    
            if (!empty($job_cat)) {
                $staff_where .= " AND kpi_group_id = " . $this->db->escape($job_cat);
            }
    
            $staff_records = $this->db->query("
                SELECT ihris_pid, surname, firstname, kpi_group_id AS job_category_id, facility_id
                FROM ihrisdata
                $staff_where
            ")->result();
    
            foreach ($staff_records as $s) {
                $staff_map[$s->facility_id][] = $s;
            }
        }
    
        // Attach staff to facility
        foreach ($facilities as $f) {
            $f->staff = isset($staff_map[$f->facility_id]) ? $staff_map[$f->facility_id] : [];
        }
    
        // --- Batch Reporting Rates Loading ---
        $staff_ids = [];
        $staff_jobs = [];
        foreach ($facilities as $facility) {
            foreach ($facility->staff as $staff) {
                $staff_ids[] = $staff->ihris_pid;
                $staff_jobs[$staff->ihris_pid] = $staff->job_category_id;
            }
        }
    
        $reporting_rates = [];
        if (!empty($staff_ids)) {
            $ids_in = "'" . implode("','", array_map('addslashes', $staff_ids)) . "'";
            $rates_data = $this->db->query("
                SELECT new_data.ihris_pid, new_data.period, COUNT(DISTINCT new_data.kpi_id) AS kpis_with_data
                FROM new_data
                JOIN kpi ON kpi.kpi_id = new_data.kpi_id
                WHERE new_data.ihris_pid IN ($ids_in)
                  AND new_data.financial_year = " . $this->db->escape($financial_year) . "
                  AND new_data.draft_status = 1
                  AND (new_data.numerator IS NOT NULL OR new_data.numerator != '')
                GROUP BY new_data.ihris_pid, new_data.period
            ")->result();
    
            foreach ($rates_data as $row) {
                $reporting_rates[$row->ihris_pid][$row->period] = $row->kpis_with_data;
            }
        }
    
        // --- Preload Job KPI Totals ---
        $job_totals = [];
        if (!empty($staff_jobs)) {
            $job_ids_in = implode(',', array_unique(array_map('intval', $staff_jobs)));
            $kpi_totals = $this->db->query("
                SELECT job_id, COUNT(kpi_id) AS total_kpis
                FROM kpi
                WHERE job_id IN ($job_ids_in)
                GROUP BY job_id
            ")->result();
    
            foreach ($kpi_totals as $k) {
                $job_totals[$k->job_id] = $k->total_kpis;
            }
        }
    
        // --- Final Data ---
        $data['facilities'] = $facilities;
        $data['reporting_rates'] = $reporting_rates;
        $data['job_totals'] = $job_totals;
        $data['financial_year'] = $financial_year;
        $data['pagination'] = ci_paginate('dashboard/slider/person_reporting_rate', $total_rows, $per_page, $uri_segment);
        $data['search'] = $search;
        $data['job_cat'] = $job_cat;
        $data['facility_id'] = $final_facility_id;
    
        // --- Save to Cache (30 seconds) ---
        $this->cache->file->save($cache_key, $data, 30);
    
        // --- Render View ---
        echo Modules::run('template/layout', $data);
    }
    
    
    
    

    public function get_staff($facility_id, $job_c = FALSE)
    {
        $this->load->driver('cache'); // Load CodeIgniter cache driver
    
        $cache_key = 'staff_list_' . $facility_id;
    
        if (!empty($job_c)) {
            $cache_key .= '_job_' . $job_c;
        } elseif (!empty($this->input->get('kpi_group'))) {
            $cache_key .= '_job_' . $this->input->get('kpi_group');
        }
    
        if (!empty($this->session->userdata('ihris_pid')) && $this->session->userdata('user_type') == 'staff') {
            $cache_key .= '_pid_' . $this->session->userdata('ihris_pid');
        }
    
        // Try to get staff list from cache first
        $staff_list = $this->cache->file->get($cache_key);
    
        if ($staff_list !== FALSE) {
            return $staff_list; // Cache hit
        }
    
        // If not in cache, fetch from database
        $this->db->select('DISTINCT ihris_pid, surname, firstname, kpi_group_id AS job_category_id');
        $this->db->from('ihrisdata');
        $this->db->where('facility_id', $facility_id);
        $this->db->where('kpi_group_id !=', '');
    
        if (!empty($job_c)) {
            $this->db->where('kpi_group_id', $job_c);
        } elseif (!empty($this->input->get('kpi_group'))) {
            $this->db->where('kpi_group_id', $this->input->get('kpi_group'));
        }
    
        if (!empty($this->session->userdata('ihris_pid')) && $this->session->userdata('user_type') == 'staff') {
            $this->db->where('ihris_pid', $this->session->userdata('ihris_pid'));
        }
    
        $query = $this->db->get();
        $staff_list = $query->result();
    
        // Save result into cache for 60 seconds
        $this->cache->file->save($cache_key, $staff_list, 60); // Cache for 1 minute
    
        return $staff_list;
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
