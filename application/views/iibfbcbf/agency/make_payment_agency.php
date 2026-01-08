<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('iibfbcbf/inc_header'); ?>    
  </head>
	
	<body class="fixed-sidebar">
    <?php $this->load->view('iibfbcbf/common/inc_loader'); ?>
		
		<div id="wrapper">
      <?php $this->load->view('iibfbcbf/agency/inc_sidebar_agency'); ?>
			<div id="page-wrapper" class="gray-bg">				
        <?php $this->load->view('iibfbcbf/agency/inc_topbar_agency'); ?>
				
        <div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-12">
						<h2>Make Proforma Invoice</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/agency/dashboard_agency'); ?>">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/agency/apply_exam_agency/candidate_listing/'.$enc_exam_code); ?>">Apply For <?php echo display_exam_name($active_exam_data[0]['description'], $active_exam_data[0]['exam_code'], $active_exam_data[0]['exam_type']); /* helpers/iibfbcbf/iibf_bcbf_helper.php */ ?></a></li>
							<li class="breadcrumb-item active"> <strong>Make Proforma Invoice </strong></li>
						</ol>
					</div>
				</div>
        
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
                <div class="ibox-content">
                  <div class="row">
                    <div class="col-xl-12 col-lg-12">
                      <div class="text-danger text-center mt-2 mb-2"><b>Note : Do not refresh the page until the proforma invoice get generated.</b></div> 
                    </div>
                  </div>

                  <div class="table-responsive">
										<table class="table table-bordered table-hover custom_inner_tbl" style="width:100%">
											<thead>
												<tr> 
                          <th class="no-sort text-center nowrap" style="width:60px;">Sr. No.</th>
													<th class="text-center nowrap">Candidate Name</th>
													<th class="text-center nowrap">Amount</th>
												</tr>
											</thead>

                      <tbody>
                        <?php if(count($form_data) > 0)
                        {
                          $i = 1;
                          $total_fees = 0;
                          foreach($form_data as $cand_res)
                          { ?>
                            <tr>
                              <td class="text-center"><?php echo $i; ?></td>
                              <td>
                                <?php echo $cand_res['salutation']." ".$cand_res['first_name']; 
                                if($cand_res['middle_name'] != "") { echo " ".$cand_res['middle_name']; } 
                                if($cand_res['last_name'] != "") { echo " ".$cand_res['last_name']; }
                                echo " (".$cand_res['training_id'].")"; ?>
                              </td>
                              <td class="text-center"><?php echo $cand_res['exam_fee']; $total_fees = $total_fees + $cand_res['exam_fee']; ?></td>
                            </tr>
                          <?php $i++;
                          } ?>

                          <tr>
                            <td class="text-center" colspan="2"><b>Total</b></td>
                            <td class="text-center"><?php echo number_format_upto2($total_fees); ?></td>
                          </tr>
                        <?php } ?>
                      </tbody>
										</table>

                    <div class="text-center">
                      <?php if($total_fees > 0) { ?>
                        <button type="button" class="btn btn-success" onclick="make_payment_modal()">Generate Proforma Invoice</button>
                        <?php /*<a target="_blank" href="<?php echo site_url('iibfbcbf/agency/apply_exam_agency/proforma_invoice/'.url_encode($fresh_cnt).'/'.url_encode($repeater_cnt)); ?>" class="btn btn-warning">Proforma Invoice</a> */ ?>
                      <?php } ?>
                      <a href="<?php echo site_url('iibfbcbf/agency/apply_exam_agency/candidate_listing/'.$enc_exam_code); ?>" class="btn btn-danger">Cancel & Return</a>
                    </div>  
									</div>								
								</div>                
              </div>
						</div>
					</div>
				</div>
				<?php $this->load->view('iibfbcbf/agency/inc_footerbar_agency'); ?>			
			</div>
		</div>

    <div class="modal inmodal fade" id="make_payment_modal" tabindex="-1" role="dialog"  aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title">Generate Proforma Invoice</h4>
          </div>
          <form action="<?php echo site_url('iibfbcbf/agency/apply_exam_agency/make_payment/'.$enc_exam_code); ?>" method="post" class="form-horizontal" name="make_payment_form" id="make_payment_form" autocomplete="off" enctype="multipart/form-data">
            
            <input type="hidden" name="selcted_member_exam_ids_str" id="selcted_member_exam_ids_str" value="<?php echo $selcted_member_exam_ids_str; ?>">
            <input type="hidden" name="chk_form_type" id="chk_form_type" value="make_payment">
            <input type="hidden" name="form_total_fees" id="form_total_fees" value="<?php echo number_format_upto2($total_fees); ?>">
            <input type="hidden" name="form_exam_code" id="form_exam_code" value="<?php echo $active_exam_data[0]['exam_code']; ?>">
            <input type="hidden" name="form_exam_period" id="form_exam_period" value="<?php echo $active_exam_data[0]['exam_period']; ?>">
            <input type="hidden" name="form_exam_from_date" id="form_exam_from_date" value="<?php echo $active_exam_data[0]['exam_from_date']; ?>">
            <input type="hidden" name="form_exam_to_date" id="form_exam_to_date" value="<?php echo $active_exam_data[0]['exam_to_date']; ?>">

            <div class="modal-body">
              <div class="modal_form_outer">
                <div class="form-group row">
                  <label class="col-lg-4 text-right"><b>Agency Name :</b></label>
                  <div class="col-lg-8"><div class="modal_form_info_text"><?php echo $agency_centre_data[0]['agency_name']." (".$agency_centre_data[0]['agency_code'].")"; ?></div></div>
                </div>
                <div class="form-group row">
                <label class="col-lg-4 text-right"><b>Centre Name :</b></label>
                  <div class="col-lg-8"><div class="modal_form_info_text"><?php echo $agency_centre_data[0]['centre_name']." (".$agency_centre_data[0]['state_name'].", ".$agency_centre_data[0]['city_name'].")"; ?></div></div>
                </div>
                <div class="form-group row">
                <label class="col-lg-4 text-right"><b>To Payee (IIBF) :</b></label>
                  <div class="col-lg-8">
                    <div class="modal_form_info_text">Beneficiary Name: Indian Institute of Banking & Finance<br>Bank: State bank of India<br>Branch: Kurla (west)<br>Code: 1886<br>IFSC: SBIN0001886<br>Current Acct No: 32344902738<br>MICR No: 400002091<br>SWIFT CODE: SBININBB357</div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-lg-4 text-right"><b>Amount :</b></label>
                  <div class="col-lg-8"><div class="modal_form_info_text"><?php echo number_format_upto2($total_fees); ?></div></div>
                </div>
                <div class="form-group row">
                  <label class="col-lg-4 text-right"><b>Exam Name & Exam Period :</b></label>
                  <div class="col-lg-8"><div class="modal_form_info_text"><?php echo display_exam_name($active_exam_data[0]['description'], $active_exam_data[0]['exam_code'], $active_exam_data[0]['exam_type']).' - '.$active_exam_data[0]['exam_period']; /* helpers/iibfbcbf/iibf_bcbf_helper.php */ ?></div></div>
                </div>

                <div class="form-group row">
                  <label class="col-lg-4 text-right"><b>NEFT / RTGS (UTR) Number <sup class="text-danger">*</sup> :</b></label>
                  <div class="col-lg-8">
                    <input type="text" name="utr_no" id="utr_no" value="IIBFBCBF-TEMP-UTR-NO" placeholder="NEFT / RTGS (UTR) Number *" class="form-control custom_input" maxlength="30" required readonly/>
                    
                    <?php if(form_error('utr_no')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('utr_no'); ?></label> <?php } ?>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal-footer" id="submit_btn_outer">
              <button class="btn btn-primary" type="submit" value="submit">Submit</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </form>
        </div>
      </div>
    </div>
		
		<?php $this->load->view('iibfbcbf/inc_footer'); ?>
    <?php $this->load->view('iibfbcbf/common/inc_common_validation_all'); ?>
       

    <script language="javascript">
      var payment_date = $('#payment_date').datepicker({ keyboardNavigation: true, forceParse: true, autoclose: true, format: "yyyy-mm-dd", clearBtn: true, startDate:"<?php echo date("Y-m-d", strtotime($active_exam_data[0]['exam_from_date'])); ?>", endDate:"<?php echo date("Y-m-d"); ?>" });
      
      $('#make_payment_modal').on('hidden.bs.modal', function() 
      {
        $("#utr_slip_preview").html('<i class="fa fa-picture-o" aria-hidden="true"></i>');
        $("#make_payment_form").trigger( "reset" );
        $('#make_payment_form').validate().resetForm();
        $('#make_payment_form').find('.error').removeClass('error');
      });

      <?php if(validation_errors() != "" && count(validation_errors()) > 0) { ?> $("#make_payment_modal").modal({backdrop: 'static', keyboard: false}, 'show'); <?php } ?>

      function make_payment_modal()
      {
        $("#make_payment_modal").modal({backdrop: 'static', keyboard: false}, 'show');
      }

      function validate_input(input_id) { $("#"+input_id).valid(); }
      $(document ).ready( function() 
			{
        $("#make_payment_form").validate( 
				{
          //onfocusout: true,
          onkeyup: function(element) { $(element).valid(); },          
          rules:
					{
            utr_no:{ required: true },
					},
					messages:
					{
            utr_no: { required: "Please enter the NEFT / RTGS (UTR) Number" },
					},
					submitHandler: function(form) 
					{
            $(window).unbind('beforeunload');

            swal({ title: "Please confirm", text: "Please confirm to generate the proforma invoice", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
            { 
              $("#page_loader").show();
              $("#submit_btn_outer").html('<button class="btn btn-primary" type="button" style="cursor:wait" value="submit">Submit <i class="fa fa-spinner" aria-hidden="true"></i></button><button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>');
              form.submit();
            });            
					}
				});
			});

      $(document).ready(function() 
      {
        // Attach a handler for the beforeunload event
        $(window).on('beforeunload', function() 
        {
          return 'Please confirm do you want to reload or leave this page?';
        });
      });
    </script>
    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>	
	</body>
</html>