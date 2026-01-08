<?php $careeradminuserdata = $this->session->userdata('career_admin');
$data = $this->session->userdata('sessionData'); //print_r($data['user_type']);die;
?>
<!DOCTYPE html>
<html>

<head>
</head>
<?php $this->load->view('count_dashboard/includes/header'); ?>

<body class="hold-transition skin-blue sidebar-mini">
	<div class="wrapper">
		<?php $this->load->view('count_dashboard/includes/topbar'); ?>
		<?php $this->load->view('count_dashboard/includes/sidebar'); ?>
		<section class="content">
			<div class="content-wrapper">
				<section class="content-header">
					<h1>Scribe Member Details</h1>
				</section>
				<!-- Search Bar POOJA MANE:15/11/2022-->
				<div class="searchfilter">
					<div class="box-header">
						<div class="pull-right box-tools">
							<button type="button" class="mb-2 float-right btn btn-primary" data-toggle="collapse" data-target="#collapseExample">
								<i class="fa fa-filter"></i>
							</button>
						</div>
						<h3 class="page-title"><br></h3>
						<div class="collapsee" id="collapseExample">
							<form class="form-control" name="searchScribeDetails" id="searchScribeDetails" action="<?php echo site_url('count_dashboard/Count_list/SearchQry') ?>" ; method="post">
								<div class="row">

									<div class="col-sm-2">
										<div class="form-group">
											<label>Select Option</label>
											<select class="custom_filter form-control" name="searchBy" id="searchBy" required="">
												<option value="">Select</option>
												<option value="01" selected="">Scrib URN</option>
												<option value="02">Member no</option>
												<option value="03">Exam</option>
												<option value="04">Exam Date</option>
											</select>
										</div>
									</div>
									<div class="col-sm-3">
										<div class="form-group">
											<label>Enter Value</label>
											<input type="text" class="form-control" id="SearchVal" name="SearchVal" placeholder="" required="" value="">
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label>Search Details</label>
											<input type="submit" class="mb-2 float-right btn btn-primary" name="Search" id="btnSearch" value="Search">
										</div>
									</div>
									<!-- <div class="col-md-2">
	                      <div class="form-group">
	                        <label>Download Data</label>
	                        <button type="submit" name="download" id="download" value="Download" class="mb-2 btn btn-warning" href=" ">Download CSV</button>
	                      </div>
	                    </div> -->
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- Search Bar End POOJA MANE:15/11/2022--->

				<div class="">
					<div class="row">
						<div class="col-lg-12">
							<input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
							<input type="hidden" name="base_url_val" id="base_url_val" value="" />
							<form id="myForm" name="myForm" method="post" action="" enctype="multipart/form-data" role="form">
								<input type="hidden" id="security_token" name="
		                          <?php echo $this->security->get_csrf_token_name(); ?>" value="
		                          <?php echo $this->security->get_csrf_hash(); ?>">
								<div class="table-responsive">
									<table id="listitems" class="table table-bordered table-striped">
										<thead>
											<tr>
												<th>Sr.No.</th>
												<th>Scribe URN</th>
												<th>Member no</th>
												<th>Member Name</th>
												<th>Exam Name</th>
												<th>Subject Name</th>
												<th>Center Name</th>
												<th>Scribe Name</th>
												<th>Exam Date</th>
												<th>Applied Date</th>
												<th>Status</th>
												<th>View</th>
											</tr>
										</thead>
										<tbody class="no-bd-y" id="list2"> <?php
																			$k = 1;
																			if (!empty($result)) {
																				foreach ($result as $res) {
																					echo '
		                                <tr>
		                                  <td>' . $k . ' </td>';
																					echo '
		                                  <td>' . $res['scribe_uid'] . ' </td>';
																					echo '
		                                  <td>' . $res['regnumber'] . ' </td>';
																					echo '
		                                  <td>' . $res['firstname'] . ' </td>';
																					echo '
		                                  <td>' . $res['exam_name'] . ' </td>';
																					echo '
		                                  <td>' . $res['subject_name'] . ' </td>';
																					echo '
		                                  <td>' . $res['center_name'] . ' </td>';
																					echo '
		                                  <td>' . $res['name_of_scribe'] . ' </td>';
																					echo '
		                                  <td>' . $res['exam_date'] . ' </td>';
																					$Applied = date_create($res['created_on']);
																					echo '
		                                  <td>' . date_format($Applied, "Y-m-d") . '</td>';

																					if ($res['scribe_approve'] == 1) {
																						$reuest_status = '
		                                  <span class="reuest_status" style="color: green">APPROVED</span>';
																					} elseif ($res['scribe_approve'] == 2) {
																						$reuest_status = '
		                                  <span class="reuest_status" style="color: blue">PENDING</span>';
																					} elseif ($res['scribe_approve'] == 3) {
																						$reuest_status = '
		                                  <span class="reuest_status" style="color: red">REJECTED</span>';
																					} elseif ($res['scribe_approve'] == 0) {
																						$reuest_status = '
		                                  <span class="reuest_status" style="color: blue">NEW</span>';
																					} else {
																						$reuest_status  = '-';
																					}
																					echo '
		                                  <td>' . $reuest_status . '</td>';

																					echo '
		                                    <td>
		                                      <a class="btn btn-primary btn-xs vbtn" href="' . base_url() . 'count_dashboard/Count_list/view/' . $res['id'] . '">View</a>';
																					echo '
		                                    </tr>';
																					$k++;
																				}
																			} ?>
										</tbody>
									</table>
									<div id="links" class="dataTables_paginate paging_simple_numbers"></div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<br>
			</div>
		</section>
	</div>
</body>
<style>
	.box {
		width: 100%;
		max-width: 650px;
		margin: 0 auto;
	}

	.typeahead,
	.tt-query,
	.tt-hint {
		width: 340px;
		height: 30px;
		padding: 8px 12px;
		font-size: 15px;
		line-height: 30px;
		outline: none;
	}

	.box {
		position: relative;
		border-radius: 3px;
		background: #ffffff;
		border: 1px solid #00c0ef;
		margin-bottom: 15px;
		width: 100%;
	}

	#listitems_length {
		width: 10%;
	}

	form#myForm {
		background: #7fd1ea;
		padding: 20px;
	}
</style>
<!-- Data Tables -->
<link href="
  <?php echo base_url(); ?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="
  <?php echo base_url(); ?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="
  <?php echo base_url(); ?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">
<!-- Data Tables -->
<script src="
  <?php echo base_url(); ?>assets/admin/plugins/datatables/jquery.dataTables.js">
</script>
<script src="
  <?php echo base_url(); ?>assets/admin/plugins/datatables/dataTables.bootstrap.js">
</script>
<script src="
   <?php echo base_url(); ?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js">
</script>
<script src="
  <?php echo base_url(); ?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js">
</script>
<script src="
   <?php echo base_url() ?>js/js-paginate.js">
</script>
<script>
	$(function() {
		$('#listitems').DataTable({
			"bStateSave": true,
		});

	});

	if (window.history.replaceState) {
		window.history.replaceState(null, null, window.location.href);
	}
</script>

<?php $this->load->view('count_dashboard/includes/footer'); ?>