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
      Exam Instructions 
      </h1>
      <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>-->
    </section>
 	<form class="form-horizontal" name="agree_diagree_Form" id="agree_diagree_Form"  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>CSCApplyexam/comApplication/">
    <section class="content">
      <div class="row">
       
        <div class="col-md-12">
          <!-- Horizontal Form -->
           <!-- Basic Details box closed-->
 		<div class="box box-info">
       	 <div class="box-header with-border">
            <div style="float:right;">
            </div>
            </div>
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
                    <?php 
					if(count($exam_info) >0 && $exam_info[0]['member_instruction']!='')
					{
						//echo htmlspecialchars_decode( $exam_info[0]['member_instruction']);
						$newstring = str_replace("#url#", "".base_url()."", htmlspecialchars_decode( $exam_info[0]['member_instruction']));
				    	echo  $finalstring = str_replace("{url}", "javascript:void(0);", $newstring);
					}
					?>
                  </div>
                </div>
                
                <div class="form-group">
                	<div class="col-sm-12">
                    <input name="declaration1" value="1" id="agree" type="checkbox" required>&nbsp;I
 have read the Rules and Regulations and other instructions governing 
the above examination and I agree to abide by the said Rules, 
Regulations and Instructions
                  </div>
                </div>
            </div>
            
            

             <div class="box-footer">
                  <div class="col-sm-4 col-xs-offset-3">
                    <input type="hidden" name="excode" id="excode" value="<?php echo base64_decode($this->input->get('ExId'));?>">
                      <?php $this->session->set_userdata('examcode',base64_decode($this->input->get('ExId')));?>
                    <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="I Agree">
                     <a href="http://iibf.org.in/" class="btn btn-info">I Disagree</a>
                    </div>
              </div>
             </div>
        </div>
      </div>
   </section>
    </form>
  </div>
  
    
  <script type="text/javascript">

  $('#agree_diagree_Form').parsley('validate');

</script>
