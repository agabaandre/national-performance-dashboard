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
	public function get_facilities()
	{
		if(!empty($this->session->userdata('facility_id'))){
		$facility_id = $this->session->userdata('facility_id');
		$data = $this->db->query("SELECT DISTINCT new_data.facility as facility_id, ihrisdata.facility from new_data JOIN ihrisdata on new_data.facility=ihrisdata.facility_id WHERE facility_id='$facility_id'")->result();
		}
		else{
		$data = $this->db->query("SELECT DISTINCT new_data.facility as facility_id, ihrisdata.facility from new_data JOIN ihrisdata on new_data.facility=ihrisdata.facility_id")->result();
		}
		
		foreach ($data as $facility) {
			$facility->staff = $this->get_staff($facility->facility_id);
		}

			//dd($data);

		return $data;

	
	}

	public function get_staff($facility_id)
	{
		$job_cat = $this->input->get('kpi_group');
		$job="";
		if(!empty($job_cat)){
        $job = "and kpi_group_id=$job_cat";
		}
		if(!empty($this->session->userdata('ihris_pid'))&& ($this->session->userdata('user_type') == 'staff')){
		$ihris_pid = $this->session->userdata('ihris_pid');
		
		return $this->db->query("SELECT DISTINCT ihris_pid, surname,firstname,kpi_group_id as job_category_id  from ihrisdata where facility='$facility_id' $job and ihris_pid='$ihris_pid'")->result();
		}
		else{
			return $this->db->query("SELECT DISTINCT ihris_pid, surname,firstname,kpi_group_id as job_category_id from ihrisdata where facility='$facility_id' $job")->result();

		}
	}
	

	public function staff_performance($ihris_id,$financial_year, $period,$kpi_id=FALSE)
	{
		if (!empty($kpi_id)){
			$kpi_id ="and kpi_id='$kpi_id'"; 
		}
		else{
			$kpi_id="";
		}

     return $this->db->query("SELECT * from performanace_data WHERE ihris_pid='$ihris_id' and financial_year='$financial_year' and period='$period' $kpi_id")->row();

	
	}

}
