
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use utils\HttpUtils;

class Person extends MX_Controller
{


    public function __Construct()
    {

        parent::__Construct();
        $this->db->query('SET SESSION sql_mode = ""');

        $this->load->model('person_mdl');

        $http = new HttpUtils();
        // $this->load->library('excel');  

    }
    public function index()
    {

        $data['title'] = 'Staff KPI Data';
        $data['page'] = 'submit_performance';
        //$id = $this->session->userdata('ihris_pid');
        $data['show'] = (!empty($this->input->get('period')) && !empty($this->input->get('financial_year'))) ? 1 : 0;
        $focus_area = $this->input->get('focus_area');
        $job_id = $this->input->get('job_id');
        $fy= $this->input->get('financial_year');
        $pd = $this->input->get('period');
        $ihris_pid = urldecode($this->input->get('ihris_pid'));
        $data['readonly'] = $this->db->query("SELECT draft_status from new_data WHERE period='$pd' AND financial_year='$fy' and ihris_pid='$ihris_pid'")->row()->draft_status;
        $data['kpidatas'] = $this->person_mdl->get_person_kpi($job_id, $focus_area);
        $data['focus_areas'] = $this->person_mdl->get_person_focus_area($job_id);
        $data['module'] = "person";
        echo Modules::run('template/layout', $data);
    


    }
    public function curlgetHttp($endpoint, $headers, $username, $password)
    {
        $url = $endpoint;
        $ch = curl_init($url);

        // Post values (if needed)
        // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));

        // Option to Return the Result, rather than just true/false
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Set Request Headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Basic Authentication
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");

        // Time to wait while waiting for connection...indefinite
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);

        // Set cURL timeout and processing timeout
        curl_setopt($ch, CURLOPT_TIMEOUT, 200);

        // Perform the request, and save content to $result
        $result = curl_exec($ch);

        // cURL error handling
        $curl_errno = curl_errno($ch);
        $curl_error = curl_error($ch);
        if ($curl_errno > 0) {
            curl_close($ch);
            return "CURL Error ($curl_errno): $curl_error\n";
        }

        $info = curl_getinfo($ch);
        curl_close($ch);

        $decodedResponse = json_decode($result);
        return $decodedResponse;
    }
    function fetch_orgunits()
    {
        // Base URL for the API endpoint
        $baseUrl = 'https://hmis.health.go.ug/api/organisationUnits';

        // Initial URL to fetch the first page
        $url = $baseUrl . '?fields=id,name,geometry,parent[id,name,parent[id,name,parent[id,name]]]&level=5&paging=false';

        // Initialize the data array
        $allData = array();
        // $headr[] = 'Content-length: 0';
        // $headr[] = 'Content-type: application/json';

        // Fetch data from the current URL
        $data = $this->curlgetHttp($url, $headr = [], 'moh-ict.aagaba', 'Agaba@432');

        $csvFile = 'organisation_units.csv';
        $organisationUnits = $data->organisationUnits;
        foreach ($organisationUnits as $organisationUnit):
            $csv['facility_id'] = $organisationUnit->id;
            $csv['facility'] = $organisationUnit->name;
            $csv['latitude'] = $organisationUnit->geometry->coordinates[1];
            $csv['longitude'] = $organisationUnit->geometry->coordinates[0];
            $csv['subcounty_id'] = $organisationUnit->parent->id;
            $csv['subcounty'] = $organisationUnit->parent->name;
            $csv['district_id'] = $organisationUnit->parent->parent->id;
            $csv['district_name'] = $organisationUnit->parent->parent->name;
            $csv['region_id'] = $organisationUnit->parent->parent->parent->id;
            $csv['region_name'] = $organisationUnit->parent->parent->parent->name;
            array_push($allData, $csv);
        endforeach;
        render_csv_data($allData, $csvFile);
    }

    public function dhis_orgunits()
    {

        ignore_user_abort(true);
        ini_set('max_execution_time', 0);
        // Initialize the data array
        $allData = array();
        $headr = array();
        $headr[] = 'Content-length: 0';
        $headr[] = 'Content-type: application/json';

        // Base URL for the API endpoint
        $baseUrl = 'https://hmis.health.go.ug/api/organisationUnits';

        // Initial URL to fetch the first page
        $url = $baseUrl . '?fields=id,name,parent[id,name,parent[id,name]]&level=2';

        // Fetch data from the current URL
        $data = $this->curlgetHttp($url, $headr, 'moh-ict.aagaba', 'Agaba@432');

        //dd($data);
        $pages = 0;
   
    }
 
    public function approve()
    {

        $data['title'] = 'Approve Staff KPI Data';
        $data['page'] = 'approve_performance';
        $filters['supervisor_id'] = $this->input->get('supervisor_id');
        $filters['ihris_pid'] = $this->input->get('ihris_pid');
        $filters['financial_year'] = $this->input->get('financial_year');
        $filters['period'] = $this->input->get('period');
        $facility = $this->input->get('facility_id');
        $data['module'] = "person";
        $data['reports'] = $this->person_mdl->get_person_data($filters,$facility);

       
      

       //dd($data['reports']);
        echo Modules::run('template/layout', $data);



    }

    // public function approve()
    // {

    //     $draw = $this->input->get('draw');
    //     $start = $this->input->get('start') ?? 0;
    //     $length = $this->input->get('length') ?? 20;

    //     // $filters['supervisor_id'] = $this->input->get('supervisor_id');
    //     // $filters['ihris_pid'] = $this->input->get('ihris_pid');
    //     $filters['financial_year'] = $this->input->get('financial_year');
    //     $filters['period'] = $this->input->get('period');
    //     $filters['approved2'] = $this->input->get('approved2');
    //     $filters['approved'] = $this->input->get('approved');
    //     $filters['ihrisdata.ihris_pid'] = $this->input->get('ihris_pid');
    //     $facility = $this->session->userdata('facility_id');
        
    //     $data['reports'] = $this->person_mdl->get_person_data($start, $length, 1,$facility,$filters);
      
    //     $totalRecords = count($this->person_mdl->get_person_data($start, $length, 0,$facility,$filters));
    //     $data['title'] = 'Approve Staff KPI Data';
    //     $data['page'] = 'approve_performance';
    //     $data['module'] = "person";
    //   // dd($this->db->last_query());

       

    //     $output = array(
    //         "draw" => $draw,
    //         "recordsTotal" => $totalRecords,
    //         "recordsFiltered" => $totalRecords,
    //         "data" => $data['reports']
    //     );
      
    //      if ($this->input->get('ajax')==1){
    //     $this->output->set_content_type('application/json')->set_output(json_encode($output));
    // }
    // else{
    //     echo Modules::run('template/layout', $data);
    // }
    // }

    public function mydata($person)
    {

        $data['title'] = 'Staff KPI Data';
        $data['page'] = 'mydata';
        $filters['supervisor_id'] = $this->input->get('supervisor_id');
        $filters['ihris_pid'] = $this->input->get('ihris_pid');
        $filters['financial_year'] = $this->input->get('financial_year');
        $filters['period'] = $this->input->get('period');
        $data['reports'] = $this->person_mdl->mydata_data($filters);
        $data['module'] = "person";

        // dd($data['reports']);
        echo Modules::run('template/layout', $data);



    }
    public function focus_areas($job_id){
     return  $this->person_mdl->get_person_focus_area($job_id);
    }
    public function data()
    {

        $data['title'] = 'KPI Data';
        $data['page'] = 'performance_data';
        $id = $this->session->userdata('ihris_pid');
        $data['kpidatas'] = $this->person_mdl->get_kpi_data($id);
        $data['module'] = "person";
        echo Modules::run('template/layout', $data);


    }
    public function performance()
    {

        $data['title'] = 'My Performance';
        $data['page'] = 'submit_performance';
        $data['module'] = "person";
        redirect('data/subject/1/Clinical_Care');
    }
    public function manage_people()
    {

        $data['title'] = 'Manage People';
        $data['page'] = 'manage_people';
        $data['module'] = "person";

        if(!empty($this->input->get('facility'))){ $facility = urldecode($this->input->get('facility')); } else {$facility = urldecode($_SESSION['facility_id']);}
        $ihris_pid = urldecode($this->input->get('ihris_pid'));
    
        $data['staff'] = $this->person_mdl->get_employees($facility, $ihris_pid,'','');
        //dd($facility);
     
        $route="person/manage_people";
        if (!empty($data['staff'])) {
            $value = count($data['staff']);
        }
        $totals = $value;
         $perPage = 10;
        $data['links'] =  ci_paginate($route, $totals, $perPage, $segment = 2);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['employees'] = $this->person_mdl->get_employees($facility, $ihris_pid, $perPage, $page);
        //dd($data['employees'] );

        $district = $_SESSION['district_id'];
        if(empty($district)){
            $district = get_field_by_facility($facility,'district');
           // dd($district);
           // dd($this->db->last_query());
        }
        
        if(!empty($_SESSION['district_id'])){
        $data['facilities'] = $this->db->query("SELECT distinct facility_id, facility from ihrisdata_staging WHERE district_id='$district'")->result();
        }
        else
        {
        $data['facilities'] = $this->db->query("SELECT distinct facility_id, facility from ihrisdata_staging")->result();   
        }
        $data['supervisors'] = $this->db->query("(SELECT id,ihris_pid,district_id,facility,surname,firstname,othername,job from ihrisdata WHERE district_id='$district' OR facility LIKE'Ministry%') UNION (SELECT id,ihris_pid,district_id,facility,surname,firstname,othername,job from ihrisdata_staging WHERE district_id='$district' OR facility LIKE'Ministry%' AND ihrisdata_staging.ihris_pid NOT IN (SELECT ihrisdata.ihris_pid FROM ihrisdata)) ORDER BY surname ASC")->result();
        $data['kpigroups'] = $this->db->query("SELECT job_id, job from kpi_job_category")->result();
        $data['jobs'] = $this->db->query("SELECT DISTINCT job_id, job from ihrisdata_staging")->result();
       // dd($data);

        echo Modules::run('template/layout', $data);
    }
    public function cache_fields(){

      

    }
    public function performance_list()
    {

        $data['title'] = 'Add Performnace';
        $data['page'] = 'analytics_staff';
        $data['module'] = "person";
        if (!empty($this->input->get('facility'))) {
            $facility = $this->input->get('facility');
        } else {
            $facility = $_SESSION['facility_id'];
        }
        $name = $this->input->get('name');
        $data['staff'] = $this->person_mdl->get_analytics_employees($facility, $name, '', '');
        $route = "person/performance_list";
        if(!empty($data['staff'])){$value =count($data['staff']);}
        $totals = $value;
        $data['links'] = ci_paginate($route, $totals, $perPage = 100, $segment = 2);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['employees'] = $this->person_mdl->get_analytics_employees($facility, $name, $perPage = 100, $page);
        // $data['employees'] = $this->person_mdl->get_employees($facility);
        $district = $_SESSION['district_id'];
        if (!empty($_SESSION['district_id'])) {
            $data['facilities'] = $this->db->query("SELECT distinct facility_id, facility from ihrisdata WHERE district_id='$district'")->result();
        } else {
            $data['facilities'] = $this->db->query("SELECT distinct facility_id, facility from ihrisdata")->result();
        }
        $data['supervisors'] = $this->db->query("(SELECT id,ihris_pid,district_id,facility,surname,firstname,othername,job from ihrisdata WHERE district_id='$district' OR facility LIKE'Ministry%') UNION (SELECT id,ihris_pid,district_id,facility,surname,firstname,othername,job from ihrisdata_staging WHERE district_id='$district' OR facility LIKE'Ministry%' AND ihrisdata_staging.ihris_pid NOT IN (SELECT ihrisdata.ihris_pid FROM ihrisdata)) ORDER BY surname ASC")->result();
        $data['kpigroups'] = $this->db->query("SELECT job_id, job from kpi_job_category")->result();
        $data['jobs'] = $this->db->query("SELECT DISTINCT job_id, job from ihrisdata_staging")->result();
        echo Modules::run('template/layout', $data);
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

    public function get_ihrisdata2()
    {
        $http = new HttpUtils();
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        $response = $http->sendiHRISRequest('apiv1/index.php/api/ihrisdata', "GET", $headers, []);

        if ($response) {
           

            foreach ($response as $data) {
                $query = $this->db->insert('ihrisdata_staging', $data);
               
            }

     
        }

        $process = 2;
        $method = "bioitimejobs/get_ihrisdata";
        $status = (count($response) > 0) ? "successful" : "failed";
    }


    public function get_ihrisdata()
    {
        $http = new HttpUtils();
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        $response = $http->sendiHRISRequest('apiv1/index.php/api/ihrisdata', "GET", $headers, []);
        $this->db->truncate('ihrisdata_staging');
        

        if ($response) {

           foreach($response as $data){
                          
            
                 $query = $this->db->replace('ihrisdata_staging',$data);
            }
            $this->db->query("DELETE FROM `ihrisdata_staging` WHERE institutiontype_name in ('UCBHCA','Uganda Peoples Defence Force (UPDF)','Uganda Police'
                            ,'Uganda Prison Services',
                            'UMMB'
                            'UOMB'
                            'UPMB') OR cadre in ('Support Staffs','Others','Allied Health Professionals');
                            ");
           
        }
        $process = 2;
        $method = "bioitimejobs/get_ihrisdata";
        if (count($response) > 0) {
            $status = "successful";
        } else {
            $status = "failed";
        }
    
    }
    //employees all enrolled users before creating new ones.

    public function all_users($ihris_pid,$change_password)
	{
      
		$staff =  $this->db->query("SELECT * from ihrisdata WHERE ihris_pid='$ihris_pid'")->row();

       try{
	
             if(empty($staff->email)){
               $email = str_replace('person|', '', $staff->ihris_pid) . '@pmd.health.go.ug';
             }
             else{
               $email = $staff->email;
             }
            $username = str_replace('person|','',$staff->ihris_pid);
            $users['ihris_pid'] = $staff->ihris_pid;
			$users['username'] = str_replace('person|','',$staff->ihris_pid);
			$users['email'] = $email;
			$users['firstname'] =  $staff->firstname;
            $users['lastname'] = $staff->surname;
			$users['status'] = 1;
			$users['is_admin'] = 0;
            $users['subject_area'] = '["1"]';
            $users['info_category'] = '';
         
			$users['password'] = $this->argonhash->make('nhwpmd@2024');
        
			$users['user_type'] = 'staff';
            $users['facility_id'] = $staff->facility_id;
            $users['district_id'] = $staff->district_id;
            $users['image'] = './assets/img/user/MOH.png';
            // print_r($users);
            // exit;

            $siteUrl = base_url();

            $message = "
                        <html>
            <head>
            <title>MoH Staff Performance</title>
            </head>
            <body>
            <p>Dear $staff->firstname,</p>
            <p>Welcome to our site! Your account has been created successfully.</p>
            <p>Your temporary password is: <a>nhwpmd@2024</a>  and your username is  <a>$staff->username</a> </p>
            <p>Please login to your account and change your password immediately after logging in.</p>
            <p>Visit our site: <a href='$siteUrl'>$siteUrl</a></p>
            <p>Thank you for joining us!</p>
            </body>
            </html>";

        //  dd($users);
         
			$new = $this->db->replace('user', $users);

            //send_email_async($email,'Your User Acct Details','Test');
		$accts = $this->db->affected_rows();
        } catch (Exception $accts) {
            // Handle the exception (display an error message, log the error, etc.)
            echo "Error: " . $accts->getMessage();
        }
        return $accts;
	
	}

    function message(){


    }

    // ... (other controller methods)

    public function save()
    {
        // Process and save the form data

        $kpiArray = $this->input->post();

       //dd($kpiArray);

        $rows = [];

        foreach ($kpiArray['numerator'] as $kpiId => $numerator) {
            if(($this->kpi_details($kpiId)->current_target)>0){ $target = $this->kpi_details($kpiId)->current_target; } else{
                $target = $kpiArray['data_target'][$kpiId][0];
            }
            $row = [
                'kpi_id' => $kpiId,
                'financial_year' => $kpiArray['financial_year'],
                'period' => $kpiArray['period'],
                'facility' => $kpiArray['facility_id'],
                'ihris_pid' => $kpiArray['ihris_pid'],
                'upload_date' => date('Y-m-d H:i:s'),
                'numerator' => $numerator[0],
                'data_target' => $target,
                'computation_category' => $this->kpi_details($kpiId)->computation_category,
                'job_id' => $kpiArray['job_id'],
                'denominator' => $kpiArray['denominator'][$kpiId][0],
                'uploaded_by'=>$this->session->userdata('id'),
                'comment' => $kpiArray['comment'][$kpiId][0],
                'supervisor_id' => $kpiArray['supervisor_id'],
                'supervisor_id_2' => $kpiArray['supervisor_id_2'],
                'entry_id' => $kpiId . $kpiArray['financial_year'] . $kpiArray['period'] . $kpiArray['ihris_pid'],
                'draft_status' => $kpiArray['draft_status']
                // Add other default values or data here
            ];

            // add validation 
            //provide target for final data
            
            



            $rows[] = $row;

          
        }
  //dd($rows);

        $this->db->trans_start();

        foreach ($rows as $row) {
            // Check if the entry_id exists in the database
            $existing_record = $this->db->get_where('new_data', ['entry_id' => $row['entry_id']])->row();

            if ($existing_record) {
                // If it exists, update the record
                $this->db->where('entry_id', $row['entry_id']);
                $this->db->update('new_data', $row);
            } else {
                // If it doesn't exist, insert a new record
                $this->db->insert('new_data', $row);
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
          echo  $msg = "Saving failed";
        
        } else {
            // Transaction succeeded
          echo  $msg = 'Records Saved';
       
        }


    }
   
    
   public function person_data($user_id){

	$this->db->where('ihris_pid', "$user_id");
	$person=$this->db->get('ihrisdata')->row();

    return $person;
   }
    public function kpi_details($kpi)
    {

        $this->db->where('kpi_id', "$kpi");
        $person = $this->db->get('kpi')->row();

        return $person;
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
      return $jobs;
    }

   public  function evaluation ($person)

    {
        $fperson = urldecode($person);
       return  $jobs = $this->db->query("REPLACE into ihrisdata (SELECT * from ihrisdata_staging where ihris_pid='$fperson')");
     
    }

    public function enroll_facility($facility)
    {
        $facility = urldecode($facility);
       // dd($facility);
        $jobs = $this->db->query("REPLACE into ihrisdata (SELECT * from ihrisdata_staging where facility_id='$facility')");
        if ($jobs) {
            $this->session->set_flashdata('message', 'Employees added to analytics.');
        } else {
            $this->session->set_flashdata('message', 'Error Contact System Administrator.');
        }
        redirect('person/manage_people');
    }


    public function add_supervisor()
    {
        if($this->input->get('add_new')=='add_new'){
            $change_password = $this->input->get('changepassword');
            $data=$this->input->get();
            $job_id = $data['job_id'];
            $job = $this->db->query("SELECT DISTINCT job from ihrisdata_staging where job_id='$job_id'")->row()->job;
            $facility_id = $data['facility_id'];
            $facility_data = $this->db->query("SELECT DISTINCT facility,district_id, district from ihrisdata_staging where facility_id='$facility_id'")->row();
            $facility = $facility_data->facility;
            $district = $facility_data->district;
            $district_id = $facility_data->district_id;

            $ihris_pid = $data['ihris_pid'];
           $data= array(
                "surname"=> $data['surname'],
                "firstname"=>$data['firstname'],
                "supervisor_id"=>$data['supervisor_id'],
                "nin"=>$data['nin'],
                "supervisor_id_2"=>$data['supervisor_id_2'],
                "job_id" => $job_id,
                "job"=>$job,
                "district"=>$district,
                "district_id"=>$district_id,
                "facility" => $facility,
                "facility_id" => $facility_id,
                "email"=>$data['email'],
                "ihris_pid"=>$data['ihris_pid'],
                "mobile"=>$data['mobile'],
                "data_role"=>$data['data_role'],
                "kpi_group_id"=> $data['kpi_group_id'],
             
            );
            //dd($data);
            $query1 = $this->db->insert('ihrisdata',$data);
        }
        else{
        if ($this->input->get()) {

            $data = $this->input->get();

             //dd($data);
               unset($data['changepassword']);
            if (empty($data['supervisor_id'])) {

                unset($data['supervisor_id']);
            }
            if (empty($data['supervisor_id_2'])) {

                unset($data['supervisor_id_2']);
            }
           if (empty($data['facility_id'])) {

                    unset($data['facility_id']);
            }
            if (empty($data['job_id'])) {

                    unset($data['job_id']);
            }


                $ihris_pid = $this->input->get('ihris_pid');

            $change_password = $this->input->get('changepassword');

            //dd($data);

            $this->db->where("ihris_pid", "$ihris_pid");
            $query1 = $this->db->update("ihrisdata", $data);

            if ($query1) {

                $this->db->where("ihris_pid", "$ihris_pid");
                $this->db->update("ihrisdata_staging", $data);


            }
           }

            $this->evaluation($ihris_pid);
        }

        if ($query1) {
            $this->session->set_flashdata('message', 'Employee Details Updated');
        } else {
            $this->session->set_flashdata('message', 'Error Contact System Administrator.');
        }

            if ($change_password=='on'){
            $this->all_users($ihris_pid, $change_password);
            }

            //dd($change_password);
        
        redirect('person/manage_people');
    }


    function getFacStaff()
    {
        $id = urldecode($this->input->get('facility_id'));
        $rows = $this->db->query("SELECT ihris_pid, surname, firstname, othername, job, 'PMD' as source FROM ihrisdata WHERE facility_id='$id' UNION SELECT ihris_pid, surname, firstname, othername, job,'iHRIS' as source from ihrisdata_staging WHERE ihrisdata_staging.ihris_pid NOT IN (SELECT DISTINCT ihris_pid from ihrisdata WHERE facility_id='$id') AND facility_id='$id'")->result();

        $opt = ""; // Initialize $opt before the loop

        if (!empty($rows)) {
            foreach ($rows as $row) {
                if (urldecode($this->input->get('ihris_pid')) == $row->ihris_pid) {
                    $selected = "selected";
                } else {
                    $selected = ""; // Initialize $selected to an empty string if the condition is not met
                }

                $opt .= "<option value='" . $row->ihris_pid . "' $selected>" . ucwords($row->surname . ' ' . $row->firstname . ' ' . $row->othername . ' - (' . $row->job) . ') ' . $row->source; "</option>";
            }
        }

        echo $opt;
    }

    function getFacs()
    {
        $id = urldecode($this->input->get('district_id'));
        $fid = urldecode($this->input->get('facility_id'));
        $rows = $this->db->query("SELECT distinct facility_id, facility from ihrisdata_staging where district_id='$id'")->result();

        $opt = ""; // Initialize $opt before the loop

        if (!empty($rows)) {
            foreach ($rows as $row) {
                 if ($fid== $row->facility_id) {
                    $selected = "selected";
                 } else {
                 $selected = ""; // Initialize $selected to an empty string if the condition is not met
                 }

                $opt .= "<option value='" . $row->facility_id . "' $selected>" . ucwords($row->facility) . "</option>";
            }
        }

        echo $opt;
    }
    function getEnrollStaff()
    {
        $id = urldecode($this->input->get('facility_id'));
        $rows = $this->db->query("SELECT ihris_pid, surname, firstname, othername, job from ihrisdata where facility_id='$id'")->result();

        $opt = ""; // Initialize $opt before the loop

        if (!empty($rows)) {
            foreach ($rows as $row) {
                if (urldecode($this->input->get('ihris_pid')) == $row->ihris_pid) {
                    $selected = "selected";
                } else {
                    $selected = ""; // Initialize $selected to an empty string if the condition is not met
                }

                $opt .= "<option value='" . $row->ihris_pid . "' $selected>" . ucwords($row->surname . ' ' . $row->firstname . ' ' . $row->othername . ' - (' . $row->job) . ')' . "</option>";
            }
        }

        echo $opt;
    }

    public function update_data_status(){

        $ihris_pid=$this->input->get('ihris_pid');
       // dd($this->input->get());
        if(!empty($this->input->get('approved'))){
        $data['approved']=$this->input->get('approved');
        $data['approved_by'] = $this->session->userdata('id');
        $data['reject_reason'] = @$this->input->get('reject_reason');
        $data['approval1_date'] = date('Y-m-d  H:i:s');
        }
        else if (!empty($this->input->get('approved2'))){
         $data['approved2'] = $this->input->get('approved2');
         $data['approved2_by'] = $this->session->userdata('id');
         $data['reject_reason2'] = @$this->input->get('reject_reason2');
         $data['approval2_date	'] = date('Y-m-d  H:i:s');
         
        }
        $period = $this->input->get('period');
        $financial_year = $this->input->get('financial_year');
        $redirect = $this->input->get('redirect');
        $supervisor = $this->session->userdata('ihris_pid');

                 $this->db->where('period', "$period");
                 $this->db->where('financial_year', "$financial_year");
                 $this->db->where('ihris_pid', "$ihris_pid");
        $query = $this->db->update('new_data',$data);
        //dd($this->db->last_query());
       //confirm if it is supervisor 2 approving
        

        if ($query) {
            if(($data['approved']==1)||($data['approved2']==1)){
            $this->session->set_flashdata('message', 'Employee Report Approved');
            }
            else if (($data['approved'] == 2)|| ($data['approved2'] == 2)){
                $this->session->set_flashdata('message', 'Employee Report Rejected');   
            }
        } else {
            $this->session->set_flashdata('message', 'Error Contact System Administrator.');
        }
      
       redirect('person/approve');




    }
    public function ihrisconnect()
    {

        $data['title'] = 'iHRIS Connect';
        $data['page'] = 'ihrislink';
        $data['module'] = "person";
        if (!empty($this->input->get('facility'))) {
            $facility = $this->input->get('facility');
        } else {
            $facility = $_SESSION['facility_id'];
        }
        $name = $this->input->get('name');
        $route = "person/manage_people";
        $value=0;
        if (!empty($data['staff'])) {
            $value = count($data['staff']);
        }
        $totals = $value;
        $data['links'] = ci_paginate($route, $totals, $perPage = 100, $segment = 2);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        $data['employees'] = $this->person_mdl->get_analytics_employees($facility, $name, $perPage = 100, $page);
        // $data['employees'] = $this->person_mdl->get_employees($facility);
        $district = $_SESSION['district_id'];
        if (!empty($_SESSION['district_id'])) {
            $data['facilities'] = $this->db->query("SELECT distinct facility_id, facility from ihrisdata WHERE district_id='$district'")->result();
        } else {
            $data['facilities'] = $this->db->query("SELECT distinct facility_id, facility from ihrisdata")->result();
        }
      //  dd($data);
        echo Modules::run('template/layout', $data);
    }
    public function getkpis(){
        $job = urldecode($this->input->get('kpi_group'));
        $kpis = $this->db->query("SELECT * FROM kpi WHERE kpi.job_id='$job'")->result();
        $opt = ""; // Initialize $opt before the loop
        if (!empty ($kpis)) {
            foreach ($kpis as $row) {
        
                $opt .= "<option value='" . $row->kpi_id ."'>". ucwords($row->short_name ) . "</option>";
            }
        }

        echo $opt;

    }




    // new file
    
}
