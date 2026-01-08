<?php $this->load->view('admin/kyc/includes/header'); ?>
<?php $this->load->view('admin/kyc/includes/approver_sidebar'); ?>
<style>
	.break_word {
		word-break: break-word;
		white-space: normal;
		word-wrap: anywhere;
	}

	.nowrap {
		white-space: nowrap;
	}

	.dataTables_wrapper {
		max-width: 97%;
		margin: 20px auto 0;
	}

	button.srch_btn,
	button.clr_btn {
		margin-left: 5px;
	}
</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Professional Banker KYC member list </h1>
	</section><br />

	<div class="col-md-12">
		<?php if ($this->session->flashdata('error') != '') { ?>
			<div class="alert alert-danger alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
				<?php echo $this->session->flashdata('error'); ?>
			</div>
		<?php }

		if ($this->session->flashdata('success') != '') { ?>
			<div class="alert alert-success alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
				<?php echo $this->session->flashdata('success'); ?>
			</div>
		<?php } ?>
	</div>

	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header"></div>
					<div class="box-body">
						<div class="col-sm-12">
							<div class="table-responsive">
								<table class="table table-bordered table-hover dataTables-example" id="dataTables-example" style="width:100%">
									<thead>
										<tr>
											<th class="no-sort text-center" style="width:40px;">Sr No.</th>
											<th class="text-center">Membership / Registration No</th>
											<th class="text-center">Candidate Name</th>
											<th class="text-center">Exam Code</th>
											<th class="text-center">Exam Name</th>
											<th class="text-center">Amount</th>
											<th class="text-center">Experience Certificate</th>
											<th class="text-center">KYC Status</th>
											<th class="text-center nowrap">Remark</th>
											<th class="text-center">Created Date</th>
											<th class="text-center no-sort" style="width:90px;">Action</th>
										</tr>
									</thead>

									<tbody></tbody>

									<tfoot>
										<tr>
											<th class="text-center" style="width:40px;">Sr No.</th>
											<th class="text-center">Membership / Registration No</th>
											<th class="text-center">Candidate Name</th>
											<th class="text-center">Exam Code</th>
											<th class="text-center">Exam Name</th>
											<th class="text-center">Amount</th>
											<th class="text-center">Experience Certificate</th>
											<th class="text-center">KYC Status</th>
											<th class="text-center">Remark</th>
											<th class="text-center">Created Date</th>
											<th class="text-center">Action</th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<link href="<?php echo base_url(); ?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.cssx" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">

<!-- Data Tables -->
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.jsx"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>

<script src="<?php echo base_url() ?>assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/plugins/datepicker/datepicker3.css">

<script src="<?php echo base_url() ?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.css">

<script src="<?php echo base_url() ?>assets/js/parsley.min.js"></script>
<script src="<?php echo base_url() ?>js/validation.js?<?php echo time(); ?>"></script>
<script type="text/javascript">
	$('#search').parsley('validate');
</script>
<style>
	div.dataTables_processing {
		position: absolute;
		top: 0%;
		left: 50%;
		width: 100%;
		height: 100%;
		margin-left: -50%;
		margin-top: -25px;
		padding-top: 20px;
		padding-bottom: 20px;
		text-align: center;
		font-size: 1.2em;
		background-color: white;
		/* background: -webkit-gradient(linear, left top, right top, color-stop(0%, rgba(255,255,255,0)), color-stop(25%, rgba(255,255,255,0.9)), color-stop(75%, rgba(255,255,255,0.9)), color-stop(100%, rgba(255,255,255,0))); */
		/* background: -webkit-linear-gradient(left, rgba(255,255,255,0) 0%, rgba(255,255,255,0.9) 25%, rgba(255,255,255,0.9) 75%, rgba(255,255,255,0) 100%); */
		/* background: -moz-linear-gradient(left, rgba(255,255,255,0) 0%, rgba(255,255,255,0.9) 25%, rgba(255,255,255,0.9) 75%, rgba(255,255,255,0) 100%); */
		/* background: -ms-linear-gradient(left, rgba(255,255,255,0) 0%, rgba(255,255,255,0.9) 25%, rgba(255,255,255,0.9) 75%, rgba(255,255,255,0) 100%); */
		/* background: -o-linear-gradient(left, rgba(255,255,255,0) 0%, rgba(255,255,255,0.9) 25%, rgba(255,255,255,0.9) 75%, rgba(255,255,255,0) 100%); */
		background: #0000001f;
	}

	.dataTables_processing h2 {
		position: absolute;
		top: 39%;
		bottom: 50%;
		left: 40%;
		font-weight: bold;
		/* background-color: white; */
		width: 200px;
		height: 45px;
		right: 43%;
		padding: 5px;
		text-align: center;
	}
</style>
<!--<script src="<?php echo base_url() ?>js/js-paginate.js"></script>-->
<script type="application/javascript">
	$(document).ready(function() {
		var table = $('.dataTables-example').DataTable({
			searching: true,
			"processing": true,
			"serverSide": true,
			initComplete: function() {
				var input = $('.dataTables_filter input').unbind(),
					self = this.api(),
					$searchButton = $('<button class="btn btn-sm btn-primary srch_btn">')
					.text('search')
					.click(function() {
						self.search(input.val()).draw();
					}),
					$clearButton = $('<button class="btn btn-sm btn-warning clr_btn">')
					.text('clear')
					.click(function() {
						input.val('');
						$searchButton.click();
					})
				$('.dataTables_filter').append($searchButton, $clearButton);
			},
			"ajax": {
				"url": '<?php echo site_url("admin/kyc/Approver/get_professional_banker_data_ajax"); ?>',
				"type": "POST",
				"data": function(d) {
					/* d.delete_ids_str = $("#selcted_checkbox_all_hidden").val();
					d.s_name = $("#s_name").val();
					d.s_conductor_main_id = $("#s_conductor_main_id").val();
					d.s_mobile = $("#s_mobile").val();
					d.s_kyc_status = $("#s_kyc_status").val();
					d.s_from_date = $("#s_from_date").val();
					d.s_to_date = $("#s_to_date").val(); */
				}
			},
			"lengthMenu": [
				[10, 25, 50, 100, 500],
				[10, 25, 50, 100, 500]
			],
			"language": {
				"lengthMenu": "_MENU_",
			},
			//"dom": '<"top"lf><"clear"><i>rt<"bottom row"<"col-sm-12 col-md-5" and i><"col-sm-12 col-md-7" and p>><"clear">',
			pageLength: 10,
			responsive: true,
			rowReorder: false,
			"columnDefs": [{
					"targets": 'no-sort',
					"orderable": false,
				},
				{
					"targets": [0],
					"className": "text-center"
				},
				{
					"targets": [6],
					"className": "text-center"
				},
				{
					"targets": [7],
					"className": "text-center"
				},
				{
					"targets": [8],
					"className": "break_word"
				},
				{
					"targets": [10],
					"className": "text-center"
				}
			],
			"aaSorting": [],
			"stateSave": false,
			oLanguage: {
				sProcessing: "<h2>Processing...</h2>"
			},
			'drawCallback': function(settings) {

			}
		});

		// $('.dataTables_filter input').unbind();
		// $('.dataTables_filter input').keyup(function(e) {
		// 	if (e.keyCode == 13) /* if enter is pressed */ {
		// 		table.search($(this).val()).draw();
		// 	}
		// });

	});
</script>
<?php $this->load->view('admin/kyc/includes/footer'); ?>