<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
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
	
public function get_person_kpi($job_id, $focus_area){
	
		
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

public function get_employees($facility, $ihris_pid, $start, $limit){

	if($facility){
	if (!empty($facility)) {
	$facility="facility_id ='$facility'";
	}
	if($ihris_pid){
		$id = "AND ihris_pid ='$ihris_pid'";
	
	}

    if(!empty($start)){
    $limiting = "LIMIT $limit,$start";
	 }

   $query =	$this->db->query("SELECT ihrisdata.*, 'PMD' as source FROM ihrisdata WHERE $facility $id UNION SELECT ihrisdata_staging.*,'iHRIS' as source from ihrisdata_staging WHERE ihrisdata_staging.ihris_pid NOT IN (SELECT DISTINCT ihris_pid from ihrisdata WHERE $facility $id) AND $facility $id ORDER BY surname ASC  $limiting ");


   //dd($ihris_pid);
	//dd($this->db->last_query());
	return $query->result();
}
}





	public function get_analytics_employees($facility, $name, $start = FALSE, $limit = FALSE)
	{
				$pid =$this->session->userdata('ihris_pid');
				$data_role=$this->session->userdata('data_role');
				$user_type = $this->session->userdata('user_type');

		if ((!empty($pid)) && ($data_role != 1) && ($user_type == "staff")) {

			$this->db->where('ihris_pid', "$pid");

		}

		if ($facility) {
			if (!empty($facility)) {
				$this->db->where('facility_id', $facility);
			}
			if ($name) {
				$this->db->group_start();
				$this->db->or_where('surname', "$name");
				$this->db->or_where('firstname', "$name");
				$this->db->or_where('othername', "$name");
				$this->db->group_end();
			}
			

			$this->db->order_by('surname', 'ASC');
			if ($start) {
				$this->db->limit($start, $limit);
			}

			$query = $this->db->get("ihrisdata")->result();
			//dd($this->db->last_query());
			return $query;
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

public function get_person_data($filters,$facility)
	{
	 $supervisor = $this->session->userdata('ihris_pid');

		$this->db->select('new_data.draft_status,new_data.period,new_data.ihris_pid,new_data.upload_date, new_data.financial_year, new_data.approved, new_data.supervisor_id,new_data.approved2, new_data.supervisor_id_2, ihrisdata.surname, ihrisdata.firstname, ihrisdata.othername, ihrisdata.facility_id,ihrisdata.facility, ihrisdata.job, new_data.job_id as kpi_group');
		$this->db->from('new_data');
		$this->db->join('ihrisdata', 'new_data.ihris_pid = ihrisdata.ihris_pid');
		$this->db->join('kpi_job_category', 'new_data.job_id = kpi_job_category.job_id');
		$this->db->where('new_data.draft_status',1);
		if(!empty($this->session->userdata('facility_id'))){
		 $this->db->where('new_data.facility',"$facility");
		}
		if(!empty($supervisor)){
		$this->db->group_start();
		$this->db->or_where('new_data.supervisor_id', "$supervisor");
		$this->db->or_where('new_data.supervisor_id_2', "$supervisor");
		$this->db->group_end();
		}
		 
		if (count($filters) > 0) {

			foreach ($filters as $key => $value) {
				if (!empty($value)) {
					$this->db->where($key, "$value");
				}
			}
		}
	
		$this->db->group_by('new_data.financial_year, new_data.period,new_data.ihris_pid');
		$this->db->order_by('new_data.financial_year', 'ASC');

		$query = $this->db->get();
		//dd($this->db->last_query());
		return $query->result();
		

	}
	// new file

	


	public function mydata_data($filters)
	{
		$ihris_pid = $this->session->userdata('ihris_pid');

		$this->db->select('new_data.period,new_data.ihris_pid,new_data.upload_date, new_data.financial_year, new_data.approved, new_data.supervisor_id,new_data.approved2, new_data.supervisor_id_2, ihrisdata.surname, ihrisdata.firstname, ihrisdata.othername, ihrisdata.facility_id,ihrisdata.facility, ihrisdata.job, new_data.job_id as kpi_group');
		$this->db->from('new_data');
		$this->db->join('ihrisdata', 'new_data.ihris_pid = ihrisdata.ihris_pid');
		$this->db->join('kpi_job_category', 'new_data.job_id = kpi_job_category.job_id');
		$this->db->where('new_data.ihris_pid',"$ihris_pid");

		if (count($filters) > 0) {

			foreach ($filters as $key => $value) {
				if (!empty($value)) {
					$this->db->where($key, "$value");
				}
			}
		}

		$this->db->group_by('new_data.financial_year, new_data.period,new_data.ihris_pid');
		$this->db->order_by('new_data.financial_year', 'ASC');

		$query = $this->db->get();
		//dd($this->db->last_query());
		return $query->result();


	}
	// new 


	public function lara_person_data($filters, $facility)
	{
		$supervisor = $this->session->userdata('ihris_pid');

		$query = DB::table('new_data')
			->select('new_data.draft_status', 'new_data.period', 'new_data.ihris_pid', 'new_data.upload_date', 'new_data.financial_year', 'new_data.approved', 'new_data.supervisor_id', 'new_data.approved2', 'new_data.supervisor_id_2', 'ihrisdata.surname', 'ihrisdata.firstname', 'ihrisdata.othername', 'ihrisdata.facility_id', 'ihrisdata.facility', 'ihrisdata.job', 'new_data.job_id as kpi_group')
			->join('ihrisdata', 'new_data.ihris_pid', '=', 'ihrisdata.ihris_pid')
			->join('kpi_job_category', 'new_data.job_id', '=', 'kpi_job_category.job_id')
			->where('new_data.draft_status', 1);

		if (!empty ($this->session->userdata('facility_id'))) {
			$query->where('new_data.facility', $facility);
		}

		if (!empty ($supervisor)) {
			$query->where(function ($query) use ($supervisor) {
				$query->orWhere('new_data.supervisor_id', $supervisor)
					->orWhere('new_data.supervisor_id_2', $supervisor);
			});
		}

		if (count($filters) > 0) {
			foreach ($filters as $key => $value) {
				if (!empty ($value)) {
					$query->where($key, $value);
				}
			}
		}

		$query->groupBy('new_data.financial_year', 'new_data.period', 'new_data.ihris_pid')
			->orderBy('new_data.financial_year', 'ASC');

		return $query->paginate(10); // Adjust the number based on your pagination needs
	}

}

