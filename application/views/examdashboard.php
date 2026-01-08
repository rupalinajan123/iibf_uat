<?php $this->load->view('webmanager/includes/header');?>
<?php $this->load->view('webmanager/includes/sidebar');?>

<!DOCTYPE html>
<html>
	<head>
<?php $this->load->view('google_analytics_script_common'); ?>
		<style> 
			.error{color: red;}
		</style>
		<title>Dashboard</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
		<script type="text/javascript" src="<?php echo base_url();?>js/validate.js"></script>
	</head>
	<body>
		<?php //$this->load->view('admin/admin_sidebar'); ?>
		<div class="content-wrapper" style="min-kash: 946px;">
			<!-- Main content -->
			<section class="content">
				<?php if ($this->session->flashdata('error_message') != "") 
		    	{ ?>
					<div class="alert alert-danger alert-dismissable">
						<i class="fa fa-ban"></i>
						<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
						<b>Alert!</b> <?php echo $this->session->flashdata('error_message'); ?>          
					</div>
				<?php } ?>
				<?php if ($this->session->flashdata('success_message') != "") 
					{ ?>
					<div class="alert alert-success alert-dismissable">
						<i class="fa fa-ban"></i>
						<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
						<b>Successfully!</b> <?php echo $this->session->flashdata('success_message'); ?>          
					</div>
				<?php } ?>
				<div class="container">
					<form id="myForm" name="myForm" method="post" action="" enctype="multipart/form-data" role="form">
						<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
				    <?php echo form_open('myForm'); ?>  
						<div class="row">
							<div class="col-sm-12">
								
								<div class="form-row">
									<br>    
									<div class="form-group col-md-4">
										
										<select name="description" id="description" class="form-control input-lg">
											<option value="">Exam Name</option>
											<?php 
												if(count($rest) > 0)
												{
													foreach($rest as $row)
													{ ?>
					    						<option <?php if($description == $row['exam_code'].'_'.$row['exam_period']){ echo 'selected';}?> value="<?php echo $row['exam_code'].'_'.$row['exam_period']; ?>"><?php echo $row['description'];?></option>	
													<?php }
												}
											?>
										</select>
										
										<div class="form-check" style="margin:5px 0 0 5px;">
											<input class="form-check-input" type="checkbox" value="1" id="elearning_check" name="elearning_check" <?php if($elearning_flag == 1) { echo "checked"; } ?>>
											<label class="form-check-label" for="elearning_check">E-Learning</label>
										</div>
									</div>
									
									<div class="form-group col-md-4">
										<input type="text" name="count" id="count" class="form-control input-lg" value="<?php echo $count;?>">
									</div>
								</div> 
								
							</div>	
							<div class="form-group" style="width: 1100px; margin: 0 auto;">
								<input type="submit" id="submit" name="submit" class="btn btn-info" value="Submit"> 
								<!-- <a href="http://iibf.teamgrowth.net/index.php/webmanager/Login/Logout" class="nav-link"><i class="fa fa-sign-out-alt"></i> Logout</a>   -->
							</div>		
						</div>
					</form>
				</div>
			</div>
		</body>
	</html>
	<script> 						
		$(document).ready(function(){ 
		$('#to').on('change', function(){ 
			var examID = $(this).val();
			
			if(examID){
				$.ajax({ alert('hi');
					type:'POST',
					url: 'http://iibf.teamgrowth.net/webmanager/Examdashboard',
					data:'to='+examID,
					success:function(html){
						$('#to').html(html.str);
					}
				}); 
			}
			else{
				$('#to').html('<option value="">Exam Name</option>');
			}
		});
    $('#center_name').on('change', function(){
			var centerID = $(this).val();
			
			if(centerID){
				$.ajax({ 
					type:'POST',
					url: 'http://iibf.teamgrowth.net/exam_venue/Seat_count/get_venue',
					data:'center_code='+centerID,
					success:function(html){
						$('#venue_name').html(html.str);
					}
				}); 
			}
			else{
				$('#venue_name').html('<option value="">Center</option>');
			}
		});
    $('#venue_name').on('change', function(){ 
			var venueID = $(this).val();
			
			if(venueID){ 
				$.ajax({ 
					type:'POST',
					url: 'http://iibf.teamgrowth.net/exam_venue/Seat_count/get_date',
					data:'center_code='+venueID,
					success:function(html){
						$('#exam_date').html(html.str);
						
					}
				}); 
			}
			else{
				$('#exam_date').html('<option value="">Venue</option>');
			}
		});
		$('#exam_date').on('change', function(){ 
			var venueID = $(this).val();
			
			if(venueID){ 
				$.ajax({ 
					type:'POST',
					url: 'http://iibf.teamgrowth.net/exam_venue/Seat_count/get_time',
					data:'center_code='+venueID,
					success:function(html){
						$('#session_time').html(html.str);
						
					}
				}); 
			}
			else{
				$('#exam_date').html('<option value="">Date</option>');
			}
		});    
		$('#submit').click(function()
		{
			//alert('hello');
			var venueID = $(this).val();
			
			if(venueID){ 
				$.ajax({ 
					type:'POST',
					url: 'http://iibf.teamgrowth.net/exam_venue/Seat_count/get_seat_count',
					data:'center_code='+venueID,
					success:function(html){
						html(html.str);
						
					}
				});  
			}
			else{
				//alert('hi');
				//$('#exam_date').html('<option value="">Date</option>');
			}
		});	
	});
</script>

<?php $this->load->view('webmanager/includes/footer');?>
