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
        $this->load->helper('url');
    
        $data['module'] = "dashboard";
        $data['page'] = "home/person_reporting_rate";
        $data['uptitle'] = "Employee Reporting Rates";
        $data['title'] = "Reporting Rates";
    
        // Load KPI Job Groups for filter dropdown
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
    
        // Get current financial year as default
        $data['current_financial_year'] = $this->session->userdata('financial_year');
    
        // Load the page
        echo Modules::run('template/layout', $data);
    }

    /**
     * AJAX endpoint for person reporting rates with pagination and search
     * OPTIMIZED: Reduced queries and improved efficiency
     */
    public function ajax_person_reporting_rates()
    {
        header('Content-Type: application/json');
    
        // Filters
        $job_cat = $this->input->get('kpi_group', TRUE);
        $search = $this->input->get('search', TRUE);
        $selected_facility_id = $this->input->get('facility_id', TRUE);
        $user_facility_id = $this->session->userdata('facility_id');
        $financial_year = $this->input->get('financial_year') ?: $this->session->userdata('financial_year');
        $page = (int)$this->input->get('page') ?: 0;
        $per_page = (int)$this->input->get('per_page') ?: 5;
    
        // Final facility filter
        $final_facility_id = !empty($selected_facility_id) ? $selected_facility_id : $user_facility_id;
    
        try {
            $ihris_pid = $this->session->userdata('ihris_pid');
            $user_type = $this->session->userdata('user_type');
    
            // --- Build WHERE clause for facilities ---
            $where = [];
            $where_params = [];
            
            if (!empty($final_facility_id)) {
                $where[] = "nd.facility = ?";
                $where_params[] = $final_facility_id;
            }
            if (!empty($search)) {
                $where[] = "id.facility LIKE ?";
                $where_params[] = '%' . $search . '%';
            }
            $where_clause = (!empty($where)) ? 'WHERE ' . implode(' AND ', $where) : '';
    
            // --- OPTIMIZED: Single query for facilities count and pagination ---
            $offset = $page * $per_page;
            
            // Get total count (optimized with subquery)
            $count_query = "SELECT COUNT(DISTINCT nd.facility) AS total
                           FROM (
                               SELECT DISTINCT facility 
                               FROM new_data
                               " . (!empty($final_facility_id) ? "WHERE facility = " . $this->db->escape($final_facility_id) : "") . "
                           ) AS nd
                           JOIN ihrisdata id ON nd.facility = id.facility_id
                           " . (!empty($search) ? "WHERE id.facility LIKE " . $this->db->escape('%' . $search . '%') : "");
            
            $total_rows = $this->db->query($count_query)->row()->total;
    
            // Fetch facilities with pagination (optimized)
            $facilities_query = "SELECT DISTINCT nd.facility AS facility_id, id.facility
                                FROM (
                                    SELECT DISTINCT facility 
                                    FROM new_data
                                    " . (!empty($final_facility_id) ? "WHERE facility = " . $this->db->escape($final_facility_id) : "") . "
                                ) AS nd
                                JOIN ihrisdata id ON nd.facility = id.facility_id
                                " . (!empty($search) ? "WHERE id.facility LIKE " . $this->db->escape('%' . $search . '%') : "") . "
                                ORDER BY id.facility ASC
                                LIMIT $per_page OFFSET $offset";
            
            $facilities = $this->db->query($facilities_query)->result();
            $facility_ids = array_column($facilities, 'facility_id');
    
            if (empty($facility_ids)) {
                echo json_encode([
                    'success' => true,
                    'data' => [],
                    'pagination' => [
                        'total_count' => $total_rows,
                        'current_page' => $page,
                        'per_page' => $per_page,
                        'total_pages' => ceil($total_rows / $per_page)
                    ]
                ]);
                return;
            }
    
            // --- OPTIMIZED: Single query to get staff with job names and categories ---
            $facility_ids_in = implode(',', array_map('intval', $facility_ids));
            $staff_conditions = "i.facility_id IN ($facility_ids_in) AND i.kpi_group_id != '' AND i.is_active = 1";
    
            if (!empty($ihris_pid) && $user_type == 'staff') {
                $staff_conditions .= " AND i.ihris_pid = " . $this->db->escape($ihris_pid);
            }
    
            if (!empty($job_cat)) {
                $staff_conditions .= " AND i.kpi_group_id = " . $this->db->escape($job_cat);
            }
    
            // Single query to get staff with job category names and job names
            $staff_query = "SELECT 
                                i.ihris_pid, 
                                i.surname, 
                                i.firstname, 
                                i.kpi_group_id AS job_category_id,
                                i.facility_id,
                                i.job AS job_name,
                                kc.job AS job_category_name
                            FROM ihrisdata i
                            LEFT JOIN kpi_job_category kc ON i.kpi_group_id = kc.job_id
                            WHERE $staff_conditions
                            ORDER BY i.facility_id, i.surname, i.firstname";
            
            $staff_records = $this->db->query($staff_query)->result();
    
            // Group staff by facility
            $staff_map = [];
            $staff_ids = [];
            $staff_jobs = [];
            
            foreach ($staff_records as $s) {
                $staff_map[$s->facility_id][] = $s;
                $staff_ids[] = $s->ihris_pid;
                $staff_jobs[$s->ihris_pid] = $s->job_category_id;
            }
    
            // Attach staff to facilities
            foreach ($facilities as $f) {
                $f->staff = isset($staff_map[$f->facility_id]) ? $staff_map[$f->facility_id] : [];
            }
    
            // --- OPTIMIZED: Single query for reporting rates ---
            $reporting_rates = [];
            if (!empty($staff_ids)) {
                $staff_ids_escaped = array_map([$this->db, 'escape'], array_unique($staff_ids));
                $ids_in = implode(',', $staff_ids_escaped);
                
                $rates_data = $this->db->query("
                    SELECT 
                        nd.ihris_pid, 
                        nd.period, 
                        COUNT(DISTINCT nd.kpi_id) AS kpis_with_data
                    FROM new_data nd
                    INNER JOIN kpi k ON k.kpi_id = nd.kpi_id
                    WHERE nd.ihris_pid IN ($ids_in)
                      AND nd.financial_year = " . $this->db->escape($financial_year) . "
                      AND nd.draft_status = 1
                      AND (nd.numerator IS NOT NULL AND nd.numerator != '')
                    GROUP BY nd.ihris_pid, nd.period
                ")->result();
    
                foreach ($rates_data as $row) {
                    $reporting_rates[$row->ihris_pid][$row->period] = $row->kpis_with_data;
                }
            }
    
            // --- OPTIMIZED: Single query for job KPI totals ---
            $job_totals = [];
            if (!empty($staff_jobs)) {
                $unique_job_ids = array_unique(array_map('intval', $staff_jobs));
                if (!empty($unique_job_ids)) {
                    $job_ids_in = implode(',', $unique_job_ids);
                    
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
            }
    
            // Format data for JSON response (already has job names and categories from staff query)
            $formatted_facilities = [];
            foreach ($facilities as $facility) {
                $facility_data = [
                    'facility_id' => $facility->facility_id,
                    'facility' => $facility->facility,
                    'staff' => []
                ];
    
                foreach ($facility->staff as $staff) {
                    $staff_data = [
                        'ihris_pid' => $staff->ihris_pid,
                        'surname' => $staff->surname,
                        'firstname' => $staff->firstname,
                        'job_category_id' => $staff->job_category_id,
                        'job_category_name' => !empty($staff->job_category_name) ? $staff->job_category_name : '-',
                        'job_name' => !empty($staff->job_name) ? $staff->job_name : '-',
                        'reporting_rates' => [
                            'Q1' => isset($reporting_rates[$staff->ihris_pid]['Q1']) ? (int)$reporting_rates[$staff->ihris_pid]['Q1'] : 0,
                            'Q2' => isset($reporting_rates[$staff->ihris_pid]['Q2']) ? (int)$reporting_rates[$staff->ihris_pid]['Q2'] : 0,
                            'Q3' => isset($reporting_rates[$staff->ihris_pid]['Q3']) ? (int)$reporting_rates[$staff->ihris_pid]['Q3'] : 0,
                            'Q4' => isset($reporting_rates[$staff->ihris_pid]['Q4']) ? (int)$reporting_rates[$staff->ihris_pid]['Q4'] : 0,
                        ],
                        'total_kpis' => isset($job_totals[$staff->job_category_id]) ? (int)$job_totals[$staff->job_category_id] : 0
                    ];
                    $facility_data['staff'][] = $staff_data;
                }
    
                $formatted_facilities[] = $facility_data;
            }
    
            echo json_encode([
                'success' => true,
                'data' => $formatted_facilities,
                'pagination' => [
                    'total_count' => (int)$total_rows,
                    'current_page' => $page,
                    'per_page' => $per_page,
                    'total_pages' => (int)ceil($total_rows / $per_page)
                ]
            ]);
        } catch (Exception $e) {
            log_message('error', 'AJAX error in ajax_person_reporting_rates: ' . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Failed to load data']);
        }
    }

    /**
     * Export person reporting rates to Excel/CSV
     * Exports ALL facilities matching filters (not paginated)
     */
    public function export_person_reporting_rates()
    {
        $this->load->helper('download');
    
        // Filters
        $job_cat = $this->input->get('kpi_group', TRUE);
        $search = $this->input->get('search', TRUE);
        $selected_facility_id = $this->input->get('facility_id', TRUE);
        $user_facility_id = $this->session->userdata('facility_id');
        $financial_year = $this->input->get('financial_year') ?: $this->session->userdata('financial_year');
    
        // Final facility filter
        $final_facility_id = !empty($selected_facility_id) ? $selected_facility_id : $user_facility_id;
    
        try {
            $ihris_pid = $this->session->userdata('ihris_pid');
            $user_type = $this->session->userdata('user_type');
    
            // --- Build WHERE ---
            $where = [];
            $where_params = [];
            
            if (!empty($final_facility_id)) {
                $where[] = "new_data.facility = ?";
                $where_params[] = $final_facility_id;
            }
            if (!empty($search)) {
                $where[] = "ihrisdata.facility LIKE ?";
                $where_params[] = '%' . $search . '%';
            }
            $where_clause = (!empty($where)) ? 'WHERE ' . implode(' AND ', $where) : '';
    
            // --- Fetch ALL Facilities (no pagination) ---
            $facilities_query = "SELECT DISTINCT new_data.facility AS facility_id, ihrisdata.facility
                                FROM new_data
                                JOIN ihrisdata ON new_data.facility = ihrisdata.facility_id
                                $where_clause
                                ORDER BY ihrisdata.facility ASC";
            
            if (!empty($where_params)) {
                $facilities_result = $this->db->query($facilities_query, $where_params);
            } else {
                $facilities_result = $this->db->query($facilities_query);
            }
            
            $facilities = $facilities_result->result();
    
            // --- Batch Staff Loading for ALL facilities ---
            $facility_ids = array_column($facilities, 'facility_id');
            $staff_map = [];
            if (!empty($facility_ids)) {
                $facility_ids_in = implode(',', array_map('intval', $facility_ids));
                $staff_where = "AND facility_id IN ($facility_ids_in) AND kpi_group_id != '' AND is_active = 1";
    
                if (!empty($ihris_pid) && $user_type == 'staff') {
                    $staff_where .= " AND ihris_pid = " . $this->db->escape($ihris_pid);
                }
    
                if (!empty($job_cat)) {
                    $staff_where .= " AND kpi_group_id = " . $this->db->escape($job_cat);
                }
    
                $staff_records = $this->db->query("
                    SELECT ihris_pid, surname, firstname, kpi_group_id AS job_category_id, facility_id
                    FROM ihrisdata
                    WHERE 1=1 $staff_where
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
                $staff_ids_escaped = array_map([$this->db, 'escape'], $staff_ids);
                $ids_in = implode(',', $staff_ids_escaped);
                
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
    
            // Load job category names and job names
            $job_category_map = [];
            $job_name_map = [];
            
            if (!empty($staff_jobs)) {
                $job_ids_in = implode(',', array_unique(array_map('intval', $staff_jobs)));
                
                // Get job category names
                $job_categories = $this->db->query("
                    SELECT job_id, job 
                    FROM kpi_job_category
                    WHERE job_id IN ($job_ids_in)
                ")->result();
                
                foreach ($job_categories as $jc) {
                    $job_category_map[$jc->job_id] = $jc->job;
                }
                
                // Get job names from ihrisdata
                if (!empty($staff_ids)) {
                    $staff_ids_escaped = array_map([$this->db, 'escape'], $staff_ids);
                    $ids_in = implode(',', $staff_ids_escaped);
                    
                    $job_names = $this->db->query("
                        SELECT DISTINCT ihris_pid, job
                        FROM ihrisdata
                        WHERE ihris_pid IN ($ids_in)
                    ")->result();
                    
                    foreach ($job_names as $jn) {
                        $job_name_map[$jn->ihris_pid] = $jn->job;
                    }
                }
            }
    
            // Format data for export
            $export_data = [];
            $row_index = 0;
            
            foreach ($facilities as $facility) {
                foreach ($facility->staff as $staff) {
                    $total_kpis = isset($job_totals[$staff->job_category_id]) ? $job_totals[$staff->job_category_id] : 0;
                    $q1_rate = $total_kpis > 0 ? (isset($reporting_rates[$staff->ihris_pid]['Q1']) ? $reporting_rates[$staff->ihris_pid]['Q1'] : 0) / $total_kpis * 100 : null;
                    $q2_rate = $total_kpis > 0 ? (isset($reporting_rates[$staff->ihris_pid]['Q2']) ? $reporting_rates[$staff->ihris_pid]['Q2'] : 0) / $total_kpis * 100 : null;
                    $q3_rate = $total_kpis > 0 ? (isset($reporting_rates[$staff->ihris_pid]['Q3']) ? $reporting_rates[$staff->ihris_pid]['Q3'] : 0) / $total_kpis * 100 : null;
                    $q4_rate = $total_kpis > 0 ? (isset($reporting_rates[$staff->ihris_pid]['Q4']) ? $reporting_rates[$staff->ihris_pid]['Q4'] : 0) / $total_kpis * 100 : null;
                    
                    $export_data[] = [
                        'Facility' => $facility->facility,
                        'Employee Name' => $staff->surname . ' ' . $staff->firstname,
                        'KPI Group' => isset($job_category_map[$staff->job_category_id]) ? $job_category_map[$staff->job_category_id] : '-',
                        'Position' => isset($job_name_map[$staff->ihris_pid]) ? $job_name_map[$staff->ihris_pid] : '-',
                        'Financial Year' => $financial_year,
                        'Q1 Reporting Rate' => isset($reporting_rates[$staff->ihris_pid]['Q1']) ? $reporting_rates[$staff->ihris_pid]['Q1'] . '/' . $total_kpis . ' (' . round($q1_rate, 2) . '%)' : '0/' . $total_kpis,
                        'Q2 Reporting Rate' => isset($reporting_rates[$staff->ihris_pid]['Q2']) ? $reporting_rates[$staff->ihris_pid]['Q2'] . '/' . $total_kpis . ' (' . round($q2_rate, 2) . '%)' : '0/' . $total_kpis,
                        'Q3 Reporting Rate' => isset($reporting_rates[$staff->ihris_pid]['Q3']) ? $reporting_rates[$staff->ihris_pid]['Q3'] . '/' . $total_kpis . ' (' . round($q3_rate, 2) . '%)' : '0/' . $total_kpis,
                        'Q4 Reporting Rate' => isset($reporting_rates[$staff->ihris_pid]['Q4']) ? $reporting_rates[$staff->ihris_pid]['Q4'] . '/' . $total_kpis . ' (' . round($q4_rate, 2) . '%)' : '0/' . $total_kpis,
                        'Total KPIs' => $total_kpis
                    ];
                }
            }
    
            if (!empty($export_data)) {
                render_csv_data($export_data, 'reporting_rates_' . date('Y_m_d_His') . '.csv');
            } else {
                echo "No data found to export.";
            }
        } catch (Exception $e) {
            log_message('error', 'Export error in export_person_reporting_rates: ' . $e->getMessage());
            echo "Error exporting data. Please try again.";
        }
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
