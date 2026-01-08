<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dailycount extends CI_Controller 
{
	public function __construct()
  {
  	parent::__construct();

    $this->load->model('master_model');
  }

  public function index()
  {
	  
  	$date1 = date('d');
    $date2 = date('Y-m-');

    for($i=1; $i<=$date1; $i++)
    {
      echo '<a href="'.base_url().'Dailycount/daily_count/'.$date2.$i.'">'.$date2.$i.'</a>';
      echo '<br>';
    }
	  echo '<a href="'.base_url().'Dailycount/old">Old</a>';
	  echo '&nbsp;&nbsp;&nbsp;&nbsp;';
	  echo '<a href="'.base_url().'Dailycount/daily_count/all">ALL</a>';
  }
  
  
  	public function old(){
		$all_old_first_date = new DateTime("last day of last month");
		$old_first_date = $all_old_first_date->format('Y-m-d');
		$datearr = explode('-',$old_first_date);
		
		for($i=1; $i<=$datearr[2]; $i++){
		  $date = $datearr[0]."-".$datearr[1]."-".$i;
		  echo '<a href="'.base_url().'Dailycount/daily_count/'.$date.'">'.$date.'</a>';
		  echo '<br>';
		}
	}

  public function daily_count()
  {
    $last_day_this_month  = date('Y-m-t');

    //$newArray = [];
    $newArray1 = array();
    $array     =$pg_post_array= array();
    $id        = $this->uri->segment(3);
    $date      = date('Y-m-d',strtotime($id));

    if($id == 'all')
    {
      $pg_array = array();
  	  $first_day_this_month = date('Y-m-1'); 
      $last_day_this_month  = date('Y-m-t');

      $this->db->select('app_type,count(app_type) as cnt');
      $this->db->where('date(created_on)>=', $first_day_this_month);
     // $this->db->select_sum('cnt');
      $this->db->group_by('app_type'); 
      $this->db->where("date(created_on)<=", $last_day_this_month); 
      
      $data['result'] = $this->master_model->getRecords("exam_invoice");
      //print_r($data);exit;

      $newArray1 = array();

      $this->db->select('module_name,edited_count');
      $this->db->select_sum('edited_count');
      $this->db->group_by('module_name'); 
      $this->db->where("date(created_on) BETWEEN '$first_day_this_month' AND '$last_day_this_month'"); 
      $count['rest'] = $this->master_model->getRecords('pg_count'); 
      
      foreach ($count['rest'] as $key =>$value) 
      {
        $newArray1[$value['module_name']] = (isset($value['edited_count']) ? $value['edited_count'] :0);
      } 
    }

    else
    {
      $array1 = array();
      $date = date('Y-m-d',strtotime($id));

      $this->db->select('app_type,count(app_type) as cnt');
      $this->db->where('date(created_on)', $date);
      $this->db->group_by('app_type'); 
      $data['result'] = $this->master_model->getRecords("exam_invoice");  

      $newArray1 = array();

      $this->db->select('module_name,edited_count');
      $this->db->where('date(date)', $date);
      $count['rest'] = $this->master_model->getRecords('pg_count'); 
      
      foreach ($count['rest'] as $key =>$value) 
      {
        $newArray1[$value['module_name']] = (isset($value['edited_count']) ? $value['edited_count'] :0);
      }    
    }
    //print_r($newArray1);
    $data['newArray1'] = $newArray1;

    if(isset($_POST['submit']))
    { 
		  if($last_day_this_month != '')
		  {
        $pg_array = $this->input->post('pg_array');
          
        if(isset($pg_array[1]))
      	{
      	 	$pg_post_array['B']=$pg_array[1];
      	}
        else
      	{
      	 	$pg_post_array['B']=0;
      	}

        if(isset($pg_array[2]))
        {
          $pg_post_array['C']=$pg_array[2];
        }
        else
        {
          $pg_post_array['C']=0;
        }

        if(isset($pg_array[3]))
        {
          $pg_post_array['D']=$pg_array[3];
        }
        else
        {
          $pg_post_array['D']=0;
        }

        if(isset($pg_array[4]))
        {
          $pg_post_array['E']=$pg_array[4];
        }
        else
        {
          $pg_post_array['E']=0;
        }

        if(isset($pg_array[5]))
        {
          $pg_post_array['F']=$pg_array[5];
        }
        else
        {
          $pg_post_array['F']=0;
        }

        if(isset($pg_array[6]))
        {
          $pg_post_array['H']=$pg_array[6];
        }
        else
        {
          $pg_post_array['H']=0;
        }

        if(isset($pg_array[7]))
        {
          $pg_post_array['L']=$pg_array[7];
        }
        else
        {
          $pg_post_array['L']=0;
        }

        if(isset($pg_array[8]))
        {
          $pg_post_array['M']=$pg_array[8];
        }
        else
        {
          $pg_post_array['M']=0;
        }

        if(isset($pg_array[9]))
        {
          $pg_post_array['N']=$pg_array[9];
        }
        else
        {
          $pg_post_array['N']=0;
        }

        if(isset($pg_array[10]))
        {
          $pg_post_array['O']=$pg_array[10];
        }
        else
        {
          $pg_post_array['O']=0;
        }

        if(isset($pg_array[11]))
        {
          $pg_post_array['P']=$pg_array[11];
        }
        else
        {
          $pg_post_array['P']=0;
        }

        if(isset($pg_array[12]))
        {
          $pg_post_array['R']=$pg_array[12];
        }
        else
        {
          $pg_post_array['R']=0;
        }

        if(isset($pg_array[13]))
        {
          $pg_post_array['T']=$pg_array[13];
        }
        else
        {
          $pg_post_array['T']=0;
        }

        if(isset($pg_array[14]))
        {
          $pg_post_array['V']=$pg_array[14];
        }
        else
        {
          $pg_post_array['V']=0;
        }

        if(isset($pg_array[15]))
        {
          $pg_post_array['W']=$pg_array[15];
        }
        else
        {
          $pg_post_array['W']=0;
        }

		    if($last_day_this_month != '')  
			  {
          $this->db->where('date',$last_day_this_month);
    			$count = $this->master_model->getRecords('pg_count'); 

          if($count > 0 )
          {
				    $this->master_model->deleteRecord('pg_count','date',$date);
          }
          /*else
          {
            foreach($pg_post_array as $k=>$v)
            {
              $update_data = array(
                                    'module_name'  => $k,
                                    'edited_count' => $v,
                                    'date'         =>$date
                                  );
              $this->db->where('date',$date);
              $this->db->update('pg_count',$update_data);
            }
            $this->session->set_flashdata('success_message','Added Record');
            redirect(base_url().'Dailycount/daily_count/'.$id);
          }*/
        }
      
        foreach($pg_post_array as $k=>$v)
        {
      	  $insert_data = array(
                                'module_name'  => $k,
                                'edited_count' => $v,
                                'date'         =>$date
                              );
          $this->db->insert('pg_count', $insert_data);
  	    }
        $this->session->set_flashdata('success_message','Added Record');
        redirect(base_url().'Dailycount/daily_count/'.$id);
      }
    }
    
    $this->load->view('pg_count_list',$data);
  }
}

