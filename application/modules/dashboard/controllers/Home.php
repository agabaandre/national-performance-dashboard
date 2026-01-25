<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Home extends 	MX_Controller {
 	
 	public function __construct()
 	{
 		parent::__construct();
 		
 		$this->load->model('home_model'); 

	
 	}
 
     // dahsboard charts formerly index
	function dashboard_charts(){
	    $data['dashkpis']=$this->home_model->dashData();
		$data['module']      = "dashboard";
		$data['page']        = "home/index";
		$data['uptitle']        = "Main Dashboard";
		$data['title']        = "Dashboard";
		echo Modules::run('template/layout', $data); 
	}

	function index()
	{
	
		redirect('dashboard/slider/facility_reporting');
	}
	

	public function profile()
	{
		$data['title']  = "Profile";
		$data['module'] = "dashboard";  
		$data['page']   = "home/profile";  
		$id = $this->session->userdata('id');//
		$data['user']   = $this->home_model->profile($id);
		echo Modules::run('template/layout', $data);  
	}

	public function setting()
	{ 
		$data['title']    = "Profile Setting";
		$id = $this->session->userdata('id');
		/*-----------------------------------*/
		$this->form_validation->set_rules('firstname', 'First Name','required|max_length[50]');
		$this->form_validation->set_rules('lastname', 'Last Name','required|max_length[50]');
		#------------------------#
       	$this->form_validation->set_rules('email', 'Email Address', "required|valid_email|max_length[100]");
       	/*---#callback fn not supported#---*/ 
		#------------------------#
		$this->form_validation->set_rules('password', 'Password','max_length[200]');
		$this->form_validation->set_rules('about', 'About','max_length[1000]');
		/*-----------------------------------*/
        $config['upload_path']          = './assets/img/user/';
        $config['allowed_types']        = 'gif|jpg|png'; 

        $this->load->library('upload', $config);
 
        if ($this->upload->do_upload('image')) {  
            $data = $this->upload->data();  
            $image = $config['upload_path'].$data['file_name']; 

			$config['image_library']  = 'gd2';
			$config['source_image']   = $image;
			$config['create_thumb']   = false;
			$config['maintain_ratio'] = TRUE;
			$config['width']          = 115;
			$config['height']         = 90;
			$this->load->library('image_lib', $config);
			$this->image_lib->resize();
			$this->session->set_flashdata('message', "Image Upload Successfully!");
        }
		/*-----------------------------------*/
		$data['user'] = (object)$userData = array(
			'id' 		  => $this->input->post('id'),
			'firstname'   => $this->input->post('firstname'),
			'lastname' 	  => $this->input->post('lastname'),
			'email' 	  => $this->input->post('email'),
			'password' 	  => (!empty($this->input->post('password')) ? $this->argonhash->make($this->input->post('password')) : $this->input->post('oldpassword')),
			'about' 	  => $this->input->post('about',true),
			'image'   	  => (!empty($image)?$image:$this->input->post('old_image')) 
		);

		/*-----------------------------------*/
		if ($this->form_validation->run()) {

	        if (empty($userData['image'])) {
				$this->session->set_flashdata('exception', $this->upload->display_errors()); 
	        }

			if ($this->home_model->setting($userData)) {

				$this->session->set_userdata(array(
					'fullname'   => $this->input->post('firstname'). ' ' .$this->input->post('lastname'),
					'email' 	  => $this->input->post('email'),
					'image'   	  => (!empty($image)?$image:$this->input->post('old_image'))
				));


				$this->session->set_flashdata('message', display('update_successfully'));
			} else {
				$this->session->set_flashdata('exception',  display('please_try_again'));
			}
			redirect("dashboard/home/setting");

		} else {
			$data['module'] = "dashboard";  
			$data['page']   = "home/profile_setting"; 
			if(!empty($id))
			$data['user']   = $this->home_model->profile($id);
			echo Modules::run('template/layout', $data);
		}
	}
	///// Notice 
	 public function view_details(){
        $id = $this->uri->segment(4);
		$data['module'] = "dashboard";  
		$data['page']   = "home/notice_details";  
		$data['detls']   = $this->evencal->details($id);
       echo Modules::run('template/layout', $data); 

    }
	public function get_facilities($job_cat=FALSE)
	{
		$data = array();
		
		try {
			if(!empty($this->session->userdata('facility_id'))){
				$facility_id = $this->session->userdata('facility_id');
				$result = $this->db->query("SELECT DISTINCT new_data.facility as facility_id, ihrisdata.facility from new_data JOIN ihrisdata on new_data.facility=ihrisdata.facility_id WHERE facility_id=?", array($facility_id));
			}
			else{
				$result = $this->db->query("SELECT DISTINCT new_data.facility as facility_id, ihrisdata.facility from new_data JOIN ihrisdata on new_data.facility=ihrisdata.facility_id");
			}
			
			if ($result) {
				$data = $result->result();
			}
			
			foreach ($data as $facility) {
				$facility->staff = $this->get_staff($facility->facility_id, $job_cat);
			}
		} catch (Exception $e) {
			log_message('error', 'Database error in get_facilities: ' . $e->getMessage());
			$data = array();
		}

		//dd($data);
		return $data;
	}

	public function get_staff($facility_id, $job_c=FALSE)
	{
		if(!empty($job_c)){
			$job_cat = $job_c;
		}
		else{
			$job_cat = $this->input->get('kpi_group');
		}
	
		if(!empty($job_cat)){
        $job = "and kpi_group_id=$job_cat";
		}
		if(!empty($this->session->userdata('ihris_pid'))&& ($this->session->userdata('user_type') == 'staff')){
		$ihris_pid = $this->session->userdata('ihris_pid');
		
		return $this->db->query("SELECT DISTINCT ihris_pid, surname,firstname,kpi_group_id as job_category_id  from ihrisdata where facility_id='$facility_id' $job and ihris_pid='$ihris_pid' and kpi_group_id!='' and is_active=1")->result();
		}
		else{
			return $this->db->query("SELECT DISTINCT ihris_pid, surname,firstname,kpi_group_id as job_category_id from ihrisdata where facility_id='$facility_id' $job and kpi_group_id!='' and is_active=1")->result();

		}
	}
	

	public function staff_performance($ihris_id,$financial_year, $period,$kpi_id=FALSE)
	{
		if (!empty($kpi_id)){
			$kpi_id_condition = "and kpi_id=" . $this->db->escape($kpi_id); 
		}
		else{
			$kpi_id_condition = "";
		}

		// Use prepared statement to prevent SQL injection and connection issues
		try {
			$query = "SELECT * from performanace_data WHERE ihris_pid=? and financial_year=? and period=? " . $kpi_id_condition;
			$params = array($ihris_id, $financial_year, $period);
			
			if (!empty($kpi_id)) {
				$query = "SELECT * from performanace_data WHERE ihris_pid=? and financial_year=? and period=? and kpi_id=?";
				$params[] = $kpi_id;
			}
			
			$result = $this->db->query($query, $params);
			
			if ($result && $result->num_rows() > 0) {
				return $result->row();
			}
		} catch (Exception $e) {
			// Log the error but don't break the page
			log_message('error', 'Database error in staff_performance: ' . $e->getMessage());
		}
		
		// Return empty object with default structure if query fails or no results
		$empty_obj = new stdClass();
		$empty_obj->numerator = null;
		$empty_obj->denominator = null;
		$empty_obj->score = null;
		$empty_obj->data_target = null;
		$empty_obj->comment = null;
		return $empty_obj;
	}

	/**
	 * Batch fetch staff performance data from new_data table for facility reporting
	 * Uses ihris_pid for matching (more reliable than dimension2 which can be NULL)
	 */
	public function batch_staff_performance_from_new_data($staff_ids, $financial_year, $periods = ['Q1', 'Q2', 'Q3', 'Q4'], $kpi_id = FALSE)
	{
		if (empty($staff_ids) || !is_array($staff_ids)) {
			return [];
		}

		$results = [];
		// Initialize results structure with ihris_pid as key
		foreach ($staff_ids as $ihris_pid) {
			foreach ($periods as $period) {
				if (!isset($results[$ihris_pid])) {
					$results[$ihris_pid] = [];
				}
				$results[$ihris_pid][$period] = (object)[
					'numerator' => null,
					'denominator' => null,
					'score' => null,
					'data_target' => null,
					'comment' => null
				];
			}
		}

		try {
			$staff_ids_escaped = array_map([$this->db, 'escape'], $staff_ids);
			$ids_in = implode(',', $staff_ids_escaped);
			
			$periods_escaped = array_map([$this->db, 'escape'], $periods);
			$periods_in = implode(',', $periods_escaped);

			// Build query with proper filtering - handle draft_status that might be NULL
			$query = "SELECT ihris_pid, period, numerator, denominator, 
						CAST(data_target AS DECIMAL(10,2)) AS data_target,
						CASE 
							WHEN denominator > 0 AND denominator IS NOT NULL THEN (numerator / denominator) * 100 
							ELSE NULL 
						END AS score
					 FROM new_data 
					 WHERE ihris_pid IN ($ids_in) 
					 AND financial_year = " . $this->db->escape($financial_year) . "
					 AND period IN ($periods_in)";
			
			// Only add draft_status filter if column exists (check by trying to query it)
			// For now, comment out draft_status filter as it might not exist in all installations
			// $query .= " AND (draft_status = 1 OR draft_status IS NULL)";

			if (!empty($kpi_id)) {
				// Handle both string and integer kpi_id formats
				$kpi_id_escaped = $this->db->escape($kpi_id);
				$kpi_id_int = intval($kpi_id);
				$query .= " AND (kpi_id = $kpi_id_escaped OR kpi_id = " . $this->db->escape($kpi_id_int) . ")";
			}

			log_message('debug', 'batch_staff_performance_from_new_data - Query: ' . substr($query, 0, 500));
			log_message('debug', 'batch_staff_performance_from_new_data - Staff IDs count: ' . count($staff_ids) . ', Financial Year: ' . $financial_year . ', KPI ID: ' . ($kpi_id ?: 'all'));
			$result = $this->db->query($query);
			
			if (!$result) {
				$error = $this->db->error();
				log_message('error', 'batch_staff_performance_from_new_data - Query failed: ' . print_r($error, true));
			} else {
				log_message('debug', 'batch_staff_performance_from_new_data - Query returned ' . $result->num_rows() . ' rows');
			}

			if ($result && $result->num_rows() > 0) {
				$rows_processed = 0;
				foreach ($result->result() as $row) {
					$ihris_pid = isset($row->ihris_pid) ? trim($row->ihris_pid) : '';
					$period = isset($row->period) ? trim($row->period) : '';
					if (!empty($ihris_pid) && !empty($period)) {
						// Initialize if not exists
						if (!isset($results[$ihris_pid])) {
							$results[$ihris_pid] = [];
							// Initialize all periods for this employee
							foreach ($periods as $p) {
								$results[$ihris_pid][$p] = (object)[
									'numerator' => null,
									'denominator' => null,
									'score' => null,
									'data_target' => null,
									'comment' => null
								];
							}
						}
						
						$results[$ihris_pid][$period] = (object)[
							'numerator' => isset($row->numerator) ? $row->numerator : null,
							'denominator' => isset($row->denominator) ? $row->denominator : null,
							'score' => isset($row->score) ? $row->score : null,
							'data_target' => isset($row->data_target) ? $row->data_target : null,
							'comment' => null // new_data doesn't have comment field
						];
						$rows_processed++;
					}
				}
				log_message('debug', 'batch_staff_performance_from_new_data - Processed ' . $rows_processed . ' rows, Found ' . count($results) . ' unique employees with performance data');
			} else {
				log_message('debug', 'batch_staff_performance_from_new_data - No performance data found (Query returned ' . ($result ? $result->num_rows() : 0) . ' rows)');
			}
		} catch (Exception $e) {
			log_message('error', 'Database error in batch_staff_performance_from_new_data: ' . $e->getMessage());
		}

		return $results;
	}

	/**
	 * Batch fetch staff performance data for multiple staff members and quarters
	 * This eliminates N+1 query problem by fetching all data in one query
	 * 
	 * @param array $staff_ids Array of ihris_pid values
	 * @param string $financial_year Financial year
	 * @param array $periods Array of periods (e.g., ['Q1', 'Q2', 'Q3', 'Q4'])
	 * @param int|false $kpi_id Optional KPI ID filter
	 * @return array Multi-dimensional array: [ihris_pid][period] = performance object
	 */
	public function batch_staff_performance($staff_ids, $financial_year, $periods = ['Q1', 'Q2', 'Q3', 'Q4'], $kpi_id = FALSE)
	{
		if (empty($staff_ids) || !is_array($staff_ids)) {
			return [];
		}

		// Create empty result structure
		$results = [];
		foreach ($staff_ids as $staff_id) {
			foreach ($periods as $period) {
				$results[$staff_id][$period] = (object)[
					'numerator' => null,
					'denominator' => null,
					'score' => null,
					'data_target' => null,
					'comment' => null
				];
			}
		}

		try {
			// Build the query with IN clause for batch fetching
			if (empty($staff_ids)) {
				return $results;
			}

			// Escape staff IDs and periods for safe query building
			$staff_ids_escaped = array_map(function($id) {
				return $this->db->escape($id);
			}, $staff_ids);
			$staff_ids_in = implode(',', $staff_ids_escaped);
			
			$periods_escaped = array_map(function($period) {
				return $this->db->escape($period);
			}, $periods);
			$periods_in = implode(',', $periods_escaped);

			$query = "SELECT * FROM performanace_data 
					  WHERE ihris_pid IN ($staff_ids_in) 
					  AND financial_year = " . $this->db->escape($financial_year) . "
					  AND period IN ($periods_in)";

			if (!empty($kpi_id)) {
				$query .= " AND kpi_id = " . $this->db->escape($kpi_id);
			}

			$result = $this->db->query($query);

			if ($result && $result->num_rows() > 0) {
				foreach ($result->result() as $row) {
					$results[$row->ihris_pid][$row->period] = $row;
				}
			}
		} catch (Exception $e) {
			log_message('error', 'Database error in batch_staff_performance: ' . $e->getMessage());
		}

		return $results;
	}

	/**
	 * Optimized get_facilities with batch performance data loading, pagination and search
	 */
	public function get_facilities_optimized($job_cat = FALSE, $financial_year = NULL, $kpi_id = FALSE, $search = '', $page = 0, $per_page = 20)
	{
		$data = [];
		$total_count = 0;
		
		try {
			// Get financial year - use provided, session, or calculate previous year
			if (empty($financial_year)) {
				$financial_year = $this->session->userdata('financial_year');
				if (empty($financial_year)) {
					// Calculate previous financial year as default
					$current_date = date('Y-m-d');
					$current_year = date('Y', strtotime($current_date));
					if (date('m-d', strtotime($current_date)) < '07-01') {
						$financial_year = ($current_year - 2) . '-' . ($current_year - 1);
					} else {
						$financial_year = ($current_year - 1) . '-' . $current_year;
					}
				}
			}

			// Build WHERE clause for facilities query
			$where_conditions = [];
			$where_params = [];

			// Add financial year filter to facility query
			$where_conditions[] = "new_data.financial_year = ?";
			$where_params[] = $financial_year;

			// Note: draft_status filter removed - column may not exist in all installations
			// If you need to filter by draft_status, uncomment below and ensure column exists
			// $where_conditions[] = "(new_data.draft_status = 1 OR new_data.draft_status IS NULL)";

			// Add KPI filter if provided - handle both string and integer formats
			if (!empty($kpi_id)) {
				// Try both string and integer comparison
				$where_conditions[] = "(new_data.kpi_id = ? OR new_data.kpi_id = ?)";
				$where_params[] = $kpi_id;
				$where_params[] = (string)intval($kpi_id); // Also try as integer string
			}

			if (!empty($this->session->userdata('facility_id'))) {
				$facility_id = $this->session->userdata('facility_id');
				$where_conditions[] = "new_data.facility = ?";
				$where_params[] = $facility_id;
			}

			// Add search condition
			if (!empty($search)) {
				$where_conditions[] = "ihrisdata.facility LIKE ?";
				$where_params[] = '%' . $search . '%';
			}

			$where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

			// Get total count for pagination
			$count_query = "SELECT COUNT(DISTINCT new_data.facility) as total 
							FROM new_data 
							JOIN ihrisdata ON new_data.facility=ihrisdata.facility_id 
							$where_clause";
			
			$count_result = $this->db->query($count_query, $where_params);
			
			if ($count_result && $count_result->num_rows() > 0) {
				$row = $count_result->row();
				$total_count = isset($row->total) ? (int)$row->total : 0;
			}

			// Fetch facilities with pagination
			$offset = (int)($page * $per_page);
			$per_page_int = (int)$per_page;
			
			$facilities_query = "SELECT DISTINCT new_data.facility as facility_id, ihrisdata.facility 
								 FROM new_data 
								 JOIN ihrisdata ON new_data.facility=ihrisdata.facility_id 
								 $where_clause
								 ORDER BY ihrisdata.facility ASC
								 LIMIT $per_page_int OFFSET $offset";
			
			$result = $this->db->query($facilities_query, $where_params);
			
			log_message('debug', 'get_facilities_optimized - Found ' . ($result ? $result->num_rows() : 0) . ' facilities for FY: ' . $financial_year . ', KPI Group: ' . ($job_cat ?: 'all') . ', KPI ID: ' . ($kpi_id ?: 'all'));
			
			if ($result) {
				$data = $result->result();
			}

			// Batch load all staff for all facilities
			$facility_ids = array_column($data, 'facility_id');
			$all_staff = [];
			$staff_ids = [];

			if (!empty($facility_ids)) {
				// Facility IDs are strings like "facility|773", not integers - need to escape properly
				$facility_ids_escaped = array_map([$this->db, 'escape'], $facility_ids);
				$facility_ids_in = implode(',', $facility_ids_escaped);
				$job_condition = !empty($job_cat) ? "AND kpi_group_id = " . $this->db->escape($job_cat) : "";
				
				$staff_query = "SELECT DISTINCT ihris_pid, surname, firstname, kpi_group_id as job_category_id, facility_id 
								FROM ihrisdata 
								WHERE facility_id IN ($facility_ids_in) 
								AND kpi_group_id != '' 
								AND is_active = 1 
								$job_condition";

				if (!empty($this->session->userdata('ihris_pid')) && ($this->session->userdata('user_type') == 'staff')) {
					$ihris_pid = $this->session->userdata('ihris_pid');
					$staff_query .= " AND ihris_pid = " . $this->db->escape($ihris_pid);
				}

				$staff_result = $this->db->query($staff_query);
				if ($staff_result) {
					foreach ($staff_result->result() as $staff) {
						$all_staff[$staff->facility_id][] = $staff;
						$staff_ids[] = $staff->ihris_pid;
					}
				}
			}

			// Batch load all performance data from new_data table using ihris_pid
			$performance_data = [];
			if (!empty($staff_ids)) {
				// Use ihris_pid directly instead of names - much more reliable!
				$performance_data = $this->batch_staff_performance_from_new_data($staff_ids, $financial_year, ['Q1', 'Q2', 'Q3', 'Q4'], $kpi_id);
				log_message('debug', 'get_facilities_optimized - Staff IDs count: ' . count($staff_ids) . ', Performance data entries: ' . count($performance_data));
			}

			// Attach staff and performance data to facilities
			foreach ($data as $facility) {
				$facility->staff = isset($all_staff[$facility->facility_id]) ? $all_staff[$facility->facility_id] : [];
				
				// Attach performance data to each staff member using ihris_pid
				foreach ($facility->staff as $staff) {
					$ihris_pid = isset($staff->ihris_pid) ? trim($staff->ihris_pid) : '';
					
					// Match by ihris_pid (much more reliable than name matching)
					if (!empty($ihris_pid) && isset($performance_data[$ihris_pid])) {
						$staff->performance = $performance_data[$ihris_pid];
					} else {
						$staff->performance = [];
					}
					
					// Ensure performance structure exists even if empty
					if (!isset($staff->performance) || !is_array($staff->performance)) {
						$staff->performance = [];
					}
					
					// Log for debugging
					if (empty($staff->performance)) {
						log_message('debug', 'No performance data found for ihris_pid: ' . $ihris_pid . ' (searched in ' . count($performance_data) . ' entries)');
					}
				}
			}
		} catch (Exception $e) {
			log_message('error', 'Database error in get_facilities_optimized: ' . $e->getMessage());
			$data = [];
		}

		return [
			'facilities' => $data,
			'total_count' => $total_count,
			'current_page' => $page,
			'per_page' => $per_page,
			'total_pages' => ceil($total_count / $per_page)
		];
	}

	/**
	 * AJAX endpoint to load facility data with pagination and search
	 */
	public function ajax_facility_data()
	{
		header('Content-Type: application/json');
		
		$kpi_group = $this->input->get('kpi_group');
		$kpi_id = $this->input->get('kpi_id');
		// Convert empty string to FALSE for proper handling
		if (empty($kpi_id)) {
			$kpi_id = FALSE;
		}
		
		// Get financial year - use provided, session, or calculate previous year
		$financial_year = $this->input->get('financial_year');
		if (empty($financial_year)) {
			$financial_year = $this->session->userdata('financial_year');
			if (empty($financial_year)) {
				// Calculate previous financial year as default
				$current_date = date('Y-m-d');
				$current_year = date('Y', strtotime($current_date));
				if (date('m-d', strtotime($current_date)) < '07-01') {
					$financial_year = ($current_year - 2) . '-' . ($current_year - 1);
				} else {
					$financial_year = ($current_year - 1) . '-' . $current_year;
				}
			}
		}
		
		$search = $this->input->get('search') ?: '';
		$page = (int)$this->input->get('page') ?: 0;
		$per_page = (int)$this->input->get('per_page') ?: 20;

		try {
			$result = $this->get_facilities_optimized($kpi_group, $financial_year, $kpi_id, $search, $page, $per_page);
			$facilities = isset($result['facilities']) ? $result['facilities'] : [];
			
			// Log for debugging
			log_message('debug', 'ajax_facility_data - Facilities count: ' . count($facilities));
			log_message('debug', 'ajax_facility_data - KPI Group: ' . ($kpi_group ?: 'empty') . ', KPI ID: ' . ($kpi_id ?: 'empty') . ', Financial Year: ' . ($financial_year ?: 'empty'));
			
			// Debug: Log first facility structure if available
			if (!empty($facilities) && isset($facilities[0])) {
				$first_facility = $facilities[0];
				log_message('debug', 'ajax_facility_data - First facility: ' . json_encode([
					'facility_id' => isset($first_facility->facility_id) ? $first_facility->facility_id : 'N/A',
					'facility' => isset($first_facility->facility) ? $first_facility->facility : 'N/A',
					'staff_count' => isset($first_facility->staff) ? count($first_facility->staff) : 0
				]));
			}
			
			// Convert to array for JSON encoding
			$response = [];
			foreach ($facilities as $facility) {
				if (!is_object($facility)) {
					continue; // Skip invalid entries
				}
				$facility_data = [
					'facility_id' => isset($facility->facility_id) ? $facility->facility_id : '',
					'facility' => isset($facility->facility) ? $facility->facility : '',
					'staff' => []
				];

				foreach ($facility->staff as $staff) {
					$staff_data = [
						'ihris_pid' => isset($staff->ihris_pid) ? $staff->ihris_pid : '',
						'surname' => isset($staff->surname) ? $staff->surname : '',
						'firstname' => isset($staff->firstname) ? $staff->firstname : '',
						'job_category_id' => isset($staff->job_category_id) ? $staff->job_category_id : '',
						'performance' => []
					];

					foreach (['Q1', 'Q2', 'Q3', 'Q4'] as $period) {
						$perf = (isset($staff->performance[$period]) && is_object($staff->performance[$period])) ? $staff->performance[$period] : null;
						$staff_data['performance'][$period] = [
							'numerator' => ($perf && isset($perf->numerator)) ? $perf->numerator : null,
							'denominator' => ($perf && isset($perf->denominator)) ? $perf->denominator : null,
							'score' => ($perf && isset($perf->score)) ? $perf->score : null,
							'data_target' => ($perf && isset($perf->data_target)) ? $perf->data_target : null,
							'comment' => ($perf && isset($perf->comment)) ? $perf->comment : null
						];
					}

					$facility_data['staff'][] = $staff_data;
				}

				$response[] = $facility_data;
			}

			$response_data = [
				'success' => true, 
				'data' => $response,
				'pagination' => [
					'total_count' => isset($result['total_count']) ? (int)$result['total_count'] : 0,
					'current_page' => isset($result['current_page']) ? (int)$result['current_page'] : $page,
					'per_page' => isset($result['per_page']) ? (int)$result['per_page'] : $per_page,
					'total_pages' => isset($result['total_pages']) ? (int)$result['total_pages'] : 0
				],
				'debug' => [
					'filters' => [
						'kpi_group' => $kpi_group ?: 'empty',
						'kpi_id' => $kpi_id ?: 'empty',
						'financial_year' => $financial_year ?: 'empty',
						'search' => $search ?: 'empty'
					],
					'facilities_count' => count($facilities),
					'response_count' => count($response)
				]
			];
			
			log_message('debug', 'ajax_facility_data - Response: ' . json_encode($response_data['debug']));
			
			echo json_encode($response_data);
		} catch (Exception $e) {
			log_message('error', 'AJAX error in ajax_facility_data: ' . $e->getMessage());
			log_message('error', 'AJAX error trace: ' . $e->getTraceAsString());
			echo json_encode([
				'success' => false, 
				'error' => 'Failed to load data: ' . $e->getMessage(),
				'debug' => [
					'filters' => [
						'kpi_group' => $kpi_group ?: 'empty',
						'kpi_id' => $kpi_id ?: 'empty',
						'financial_year' => $financial_year ?: 'empty'
					]
				]
			]);
		}
	}
	
	/**
	 * AJAX endpoint to get KPI information by ID
	 */
	public function get_kpi_info()
	{
		header('Content-Type: application/json');
		
		$kpi_id = $this->input->get('kpi_id', TRUE);
		
		if (empty($kpi_id)) {
			echo json_encode(['success' => false, 'error' => 'KPI ID is required']);
			return;
		}
		
		$numerator_description = '';
		$denominator_description = '';
		
		// First, try to get numerator_description and denominator_description from performanace_data
		// This view/table has the descriptions per KPI
		$perf_query = $this->db->query("SELECT DISTINCT numerator_description, denominator_description 
										FROM performanace_data 
										WHERE kpi_id = " . $this->db->escape($kpi_id) . " 
										AND numerator_description IS NOT NULL 
										AND numerator_description != '' 
										LIMIT 1");
		
		if ($perf_query && $perf_query->num_rows() > 0) {
			$perf = $perf_query->row();
			$numerator_description = isset($perf->numerator_description) ? $perf->numerator_description : '';
			$denominator_description = isset($perf->denominator_description) ? $perf->denominator_description : '';
		}
		
		// Get KPI basic info from kpi table
		$query = $this->db->query("SELECT kpi_id, short_name, full_name, numerator, denominator FROM kpi WHERE kpi_id = " . $this->db->escape($kpi_id));
		
		if ($query && $query->num_rows() > 0) {
			$kpi = $query->row();
			
			// Use numerator_description from performanace_data if available, otherwise use from kpi table
			if (empty($numerator_description) && isset($kpi->numerator)) {
				$numerator_description = $kpi->numerator;
			}
			
			// Use denominator_description from performanace_data if available, otherwise use from kpi table
			if (empty($denominator_description) && isset($kpi->denominator)) {
				$denominator_description = $kpi->denominator;
			}
			
			echo json_encode([
				'success' => true,
				'kpi' => [
					'kpi_id' => $kpi->kpi_id,
					'short_name' => isset($kpi->short_name) ? $kpi->short_name : '',
					'full_name' => isset($kpi->full_name) ? $kpi->full_name : '',
					'numerator_description' => $numerator_description,
					'denominator_description' => $denominator_description
				]
			]);
		} else {
			echo json_encode(['success' => false, 'error' => 'KPI not found']);
		}
	}

}
