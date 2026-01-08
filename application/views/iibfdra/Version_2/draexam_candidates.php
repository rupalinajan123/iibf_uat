<style type="text/css">
  .isDisabled {
  pointer-events: none;
  color: #0975b36b;
	/* display: none*/
	}
	.types {
	color: #223fcc;
	font-weight: 800;
	}
	.typec {
	color: #73c5ce;
	font-weight: 800;
	}
	.statusa {
  color: green;
  font-weight: 800;
	}
	.statusc {
  color: #9aa544;
  font-weight: 800;
	}
	.statusr {
  color: #cc4122;
  font-weight: 800;
	}
	
	.custom_pagination { margin-top: 20px; text-align: center; }
	.custom_pagination a { padding: 2px 10px; margin: 0 0px 2px 0px; }
	.custom_pagination a.active { cursor:auto; }
	
	.search_form_common_all { background: #ededed; padding: 20px 20px 10px 20px; text-align: left; margin-bottom:20px; }
	.search_form_common_all .form-group { display: inline-block; margin: 0 0px 10px; width: 100%; vertical-align: top; }
	.search_form_common_all .form-group .form-label { display: block; font-size: 14px; margin: 0 0 3px 0; line-height: 22px; text-align: left; }
	.search_form_common_all .form-group .form-control { padding: 5px 20px 5px 10px; height: 35px !important; }
	.search_form_common_all .btn { display: inline-block; vertical-align: top; padding: 7px 20px 6px; margin: 0 0px 0 0; min-width: 97px; }
	
	.custom_first_row { padding: 0 !important; }
	#page_loader {
	background: rgba(0, 0, 0, 0.35) none repeat scroll 0 0;
	height: 100%;
	left: 0;
	position: fixed;
	top: 0;
	width: 100%;
	z-index: 99999;
	display: none;
	}
	#page_loader .loading {
	margin: 0 auto;
	position: relative;
	width: 80px;
	height: 80px;
	top: calc( 50% - 40px);
	color: #fff;
	font-size: 30px;
	}
</style>
<div id="page_loader" style="display: none;"><div class="loading">Processing...</div></div>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
	<section class="content-header">
		<?php 
			$desc = '';
			foreach( $active_exams as $exam ) 
			{
				//if($examcode == base64_encode($exam['exam_code']))
				if($examcode == $exam['exam_code'])
				{
					$desc = strtolower($exam['description']);
					$desc = str_ireplace('debt recovery agent','DRA',$desc);
					$desc = str_ireplace('examination','Exam',$desc);
					$desc = str_ireplace('-','',$desc);
				}
			}
		?>
		<?php $_SESSION['reffer'] = $_SERVER['REQUEST_URI'];
		?>
		<?php $_SESSION['excode'] = $examcode;
		?>
		<h1><?php echo ucwords($desc);?> Application Entry And Payment </h1>
	</section>
  <!-- Main content -->
	<form name="draexampay" class="draexampay" method="post" action="<?php echo base_url();?>iibfdra/Version_2/TrainingBatches/payment/<?php echo base64_encode($examcode);?>">
		
	    <section class="content">
	      	<div class="row">
	        	<div class="col-xs-12">
					<div class="box">
						<div class="box-header">
							<h3 class="box-title">Candidate Details</h3>
							<div class="pull-right">
								<input type="submit" name="make_payment" class="btn  btn-warning mk-payment" value="Preview and Generate Proforma Invoice"/>
								<a href="<?php echo base_url();?>iibfdra/Version_2/TrainingBatches/allapplicants/<?php echo base64_encode($examcode);?>" class="btn btn-info">Refresh</a>
								<input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
								<input type="hidden" name="base_url_val" id="base_url_val" value="" /> 
							</div>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<?php if($this->session->flashdata('error')!=''){?>
								<div class="alert alert-danger alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<?php echo $this->session->flashdata('error'); ?>
								</div>
								<?php } if($this->session->flashdata('success')!=''){ ?>
								<div class="alert alert-success alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<?php echo $this->session->flashdata('success'); ?>
								</div>
								<?php } 
								//echo count($eligible); die; 
								
							/* print_r($eligible); die; */ ?> 
							
							<section class="search_form_common_all">
								<div class="form-group" style="width: 100%;">
									<label class="form-label">Batch Code</label>
									<input type="text" class="form-control custom_search_cls" name="s_batch_code" id="s_batch_code" value="<?php if($s_batch_code != '0') { echo $s_batch_code; } ?>" placeholder="If you want to enter multiple batch codes, then use comma for separation" autocomplete="off">
								</div>
								
								<div class="form-group" style="width: 100%">
									<label class="form-label">Registration No.</label>
									<input type="text" class="form-control custom_search_cls" name="s_member_no" id="s_member_no" value="<?php echo $s_member_no; ?>" placeholder="If you want to enter multiple Registration Numbers, then use comma for separation" autocomplete="off">
								</div>
								
								<div class="form-group" style="">
									<a href="javascript:void(0)" class="btn btn-primary" onclick="search_form_submit()" style="margin-right:3px;">Search</a> 
									<a href="<?php echo site_url('iibfdra/Version_2/TrainingBatches/allapplicants/'.base64_encode($examcode)); ?>" class="btn btn-primary">Clear</a> 
								</div>
							</section>
							
							<!-- <input id="myInput" type="text" placeholder="Search.." style="float: right"><br><br>-->
							<div class="table-responsive">
								<div class="col-md-12" style="font-style: italic; font-size: 16px;">
										<b>Disclaimer :</b> <span>Upto 525 candidates can be selected while generating the proforma invoice and making</span> payment. 
								</div>

								<table id="listitems" class="table table-bordered table-striped dataTables-example">
									<thead>
										<tr>
											<th style='	width: 0;height: 0;display: block;padding: 0;'>
												<div style='position: absolute;left: 7px;top: 36px;z-index: 999;width: 25px;height: 25px;text-align: center; display: block; padding: 4px 0 0 0; '><input type="checkbox" class="all-checkbox" id="selectall" style='margin:0' /></div>
											</th>
											<th id="checkbox_id" class="checkboxcls"></th>
											<th id="srNo" class="serial_no">Sr.No.</th>
											<th id="batch_code">Batch Code</th>
											<!-- <th id="batch_id">Batch Name</th> -->
											<th id="member_no">Registration No.</th>
											<th id="firstname">Candidate Name</th>
											<th id="dateofbirth">DOB</th>
											<th id="email">Email</th>
											<th id="exam_fee">Fee</th>
											<th id="pay_status">Payment Status</th>
											<th id="">Transaction No</th>
											<th id="exam_center_code">Exam Center Name</th>
											<th id="exam_medium">Exam Medium</th>
											<th id="action">Operations</th> 
										</tr>
									</thead>
									
									<tbody class="no-bd-y" id="ApplicantDataOuter"></tbody>
								</table>
							</div>
							<div id="showMoreBtnOuter"></div>
							<input type="hidden" name="selcted_checkbox_all_hidden" id="selcted_checkbox_all_hidden">
							<input type="hidden" value="<?=$examcode?>" id="examcode">
							<div>
							</div>							
						</div>
					</div>
				</div>
			</div>
		</section>
	</form>
</div>

<script type="text/javascript">
	var table;	
	$(function () 
	{		
		// $("#listitems2_filter").show();
    
		// DataTable
		/*var table = $('#listitems').DataTable();
		table.columns( '.serial_no' ).order( 'asc' ).draw();*/
		
		var table = $('#listitems').DataTable(
		{
			/* "columnDefs": [ {
				"targets": 'checkboxcls',
				"orderable": false,
			} ], */
			//"lengthMenu": [[50, 100, 500, 1000, 2000, 3000, 4000, 5000, 7000], [50, 100, 500, 1000, 2000, 3000, 4000, 5000, 7000]], 
			"pageLength": 50000,
			"bLengthChange":false,
			"paging": false,
			//"pagingType": 'full_numbers',
			'stateSave': true,
			"columnDefs":
			[
			{"targets": 'no-sort', "orderable": false, },
			{"targets": [0], "className": "custom_first_row"},
			/* {"targets": [7], "className": "text-center"},
				{"targets": [8], "className": "hide"},
			{"targets": [11], "className": "text-center"},   */          
			],
			/*"fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
				console.log('aData:'+aData[14]);
		      if (aData[14] == "<td>Manual Hold</td>" || aData[14] == "<td>Auto Hold</td>") {
		        $('td', nRow).css('color', '#808080');
		      } 
		    }*/
		}
		);
		table.columns( '.checkboxcls' ).order( 'desc' ).draw();
		
		$("#listitems tfoot th").each( function ( i ) {
			var select = $('<select  class="pp'+i+'" ><option value="">All</option></select>')
			.appendTo( $(this).empty() )
			.on( 'change', function () {
				table.column( i )
				.search( $(this).val() )
				.draw();
			} );
			
			table.column( i ).data().unique().sort().each( function ( d, j ) {
				select.append( '<option value="'+d+'">'+d+'</option>' )
			} );
		});		
	});
	
	function getTableDataAjax(i_val, chk_val)
	{
		$("#showMoreBtn").remove();
		//alert(i_val+'***'+chk_val);
		
		var examcode = encodeURIComponent('<?php echo $examcode; ?>');
		var s_batch_code = $("#s_batch_code").val().trim(); //encodeURIComponent('<?php echo $s_batch_code; ?>');
		var s_member_no = $("#s_member_no").val().trim(); //encodeURIComponent('<?php echo $s_member_no; ?>');
		var dispLimit = encodeURIComponent('<?php echo $dispLimit; ?>');
		
		var parameters= { 'i_val':i_val, 'chk_val':chk_val, 'examcode':examcode, 's_batch_code':s_batch_code, 's_member_no':s_member_no, 'dispLimit':dispLimit }
		//alert(parameters);
		$("#page_loader").show();
		$.ajax( 
		{
			type: "POST",
			url: "<?php echo site_url('iibfdra/Version_2/TrainingBatches/getTableDataAjax'); ?>",
			data: parameters,
			cache: false,
			dataType: 'JSON',
			success:function(data)
			{
				console.log('----'+data);
				if(data.flag == "success")
				{
					//$("#ApplicantDataOuter").append(data.ApplicantResponse);
					
					var temp_response = data.ApplicantResponse;
					var response = temp_response[0];
					var response_length = response.length;
					
					var table;
					table = $('#listitems').DataTable();
					for (var i = 0; i < response_length; i++) 
					{
						table.row.add(['', response[i][0], response[i][1], response[i][2], response[i][3], response[i][4], response[i][5], response[i][6], response[i][7], response[i][8], response[i][9], response[i][10], response[i][11], response[i][12], response[i][13]]);//, response[i][14]
					}
					table.draw();
					
					if(data.ShowMoreBtn.trim() != "") { $("#showMoreBtnOuter").html(data.ShowMoreBtn); }
				}
				else { 
					alert("Error Occurred. Please try again."); 
				}
				$("#page_loader").hide();
			}
		});
	}
	getTableDataAjax(1, '<?php echo $dispLimit; ?>');

	$("input#s_batch_code").keydown(function(event)
	{
		if(event.keyCode == 13) { event.preventDefault(); return false; }
	});
	
	$("input#search_member_no").keydown(function(event)
	{
		if(event.keyCode == 13) { event.preventDefault(); return false; }
	});
	
	function search_form_submit()
	{
    $('#listitems').DataTable().clear();
    getTableDataAjax(1, '<?php echo $dispLimit; ?>');

		/* var search_val = $("#s_batch_code").val();
		var search_mem_no = $("#s_member_no").val();
		
		var redirect_url = "<?php echo site_url('iibfdra/Version_2/TrainingBatches/allapplicants/'.base64_encode($examcode)) ?>";
		
    if(search_val != "")
		{
			//redirect_url += '&sBtCd='+encodeURI(search_val);
			redirect_url += '/'+encodeURI(search_val);
		}	
		
		if(search_mem_no != "")
		{
      if(search_val == "") { redirect_url += '/0'; }
			//redirect_url += '&sMeNo='+encodeURI(search_mem_no);
			redirect_url += '/'+encodeURI(search_mem_no);
		}
		//alert(redirect_url);
		window.location.href = redirect_url; */
	}
	
	$(document).ready(function() 
	{
		$('.dataTables-example_121212').wrap('<div class="table-responsive"></div>');
		var i = 0;
    /* $('table tr').each(function(index) {
			$(this).find('td:nth-child(2)').html(index-1+1);
		}); */
		
		$("body").on("contextmenu",function(e){
			return false;
		});
		
		$('[data-toggle="tooltip"]').tooltip();   
		
		/*$("#myInput").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#listitems tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});*/
		
		var arr_checked_candidates = [];


		$("#selectall").click(function () 
		{			
     	const isChecked = this.checked;
	    $('.chkmakepay').prop('checked', isChecked);

	    if (isChecked) {
	        // Add all values
	        $(".chkmakepay").each(function () {
	            const val = $(this).val();
	            if (!arr_checked_candidates.includes(val)) {
	                arr_checked_candidates.push(val);
	            } else {
	            	$(this).prop('checked', false);
	            	alert('You have double select the same candidate.'+val);
	            }
	        });
	    } else {
	        // Clear all values
	        arr_checked_candidates = [];
	    }

      updateCount('all',this);
		});

		// Function to update the count
    function updateCount(checkType,event) {
        const count = $(".chkmakepay:checked").length; // Count selected checkboxes
        console.log('arr_checked_candidates',arr_checked_candidates);
        if ( count > 525 ) {
        	alert('Upto 525 candidates can be selected while generating the proforma invoice and making payment.'); 

        	if (checkType == 'all') 
        	{
        		$('.chkmakepay').prop('checked', false);
        		$('#selectall').prop('checked', false);
        	}
        	else if(checkType == 'single')
        	{
        		$(event).prop('checked', false);	
        	}

        	// Deselect all if limit exceeded
	        arr_checked_candidates = [];
        }
    }

    // Event listener for checkbox changes
    $(document).on('change', '.chkmakepay', function() 
    {
  		const val = $(this).val();

	    if ($(this).is(':checked')) {
	        if (!arr_checked_candidates.includes(val)) {
	            arr_checked_candidates.push(val);
	        } else {
	        	$(this).prop('checked', false);
	        	alert('You have double select the same candidate.'+val);
	        }
	    } else {
	        // Remove value if unchecked
	        const index = arr_checked_candidates.indexOf(val);
	        if (index !== -1) {
	            arr_checked_candidates.splice(index, 1);
	        }
	    }

      updateCount('single',this); // Update count on checkbox change
    });

    $( ".draexampay" ).submit(function() 
		{
			if( $(".chkmakepay:checked").length == 0 ) {
				alert('Please select at least one candidate to pay');
				return false; 
			} else if ($(".chkmakepay:checked").length > 525) {
				alert('Upto 525 candidates can be selected while generating the proforma invoice and making payment.');
				return false; 
			} else {
				
				return true;  
			}
		});

    // Initial count update on page load
    updateCount('both');
	});
</script>
<script type="text/javascript">
	$(function () 
	{
		/*$("#listitems").DataTable();
			var base_url = '<?php //echo base_url(); ?>';
			paginate(base_url+'iibfdra/Version_2/DraExam/getApplicantList','','','');
		$("#base_url_val").val(base_url+'iibfdra/Version_2/DraExam/getApplicantList');*/
		// add multiple select / deselect functionality
		
		// if all checkbox are selected, check the selectall checkbox
		// and viceversa
		$(".chkmakepay").click(function()
		{
			/*var row_id=$(this).attr("data-attr");
				var exam_medium = $('#exam_medium'+row_id).val();
				var exam_center = $('#exam_center'+row_id).val();
				if(exam_medium != '' && exam_center !='')
			{*/
			if($(".chkmakepay").length == $(".chkmakepay:checked").length) {
				//    $("#selectall").prop("checked", true);
			}
			else {
				//  $(this).removeAttr("checked");
				/*  } 
					}
					else{
          $(this).removeAttr("checked");
					alert('Please select exam medium and exam center');
				}*/
			}
		});
	});
</script>
<script>
	function upadteexam(obj)
	{		
		var regid = obj;
		var batchId = $('#batch_id'+obj).val();;
		var memtype = $('#memtype'+obj).val();
		var examcode = $('#examcode').val();
		var exam_medium = $('#exam_medium'+obj).val();
		var exam_center = $('#exam_center'+obj).val();
		var training_from = $('#training_from'+obj).val();
		var training_to = $('#training_to'+obj).val();
		$('#upadteexam'+obj).css('pointer-events','none');
		$('#upadteexam'+obj).css('color', '#0975b36b');
		//alert(regid);
		//alert(regid);
		
		// AJAX request
		if(exam_medium != '' && exam_center !='' && memtype !='')
		{
			// alert(exam_medium); 
      $.ajax({
        url:'<?=base_url()?>iibfdra/Version_2/TrainingBatches/upadeApplicant',
        method: 'post',
        data: {regid: regid,examcode: examcode,exam_medium: exam_medium,exam_center: exam_center,training_from: training_from,training_to: training_to,memtype: memtype,batchId: batchId},
        dataType: 'json',
        success: function(response){
					//alert(response);
          // Add options
					if(response == 1){
            alert('Updating record is getting fail.please try again.')
					}
					else{
						location.reload();
						//$('#fee'+obj).text(response);
						//$('#status'+obj).text('Payment For Approve By IIBF');
						// $('#upadteExam').remove();
					}
          
				}
			});
		}
		else
		{
			alert('Please select exam medium and exam center and member type can not be empty.');
		}		
	}	
	
	function clearexam(obj)
	{
		var regid = obj;
		// var memtype = $('#memtype'+obj).val();
		var regid = obj;
		var batchId = $('#batch_id'+obj).val();;
		var memtype = $('#memtype'+obj).val();
		var examcode = $('#examcode').val();
		var exam_medium = $('#exam_medium'+obj).val();
		var exam_center = $('#exam_center'+obj).val();
		var training_from = $('#training_from'+obj).val();
		var training_to = $('#training_to'+obj).val();
    
    if (confirm('Are you sure you want to Clear Exam details of the selected application')) 
		{			
      if(exam_medium != '' && exam_center !='' && regid != '')
			{     
				$.ajax({
					url:'<?=base_url()?>iibfdra/Version_2/TrainingBatches/clearApplicant',
					method: 'post',
					data: {regid: regid,examcode: examcode,exam_medium: exam_medium,exam_center: exam_center,training_from: training_from,training_to: training_to,memtype: memtype,batchId: batchId},
					dataType: 'json',
					success: function(response){          
						if(response == 'fail'){
							alert('Updating record is getting fail.please try again.')
							}else{
							console.log('done');
							location.reload();             
						}
						
					}
				});   
				}else{
				alert('Please select exam medium and exam center');
			}
		}		
	} 
</script> 
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">

<!-- Data Tables --> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script> 
<!-- Data Tables --> 
