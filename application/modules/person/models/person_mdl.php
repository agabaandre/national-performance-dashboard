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


}