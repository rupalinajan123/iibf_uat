<?php
/* 
* Created by Pooja mane : 20-06-2024
* Description: Controller for Learning Purpose
*/
defined('BASEPATH') or exit('No direct script access allowed');
class Scribe extends CI_Controller
{
   public function __construct()
   {
      parent::__construct();

   }

   public function captcha()
   {
      /*GET ALL EXAMS*/
      // $this->db->select('e.description,e.exam_code');
      // $data['exams'] = $exams = $this->master_model->getRecords('exam_master e');

      /*GET ALL SUBJECTS*/
      // $this->db->select('s.subject_code,s.exam_code,s.subject_description');
      // $data['subjects'] = $subjects = $this->master_model->getRecords('subject_master s');

      /********** START : ACTIVE EXAM DATA TO DISPLAY IN DROPDOWN *****************/
      // $select_exam = "a.id, a.exam_code, a.exam_period, a.exam_from_date, a.exam_from_time, a.exam_to_date, a.exam_to_time, a.exam_activation_delete, em.description";
      // $whr_exam['a.exam_activation_delete'] = '0';
      // $whr_exam['em.exam_delete']           = '0';

      // $this->db->order_by('a.exam_code', 'ASC');
      // $this->db->join("exam_master em", "em.exam_code = a.exam_code", "LEFT");
      // $this->db->join("member_exam me", "me.exam_code = a.exam_code", "LEFT");
      // $this->db->group_by('description');
      // $data['active_exam_data'] = $active_exam_data = $this->master_model->getRecords('exam_activation_master a', $whr_exam, $select_exam, array(), '', '');

      // echo $this->db->last_query();die;
      $this->load->model('Captcha_model');
      $data['captcha_img'] = $this->Captcha_model->generate_captcha_img('LOGIN_SCRIBE_FORM');
      // echo'<pre>';print_r($data);die;

      $this->load->view('scribe',$data);
   }

}
?>