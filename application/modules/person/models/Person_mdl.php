<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Person_mdl extends CI_Model {

	
public function __Construct(){

		parent::__Construct();


}
	public function get_person_job($user_id)
	{
		$this->db->where('ihris_pid', "$user_id");
	return	$job_id = $this->db->get('ihrisdata')->row()->job_id;
	}
	public function get_person_focus_area($job_id)
	{
	
		$job = $this->db->query("SELECT DISTINCT id, name as subject_area, icon FROM subject_areas WHERE id IN(SELECT distinct kpi.subject_area from kpi where kpi.job_id='$job_id')")->result();
		return $job;
	}
	
public function get_person_kpi($user_id, $focus_area){
	$job_id = $this->get_person_job($user_id);
		
	if($job_id){

	if($focus_area){
		$fa = "and subject_area='$focus_area'";
	}
	else{
		$fa="";
	}
	 return $this->db->query("SELECT * from kpi where job_id='$job_id' $fa and status=1 ")->result();

	}
	else{
	return array("data"=>"no data");
	}
}

public function get_employees($filters, $start = FALSE, $limit = FALSE){

	if ($start) {
			$limits = " LIMIT $limit,$start";
	} else {
			$limits = " ";
	}
		
	if($filters){
	$facility= $filters;
   $query =	$this->db->query("SELECT * FROM ihrisdata_staging WHERE facility_id='$facility' ORDER BY surname ASC $limits ")->result();
	//dd($this->db->last_query());
	return $query;

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

