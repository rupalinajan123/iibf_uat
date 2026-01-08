<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Dra_count_csv extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Master_model');
        $this->load->library('session');

        error_reporting(E_ALL); // Report all errors
        ini_set("error_reporting", E_ALL); // Same as error_reporting(E_ALL);
    }
    public function index()
    {
        echo "string";die;
        $data = array();
        if (count($_POST) > 0) 
        {

            /*$exam_code = 57;
            $exam_period = '719';*/
            $exam_code   = $this->input->post('exam_code');
            $exam_period = $this->input->post('exam_period');
            /*$exam_type   = $this->input->post('exam_type');*/
            echo "string";die;
            $result=$this->master_model->getRecords('dra_center_master',array('exam_name'=>$exam_code,'exam_period'=>$exam_period),'center_code,center_name,exam_period');
            echo $this->db->last_query();
            echo "dOWN";
            print_r($result);die;
            if (count($result) > 0) 
            {

                $csv   = "Sr. No, Exam code, Center code, Center name, Exam period, Registration count\n";
                $sr_no = 1;
                foreach ($result as $record) 
                {
                    /*if ($exam_type == 0) 
                    {
                        $this->db->where('institute_id', 0);
                    } else 
                    {
                        $this->db->where('institute_id >', 0);
                    }*/

                    $reg = $this->master_model->getRecords('dra_member_exam',array('exam_code'=>$exam_code,'exam_period'=>$exam_period,'exam_center_code'=>$record['center_code'],"pay_status"=>1));
                     //, "examination_date"=>2018-05-12


                    $csv .= $sr_no . "," . $exam_code . "," . $record['center_code'] . "," . $record['center_name'] . "," . $exam_period . "," . sizeof($reg) . "\n";
                    $sr_no++;

                }

                $filename = "dra_count.csv";
                /* if ($exam_type == 0) 
                 {
                        $filename = "dra_count_individual_" . date("YmdHis") . ".csv";
                } else 
                {
                    $filename = "dra_count_bulk_" . date("YmdHis") . ".csv";
                
                }*/
                header('Content-type: application/csv');
                header('Content-Disposition: attachment; filename=' . $filename);
                $csv_handler = fopen('php://output', 'w');
                fwrite($csv_handler, $csv);
                fclose($csv_handler);
                exit;
            } 
            else 
            {
                $this->session->set_flashdata('error', 'No records found');
            }
        }

        $this->load->model('Captcha_model');
        $captcha_img   = $this->Captcha_model->generate_captcha_img('DRA_APP_COUNT');
        $data['image'] = $captcha_img;
        $this->load->view('dra_count_form', $data);

    }

    public function indexdrop()
    {
        //echo "string";die;
        $data = array();
        if (count($_POST) > 0) 
        {

            /*$exam_code = 57;
            $exam_period = '719';*/
            $exam_code   = $this->input->post('exam_code');
            $exam_period = $this->input->post('exam_period');
            $exam_type   = $this->input->post('exam_type');
            echo "string";die;
            $result=$this->master_model->getRecords('dra_center_master',array('exam_name'=>$exam_code,'exam_period'=>$exam_period),'center_code,center_name,exam_period');
            echo $this->db->last_query();
            echo "dOWN";
            print_r($result);die;
            if (count($result) > 0) 
            {

                $csv   = "Sr. No, Exam code, Center code, Center name, Exam period, Registration count\n";
                $sr_no = 1;
                foreach ($result as $record) 
                {
                    if ($exam_type == 0) 
                    {
                        $this->db->where('institute_id', 0);
                    } else 
                    {
                        $this->db->where('institute_id >', 0);
                    }

                    $reg = $this->master_model->getRecords('dra_member_exam',array('exam_code'=>$exam_code,'exam_period'=>$exam_period,'exam_center_code'=>$record['center_code'],"pay_status"=>1));
                     //, "examination_date"=>2018-05-12


                    $csv .= $sr_no . "," . $exam_code . "," . $record['center_code'] . "," . $record['center_name'] . "," . $exam_period . "," . sizeof($reg) . "\n";
                    $sr_no++;

                }

                $filename = "dra_count.csv";
                 if ($exam_type == 0) 
                 {
                        $filename = "dra_count_individual_" . date("YmdHis") . ".csv";
                } else 
                {
                    $filename = "dra_count_bulk_" . date("YmdHis") . ".csv";
                
                }
                header('Content-type: application/csv');
                header('Content-Disposition: attachment; filename=' . $filename);
                $csv_handler = fopen('php://output', 'w');
                fwrite($csv_handler, $csv);
                fclose($csv_handler);
                exit;
            } 
            else 
            {
                $this->session->set_flashdata('error', 'No records found');
            }
        }

        $this->load->model('Captcha_model');
        $captcha_img   = $this->Captcha_model->generate_captcha_img('DRA_APP_COUNT');
        $data['image'] = $captcha_img;
        $this->load->view('dra_count_form', $data);

    }
}
