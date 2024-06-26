<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
public function person_performance($fid,$period,$fy){
	
	$id = urldecode($fid);

$person_data= $this->db->query("SELECT * from performanace_data WHERE ihris_pid='$id' AND financial_year='$fy' AND period='$period'")->row();

echo json_encode($person_data);

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
	

	

   

}
