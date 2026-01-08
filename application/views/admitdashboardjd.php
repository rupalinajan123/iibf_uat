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
							
							$dayDiff = abs($pdate - $today);
							$numberDays = $dayDiff/86400;
							$numberDays = intval($numberDays);
							
							$dates=$this->master_model->getRecords('admitcard_info',array('exm_cd'=>$exam_name->exm_cd,"mem_mem_no"=>$mid ),'date');
							
							foreach($dates as $dates){
								$exdate = $dates['date'];
								$examdate = explode("-",$exdate);
								$examdatearr[] = $examdate[1];
							}
								
							$exdate = $dates['date'];
							$examdate = explode("-",$exdate);
							//$printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];
							$printdate = date('M Y',strtotime($exdate));
				  ?>
                  		
                        <a href="<?php echo base_url()?>admitcard/getadmitcardjd/<?php echo base64_encode($exam_name->exm_cd)?>">
                        <!-- Admit Letter for -->
                          <?php 
                            //echo $exam_name->mode." ".$exam_name->description." Examination ".$printdate; 
                            //echo $exam_name->mode." ".$exam_name->description." Examination March-2022"; 
                            /* Exam subject Changes made by pratibha borse on 30 March22 */
                            $dipcert=array(166,78,153,11,151,154,18,162,79,19,119,157,158,149,8,165,163,26,25,24,156);
                    
                            if($exam_name->description == 'CAIIB') {
                              echo "Admit Letters for CAIIB/CAIIB Elective Nov Dec, 2025 Examination";
                              // echo "Re-examination-Admit letters for CAIIB/CAIIB Elective April,2022- Covid candidates"; 
                            } else if($exam_name->description == 'JAIIB') {
                              // echo "Re-examination-Admit letters for JAIIB/DBF April,2022- Covid/Election Duty candidates"; 
                              echo "Admit letter for JAIIB/DBF Nov Dec, 2025 examination"; ;
                              // Admit Letters for JAIIB/DBF June 2022 Re-Examination on 23rd July,2022
                            } else if($exam_name->description == 'DIPLOMA IN BANKING &amp; FINANCE(DB&amp;F)') {
                              
                              echo "Admit letter for JAIIB/DBF Nov Dec, 2025 examination"; ;
                            } else { 
                              echo "Admit Letter for ".$exam_name->mode." ".$exam_name->description." Examination January, 2026"; 
                            } 

                          ?> 
                        <br>
                        </a>
                  <?php } ?>
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