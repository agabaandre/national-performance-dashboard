<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use utils\HttpUtils;

class Data extends MX_Controller {

	
	public function __Construct(){

		parent::__Construct();
		$this->db->query('SET SESSION sql_mode = ""');

		$this->load->model('data_mdl');
		$this->load->model('graph_mdl');
		$this->load->model('kpi/kpi_mdl','kpi_mdl');

		$this->kpi=$this->uri->segment(3);
		$this->financial_year = $this->session->userdata('financial_year');


	}

	


	//GAUGE
	public function kpi($dashkpi=FALSE,$dashdis=FALSE){

		if($dashdis == 'on'){
		    $kpi = $dashkpi;
		}

		else {	
	        
		     $kpi = $this->kpi;
		}

		$data['chartkpi'] = $kpi;

		//gauge data
	    $data['gauge']    = $this->graph_mdl->gaugeData(str_replace(" ",'',$kpi));
		$data['financial_year'] = $_SESSION['financial_year'];
		$data['title']    = @str_replace("_"," ",urldecode($this->uri->segment(4)));
		$data['page']     = 'dash_chart';
		$data['module']   = "data";

		

		if(!empty($dashdis === 'on')){
		 	$this->load->view('dash_chart',$data);
		}
		else{
			echo Modules::run('template/layout', $data); 
		}
	
	}
	

	public function dim1display($kpi){
		$data = $this->data_mdl->dim1display($kpi);
	  //print_r($data);
	  return $data;
	}

	public function dimalldisplay($kpi){
		$data = $this->data_mdl->dimalldisplay($kpi);
	  //print_r($data);
	  return $data;
	}

	public function dim2display($kpi){
		$data = $this->data_mdl->dim2display($kpi);
	  // print_r($data);
		return $data;
	}

	public function dim3display($kpi){
		$data = $this->data_mdl->dim3display($kpi);
	  // print_r($data);
	  return $data;	
	}


  // Dimension 0, KPI DRILL DOWMN
  public function kpiData($kpi){

		$data['module']  = "data";
	 	$data['page']    = 'trend';
		$data['chartkpi']=$kpi;
		$data['uptitle'] = $this->data_mdl->subject_name($kpi);
		$data['title']   = $this->data_mdl->kpi_name($kpi);
	
     echo Modules::run('template/layout', $data); 

	}


	public function kpiDetails($kpi){

		$data['module']    = "data";
	 	$data['page']      = 'kpi_details';
		$data['uptitle']   = ucwords($kpi).' Details';
		$data['kpi_table'] = $this->data_mdl->gaugeDetails($kpi);
	
    echo Modules::run('template/layout', $data); 

	}


	public function getdimSubject($kpi){

	   return $this->data_mdl->subject_name($kpi);
	}


	//BAR GRAPH
	public function dimension0($kpi){
		$data['chartkpi'] = $kpi;
		$data['quaters'] = $this->graph_mdl->dim0quaters($kpi);
		$data['data']    = $this->graph_mdl->dim0data($kpi);
		$data['target']  = $this->graph_mdl->dim0targets($kpi);

    return $data;
  }
	

  public function dimension1($kpi){
		$data['chartkpi'] = $kpi;
		$data['module']  = "data";
	 	$data['page']    = 'trend1';
		$data['uptitle'] = $this->data_mdl->subject_name($kpi);
		$data['title']   = $this->data_mdl->kpi_name($kpi);

    echo Modules::run('template/layout', $data);
		
	}


	public function dimension2($kpi){
		$data['chartkpi'] = $kpi;
		
		$data['module']  = "data";
	 	$data['page']    = 'trend2';
		$data['uptitle'] = $this->data_mdl->subject_name($kpi);
		$data['title']   = $this->data_mdl->kpi_name($kpi);

    echo Modules::run('template/layout', $data);
	
	}


	public function dimension3($kpi){

		$data['chartkpi'] = $kpi;
		$data['module']  = "data";
	 	$data['page']    = 'trend3';
		$data['uptitle'] = $this->data_mdl->subject_name($kpi);
		$data['title']   = $this->data_mdl->kpi_name($kpi);
		//$this->change_chart($this->input->post('dimension_chart'));
		
    echo Modules::run('template/layout', $data); 
	}


	public function trend($kpi){
		
		$data = $this->graph_mdl->dim0Graph($kpi);
    echo json_encode($data,JSON_NUMERIC_CHECK);

  }


  //Dimensions Graphs
	//Gauge 1 Data
	public function dim1data($kpi){
		$dimension1 = $this->input->post('dimension1');
        $dim1=str_replace('""','"',str_replace("_"," ",str_replace("]","",str_replace("[","",json_encode($dimension1)))));
		$data = $this->graph_mdl->dim1Graph($kpi,$dim1);
	  //print_r($data);
	
	  echo json_encode($data,JSON_NUMERIC_CHECK);
	}

	public function dim2data($kpi){
		$dimension1 = $this->input->post('dimension1');
       // $dim2=str_replace('""','"',str_replace("_"," ",str_replace("]","",str_replace("[","",json_encode($dimension2)))));
	   //	echo json_encode($dimension1);
		$data = $this->graph_mdl->dim2Graph($kpi,$dimension1);
	
	 echo json_encode($data,JSON_NUMERIC_CHECK);
	
	}


	public function dim3data($kpi){
	    $dimension2 = $this->input->post('dimension2');
		$dimension_chart = $this->input->post('dimension_chart');
        $dim2=str_replace('""','"',str_replace("_"," ",str_replace("]","",str_replace("[","",json_encode($dimension2)))));

		$data = $this->graph_mdl->dim3Graph($kpi,$dim2);

		// print_r($data);
		echo  json_encode($data,JSON_NUMERIC_CHECK);
	}

  //subject dashboard
	public function subject($subject,$SubjectName=""){

   // dd($subject);
	 $data['module']  = "data";
	 $data['page']    = 'subject_charts';
	 $filters = $this->input->get();
	 
	 $data['subdash'] = $this->data_mdl->subjectDash($filters,$subject);
	 $data['uptitle'] = str_replace("_"," ",$this->uri->segment(4));
    
     echo Modules::run('template/layout', $data); 
	}


	public function kpiTrend($current_target,$gauge_value,$previousgauge_value,$current_period=FALSE,$previous_period=FALSE){

		$params = array(
			"current_target" => $current_target,
			"current_value"  => $gauge_value,
			"prev_value"     => $previousgauge_value,
			"current_period" => $current_period, 
			"prev_period"    => $previous_period
		);

		//kpiTrend is in a kpiTrend helper
		return kpiTrend($params);
	}


	public function dim1s($kpi_id){

		$query = $this->db->query("SELECT distinct dimension1 from report_trend_dimension1 where kpi_id='$kpi_id'");
		return $query->result();

	}

	public function dim2s($kpi_id){
		
		$query = $this->db->query("SELECT distinct dimension2 from report_trend_dimension2 where kpi_id='$kpi_id'");
		return $query->result();

	}

	public function get_period($kpi){

		$result = $this->db->query("SELECT DISTINCT period from new_data where kpi_id='$kpi' and financial_year='$this->financial_year'
")->result();
		return $result;
	}
	public function get_comments($kpi,$period)
	{

		$results = $this->db->query("SELECT DISTINCT comment from new_data where kpi_id='$kpi' and financial_year='$this->financial_year' and period = '$period' and comment is NOT NULL")->result();

	return	$results;
	}
	public function get_calculation($kpi, $period)
	{

		$results = $this->db->query("SELECT total_numerator as numerator, total_denominator as denominator from report_kpi_trend where kpi_id='$kpi' and financial_year='$this->financial_year' and period = '$period'")->result();

		return $results;
	}

	public function get_computation($kpi)
	{

		$results = $this->db->query("SELECT computation from kpi where kpi_id='$kpi'")->row()->computation;

		return $results;
	}


	//api calls

	public function person_performance($district, $fy,$api=FALSE)
	{
		$id = urldecode($district);

		$person_datas = $this->db->query("SELECT * FROM performanace_data JOIN data_mapper ON performanace_data.ihris_pid=data_mapper.ihris4_pid WHERE district='$id' AND financial_year='$fy' ")->result_array();

		$entries = [];

		foreach ($person_datas as $person_data) {
			// Extract necessary data
			$kpi = $person_data['short_name'];
			$kpi_id = $person_data['kpi_id'];
			$periodValue = $person_data['financial_year'];
			$quarter = explode("Q", $person_data['period'])[1];
			$target = intval($person_data['data_target']);
			$score = floatval($person_data['score']);
			$practitionerId = $person_data['ihris5_pid'];

			// Construct each entry
			$entry = [
				"resource" => [
					"resourceType" => "Basic",
					"meta" => [
						"profile" => ["http://ihris.org/fhir/StructureDefinition/ihris-basic-performance"]
					],
					"extension" => [
						[
							"url" => "http://ihris.org/fhir/StructureDefinition/ihris-performance",
							"extension" => [
								[
									"url" => "kpi_id",
									"valueString" => $kpi_id
								],
								[
									"url" => "kpi",
									"valueString" => $kpi
								],
								[
									"url" => "period",
									"valueString" => $periodValue
								],
								[
									"url" => "quarter",
									"valueCoding" => [
										"system" => "http://ihris.org/fhir/CodeSystem/ihris-quarter-codesystem",
										"version" => "0.2.0",
										"code" => $quarter,
										"display" => $quarter
									]
								],
								[
									"url" => "target",
									"valueInteger" => $target
								],
								[
									"url" => "score",
									"valueDecimal" => $score
								]
							]
						],
						[
							"url" => "http://ihris.org/fhir/StructureDefinition/ihris-practitioner-reference",
							"valueReference" => [
								"reference" => "Practitioner/{$practitionerId}"
							]
						]
					]
				],
				"request" => [
					"method" => "POST",
					"url" => "Basic"
				]
			];

			$entries[] = $entry;
		}

		// Construct the FHIR Bundle
		$fhirBundle = [
			"resourceType" => "Bundle",
			"type" => "transaction",
			"entry" => $entries
		];
		if($api=="allow"){
			return $fhirBundle;

		}
		else{
		header('Content-Type: application/fhir+json');
		echo json_encode($fhirBundle, JSON_PRETTY_PRINT);
		}
	}



	public function get_ihris5data($disti)
	{
		$http = new HttpUtils();
		$headers = [
			'Content-Type' => 'application/json',
			'Accept' => 'application/json',
		];
		$districts = $this->db->get('ihris5_districts')->result();
		//$this->db->query("TRUNCATE table ihrisdata5");
		foreach ($districts as $district) {

			//s $dist = str_replace(" District","",$district->name);
			$dist = $disti;
			$response = $http->sendiHRIS5Request('ihrisdata/' . $dist, "GET", $headers, []);

			if ($response) {
				//dd(count($response));
				//$message = $this->biotimejobs_mdl->add_ihrisdata($response);

				foreach ($response->entry as $insert) {
					//dd($insert);




					$data = array(
						'ihris_pid' => $insert->ihris_pid,
						'district_id' => $insert->district_id,
						'district' => $insert->district,
						'nin' => isset($insert->nin) ? $insert->nin : null,
						'card_number' => $insert->card_number,
						'ipps' => $insert->ipps,
						'facility_type_id' => $insert->facility_type_id,
						'facility_id' => null, // Assuming facility_id is not present in JSON
						'facility' => $insert->facility,
						'department_id' => null, // Assuming department_id is not present in JSON
						'department' => null, // Assuming department is not present in JSON
						'division' => null, // Assuming division is not present in JSON
						'section' => null, // Assuming section is not present in JSON
						'unit' => '', // Assuming unit is not present in JSON
						'job_id' => $insert->job_id,
						'job' => $insert->job,
						'employment_terms' => $insert->employmentTerms,
						'salary_grade' => isset($insert->salary_grade) ? $insert->salary_grade : null,
						'surname' => $insert->surname,
						'firstname' => $insert->firstname,
						'othername' => $insert->othername,
						'mobile' => isset($insert->mobile) ? $insert->mobile : null,
						'telephone' => isset($insert->telephone) ? $insert->telephone : null,
						'institution_type_id' => $insert->facility_type_id,
						'institutiontype_name' => $insert->facility_type_id,
						'gender' => $insert->gender,
						'birth_date' => date('Y-m-d', strtotime($insert->birth_date)),
						'cadre' => isset($insert->cadre) ? $insert->cadre : null,
						'email' => isset($insert->email) ? $insert->email : null,
						'region' => $insert->region
					);



					dd($data);


					$message = $this->db->replace('ihrisdata5', $data);
					///dd($this->last->query);
				}
				$this->remap_data();

				$this->log($message);
			}
			$process = 2;
			$method = "bioitimejobs/get_ihris5data";
			if (count($response) > 0) {
				$status = "successful";
			} else {
				$status = "failed";
			}
		}

	}

	public function get_districts()
	{
		$http = new HttpUtils();
		$headers = [
			'Content-Type' => 'application/json',
			'Accept' => 'application/json',
		];

		$response = $http->sendiHRIS5Request('ihrisdata/districts', "GET", $headers, []);

		if ($response) {
			//dd(count($response));
			//$message = $this->biotimejobs_mdl->add_ihrisdata($response);
			$this->db->query("TRUNCATE table ihris5_districts");
			foreach ($response as $insert) {

				//  dd($insert);

				$message = $this->db->insert('ihris5_districts', $insert);
				///dd($this->last->query);
			}

			$this->log($message);
		}
		$process = 2;
		$method = "bioitimejobs/ihris5_districts";
		if (count($response) > 0) {
			$status = "successful";
		} else {
			$status = "failed";
		}
	}
	public function remap_data()
	{

		// Optimized and fixed query to get matching values
		$this->db->select('ihrisdata.ihris_pid as ihris4_pid, ihrisdata5.ihris_pid as ihris5_pid');
		$this->db->from('ihrisdata');
		$this->db->join(
			'ihrisdata5',
			'ihrisdata.card_number = ihrisdata5.card_number OR 
     ihrisdata.ipps = ihrisdata5.ipps OR 
     ihrisdata.nin = ihrisdata5.nin'
		);
		$this->db->where('ihrisdata.nin IS NOT NULL');
		$this->db->where('ihrisdata.ipps IS NOT NULL');
		$this->db->where('ihrisdata.card_number IS NOT NULL');

		$query = $this->db->get();
		$map_values = $query->result();

		// Check if there are values to insert
		if (!empty($map_values)) {
			foreach ($map_values as $insert) {
				$data = array(
					'ihris4_pid' => $insert->ihris4_pid,
					'ihris5_pid' => $insert->ihris5_pid
				);

				// Using REPLACE to avoid duplicates
				$this->db->replace('data_mapper', $data);
			}
		}

	}
	public function fhir_Server_post()
	{
		$fy = '2023-2024';
		$district = 'MBALE';
		$body = $this->person_performance($district,$fy,'allow');
		// dd($body);
		$http = new HttpUtils();

		$endpoint = 'hapi/fhir';
		$headers = array(
			'Content-Type: application/fhir+json',
			'Content-Length: ' . strlen(json_encode($body)),
			//'Authorization: JWT ' . $this->get_token()
		);

		$response = $http->curlsendiHRIS5HttpPost($endpoint, $headers, $body);

		if ($response) {
			dd($response);
		}
	}













}
