
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    	<h1> View Profile</h1>
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
                         <?php //echo"<pre>"; print_r($instdata); exit;?>
                        	<div class="form-group">
                            	<label for="name" class="col-sm-3 control-label">Agency Name</label>
                                <div class="col-sm-5">
                                    <?php echo $instdata['institute_name'];?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label">Year Of Establishment</label>
                                <div class="col-sm-5">
                                    <?php echo $instdata['estb_year'];?>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label">Year of Grant of DRA Accreditation</label>
                                <div class="col-sm-5">
                                    Not given
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label">Agency Code</label>
                                <div class="col-sm-5">
                                    <?php echo $instdata['institute_code'];?>
                                </div>
                            </div>

                            <div class="form-group">
                            	<label for="address" class="col-sm-3 control-label"><strong>Address for communication</strong></label>
                            </div>  

                              <div class="form-group">
                              <label for="address_line1" class="col-sm-3 control-label">Address line 1</label>
                                <div class="col-sm-5">
                                  <?php echo $instdata['address1'];?>
                                
                                </div> 
                            </div>
                              <div class="form-group">
                              <label for="address_line1" class="col-sm-3 control-label">Address line 2</label>
                                <div class="col-sm-5">
                                
                                  <?php echo $instdata['address2'];?><br>
                                  
                                </div> 
                            </div>
                              <div class="form-group">
                              <label for="address_line1" class="col-sm-3 control-label">Address line 3</label>
                                <div class="col-sm-5">
                                
                                  <?php echo $instdata['address3'];?><br>
                                
                                </div> 
                            </div>
                              <div class="form-group">
                              <label for="address_line1" class="col-sm-3 control-label">Address line 4</label>
                                <div class="col-sm-5">

                                  <?php echo $instdata['address4'];?><br>

                                </div> 
                            </div>
                              <div class="form-group">
                              <label for="address_line1" class="col-sm-3 control-label">Address line 5</label>
                                <div class="col-sm-5">

                                  <?php echo $instdata['address5'];?><br>
                                </div> 
                            </div>
                             <div class="form-group">
                                <label for="pincode" class="col-sm-3 control-label">State</label>
                                <div class="col-sm-5">
                                  <?php //echo $instdata['state'];?>
                                  <?php
                                    if(count($states) > 0){
                                        foreach($states as $row1)
                                        {   
                                            if($instdata['ste_code']== $row1['state_code']){
                                                echo  $row1['state_name'];
                                            } 
                                        } 
                                    } 
                                  ?>
                                </div>
                              </div>
                            </div>
                             <div class="form-group">
                                <label for="pincode" class="col-sm-3 control-label">City</label>
                                <div class="col-sm-5">
                                  <?php
                                   if(is_numeric($instdata['address6']))
                                    {
                                     echo $city_name = isset($city_name[0]['city_name']) ? strtoupper($city_name[0]['city_name']) : '';    
                                    }
                                    else
                                    {
                                     echo strtoupper($instdata['address6']);
                                    }
                                 ?>
                                </div>
                            </div>
                            <div class="form-group">
                            	<label for="pincode" class="col-sm-3 control-label">Pin Code</label>
                                <div class="col-sm-5">
                                  <?php echo $instdata['pin_code'];?>
                                </div>
                            </div>
                            
                            <div class="form-group">
                            	<label for="mobile" class="col-sm-3 control-label">Mobile No</label>
                                <div class="col-sm-5">
                                  <?php echo $instdata['mobile'];?>
                                </div>
                            </div> 
                            
                            <div class="form-group">
                            	<label for="emailid" class="col-sm-3 control-label">Email ID</label>
                                <div class="col-sm-5">
                                  <?php echo $instdata['email'];?>
                                </div>
                            </div> 
                            
                            <div class="form-group">
                            	<label for="contact_person" class="col-sm-3 control-label">Contact Person</label>
                                <div class="col-sm-5">
                                  <?php echo $instdata['coord_name'];?>
                                </div>
                            </div>
                            
                            <div class="form-group">
                            	<label for="contact_person" class="col-sm-3 control-label">GST-IN No</label>
                                <div class="col-sm-5">
                                  <?php echo $instdata['gstin_no'];?>
                                </div>
                            </div>
                             <div class="form-group">
                                <label for="contact_person" class="col-sm-3 control-label">Designation</label>
                                <div class="col-sm-5">
                                  <?php if($instdata['designation'] == "")
                                  {
                                    echo "-";
                                  }else
                                  {
                                     echo $instdata['designation'];
                                  }
                                   ?>
                                </div>
                            </div>  
                
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
</div><!-- /.content-wrapper