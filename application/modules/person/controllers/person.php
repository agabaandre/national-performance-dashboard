
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Person extends MX_Controller
{


    public function __Construct()
    {

        parent::__Construct();
        $this->db->query('SET SESSION sql_mode = ""');

        $this->load->model('person_mdl');
        // $this->load->library('excel');  

    }
    public function index()
    {

        $data['title'] = 'Staff KPI Data';
        $data['page'] = 'submit_performance';
        $data['module'] = "person";
        echo Modules::run('template/layout', $data);


    }
    public function performance()
    {

        $data['title'] = 'My Performance';
        $data['page'] = 'submit_performance';
        $data['module'] = "person";
        redirect('data/subject/1/Finance_and_Administration');
    }
    function importcsv()
    {
        if (isset($_FILES["upload_csv_file"]["name"])) {
            $path = $_FILES["upload_csv_file"]["tmp_name"];
            $object = PHPExcel_IOFactory::load($path);
            foreach ($object->getWorksheetIterator() as $sale) {
                $highestRow = $sale->getHighestRow();
                $highestColumn = $sale->getHighestColumn();
                for ($row = 1; $row <= $highestRow; $row++) {

                    if (!empty($sale->getCellByColumnAndRow(1, $row)->getValue())) {
                        $dim1 = trim($sale->getCellByColumnAndRow(1, $row)->getValue());
                        $dim1k = trim($sale->getCellByColumnAndRow(2, $row)->getValue());
                    } else {
                        $dim1 = NULL;
                        $dim1k = NULL;

                    }


                    if (!empty($sale->getCellByColumnAndRow(3, $row)->getValue())) {
                        $dim2 = trim($sale->getCellByColumnAndRow(3, $row)->getValue());
                        $dim2k = trim($sale->getCellByColumnAndRow(4, $row)->getValue());
                    } else {
                        $dim2 = NULL;
                        $dim2k = NULL;

                    }
                    if (!empty(trim($sale->getCellByColumnAndRow(5, $row)->getValue()))) {
                        $dim3 = trim($sale->getCellByColumnAndRow(5, $row)->getValue());
                        $dim3k = trim($sale->getCellByColumnAndRow(6, $row)->getValue());
                    } else {
                        $dim3 = NULL;
                        $dim3k = NULL;

                    }

                    $data = array(
                        'kpi_id' => str_replace(" ", "", $sale->getCellByColumnAndRow(0, $row)->getValue()),
                        'dimension1' => @$dim1,
                        'dimension1_key' => @$dim1k,
                        'dimension2' => @$dim2,
                        'dimension2_key' => @$dim2k,
                        'dimension3' => @$dim3,
                        'dimension3_key' => @$dim3k,
                        'financial_year' => str_replace("/", "-", $sale->getCellByColumnAndRow(7, $row)->getValue()),
                        'period_year' => str_replace(" ", "", $sale->getCellByColumnAndRow(8, $row)->getValue()),
                        'period' => ucwords(str_replace(" ", "", $sale->getCellByColumnAndRow(9, $row)->getValue())),
                        'numerator' => str_replace("%", "", str_replace(",", "", $sale->getCellByColumnAndRow(10, $row)->getValue())),
                        'denominator' => str_replace("%", "", str_replace(",", "", $sale->getCellByColumnAndRow(11, $row)->getValue())),
                        'data_target' => str_replace("%", "", $sale->getCellByColumnAndRow(12, $row)->getValue()),
                        'comment' => $sale->getCellByColumnAndRow(13, $row)->getValue(),
                        'uploaded_by' => $_SESSION['id'],
                        'staff_id' => $_SESSION['id']
                     
                    );

                    // print_r($data);

                    if (!empty($data)) {
                        $this->db->insert("new_data", $data);
                    }


                }
            }

            $this->session->set_flashdata('message', 'Upload Successful');
            redirect('person');
        }
    }

    
	public function all_users($job = FALSE)
	{
	
		$staffs =  $this->db->query("SELECT * from ihrisdata")->result();
        //print_r($staffs);
       
		foreach ($staffs as $staff) :
             if(empty($staff->email)){
               $email = str_replace('person|', '', $staff->ihris_pid) . '@pmd.health.go.ug';
             }
             else{
               $email = $staff->email;
             }
            $users['ihris_pid'] = $staff->ihris_pid;
			$users['username'] = str_replace('person|','',$staff->ihris_pid);
			$users['email'] = $email;
			$users['firstname'] =  $staff->firstname;
            $users['lastname'] = $staff->surname;
			$users['status'] = 1;
			$users['is_admin'] = 0;
            $users['subject_area'] = '["1"]';
            $users['info_category'] = 6;
			$users['password'] = $this->argonhash->make('12345678');
			$users['user_type'] = 'staff';
            $users['image'] = './assets/img/user/MOH.png';
            print_r($users);
            exit;
			$this->db->replace('user', $users);
		endforeach;
		$accts = $this->db->affected_rows();


		$msg = array(
			'msg' => $accts . 'Staff Accounts Created .',
			'type' => 'info'
		);
		// Modules::run('utility/setFlash', $msg);
		// if (!$job) {
		 	redirect('person');
		// }
	}


    public function do_upload()
    {
     

                $data = array(
                    'kpi_id' => $this->input->post('kpi_id'),
                    'financial_year' => $this->input->post('financial_year'),
                    'period_year' => $this->input->post('period_year'),
                    'period' => $this->input->post('period'),
                    'numerator' => $this->input->post('numerator'),
                    'denominator' => $this->input->post('denominator'),
                    'data_target' => $this->input->post('data_target'),
                    'comment' => $this->input->post('comment'),
                    'upload_date' =>$this->input->post('upload_date'),
                    'uploaded_by' => $this->session->userdata('ihris_pid'),
                    'officer_role_id' => get_field($this->session->userdata('ihris_pid'),'job_id'),
                    'facility' => get_field($this->session->userdata('ihris_pid'), 'facility_id'),

                );
            
        

                    $this->db->insert('new_data', $data);
          
            $this->session->set_flashdata('message', 'Data Added successfully.');
            redirect('person/index');


        

    }

    public function update_check($kpi_id,$period,$financial_year){
    return $this->db->query("SELECT kpi_id from new_data where kpi_id='$kpi_id' and period='$period' and financial_year='$financial_year'")->num_rows();

    }
    function generate_csv_file()
    {
        // define header row
        $kpi_id = $this->input->get('kpi_id');
    

        // define example data row
        $data_rows = $this->db->query("SELECT kpi_id,
                dimension1,
                dimension1_key,
                dimension2,
                dimension2_key,
                dimension3,
                dimension3_key,
                financial_year,
                period_year,
                period,
                numerator,
                denominator,
                data_target,
                comment FROM new_data where kpi_id= '$kpi_id' LIMIT 10") ->result_array();
    
        $filename = 'sample_upload_'.$kpi_id;
        if (count($data_rows) > 0) {
            render_csv_data($data_rows, $filename, true);
        }
        else{
            $data_rows = array(
                'kpi_id',
                'dimension1',
                'dimension1_key',
                'dimension2',
                'dimension2_key',
                'dimension3',
                'dimension3_key',
                'financial_year',
                'period_year',
                'period',
                'numerator',
                'denominator',
                'data_target',
                'comment'
            );
            render_csv_data($data_rows, $filename, false);

        }
    }
function jobs()
    {

        $jobs = $this->kpi_mdl->get_all_jobs($id = FALSE);
        dd($jobs);
    }
}