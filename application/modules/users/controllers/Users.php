<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends MX_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->db->query('SET SESSION sql_mode = ""');
		$this->load->model(
			array(
				'user_mdl'
			)
		);

		if (!$this->session->userdata('isAdmin'))
			redirect('login');
	}

	public function index()
	{
		$data['title'] = display('user_list');
		$data['module'] = "users";
		$data['page'] = "list";
		// Don't load all users - DataTables will fetch via AJAX
		echo Modules::run('template/layout', $data);
	}

	/**
	 * Server-side DataTables endpoint
	 */
	public function datatables()
	{
		// Check admin permission
		if (!$this->session->userdata('isAdmin')) {
			header('HTTP/1.1 403 Forbidden');
			echo json_encode(array('error' => 'Access denied'));
			exit;
		}

		// Get DataTables parameters
		$draw = intval($this->input->post("draw"));
		$start = intval($this->input->post("start"));
		$length = intval($this->input->post("length"));
		$search = $this->input->post("search")["value"] ?? '';
		$order_column = intval($this->input->post("order")[0]["column"] ?? 0);
		$order_dir = $this->input->post("order")[0]["dir"] ?? "ASC";

		// Get data from model
		$result = $this->user_mdl->get_datatables($start, $length, $search, $order_column, $order_dir);
		$total_records = $this->user_mdl->count_all();

		// Format data for DataTables
		$data = array();
		$sl = $start + 1;
		
		foreach ($result['data'] as $value) {
			$row = array();
			$row[] = $sl++;
			$row[] = '<img src="' . base_url(!empty($value->image) ? $value->image : 'assets/img/icons/default.jpg') . '" alt="Image" height="50">';
			$row[] = $value->fullname;
			$row[] = $value->email;
			$row[] = $value->last_login ?? '-';
			$row[] = $value->user_type ?? '-';
			$row[] = $value->ip_address ?? '-';
			$row[] = (($value->status == 1) ? display('active') : display('inactive'));
			
			// Action buttons
			$actions = '<a href="' . base_url("users/form/$value->id") . '?facility_id=' . urlencode($value->facility_id ?? '') . '" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Update"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
			
			if ($value->is_admin == 1) {
				$actions .= '<button class="btn btn-info btn-sm" title="' . display('admin') . '">' . display('admin') . '</button>';
			} else {
				$actions .= '<a href="' . base_url("users/delete/$value->id") . '" onclick="return confirm(\'Are you sure ?\')" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>';
			}
			
			$row[] = $actions;
			$data[] = $row;
		}

		// Prepare response
		$output = array(
			"draw" => $draw,
			"recordsTotal" => $total_records,
			"recordsFiltered" => $result['total_records'],
			"data" => $data
		);

		// Output JSON
		header('Content-Type: application/json');
		echo json_encode($output);
		exit;
	}
	


	public function email_check($email, $id)
	{
		$emailExists = $this->db->select('email')
			->where('email', $email)
			->where_not_in('id', $id)
			->get('user')
			->num_rows();

		if ($emailExists > 0) {
			$this->form_validation->set_message('email_check', 'The {field} is already registered.');
			return false;
		} else {
			return true;
		}
	}


	public function form()
	{
		@$id = $this->uri->segment(3);
		$data['title'] = display('add_user');
		/*-----------------------------------*/
		$this->form_validation->set_rules('firstname', display('firstname'), 'required|max_length[50]');
		$this->form_validation->set_rules('lastname', display('lastname'), 'required|max_length[50]');
		#------------------------#
		$this->form_validation->set_rules('email', display('email'), 'required|valid_email|max_length[100]');
	
		$this->form_validation->set_rules('status', display('status'), 'required|max_length[1]');
		/*-----------------------------------*/
		$config['upload_path'] = './assets/img/user/';
		$config['allowed_types'] = 'gif|jpg|png';
		$image = $this->input->post('image');
	
		/*-----------------------------------*/
		if (!empty(($this->input->post('subject_area')) && ($this->input->post('user_type') != 'admin'))) {
			@$subjectarea = json_encode($this->input->post('subject_area'));
		} else {
			@$subjectarea = '';
		}

		$data['user'] = (object) $userLevelData = array(
			'id' => $this->input->post('id'),
			'firstname' => $this->input->post('firstname'),
			'lastname' => $this->input->post('lastname'),
			'email' => $this->input->post('email'),
			'username' => $this->input->post('email'),
			'password' => (!empty($this->input->post('password')) ? $this->argonhash->make($this->input->post('password')) : $this->argonhash->make($this->input->post('oldpassword'))),
			'about' => $this->input->post('about', true),
			'image' => (!empty($image) ? $image : $this->input->post('old_image')),
			'last_login' => null,
			'last_logout' => null,
			'ip_address' => null,
			'status' => $this->input->post('status'),
			'district_id' => $this->input->post('district_id'),
			'facility_id' => $this->input->post('facility_id'),
			'subject_area' => $subjectarea,
			'info_category' => $this->input->post('info_category'),
			'allow_all_categories' => $this->input->post('allow_all_categories'),
			'user_type' => $this->input->post('user_type'),
			'is_admin' => 0
		);

		/*-----------------------------------*/
		if ($this->form_validation->run()) {


			if (empty($userLevelData['id'])) {
				try {
					if ($this->user_mdl->create($userLevelData)) {
						$this->session->set_flashdata('message', display('save_successfully'));
					} else {
						// Check for database errors
						$dbError = $this->db->error();
						if (!empty($dbError['code']) && $dbError['code'] == 1062) {
							// Extract duplicate value from error message
							if (preg_match("/Duplicate entry '([^']+)' for key/", $dbError['message'], $matches)) {
								$duplicateValue = $matches[1];
								$this->session->set_flashdata('exception', "A user with email '{$duplicateValue}' already exists. Please use a different email address.");
							} else {
								$this->session->set_flashdata('exception', "A user with this email already exists. Please use a different email address.");
							}
						} else {
							$this->session->set_flashdata('exception', display('please_try_again'));
						}
					}
				} catch (Exception $e) {
					// Check if it's a duplicate entry error
					$errorMessage = $e->getMessage();
					$errorCode = $e->getCode();
					
					if ($errorCode == 1062 || strpos($errorMessage, 'Duplicate entry') !== false) {
						// Extract the duplicate value from the error message
						if (preg_match("/Duplicate entry '([^']+)' for key/", $errorMessage, $matches)) {
							$duplicateValue = $matches[1];
							$this->session->set_flashdata('exception', "A user with email '{$duplicateValue}' already exists. Please use a different email address.");
						} else {
							$this->session->set_flashdata('exception', "A user with this email already exists. Please use a different email address.");
						}
					} else {
						$this->session->set_flashdata('exception', 'An error occurred while creating the user. Please try again.');
					}
				}
				redirect("users/form");

			} else {
				try {
					if ($this->user_mdl->update($userLevelData)) {
						$this->session->set_flashdata('message', display('update_successfully'));
					} else {
						// Check for database errors
						$dbError = $this->db->error();
						if (!empty($dbError['code']) && $dbError['code'] == 1062) {
							// Extract duplicate value from error message
							if (preg_match("/Duplicate entry '([^']+)' for key/", $dbError['message'], $matches)) {
								$duplicateValue = $matches[1];
								$this->session->set_flashdata('exception', "A user with email '{$duplicateValue}' already exists. Please use a different email address.");
							} else {
								$this->session->set_flashdata('exception', "A user with this email already exists. Please use a different email address.");
							}
						} else {
							$this->session->set_flashdata('exception', display('please_try_again'));
						}
					}
				} catch (Exception $e) {
					// Check if it's a duplicate entry error
					$errorMessage = $e->getMessage();
					$errorCode = $e->getCode();
					
					if ($errorCode == 1062 || strpos($errorMessage, 'Duplicate entry') !== false) {
						// Extract the duplicate value from the error message
						if (preg_match("/Duplicate entry '([^']+)' for key/", $errorMessage, $matches)) {
							$duplicateValue = $matches[1];
							$this->session->set_flashdata('exception', "A user with email '{$duplicateValue}' already exists. Please use a different email address.");
						} else {
							$this->session->set_flashdata('exception', "A user with this email already exists. Please use a different email address.");
						}
					} else {
						$this->session->set_flashdata('exception', 'An error occurred while updating the user. Please try again.');
					}
				}
				$post_id = $this->input->post('id');
				redirect("users/form/$post_id ");
			}
		}


		 else {
			$data['module'] = "users";
			$data['page'] = "form";
			if (!empty($id)) {
				$data['user'] = $this->user_mdl->single($id);
			}
			echo Modules::run('template/layout', $data);
		}
	}

	public function delete($id = null)
	{
		if ($this->user_mdl->delete($id)) {
			$this->session->set_flashdata('message', display('delete_successfully'));
		} else {
			$this->session->set_flashdata('exception', display('please_try_again'));
		}

		redirect("users/index");
	}


}