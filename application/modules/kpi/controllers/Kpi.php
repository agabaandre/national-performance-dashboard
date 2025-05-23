<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kpi extends MX_Controller
{


	public function __Construct()
	{

		parent::__Construct();
		$this->db->query('SET SESSION sql_mode = ""');

		$this->load->model('kpi_mdl');
		$this->load->model('graph_mdl');
		$this->module = "kpi";
		$this->load->library('form_validation');
	
		
	}

	public function Kpis()
	{

		$data['title'] = 'Key Performance Indicators';
		$data['page'] = 'kpi';
		$data['module'] = $this->module;
		$data['category_twos'] = $this->kpi_mdl->getCategoryTwo();

		echo Modules::run('template/layout', $data);
	}
	public function delete($id)
	{
		
	
		// Attempt to delete the KPI record
		$session=$this->session->userdata();
		//dd($session);

		if($session['user_type']=='admin'){
			


		$deleted = $this->db->query("DELETE from kpi where kpi_id='$id'");
	
		if ($deleted) {

			$this->db->query("DELETE from  new_data where kpi_id='$id'");
			// Set a success message
			$this->session->set_flashdata('success', 'KPI deleted successfully.');
		} else {
			// Set an error message
			$this->session->set_flashdata('error', 'Failed to delete KPI. Please try again.');
		}


	   }
	    $this->session->set_flashdata('error', 'Action not Permitted');

		
	
		// Redirect to the KPIs list page
		redirect('kpi/kpis');
	}
	

	public function kpiData()
	{

		return $this->kpi_mdl->kpiData();
	}

	public function dashKpi($id = FALSE)
	{

		$kpis = $this->kpi_mdl->navkpi($id);
		return $kpis;
	}
	
	public function nav_generalKpi($id)
	{

		$kpis = $this->kpi_mdl->general_menukpi($id);
		return $kpis;
	}

	public function categoryKpi($id, $kpiType)
	{

		$kpis = $this->kpi_mdl->categoryKpi($id, $kpiType);
		return $kpis;
	}

	public function subject()
	{

		$data['title'] = 'Subject Areas';
		$data['page'] = 'subject';
		$data['module'] = $this->module;

		echo Modules::run('template/layout', $data);
	}
	public function info_category()
	{

		$data['title'] = 'Institution Category';
		$data['page'] = 'info_category';
		$data['module'] = $this->module;

		echo Modules::run('template/layout', $data);
	}

	public function subjectData()
	{

		$menu = $this->kpi_mdl->subjectData();
		//dd($menu);
		return $menu;
	}

	public function info_category_Data()
	{

		$menu = $this->kpi_mdl->info_category_Data();
		return $menu;
	}
	

	public function getCategoryTwos()
	{

		$rows = $this->kpi_mdl->getCategoryTwo();
		return $rows;
	}
	public function getCategoryMenu($subject)
	{

		$rows = $this->kpi_mdl->catgoryTwoMenu($subject);
		return $rows;
	}

	//objectives for cphl
	public function categoryTwo()
	{

		if ($this->input->post()):

			$insert_data = $this->input->post();
			if ($this->form_validation->run() == FALSE) {

				$this->form_validation->set_rules('name', 'Objective', 'required');
				$this->form_validation->set_rules('subject_area', 'Subject area', 'required');

				$data['message'] = $this->kpi_mdl->saveCategoryTwo($insert_data);
				$this->session->set_flashdata('message', $data['message']);
			}

		endif;

		$data['title'] = 'Objectives';
		$data['page'] = 'category_two';
		$data['subjects'] = $this->kpi_mdl->subjectData();
		$data['module'] = $this->module;
		$data['data'] = $this->getCategoryTwos();

		echo Modules::run('template/layout', $data);
	}
	public function view_kpi_data(){
		

		//$this->session->set_flashdata('message', 'Added');
		$data['data'] = $this->kpi_mdl->get_kpi_data();
		$data['title'] = 'Indicator Data';
		$data['page'] = 'view_kpi_data';
		$data['module'] = $this->module;

		echo Modules::run('template/layout', $data);
	}

	public function addKpi()
	{

		$insert = $this->input->post();
		$data['message'] = $this->kpi_mdl->addKpi($insert);

		$this->session->set_flashdata('message', 'Added');
		$data['title'] = 'Key Performance Indicators';
		$data['page'] = 'kpi';
		$data['module'] = $this->module;

		echo Modules::run('template/layout', $data);
	}
	public function addinstitution()
	{

		$insert = $this->input->post();
		$data['message'] = $this->kpi_mdl->addinstitution($insert);

		$this->session->set_flashdata('message', 'Added');
		redirect('kpi/info_category');
	}
	public function addKpiData()
	{

		$insert = $this->input->post();
		$data['message'] = $this->kpi_mdl->addKpi($insert);

		$this->session->set_flashdata('message', 'Saved');
		$data['title'] = 'Key Performance Indicator Data';
		$data['page'] = 'add_data';
		$data['module'] = $this->module;

		echo Modules::run('template/layout', $data);
	}

	public function updateKpi()
	{

		$kpi = $this->input->post('kpi_id');
		$is = $this->input->post('indicator_statement');
		$sn = $this->input->post('short_name');
		$des = $this->input->post('description');
		$ds = $this->input->post('data_sources');
		$freq = $this->input->post('frequency');
		$target = $this->input->post('current_target');
		$num = $this->input->post('numerator');
		$den = $this->input->post('denominator');
		$sa = $this->input->post('subject_area');
		$jb = $this->input->post('job_id');
		$cp = $this->input->post('computation_category');

		$count = count($kpi);
		//print_r($count);

		for ($i = 0; $i < $count; $i++) {
			//build and insert array
			$insert = array(
				'kpi_id' => $kpi[$i],
				'indicator_statement' => $is[$i],
				'description' => $des[$i],
				'frequency' => $freq[$i],
				'data_sources' => $ds[$i],
				'current_target' => $target[$i],
				'numerator' => $num[$i],
				'denominator' => $den[$i],
				'subject_area' => $sa[$i],
				'job_id' => $jb[$i],
				'computation_category' => $cp[$i],
				'short_name' => $sn[$i]
			);

			$data['message'] = $this->kpi_mdl->updatekpiData($insert);
			// print_r($insert);

		}

		$this->session->set_flashdata('message', 'Saved');

		$data['title'] = 'Key Performance Indicators';
		$data['page'] = 'kpi';
		$data['module'] = $this->module;

		echo Modules::run('template/layout', $data);
	}


	public function deletekpi()
	{
		$id = $this->input->post('kpi_id');
		if ($this->input->post('deletekpi') == 1) {
			$del = $this->db->query("DELETE from kpi where kpi_id='$id'");
			$this->db->query("DELETE from new_data where kpi_id='$id'");
		}
		else if ($this->input->post('deletekpi') == 2) {
			$del = $this->db->query("DELETE from new_data where kpi_id='$id'");
		}
		else if ($this->input->post('deletekpi') == 3){
			$del = $this->db->query("DELETE from kpi where kpi_id='$id'");
		}
		if ($del) {
			$this->session->set_flashdata('message', 'Deleted');
		}
		else{
			$this->session->set_flashdata('message', 'Delete Failed');
		}
		$data['title'] = 'Key Performance Indicators';
		$data['page'] = 'kpi_display';
		$data['module'] = $this->module;

		echo Modules::run('template/layout', $data);
	}

	public function addSubject()
	{

		$insert = $this->input->post();
		//print_r($insert);
		$data['message'] = $this->kpi_mdl->addsubject($insert);

		$this->session->set_flashdata('message', $data['message']);

		$data['title'] = 'Subject Areas';
		$data['page'] = 'subject';
		$data['module'] = $this->module;
		echo Modules::run('template/layout', $data);

	}
	public function info_category_name($id){
		return $this->db->query("SELECT name from info_category WHERE id = '$id'")->row()->name;
	}

	public function kpiDisplayData()
	{
		return $this->kpi_mdl->kpiDisplayData();
	}

	public function deletedata($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->delete('kpi');
		if ($query) {

		}
	}

	public function insertDisplayData()
	{

		$kpi = $this->input->post('kpi_id');
		$dash = $this->input->post('dashboard_index');
		$sub = $this->input->post('subject_index');
		$count = count($kpi);

		for ($i = 0; $i < $count; $i++) {
			//build and insert array
			$insert = array('kpi_id' => $kpi[$i], 'dashboard_index' => $dash[$i], 'subject_index' => $sub[$i]);
			$data['message'] = $this->kpi_mdl->insertDisplayData($insert);

		}

		$this->session->set_flashdata('message', 'Saved');

		$data['title'] = 'KPI Display ';
		$data['page'] = 'kpi_display';
		$data['module'] = $this->module;

		echo Modules::run('template/layout', $data);
	}

	public function kpiDisplay()
	{

		$data['title'] = 'KPI Display ';
		$data['page'] = 'kpi_display';
		$data['module'] = $this->module;

		echo Modules::run('template/layout', $data);
	}

	public function summary()
	{

		$data['title'] = 'KPI Summary ';
		$data['page'] = 'kpi_summary';
		$data['module'] = $this->module;

		echo Modules::run('template/layout', $data);
	}
	public function summaryData($ffilter = FALSE)
	{


		return $this->kpi_mdl->kpiSummaryData($ffilter);
	}

	public function kpiTrendcolors(
		$current_target,
		$gauge_value
		,
		$previousgauge_value
		,
		$current_period = FALSE
		,
		$previous_period = FALSE
	)
	{
		if ($gauge_value) {

			if ($previous_period != 0) {
				$previous_period = 'for ' . $previous_period;
			} else {
				$previous_period = '';
			}

			if (($current_target) >= 40) {
				if ($gauge_value >= $current_target) {
					return 'style="background-color:green; color:white;"';
				} elseif (($gauge_value < $current_target) && ($gauge_value >= 50)) {
					return 'style="background-color:orange; color:white;"';
				} else {
					return 'style="background-color:#de1a1a; color:white;"';
				}
			}

			//reducing
			if (($current_target) <= 40) {

				if ($gauge_value <= $current_target) {
					return 'style="background-color:green; color:white;"';
				} elseif (($gauge_value < $current_target) && ($gauge_value >= 50)) {
					return 'style="background-color:orange; color:white;"';
				} else {
					return 'style="background-color:#de1a1a; color:white;"';
				}
			}
		}
		else{
			return 'style="background-color:#de1a1a; color:white;"';

		}

	}

	public function gaugeData($kpi)
	{

		$data['chartkpi'] = $kpi;
		//gauge data
		$data['gauge'] = $this->graph_mdl->gaugeData(str_replace(" ", '', $kpi));
		$data['financial_year'] = $_SESSION['financial_year'];
		$data['module'] = "data";

		return $data;
	}
     function get_cat_subjects($id){

       $rows = $this->db->query("SELECT * from subject_areas where info_category='$id'")->result();

		if (!empty($rows)) {

			foreach ($rows as $row) {
			$opt .= "<option value='" . $row->id . "'>" . ucwords($row->name) . "</option>";
			}
		}

      echo $opt;
    }

	public function get_indicators()
	{


		if (!empty($_GET['cat_data'])) {

			$id = $_GET["cat_data"];
			$rows = $this->db->query("SELECT * from kpi where category_two_id='$id'")->result();

			if (!empty($rows)) {

				foreach ($rows as $row) {
					$opt .= "<option value='" . $row->kpi_id . "'>" . ucwords($row->short_name) . "</option>";
				}
			}

			echo $opt;
		}
	}

	public function get_subcatgories()
	{
		
                  
		if (!empty($_GET['sub_data'])) {

			$id = $_GET["sub_data"];
			  $rows = $this->db->query("SELECT * from category_two where subject_area_id='$id'")->result();

		if (!empty($rows)) {

			foreach ($rows as $row) {
			$opt .= "<option value='" . $row->id . "'>" . ucwords($row->cat_name) . "</option>";
			}
		}

      echo $opt;
	 }
	}

	

	 

	public function printsummary($view, $json)
	{
		// $data['json'] = $json;
		// $html = $this->load->view($view, $data, true);
		// $PDFContent = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
		// $this->m_pdf->pdf->SetWatermarkImage($this->watermark);
		// $this->m_pdf->pdf->showWatermarkImage = true;
		// $filename = "KPI_Summary Data" . date('Y-m-d');
		// date_default_timezone_set("Africa/Kampala");
		// $this->m_pdf->pdf->SetHTMLFooter("Printed/ Accessed on: <b>" . date('d F,Y h:i A') . "</b><br style='font-size: 9px !imporntant;'>" . " Source: MoH PM Dashboard " . base_url());
		// $this->m_pdf->pdf->SetWatermarkImage($this->watermark);
		// $this->m_pdf->showWatermarkImage = true;
		// ini_set('max_execution_time', 0);
		// $this->m_pdf->pdf->WriteHTML($PDFContent); //ml_pdf because we loaded the library ml_pdf for landscape format not m_pdf
		// //download it D save F.
		// $this->m_pdf->pdf->Output($filename, 'I');
	}
	public function test(){
		print_r($this->session->userdata());
	}


	public function kpi_session()
	{
		if ($_SESSION['subject_area'] != "") {
			@$id = implode(",", json_decode($_SESSION['subject_area']));
		}


		print_r("where subject_areas.id in ('$id')");
	}






}