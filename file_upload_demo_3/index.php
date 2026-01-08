<?php
	
	include "database.php";
	
	$userLogin = isUserLogin();
	if (isUserLogin()) {
    $email = loginEmail();
    $query = mysqli_query($conn, "SELECT * FROM upload_webcam WHERE user_email = '$email' ");
    $row = mysqli_fetch_array($query);
	}
	
	
?>
<!DOCTYPE html>
<html lang="en">
	
	<head>
    <title>Jamsrworld.com</title>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">
    <link href="assets/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="assets/css/font.css">
    <link rel="stylesheet" href="assets/css/cropper.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://kit.fontawesome.com/a81368914c.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	</head>
	
	<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed" style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px" cz-shortcut-listen="true">


		 


    <div class="d-flex flex-column flex-root">
			<div class="page d-flex flex-row flex-column-fluid">
				
				<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
					
					<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
						<div class="product d-flex flex-column-fluid" id="kt_product">
							<div id="kt_content_container" class="container-xxl">
								
								<div class="card w-100 w-lg-500px mx-auto mb-5 mb-xl-8">
									<div class="card-body pt-15">
										<?php if ($userLogin) {
										?>
										<div class="d-flex flex-center flex-column mb-5">											
											<div class="mb-4">
												<div id="profileContainer" class="image-input image-input-outline image-input-circle image-input-empty">
													<div class="profile-progress"></div>
													<?php if(isset($row["photo"]) && $row["photo"] == "") { $row["photo"] = 'avatar.png'; } ?>
													<img id="profileImage" class="image-input-wrapper w-125px h-125px" src="<?php echo "assets/images/" . $row["photo"]; ?> ">
													
													<button type="button" class="btn btn-sm btn-primary w-100 mb-5" onclick="open_img_upload_modal('profileImage','photo')">Select Photo</button>
													<?php /*<label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-bs-toggle="modal" data-bs-target="#optionsModal" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change avatar">
														<i class="bi bi-pencil-fill fs-7"></i>
													</label>
													
													<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove avatar">
														<i class="bi bi-x fs-2"></i>
													</span> */ ?>
												</div>
												
												
												
												<div class="image-input image-input-outline image-input-circle image-input-empty">
													<div class="profile-progress"></div>
													<?php if(isset($row["sign"]) && $row["sign"] == "") { $row["sign"] = 'avatar.png'; } ?>
													<img id="profileSign" class="image-input-wrapper w-125px h-125px" src="<?php echo "assets/images/" . $row["sign"]; ?> ">
													
													<button type="button" class="btn btn-sm btn-primary w-100 mb-5" onclick="open_img_upload_modal('profileSign','sign')">Select Sign</button>													
												</div>
											</div>
										</div>
										
										</div>
										<?php
											} else {
										?>
										<h1 class="text-gray-800 text-center fs-2x my-3">Login</h1>
										<form class="form w-100" id="loginForm">
											<div class="mb-10">
												<label class="form-label fs-6 fw-bolder text-dark">Full Name</label>
												<input required placeholder="Enter Full Name" class="form-control form-control-lg" type="text" name="full_name">
											</div>
											<div class="mb-10">
												<label class="form-label fw-bolder text-dark fs-6 mb-0">Email</label>
												<input required placeholder="Enter Email" class="form-control form-control-lg" type="email" name="email">
												<div class="fv-plugins-message-container invalid-feedback"></div>
											</div>
											<button type="submit" id="submit" class="btn btn-lg btn-primary w-100 mb-5">
												<span class="indicator-label">Continue</span>
												<span class="indicator-progress">Please wait...
												<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
											</button>
											<div></div>
										</form>
										<?php
										}
										?>
										
									</div>
								</div>
								
							</div>
						</div>
					</div>
					
					
				</div>
			</div>
		</div>
		
    <!-- Modals -->
		
    <div class="modal fade" id="optionsModal" tabindex="-1" style="display: none;" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered mw-450px">
				<div class=" modal-content">
					<div class="modal-header">
						<h2 class="fw-bolder">Change avatar</h2>
						<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
							<span class="svg-icon svg-icon-1">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
									<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"></rect>
									<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"></rect>
								</svg>
							</span>
						</div>
					</div>
					<div class="modal-body">
						<input type="hidden" name="current_image_id" id="current_image_id">
						<input type="hidden" name="db_col_name" id="db_col_name">
						
						<input class="sr-only" id="fileChooser" type="file" name="avatar" accept=".png, .jpg, .jpeg">
						<label for="fileChooser" class="btn btn-primary">Upload</label>
						<button id="openWebCamModal" class="btn btn-primary">Camera</button>
						<?php /* <button id="editProfile" class="btn btn-primary">Edit</button>
						<button id="removeProfile" class="btn btn-primary">Remove</button> */ ?>
					</div>
				</div>
			</div>
		</div>
		
    <div class="modal fade" id="webCamModal" tabindex="-1" style="display: none;" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class=" modal-content">
					<div class="modal-header">
						<h2 class="fw-bolder">Take a picture</h2>
						<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
							<span class="svg-icon svg-icon-1">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
									<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"></rect>
									<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"></rect>
								</svg>
							</span>							
						</div>
					</div>
					<div class="modal-body">
						<div id="webCameraArea"></div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" id="spanshot">Take a picture</button>
					</div>
				</div>
			</div>
		</div>
		

	  <div class="modal fade" id="webcamErrormodal" tabindex="-1" style="display: none;" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class=" modal-content">
					<div class="modal-header">
						<h2 class="fw-bolder">Camera Permission</h2> 
					</div>
					<div class="modal-body">
          <h3 id="webcamErrormodalMessage" class="text-danger text-center mt-6 mb-6"></h3>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> 
					</div>
				</div>
			</div>
		</div>
			
		
    <div class="modal fade" id="cropModal" tabindex="-1" style="display: none;" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class=" modal-content">
					<div class="modal-header">
						<h2 class="fw-bolder">Make a selection</h2>
						<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
							<span class="svg-icon svg-icon-1">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
									<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"></rect>
									<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"></rect>
								</svg>
							</span>
						</div>
					</div>
					<div class="modal-body">
						<div id="cropimage">
							<img id="imageprev" src="" />
						</div>
						
						<div class="progress mt-6">
							<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
						</div>
					</div>
					<div class="modal-footer d-flex justify-content-between">
						<div class="d-flex">
							<button type="button" class="btn btn-light-primary btn-sm" id="rotateL" title="Rotate Left">
							<i class="fas fa-undo"></i></button>
							<button type="button" class="ms-2 btn btn-light-primary btn-sm" id="rotateR" title="Rotate Right">
								<i class="fas fa-repeat"></i>
							</button>
							<button type="button" class="ms-2 btn btn-light-primary btn-sm" id="scaleX" title="Flip Horizontal">
								<i class="fa fa-arrows-h"></i>
							</button>
							<button type="button" class="ms-2 btn btn-light-primary btn-sm" id="scaleY" title="Flip Vertical">
								<i class="fa fa-arrows-v"></i>
							</button>
							<button type="button" class="ms-2 btn btn-light-primary btn-sm" id="reset" title="Reset">
								<i class="fas fa-refresh"></i>
							</button>
						</div>
						<div class="d-flex"> <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
							<button type="button" class="ms-2 btn btn-light-primary float-right" id="saveAvatar">Save</button>
						</div>
						
					</div>
				</div>
				
			</div>


   

		</div>
    <!-- Modals -->
		
		
    <script src="assets/js/jquery.js?<?php echo time(); ?>"></script>
    <script src="assets/js/bootstrap.min.js?<?php echo time(); ?>"></script>
    <script src="assets/js/webcam.min.js?<?php echo time(); ?>"></script>
    <script src="assets/js/cropper.js?<?php echo time(); ?>"></script>
    <?php /* <script src="assets/js/alert.js"></script> */ ?>
    <script src="assets/js/script.js?<?php echo time(); ?>"></script>
		
		<script>
			function open_img_upload_modal(current_image_id,db_col_name)
			{
				$("#current_image_id").val(current_image_id);
				$("#db_col_name").val(db_col_name);
				$("#optionsModal").modal('show')
			}  
		</script>
		
	</body>
	
</html>