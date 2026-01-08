<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('google_analytics_script_common'); ?>
	<meta charset="utf-8">
	<title>Welcome to IIBF</title>
    
</head>
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
                <!--form new table-->
                <?php if($tbl_new == 'new'){?>
                <tr>
                <td style="background-color:#7fd1ea; color:#fff; text-align:center; font-weight:bold; font-size:14px; padding:7px 0;">
                  <?php 
				  		
						
				  		foreach($exam_name_new as $exam_name_new){
							 $dates_new=$this->master_model->getRecords('admit_card_details',array('exm_cd'=>$exam_name_new->exm_cd,"mem_mem_no"=>$mid ),'exam_date');
							 
							foreach($dates_new as $dates_new){
								
								$exdate = date('d-M-y',strtotime($dates_new['exam_date']));
								$examdate = explode("-",$exdate);
								$examdatearr_new[] = $examdate[1];
							}
							
							$exdate = date('d-M-y',strtotime($dates_new['exam_date']));
							$examdate = explode("-",$exdate);
							$printdate_new = implode("/",array_unique($examdatearr_new))." 20".$examdate[2];
							
                        	$h = base_url()."admitcard/getadmitcardsp_new/".base64_encode($exam_name_new->exm_cd);
                        
						 ?>
                        <a href="<?php echo $h;?>">
                        <?php
                        	$caiib = array($this->config->item('examCodeCaiib'),"61","62",$this->config->item('examCodeCaiibElective63'),"64","65","66","67",$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'),"72");
							if(!in_array($exam_name_new->exm_cd,$caiib)){
								
							if($exam_name_new->exm_cd == 101){
								echo "Admit Letter for Online CERTIFICATE EXAMINATION FOR BUSINESS CORRESPONDENTS/FACILITATORS - Nov 2017";
							}else{
						?>
                        
                        Admit Letter for
                        						
						<?php echo $exam_name_new->mode?>  
						<?php if($exam_name_new->exm_cd == '20' )
						{ 
						echo preg_replace("/\([^)]+\)/","",$exam_name_new->description); 
						}
						else
						{
							//echo preg_replace("/\([^)]+\)/","",$exam_name_new->description); 
							echo $exam_name_new->description;
						}
						?> 
                        Examination - <?php echo $printdate_new;?>
                        
                        <?php } }?>
                        <?php
							$elective = array("62",$this->config->item('examCodeCaiibElective63'),"64","65","66","67",$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'),"72");
                        	if($exam_name_new->exm_cd == $this->config->item('examCodeCaiib')){
								echo "Admit letter for Online CAIIB Examination – Dec 2017";
							}
							if(in_array($exam_name_new->exm_cd,$elective)){
								echo "Admit letter for Online CAIIB Electives – Dec 2017";
							}
						?>
                        <br>
                        </a>
                  <?php       }?>
                  </td>
                </tr>
               <?php }?> 
              </table><!--table-2-->
            </td>
          </tr>
        </table><!--table-1-->
      </td>
    </tr>
  </table><!--main-table-->
</body>
</html>