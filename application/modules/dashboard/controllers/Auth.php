<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MX_Controller {
 	
 	public function __construct()
 	{
 		parent::__construct();

   $this->db->query('SET SESSION sql_mode = ""');

 		$this->load->model(array(
 			'auth_model'
 		));

		//$this->load->helper('captcha');
 	}

	public function index()
	{  
		if ($this->session->userdata('isLogIn'))
		redirect('dashboard/home');
		$data['title']    = display('login'); 
		$this->form_validation->set_rules('email', display('email'), 'required|valid_email|max_length[100]|trim');
		$this->form_validation->set_rules('password', display('password'), 'required|max_length[100]|trim');

		$data['user'] = (object)$userData = array(
			'email' 	 => $this->input->post('email'),
			'password'   => $this->input->post('password'),
		);
		#-------------------------------------#
		if ( $this->form_validation->run())
		{

		$user = $this->auth_model->checkUser($userData);

		$user->row()->password;
		
	     $auth = ($this->argonhash->check($this->input->post('password'), $user->row()->password));

		// 	print_r($this->argonhash->make($this->input->post('password')));
		//  die();
		//ignore argon for dev

		if(!empty($user->row()->ihris_pid)){
			$facilityid = @get_field($user->row()->ihris_pid, 'facility_id');
			$facilityname  = @get_field($user->row()->ihris_pid, 'facility');

		}
		else if (!empty($user->row()->facility_id)){

			$facilityid = $user->row()->facility_id;
            $this->db->where("facility_id","$facilityid");
			$facilityname = $this->db->get('ihrisdata_staging')->row()->facility;
		}
		
	   if(!empty($user->row()->image)){
         $image = $user->row()->image;
	   }
	   else{
		 $image = './assets/img/user/MOH.png';

	   }

		if($auth) {

             	$sData = array(
					'isLogIn' 	  => true,
					'isAdmin' 	  => (($user->row()->is_admin == 1)?true:false),
					'id' 		  => $user->row()->id,
					'fullname'	  => $user->row()->fullname,
					'email' 	  => $user->row()->email,
					'image' 	  => $image,
					'last_login'  => $user->row()->last_login,
					'last_logout' => $user->row()->last_logout,
					'ip_address'  => $user->row()->ip_address,
					'user_type'  => $user->row()->user_type,
					'subject_area'  => $user->row()->subject_area,
					'financial_year' => $this->current_financial_year(),
					'dimension_chart' => $this->dimension_chart(),
					'info_category' => $user->row()->info_category,
					'allow_all_categories'=> $user->row()->allow_all_categories,
					'ihris_pid' => $user->row()->ihris_pid,
					'facility_id'=> $facilityid ,
					'facility' => $facilityname ,
					'district_id' => @get_field($user->row()->ihris_pid, 'district_id')
					);	

					//dd($sData);
					

					//store date to session 
					$this->session->set_userdata($sData);
					//update database status
					$this->auth_model->last_login();
					if($user->row()->user_type == 'admin'){
					$this->session->set_flashdata('message', display('welcome_back').' '.$user->row()->fullname);
					redirect('person/manage_people');
					}
					else if (($user->row()->user_type == 'staff')||($user->row()->user_type == 'data')) {
					$this->session->set_flashdata('message', display('welcome_back') . ' ' . $user->row()->fullname);
					redirect('person/performance_list');
					}

			   } else {
				$this->session->set_flashdata('exception', display('incorrect_email_or_password'));
				redirect('login');
			} 

		} else {

			echo Modules::run('template/login',$data);
		}
	}

	public function financialYear()
	{
	
	$_SESSION['financial_year'] = str_replace(" ", "", $this->input->post('financial_year'));

	redirect('data/subject/1/Clinical_Care');
	

	}

	public function dimension_chart(){
	return $this->db->get('setting')->row()->dimension_chart;
	}

	public function current_financial_year()
	{
		$current_year = date("Y");
		$current_month = date("m");
		if ($current_month > 6) {
			return ($current_year . "-" . ($current_year + 1));
		} else {
			return (($current_year - 1) . "-" . $current_year);
		}
	}
  
	public function logout()
	{ 
		//update database status
		$this->auth_model->last_logout();
		//destroy session
		$this->session->sess_destroy();
		redirect('login');
	}
    /*
 |--------------------------------------------------------
 | Finger print Device information
 |--------------------------------------------------------
 */
 public function deviceData(){
    return $this->db->select('*')->from('deviceinfo')->get()->row();
 }
 function DataCategory(){
		$_SESSION['info_category'] = $_GET['info_category'];
		redirect();
 }
}


