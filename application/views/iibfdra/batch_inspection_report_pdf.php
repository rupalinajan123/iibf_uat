<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

</head>


<body>

 <div class="content-wrapper">  
  <!-- Content Header (Page header) -->
  <section class="content-header" style="text-align: center;">
    <h1> DRA Inspection - Online Training Form </h1>
    <h3 style="color: red"> (This form will be filled in by the inspector while inspecting the batch)</h3>
    <?php //$drauserdata = $this->session->userdata('dra_admin');?> 
  </section>
  <section class="content-header">
    <h4> The DRA Training Programs are to be conducted as per the latest terms and conditions as laid down by IIBF and abided by all the DRA accredited Institutions / Agencies.  </h4>
    <h4> Below mentioned format is to be filled with the fact of the training activities as delivered by the agencies and experienced by the assigned Inspector.  </h4>
  </section>
  <div class="col-md-12"> <br />
  </div>
  <!-- Main content -->
  <section class="content">
 <div class="row">
      <div class="col-md-12" style="margin-bottom:1em;">
	    <div class="box box-info box-solid disabled" style="border: 1px solid #00c0ef;">
    <div class="box-body" >
      <div class="table-responsive ">
	  <table class="table table-bordered table-striped" style="width: 100%;max-width: 100%;margin-bottom: 0px; border: 1px solid #f4f4f4;border-spacing: 0;
    border-collapse: collapse;font-size:12px;" >
          <tbody>
            <tr style="background-color: #f9f9f9;">
              <td width="29%" style="border: 1px solid #f4f4f4;padding:8px;"><strong>Name of the DRA Accredited Institution/Bank/FI:</strong></td>
              <td width="20%" style="border: 1px solid #f4f4f4;padding:8px; " id="agency_name"><?php echo $agency_data[0]['institute_name']; ?></td>
              <td width="2%"  style="border: 1px solid #f4f4f4;padding:8px; "></td>
              <td width="29%" style="border: 1px solid #f4f4f4;padding:8px; "><strong>Batch Type:</strong></td>
              <td width="20%" style="border: 1px solid #f4f4f4;padding:8px; " id="training_time_duration"><?php echo $batch_data['hours'].' Hours'; ?></td>
            </tr>
            <tr>
              <td width="29%" style="border: 1px solid #f4f4f4;padding:8px; "><strong> Batch Code :</strong></td>
              <td width="20%" style="border: 1px solid #f4f4f4;padding:8px; " id="batch_code"><?php echo $batch_data['batch_code']; ?></td>
              <td width="2%" style="border: 1px solid #f4f4f4;padding:8px; "></td>
              <td width="29%" style="border: 1px solid #f4f4f4;padding:8px; "><strong>Batch Duration:</strong></td>
              <td width="20%" style="border: 1px solid #f4f4f4;padding:8px; " id="duration"><?php echo $batch_data['batch_from_date'].' To '.$batch_data['batch_to_date']; ?></td>
            </tr>
            <tr style="background-color: #f9f9f9;">
              <td width="29%" style="border: 1px solid #f4f4f4;padding:8px; "><strong>Daily Training Timing:</strong></td>
              <td width="20%" style="border: 1px solid #f4f4f4;padding:8px; " id="daily_training_timing"><?php echo $batch_data['timing_from'].' To '.$batch_data['timing_to']; ?></td>
              <td width="2%" style="border: 1px solid #f4f4f4;padding:8px; "></td>
              <td width="29%" style="border: 1px solid #f4f4f4;padding:8px; "><strong>No. of candidates enrolled in the Batch:</strong></td>
              <td width="20%" style="border: 1px solid #f4f4f4;padding:8px; " id="candidate_count"><?php echo $batch_data['total_candidates']; ?></td>
            </tr>
            <tr>
              <td width="29%" style="border: 1px solid #f4f4f4;padding:8px; " ><strong>Assigned Faculty (main 1):</strong></td>
              <td width="20%" style="border: 1px solid #f4f4f4;padding:8px; "  id="assigned_faculty1"><?php echo $batch_data['first_faculty_name']; ?></td>
              <td width="2%" style="border: 1px solid #f4f4f4;padding:8px; " ></td>
              <td width="29%" style="border: 1px solid #f4f4f4;padding:8px; " ><strong>Assigned Faculty(main 2):</strong></td>
              <td width="20%" style="border: 1px solid #f4f4f4;padding:8px; "  id="assigned_faculty2"><?php echo $batch_data['sec_faculty_name']; ?></td>
            </tr>
            <tr style="background-color: #f9f9f9;">
              <td width="29%" style="border: 1px solid #f4f4f4;padding:8px; " ><strong>Assigned Faculty (additional 1):</strong></td>
              <td width="20%" style="border: 1px solid #f4f4f4;padding:8px; "  id="additional_faculty1"><?php echo $batch_data['add_first_faculty_name']; ?></td>
              <td width="2%" style="border: 1px solid #f4f4f4;padding:8px; " ></td>
              <td width="29%" style="border: 1px solid #f4f4f4;padding:8px; " ><strong>Assigned Faculty(additional 2):</strong></td>
              <td width="20%" style="border: 1px solid #f4f4f4;padding:8px; " id="additional_faculty2"><?php echo $batch_data['add_sec_faculty_name']; ?></td>
            </tr>
            <tr>
              <td width="29%" style="border: 1px solid #f4f4f4;padding:8px; " ><strong>Co-ordinator name and Mobile no. :</strong></td>
              <td width="20%" style="border: 1px solid #f4f4f4;padding:8px; "  id="coordinator_name"><?php echo $batch_data['contact_person_name'].' ('. $batch_data['contact_person_phone'].')'; ?></td>
              <td width="2%" style="border: 1px solid #f4f4f4;padding:8px; " ></td>
              <td width="29%" style="border: 1px solid #f4f4f4;padding:8px; "><strong>Co-ordinator name and Mobile no. (additional):</strong></td>
              <td width="20%" style="border: 1px solid #f4f4f4;padding:8px; " id="additional_coordinator_name"><?php echo $batch_data['alt_contact_person_name'].' ('. $batch_data['alt_contact_person_phone'].')'; ?></td>
            </tr>

            <tr style="background-color: #f9f9f9;">
              <td width="29%" style="border: 1px solid #f4f4f4;padding:8px; "><strong>Training Language :</strong></td>
              <td width="20%" style="border: 1px solid #f4f4f4;padding:8px; " id="training_language"><?php echo $batch_data['training_medium']; ?></td>
              <td width="2%" style="border: 1px solid #f4f4f4;padding:8px; " ></td>
      				<td width="2%" style="border: 1px solid #f4f4f4;padding:8px; ">&nbsp;</td>
      				<td width="29%" style="border: 1px solid #f4f4f4;padding:8px; " >Document:</td>
      				<td width="20%" style="border: 1px solid #f4f4f4;padding:8px; " ><a href="<?php echo base_url('uploads/training_schedule/'.$batch_data['training_schedule']); ?>" id="document_href" target="_blank"></a></td>
      			</tr>

            <?php if($batch_data['batch_online_offline_flag'] == 1) { ?>
              <tr class="online_offline_flag">
                <td width="29%" style="border: 1px solid #f4f4f4;padding:8px; "><strong>Name of the on-line platform:</strong></td>
                <td width="20%" style="border: 1px solid #f4f4f4;padding:8px; "  id="batch_training_platform"><?php echo $batch_data['online_training_platform']; ?></td>
                <td width="2%" style="border: 1px solid #f4f4f4;padding:8px; "></td>
                <td width="29%" style="border: 1px solid #f4f4f4;padding:8px; " ><strong>Platform Link:</strong></td>
                <td width="20%" style="border: 1px solid #f4f4f4;padding:8px; "  id="platform_link"><a href="<?php echo $batch_data['platform_link']; ?>" target="_blank"><?php echo $batch_data['platform_link']; ?></a></td>
              </tr>

              <tr style="background-color: #f9f9f9;" class="online_offline_flag">
                <td width="29%" style="border: 1px solid #f4f4f4;padding:8px; "><strong>Login ID/Password:</strong></td>
                <td width="20%" rowspan="2" style="border: 1px solid #f4f4f4;" >
                  <table border="0" style="border-spacing: 0;border-collapse: collapse;">
                    <thead>
                      <tr>
                      <th width="50%">Login Id</th>
                      <th width="50%">Password</th>
                    </tr>
                  </thead>
                  <tbody>
                   <?php foreach ($batch_login_details as $key => $value) { ?>
                    <tr>
                      <td style="border: 1px solid #f4f4f4;padding:8px"><?php echo $value['login_id']; ?></td>
                      <td style="border: 1px solid #f4f4f4;padding:8px;" ><?php echo base64_decode($value['password']); ?></td>
                    </tr> 
                  <?php } ?>
                   
                  </tbody>
                </table>

                </td>
        			  <td width="2%" style="border: 1px solid #f4f4f4;padding:8px; ">&nbsp;</td>
        				<td width="29%" style="border: 1px solid #f4f4f4;padding:8px; " >&nbsp;</td>
        				<td width="20%" style="border: 1px solid #f4f4f4;padding:8px; ">&nbsp;</td>
              </tr>   
            <?php }?>   
            
          </tbody>
      </table>
    </div>
    </div>
    </div>
    </div>
    </div>

    <div class="row">
      <div class="col-md-12" style="margin-bottom:1em">
        <div class="box box-info box-solid disabled" style="border: 1px solid #00c0ef;">
          <div class="box-header with-border" style="border: none;box-shadow: none;background: #7fd1ea; border-radius: 0;">
            <h3 class="box-title" style="color:#fff;margin:0;padding:0">Batch Inspection</h3>
            
            <!-- /.box-tools --> 
          </div>
          <!-- /.box-header -->
          <div class="box-body" >
       
            <div class="table-responsive">
              <table class="table table-bordered table-striped" style="width: 100%;max-width: 100%;margin-bottom: 0px; border: 1px solid #f4f4f4;border-spacing: 0;
    border-collapse: collapse;font-size:12px;" >
                <tbody>
                  <?php 
                    $width = '30%';
                    $str = '';
                    $str.='<tr style="background-color: #f9f9f9;">';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;"><strong>Sr</strong></td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;"><strong>Title</strong></td>';

                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;"><strong>Inspection No:'.$batch['inspection_no'].'</strong></td>';
                    }
                    $str.='</tr>';

                    $str.='<tr>';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='<td width="10%" style="border: 1px solid #f4f4f4;padding:8px;"><strong>Inspection Start Date/Time</strong></td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;"><strong>'.$batch['inspection_start_time'].'</strong></td>';
                    }
                    $str.='</tr>';

                    $str.='<tr style="background-color: #f9f9f9;">';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='<td width="10%" style="border: 1px solid #f4f4f4;padding:8px;"><strong>Inspection End Date/Time</strong></td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;"><strong>'.$batch['created_on'].'</strong></td>';
                    }
                    $str.='</tr>';

                    $str.='<tr>';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;"></td>';
                    $str.='<td width="5%" style="border: 1px solid #f4f4f4;padding:8px;"><strong>Inspection Name/ID</strong></td>';
                    foreach($batch_insp as $key => $batch){
                      $insp=$this->master_model->getRecords('agency_inspector_master',array('id'=>$batch['inspector_id']));
                      $str.='<td style="border: 1px solid #f4f4f4;"><strong>'.$insp[0]['inspector_name'].'/ '.$insp[0]['id'].'</strong></td>';
                    }
                    $str.='</tr>';

                    $str.='<tr style="background-color: #f9f9f9;">';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;">1</td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">Number of candidates logged-in at start of visit to the platform (excluding self / faculty/ coordinator or any other administrator)</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['candidates_loggedin'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr>';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;">2</td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">Whether the declared Link / Platform for the training got changed (Yes / No). If Yes, mention the Link / Name of the Platform for the training purpose.</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['platform_name'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr style="background-color: #f9f9f9;">';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;">3</td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">Whether there are multiple logins with same name (Yes / No)? If Yes, how many such multiple logins are there?</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['multiple_login_same_name'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr>';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;">4</td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">Whether log-ins with instrument name (Samsung/oppo etc) is there (Yes / No). If Yes, how many such log-ins?</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['instrument_name'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr style="background-color: #f9f9f9;">';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;">5</td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">Whether any issues were faced while logging-in onto the Online Platform (e.g. wrong log-in credentials / waited for more than 2 minutes in waiting room / taking you into a platform of a different link / only buffering for minutes etc.)</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['issues'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr>';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;">6</td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">Whether virtual recording is ‘On’ or “not On” or started after your joining / insisting for the same. In case the session recording is not on, mention the reason of such situation.</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['training_session'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr style="background-color: #f9f9f9;">';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;">7</td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">Training Details</td>';
                    $str.='<td style="border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='</tr>';

                    $str.='<tr>';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">(i) No. of candidates available during training sessions</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['session_candidates'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr style="background-color: #f9f9f9;">';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">(ii) Is the training going on as per session plan shared by the Agency (can be confirmed from the Faculty)</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['training_session_plan'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr>';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;">8</td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">Attendance</td>';
                    $str.='<td style="border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='</tr>';

                    $str.='<tr style="background-color: #f9f9f9;">';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">i. Whether Attendance Sheet is updated by the Agency till the time of inspection (Yes / No).</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['attendance_sheet_updated'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr>';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">ii. Mode of taking attendance (Online / Screen Shot / Manual calling etc.)</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td>'.$batch['attendance_mode'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr style="background-color: #f9f9f9;">';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">iii. Whether the Attendance Sheet is shown promptly to the Inspector on demand (Yes / No).</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td>'.$batch['attendance_shown'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr>';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;">9</td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">Is there any group of candidates attending the sessions through a single device? (loptop/Mobile/PC/Big screen/monitor)
                                    please mention the candidate count and device)</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['candidate_count_device'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr style="background-color: #f9f9f9;">';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;">10</td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">Faculty Details</td>';
                    $str.='<td style="border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='</tr>';

                    $str.='<tr>';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">a) Whether Name / Code of Faculty is displayed on the platform (Yes / No).</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['actual_faculty'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr style="background-color: #f9f9f9;">';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">b) Name / Code of Faculty taking session</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['faculty_taking_session'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr>';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">c) If the Faculty who is taking session is different from the declared one, please mention:
                           <br>i. Name and Qualification (highest) of the Faculty</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['name_qualification'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr style="background-color: #f9f9f9;">';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">ii. No. of days / sessions she/he has taken / will take</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['no_of_days'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr>';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">iii. Reason of such change in faculty</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['reason_of_change_in_faculty'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr style="background-color: #f9f9f9;">';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">iv. Whether the Faculty is having earlier experience in teaching / training in BFSI sector (mention in brief).</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['experience_teaching_training_BFSI_sector'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr>';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">d) Language in which the Faculty is taking the session</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['faculty_language'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr style="background-color: #f9f9f9;">';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">e) The Faculty is taking sessions for how many hrs/min per day</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['faculty_session_time'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr>';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">f) Whether there are minimum 2 faculties are taking sessions to complete the 50 / 100 hours training.</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['two_faculty_taking_session'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr style="background-color: #f9f9f9;">';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">g) Whether the language(s) used by the Faculty is understandable by the candidates (can be confirmed from the participants).</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['faculty_language_understandable'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr>';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">h) Whether the online training tools like whiteboard / PPT / PDF / Documents are used while delivering lectures.</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['whiteboard_ppt_pdf_used'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr style="background-color: #f9f9f9;">';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;">11</td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">Whether the faculty (in case of new faculty only) and all the candidates have attended preparatory / briefing session on the etiquettes of the upcoming DRA training (Yes / No).</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['session_on_etiquettes'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr>';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;">12</td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">Whether the faculty and trainees were conversant with the process of on-line training.</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['faculty_trainees_conversant'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr style="background-color: #f9f9f9;">';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;">13</td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">Whether the candidates could recognise the name of the training providing agency / institution (Yes / No).</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['candidates_recognise'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr>';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;">14</td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">Whether candidates were given "Handbook on debt recovery" by the concerned agency.</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['handbook_on_debt_recovery'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr style="background-color: #f9f9f9;">';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;">15</td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">Whether candidates are provided with other study materials in word/pdf format by the agency).</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['other_study_materials'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr>';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;">16</td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">Whether the training was conducted without any disturbances/ noises?</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['training_conduction'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr style="background-color: #f9f9f9;">';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;">17</td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">Batch Coordinator</td>';
                    $str.='<td style="border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='</tr>';

                    $str.='<tr>';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">a) Whether Name of Batch Coordinator is displayed on the virtual platform with Batch Code (Yes / No).</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['batch_coordinator_available'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr style="background-color: #f9f9f9;">';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">b) Name / Code of the Coordinator is available in the Session</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['coordinator_available_name'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr>';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">c) The Coordinator is whether originally allotted or not (Yes/ No). In case No, mention the name and contact no. of the available Coordinator.</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['current_coordinator_available_name'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr style="background-color: #f9f9f9;">';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;">18</td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">Any irregularity(ies) consistently / frequently persist despite repetitive reminders for rectification</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['any_irregularity'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr>';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;">19</td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">Assessment / rating (viz. 1-Poor / 2-Average / 3-Good / 4-Excellent) consequent to overall impression during visit to the virtual training session</td>';
                    $str.='<td style="border: 1px solid #f4f4f4;padding:8px;"></td>';
                    /*foreach($batch_insp as $key => $batch){
                      $str.='<td>'.$batch['assessment'].'</td>';
                    }*/
                    $str.='</tr>';

                    $str.='<tr style="background-color: #f9f9f9;">';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">a) Quality of Teaching:
                           <br>i. Level of interaction with candidates
                           </td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['teaching_quality_interaction_with_candidates'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr>';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">
                           <br>ii. Understanding with curiosity while teaching (especially  during soft-skill session)</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['teaching_quality_softskill_session'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr style="background-color: #f9f9f9;">';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">b) Candidates attentiveness and participation</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['candidates_attentiveness'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr>';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">c) Candidates Attitude and their Behaviour</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['DRA_attitude_behaviour'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr style="background-color: #f9f9f9;">';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">d) Quality of learning by DRAs:
                                <br>i.  Interaction with Faculty</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['learning_quality_interaction_with_faculty'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr>';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">
                                <br>ii. Response to queries made by faculty / inspector </td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['learning_quality_response_to_queries'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr style="background-color: #f9f9f9;">';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">e) Effectiveness of training</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['teaching_effectiveness'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr>';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">f) Curriculum covered with reference to the Syllabus</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['curriculum_covered'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr style="background-color: #f9f9f9;">';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">g) Overall compliance on:
                                i.  Training delivery</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['overall_compliance_training_delivery'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr>';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;"></td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">
                                ii. Training coordination</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['overall_compliance_training_coordination'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr style="background-color: #f9f9f9;">';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;">20</td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">Any other observations with respect to non-adherence to the conditions stipulated by IIBF for conducting on-line DRA Training</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['other_observations'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr>';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;">21</td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">Overall Observation of the Inspector on the training of the DRA Batch</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['overall_observation'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr style="background-color: #f9f9f9;">';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;">22</td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">Over all compliance</td>';
                    foreach($batch_insp as $key => $batch){
                      $str.='<td style="border: 1px solid #f4f4f4;padding:8px;">'.$batch['overall_compliance'].'</td>';
                    }
                    $str.='</tr>';

                    $str.='<tr>';
                    $str.='<td width="8%" style="text-align: center;border: 1px solid #f4f4f4;padding:8px;">23</td>';
                    $str.='<td width="'.$width.'" style="border: 1px solid #f4f4f4;padding:8px;">Attachment</td>';
                    foreach($batch_insp as $key => $batch){
                      if(!empty($batch['attachment'])){
                        $str.='<td style="border: 1px solid #f4f4f4;padding:8px;"><a href="'.base_url('uploads/inspection_report/'.$batch['attachment']).'" target="_blank">View</a></td>';
                      }
                      else{
                        $str.='<td style="border: 1px solid #f4f4f4;padding:8px;"></td>';
                      }
                      
                      //$str.='<td></td>';
                    }
                    $str.='</tr>';

                    echo $str;
                  ?>
                </tbody>
              </table>
            </div>
           
          </div>
          <!-- /.box-body --> 
        </div>
        <!-- /.box --> 
      </div>
	  

	  
      <div class="col-md-12" style="margin-bottom:5em;">
	   <div class="box box-info box-solid disabled" style="border: 1px solid #00c0ef;">
       <div class="box-header with-border" style="border: none;box-shadow: none;background: #7fd1ea; border-radius: 0;">
          <h3 class="box-title" style="color:#fff;margin:0;padding:0">Batch Candidate's Details</h3>
          <div class="box-tools pull-right">
            <button data-widget="collapse" class="btn btn-box-tool" type="button"><i class="fa fa-minus"></i> </button>
          </div>
        </div>
       
          <div class="box-body">
            <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
            <input type="hidden" name="base_url_val" id="base_url_val" value="" />
            <!-- <input type="hidden" name="question_checked_array" id="question_checked_array" value="" /> -->

            <div class="table-responsive">
			 <table id="listitems" class="table table-bordered table-striped" style="width: 100%;max-width: 100%;margin-bottom: 0px; border: 1px solid #f4f4f4;border-spacing: 0;
    border-collapse: collapse;font-size:12px;" >
                <thead>
                  <tr>
                    <th width="5%" style="text-align: center;">Sr.No.</th> 
                    <th width="15%">Training Id</th>
                    <th width="15%">Candidate Name</th>
                    <th width="10%">DOB</th>
                    <th width="10%">Mobile</th>
                    <th width="10%">Photo</th>
                    <th width="10%">Present Count</th>
                    <th width="10%">Absent Count</th>
                    <th width="15%">Remark</th>
                  </tr>
                </thead>
                <tbody class="no-bd-y" id="list">
                  <?php foreach ($batch_candidates as $key => $value) { 
                    $j = $key+1;
                    $img = '';
                    if(file_exists('uploads/iibfdra/'.$value['scannedphoto']) && $value['scannedphoto'] !=''){
                      $photo = '<img height="90" width="70" src="'.base_url('uploads/iibfdra/'.$value['scannedphoto']).'" alt="">';
                    }
                    else{
                      $photo ='';
                    }
                    
                  ?>
                    <tr>
                      <td style="border: 1px solid #f4f4f4;padding:8px;text-align: center;"><?php echo $j; ?></td> 
                      <td style="border: 1px solid #f4f4f4;padding:8px;"><?php echo $value['training_id']; ?></td>
                      <td style="border: 1px solid #f4f4f4;padding:8px;"><?php echo $value['name']; ?></td>
                      <td style="border: 1px solid #f4f4f4;padding:8px;"><?php echo $value['dateofbirth']; ?></td>
                      <td style="border: 1px solid #f4f4f4;padding:8px;"><?php echo $value['mobile_no']; ?></td>
                      <td style="border: 1px solid #f4f4f4;padding:8px;"><?php echo $photo; ?></td>
                      <td style="border: 1px solid #f4f4f4;padding:8px;"><?php echo $value['present_cnt']; ?></td>
                      <td style="border: 1px solid #f4f4f4;padding:8px;"><?php echo $value['absent_cnt']; ?></td>
                      <td style="border: 1px solid #f4f4f4;padding:8px;"><?php echo $value['remark']; ?></td>
                    </tr>
                  <?php }?>
                </tbody>
              </table>
              
            </div>
          </div>
          <!-- /.box-body --> 
        </div>
        <!-- /.box --> 
      </div>
      <!-- /.col collapsed-box-->

    </div>

  </section>
 </div> 



<?php //$this->load->view('iibfdra/admin/includes/footer');?>

</body>
</html>