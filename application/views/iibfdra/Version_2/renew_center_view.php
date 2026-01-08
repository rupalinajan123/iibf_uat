<style>
.control-label {
	font-weight: bold !important;
}
.act_msg {
    font-size: 14px;
    font-style: italic;
    color: #D8000C !!important;  
}
</style>
<div class="content-wrapper">
  <section class="content-header">
    <h1> Center View </h1>
  </section>
  <form class="form-horizontal" name="drafrmpreview" id="drafrmpreview"  method="post"  enctype="multipart/form-data" 
    action="">
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Added Center view</h3>
              <div class="pull-right"> <a href="<?php echo base_url();?>iibfdra/Version_2/Center/listing" class="btn btn-warning">Center listing</a> </div>
            </div>
            
            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">State <span style="color:#F00">*</span></label>
              <div class="col-sm-5">
                <?php
                    if(count($states) > 0){
                        foreach($states as $row1){ 	 
                            if($center_view[0]['state']== $row1['state_code']){
                                echo  $row1['state_name'];
                            } 
                        } 
                    } 
                  ?>
              </div>             
            </div>
            
            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Name Of Location(City)<span style="color:#F00">*</span></label>
              <div class="col-sm-2"><?php
              if(count($cities) > 0){
                  foreach($cities as $row){   
                      if($center_view[0]['location_name'] == $row['id']){
                          echo  $row['city_name'];
                      } 	
                  } 
              }else{
				  	echo $center_view[0]['location_name'];
			  } 
            ?> </div>
            <label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode <span style="color:#F00">*</span></label>
              <div class="col-sm-2"> <?php echo $center_view[0]['pincode'];?> </div>
            </div>
            
            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Address line1 <span style="color:#F00">*</span></label>
              <div class="col-sm-5">
                <?php if($center_view[0]['address1'] != ""){ echo $center_view[0]['address1'];} else { echo "-";}?>
              </div>
            </div>
            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Address line2</label>
              <div class="col-sm-5">
                <?php if($center_view[0]['address2'] != ""){ echo $center_view[0]['address2']; } else { echo "-";}?>
              </div>
            </div>
            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Address line3</label>
              <div class="col-sm-5">
                <?php if($center_view[0]['address3'] != ""){ echo $center_view[0]['address3']; } else { echo "-";}?>
              </div>
            </div>
            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Address line4</label>
              <div class="col-sm-5">
                <?php if($center_view[0]['address4'] != ""){ echo $center_view[0]['address4']; } else { echo "-";}?>
              </div>
            </div>
            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">District<span style="color:#F00">*</span></label>
              <div class="col-sm-5"> <?php echo $center_view[0]['district'];?> </div>
            </div>
            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">City<span style="color:#F00">*</span></label>
              <div class="col-sm-5"><?php
              if(count($cities) > 0){
                  foreach($cities as $row){   
                      if($center_view[0]['location_name'] == $row['id']){
                          echo  $row['city_name'];
                      } 	
                  } 
              }else{
				  	echo $center_view[0]['location_name'];
			  } 
            ?> </div>
            </div>
            
            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Office Number<span style="color:#F00">*</span></label>
              <div class="col-sm-6"> <?php echo $center_view[0]['office_no'];?> </div>
            </div>
            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Contact Person Name<span style="color:#F00">*</span></label>
              <div class="col-sm-6"> <?php echo $center_view[0]['stdcode'];?> - <?php echo $center_view[0]['contact_person_name'];?> </div>
            </div>
            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Mobile Number<span style="color:#F00">*</span></label>
              <div class="col-sm-6"> <?php echo $center_view[0]['contact_person_mobile'];?> </div>
            </div>
            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Email id<span style="color:#F00">*</span></label>
              <div class="col-sm-6"> <?php echo $center_view[0]['email_id'];?> </div>
            </div>
            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Center Type <span style="color:#F00">*</span></label>
              <div class="col-sm-3">
                <?php if($center_view[0]['center_type']=='R'){echo 'Regular';}?>
                <?php if($center_view[0]['center_type']=='T'){echo 'Temporary';}?>
              </div>
            </div>
            <?php 
            if($center_view[0]['center_type']=='T')
            {?>
            <?php if($center_view[0]['faculty_name1']=="" ){ } else{ ?>
           
            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">1.Faculty <span style="color:#F00">*</span></label>
              <div class="col-sm-6"> <?php echo $center_view[0]['faculty_name1'];?> 
                <?php echo"(".$center_view[0]['faculty_qualification1'].")";?> &nbsp; 
                <a class="btn  btn-info btn-xs"  href="<?php echo base_url()?>uploads/iibfdra/Version_2/agency_center/faculty_cv/<?php echo $center_view[0]['cv1'];?> " target="_blank"> View CV </a> </div>
            </div>
			<?php } ?>
            
            <?php if($center_view[0]['faculty_name2']=="" ){ } else{ ?>
             <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">2.Faculty </label>
              <div class="col-sm-6"> <?php echo $center_view[0]['faculty_name2'];?>
              <?php echo"(". $center_view[0]['faculty_qualification2'].")";?>&nbsp; <a class="btn  btn-info btn-xs"  href="<?php echo base_url()?>uploads/iibfdra/Version_2/agency_center/faculty_cv/<?php echo $center_view[0]['cv2'];?> " target="_blank"> View CV </a> </div>
            </div>
            <?php } ?>
            <?php if($center_view[0]['faculty_name3']==""){ } else{ ?>
             <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">3.Faculty</label>
              <div class="col-sm-6"> <?php echo $center_view[0]['faculty_name3'];?>
                <?php echo"(". $center_view[0]['faculty_qualification3'].")";?> &nbsp; <a class="btn  btn-info btn-xs"  href="<?php echo base_url()?>uploads/iibfdra/Version_2/agency_center/faculty_cv/<?php echo $center_view[0]['cv3'];?> " target="_blank"> View CV </a>
            </div>
           </div><?php } ?>

            <?php if($center_view[0]['faculty_name4']==""){ } else{ ?>
             <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">4.Faculty </label>
              <div class="col-sm-6"> <?php echo $center_view[0]['faculty_name4'];?>
              <?php echo"(". $center_view[0]['faculty_qualification4'].")";?>&nbsp; <a class="btn  btn-info btn-xs"  href="<?php echo base_url()?>uploads/iibfdra/Version_2/agency_center/faculty_cv/<?php echo $center_view[0]['cv4'];?> " target="_blank"> View CV </a> </div>
            </div>
            <?php } ?>
            <?php if($center_view[0]['faculty_name5']==""){ } else{ ?>
             <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">5.Faculty </label>
              <div class="col-sm-6"> <?php echo $center_view[0]['faculty_name5'];?>
              <?php echo "(".$center_view[0]['faculty_qualification5'].")";?>&nbsp; <a class="btn  btn-info btn-xs"  href="<?php echo base_url()?>uploads/iibfdra/Version_2/agency_center/faculty_cv/<?php echo $center_view[0]['cv5'];?> " target="_blank"> View CV </a>  </div> 
            </div>
            <?php }?>
             <div class="form-group">
               <label for="roleid" class="col-sm-4 control-label">Request Letter from Accredited Institute<span style="color:#F00">*</span></label>
               <div class="col-sm-6"> 
  			         <a href="<?php echo base_url()?>uploads/iibfdra/Version_2/agency_center/<?php echo $center_view[0]['upload_file1'];?> " target="_blank"><?php echo $center_view[0]['upload_file1'];?> </a>
  			       </div>
             </div>
             <div class="form-group">
               <label for="roleid" class="col-sm-4 control-label">Letter From Sponsoring Agency</label>
                <div class="col-sm-6">
                <?php if($center_view[0]['upload_file2']!="")
                {?>
                  <a href="<?php echo base_url()?>uploads/iibfdra/Version_2/agency_center/<?php echo $center_view[0]['upload_file2'];?> " target="_blank"><?php echo $center_view[0]['upload_file2'];?> </a>
                  <?php }else{ echo"-"; }?>
                </div>
            </div><?php }?>
            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Due diligence </label>
              <div class="col-sm-3">
                <?php if($center_view[0]['due_diligence']=='Yes'){echo 'Yes';}?>
                <?php if($center_view[0]['due_diligence']=='No'){echo 'No';}?>
              </div>
            </div>
            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">GSTIN No</label>
              <div class="col-sm-6"> <?php echo $center_view[0]['gstin_no'];?> </div>
            </div>
            
            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Remarks</label>
              <div class="col-sm-6"> <?php echo $center_view[0]['remarks'];?> </div>
            </div>
            
            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Invoice Address</label>
              <div class="col-sm-6"> <?php if($center_view[0]['invoice_flag']=='CS')
              {?>
                <span style="color:#090;">'Accreditation Centre'</span> address on the invoice
              <?php }else
              {?>
                <span style="color:#090;">'Agency'</span> address on the invoice
             <?php }
                ?> <span class="error">
                <?php //echo form_error('nameOfBank');?>
                </span> </div>
            </div>   
            <!-- Make Payment Start-->
            <?php
			if(count($center_reject_text) > 0 && $center_view[0]['center_status'] == 'R'){ ?>
              <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Reason Of Center Rejection</label>
              <div class="col-sm-6 act_msg"> <strong class="act_msg red" style="
    color: red;"><?php echo $center_reject_text[0]['rejection'];?> </strong> </div>
            </div>          
				<?php } ?>
                
			<?php
			
			      // This is only for Approve Centers
				//  print_r($center_view);				  
            if($center_view[0]['center_status'] == 'A' && $center_view[0]['pay_status'] != '1')
        	  {
        	  ?>
            <div class="box-footer">
              <div class="col-sm-6 col-sm-offset-3">
                <div class="col-sm-12">
                  <center>
                    <a class="btn btn-success" id="make_payment" name="make_payment" href="<?php echo base_url();?>iibfdra/Version_2/CenterRenew/renew_temporary_center/<?php echo $center_view[0]['center_id'];?>">Make Payment</a>
                  </center>
                </div>
              </div>
            </div>
            <?php } else if($center_view[0]['pay_status'] == '1') {?>
            <div class="box-footer">
              <div class="col-sm-6 col-sm-offset-3">
                <div class="col-sm-12">
                  <center>
                   <strong style="color:#090">PAID</strong>
                  </center>
                </div>
              </div>
            </div>
            <?php }?>
            
            
            <!-- Make Payment Close--> 
          </div>
        </div>
      </div>
    </section>
  </form>
</div>
