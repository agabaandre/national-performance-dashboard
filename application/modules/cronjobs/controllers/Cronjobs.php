<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cronjobs extends MX_Controller {

	
	public function __Construct(){
        $this->load->model("Cronjobs_mdl","cjobs_ml");
        $this->db->query('SET SESSION sql_mode = ""');

       
	}
    public function fyears($kpi){

        $query=$this->db->query("SELECT distinct financial_year FROM new_data where kpi_id='$kpi' ")->result();

    return $query;
  }

  public function maxfyears($kpi){

    $query=$this->db->query("SELECT max(financial_year) as financial_year FROM new_data where kpi_id='$kpi' ")->result();

   return $query;
  }

   //GAUGE
   public function getKpi(){

         $kpis=$this->cjobs_ml->getkpiids();

	return $kpis;
   }

   public function gaugeData(){

        $kpis=$this->getKpi();
        
            foreach ($kpis as $kpi):
                $years = $this->maxfyears($kpi->kpi_id);
                foreach($years as $year):
                $data=$this->cjobs_ml->gaugeData($kpi->kpi_id,$year->financial_year);
                echo $data.'<br>';
                endforeach;
            endforeach;
        
       
	}

    public function previousgaugeData(){

        $kpis=$this->getKpi();
        foreach ($kpis as $kpi):
            $years = $this->maxfyears($kpi->kpi_id);
            foreach($years as $year):
                $fy=$year->financial_year;
	            $data=$this->cjobs_ml->previousgaugeData($kpi->kpi_id,$fy);
            echo $data.'<br>';
            endforeach;
        endforeach;
       
	}
 
    public function dimension0(){

         $kpis=$this->getKpi();
        foreach ($kpis as $kpi):
            $years = $this->fyears($kpi->kpi_id);
            foreach($years as $year):
                $fy=$year->financial_year;
	       $data=$this->cjobs_ml->dimension0Data($kpi->kpi_id,$fy);
            echo $data.'<br>';
            endforeach;
        endforeach;


    }

    public function dimension1(){

           $kpis=$this->getKpi();
        foreach ($kpis as $kpi):
            $years = $this->fyears($kpi->kpi_id);
            foreach($years as $year):
                $fy=$year->financial_year;
	       $data=$this->cjobs_ml->dimension1Data($kpi->kpi_id,$fy);
            echo $data.'<br>';
            endforeach;
        endforeach;
    }

    public function dimension2(){

        $kpis=$this->getKpi();
         foreach ($kpis as $kpi):
            $years = $this->fyears($kpi->kpi_id);
            foreach($years as $year):
                $fy=$year->financial_year;
	       $data=$this->cjobs_ml->dimension2Data($kpi->kpi_id,$fy);
            echo $data.'<br>';
            endforeach;
        endforeach;
    }

    public function test(){

	       $data=$this->cjobs_ml->preperiodlimits($kpi='KPI-19');
           print_r($data);
    }

    public function dimension3(){

        $kpis=$this->getKpi();
        foreach ($kpis as $kpi):
            $years = $this->fyears($kpi->kpi_id);
            foreach($years as $year):
                $fy=$year->financial_year;
	       $data=$this->cjobs_ml->dimension3Data($kpi->kpi_id,$fy);
            echo $data.'<br>';
            endforeach;
        endforeach;
    }

    public function truncate(){

       $this->db->query("TRUNCATE TABLE new_data");
       $this->db->query("TRUNCATE TABLE report_kpi_summary");
       $this->db->query("TRUNCATE TABLE report_kpi_trend");
       $this->db->query("TRUNCATE TABLE report_trend_dimension1");
       $this->db->query("TRUNCATE TABLE report_trend_dimension2");
       $this->db->query("TRUNCATE TABLE report_trend_dimension3");

       $return=$this->db->affected_rows(); 
       
      echo "Successful";

    }

    
	



}
