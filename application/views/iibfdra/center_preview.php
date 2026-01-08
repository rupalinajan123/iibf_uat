<style>
.control-label {
	font-weight: bold !important;
}
</style>
<div class="content-wrapper">
  <section class="content-header">
    <h1> Center Preview </h1>
    <h4>Please go through the given detail, correction may be made if necessary. <a href="javascript:window.history.go(-1);">Modify</a></h4>
  </section>
  <form class="form-horizontal" name="drafrmpreview" id="drafrmpreview"  method="post"  enctype="multipart/form-data" 
    action="<?php echo base_url()?>iibfdra/Center/register">
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Added Center Preview</h3>
            </div>
            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">State <span style="color:#F00">*</span></label>
              <div class="col-sm-5">
                <?php
                    if(count($states) > 0){
                        foreach($states as $row1){   
                            if($this->session->userdata['userinfo']['state']== $row1['state_code']){
                                echo  $row1['state_name'];
                            } 
                        } 
                    } 
                    ?>
              </div>
              
            </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Name Of Location(City)<span style="color:#F00">*</span></label>
                <div class="col-sm-2"> <?php 
                if(count($cities) > 0){
                      foreach($cities as $row){   
                          if($this->session->userdata['userinfo']['city']== $row['id']){
                              echo  $row['city_name'];
                          } 
                      } 
                  }  ?>  
                </div>
                <label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode <span style="color:#F00">*</span></label>
                <div class="col-sm-2"> <?php echo $this->session->userdata['userinfo']['pincode'];?> </div>
            </div>

            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Address line1 <span style="color:#F00">*</span></label>
              <div class="col-sm-5"> <?php echo $this->session->userdata['userinfo']['addressline1'];?> </div>
            </div>
            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Address line2</label>
              <div class="col-sm-5"> <?php if($this->session->userdata['userinfo']['addressline2']==""){ echo"-";}else{ echo $this->session->userdata['userinfo']['addressline2'];}?> </div>
            </div>
            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Address line3</label>
              <div class="col-sm-5"> <?php if($this->session->userdata['userinfo']['addressline3']==""){ echo"-";}else{ echo $this->session->userdata['userinfo']['addressline3']; }?> </div>
            </div>
            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Address line4</label>
              <div class="col-sm-5"> <?php if($this->session->userdata['userinfo']['addressline4']==""){ echo"-";}else{ echo $this->session->userdata['userinfo']['addressline4']; }?> </div>
            </div>
            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">District<span style="color:#F00">*</span></label>
              <div class="col-sm-5"> <?php echo $this->session->userdata['userinfo']['district'];?> </div>
            </div>

            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Office Number<span style="color:#F00">*</span></label>
              <div class="col-sm-6"> <?php echo $this->session->userdata['userinfo']['stdcode'];?> - <?php echo $this->session->userdata['userinfo']['office_no'];?> </div>
            </div>
            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Contact Person Name<span style="color:#F00">*</span></label>
              <div class="col-sm-6"> <?php echo $this->session->userdata['userinfo']['contact_person_name'];?> </div>
            </div>
            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Mobile Number<span style="color:#F00">*</span></label>
              <div class="col-sm-6"> <?php echo $this->session->userdata['userinfo']['contact_person_mobile'];?> </div>
            </div>
            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Email id<span style="color:#F00">*</span></label>
              <div class="col-sm-6"> <?php echo $this->session->userdata['userinfo']['email_id'];?> </div>
            </div>
            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Center Type <span style="color:#F00">*</span></label>
              <div class="col-sm-3">
                <?php if($this->session->userdata['userinfo']['center_type']=='R'){echo 'Regular';}?>
                <?php if($this->session->userdata['userinfo']['center_type']=='T'){echo 'Temporary';}?>
              </div>
            </div>
           
           <?php 
            if($this->session->userdata['userinfo']['center_type']=='T')
            {?>
            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Faculty 1<span style="color:#F00">*</span></label>
              <div class="col-sm-6"> <?php echo $this->session->userdata['userinfo']['faculty_name1'];?>
                <?php echo "(".$this->session->userdata['userinfo']['faculty_qualification1']. ")";?>
                 &nbsp; <a class="btn  btn-info btn-xs"  href="<?php echo base_url()?>uploads/iibfdra/agency_center/faculty_cv/<?php echo $this->session->userdata['userinfo']['cv1'];?> " target="_blank"> View CV </a>
               </div>
            </div>

            <?php if($this->session->userdata['userinfo']['faculty_name2']==""){ } else{?>
             <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Faculty 2</label>
              <div class="col-sm-6"><?php echo $this->session->userdata['userinfo']['faculty_name2']; ?>
              <?php echo"(".$this->session->userdata['userinfo']['faculty_qualification2'].")";?>&nbsp; <a class="btn  btn-info btn-xs"  href="<?php echo base_url()?>uploads/iibfdra/agency_center/faculty_cv/<?php echo $this->session->userdata['userinfo']['cv2'];?> " target="_blank"> View CV </a>   </div>
            </div>
            <?php }
            if($this->session->userdata['userinfo']['faculty_name3']==""){ } else{
            ?>
             <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Faculty 3</label>
              <div class="col-sm-6"> <?php echo $this->session->userdata['userinfo']['faculty_name3']; ?>
              <?php echo "(". $this->session->userdata['userinfo']['faculty_qualification3'].")";?> &nbsp; <a class="btn  btn-info btn-xs"  href="<?php echo base_url()?>uploads/iibfdra/agency_center/faculty_cv/<?php echo $this->session->userdata['userinfo']['cv3'];?> " target="_blank"> View CV </a> </div>
            </div>
          
            <?php }
            if($this->session->userdata['userinfo']['faculty_name4']==""){ } else{
            ?>
             <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Faculty 4</label>
              <div class="col-sm-6"> <?php echo $this->session->userdata['userinfo']['faculty_name4'];?>
               <?php  echo "(". $this->session->userdata['userinfo']['faculty_qualification4'].")"; ?>&nbsp; <a class="btn  btn-info btn-xs"  href="<?php echo base_url()?>uploads/iibfdra/agency_center/faculty_cv/<?php echo $this->session->userdata['userinfo']['cv4'];?> " target="_blank"> View CV </a> </div>
            </div>
            
            <?php }
            if($this->session->userdata['userinfo']['faculty_name5']==""){} else{
            ?>
             <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Faculty 5</label>
              <div class="col-sm-6"> <?php echo $this->session->userdata['userinfo']['faculty_name5'];?> <?php echo"(". $this->session->userdata['userinfo']['faculty_qualification5'].")";?> &nbsp; <a class="btn  btn-info btn-xs"  href="<?php echo base_url()?>uploads/iibfdra/agency_center/faculty_cv/<?php echo $this->session->userdata['userinfo']['cv5'];?> " target="_blank"> View CV </a> </div>
            </div>
            
            <?php }?>
             <div class="form-group">
               <label for="roleid" class="col-sm-4 control-label">Request Letter from Accredited Institute<span style="color:#F00">*</span></label>
              <div class="col-sm-6"> 
            <a href="<?php echo base_url()?>uploads/iibfdra/agency_center/<?php echo $this->session->userdata['userinfo']['upload_file1'];?>" target="_blank"><?php echo $this->session->userdata['userinfo']['upload_file1'];?></a> 
          </div>
            </div>
             <div class="form-group">
               <label for="roleid" class="col-sm-4 control-label">Letter From Sponsoring Agency</label>
              <div class="col-sm-6"> 
                <?php if($this->session->userdata['userinfo']['upload_file2']!="")
                {?>
                    <a href="<?php echo base_url()?>uploads/iibfdra/agency_center/<?php echo $this->session->userdata['userinfo']['upload_file2'];?>" target="_blank"><?php echo $this->session->userdata['userinfo']['upload_file2'];?></a>
                <?php }else{ echo"-"; }?>
               </div>
            </div>
            <?php }?>
            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Due diligence </label>
              <div class="col-sm-3">
                <?php if($this->session->userdata['userinfo']['due_diligence']=='Yes'){echo 'Yes';}?>
                <?php if($this->session->userdata['userinfo']['due_diligence']=='No'){echo 'No';}?>
                <?php if($this->session->userdata['userinfo']['due_diligence']==''){echo '-';}?>
              </div>
            </div>
            
            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">GST No</label>
              <div class="col-sm-6"><?php if($this->session->userdata['userinfo']['gstin_no']==""){ echo "-";}else{echo $this->session->userdata['userinfo']['gstin_no'];}?> 
              <span class="error"> </span> 
              </div>
            </div>
            
            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Remarks</label>
              <div class="col-sm-6"><?php if($this->session->userdata['userinfo']['remarks']==""){ echo "-";}else{echo $this->session->userdata['userinfo']['remarks'];}?> 
              <span class="error"> </span> 
              </div>
            </div>
            
            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Invoice Address</label>
              <div class="col-sm-6"> 
                <?php if($this->session->userdata['userinfo']['invoice_flag']=='CS') {?>
                  <span style="color:#090;">'Accreditation Centre'</span> address on the invoice
                <?php } else{?>
                  <span style="color:#090;">'Agency'</span> address on the invoice
                <?php } ?> 
                <span class="error"></span> 
              </div>
            </div>
            <div class="box-footer">
              <div class="col-sm-6 col-xs-offset-3 text-center">
                <input type="submit" class="btn btn-info text-center" name="btnSubmit" id="btnSubmit" value="Submit">
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </form>
</div>
