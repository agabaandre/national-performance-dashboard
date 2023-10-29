<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Person_mdl extends CI_Model {

	
public function __Construct(){

		parent::__Construct();


}
public function get_person_kpi($user_id){

	$this->db->where('ihris_pid', "$user_id");
	$job_id=$this->db->get('ihrisdata')->row()->job_id;
		
	if($job_id){
	 return $this->db->query("SELECT * from kpi where job_id='$job_id' and status=1")->result();

	}
	else{
	return array("data"=>"no data");
	}
}

	public function get_kpi_data($user_id)
	
	{

		
			$this->db->where('ihris_pid', "$user_id");
			$job_id = $this->db->get('ihrisdata')->row()->job_id;

		if ($job_id) {

			$this->db->select('new_data.kpi_id, new_data.period, new_data.financial_year, new_data.numerator, new_data.denominator, new_data.data_target, new_data.comment, kpi.short_name, kpi.job_id, new_data.uploaded_by');
			$this->db->from('new_data');
			$this->db->join('kpi', 'kpi.job_id = new_data.job_id and kpi.kpi_id = new_data.kpi_id');
			$this->db->where('new_data.uploaded_by', $user_id);
			if (isset($_GET['period'])){
				$period = $_GET['period'];
			$this->db->where('new_data.period',"$period");
			}
			if (isset($_GET['financial_year'])) {
				$fy = $_GET['financial_year'];
				$this->db->where('new_data.financial_year', "$fy");
			}
			
		} else {
			return array("data" => "no data");
		}
	}
public function get_employees($filters){
	if(count($filters)>0){
	$name= $filters['name'];
	$facility= $filters['facility'];
   return	$this->db->query("SELECT * FROM ihrisdata_staging WHERE (surname LIKE '$name%' OR firstname LIKE '$name%' OR othername LIKE '$name%') AND facility_id='$facility'")->result();
  }
  else{

	return (object)array();

   }

}
	public function ppa_employees($filters)
	{
		if (count($filters) > 0) {
			$name = $filters['name'];
			$facility = $filters['facility'];
			return $this->db->query("SELECT * FROM ihrisdata_staging WHERE (surname LIKE '$name%' OR firstname LIKE '$name%' OR othername LIKE '$name%') AND facility_id='$facility'")->result();
		} else {
			return $this->db->get('ihrisdata')->result();
		}

	}
	// new file

}

