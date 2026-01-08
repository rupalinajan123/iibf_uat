<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('ncvet/kyc/inc_header'); ?>    

    <!-- Include FancyBox CSS & JS -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" /> -->
    <link href="<?php echo auto_version(base_url('assets/ncvet/css/fancybox.css')); ?>" rel="stylesheet">
    <!-- <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script> -->
    <script src="<?php echo auto_version(base_url('assets/ncvet/js/fancybox.umd.js')); ?>"></script>
    <style type="text/css">
      /* Custom position for FancyBox */
    .custom-fancybox .fancybox__container {
      align-items: flex-start !important;  /* push to top */
      justify-content: center;             /* keep horizontally centered */
    }

    .custom-fancybox .fancybox__content {
      width: 70% !important;
      height: 100% !important;
      margin-left: 250px; 
    }

    .fancybox__caption {
      position: absolute;
      top: 93%;
      left: 27%;
      bottom: auto;
      right: auto;
      transform: translateY(-50%);
      text-align: left;
      width: auto;
      /*background: rgba(0,0,0,0.6);*/
      /*color: #fff;*/
      padding: 6px 10px;
      border-radius: 4px;
    }
    </style>

  </head>
	<body class="fixed-sidebar">
  <?php $this->load->view('ncvet/common/inc_loader'); ?>
		
		<div id="wrapper">
    <?php $this->load->view('ncvet/kyc/inc_sidebar_admin'); ?>		
			<div id="page-wrapper" class="gray-bg">				
      <?php $this->load->view('ncvet/kyc/inc_topbar_admin'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2><?php echo $page_title; ?></h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item active"> <strong><?php echo $disp_title; ?></strong></li>
						</ol>
					</div>
					<div class="col-lg-2"> </div>
				</div>

        <?php  

        function get_kyc_radio_btn_details($member_data=array(),$input_name='')
        {
          $login_user_type = $_SESSION['NCVET_KYC_ADMIN_TYPE'];
          $approve_check = $reject_check = $approve_cls = $reject_cls = '';
          $disabled_flg ='';
          
          if($member_data['img_ediited_on'] != '' && $member_data['img_ediited_on'] != '0000-00-00 00:00:00')//EDITED MEMBER
          {
            if(isset($member_data[$input_name]) && $member_data[$input_name] == 'Y' && $login_user_type == '1') { $disabled_flg = 'disabled'; }
          }
          
          if($member_data[$input_name] == 'Y') 
          { 
            $approve_check = 'checked'; 
            if($disabled_flg != '') { $reject_cls = 'hide'; }                                
          } 
          else if($member_data[$input_name] == 'N') 
          { 
            //$reject_check = 'checked'; 
            //if($disabled_flg != '') { $approve_cls = 'hide'; }
          }

          $data = array();
          $data['approve_check'] = $approve_check;
          $data['reject_check'] = $reject_check;
          $data['approve_cls'] = $approve_cls;
          $data['reject_cls'] = $reject_cls;
          $data['disabled_flg'] = $disabled_flg;
          return $data;
        } 

        $declaration_show_hide = 'hide';
        $institute_idproof_show_hide = 'hide';
        $qualification_certificate_file_show_hide = 'hide';
        $exp_certificate_show_hide = 'hide';
        $vis_imp_cert_img_show_hide = 'hide';
        $orth_han_cert_img_show_hide = 'hide';
        $cer_palsy_cert_img_show_hide = 'hide';
        if(isset($mem_data) && $mem_data != "")
        {
            if($mem_data[0]['qualification'] == "3" || $mem_data[0]['qualification'] == "4"){
              $declaration_show_hide = '';
              $institute_idproof_show_hide = '';
            }
            if($mem_data[0]['qualification'] == "1" || $mem_data[0]['qualification'] == "2"){
              $qualification_certificate_file_show_hide = '';
            }
            if($mem_data[0]['qualification'] == "1"){
              $qualification_certificate_file_show_hide = '';
              $exp_certificate_show_hide = '';
            }
            if($mem_data[0]['benchmark_disability'] == "Y" && $mem_data[0]['visually_impaired'] == "Y"){
              $vis_imp_cert_img_show_hide = '';
            }
            if($mem_data[0]['benchmark_disability'] == "Y" && $mem_data[0]['orthopedically_handicapped'] == "Y"){
              $orth_han_cert_img_show_hide = '';
            }
            if($mem_data[0]['benchmark_disability'] == "Y" && $mem_data[0]['cerebral_palsy'] == "Y"){
              $cer_palsy_cert_img_show_hide = '';
            }
        }

        ?>
				
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
								<div class="ibox-content"> 
                  <?php if($this->session->flashdata('success_kyc')){ ?>
                  <div class="alert alert-success "><?php echo $this->session->flashdata('success_kyc'); ?><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                <?php } ?>

                  <form method="post" action="<?php echo site_url('ncvet/kyc/benchmark_kyc_all/process_kyc_details'); ?>" id="kyc_form" enctype="multipart/form-data" autocomplete="off">
                     
                    <div class="table-responsive">
                      <table class="table table-bordered" style="width:100%">
                        <thead>
                          <tr> 
                            <th class="text-center nowrap">Name</th>
                            <th class="text-center nowrap">Enrollement No.</th>
                            <!-- <th class="text-center nowrap">Training ID</th> -->
                            <th class="text-center nowrap">Birth Date</th>
                            <th class="text-center nowrap">Aadhar No.</th>
                            <th class="text-center nowrap">APAAR ID/ABC ID</th>
                            <th class="wrap" style="width: 260px;">Eligibility</th>
                            <th class="text-center nowrap">Photo</th>
                            <th class="text-center nowrap">Signature</th>
                            <th class="text-center nowrap">APAAR ID/ABC ID</th>
                            <th class="text-center nowrap">Aadhar Card</th>
                            <th class="text-center nowrap <?php echo $declaration_show_hide; ?>">Declaration</th>
                            <th class="text-center nowrap <?php echo $institute_idproof_show_hide; ?>">Institute Id Proof</th>
                            <th class="text-center nowrap <?php echo $qualification_certificate_file_show_hide; ?>">Qualification Certificate</th>
                            <th class="text-center nowrap <?php echo $exp_certificate_show_hide; ?>">Experience Certificate</th>
                            <th class="text-center nowrap <?php echo $vis_imp_cert_img_show_hide; ?>">Visually Impaired</th>
                            <th class="text-center nowrap <?php echo $orth_han_cert_img_show_hide; ?>">Orthopedically Handicapped</th>
                            <th class="text-center nowrap <?php echo $cer_palsy_cert_img_show_hide; ?>">Cerebral Palsy</th>

                          </tr>
                        </thead>
                        
                        <tbody>
                          <?php 
                          $empty_fullname_flag = $empty_dob_flag = $empty_aadhar_flag = $empty_apaar_flag = $empty_eligibility_flag = $empty_photo_flag = $empty_sign_flag = $empty_id_proof_flag = $empty_declaration_flag = $empty_institute_idproof_flag = $empty_qualification_certificate_file_flag = $empty_exp_certificate_flag = $empty_vis_imp_cert_img_flag = $empty_orth_han_cert_img_flag = $empty_cer_palsy_cert_img_flag = '';

                          $qualification_arr = array('1'=>'12th Pass with 1.5 years of experience in BFSI (not pursuing graduation / post graduation)', '2'=>'Graduate not pursuing Post Graduation', '3'=>'Pursuing Graduation','4'=>'Pursuing Postgraduation');

                          if(count($mem_data) > 0)
                          { ?>
                            <tr>
                              <td><?php /* Name */ ?>
                                <?php 
                                  $disp_name = $mem_data[0]['salutation']; 
                                  $disp_name .= $mem_data[0]['first_name'] !=''? ' '.$mem_data[0]['first_name']:'';
                                  $disp_name .= $mem_data[0]['middle_name'] !=''? ' '.$mem_data[0]['middle_name']:'';
                                  $disp_name .= $mem_data[0]['last_name'] !=''? ' '.$mem_data[0]['last_name']:'';
                                  echo $disp_name;
                                ?>
                                 
                              </td>
                              <td><?php echo $mem_data[0]['regnumber']; ?></td><?php /* Regnumber */ ?>
                              <!-- <td><?php //echo $mem_data[0]['training_id']; ?></td> --><?php /* Training ID */ ?>
                              <td><?php echo $mem_data[0]['dob']; ?></td><?php /* Birth Date */ ?>
                              <td><?php echo $mem_data[0]['aadhar_no']; ?></td><?php /* aadhar_no */ ?>
                              <td><?php echo $mem_data[0]['id_proof_number']; ?></td><?php /* id_proof_number */ ?>

                              <td><?php 
                              if($mem_data[0]['qualification'] != ""){
                                echo isset($qualification_arr[$mem_data[0]['qualification']]) ? $qualification_arr[$mem_data[0]['qualification']] : '';
                              }
                              ?></td><?php /* qualification */ ?>
                              
                              <td class="text-center"><?php /* Photo */ ?>
                                <?php if ($mem_data[0]['photo_file'] != "" && file_exists($photo_path . '/' . $mem_data[0]['photo_file']))
                                { ?>
                                  <div id="photo_file_preview" class="upload_img_preview" style="margin:0 auto;">
                                    <a href="<?php echo base_url($photo_path . '/' . $mem_data[0]['photo_file']) . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Photo - '.$disp_name.' ('.$mem_data[0]['training_id'].')'; ?>">
                                      <img src="<?php echo base_url($photo_path . '/' . $mem_data[0]['photo_file']) . "?" . time(); ?>">
                                    </a>
                                  </div>
                                <?php } else { $empty_photo_flag = '1'; } ?>
                              </td>

                              <td class="text-center"><?php /* Signature */ ?>
                                <?php if ($mem_data[0]['sign_file'] != "" && file_exists($sign_path . '/' . $mem_data[0]['sign_file']))
                                { ?>
                                  <div id="sign_file_preview" class="upload_img_preview" style="margin:0 auto;">
                                    <a href="<?php echo base_url($sign_path . '/' . $mem_data[0]['sign_file']) . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Signature - '.$disp_name.' ('.$mem_data[0]['training_id'].')'; ?>">
                                      <img src="<?php echo base_url($sign_path . '/' . $mem_data[0]['sign_file']) . "?" . time(); ?>">
                                    </a>
                                  </div>
                                <?php } else { $empty_sign_flag = '1'; } ?>
                              </td>

                              <td class="text-center"><?php /* APAAR ID/ABC ID */ ?>
                                <?php if ($mem_data[0]['id_proof_file'] != "" && file_exists($id_proof_path . '/' . $mem_data[0]['id_proof_file']))
                                { 
                                  $extention = strtolower(pathinfo($id_proof_path . '/' . $mem_data[0]['id_proof_file'], PATHINFO_EXTENSION));
                                  if($extention == "pdf"){
                                  ?>  
                                  <a data-fancybox data-type="iframe" 
                                     data-src="https://mozilla.github.io/pdf.js/web/viewer.html?file=<?php echo base_url($id_proof_path . '/' . $mem_data[0]['id_proof_file']) . "?" . time(); ?>" href="javascript:;" data-caption="<?php echo 'APAAR ID/ABC ID - '.$disp_name.' ('.$mem_data[0]['regnumber'].')'; ?>">
                                  <div id="id_proof_file_preview" class="upload_img_preview" style="margin:0 auto;"> 
                                      <img src="<?php echo base_url('assets/images/pdf.png'); ?>" width="80" height="80" alt="PDF"> 
                                  </div> 
                                  </a> 
                                  <?php
                                  }else{
                                    ?>
                                  <div id="id_proof_file_preview" class="upload_img_preview" style="margin:0 auto;">
                                    <a href="<?php echo base_url($id_proof_path . '/' . $mem_data[0]['id_proof_file']) . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'APAAR ID/ABC ID - '.$disp_name.' ('.$mem_data[0]['regnumber'].')'; ?>">
                                      <img src="<?php echo base_url($id_proof_path . '/' . $mem_data[0]['id_proof_file']) . "?" . time(); ?>">
                                    </a>
                                  </div>
                                <?php } ?>
                                   
                                <?php }else { $empty_id_proof_flag = '1'; } ?>
                              </td>

                              <td class="text-center"><?php /* Aadhar Card */ ?>
                                <?php if ($mem_data[0]['aadhar_file'] != "" && file_exists($aadhar_file_path . '/' . $mem_data[0]['aadhar_file']))
                                { 
                                  $extention = strtolower(pathinfo($aadhar_file_path . '/' . $mem_data[0]['aadhar_file'], PATHINFO_EXTENSION));
                                  if($extention == "pdf"){
                                  ?>  
                                  <a data-fancybox data-type="iframe" 
                                     data-src="https://mozilla.github.io/pdf.js/web/viewer.html?file=<?php echo base_url($aadhar_file_path . '/' . $mem_data[0]['aadhar_file']) . "?" . time(); ?>" href="javascript:;" data-caption="<?php echo 'Aadhar Card - '.$disp_name.' ('.$mem_data[0]['regnumber'].')'; ?>">
                                  <div id="aadhar_file_preview" class="upload_img_preview" style="margin:0 auto;"> 
                                      <img src="<?php echo base_url('assets/images/pdf.png'); ?>" width="80" height="80" alt="PDF"> 
                                  </div> 
                                  </a> 
                                  <?php
                                  }else{
                                    ?>
                                  <div id="aadhar_file_preview" class="upload_img_preview" style="margin:0 auto;">
                                    <a href="<?php echo base_url($aadhar_file_path . '/' . $mem_data[0]['aadhar_file']) . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Aadhar Card - '.$disp_name.' ('.$mem_data[0]['regnumber'].')'; ?>">
                                      <img src="<?php echo base_url($aadhar_file_path . '/' . $mem_data[0]['aadhar_file']) . "?" . time(); ?>">
                                    </a>
                                  </div>
                                <?php } ?>
                                   
                                <?php }else { $empty_aadhar_file_flag = '1'; } ?>
                              </td>

                              <td class="text-center <?php echo $declaration_show_hide; ?>"><?php /* Declaration */ ?>
                                <?php if ($mem_data[0]['declarationform'] != "" && file_exists($declaration_path . '/' . $mem_data[0]['declarationform']))
                                { 
                                  $extention = strtolower(pathinfo($declaration_path . '/' . $mem_data[0]['declarationform'], PATHINFO_EXTENSION));
                                  if($extention == "pdf"){
                                  ?>   
                                  <a data-fancybox data-type="iframe" 
                                     data-src="https://mozilla.github.io/pdf.js/web/viewer.html?file=<?php echo base_url($declaration_path . '/' . $mem_data[0]['declarationform']) . "?" . time(); ?>" href="javascript:;" data-caption="<?php echo 'Declaration - '.$disp_name.' ('.$mem_data[0]['regnumber'].')'; ?>">
                                  <div id="declaration_preview" class="upload_img_preview" style="margin:0 auto;"> 
                                      <img src="<?php echo base_url('assets/images/pdf.png'); ?>" width="80" height="80" alt="PDF"> 
                                  </div> 
                                  </a> 
                                  <?php
                                  }else{
                                    ?>
                                  <div id="declaration_preview" class="upload_img_preview" style="margin:0 auto;">
                                    <a href="<?php echo base_url($declaration_path . '/' . $mem_data[0]['declarationform']) . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Declaration - '.$disp_name.' ('.$mem_data[0]['training_id'].')'; ?>">
                                      <img src="<?php echo base_url($declaration_path . '/' . $mem_data[0]['declarationform']) . "?" . time(); ?>">
                                    </a>
                                  </div>
                                <?php } ?>

                                <?php } else { $empty_declaration_flag = '1'; } ?>
                              </td>

                              <td class="text-center <?php echo $institute_idproof_show_hide; ?>"><?php /* institute_idproof */ ?>
                                <?php if ($mem_data[0]['institute_idproof'] != "" && file_exists($institute_idproof_path . '/' . $mem_data[0]['institute_idproof']))
                                { 
                                  $extention = strtolower(pathinfo($institute_idproof_path . '/' . $mem_data[0]['institute_idproof'], PATHINFO_EXTENSION));
                                  if($extention == "pdf"){
                                  ?>  
                                  <a data-fancybox data-type="iframe" 
                                     data-src="https://mozilla.github.io/pdf.js/web/viewer.html?file=<?php echo base_url($institute_idproof_path . '/' . $mem_data[0]['institute_idproof']) . "?" . time(); ?>" href="javascript:;" data-caption="<?php echo 'Institute Id Proof - '.$disp_name.' ('.$mem_data[0]['regnumber'].')'; ?>">
                                  <div id="institute_idproof_preview" class="upload_img_preview" style="margin:0 auto;"> 
                                      <img src="<?php echo base_url('assets/images/pdf.png'); ?>" width="80" height="80" alt="PDF"> 
                                  </div> 
                                  </a> 
                                  <?php
                                  }else{
                                    ?>
                                  <div id="institute_idproof_preview" class="upload_img_preview" style="margin:0 auto;">
                                    <a href="<?php echo base_url($institute_idproof_path . '/' . $mem_data[0]['institute_idproof']) . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Institute Id Proof - '.$disp_name.' ('.$mem_data[0]['training_id'].')'; ?>">
                                      <img src="<?php echo base_url($institute_idproof_path . '/' . $mem_data[0]['institute_idproof']) . "?" . time(); ?>">
                                    </a>
                                  </div>
                                  <?php } ?>

                                <?php } else { $empty_institute_idproof_flag = '1'; } ?>
                              </td>

                              <td class="text-center <?php echo $qualification_certificate_file_show_hide; ?>"><?php /* qualification_certificate_file */ ?>
                                <?php if ($mem_data[0]['qualification_certificate_file'] != "" && file_exists($qualification_certificate_path . '/' . $mem_data[0]['qualification_certificate_file']))
                                { 
                                  $extention = strtolower(pathinfo($qualification_certificate_path . '/' . $mem_data[0]['qualification_certificate_file'], PATHINFO_EXTENSION));
                                  if($extention == "pdf"){
                                  ?>  
                                  <a data-fancybox data-type="iframe" 
                                     data-src="https://mozilla.github.io/pdf.js/web/viewer.html?file=<?php echo base_url($qualification_certificate_path . '/' . $mem_data[0]['qualification_certificate_file']) . "?" . time(); ?>" href="javascript:;" data-caption="<?php echo 'Qualification Certificate File - '.$disp_name.' ('.$mem_data[0]['regnumber'].')'; ?>">
                                  <div id="qualification_certificate_file_preview" class="upload_img_preview" style="margin:0 auto;"> 
                                      <img src="<?php echo base_url('assets/images/pdf.png'); ?>" width="80" height="80" alt="PDF"> 
                                  </div> 
                                  </a> 
                                  <?php
                                  }else{
                                    ?>
                                  <div id="qualification_certificate_file_preview" class="upload_img_preview" style="margin:0 auto;">
                                    <a href="<?php echo base_url($qualification_certificate_path . '/' . $mem_data[0]['qualification_certificate_file']) . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Qualification Certificate File - '.$disp_name.' ('.$mem_data[0]['training_id'].')'; ?>">
                                      <img src="<?php echo base_url($qualification_certificate_path . '/' . $mem_data[0]['qualification_certificate_file']) . "?" . time(); ?>">
                                    </a>
                                  </div>
                                  <?php } ?>

                                <?php } else { $empty_qualification_certificate_file_flag = '1'; } ?>
                              </td>

                              <td class="text-center <?php echo $exp_certificate_show_hide; ?>"><?php /* exp_certificate */ ?>
                                <?php if ($mem_data[0]['exp_certificate'] != "" && file_exists($exp_certificate_path . '/' . $mem_data[0]['exp_certificate']))
                                { 
                                  $extention = strtolower(pathinfo($exp_certificate_path . '/' . $mem_data[0]['exp_certificate'], PATHINFO_EXTENSION));
                                  if($extention == "pdf"){
                                  ?>  
                                  <a data-fancybox data-type="iframe" 
                                     data-src="https://mozilla.github.io/pdf.js/web/viewer.html?file=<?php echo base_url($exp_certificate_path . '/' . $mem_data[0]['exp_certificate']) . "?" . time(); ?>" href="javascript:;" data-caption="<?php echo 'Experience Certificate - '.$disp_name.' ('.$mem_data[0]['regnumber'].')'; ?>">
                                    <div id="exp_certificate_preview" class="upload_img_preview" style="margin:0 auto;"> 
                                      <img src="<?php echo base_url('assets/images/pdf.png'); ?>" width="80" height="80" alt="PDF"> 
                                    </div> 
                                  </a> 
                                  <?php
                                  }else{
                                    ?>
                                  <div id="exp_certificate_preview" class="upload_img_preview" style="margin:0 auto;">
                                    <a href="<?php echo base_url($exp_certificate_path . '/' . $mem_data[0]['exp_certificate']) . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Experience Certificate - '.$disp_name.' ('.$mem_data[0]['training_id'].')'; ?>">
                                      <img src="<?php echo base_url($exp_certificate_path . '/' . $mem_data[0]['exp_certificate']) . "?" . time(); ?>">
                                    </a>
                                  </div>
                                  <?php } ?>
                                  
                                <?php } else { $empty_exp_certificate_flag = '1'; } ?>
                              </td>

                              <td class="text-center <?php echo $vis_imp_cert_img_show_hide; ?>"><?php /* vis_imp_cert_img */ ?>
                                <?php if ($mem_data[0]['vis_imp_cert_img'] != "" && file_exists($disability_path . '/' . $mem_data[0]['vis_imp_cert_img']))
                                { 
                                  $extention = strtolower(pathinfo($disability_path . '/' . $mem_data[0]['vis_imp_cert_img'], PATHINFO_EXTENSION));
                                  if($extention == "pdf"){
                                  ?>  
                                  <a data-fancybox data-type="iframe" 
                                     data-src="https://mozilla.github.io/pdf.js/web/viewer.html?file=<?php echo base_url($disability_path . '/' . $mem_data[0]['vis_imp_cert_img']) . "?" . time(); ?>" href="javascript:;" data-caption="<?php echo 'Visually impaired Certificate - '.$disp_name.' ('.$mem_data[0]['regnumber'].')'; ?>">
                                    <div id="vis_imp_cert_img_preview" class="upload_img_preview" style="margin:0 auto;"> 
                                      <img src="<?php echo base_url('assets/images/pdf.png'); ?>" width="80" height="80" alt="PDF"> 
                                    </div> 
                                  </a> 
                                  <?php
                                  }else{
                                    ?>
                                  <div id="vis_imp_cert_img_preview" class="upload_img_preview" style="margin:0 auto;">
                                    <a href="<?php echo base_url($disability_path . '/' . $mem_data[0]['vis_imp_cert_img']) . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Visually impaired Certificate - '.$disp_name.' ('.$mem_data[0]['regnumber'].')'; ?>">
                                      <img src="<?php echo base_url($disability_path . '/' . $mem_data[0]['vis_imp_cert_img']) . "?" . time(); ?>">
                                    </a>
                                  </div>
                                <?php } ?>

                                <?php } else { $empty_vis_imp_cert_img_flag = '1'; } ?>
                              </td>

                              <td class="text-center <?php echo $orth_han_cert_img_show_hide; ?>"><?php /* orth_han_cert_img */ ?>
                                <?php if ($mem_data[0]['orth_han_cert_img'] != "" && file_exists($disability_path . '/' . $mem_data[0]['orth_han_cert_img']))
                                { 
                                  $extention = strtolower(pathinfo($disability_path . '/' . $mem_data[0]['orth_han_cert_img'], PATHINFO_EXTENSION));
                                  if($extention == "pdf"){
                                  ?>  
                                  <a data-fancybox data-type="iframe" 
                                     data-src="https://mozilla.github.io/pdf.js/web/viewer.html?file=<?php echo base_url($disability_path . '/' . $mem_data[0]['orth_han_cert_img']) . "?" . time(); ?>" href="javascript:;" data-caption="<?php echo 'Orthopedically handicapped Certificate - '.$disp_name.' ('.$mem_data[0]['regnumber'].')'; ?>">
                                    <div id="orth_han_cert_img_preview" class="upload_img_preview" style="margin:0 auto;"> 
                                      <img src="<?php echo base_url('assets/images/pdf.png'); ?>" width="80" height="80" alt="PDF"> 
                                    </div> 
                                  </a> 
                                  <?php
                                  }else{
                                    ?>
                                  <div id="orth_han_cert_img_preview" class="upload_img_preview" style="margin:0 auto;">
                                    <a href="<?php echo base_url($disability_path . '/' . $mem_data[0]['orth_han_cert_img']) . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Orthopedically handicapped Certificate - '.$disp_name.' ('.$mem_data[0]['regnumber'].')'; ?>">
                                      <img src="<?php echo base_url($disability_path . '/' . $mem_data[0]['orth_han_cert_img']) . "?" . time(); ?>">
                                    </a>
                                  </div>
                                <?php } ?>

                                <?php } else { $empty_orth_han_cert_img_flag = '1'; } ?>
                              </td>

                              <td class="text-center <?php echo $cer_palsy_cert_img_show_hide; ?>"><?php /* cer_palsy_cert_img */ ?>
                                <?php if ($mem_data[0]['cer_palsy_cert_img'] != "" && file_exists($disability_path . '/' . $mem_data[0]['cer_palsy_cert_img']))
                                { 
                                  $extention = strtolower(pathinfo($disability_path . '/' . $mem_data[0]['cer_palsy_cert_img'], PATHINFO_EXTENSION));
                                  if($extention == "pdf"){
                                  ?>  
                                  <a data-fancybox data-type="iframe" 
                                     data-src="https://mozilla.github.io/pdf.js/web/viewer.html?file=<?php echo base_url($disability_path . '/' . $mem_data[0]['cer_palsy_cert_img']) . "?" . time(); ?>" href="javascript:;" data-caption="<?php echo 'Cerebral palsy Certificate - '.$disp_name.' ('.$mem_data[0]['regnumber'].')'; ?>">
                                    <div id="cer_palsy_cert_img_preview" class="upload_img_preview" style="margin:0 auto;"> 
                                      <img src="<?php echo base_url('assets/images/pdf.png'); ?>" width="80" height="80" alt="PDF"> 
                                    </div> 
                                  </a> 
                                  <?php
                                  }else{
                                    ?>
                                  <div id="cer_palsy_cert_img_preview" class="upload_img_preview" style="margin:0 auto;">
                                    <a href="<?php echo base_url($disability_path . '/' . $mem_data[0]['cer_palsy_cert_img']) . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Cerebral palsy Certificate - '.$disp_name.' ('.$mem_data[0]['regnumber'].')'; ?>">
                                      <img src="<?php echo base_url($disability_path . '/' . $mem_data[0]['cer_palsy_cert_img']) . "?" . time(); ?>">
                                    </a>
                                  </div>
                                <?php } ?>

                                <?php } else { $empty_cer_palsy_cert_img_flag = '1'; } ?>
                              </td>

                            </tr>
                            
                            <tr>
                              <td><?php /* Name */ ?>
                                <?php 
                                $kyc_radio_btn_details_fullname = get_kyc_radio_btn_details($mem_data[0],'kyc_fullname_flag');
                                $approve_check = $kyc_radio_btn_details_fullname['approve_check'];
                                $reject_check = $kyc_radio_btn_details_fullname['reject_check'];
                                $approve_cls = $kyc_radio_btn_details_fullname['approve_cls'];
                                $reject_cls = $kyc_radio_btn_details_fullname['reject_cls'];
                                $disabled_flg  = 'disabled'; //$kyc_radio_btn_details_fullname['disabled_flg']; ?>
        
                                <div id="fullname_kyc_err" class="kyc_radio_outer" title="<?php //echo "<pre>"; print_r($kyc_radio_btn_details_fullname); ?>"> 
                                  <?php if($empty_fullname_flag == '') 
                                  { ?>                               
                                    <label class="css_checkbox_radio radio_only kyc_appprove_radio <?php echo $approve_cls; ?> <?php echo $disabled_flg; ?>"> <?php if($disabled_flg == '') { echo 'Approve'; } else { echo 'Approved'; } ?>
                                      <input type="radio" disabled value="Y" name="fullname_kyc" required <?php echo $approve_check; ?>><span class="radiobtn"></span>
                                    </label>

                                    <label class="css_checkbox_radio radio_only kyc_reject_radio <?php echo $reject_cls; ?> <?php echo $disabled_flg; ?>"> Reject
                                      <input type="radio" disabled value="N" name="fullname_kyc" required <?php echo $reject_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  <?php }
                                  else
                                  { ?>
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio"> Reject
                                      <input type="radio" disabled value="N" name="fullname_kyc" required checked><span class="radiobtn"></span>
                                    </label>
                                  <?php } ?>
                                </div>
                                <?php if (form_error('fullname_kyc') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('fullname_kyc'); ?></label> <?php } ?>
                              </td>
                              <td></td><?php /* Regnumber */ ?>
                              <!-- <td></td> --><?php /* Training ID */ ?>
                              <td><?php /* Birth Date */ ?>
                                <?php 
                                $kyc_radio_btn_details_dob = get_kyc_radio_btn_details($mem_data[0],'kyc_dob_flag');
                                $approve_check = $kyc_radio_btn_details_dob['approve_check'];
                                $reject_check = $kyc_radio_btn_details_dob['reject_check'];
                                $approve_cls = $kyc_radio_btn_details_dob['approve_cls'];
                                $reject_cls = $kyc_radio_btn_details_dob['reject_cls'];
                                $disabled_flg = 'disabled'; //$kyc_radio_btn_details_dob['disabled_flg']; ?>
        
                                <div id="dob_kyc_err" class="kyc_radio_outer" title="<?php //echo "<pre>"; print_r($kyc_radio_btn_details_dob); ?>"> 
                                  <?php if($empty_dob_flag == '') 
                                  { ?>                               
                                    <label class="css_checkbox_radio radio_only kyc_appprove_radio <?php echo $approve_cls; ?> <?php echo $disabled_flg; ?>"> <?php if($disabled_flg == '') { echo 'Approve'; } else { echo 'Approved'; } ?>
                                      <input type="radio" disabled value="Y" name="dob_kyc" required <?php echo $approve_check; ?>><span class="radiobtn"></span>
                                    </label>

                                    <label class="css_checkbox_radio radio_only kyc_reject_radio <?php echo $reject_cls; ?> <?php echo $disabled_flg; ?>"> Reject
                                      <input type="radio" disabled value="N" name="dob_kyc" required <?php echo $reject_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  <?php }
                                  else
                                  { ?>
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio"> Reject
                                      <input type="radio" disabled value="N" name="dob_kyc" required checked><span class="radiobtn"></span>
                                    </label>
                                  <?php } ?>
                                </div>
                                <?php if (form_error('dob_kyc') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('dob_kyc'); ?></label> <?php } ?>
                              </td>
                              
                              <td><?php /* aadhar_no */ ?>
                                <?php 
                                $kyc_radio_btn_details_aadhar = get_kyc_radio_btn_details($mem_data[0],'kyc_aadhar_flag');
                                $approve_check = $kyc_radio_btn_details_aadhar['approve_check'];
                                $reject_check = $kyc_radio_btn_details_aadhar['reject_check'];
                                $approve_cls = $kyc_radio_btn_details_aadhar['approve_cls'];
                                $reject_cls = $kyc_radio_btn_details_aadhar['reject_cls'];
                                $disabled_flg = 'disabled'; //$kyc_radio_btn_details_aadhar['disabled_flg']; ?>
        
                                <div id="aadhar_kyc_err" class="kyc_radio_outer" title="<?php //echo "<pre>"; print_r($kyc_radio_btn_details_aadhar); ?>"> 
                                  <?php if($empty_aadhar_flag == '') 
                                  { ?>                               
                                    <label class="css_checkbox_radio radio_only kyc_appprove_radio <?php echo $approve_cls; ?> <?php echo $disabled_flg; ?>"> <?php if($disabled_flg == '') { echo 'Approve'; } else { echo 'Approved'; } ?>
                                      <input type="radio" disabled value="Y" name="aadhar_kyc" required <?php echo $approve_check; ?>><span class="radiobtn"></span>
                                    </label>

                                    <label class="css_checkbox_radio radio_only kyc_reject_radio <?php echo $reject_cls; ?> <?php echo $disabled_flg; ?>"> Reject
                                      <input type="radio" disabled value="N" name="aadhar_kyc" required <?php echo $reject_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  <?php }
                                  else
                                  { ?>
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio"> Reject
                                      <input type="radio" disabled value="N" name="aadhar_kyc" required checked><span class="radiobtn"></span>
                                    </label>
                                  <?php } ?>
                                </div>
                                <?php if (form_error('aadhar_kyc') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('aadhar_kyc'); ?></label> <?php } ?>
                              </td>
 
                              <td><?php /* id_proof_number / Apaar ID */ ?>
                                <?php 
                                $kyc_radio_btn_details_apaar = get_kyc_radio_btn_details($mem_data[0],'kyc_apaar_flag');
                                $approve_check = $kyc_radio_btn_details_apaar['approve_check'];
                                $reject_check = $kyc_radio_btn_details_apaar['reject_check'];
                                $approve_cls = $kyc_radio_btn_details_apaar['approve_cls'];
                                $reject_cls = $kyc_radio_btn_details_apaar['reject_cls'];
                                $disabled_flg = 'disabled'; //$kyc_radio_btn_details_apaar['disabled_flg']; ?>
        
                                <div id="apaar_kyc_err" class="kyc_radio_outer" title="<?php //echo "<pre>"; print_r($kyc_radio_btn_details_apaar); ?>"> 
                                  <?php if($empty_apaar_flag == '') 
                                  { ?>                               
                                    <label class="css_checkbox_radio radio_only kyc_appprove_radio <?php echo $approve_cls; ?> <?php echo $disabled_flg; ?>"> <?php if($disabled_flg == '') { echo 'Approve'; } else { echo 'Approved'; } ?>
                                      <input type="radio" disabled value="Y" name="apaar_kyc" required <?php echo $approve_check; ?>><span class="radiobtn"></span>
                                    </label>

                                    <label class="css_checkbox_radio radio_only kyc_reject_radio <?php echo $reject_cls; ?> <?php echo $disabled_flg; ?>"> Reject
                                      <input type="radio" disabled value="N" name="apaar_kyc" required <?php echo $reject_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  <?php }
                                  else
                                  { ?>
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio"> Reject
                                      <input type="radio" disabled value="N" name="apaar_kyc" required checked><span class="radiobtn"></span>
                                    </label>
                                  <?php } ?>
                                </div>
                                <?php if (form_error('apaar_kyc') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('apaar_kyc'); ?></label> <?php } ?>
                              </td> 
                               
                              <td><?php /* Qualification Eligibility */ ?>
                                <?php 
                                $kyc_radio_btn_details_eligibility = get_kyc_radio_btn_details($mem_data[0],'kyc_eligibility_flag');
                                $approve_check = $kyc_radio_btn_details_eligibility['approve_check'];
                                $reject_check = $kyc_radio_btn_details_eligibility['reject_check'];
                                $approve_cls = $kyc_radio_btn_details_eligibility['approve_cls'];
                                $reject_cls = $kyc_radio_btn_details_eligibility['reject_cls'];
                                $disabled_flg = 'disabled'; //$kyc_radio_btn_details_eligibility['disabled_flg']; ?>
        
                                <div id="eligibility_kyc_err" class="kyc_radio_outer" title="<?php //echo "<pre>"; print_r($kyc_radio_btn_details_eligibility); ?>"> 
                                  <?php if($empty_eligibility_flag == '') 
                                  { ?>                               
                                    <label class="css_checkbox_radio radio_only kyc_appprove_radio <?php echo $approve_cls; ?> <?php echo $disabled_flg; ?>"> <?php if($disabled_flg == '') { echo 'Approve'; } else { echo 'Approved'; } ?>
                                      <input type="radio" disabled value="Y" name="eligibility_kyc" required <?php echo $approve_check; ?>><span class="radiobtn"></span>
                                    </label>

                                    <label class="css_checkbox_radio radio_only kyc_reject_radio <?php echo $reject_cls; ?> <?php echo $disabled_flg; ?>"> Reject
                                      <input type="radio" disabled value="N" name="eligibility_kyc" required <?php echo $reject_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  <?php }
                                  else
                                  { ?>
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio"> Reject
                                      <input type="radio" disabled value="N" name="eligibility_kyc" required checked><span class="radiobtn"></span>
                                    </label>
                                  <?php } ?>
                                </div>
                                <?php if (form_error('eligibility_kyc') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('eligibility_kyc'); ?></label> <?php } ?>
                              </td>

                              <td><?php /* Photo */ ?>
                                <?php 
                                $kyc_radio_btn_details_photo = get_kyc_radio_btn_details($mem_data[0],'kyc_photo_flag');
                                $approve_check = $kyc_radio_btn_details_photo['approve_check'];
                                $reject_check = $kyc_radio_btn_details_photo['reject_check'];
                                $approve_cls = $kyc_radio_btn_details_photo['approve_cls'];
                                $reject_cls = $kyc_radio_btn_details_photo['reject_cls'];
                                $disabled_flg = 'disabled'; //$kyc_radio_btn_details_photo['disabled_flg']; ?>
        
                                <div id="photo_file_kyc_err" class="kyc_radio_outer"> 
                                  <?php if($empty_photo_flag == '') 
                                  { ?>                               
                                    <label class="css_checkbox_radio radio_only kyc_appprove_radio <?php echo $approve_cls; ?> <?php echo $disabled_flg; ?>"> <?php if($disabled_flg == '') { echo 'Approve'; } else { echo 'Approved'; } ?>
                                      <input type="radio" disabled value="Y" name="photo_file_kyc" required <?php echo $approve_check; ?>><span class="radiobtn"></span>
                                    </label>

                                    <label class="css_checkbox_radio radio_only kyc_reject_radio <?php echo $reject_cls; ?> <?php echo $disabled_flg; ?>"> Reject
                                      <input type="radio" disabled value="N" name="photo_file_kyc" required <?php echo $reject_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  <?php }
                                  else
                                  { ?>
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio"> Reject
                                      <input type="radio" disabled value="N" name="photo_file_kyc" required checked><span class="radiobtn"></span>
                                    </label>
                                  <?php } ?>
                                </div>
                                <?php if (form_error('photo_file_kyc') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('photo_file_kyc'); ?></label> <?php } ?>
                              </td>

                              <td><?php /* Signature */ ?>
                                <?php 
                                $kyc_radio_btn_details_sign = get_kyc_radio_btn_details($mem_data[0],'kyc_sign_flag');
                                $approve_check = $kyc_radio_btn_details_sign['approve_check'];
                                $reject_check = $kyc_radio_btn_details_sign['reject_check'];
                                $approve_cls = $kyc_radio_btn_details_sign['approve_cls'];
                                $reject_cls = $kyc_radio_btn_details_sign['reject_cls'];
                                $disabled_flg = 'disabled'; //$kyc_radio_btn_details_sign['disabled_flg']; ?>
                                
                                <div id="sign_file_kyc_err" class="kyc_radio_outer">   
                                  <?php if($empty_sign_flag == '') 
                                  { ?>                                                           
                                    <label class="css_checkbox_radio radio_only kyc_appprove_radio <?php echo $approve_cls; ?> <?php echo $disabled_flg; ?>"> <?php if($disabled_flg == '') { echo 'Approve'; } else { echo 'Approved'; } ?>
                                      <input type="radio" disabled value="Y" name="sign_file_kyc" required <?php echo $approve_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio <?php echo $reject_cls; ?> <?php echo $disabled_flg; ?>"> Reject
                                      <input type="radio" disabled value="N" name="sign_file_kyc" required <?php echo $reject_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  <?php }
                                  else
                                  { ?>
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio"> Reject
                                      <input type="radio" disabled value="N" name="sign_file_kyc" required checked><span class="radiobtn"></span>
                                    </label>
                                  <?php } ?>
                                </div>
                                <?php if (form_error('sign_file_kyc') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('sign_file_kyc'); ?></label> <?php } ?>
                              </td>
                            
                              <td><?php /* APAAR ID/ABC ID */ ?>
                                <?php 
                                $kyc_radio_btn_details_id_proof = get_kyc_radio_btn_details($mem_data[0],'kyc_id_card_flag');
                                $approve_check = $kyc_radio_btn_details_id_proof['approve_check'];
                                $reject_check = $kyc_radio_btn_details_id_proof['reject_check'];
                                $approve_cls = $kyc_radio_btn_details_id_proof['approve_cls'];
                                $reject_cls = $kyc_radio_btn_details_id_proof['reject_cls'];
                                $disabled_flg = 'disabled'; //$kyc_radio_btn_details_id_proof['disabled_flg']; ?>
                                
                                <div id="id_proof_file_kyc_err" class="kyc_radio_outer">
                                  <?php if($empty_id_proof_flag == '') 
                                  { ?>                                
                                    <label class="css_checkbox_radio radio_only kyc_appprove_radio <?php echo $approve_cls; ?> <?php echo $disabled_flg; ?>"> <?php if($disabled_flg == '') { echo 'Approve'; } else { echo 'Approved'; } ?>
                                      <input type="radio" disabled value="Y" name="id_proof_file_kyc" required <?php echo $approve_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio <?php echo $reject_cls; ?> <?php echo $disabled_flg; ?>"> Reject
                                      <input type="radio" disabled value="N" name="id_proof_file_kyc" required <?php echo $reject_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  <?php }
                                  else
                                  { ?>
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio"> Reject
                                      <input type="radio" disabled value="N" name="id_proof_file_kyc" required checked><span class="radiobtn"></span>
                                    </label>
                                  <?php } ?>
                                </div>
                                <?php if (form_error('id_proof_file_kyc') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('id_proof_file_kyc'); ?></label> <?php } ?>
                              </td>

                              <td><?php /* Aadhar Card */ ?>
                                <?php 
                                $kyc_radio_btn_details_aadhar_file = get_kyc_radio_btn_details($mem_data[0],'kyc_aadhar_file_flag');
                                $approve_check = $kyc_radio_btn_details_aadhar_file['approve_check'];
                                $reject_check = $kyc_radio_btn_details_aadhar_file['reject_check'];
                                $approve_cls = $kyc_radio_btn_details_aadhar_file['approve_cls'];
                                $reject_cls = $kyc_radio_btn_details_aadhar_file['reject_cls'];
                                $disabled_flg = 'disabled'; //$kyc_radio_btn_details_aadhar_file['disabled_flg']; ?>
                                
                                <div id="aadhar_file_kyc_err" class="kyc_radio_outer">
                                  <?php if($empty_aadhar_file_flag == '') 
                                  { ?>                                
                                    <label class="css_checkbox_radio radio_only kyc_appprove_radio <?php echo $approve_cls; ?> <?php echo $disabled_flg; ?>"> <?php if($disabled_flg == '') { echo 'Approve'; } else { echo 'Approved'; } ?>
                                      <input type="radio" disabled value="Y" name="aadhar_file_kyc" required <?php echo $approve_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio <?php echo $reject_cls; ?> <?php echo $disabled_flg; ?>"> Reject
                                      <input type="radio" disabled value="N" name="aadhar_file_kyc" required <?php echo $reject_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  <?php }
                                  else
                                  { ?>
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio"> Reject
                                      <input type="radio" disabled value="N" name="aadhar_file_kyc" required checked><span class="radiobtn"></span>
                                    </label>
                                  <?php } ?>
                                </div>
                                <?php if (form_error('aadhar_file_kyc') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('aadhar_file_kyc'); ?></label> <?php } ?>
                              </td>
                              
                              <td class="<?php echo $declaration_show_hide; ?>"><?php /* Declaration */ ?>
                                <?php 
                                $kyc_radio_btn_details_declaration = get_kyc_radio_btn_details($mem_data[0],'kyc_declaration_flag');
                                $approve_check = $kyc_radio_btn_details_declaration['approve_check'];
                                $reject_check = $kyc_radio_btn_details_declaration['reject_check'];
                                $approve_cls = $kyc_radio_btn_details_declaration['approve_cls'];
                                $reject_cls = $kyc_radio_btn_details_declaration['reject_cls'];
                                $disabled_flg = 'disabled'; //$kyc_radio_btn_details_declaration['disabled_flg']; ?>
                                
                                <div id="declaration_file_kyc_err" class="kyc_radio_outer">
                                  <?php if($empty_declaration_flag == '') 
                                  { ?>                                
                                    <label class="css_checkbox_radio radio_only kyc_appprove_radio <?php echo $approve_cls; ?> <?php echo $disabled_flg; ?>"> <?php if($disabled_flg == '') { echo 'Approve'; } else { echo 'Approved'; } ?>
                                      <input type="radio" disabled value="Y" name="declaration_file_kyc" required <?php echo $approve_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio <?php echo $reject_cls; ?> <?php echo $disabled_flg; ?>"> Reject
                                      <input type="radio" disabled value="N" name="declaration_file_kyc" required <?php echo $reject_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  <?php }
                                  else
                                  { ?>
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio"> Reject
                                      <input type="radio" disabled value="N" name="declaration_file_kyc" required checked><span class="radiobtn"></span>
                                    </label>
                                  <?php } ?>
                                </div>
                                <?php if (form_error('declaration_file_kyc') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('declaration_file_kyc'); ?></label> <?php } ?>
                              </td>


                              <td class="<?php echo $institute_idproof_show_hide; ?>"><?php /* institute_idproof */ ?>
                                <?php 
                                $kyc_radio_btn_details_institute_idproof = get_kyc_radio_btn_details($mem_data[0],'kyc_institute_idproof_flag');
                                $approve_check = $kyc_radio_btn_details_institute_idproof['approve_check'];
                                $reject_check = $kyc_radio_btn_details_institute_idproof['reject_check'];
                                $approve_cls = $kyc_radio_btn_details_institute_idproof['approve_cls'];
                                $reject_cls = $kyc_radio_btn_details_institute_idproof['reject_cls'];
                                $disabled_flg = 'disabled'; //$kyc_radio_btn_details_institute_idproof['disabled_flg']; ?>
                                
                                <div id="institute_idproof_file_kyc_err" class="kyc_radio_outer <?php echo $institute_idproof_show_hide; ?>">
                                  <?php if($empty_institute_idproof_flag == '') 
                                  { ?>                                
                                    <label class="css_checkbox_radio radio_only kyc_appprove_radio <?php echo $approve_cls; ?> <?php echo $disabled_flg; ?>"> <?php if($disabled_flg == '') { echo 'Approve'; } else { echo 'Approved'; } ?>
                                      <input type="radio" disabled value="Y" name="institute_idproof_file_kyc" required <?php echo $approve_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio <?php echo $reject_cls; ?> <?php echo $disabled_flg; ?>"> Reject
                                      <input type="radio" disabled value="N" name="institute_idproof_file_kyc" required <?php echo $reject_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  <?php }
                                  else
                                  { ?>
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio"> Reject
                                      <input type="radio" disabled value="N" name="institute_idproof_file_kyc" required checked><span class="radiobtn"></span>
                                    </label>
                                  <?php } ?>
                                </div>
                                <?php if (form_error('institute_idproof_file_kyc') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('institute_idproof_file_kyc'); ?></label> <?php } ?>
                              </td>

                              <td class="<?php echo $qualification_certificate_file_show_hide; ?>"><?php /* qualification_certificate_file */ ?>
                                <?php 
                                $kyc_radio_btn_details_qual_cert = get_kyc_radio_btn_details($mem_data[0],'kyc_qualification_cert_flag');
                                $approve_check = $kyc_radio_btn_details_qual_cert['approve_check'];
                                $reject_check = $kyc_radio_btn_details_qual_cert['reject_check'];
                                $approve_cls = $kyc_radio_btn_details_qual_cert['approve_cls'];
                                $reject_cls = $kyc_radio_btn_details_qual_cert['reject_cls'];
                                $disabled_flg = 'disabled'; //$kyc_radio_btn_details_qual_cert['disabled_flg']; ?>
                                
                                <div id="qualification_certificate_file_kyc_err" class="kyc_radio_outer <?php echo $qualification_certificate_file_show_hide; ?>">
                                  <?php if($empty_qualification_certificate_file_flag == '') 
                                  { ?>                                
                                    <label class="css_checkbox_radio radio_only kyc_appprove_radio <?php echo $approve_cls; ?> <?php echo $disabled_flg; ?>"> <?php if($disabled_flg == '') { echo 'Approve'; } else { echo 'Approved'; } ?>
                                      <input type="radio" disabled value="Y" name="qualification_certificate_file_kyc" required <?php echo $approve_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio <?php echo $reject_cls; ?> <?php echo $disabled_flg; ?>"> Reject
                                      <input type="radio" disabled value="N" name="qualification_certificate_file_kyc" required <?php echo $reject_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  <?php }
                                  else
                                  { ?>
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio"> Reject
                                      <input type="radio" disabled value="N" name="qualification_certificate_file_kyc" required checked><span class="radiobtn"></span>
                                    </label>
                                  <?php } ?>
                                </div>
                                <?php if (form_error('qualification_certificate_file_kyc') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('qualification_certificate_file_kyc'); ?></label> <?php } ?>
                              </td>

                              <td class="<?php echo $exp_certificate_show_hide; ?>"><?php /* exp_certificate */ ?>
                                <?php 
                                $kyc_radio_btn_details_exp_certificate = get_kyc_radio_btn_details($mem_data[0],'kyc_exp_certificate_flag');
                                $approve_check = $kyc_radio_btn_details_exp_certificate['approve_check'];
                                $reject_check = $kyc_radio_btn_details_exp_certificate['reject_check'];
                                $approve_cls = $kyc_radio_btn_details_exp_certificate['approve_cls'];
                                $reject_cls = $kyc_radio_btn_details_exp_certificate['reject_cls'];
                                $disabled_flg = 'disabled'; //$kyc_radio_btn_details_exp_certificate['disabled_flg']; ?>
                                
                                <div id="exp_certificate_file_kyc_err" class="kyc_radio_outer <?php echo $exp_certificate_show_hide; ?>">
                                  <?php if($empty_exp_certificate_flag == '') 
                                  { ?>                                
                                    <label class="css_checkbox_radio radio_only kyc_appprove_radio <?php echo $approve_cls; ?> <?php echo $disabled_flg; ?>"> <?php if($disabled_flg == '') { echo 'Approve'; } else { echo 'Approved'; } ?>
                                      <input type="radio" disabled value="Y" name="exp_certificate_file_kyc" required <?php echo $approve_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio <?php echo $reject_cls; ?> <?php echo $disabled_flg; ?>"> Reject
                                      <input type="radio" disabled value="N" name="exp_certificate_file_kyc" required <?php echo $reject_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  <?php }
                                  else
                                  { ?>
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio"> Reject
                                      <input type="radio" disabled value="N" name="exp_certificate_file_kyc" required checked><span class="radiobtn"></span>
                                    </label>
                                  <?php } ?>
                                </div>
                                <?php if (form_error('exp_certificate_file_kyc') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('exp_certificate_file_kyc'); ?></label> <?php } ?>
                              </td>

                              <td class="<?php echo $vis_imp_cert_img_show_hide; ?>"><?php /* vis_imp_cert_img */ ?>
                                <?php 
                                $kyc_radio_btn_details_vis_imp_cert_img = get_kyc_radio_btn_details($mem_data[0],'kyc_vis_imp_cert_flag');
                                $approve_check = $kyc_radio_btn_details_vis_imp_cert_img['approve_check'];
                                $reject_check = $kyc_radio_btn_details_vis_imp_cert_img['reject_check'];
                                $approve_cls = $kyc_radio_btn_details_vis_imp_cert_img['approve_cls'];
                                $reject_cls = $kyc_radio_btn_details_vis_imp_cert_img['reject_cls'];
                                $disabled_flg = 'disabled'; //$kyc_radio_btn_details_vis_imp_cert_img['disabled_flg']; ?>
                                
                                <div id="vis_imp_cert_img_file_kyc_err" class="kyc_radio_outer <?php echo $vis_imp_cert_img_show_hide; ?>">
                                  <?php if($empty_vis_imp_cert_img_flag == '') 
                                  { ?>                                
                                    <label class="css_checkbox_radio radio_only kyc_appprove_radio <?php echo $approve_cls; ?> <?php echo $disabled_flg; ?>"> <?php if($disabled_flg == '') { echo 'Approve'; } else { echo 'Approved'; } ?>
                                      <input type="radio" disabled value="Y" name="vis_imp_cert_img_file_kyc" required <?php echo $approve_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio <?php echo $reject_cls; ?> <?php echo $disabled_flg; ?>"> Reject
                                      <input type="radio" disabled value="N" name="vis_imp_cert_img_file_kyc" required <?php echo $reject_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  <?php }
                                  else
                                  { ?>
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio"> Reject
                                      <input type="radio" disabled value="N" name="vis_imp_cert_img_file_kyc" required checked><span class="radiobtn"></span>
                                    </label>
                                  <?php } ?>
                                </div>
                                <?php if (form_error('vis_imp_cert_img_file_kyc') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('vis_imp_cert_img_file_kyc'); ?></label> <?php } ?>
                              </td>

                              <td class="<?php echo $orth_han_cert_img_show_hide; ?>"><?php /* orth_han_cert_img */ ?>
                                <?php 
                                $kyc_radio_btn_details_orth_han_cert_img = get_kyc_radio_btn_details($mem_data[0],'kyc_orth_han_cert_flag');
                                $approve_check = $kyc_radio_btn_details_orth_han_cert_img['approve_check'];
                                $reject_check = $kyc_radio_btn_details_orth_han_cert_img['reject_check'];
                                $approve_cls = $kyc_radio_btn_details_orth_han_cert_img['approve_cls'];
                                $reject_cls = $kyc_radio_btn_details_orth_han_cert_img['reject_cls'];
                                $disabled_flg = 'disabled'; //$kyc_radio_btn_details_orth_han_cert_img['disabled_flg']; ?>
                                
                                <div id="orth_han_cert_img_file_kyc_err" class="kyc_radio_outer <?php echo $orth_han_cert_img_show_hide; ?>">
                                  <?php if($empty_orth_han_cert_img_flag == '') 
                                  { ?>                                
                                    <label class="css_checkbox_radio radio_only kyc_appprove_radio <?php echo $approve_cls; ?> <?php echo $disabled_flg; ?>"> <?php if($disabled_flg == '') { echo 'Approve'; } else { echo 'Approved'; } ?>
                                      <input type="radio" disabled value="Y" name="orth_han_cert_img_file_kyc" required <?php echo $approve_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio <?php echo $reject_cls; ?> <?php echo $disabled_flg; ?>"> Reject
                                      <input type="radio" disabled value="N" name="orth_han_cert_img_file_kyc" required <?php echo $reject_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  <?php }
                                  else
                                  { ?>
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio"> Reject
                                      <input type="radio" disabled value="N" name="orth_han_cert_img_file_kyc" required checked><span class="radiobtn"></span>
                                    </label>
                                  <?php } ?>
                                </div>
                                <?php if (form_error('orth_han_cert_img_file_kyc') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('orth_han_cert_img_file_kyc'); ?></label> <?php } ?>
                              </td>

                              <td class="<?php echo $cer_palsy_cert_img_show_hide; ?>"><?php /* cer_palsy_cert_img */ ?>
                                <?php 
                                $kyc_radio_btn_details_cer_palsy_cert_img = get_kyc_radio_btn_details($mem_data[0],'kyc_cer_palsy_cert_flag');
                                $approve_check = $kyc_radio_btn_details_cer_palsy_cert_img['approve_check'];
                                $reject_check = $kyc_radio_btn_details_cer_palsy_cert_img['reject_check'];
                                $approve_cls = $kyc_radio_btn_details_cer_palsy_cert_img['approve_cls'];
                                $reject_cls = $kyc_radio_btn_details_cer_palsy_cert_img['reject_cls'];
                                $disabled_flg = 'disabled'; //$kyc_radio_btn_details_cer_palsy_cert_img['disabled_flg']; ?>
                                
                                <div id="cer_palsy_cert_img_file_kyc_err" class="kyc_radio_outer <?php echo $cer_palsy_cert_img_show_hide; ?>">
                                  <?php if($empty_cer_palsy_cert_img_flag == '') 
                                  { ?>                                
                                    <label class="css_checkbox_radio radio_only kyc_appprove_radio <?php echo $approve_cls; ?> <?php echo $disabled_flg; ?>"> <?php if($disabled_flg == '') { echo 'Approve'; } else { echo 'Approved'; } ?>
                                      <input type="radio" disabled value="Y" name="cer_palsy_cert_img_file_kyc" required <?php echo $approve_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio <?php echo $reject_cls; ?> <?php echo $disabled_flg; ?>"> Reject
                                      <input type="radio" disabled value="N" name="cer_palsy_cert_img_file_kyc" required <?php echo $reject_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  <?php }
                                  else
                                  { ?>
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio"> Reject
                                      <input type="radio" disabled value="N" name="cer_palsy_cert_img_file_kyc" required checked><span class="radiobtn"></span>
                                    </label>
                                  <?php } ?>
                                </div>
                                <?php if (form_error('cer_palsy_cert_img_file_kyc') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('cer_palsy_cert_img_file_kyc'); ?></label> <?php } ?>
                              </td>


                            </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                    </div>
                  
                    <?php if(count($mem_data) > 0)
                    { ?>
                      <div class="hr-line-dashed"></div>										
                      <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right" id="submit_btn_outer"> 

                          <?php if(isset($page_url) && $page_url != ""){ 
                            ?>
                            <a class="btn btn-warning" href="<?php echo site_url('ncvet/kyc/benchmark_kyc_all/'.$page_url); ?>">Back</a>
                          <?php
                            }else{
                            ?>
                            <button class="btn btn-warning" type="button" onclick="history.back();">Back</button>
                          <?php
                            }
                          ?>

                          
                        </div>
                      </div>
                    <?php } ?>
                  </form>

								</div>               
							</div>
              <div id="common_log_outer"></div>                  
						</div>
					</div>
				</div>				
				
				<?php $this->load->view('ncvet/kyc/inc_footerbar_admin'); ?>	
			</div>
		</div>
		<?php $this->load->view('ncvet/kyc/inc_footer'); ?>

    <?php 
    $enc_pk_id = '';
    if($module_name == 'bcbf')
    {
      $enc_pk_id = url_encode($mem_data[0]['candidate_id']);
    }
    else if($module_name == 'ncvet')
    {
      $enc_pk_id = url_encode($mem_data[0]['candidate_id']);
    }
    else if($module_name == 'dra')
    {
      $enc_pk_id = url_encode($mem_data[0]['candidate_id']);
    }

    
    $module_slug = 'kyc_recommender_Approved,kyc_recommender_Rejected,kyc_approver_Approved,kyc_approver_Rejected';
    $this->load->view('ncvet/kyc/common/get_logs_common_ajax_call', array('enc_pk_id'=>$enc_pk_id, 'module_slug'=>$module_slug, 'log_title'=>'KYC Logs', 'module_name'=>$module_name));
  ?>
		
    <?php $this->load->view('ncvet/common/inc_common_validation_all'); ?>    
    <script type="text/javascript">  
      function fun_form_action(form_action)
      {
        $("#form_action").val(form_action);
      } 

      //START : JQUERY VALIDATION SCRIPT 
      function validate_input(input_id) { $("#"+input_id).valid(); }
      $(document ).ready( function() 
      {
        var form = $("#kyc_form").validate( 
        {
          onkeyup: function(element) { $(element).valid(); },          
          rules:
          {
            photo_file_kyc:{ required: true, },
            sign_file_kyc:{ required: true, },
            id_proof_file_kyc:{ required: true, },                       
            declaration_file_kyc:{ required: true, },                       
          },
          messages:
          {
            photo_file_kyc: { required: "Please select the option" },
            sign_file_kyc: { required: "Please select the option" },
            id_proof_file_kyc: { required: "Please select the option" },
            declaration_file_kyc: { required: "Please select the option" },
          }, 
          errorPlacement: function(error, element) // For replace error 
          {
            if (element.attr("name") == "photo_file_kyc") { error.insertAfter("#photo_file_kyc_err"); }
            else if (element.attr("name") == "sign_file_kyc") { error.insertAfter("#sign_file_kyc_err"); }
            else if (element.attr("name") == "id_proof_file_kyc") { error.insertAfter("#id_proof_file_kyc_err"); }
            else if (element.attr("name") == "declaration_file_kyc") { error.insertAfter("#declaration_file_kyc_err"); }
            else { error.insertAfter(element); }
          },          
          submitHandler: function(form) 
          {          
            $("#page_loader").hide();
            var photo_file_kyc = $('input[name="photo_file_kyc"]:checked').val();
            var sign_file_kyc = $('input[name="sign_file_kyc"]:checked').val();
            var id_proof_file_kyc = $('input[name="id_proof_file_kyc"]:checked').val();
            var declaration_file_kyc = $('input[name="declaration_file_kyc"]:checked').val();

            var swal_message = "Please confirm to ";
            if (photo_file_kyc === "Y") { swal_message += "Approve Photo"; } 
            else if (photo_file_kyc === "N") { swal_message += "Reject Photo"; } 

            if (sign_file_kyc === "Y") { swal_message += ", Approve Signature"; } 
            else if (sign_file_kyc === "N") { swal_message += ", Reject Signature"; }

            if (id_proof_file_kyc === "Y") { swal_message += ", Approve APAAR ID/ABC ID"; } 
            else if (id_proof_file_kyc === "N") { swal_message += ", Reject APAAR ID/ABC ID"; }

            if (declaration_file_kyc === "Y") { swal_message += ", Approve Declaration"; } 
            else if (declaration_file_kyc === "N") { swal_message += ", Reject Declaration"; }
            
            swal({ title: "Please confirm", text: swal_message+"?", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Confirm", closeOnConfirm: true }, function () 
            { 
              $("#page_loader").show();
              form.submit();
            }); 
          }
        });
      });
    </script>

    <script>
      Fancybox.bind("[data-fancybox]", {
      mainClass: "custom-fancybox",
      autoFocus: false
    });

    /*Fancybox.bind("[data-fancybox]", {
      iframe: {
        preload: false,
        css: {
          width: "40%",   // set width
          height: "40%"  // set height
          ma
        }
      }
    });*/
    </script>

		<?php $this->load->view('ncvet/kyc/common/inc_bottom_script'); ?>
	</body>
</html>