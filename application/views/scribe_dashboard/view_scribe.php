<head><script>var site_url="<?php echo base_url();?>";</script>
    <?php $data = $this->session->userdata('sessionData');
    $this->load->view('scribe_dashboard/includes/topbar'); ?>
</head>
    
    <body class="hold-transition skin-blue sidebar-mini">
       

<div class="content-wrapper">
	<?php $this->load->view('scribe_dashboard/includes/header');?>
	<?php $this->load->view('scribe_dashboard/includes/sidebar');?>
            
  <section class="content-header">
    <h1> Request Details  </h1>
  </section>
 
    <section class="content">
      <div class="row myview">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Preview</h3>
               <div class="pull-right"><button class="btn btn-warning" onclick="history.back()">Back</button>
              </div>
            </div>
            <div class="box-body" style="padding-left: 10px">


              <?php if($this->session->flashdata('error')!=''){?>
              <div class="alert alert-danger alert-dismissible" id="error_id">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('error'); ?> </div>
              <?php } if($this->session->flashdata('success')!=''){ ?>
              <div class="alert alert-success alert-dismissible" id="success_id">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('success'); ?> </div>
              <?php } 
            if(validation_errors()!=''){?>
              <div class="alert alert-danger alert-dismissible" id="error_id">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo validation_errors(); ?> </div>
              <?php }


             if (isset($reuest_list)) { 

              # code...
              
              foreach ($reuest_list as $res) 
				  { 
						$regnumber = $res['regnumber'];
						$exam_code = $res['exam_code'];
						$subject_code = $res['subject_code'];
						$query = $this->db->query("SELECT scribe_uid FROM scribe_registration WHERE regnumber = $regnumber AND exam_code = $exam_code AND subject_code = $subject_code AND mobile_scribe != 0 AND remark = 3");
						$result = $query->result_array();

						if (!empty($result) && $data['user_type']=='admin' ){ ?>
							<div class="alert alert-info" id="warning_id" style="padding: 10px;">
							<?php echo '<h4 style="font-size: 15px;">Candidate wants to change the scribe for given exam</h4>'; ?> 
							</div>
						<?php }
						//print_r($result);
						?>
               
                 <div class="table-responsive ">
                  <table class="table table-bordered" style="">
                    <tbody>
                    <tr><!-- 03/11/2022 -->
                      <input hidden type="text" id='scribe_uid' name="scribe_uid" value="<?php echo $res['scribe_uid']; ?>">
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>Member no:</strong></td>
                      <td width="50%"><?php echo $res['regnumber']; ?></td>
                    </tr> 
                    <?php 
                     foreach ($fullname as $name) { ?>
                     <tr>                    
                      <td width="50%"><strong>Member Name:</strong></td>
                      <td width="50%"><?php echo $name['name']; ?></td>
                    </tr>
                  <?php }?>
                    <tr> <!-- 03/11/2022 EDIT MAIL-->                   
                      <td width="50%"><strong>Email ID:</strong></td>
                      <td width="50%">
                        <input style="width:300px;" hidden type="text" id='email' name="email" value="<?php echo $res['email']; ?>">
                        <span class="email"> <?php echo $res['email']; ?> </span>
                        <div class="pull-right"><button id="edit" class="btn btn-success" onclick="save_mail();"><span class="glyphicon">&#xe013;</span></i></button>
                        </div>
                        <div style="margin-right: 5px;" class="pull-right"><button id="edit" class="btn btn-warning" onclick="edit_mail();"><i class="bi bi-pen"></i></button>
                        </div> 
                      </td>
                    </tr><!-- 03/11/2022 EDIT MAIL END -->
                     <tr>                    
                      <td width="50%"><strong>Mobile no:</strong></td>
                      <td width="50%"><?php echo $res['mobile']; ?></td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>Exam Date:</strong></td>
                      <td width="50%"><?php echo $res['exam_date']; ?></td>
                    </tr>
                     <tr>                    
                      <td width="50%"><strong>Exam Name:</strong></td>
                      <td width="50%"><?php echo $res['exam_name']; ?></td>
                    </tr>
                     <tr>                    
                      <td width="50%"><strong>Subject Name:</strong></td>
                      <td width="50%"><?php echo $res['subject_name']; ?></td>
                    </tr>
                     <tr>                    
                      <td width="50%"><strong>Center Name:</strong></td>
                      <td width="50%"><?php echo $res['center_name']; ?></td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>Center Code:</strong></td>
                      <td width="50%"><?php echo $res['center_code']; ?></td>
                    </tr>
                    <?php 
                   if($res['name_of_scribe'])
                   { 
                     ?>
                     <tr>                    
                      <td width="50%"><strong>Scribe Name:</strong></td>
                      <td width="50%"><?php echo $res['name_of_scribe']; ?></td>
                    </tr>
                  
                     <tr>                    
                      <td width="50%"><strong>Scribe Mobile no:</strong></td>
                      <td width="50%"><?php echo $res['mobile_scribe']; ?></td>
                    </tr>
                  
                    <tr>                    
                      <td width="50%"><strong>Employment Details of Scribe:</strong></td>
                      <td width="50%"><?php echo $res['emp_details_scribe']; ?></td>
                    </tr>

                    <!-- Qualification -->
                      <tr>                    
                        <td width="50%"><strong>Qualification</strong></td>
                        <td width="50%"><?php 
                          if($res['qualification']=='U'){echo  'Under-Graduate';}
                          if($res['qualification']=='G'){echo  'Graduate';}
                          if($res['qualification']=='P'){echo  'Post-Graduate';}?>
                        </td>
                      </tr>

                      <?php foreach ($specify_qualification as $education) 
                      { ?>
                        <tr>                    
                          <td width="50%"><strong>Specified Qualification :</strong></td>
                          <td width="50%"><?php echo $education['education']; ?></td>
                        </tr>
                      <?php }
                      ?> 
                    <!-- Qualification -->

                    <?php }?> 

                  <!-- Benchmark Disability Code End -->
                    <tr>                    
                      <td width="50%"><strong>Person with Benchmark Disability</strong></td>
                      <td width="50%"><?php 
                        if($res['benchmark_disability']=='Y'){echo  'Yes';}
                        if($res['benchmark_disability']=='N'){echo  'No';}?>
                      </td>
                    </tr>

                    <?php 
                   if($res['benchmark_disability']=='Y')
                   { 
                     ?>
                      <tr>                    
                        <td width="50%"><strong>Visually impaired</strong></td>
                        <td width="50%"><?php 
                          if($res['visually_impaired']=='Y'){echo  'Yes';}
                          if($res['visually_impaired']=='N'){echo  'No';}?>
                        </td>
                      </tr>

                      <?php 
                     if($res['visually_impaired']=='Y')
                     { 
                      ?>
                      <tr>                    
                        <td width="50%"><strong>Attach scan copy of PWD certificate</strong></td>
                        <td width="50%"><img id="vis_imp_cert" src="<?php $path = "uploads/scribe/disability/".$res['vis_imp_cert_img']; 
                        echo base_url().$path;?>" height="100" width="100" ondblclick="open_vis_imp_cert()" > 
                              
                        </td>
                      </tr>
                      <?php }?>

                      <tr>                    
                        <td width="50%"><strong>Orthopedically handicapped</strong></td>
                        <td width="50%"><?php 
                          if($res['orthopedically_handicapped']=='Y'){echo  'Yes';}
                          if($res['orthopedically_handicapped']=='N'){echo  'No';}?>
                        </td>
                      </tr>

                      <?php 
                     if($res['orthopedically_handicapped']=='Y')
                     { 
                      ?>
                      <tr>                    
                        <td width="50%"><strong>Attach scan copy of PWD certificate</strong></td>
                        <td width="50%"><!-- <img src="<?php echo $res['orth_han_cert_img'];?>" height="100" width="100" > -->
                          <img src="<?php $path = "uploads/scribe/disability/".$res['orth_han_cert_img']; 
                        echo base_url().$path;?>" height="100" width="100" id="orth_han_cert" ondblclick="open_orth_han_cert()">
                        </td>
                      </tr>
                    <?php }?>

                      <tr>                    
                        <td width="50%"><strong>Cerebral palsy</strong></td>
                        <td width="50%"><?php 
                          if($res['cerebral_palsy']=='Y'){echo  'Yes';}
                          if($res['cerebral_palsy']=='N'){echo  'No';}?>
                        </td>
                      </tr>

                    <?php 
                     if($res['cerebral_palsy']=='Y')
                     { 
                      ?>
                      <tr>                    
                        <td width="50%"><strong>Attach scan copy of PWD certificate</strong></td>
                        <td width="50%"><!-- <img src="<?php echo $res['cer_palsy_cert_img'];?>" height="100" width="100" > -->
                          <img src="<?php $path = "uploads/scribe/disability/".$res['cer_palsy_cert_img']; 
                        echo base_url().$path;?>" height="100" width="100" id="cer_palsy_cert" ondblclick="open_cer_palsy_cert()" >
                        </td>
                      </tr>
                    <?php }?>
                <?php }?>
                <!-- Benchmark Disability Code End -->
                  

                    <!-- scribe details -->

                    <?php 
                     if($res['name_of_scribe'] =='')
                     {  //echo "noooooo";
                      ?>
                    <tr>                    
                        <td width="50%"><strong>Request Type</strong></td>
                        <td width="50%">
                    <?php 
                     if($res['special_assistance'])
                     { 
                      ?>
                        <?php echo 'Special Assistance ';?>
                    
                    <?php } if($res['extra_time']) { ?>
                        <?php echo ' Extra Time';?>
                    <?php } ?>
                          </td>
                        </tr>
                      <tr>                    
                        <td width="50%"><strong>Request Description</strong></td>
                        <td width="50%"><textarea rows="4" cols="60"><?php echo $res['description'];?></textarea></td>
                      </tr>


                    <?php }//echo "hoooooo";?>


                    <!-- scribe details -->
                  <?php if($res['name_of_scribe']) { ?>
                    <tr>                    
                        <td width="50%"><strong>Photo ID No</strong></td>
                        <td width="50%"><?php echo $res['photoid_no'];?></td>
                    </tr>

                    <tr>                    
                        <td width="50%"><strong>Uploaded ID Proof</strong></td>
                        <td width="50%">
                        <img src="<?php $path = "uploads/scribe/idproof/".$res['idproofphoto']; 
                        echo base_url().$path;?>" height="100" width="100" id="idproof" ondblclick="open_idproof()" >

                        </td>
                    </tr>

                    <tr>                    
                        <td width="50%"><strong>Uploaded Declaration Form</strong></td>
                        <td width="50%"><!-- <img src="<?php echo $res['declaration_img'];?>" height="100" width="100" > -->
                          <img src="<?php $path = "uploads/scribe/declaration/".$res['declaration_img']; 
                        echo base_url().$path;?>" height="100" width="100" id="declaration" ondblclick="open_declaration()">
                        </td>
                      </tr>
                  <?php } ?>

                  <?php if($res['scribe_approve'] == 3){ ?>
                    <tr>                    
                      <td width="50%"><strong>Rejection Reason</strong></td>
                      <td width="50%"> <?php foreach ($reasons as $key => $reason) { ?>
                        <ul><li>
                          <?php echo stripslashes(htmlspecialchars_decode($reason['reason_description']));?>
                        </li></ul>
                        <?php  } ?>  
                      </td>
                    </tr>
                  <?php }?>

                                         
                      <td width="50%"><strong>STATUS</strong></td>
                      <td width="50%">
                        <?php
                        if($res['scribe_approve'] == 1){
                          $reuest_status = '<span class="reuest_status" style="color: green">APPROVED</span>' ;
                          }elseif($res['scribe_approve'] == 3){
                          $reuest_status = '<span class="reuest_status" style="color: #FF0000">REJECTED</span>'; 
                          }elseif($res['scribe_approve'] == 0){
                          $reuest_status = '<span class="reuest_status" style="color: blue">NEW</span>'; 
                          }elseif($res['scribe_approve'] == 6){
                          $reuest_status = '<span class="reuest_status" style="color: #da8be8">RESUBMITED</span>'; 
                          }elseif($res['scribe_approve'] == 4){
                          $reuest_status = '<span class="reuest_status" style="color: #ff8d00">Application Cancelled</span>'; 
                          }elseif($res['scribe_approve'] == 5){
                          $reuest_status = '<span class="reuest_status" style="color: #089ac5">REFUND</span>'; 
                          }else{
                          $reuest_status = '-';
                          }
                          echo $reuest_status; ?>
                      </td>
                    </tr>
                  
                  </tbody>
                  </table>

                <!--REASON FORM FOR APPROVAL -->
                <!-- <div id="reason_form1" style="display: none">
                  <form action="<?php echo base_url('scribe_dashboard/Scribe_list/approve/'.$res['id']);?>" method="post"  class=""  enctype="multipart/form-data"data-parsley-validate="parsley">
                    <table  class="table table-bordered" style="">
                      <tbody>
                        <tr>
                            <input type="hidden" name="id" value="<?=$res['id']?>">
                            <td width="50%"><strong><p class="reason_class">APPROVAL REASON *:</p></strong><textarea maxlength="300" required="" class="form-control" name="approve_reason"></textarea></td>
                            <td width="50%"><br><input type="submit" class="btn btn-success" name="btnSubmit" value="Submit"></td>
                         </tr>

                      </tbody>
                    </table>
                   </form>
                </div> -->
                <!-- REASON FORM FOR REJECTION -->
                <div id="reason_form2" style="display: none">
                  <form action="<?php echo base_url();?>scribe_dashboard/Scribe_list/reject/" method="post"  class=""  enctype="multipart/form-data" data-parsley-validate="parsley"> 
                    <table  class="table table-bordered" style="">
                      <tbody>
                        <tr><?php
                           if($res['mobile_scribe']){ ?>
                            <!-- DISPLAY CHECKBOX FOR SCRIBE CASE -->
                              <div class="col-sm-7">
                                <input type="checkbox" id="reason1" name="reason1" value="Declaration form uploaded by you is not properly filled-in/clearly visible">
                                <label for="special_assistance">Declaration form uploaded by you is not properly filled-in/clearly visible</label><br>
                                <input type="checkbox" id="reason2" name="reason2" value="Disability Certificate is not valid/clearly visible">
                                <label for="extra_time">Disability Certificate is not valid/clearly visible</label><br>
                                <input type="checkbox" id="reason3" name="reason3" value="Information mismatch with the application form">
                                <label for="special_assistance">Information mismatch with the application form</label><br>
                            </div>
                              <!-- DISPLAY CHECKBOX FOR special CASE -->
                           <?php }else{ ?>
                              <div class="col-sm-7">
                                <input type="checkbox" id="reason1" name="reason1" value="Disability Certificate is not valid/clearly visible">
                                <label for="special_assistance">Disability Certificate is not valid/clearly visible</label><br>
                              </div>
                           <?php } ?>            
                            <!-- DISPLAY CHECKBOX FOR SPECIAL CASE -->                
                            <input type="hidden" name="id" value="<?=$res['id']?>">
                            <td width="50%"><strong><p class="reason_class">REJECTION REASON  *:</p></strong><textarea maxlength="300" class="form-control" name="reject_reason"></textarea></td>
                            <td width="50%"><br><input type="submit" class="btn btn-danger" name="btnSubmit" value="Submit"></td>
                         </tr>

                      </tbody>
                    </table>
                   </form>
                </div>    

               <div align="center"> 
					<?php
                  if($res['id'] != 0){
                    if($res['scribe_approve'] == 0 || $res['scribe_approve'] == 6){ ?>
                     <!-- <a href="<?php echo base_url();?>scribe_dashboard/Scribe_list/approve/" class="btn btn-primary action" id="1">Approve</a> -->
                     <a href="<?php echo base_url('scribe_dashboard/Scribe_list/approve/'.$res['id']);?>" class="btn btn-primary action">Approve</a>
                     <a class="btn btn-danger action" id="2">Reject</a>
                     
                  <?php 
                     } 
                 
                  }
                   ?>
                   <!-- SCRIBE REJECT WHEN ALREDY APPROVED POOJA MANE : 17/11/2022  -->
                   <?php if($data['user_type'] == 'admin' && $res['scribe_approve'] == 1 ){ ?>
                        <a class="btn btn-danger action" id="2">Reject</a>
                    <?php  }?>
                 </div>

                  <!-- <div align="center"> 
                 <?php

                  if($res['scribe_approve'] == 0 || $res['scribe_approve'] == 6){ ?>
                    
                     <a href="<?php echo base_url('scribe_dashboard/Scribe_list/approve/'.$res['id']);?>" class="btn btn-primary action">Approve</a>
                     
                     <a href="<?php echo base_url('scribe_dashboard/Scribe_list/reject/'.$res['id']);?>" class="btn btn-danger action">Reject</a>
                  <?php 
                     }
                  ?>
                 </div>  -->
                 <!--  <a class="btn btn-primary action" id="1">Approve</a> -->
                 <!-- <a class="btn btn-danger action" id="2">Reject</a> -->

               </div>     
    
           
            </div>
          </div>
        </div>
      </div>

        
    </section>
      <?php  
                }
              } 
            ?>

  </div>
  
  <script type="text/javascript">
 $(document).ready(function() {
    
     $("body").on("contextmenu",function(e){
        return false;
    });

   
    //ON Click Reject button show form2 
    $("#2").click(function(){
    $("#reason_form1").hide();  
    $("#reason_form2").show();
    });

 });
</script>
<script type="text/javascript">
/*Call to image opening funtion*/
function open_idproof() {  //open ID Proof
   var url = $('#idproof').attr('src');
   window.open(url);
}

function open_vis_imp_cert() {  //open ID Proof
   var url = $('#vis_imp_cert').attr('src');
   window.open(url);
}

function open_orth_han_cert() {  //open ID Proof
   var url = $('#orth_han_cert').attr('src');
   window.open(url);
}

function open_cer_palsy_cert() {  //open ID Proof
   var url = $('#cer_palsy_cert').attr('src');
   window.open(url);
}

function open_declaration() {  //open ID Proof
   var url = $('#declaration').attr('src');
   window.open(url);
}
</script>
<!-- Data Tables -->
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">
<!-- Option 1: Include in HTML FOR EDIT ICON 03/11/2022 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
<style>
.active_batch{
color:#00a65a;  
font-weight:600;
}

.deactive_batch{
color:#930; 
font-weight:600;
}
.input_search_data{
 width:100%;  
}
tfoot {
    display: table-header-group;
}
.vbtn{
padding: 3px 4px;
font-weight: 600;
}
</style>

<!-- Data Tables -->
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>

<script src="<?php echo base_url()?>js/js-paginate.js"></script>
<script>
$(function () {
  $('#listitems2').DataTable();
  $("#listitems_filter").show();
});
</script>
<!-- Update details of scribe -->
<script type="text/javascript">
 function edit_mail()
      {
        $("#email").show();
        $(".email").hide();
      }  
 function save_mail()
      {
        var email = $("#email").val(); 
        var scribe_uid = $("#scribe_uid").val(); 
        $.ajax(
        {
          type: 'POST',
          url: '<?php echo site_url("scribe_dashboard/scribe_list/update_details/"); ?>',
          data: { scribe_uid : $("#scribe_uid").val(), email: $("#email").val()  },
          async: false,
          success: function(res)
          { 
            location.reload();

          }
        });
      }      
</script>
<!-- Update details of scribe END -->
<?php $this->load->view('creditnote/admin/includes/footer');?>
