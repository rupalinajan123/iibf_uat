<script>
$(document).ready(function(){
$('#confirm').modal('show');
});
function Show(){ 
$('#confirm').modal('hide');
$('#confirmTwo').modal('show');
} 
</script>
<div class="modal fade" id="confirm"  role="dialog" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header"> 
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <div class="message" style="color:#F00; text-align:center; font-size:20px;"><strong>VERY IMPORTANT</strong></div>
        <br />
        <br />
        <div class="message" style="color:#F00; text-align:justify;font-size:16px;"> Candidates are required to take utmost care and precaution in selecting Centre, Venue and Time slot, as there is no provision to change the Centre, Venue and Time slot in the system.<br />
          <br />
          Hence no request for change of centre, venue and time slot will be entertained for any reason.<br />
          <br />
          THE FEES ONCE PAID WILL NOT BE REFUNDED OR ADJUSTED ON ANY ACCOUNT</div>
      </div>
      <div class="modal-footer"><!--data-dismiss="modal"-->
        <input type="button" name="btnSubmit"  class="btn btn-primary" id="btnSubmit" value="Okay" onclick="Show();" >
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="confirmTwo"  role="dialog" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header"> 
        <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <div class="message" style="color:#F00; text-align:center; font-size:20px;"><strong>VERY IMPORTANT</strong></div>
        <br />
        <br />
        <div class="message" style="color:#F00; text-align:justify;font-size:16px;"> For candidates who are unable to view the Venue details, in the drop down list they are required to do the following to solve this issue.<br />
          <br />
          Clear the browsers history by going to the settings menu of the browser and click the <strong>"Clear browsing history"</strong>.After clearing the browsing history candidates are required to close the browser and start again for registration. </div>
      </div>
      <div class="modal-footer">
        <input type="button" name="btnSubmit_two"  data-dismiss="modal" class="btn btn-primary" id="btnSubmit_two" value="Okay" >
      </div>
    </div>
  </div>
</div>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
   
      </h1>
      <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>-->
    </section>

    <section class="content">
      <div class="row">
       
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Dashboard</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
            <?php //echo validation_errors(); ?>
              <?php if($this->session->flashdata('error')!=''){?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
              <?php } if($this->session->flashdata('success')!=''){ ?>
                <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
                <?php echo $this->session->flashdata('success'); ?>
              </div>
             <?php } 
			 if(validation_errors()!=''){?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                    <?php echo validation_errors(); ?>
                </div>
              <?php } 
			 ?> 
             
               
                
                
                
                
                <div class="form-group">
             
                	<div class="col-sm-12">
                     
                    <ul>
					 
						<!--<li>Since the Institute will not be sending the Printed Admit Letter through post, Correct/Active E-mail address is mandatory for receipt of Admit Letter and other communication/s through e-mail.</li>-->
                        
                          <li>	Since the Institute will not be sending the Printed Admit Letter through post, Correct/Active E-mail address is mandatory for receipt of Admit Letter and other communication/s through e-mail.</li>
                          
				
               <!-- <li>If the candidates photograph, signature or ID proof is not uploaded (is blank), candidates are required to upload the same thru EDIT PROFILE given on the left. Candidates will not be permitted to apply for any examination if the photograph, signature or ID proof is not uploaded.</li>-->
                 <li> If  the candidates photograph, signature or ID proof is not uploaded (if it is blank), candidates are required to upload the same thru EDIT PROFILE given on the left. Pl ensure the images to be uploaded are as per the specifications given. Candidates will not be permitted to apply for any examination  if the Photograph, Signature or ID proof is not uploaded.</li>
                
               <!-- <li>If uploaded photograph/signature/Id Proof is not proper in size, clear or blur candidates are required to email soft copy of the images to 
                <a href="mailto:mem-services@iibf.org.in">mem-services@iibf.org.in</a> quoting membership number and required proof of identity.</li>-->
                  <li> If  uploaded photograph/signature/Id Proof is not proper in size, clear or blurred, candidates are required to email soft copy of the images as per the specifications given  to  <a href="mailto:iibfwzmem@iibf.org.in">iibfwzmem@iibf.org.in</a> quoting membership number and required proof  of  identity.</li>
                
               <!-- <li>If uploaded photograph/signature is not proper in size, clear, blank or blur Institute will not issue Final Examination Certificate to the candidates till such time photograph/signature is properly uploaded as per the Institute’s requirement.</li>-->
                <li>
                If uploaded photograph/signature is not proper in size, clear, blank or blur Institute will not issue Final Examination Certificate to the candidates till such time photograph/signature is properly uploaded as per the Institute’s requirement.
                </li>
                
            	<li>For updating E-mail, Contact No. and other details, Click on EDIT PROFILE given on the left.</li>
            
            
          <!--<li>  CONTACT DETAILS:
Register your queries through website www.iibf.org.in > Members/Candidates Support Services(Help)  available in MEMBERSHIP/MEMBERSHIP SUPPORT SERVICES Menu at the home page.<br>
or<br>
Email all your queries to <a href="mailto:care@iibf.org.in ">care@iibf.org.in </a><br>
Member Support Service Office:<br>
 Indian Institute of Banking & Finance<br>
191-F, Maker Towers, 19th Floor,<br>
Cuffe Parade, Mumbai - 400 005<br>
Tel. : 022-2218 3302 / 2218 5134</li>-->
<li>CONTACT  DETAILS: 
Register your queries through website www.iibf.org.in > <b><font color="#FF0000">Members/Candidates Support Services(Help)</font></b> available on  the home page.<br>
or<br>
Email all your queries to <a href="mailto:care@iibf.org.in">care@iibf.org.in</a> <br>
Members  Support Services Office:<br>
Indian Institute of  Banking  & Finance<br>
193-F, Maker Towers, 19th  Floor,<br>
Cuffe Parade, Mumbai - 400 005<br>
Tel. : 022-2218 3302 / 2218 5134</li>

                </ul>
                    
                    </div><!--(Max 30 Characters) -->
                </div>
                
                
                
                
                </div>
                
               </div> <!-- Basic Details box closed-->
        </div>
      </div>
     
      
      
    </section>

  </div>
  
<!-- Data Tables -->






 


 
