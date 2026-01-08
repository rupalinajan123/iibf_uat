<html>
<head><meta http-equiv=Content-Type content="text/html; charset=UTF-8">
</head>
<body>
 
<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" style="font-size:14px;border:1px solid #619fda;">
<tbody>
<tr>
<td>
<table style="border-bottom:1px solid #619fda;width:100%;padding:5px;">
<tr>
<td style="width:15%;"><img src="<?php echo  base_url()?>assets/images/iibf_logo_short.png" /></td>
<td style="width:65%;" align="center" style="font-size:18px;"><a>INDIAN INSTITUTE OF BANKING &amp; FINANCE<sup></sup><br>
                        <small style="font-size:11px;">An ISO 21001:2018 Certified Organisation</small><br/>
                        <span style="font-size:12px;">KOHINOOR CITY, COMMERCIAL - II, TOWER - 1, 2ND FLOOR<br/>
                        KIROL ROAD, KURLA (WEST), MUMBAI - 400 070</span></a>
</td>
<td style="width:20%;text-align:right;">
  <!-- <img src="<?php echo base_url()?>assets/images/90 Year of Service [IIBF]_Logo _Final_08062017.png" width="100" height="100" /> -->
</td>
</tr>
</table> 
</td>
</tr>

<tr>
<td>
<table style="border-bottom:1px solid #619fda;width:100%;padding:5px;">
<tr>
<td width="40%">Membership/Registration No: <?php echo $result_info['regnumber']; ?><br/>
<?php echo $result_info['firstname']." ".$result_info['middlename']." ".$result_info['lastname']; ?><br/>
<?php echo $result_info['address1'];?><br/>
<?php echo $result_info['address2'];?><br/>
<?php 
        if($result_info['address3'] != $result_info['address2'] && $result_info['address3']!=''){
            echo $result_info['address3']."<br/>";
        }
?>
 <?php 
    if($result_info['address4'] != $result_info['address3'] && $result_info['address4']!=''){
      echo $result_info['address4']."<br/>";
    }  
?>
 <?php 
    if($result_info['address5'] != $result_info['address4'] && $result_info['address5']!=''){
      echo $result_info['address5']."<br/>";
    }
 ?>
  <?php 
    if($result_info['address6'] != $result_info['address5'] && $result_info['address6']!=''){
      echo $result_info['address6']."<br/>";
    }
?>
Pin :<?php echo $result_info['pincode']?>
</td>
 <td style="text-align:right;">
  <?php
  $photo_img_name = get_img_name_dra($result_info['regnumber'],'p');
                      if($photo_img_name)
                      {
                        $photo_img = $photo_img_name;
                      }
                      else
                      {
                        $photo_img = "assets/images/defult_user.png";
                      }
              ?>
        <img src="<?php echo base_url();?><?php echo $photo_img;?>" width="100" height="115" />
</td>

</tr>
<tr>
  <td style="width:50%;"></td>
  <td style="text-align:right;">
  <?php
  $sign_img_name = get_img_name_dra($result_info['regnumber'],'s');
                      if($sign_img_name)
                      {
                        $sign_img = $sign_img_name;
                      }
                      else
                      {
                        $sign_img = "assets/images/defult_sign.jpeg";
                      }
              ?>
        <img src="<?php echo base_url();?><?php echo $sign_img;?>" width="100" height="35" />
</td>

  </tr>

</td>
</table>
</tr>

<tr>
<td>
<table style="border-bottom:1px solid #619fda;width:100%;padding:5px;">
<tr>
<td style="width:50%;"><b>Examination</b><br/>
<?php $ename = $result_info['exam_name_short']; ?>
<?php  echo preg_replace("/\([^)]+\)/","",$ename).' '.$result_info['exam_conduct'];?> </td>
<td style="width:50%;text-align:right;"><b>MUMBAI, Dated</b><br/>  <?php echo  $result_info['result_date'];?></td>
</tr>
</table>
</td>
</tr>

<tr>
<td> 
<table width="100%" style="padding:5px;">
<tr>
<td style="width:100%;padding:3px;">DEAR CANDIDATE,</td></tr>
<tr>
<td style="width:100%;padding:3px;">WE HERE UNDER ADVISE YOUR RESULT OF THE ABOVE EXAMINATION</td>
</tr>
</table>
</td>
</tr>

<tr>
<td style="padding: 1em 2em;">
<table style="width:100%;" cellpadding="0" cellspacing="0" style="border:1px solid #ddd;width:100%;padding:3px;">
<tr style="background-color:#ddd;border-bottom:1px solid #ddd;">
<th style="width:50%;text-align:left;padding:9px;text-align:center;">Subject</th>
<th style="width:25%;text-align:left;padding:9px;text-align:center;">Marks Obtained</th>
<th style="width:25%;text-align:left;padding:9px;text-align:center;">Result</th>
<?php $scnt = 0; ?>
</tr>
<tr>
<td style="width:50%;text-align:left;padding:8px;text-align:left;"><?php echo $result_info['subject_description']?></td>
<td style="width:25%;text-align:left;padding:8px;text-align:center;">
<?php
                if($result_info['status'] == 'A'){
                  echo "-"; 
                }
                if($result_info['status'] == 'F' || $result_info['status'] == 'P'){
                   echo $result_info['marks'];
                }
              
               
               ?>
</td>
<td style="width:25%;text-align:left;padding:8px;text-transform:uppercase;text-align:center;"><?php 
                if($result_info['status'] == 'P'){
                  echo "PASS";
                  $scnt++;
                }elseif($result_info['status'] == 'F'){
                  echo "FAIL";
                }elseif($result_info['status'] == 'A'){
                  echo "ABSENT";
                }elseif($result_info['status'] == 'C'){
                  echo "CREDIT TRANSFER"; 
                }elseif($result_info['status'] == 'E'){
                  echo "EXEMPTED "; 
                }
              ?></td>
             
</tr>

</table>
</td>
</tr>

<tr>
<td style="padding: 0em 2em 1em 2em;text-align:right;"> 
<table width="100%" style="padding:2px;text-align:right;">
<tr>
  <td style="width:100%;text-align:left;">MINIMUM PASSING MARKS - 50</td>
</tr>
<tr>
<td style="width:100%;">
Yours Faithfully<br/>
</td>
</tr>
<tr>
<td style="width:50%;">
 <img src="<?php echo base_url();?>assets/images/iibf_officer_sign1.png" alt="" width="75" height="40" style="max-width:40%" />
</td>
</tr>
<tr>
<td style="width:50%;">
Joint Director<br/>Examinations
</td>
</tr>
</table>
</td>
</tr>


<tr>
<td>
<table style="border-top:1px solid #619fda;border-bottom:1px solid #619fda;width:100%;padding:0.5em 5px;">
<tr>
<td style="width:100%;">
<b>This result advice is issued subject to what is stated below which may please be noted</b><br/>
</td>
<tr><td style="padding-bottom:0.2em;">1. As per the rules, no request for revaluation of answer paper will be entertained. The facility of verification of marks for online mode will not be available for
Objective Type (Multiple Choice Questions) examinations since the evaluation is computerized.<br/></td></tr>
<tr><td style="padding-bottom:0.2em;">2. In case a candidate has adopted unfair practices, the declaration of results is only provisional and is subject to cancellation in the event of adoption of unfair
practices being established.<br/></td></tr>
<tr><td style="padding-bottom:0.2em;">3. The result of this examination shall also be liable to be cancelled in the event of it being established that the candidate has adopted unfair practices in any
previous examination.<br/></td></tr>
<tr><td style="padding-bottom:0.2em;">4. <b>Candidates passing the examination will be issued a Certificate</b><br/></td></tr>
<tr><td style="padding-bottom:0.2em;">5. For Important Announcements/Notices & Examination schedule/rules/syllabus visit Institute's website: www.iibf.org.in</td></tr>
</td>
</tr>
</table>
</td>
</tr>

<!-- <tr style="background-color:#ddd;">
<td>
<table width="100%" style="padding:1em 0.5em;">
<tr>
<td style="width:100%;"> Copyright &copy; 2012. INDIAN INSTITUTE OF BANKING AND FINANCE. All rights reserved.</td>
</tr>
</table>
</td>
</tr>-->

</tbody>
</table>
</body>
</html>