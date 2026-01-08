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
                <tr>
                  <td style="background-color:#1287c0; color:#fff; text-align:left; font-weight:bold; font-size:14px; padding:5px 5px 5px 5px;">Welcome <?php echo $name;?>
                  <div style="float:right;">
                  	<?php echo date('d-M-Y');?> &nbsp;
                  	<a href="<?php echo base_url()?>admitcard/logout">Logout</a>
                  </div>
                  </td>
                </tr>
                <tr>
                  <td height="5"></td>
                </tr>
                <tr>
                  <td style="background-color:#7fd1ea; color:#fff; text-align:center; font-weight:bold; font-size:14px; padding:7px 0;">
                  <?php
                  	foreach($exam_name as $exam_name){
						
						$pdate = strtotime($exam_name->exam_date);
						$today = strtotime(date("d-M-y"));
						if($pdate >= $today){
						
						$isvalid=$this->master_model->checkadmitcarddate($exam_name->exm_cd);
							if($isvalid)
							{
								if($this->session->userdata('nmregnumber')!=''){
									$dates=$this->master_model->getRecords('admitcard_info',array('exm_cd'=>$exam_name->exm_cd,"mem_mem_no"=>$this->session->userdata('nmregnumber')),'date');
								}
								if($this->session->userdata('dbregnumber')!=''){
									$dates=$this->master_model->getRecords('admitcard_info',array('exm_cd'=>$exam_name->exm_cd,"mem_mem_no"=>$this->session->userdata('dbregnumber')),'date');
								}
								
								
								foreach($dates as $dates){
									$exdate = $dates['date'];
									$examdate = explode("-",$exdate);
									$examdatearr[] = $examdate[1];
								}
								
								$exdate = $dates['date'];
								$examdate = explode("-",$exdate);
								$printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];
				  ?>
                        <a href="<?php echo base_url()?>admitcard/getadmitcardnomem/<?php echo base64_encode($exam_name->exm_cd)?>">
                        <?php
                        	$caiib = array($this->config->item('examCodeCaiib'),"61","62",$this->config->item('examCodeCaiibElective63'),"64","65","66","67",$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'),"72");
							if(!in_array($exam_name->exm_cd,$caiib)){
						?>
                        Admit Letter for 
						<?php echo $exam_name->mode?>  
						<?php echo $exam_name->description?> 
                        Examination - <?php echo $printdate;?>
                        <?php }?>
                        <?php
							$elective = array("62",$this->config->item('examCodeCaiibElective63'),"64","65","66","67",$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'),"72");
                        	if($exam_name->exm_cd == $this->config->item('examCodeCaiib')){
								echo "Admit letter for Online CAIIB Examination – Jun 2021";
							}
							if(in_array($exam_name->exm_cd,$elective)){
								echo "Admit letter for Online CAIIB Electives – Jun 2021";
							}
						?>
                        <br></a>
                  <?php } } }?>
                  </td>
                </tr>
              </table><!--table-2-->
            </td>
          </tr>
        </table><!--table-1-->
      </td>
    </tr>
  </table><!--main-table-->
</body>
</html>