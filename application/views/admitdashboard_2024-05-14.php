<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $this->load->view('google_analytics_script_common'); ?>
    <meta charset="utf-8">
    <title>Welcome to IIBF</title>
  </head>
  <?php //echo $this->db->last_query(); ?>
  
  <body style="background-color:#fff; margin:0 auto; padding:0; font-family:Arial, Helvetica, sans-serif; font-size:14px;">
    <table cellpadding="0" cellspacing="0" width="800" border="0" align="center">
      <tr>
        <td style="background-color:#fff;">
          <!--table-1-->
          <table cellpadding="0" cellspacing="0" width="100%" border="0" align="center">
            <tr>
              <td style="border:1px solid #1287c0; padding:5px;">
                <table cellpadding="0" cellspacing="0" width="100%" border="0" align="center">
                  <tr>
                    <td align="center"><img src="<?php echo base_url();?>assets/images/logo.jpg" width="400" height="66" /></td>
                  </tr>
                  <tr>
                    <td height="5"></td>
                  </tr>
                           <?php
                           
                           $exam_codes_covered=array();
                          // print_r($exam_name_new);//die;

                           if($tbl == 'old'){?>
                    <tr>
                      <td style="background-color:#1287c0; color:#fff; text-align:left; font-weight:bold; font-size:14px; padding:5px 5px 5px 5px;">
                        Welcome <?php echo $name;?>
                        <div style="float:right;">
                          <?php echo date('d-M-Y');?> &nbsp;
                          <a href="<?php echo base_url()?>admitcard/logout">Logout</a>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td height="5"></td>
                    </tr>
                    <!--form old table-->
                    <tr>
                      <td style="background-color:#7fd1ea; color:#fff; text-align:center; font-weight:bold; font-size:14px; padding:7px 0;">
                        <?php 
                          foreach($exam_name as $exam_name)
                          {
                            $dates=$this->master_model->getRecords('admitcard_info',array('exm_cd'=>$exam_name->exm_cd,"mem_mem_no"=>$mid),'date');
                            foreach($dates as $dates)
                            {
                              $exdate = $dates['date'];
                              $examdate = explode("-",$exdate);
                              $examdatearr[] = $examdate[1];
                            }
                            $exdate = $dates['date'];
                            $examdate = explode("-",$exdate);
                            $printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];
                            
                            $exam_in_array = array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeCaiib'),62,63,64,65,66,67,$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'),72,20,34,58,74,78,79,135,148,149,153,158,160,161,162,163,164,166,175,177,8,11,18,19,24,25,26,59,81,151,156,165,200,590,5800,3400,177,1600,810);
                            // if($exam_name->exm_cd == 21 || $exam_name->exm_cd == 42){
                            if (in_array($exam_name->exm_cd, $exam_in_array))
                            {
                              $h = base_url()."admitcard/getadmitcardjd/".base64_encode($exam_name->exm_cd);
                            }
                            else
                            {
                              $h = base_url()."admitcard/getadmitcardsp/".base64_encode($exam_name->exm_cd);
                            }
                                       $exam_codes_covered[]=$exam_name->exm_cd;
                          ?>
                          <a href="<?php echo $h;?>">
                            <?php
                              $caiib = array($this->config->item('examCodeCaiib'),"61","62",$this->config->item('examCodeCaiibElective63'),"64","65","66","67",$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'),"72");
                              $dipcert_arr = array(8,11,18,19,24,25,26,78,79,149,151,153,156,158,162,163,165,166);
                              if(!in_array($exam_name->exm_cd,$caiib) && !in_array($exam_name->exm_cd,$dipcert_arr))
                              {
                                if($exam_name->exm_cd == 101 || $exam_name->exm_cd == 996)
                                {
                                  echo "Admit Letter for Online CERTIFICATE EXAMINATION FOR BUSINESS CORRESPONDENTS/FACILITATORS - March 2024";
                                }
                                else if($exam_name->exm_cd == 1037 || $exam_name->exm_cd == 1038)
                                {
                                  echo "Admit Letter for Online CERTIFICATE EXAMINATION FOR BUSINESS CORRESPONDENTS/FACILITATORS - May 2024";
                                }
                                else
                                { ?>
                                Admit Letter for 
                                <?php 
                                  echo $exam_name->mode." " ;
                                  //echo "Online";
                                  if($exam_name->exm_cd == '20')
                                  { 
                                    //echo "CERTIFIED CREDIT OFFICER";
                                    echo "CERTIFIED CREDIT PROFESSIONA";
                                  }
                                  else
                                  {
                                    //echo preg_replace("/\([^)]+\)/","",$exam_name->description)." "; 
                                    echo $exam_name->description." "; 
                                  }
                                ?>
                                Examination - Jan-2023 <?php //echo $printdate;?>
                              <?php  } }?>
                              <?php
                                /* $elective = array("62","63","64","65","66","67","68","69","70","71","72");
                                  if($exam_name->exm_cd == 60){
                                  echo "Admit letter for Online CAIIB Examination –June/July 2022 ";
                                  }
                                  if(in_array($exam_name->exm_cd,$elective)){
                                  echo "Admit letter for Online CAIIB Electives – July-2022";
                                }*/
                              ?>
                              <!-- <br> -->
                          </a>
                        <?php }?>
                      </td>
                    </tr>
                  <?php }?>
                  <!--form new table-->
                  <?php if($tbl_new == 'new')
                  { ?>
                    <tr>
                      <td style="background-color:#7fd1ea; color:#fff; text-align:center; font-weight:bold; font-size:14px; padding:7px 0;">
                        <?php 
                          foreach($exam_name_new as $exam_name_new){
                            if($exam_name_new->exm_cd == $this->config->item('examCodeJaiib') || $exam_name_new->exm_cd == $this->config->item('examCodeDBF') || $exam_name_new->exm_cd == $this->config->item('examCodeSOB')){
                              $dates_new=$this->master_model->getRecords('admit_card_details',array('exm_cd'=>$exam_name_new->exm_cd,"mem_mem_no"=>$mid,'exam_date >= '=> '2022-03-01' ),'exam_date');	
                              }else{
                              //Added by Priyanka W for RPE old Admit card display functionality
                              $examCdArr = array('1002','1003','1004','1005','1006','1007','1008','1009','1010','1011','1012','1013','1014','1017','1019','1020','2027');
                              if(in_array($exam_name_new->exm_cd, $examCdArr)){
                                $dates_new=$this->master_model->getRecords('admit_card_details',array('exm_cd'=>$exam_name_new->exm_cd,"mem_mem_no"=>$mid,'exam_date'=>$exam_name_new->exam_date,'exm_prd'=>$exam_name_new->exm_prd));
                              }
                              else{
                                $dates_new=$this->master_model->getRecords('admit_card_details',array('exm_cd'=>$exam_name_new->exm_cd,"mem_mem_no"=>$mid,'exam_date >= '=> date("Y-m-d") ),'exam_date');
                              }
                              //echo $this->db->last_query();
                              //End
                            }
                            //echo '<pre>';
                            //print_r($dates_new);
                            foreach($dates_new as $dates_new){
                              
                              $exdate = date('d-M-y',strtotime($dates_new['exam_date']));
                              $examdate = explode("-",$exdate);
                              $examdatearr_new[] = $examdate[1];
                            }
                            
                            $exdate = date('d-M-y',strtotime($dates_new['exam_date']));
                            $examdate = explode("-",$exdate);
                            
                            if(in_array($exam_name_new->exm_cd, $examCdArr)){
                              $printdate_new = $examdate[1]." 20".$examdate[2];
                            }
                            else{
                              $printdate_new = implode("/",array_unique($examdatearr_new))." 20".$examdate[2];
                            }
                            
                            $h = base_url()."admitcard/getadmitcardsp_new/".base64_encode($exam_name_new->exm_cd)."/".$exam_name_new->mem_exam_id;
                            
                          ?>
                          <a href="<?php echo $h;?>">
                            <?php
                              $caiib = array($this->config->item('examCodeCaiib'),"61","62",$this->config->item('examCodeCaiibElective63'),"64","65","66","67",$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'),"72");
                              
                              if(!in_array($exam_name_new->exm_cd,$caiib)){
                                if($exam_name_new->exm_cd == 101 || $exam_name_new->exm_cd == 1038 || $exam_name_new->exm_cd == 1039){
                                  echo "Admit Letter for Online CERTIFICATE EXAMINATION FOR BUSINESS CORRESPONDENTS/FACILITATORS - MAY 2019";
                                  }else{
                                ?>
                                Admit Letter for
                                <?php 
                                  //echo $exam_name_new->mode; 
                                  echo "Online ";
                                  if($exam_name_new->exm_cd == '20'){ 
                                    echo preg_replace("/\([^)]+\)/","",$exam_name_new->description); 
                                    }else{
                                    echo $exam_name_new->description; 
                                  }
                                ?> 
                                Examination - <?php echo $printdate_new; ?>
                              <?php } }?>
                              <?php
                                $elective = array("62",$this->config->item('examCodeCaiibElective63'),"64","65","66","67",$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'),"72");
                                if($exam_name_new->exm_cd == $this->config->item('examCodeCaiib')){
                                  echo "Admit letter for Online CAIIB Examination – July-2024";
                                }
                                if(in_array($exam_name_new->exm_cd,$elective)){
                                  echo "Admit letter for Online CAIIB Electives – July-2024";
                                }
                              ?>
                              <br>
                          </a>
                        <?php }?>
                      </td>
                    </tr>
                  <?php }?> 
                </table>
                <!--table-2-->
              </td>
            </tr>
          </table>
          <!--table-1-->
        </td>
      </tr>
    </table>
    <!--main-table-->
  </body>
</html>
