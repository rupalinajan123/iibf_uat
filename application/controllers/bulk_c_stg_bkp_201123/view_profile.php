
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    	<h1> Bank Profile</h1>
    </section>
    <form class="form-horizontal" name="draInstEditfrm" id="draInstEditfrm"  method="post"  enctype="multipart/form-data">
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                <!-- Horizontal Form -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Basic Details</h3>
                        </div><!-- /.box-header -->
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
                            <?php } ?> 
                            
                           <?php  if(isset($instRes))
                            {?>
                        	<div class="form-group">
                            	<label for="name" class="col-sm-3 control-label"><strong style="color:#000">Name :</strong></label>
                                <div class="col-sm-5">
                                    <?php 
									if(isset($instRes[0]['institute_name']))
									{
									  echo $instRes[0]['institute_name'];
									}else
									{
									  echo '';
						        	}?>
                                </div>
                            </div>
                            
                            <div class="form-group">
                            	<label for="address" class="col-sm-3 control-label"><strong style="color:#000">Address for Communication :</strong></label>
                            </div>  
                            
                            <div class="form-group">
                            	<label for="address_line1" class="col-sm-3 control-label"><strong style="color:#000">Address Line 1 :</strong></label>
                                <div class="col-sm-5">
                                  <?php
								  if(isset($instRes[0]['address1']))
									{
									  echo $instRes[0]['address1'];
									}else
									{
									  echo '';
						        	}?>
                                </div> 
                            </div>
                            
                            <div class="form-group">
                            	<label for="address_line2" class="col-sm-3 control-label"><strong style="color:#000">Address Line 2 :</strong></label>
                                <div class="col-sm-5">
                                  <?php
								  if(isset($instRes[0]['address2']))
									{
									  echo $instRes[0]['address2'];
									}else
									{
									  echo '';
						        	}?>
                                  <?php //echo $instdata['address2'];?>
                                </div>
                            </div> 
                             
                            <div class="form-group">
                            	<label for="address_line3" class="col-sm-3 control-label"><strong style="color:#000">Address Line 3 :</strong></label>
                                <div class="col-sm-5">
                                   <?php
								  if(isset($instRes[0]['address3']))
									{
									  echo $instRes[0]['address3'];
									}else
									{
									  echo '';
						        	}?>
							
                                </div>
                            </div>  
                            
                            <div class="form-group">
                            	<label for="address_line2" class="col-sm-3 control-label"><strong style="color:#000">Address Line 4 :</strong></label>
                                <div class="col-sm-5">
                                       <?php
								  if(isset($instRes[0]['address4']))
									{
									  echo $instRes[0]['address4'];
									}else
									{
									  echo '';
						        	}?>
                                
                                </div> 
                            </div> 
                            
                            <div class="form-group">
                            	<label for="pincode" class="col-sm-3 control-label"><strong style="color:#000">Pin Code :</strong></label>
                                <div class="col-sm-5">
                                   <?php
								  if(isset($instRes[0]['pin_code']))
									{
									  echo $instRes[0]['pin_code'];
									}else
									{
									  echo '';
						        	}?>
                                
                                </div>
                            </div>
                            
                            <div class="form-group">
                            	<label for="mobile" class="col-sm-3 control-label"><strong style="color:#000">Mobile No. :</strong></label>
                                <div class="col-sm-5">
                                 
                                  <?php
								  if(isset($instRes[0]['mobile']))
									{
									  echo $instRes[0]['mobile'];
									}else
									{
									  echo '';
						        	}?>
                                 
                               
                                </div>
                            </div> 
                            
                            <div class="form-group">
                            	<label for="emailid" class="col-sm-3 control-label"><strong style="color:#000">Email ID </strong>:</label>
                                <div class="col-sm-5">
                                    <?php
								  if(isset($instRes[0]['email']))
									{
									  echo $instRes[0]['email'];
									}else
									{
									  echo '';
						        	}?>
                                
                                </div>
                            </div> 
                            
                            <div class="form-group">
                            	<label for="contact_person" class="col-sm-3 control-label"><strong style="color:#000">Contact Person Name :</strong> </label>
                                <div class="col-sm-5">
                                  <?php
								  if(isset($instRes[0]['coord_name']))
									{
									  echo $instRes[0]['coord_name'];
									}else
									{
									  echo '';
						        	}?>  
                                  <?php //echo $instdata['coord_name'];?>
                                </div>
                            </div>  
                            
                            <div class="form-group">
                            	<label for="contact_person" class="col-sm-3 control-label"><strong style="color:#000">Contact Person Designation :</strong></label>
                                <div class="col-sm-5">
                                 <?php
								  if(isset($instRes[0]['designation']))
									{
									  echo $instRes[0]['designation'];
									}else
									{
									  echo '';
						        	}?> 
                                  <?php //echo $instdata['designation'];?>
                                </div>
                            </div> 
                            
                            <div class="form-group">
                            	<label for="gstin_no" class="col-sm-3 control-label"><strong style="color:#000">GSTIN No. :</strong></label>
                                <div class="col-sm-5">
                                 <?php
								  if(isset($instRes[0]['gstin_no']))
									{
									  echo $instRes[0]['gstin_no'];
									}else
									{
									  echo '';
						        	}?> 
                                  <?php //echo $instdata['gstin_no'];?>
                                </div>
                            </div>
                            <?php } ?>  
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <div class="col-sm-2 col-xs-offset-5">
                            	<!--<input class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Submit" type="submit">-->
                           </div>
                      	</div>
                    </div><!-- /.box-info -->
                </div><!-- /.col-md-12 -->
            </div><!-- /.row -->
        </section><!-- /.content -->
	</form>
</div><!-- /.content-wrapper -->

