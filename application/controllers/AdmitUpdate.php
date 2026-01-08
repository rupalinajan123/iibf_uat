<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class AdmitUpdate extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('upload');
        $this->load->helper('upload_helper');
        $this->load->helper('master_helper');
        $this->load->helper('general_helper');
        $this->load->model('Master_model');
    }
    public function index()
    {
        $regnumber = array(
            '510382758','510383931','510384340','510384385','510384416','510384685','510384685','510386276','510386444','510387059','510387059','510387296','510387629','510387715','510387837','510387921','510388274','510388274','510388566','510388741','510388741','5151993','5925905','6249261','6249261','7071631','7077124','7077124','7227473','7290600','7310293','7500135','7506353','7557307','801186581','801186582','801186583','801206578','801218031','801218034','801218034','801218047','801218054','801218056');
        $select    = 'venueid,exam_date,time,mem_mem_no,admitcard_id';
        $this->db->where_in('mem_mem_no', $regnumber);
        $dataArr = $this->Master_model->getRecords('admit_card_details', array(
            'remark' => 1,
            'exm_prd' => 738
        ), $select);
        
		echo "<br> 1 SQL => " . $this->db->last_query();
        
		if (count($dataArr)) 
		{
            foreach ($dataArr as $data) 
			{
				$admitcard_id    = $data['admitcard_id'];
                $venueid    = $data['venueid'];
                $exam_date  = $data['exam_date'];
                $time       = $data['time'];
                $mem_mem_no = $data['mem_mem_no'];
                $select     = '*';
                $venueArr   = $this->Master_model->getRecords('venue_master', array(
                    'venue_code' => $venueid,
                    'exam_date' => $exam_date,
                    'session_time' => $time
                ), $select);
                
                echo "<br>-------------------------------<br>";
				
				echo "<br>Venue Get SQL => " . $this->db->last_query();
                
				if (count($venueArr)) 
				{
                    foreach ($venueArr as $venue) 
					{
                        echo "<br>: ".$venue_name    = $venue['venue_name'];
                        echo "<br>: ".$venue_addr1   = $venue['venue_addr1'];
                        echo "<br>: ".$venue_addr2   = $venue['venue_addr2'];
                        echo "<br>: ".$venue_addr3   = $venue['venue_addr3'];
                        echo "<br>: ".$venue_addr4   = $venue['venue_addr4'];
                        echo "<br>: ".$venue_addr5   = $venue['venue_addr5'];
                        echo "<br>: ".$venue_pincode = $venue['venue_pincode'];
                       
                        //exit;
						$update    
						= array(
							'venue_name'=> $venue_name,
                            'venueadd1' => $venue_addr1,
                            'venueadd2' => $venue_addr2,
                            'venueadd3' => $venue_addr3,
                            'venueadd4' => $venue_addr4,
                            'venueadd5' => $venue_addr5,
                            'venpin' => $venue_pincode
                        );
                        $this->master_model->updateRecord('admit_card_details', $update, array(
                            'admitcard_id' => $admitcard_id,
							'mem_mem_no' => $mem_mem_no,
                            'remark' => 1,
                            'exm_prd' => 738,
                            'venueid' => $venueid,
                            'exam_date' => $exam_date,
                            'time' => $time
                        ));
                        
                        echo "<br>Update SQL => " . $this->db->last_query();
						
						echo "<br>-------------------------------<br>";
                    }
                }
            }
        }
    }
} 