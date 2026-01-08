<!-- custom style for datepicker dropdowns -->
<style>
.example {
    width: 33%;
    min-width: 370px;
   /* padding: 15px;*/
    display: inline-block;
    box-sizing: border-box;
    /*text-align: center;*/
}

.example select {
    padding: 10px;
    background: #ffffff;
    border: 1px solid #CCCCCC;
    border-radius: 3px;
    margin: 0 3px;
}

.example select.invalid {
    color: #E9403C;
}
.mandatory-field, .required-spn {
	color:#F00;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    	<h1> Upload Excel File</h1>
		  <!--<a  class="btn btn-info" href = "javascript:history.back()" style="float:right">Back</a>-->
		 
        <?php ////echo $breadcrumb;?>
    </section>
	 
    	<section class="content">
      		<div class="row">
        		<div class="col-md-12">
          			<!-- Horizontal Form -->
					
						<?php 
						/*  if(validation_errors()!=''){?>
						<div class="alert alert-danger alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<?php //echo validation_errors(); ?>
						</div>
						<?php } */ ?> 
						<?php
							if(isset($errmsg)){ ?>
								<div class="alert alert-danger alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<?php echo $errmsg; ?>
								</div>
								
						<?php 	} 
							if(validation_errors()!=''){?>
								
								<div class="alert alert-danger alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<?php echo validation_errors(); ?>
								</div>
								<?php 	}
								if(isset($succmsg)) {?>
						        <div class="alert alert-success alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<?php echo $succmsg; ?>
								</div>
								
						
						<?php } ?>
				
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Upload Excel File</h3>		
						</div>
						<div class="box-body">
							<form class="form-horizontal"  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>bulk/BulkApplyExcel/read_xlsx/">
							
		  
								<div class="form-group">
											<label class="col-sm-3 control-label">Upload file</label>
											<div class="col-sm-5">
												<input type="file" class="form-control"  name="csv_file" id="csv_file"  required>
											</div><input type="submit" name="submit" value="Submit" />
								</div>                                    
							</form>  
						</div>	
					</div>	
				</div> 
			</div> 
        </section>
	</div>