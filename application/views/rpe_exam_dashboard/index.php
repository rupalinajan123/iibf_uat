<style>
  .modal-dialog { position: relative; display: table; overflow-y: auto; overflow-x: auto; width: 920px; min-width: 300px; }
  #confirm .modal-dialog { position: relative; display: table; overflow-y: auto; overflow-x: auto; width: 420px; min-width: 400px; }
  .skin-blue .main-header .navbar { background-color: #fff; }
  body.layout-top-nav .main-header h1 { color: #0699dd; margin-bottom: 0; margin-top: 30px; }
  .container { position: relative; }
  .box-header.with-border { background-color: #7fd1ea; border-top-left-radius: 0; border-top-right-radius: 0; margin-bottom: 10px; }
  .header_blue { background-color: #2ea0e2 !important; color: #fff !important; margin-bottom: 0 !important; }
  .box { border: none; box-shadow: none; border-radius: 0; margin-bottom: 0; }
  .nobg { background: none !important; border: none !important; }
  .box-title-hd { color: #3c8dbc; font-size: 16px; margin: 0; }
  .blue_bg { background-color: #e7f3ff; } 
  .m_t_15 { margin-top: 15px; }
  .main-footer { padding-left: 0px; padding-right: 0px; margin:0 auto !important; width: 95%;}
  .content-header > h1 { font-size: 22px; font-weight: 600; }
  h4 { margin-top: 5px; margin-bottom: 10px !important; line-height: 18px; padding: 0 5px; font-weight: 600; text-align: justify; }
  /* .form-horizontal .control-label { padding-top: 400px; } */
  .pad_top_2 { padding-top: 2px !important; }
  .pad_top_0 { padding-top: 0px !important; }
  /* div.form-group:nth-child(odd) { background-color: #dcf1fc; padding: 5px 0; } */
  #confirmBox { display: none; background-color: #eee; border-radius: 5px; border: 1px solid #aaa; position: fixed; width: 300px; left: 50%; margin-left: -150px; padding: 6px 8px 8px; box-sizing: border-box; text-align: center; z-index: 1; box-shadow: 0 1px 3px #000; }
  #confirmBox .button { background-color: #ccc; display: inline-block; border-radius: 3px; border: 1px solid #aaa; padding: 2px; text-align: center; width: 80px; cursor: pointer; }
  #confirmBox .button:hover { background-color: #ddd; }
  #confirmBox .message { text-align: left; margin-bottom: 8px; }
  .form-group { margin-bottom: 10px; }
  .form-horizontal .form-group { margin-left: 0; margin-right: 0; }
  .form-control { border-color: #888; }
  /* .form-horizontal .control-label { font-weight: normal; } */
  a.forget { color: #9d0000; }
  a.forget:hover { color: #9d0000; text-decoration: underline; }
  ol li { line-height: 18px; }
  .example { text-align: left !important; padding: 0 10px; }
  
  
  .main-header { max-height: none; width: 100%; max-width: 900px; }
  .container { width: 100%; max-width: 900px; }
  .error, .error > p { color: #F00; margin: 0; font-weight: 500; line-height: 15px; display: block; text-align:left; }
  
  ul.member_img_outer { list-style:none; margin:0; padding:0; text-align:center; }
  ul.member_img_outer li { display:inline-block; margin:10px; }
  ul.member_img_outer li a { display: table-cell; width: 180px; height: 130px; overflow: hidden; border: 4px solid #f6f6f6; padding: 2px; background: #FBFBFB; vertical-align: middle; }
  ul.member_img_outer li a.missing_img_outer, ul.member_img_outer li a.missing_img_outer:hover { color: #F00;font-size: 20px;background: #fff; }
  ul.member_img_outer li a img { max-width:100%; max-height:100%; opacity:0.8; transition: all .3s ease-in-out; }
  ul.member_img_outer li a:hover img { opacity:1; }
  ul.member_img_outer li p { text-align: center; font-weight: 600; margin: 4px 0 5px 0; }
  a.download_img_btn { margin: 0 auto 15px;display: block;max-width: 160px; }
  
  html, body { height: 100%; }
  #top_header { margin-bottom: -51px; min-height: 100%; padding-bottom:55px; }
  .main-footer { position:relative; }
	
	.table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td 
	{ border: 1px solid #9b9b8c !important; }
	.bg_header > th { background:#dddddd; }
	
	.custom_form_filter { background: #f1f1f1;text-align: center;padding: 20px 20px 10px 20px;margin: 0px 0 20px 0;	}
	.custom_form_filter .form-group { margin: 0 0px 15px; vertical-align:top; }
	.custom_form_filter .form-group > .btn { padding: 4px 15px; border-radius: 0; }
	.main-header { position: unset; }
  
  .form-group #exam_code_chosen ul.chosen-choices { border-color: #888; border-radius: 0; padding: 0 0 0 10px; }
  .form-group #exam_code_chosen ul li::before { position: unset; left: 0; top: 0; display: none; color: #ff9000; content: ""; font-family: ''; font-size: 0px; }
	.chosen-container ul.chosen-results li.highlighted { background-color: #1287c0; }
	.chosen-container ul.chosen-results li { text-align: left; padding-left:15px; }
  #exam_code_chosen.chosen-container-multi .chosen-choices .search-choice { background-image: -webkit-linear-gradient(top, #f1f1f1 0%, #f1f1f1 100%); background-image: -o-linear-gradient(top, #f1f1f1 0%, #f1f1f1 100%); background-image: linear-gradient(to bottom, #f1f1f1 0%, #f1f1f1 100%); }  

	@media only screen and (max-width:991px) { #getDetailsForm > .form-group > .col-sm-6 { margin-bottom:15px; } }
  @media only screen and (max-width:768px) { #getDetailsForm > .form-group > .col-xs-12 { padding:0; } }
</style>

<?php
  header('Cache-Control: must-revalidate');
  header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>

<div class="container">
  <section class="content-header box-header with-border" style="height: 45px; background-color: #1287C0; ">
    <h1 class="register">RPE Exam Dashboard</h1><br />
	</section>
  <p style="margin: 0 0 10px 0;text-align: right;	font-weight: 600;	font-size: 15px;">Total count From 15th July, 2020 : <?php echo $total_cnt; ?></p>
  <section>
    <div class="row">
      <div class="col-md-12">
        <?php
          if($this->session->flashdata('error') != '') 
          { ?>
          <div class="alert alert-danger alert-dismissible" id="error_id">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo $this->session->flashdata('error');?> 
					</div>
				<?php } ?>
        
        <form class="form-horizontal custom_form_filter" name="getDetailsForm" id="getDetailsForm" method="post" action="<?php echo site_url('admin/rpe_exam_dashboard'); ?>" autocomplete="off">
          <?php if($error_msg!=""){ ?> <div class="clearfix"></div><label class="error" style="text-align: center;margin-bottom: 10px;"><?php echo $error_msg; ?></label> <?php } ?>
					
          <div class="row">
						<div class="col-lg-12">
							<div class="form-group">
								<select class="form-control chosen-select" id="exam_code" name="exam_code[]" required autofocus onchange="get_date_range()" multiple data-placeholder="<?php if(!empty($active_exam_data) && count($active_exam_data) > 0) { echo 'Select Exam'; } else { echo 'No Exam Available'; } ?>">
									<?php
										if(!empty($active_exam_data) && count($active_exam_data) > 0)
										{
											foreach($active_exam_data as $active_exam_res)
											{ ?>
											<option value="<?php echo $active_exam_res['exam_code']; ?>" <?php if(set_value('exam_code') != '') { $exam_code_arr = set_value('exam_code'); } else { $exam_code_arr = $exam_code; } if(is_array($exam_code_arr) && in_array($active_exam_res['exam_code'],$exam_code_arr)) { echo "selected = 'selected'"; } ?>><?php if($active_exam_res['description'] != "") { echo $active_exam_res['description']." - "; } if($active_exam_res['exam_code'] == '2027'){echo '1017';}else{ echo $active_exam_res['exam_code']; }?></option>
											<?php	}
										} ?>
								</select>
								<?php if(form_error('exam_code')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('exam_code'); ?></label> <?php } ?>
							</div>
						</div>
						
						<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
							<div class="form-group">
								<span id="start_date_outer"><input type="text" class="form-control" id="start_date" name="start_date" value="<?php if(set_value('start_date')) { echo set_value('start_date'); } else { echo $start_date; } ?>" placeholder="Start Date"></span>
								<?php if(form_error('start_date')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('start_date'); ?></label> <?php } ?>
							</div>
						</div>
						
						<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
							<div class="form-group">
								<span id="end_date_outer"><input type="text" class="form-control" id="end_date" name="end_date" value="<?php if(set_value('end_date')) { echo set_value('end_date'); } else { echo $end_date; } ?>" placeholder="End Date" ></span>
								<?php if(form_error('end_date')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('end_date'); ?></label> <?php } ?>
							</div>
						</div>
						
						<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
							<div class="form-group">
								<select class="form-control" id="exam_date" name="exam_date">
									<?php
										if(!empty($exam_date_data) && count($exam_date_data) > 0)
										{
											echo '<option value="">Select Exam Date</option>';
											foreach($exam_date_data as $exam_date_res)
											{	?>
											<option value="<?php echo $exam_date_res['ExamDate']; ?>" <?php if(set_value('exam_date') == $exam_date_res['ExamDate']) { echo "selected"; } else { if($exam_date == $exam_date_res['ExamDate']) { echo "selected"; } } ?>><?php echo $exam_date_res['ExamDate']; ?></option>
											<?php	}
										}
										else
										{
											echo '<option value="">No Exam Date Available</option>';
										} ?>
								</select>
								<?php if(form_error('exam_date')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('exam_date'); ?></label> <?php } ?>
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<button type="submit" class="btn btn-primary" style="">Get Count</button>
						<a href="<?php echo site_url('admin/rpe_exam_dashboard'); ?>" class="btn btn-primary" style="">Clear</a>
					</div>		
					<p class="small" style="margin: 0 0px 0px 0; text-align: left;">Note : Please select (Start date & End Date) or (Only Exam Date) or (Start date, End Date & Exam Date) combinations for filter</p>
				</form> 
				
				<div style="display:none;">
					<?php 
					
					echo '<br> free_result_dataq : '.$free_result_dataq;
					echo '<br> free_elearning_result_dataq : '.$free_elearning_result_dataq;
					echo '<br> paid_result_dataq : '.$paid_result_dataq;
					echo '<br> paid_elearning_result_dataq : '.$paid_elearning_result_dataq;
					
					?>
				</div>
				
				<?php if(count($result_data) > 0)
				{	?>
					<section class="content" style="min-height:auto;">
						<div class="box box-info">
							<?php /* <h4 class="text-center"><?php echo "Exam Name : ".rtrim($exam_disp_name,", "); ?></h4> */ ?>
							<div class="table-responsive custom_datatable_style">
								<table class="table table-hover table-bordered" cellspacing="0" width="100%">
									<thead>
										<tr class="bg_header">
											<th class="text-center">Exam Code</th>
											<th class="text-center"><?php if($start_date != "" && $end_date != "") { echo "Register Date"; } else { echo "Exam Date"; } ?></th>
											<th class="text-center">Free Count</th>
											<th class="text-center">Free + E-learning Count</th>
											<th class="text-center">Paid Count</th>
											<th class="text-center">Paid + E-learning Count</th>
										</tr>
									</thead>
									<tbody>  
										<?php 
										$i= $col_free_tot = $col_free_elearn_tot = $col_paid_tot = $col_paid_elearn_tot = $final_tot = 0;
										foreach($result_data as $key => $res)
										{	
											$col_free_tot = $col_free_tot + $res['free_cnt'];
											$col_free_elearn_tot = $col_free_elearn_tot + $res['free_elearning_cnt'];
											$col_paid_tot = $col_paid_tot + $res['paid_cnt'];
											$col_paid_elearn_tot = $col_paid_elearn_tot + $res['paid_elearning_cnt'];
											
											$final_tot = $final_tot + $res['free_cnt'] + $res['free_elearning_cnt'] + $res['paid_cnt'] + $res['paid_elearning_cnt']; ?>
											<tr>
												<?php if($i == 0) { ?> <td class="text-center" rowspan="<?php echo count($result_data); ?>" style="vertical-align:middle"><?php if(count($exam_code) > 0) { foreach($exam_code as $ExamCode) { if($ExamCode == '2027'){ echo '1017'."<br>";} else {echo $ExamCode."<br>";} } } ?></td><?php } ?>
												<td class="text-right"><?php echo $key; ?></td>                          
												<td class="text-center <?php if($res['free_cnt'] > 0) { echo "text-bold"; } ?>"><?php echo $res['free_cnt']; ?></td>                          
												<td class="text-center <?php if($res['free_elearning_cnt'] > 0) { echo "text-bold"; } ?>"><?php echo $res['free_elearning_cnt']; ?></td>                          
												<td class="text-center <?php if($res['paid_cnt'] > 0) { echo "text-bold"; } ?>"><?php echo $res['paid_cnt']; ?></td>                          
												<td class="text-center <?php if($res['paid_elearning_cnt'] > 0) { echo "text-bold"; } ?>"><?php echo $res['paid_elearning_cnt']; ?></td>                          
											</tr>
								<?php $i++;
										} ?>
									</tbody>
									<?php if($start_date != "" && $end_date != "") {	?>
									<tfoot>
										<tr class="bg_header">
											<th class="text-right" colspan="2">Total</th>
											<th class="text-center"><?php echo $col_free_tot; ?></th>
											<th class="text-center"><?php echo $col_free_elearn_tot; ?></th>
											<th class="text-center"><?php echo $col_paid_tot; ?></th>
											<th class="text-center"><?php echo $col_paid_elearn_tot; ?></th>
										</tr>
									</tfoot>
									<?php } ?>
								</table>
							</div>
							<h4 class="text-center" style="margin: 0 0 15px 0 !important; position: relative; padding: 0;">
								Total : <?php echo $final_tot; ?>
								<?php if($final_tot > 0) { if($start_date == '') { $start_date = 0; } if($end_date == '') { $end_date = 0; } if($exam_date == '') { $exam_date = 0; }   ?> <a target="_blank" href="<?php echo site_url('admin/rpe_exam_dashboard/download_admit_card/'.implode(",",$exam_code).'/'.$start_date.'/'.$end_date.'/'.$exam_date); ?>" class="btn btn-primary" style="padding: 2px 10px 3px;border-radius: 0; font-size: 13px;line-height: 15px;float: right;	margin: 0 0 5px 0;">Download Admit Cards</a> <?php } ?>
							</h4>
						</div>
					</section>
				<?php } ?>
			</div>
		</div>
	</section>
</div>
<div id="footer_script_outer"></div>
<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">

<link href="<?php echo base_url('assets/chosen/bootstrap-chosen.css'); ?>" rel="stylesheet">
<script src="<?php echo base_url('assets/chosen/chosen.jquery.js'); ?>"></script>
<script>$('.chosen-select').chosen({width: "100%"});</script>

<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/datepicker/datepicker3.css">
<style>.datepicker table tbody tr td.disabled, .datepicker table tbody tr td.disabled:hover { background: rgba(0, 0, 0, 0.04) !important; cursor: not-allowed !important; border: 1px solid #fff; color: #ccc !important; }</style>

<script src="<?php echo base_url()?>assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<script type="text/javascript">
	$(document).ready(function() 
	{
		$('#start_date').datepicker(
		{ 
			/* todayBtn: "linked", */ 
			keyboardNavigation: true, 
			forceParse: true, 
			/* calendarWeeks: true, */ 
			autoclose: true, 
			format: "yyyy-mm-dd", 
			/* todayHighlight:true,  */ 
			clearBtn: true,
			<?php if($exam_code != '') { ?>
        startDate:"<?php echo $StartDateLimit; ?>",
        endDate:"<?php echo $EndDateLimit; ?>"
      <?php } 
      else { ?> endDate:"<?php echo date('Y-m-d', strtotime($current_date)); ?>" <?php } ?>
		});
		
		$('#end_date').datepicker(
		{ 
			/* todayBtn: "linked", */ 
			keyboardNavigation: true, 
			forceParse: true, 
			/* calendarWeeks: true, */ 
			autoclose: true, 
			format: "yyyy-mm-dd", 
			/* todayHighlight:true,  */
			clearBtn: true,
			<?php if($exam_code != '') { ?>
        startDate:"<?php echo $StartDateLimit; ?>",
        endDate:"<?php echo $EndDateLimit; ?>"
      <?php } 
      else { ?> endDate:"<?php echo date('Y-m-d', strtotime($current_date)); ?>" <?php } ?>
		});
	});	
	
	function get_date_range()
	{
    var exam_code =  $("#getDetailsForm .chosen-select").val();
    
    $("#page_loader").show();
		var selected_start_date = $("#start_date").val();
		var selected_end_date = $("#end_date").val();
		parameters= { 'exam_code':exam_code, 'selected_start_date':selected_start_date, 'selected_end_date':selected_end_date }
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('admin/rpe_exam_dashboard/get_date_range_ajax') ?>",
			data: parameters,
			cache: false,
			dataType: 'JSON',
			success:function(data)
			{
				if(data.flag == "success")
				{ 
					$("#start_date_outer").html(data.start_date_html);
					$("#end_date_outer").html(data.end_date_html);
					$("#footer_script_outer").html(data.date_response);		
					$("#page_loader").hide();
				}
				else { location.reload();	}
			}
		});
	}
</script>