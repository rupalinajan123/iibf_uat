<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
   <head>
      <title>Welcome to Indian Institute of Banking &amp; Finance</title>
      <SCRIPT type="text/javascript">
         window.history.forward();
         function noBack() { window.history.forward(); }
      </SCRIPT>
      <style>
         .button {
         border: none;
         color: white;
         padding: 5px 10px;
         text-align: center;
         text-decoration: none;
         display: inline-block;
         font-size: 16px;
         margin: 4px 2px;
         cursor: pointer;
         border-radius: 4px;
         background-color: white; 
         color: black; 
         border: 3px solid #008CBA;
         }
      </style>
   </head>
   <h1>
      <!-- <CENTER>SITE IS UNDER MAINTAINANCE</CENTER> -->
   </h1>
   <body onselectstart="return false" ondragstart="return false" oncontextmenu="return false;" onload="noBack();" onpageshow="if (event.persisted) noBack();" onunload="">
      <?php if(!empty($result)) { //print_r($result[0]);DIE;
         $this->elearning_course_code =  [528,529,530,531,534];//e-learning array added by pooja mane-2-8-24
         ?>
      <table style="border:1px solid #1287C0;" width="850px" align="center" border="0" cellpadding="5" cellspacing="0">
         <tr>
            <td>
               <center>
                  <form method="post"> 
                     <input type="submit" class="button" name="submit_excel" value="Export To Excel">
                  </form>
               </center>
            </td>
         </tr>
         <tr height='70'>
            <td align='center'>
               <table width="90%" border="1" align="center" cellpadding="5" cellspacing="" style="border-collapse:collapse">
                  <tr>
                     <td width="10%" align="center">Sr. No.</td>
                     <td width="25%" align="center">Exam Code</td>
                     <td width="25%" align="center">Exam Period</td>
                     <td width="25%" align="center">Registration Number</td>
                     <td width="20%" align="center">Member name</td>
                     <td width="25%" align="center">Employee ID</td>
                     <!-- <td width="25%" align="center">Email</td> -->
                     <td width="25%" align="center">Mobile</td>
                    
                     <?php if(! in_array($result[0]['exam_code'], $this->elearning_course_code)){
                        // //admit cards not present for e-learning?>
                     <td width="30%" align="center" colspan="2">Operation</td>
                     <?php } ?>
                     
                     <td width="25%" align="center">Institute Code</td>
                     <td width="25%" align="center" colspan="2">Repeater</td>
                  </tr>
                  <?php $cnt = 0; foreach($result as $row) {$res =array(); ?>
                  <tr>
                     <td width="10%" align="center"><?php echo ++$cnt; ?></td>
                     <td width="20%" align="center"><?php echo $row['exam_code']; ?></td>
                     <td width="20%" align="center"><?php echo $row['exam_period']; ?></td>
                     <td width="20%" align="center"><?php echo $row['regnumber']; ?></td>
                     <td width="20%" align="center"><?php echo $row['firstname']." ".$row['middlename']." ".$row['lastname']; ?>
                     </td>
                     <td width="20%" align="center">
                        <?php 
                           if (isset($row['bank_emp_id']) && $row['bank_emp_id']!='') {
                           	echo $row['bank_emp_id'];
                           	
                           }else{
                           	echo "-";
                           } ?>
                     </td>
                     <!-- <td width="20%" align="center"><?php //echo $row['email']; ?></td> -->
                     <td width="20%" align="center"><?php echo $row['mobile']; ?></td>
                     
                     

                     <?php if(! in_array($row['exam_code'], $this->elearning_course_code)){ 
                     ////admit cards not present for e-learning (added by pooja mane - 2-8-24)?>

                     <td width="30%" align="center" colspan="2"><?php 
                        
                        $this->db->where(array('admitcard_info.mem_mem_no' => $row['regnumber'],'exm_cd'=>101));
                        $res =  $this->UserModel->getRecords("admitcard_info");
                        
                        		
                		if($row['exam_code']=='101' || $row['exam_code']=='1010' || $row['exam_code']=='10100' || $row['exam_code']=='101000' || $row['exam_code']=='1010000')
                		{
                        			
                        			
                        			 if(!empty($res))
                        {//echo '<pre>';
                        //print_r($res);?>
                        <a target="_blank" href="<?php echo base_url().'bulk/BulkTransaction/getadmitcardpdfsp/'.$row['regnumber'].'/'. 101; ?>"> Admit Card </a>
                        <?php }else
                           {
                           	echo 'Admit card not available.';
                           }	}else
                           { 
                              if($row['exam_code'] != '1046')
                              {
                              ?>
                        <?php /*?><a target="_blank" href="<?php echo base_url().'dwnletter/naar_institute_profile_admitcard_pdf_single/'.$row['regnumber']; ?>"> View Admit Card </a><?php */?>
                        <a target="_blank" href="<?php echo base_url().'bulk/BulkTransaction/naar_getadmitcardpdfsp/'.$row['regnumber'].'/'.$row['exam_code'].'/'.$row['exam_period']; ?>">  Admit Card </a>
                        <?php /*?><a target="_blank" href="<?php echo base_url().'uploads/admitcardpdf/'.$row['exam_code'].'_'.$row['exam_period'].'_'.$row['regnumber'].'.pdf'; ?>"> View Admit Card </a><?php */?>
                        <?php 	} 
                              }  
                           ?>
                     </td>
                     <?php } ?>
                     <td width="20%" align="center"><?php echo $row['institute_id']; ?></td>
                     <?php 
                        $this->db->select('app_category');
                        $this->db->where('member_exam.regnumber', $row['regnumber']);
                        $this->db->where('exam_code', $row['exam_code']);
                        $this->db->where('exam_period', $row['exam_period']);
                        $repeater =  $this->master_model->getRecords("member_exam");
                        // echo'<pre>';print_r($repeater);//die;
                        // echo $this->db->last_query();die;
                        ?>
                     <td>
                        <?php if($repeater[0]['app_category'] == 'B1_2' || $repeater[0]['app_category'] == 'B2_1')
                           { echo 'Yes'; } 
                           else
                           { echo 'No'; }?>
                     </td>
                  </tr>
                  <?php } ?>
               </table>
            </td>
         </tr>
         <tr>
            <td></td>
         </tr>
      </table>
      <?php } ?>
   </body>
</html>