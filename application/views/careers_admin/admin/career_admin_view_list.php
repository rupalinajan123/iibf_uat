<?php $this->load->view('careers_admin/admin/includes/header');?>
<?php $this->load->view('careers_admin/admin/includes/sidebar');?>

<head>
  <style>
    h4 
    {
      color: #66d9ff;
    }
    h3 
    {
      text-align: center;
    }
  </style>
</head>
<div class="content-wrapper">
  <section class="content-header">
    <h1> View Details </h1>
  </section>
 
    <section class="content">
      <div class="row myview">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border" style="text-align: center">
              <h3 class="box-title">Preview</h3>
              <div class="pull-right"> <a href="<?php echo base_url();?>careers_admin/admin/Career_admin/career_admin_list" class="btn btn-warning">Back</a>
                
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
           
             $creditnoteuserdata = $this->session->userdata('career_admin');
             if (isset($careers_registration_arr)) {
              # code...
              //print_r($reuest_list); die;
              foreach ($careers_registration_arr as $res) { ?>
               
                 <div class="table-responsive ">
                  <table class="table table-bordered" style="">
                    <tbody>
                  <?php if ($position_id != 13) { ?>    
                    <tr>                    
                      <td width="50%"><strong>FIRST NAME:</strong></td>
                      <td width="50%"><?php echo $res['firstname']; ?></td> 
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>MIDDLE NAME:</strong></td>
                      <td width="50%"><?php echo $res['middlename']; ?></td>
                    </tr>
                     <tr>                    
                      <td width="50%"><strong>LAST NAME:</strong></td>
                      <td width="50%"><?php echo $res['lastname']; ?></td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>MARITAL STATUS:</strong></td>
                      <td width="50%"><?php echo $res['marital_status']; ?></td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>SPOUSE'S NAME:</strong></td>
                      <td width="50%"><?php echo $res['spouse_name']; ?></td>
                    </tr>
                     <tr>                    
                      <td width="50%"><strong>FATHER'S NAME:</strong></td>
                      <td width="50%"><?php echo $res['father_husband_name']; ?></td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>DATE OF BIRTH:</strong></td>
                      <td width="50%"><?php echo $res['dateofbirth']; ?></td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>EMAIL ID:</strong></td>
                      <td width="50%"><?php echo $res['email']; ?></td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>MOBILE:</strong></td>
                      <td width="50%"><?php echo $res['mobile']; ?></td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>GENDER:</strong></td>
                      <td width="50%"><?php echo $res['gender']; ?></td>
                    </tr>
                    
                   

                    <tr>                    
                      <td width="50%"><strong>ALTERNATE MOBILE:</strong></td>
                      <td width="50%"><?php echo $res['alternate_mobile']; ?></td>
                    </tr>

                    <tr>                    
                      <td width="50%"><strong>PAN NO:</strong></td>
                      <td width="50%"><?php echo $res['pan_no']; ?></td>
                    </tr>

                    <tr>                    
                      <td width="50%"><strong>AADHAR CARD NO:</strong></td>
                      <td width="50%"><?php echo $res['aadhar_card_no']; ?></td>
                    </tr>

                     <tr>                    
                      <td width="50%"><strong>ADDRESS:</strong></td>
                      <td width="50%"><?php echo $rst[0]['addressline1'].' ,'.$rst[0]['addressline2'].' ,'.$rst[0]['addressline3'].' ,'.$rst[0]['addressline4'].'<br>'.$rst[0]['district'].' ,'.$rst[0]['city'].'<br>'.$rst[0]['state'].'<br>'.$rst[0]['pincode']; ?></td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>PERMANENT ADDRESS:</strong></td>
                      <td width="50%"><?php echo $rst[0]['addressline1_pr'].' ,'.$rst[0]['addressline2_pr'].' ,'.$rst[0]['addressline3_pr'].' ,'.$rst[0]['addressline4_pr'].'<br>'.$rst[0]['district_pr'].' ,'.$rst[0]['city_pr'].'<br>'.$rst[0]['state_pr'].'<br>'.$rst[0]['pincode_pr']; ?></td>
                    </tr>

                    <tr>                    
                      <td width="50%"><strong>EXAM CENTER:</strong></td>
                      <td width="50%"><?php echo $res['exam_center']; ?></td>
                    </tr>
                  <?php } else { ?>  
                    <tr>                    
                      <td width="50%"><strong>NAME:</strong></td>
                      <td width="50%"><?php echo $res['firstname']; ?></td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>DATE OF BIRTH:</strong></td>
                      <td width="50%"><?php echo $res['dateofbirth']; ?></td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>EMAIL ID:</strong></td>
                      <td width="50%"><?php echo $res['email']; ?></td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>MOBILE:</strong></td>
                      <td width="50%"><?php echo $res['mobile']; ?></td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>Educational Qualification:</strong></td>
                      <td width="50%"><?php echo $res['educational_qualification']; ?></td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>CAIIB Qualification :</strong></td>
                      <td width="50%"><?php echo ucfirst($res['CAIIB_qualification']); ?></td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>ADDRESS:</strong></td>
                      <td width="50%"><?php echo $res['addressline1'].',<br>'.$res['addressline2'].' '.'<br>'.$res['city'].'<br>'.$res['state'].'<br>'.$res['pincode']; ?></td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>Bank/Educational Institute:</strong></td>
                      <td width="50%"><?php if($res['bank_education']=='bank'){ echo ucfirst($res['bank_education']); } else { echo 'Educational Institute'; } ?></td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>Organisation Name:</strong></td>
                      <td width="50%"><?php echo $res['ess_college_name']; ?></td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>Retired/Working:</strong></td>
                      <td width="50%"><?php echo ucfirst($res['retired_working']); ?></td>
                    </tr>
                    <?php
                      $experience = explode(',',$res['exp_in_bank']);
                      // echo "<pre>";
                      // print_r($res); exit;
                    ?>
                    <tr>                    
                      <td width="50%"><strong>Total year of Work experience:</strong></td>
                      <td width="50%"><?php echo $experience[0]." Year ".$experience[1]." Month"; ?></td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>Designation:</strong></td>
                      <td width="50%"><?php echo $res['designation']; ?></td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>Resume/CV:</strong></td>
                      <td><a href="<?php echo base_url();?>uploads/uploadcv/<?php echo $res['uploadcv'];?>" target="_blank" id="thumb" />Download</a>
                      </td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>General Banking Subjects:</strong></td>
                      <td><?php echo $res['general_subjects']; ?></td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>Specialized Banking Subjects:</strong></td>
                      <td><?php echo $res['specialisation']; ?></td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>Information Technology Subject:</strong></td>
                      <td><?php echo $res['it_subjects']; ?></td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>Other Banking Subjects:</strong></td>
                      <td><?php echo $res['other_subjects']; ?></td>
                    </tr>
                    
                  <?php } ?>   

<?php if ($position_id != 13) { ?>
<tr><td><h4><strong>EDUCATION QUALIFICATION(ESSENTIAL)</strong></h4></td><td></td></tr>                    
<?php } ?>
<?php 
 foreach ($rst as $row) { ?>

                  <tr>                    
                      <td width="50%"><strong>NAME OF COURSE:</strong></td>
                      <td width="50%"><?php echo $row['ess_course_name']; ?></td>
                    </tr>

                  <?php
                    
                  if(!empty($row['ess_subject']))
                  {
                  ?>
                    <tr>                    
                          <td width="50%"><strong>SUBJECT:</strong></td>
                          <div style="word-break:break-all;">
                          <td width="50%"><?php echo $row['ess_subject']; ?></td>
                          </div>
                    </tr>
                <?php }?>

                    <tr>                    
                      <td width="50%"><strong>COLLEGE NAME AND ADDRESS:</strong></td>
                      <td width="50%"><?php echo $row['ess_college_name']; ?></td>
                    </tr>

                    <tr>                    
                      <td width="50%"><strong>UNIVERSITY:</strong></td>
                      <td width="50%"><?php echo $row['ess_university']; ?></td>
                    </tr>

                    <tr>                    
                      <td width="50%"><strong>PERIOD:</strong></td>
                      <td width="50%"><?php echo $row['ess_from_date']." to ".$row['ess_to_date']; ?></td>
                    </tr>

                    <tr>                    
                      <td width="50%"><strong>GRADE/MARKS:</strong></td>
                      <td width="50%"><?php echo $row['ess_grade_marks']; ?></td>
                    </tr>

                    <tr>                    
                      <td width="50%"><strong>CLASS:</strong></td>
                      <td width="50%"><?php echo $row['ess_class']; ?></td>
                    </tr>
                    <tr><td></td><td></td></tr>
<?php
}
?>
<?php if ($position_id != 13) { ?>
<tr><td><h4><strong>EDUCATION QUALIFICATION(DESIRABLE)</strong></h4></td><td></td></tr>                    
<?php } ?>
<?php 
 foreach ($qualification_arr as $row) { ?>

                  <tr>                    
                      <td width="50%"><strong>NAME OF COURSE:</strong></td>
                      <td width="50%"><?php echo $row['course_name']; ?></td>
                    </tr>

                  <?php
                    
                  if(!empty($row['ess_subject']))
                  {
                  ?>
                    <tr>                    
                          <td width="50%"><strong>SUBJECT:</strong></td>
                          <div style="word-break:break-all;">
                          <td width="50%"><?php echo $row['ess_subject']; ?></td>
                          </div>
                    </tr>
                <?php }?>

                    <tr>                    
                      <td width="50%"><strong>COLLEGE NAME AND ADDRESS:</strong></td>
                      <td width="50%"><?php echo $row['college_name']; ?></td>
                    </tr>

                    <tr>                    
                      <td width="50%"><strong>UNIVERSITY:</strong></td>
                      <td width="50%"><?php echo $row['university']; ?></td>
                    </tr>

                    <tr>                    
                      <td width="50%"><strong>PERIOD:</strong></td>
                      <td width="50%"><?php echo $row['from_date']." to ".$row['to_date']; ?></td>
                    </tr>

                    <tr>                    
                      <td width="50%"><strong>GRADE/MARKS:</strong></td>
                      <td width="50%"><?php echo $row['grade_marks']; ?></td>
                    </tr>

                    <tr>                    
                      <td width="50%"><strong>CLASS:</strong></td>
                      <td width="50%"><?php echo $row['class']; ?></td>
                    </tr>
                    <tr><td></td><td></td></tr>
<?php
}
?>                
<?php if ($position_id != 13) { ?>
<tr><td><h4><strong>EMPLOYMENT HISTORY</strong></h4></td><td></td></tr>
<?php } ?>
<?php
foreach ($emp_hist_arr as $rest) { ?>

                    <tr>                    
                      <td width="50%"><strong>NAME OF THE ORGANIZATION:</strong></td>
                      <td width="50%"><?php echo $rest['organization']; ?></td>
                    </tr>

                    <tr>                    
                      <td width="50%"><strong>DESIGNATION:</strong></td>
                      <td width="50%"><?php echo $rest['designation']; ?></td>
                    </tr>

                    <tr>                    
                      <td width="50%"><strong>RESPOSIBILITIES:</strong></td>
                      <td width="50%"><?php echo $rest['responsibilities']; ?></td>
                    </tr>

                    <tr>                    
                      <td width="50%"><strong>PERIOD:</strong></td>
                      <td width="50%"><?php echo $rest['job_from_date']." to ".$rest['job_to_date']; ?></td>
                    </tr>
                    <tr><td></td><td></td></tr>
<?php
}
?>  

<?php if ($position_id != 13) { ?>
<br><tr><td style="color: #66d9ff"><h4><strong>LANGUAGES, EXTRACURRICULAR, ACHIEVEMENTS</strong></h4></td><td></td></tr>
                  <tr>                    
                      <td width="50%"><strong>LANGUAGES KNOWN:</strong></td>
                      <td width="50%"><?php echo $rst[0]['languages_known']; ?></td>
                  </tr>    
                  <tr>                    
                      <td width="50%"><strong>LANGUAGES OPTIONS:</strong></td>
                      <td width="50%"><?php echo $rst[0]['languages_option']; ?></td>
                  </tr>       
                  <tr>                    
                        <td width="50%"><strong>EXTRACURRICULAR:</strong></td>
                        <td width="50%"><?php echo $rst[0]['extracurricular']; ?></td>
                      </tr>
                  <tr>                    
                    <td width="50%"><strong>HOBBIES:</strong></td>
                    <td width="50%"><?php echo $rst[0]['hobbies']; ?></td>
                  </tr>    
                  <tr>                    
                      <td width="50%"><strong>ACHIEVEMENTS:</strong></td>
                      <td width="50%"><?php echo $rst[0]['achievements']; ?></td>
                  </tr>   
                  <tr>                    
                      <td width="50%"><strong>DECLARATION:</strong></td>
                      <td width="50%"><?php echo $rst[0]['declaration2']; ?></td>
                  </tr>
<?php } ?>

<?php if ($position_id != 13) { ?>
<tr><td><h4><strong>REFERENCE ONE</strong></h4></td><td></td></tr>
                    <tr>                    
                      <td width="50%"><strong>NAME:</strong></td>
                      <td width="50%"><?php echo $res['refname_one']; ?></td>
                    </tr>

                    <tr>                    
                      <td width="50%"><strong>ADDRESS:</strong></td>
                      <td width="50%"><?php echo $res['refaddressline_one']; ?></td>
                    </tr>

                    <tr>                    
                      <td width="50%"><strong>EMAIL ID:</strong></td>
                      <td width="50%"><?php echo $res['refemail_one']; ?></td>
                    </tr>

                    <tr>                    
                      <td width="50%"><strong>MOBILE:</strong></td>
                      <td width="50%"><?php echo $res['refmobile_one']; ?></td>
                    </tr>

<tr><td><h4><strong>REFERENCE TWO</strong></h4></td><td></td></tr>
                    <tr>                    
                      <td width="50%"><strong>NAME:</strong></td>
                      <td width="50%"><?php echo $res['refname_two']; ?></td>
                    </tr>

                    <tr>                    
                      <td width="50%"><strong>ADDRESS:</strong></td>
                      <td width="50%"><?php echo $res['refaddressline_two']; ?></td>
                    </tr>

                    <tr>                    
                      <td width="50%"><strong>EMAIL ID:</strong></td>
                      <td width="50%"><?php echo $res['refemail_two']; ?></td>
                    </tr>

                    <tr>                    
                      <td width="50%"><strong>MOBILE:</strong></td>
                      <td width="50%"><?php echo $res['refmobile_two']; ?></td>
                    </tr>
<?php } ?>

<?php if ($position_id != 13) { ?>
<tr><td><h4><strong>DECLARATION</strong></h4></td><td></td></tr> 
                    <tr>                    
                      <td width="50%"><strong>DECLARATION:</strong></td>
                      <td width="50%"><?php echo $rst[0]['declaration2']; ?></td>
                    </tr>                    
<?php } ?>
<?php if ($position_id != 13) { ?>
<tr><td><h4><strong>UPLOAD</strong></h4></td><td></td></tr> 
                    <tr>                    
                      <td width="50%"><strong>PHOTO:</strong></td>
                      <td><img width="70px" height="70px" src="<?php echo base_url();?>uploads/photograph/<?php echo $res['scannedphoto'];?>" id="thumb" />
                      </td>
                    </tr>

                    <tr>                    
                      <td width="50%"><strong>SIGNATURE:</strong></td>
                      <!-- <td width="50%"><?php echo $res['scannedsignaturephoto']; ?></td> -->
                      <td><img width="70px" height="70px" src="<?php echo base_url();?>uploads/scansignature/<?php echo $res['scannedsignaturephoto'];?>" id="thumb" />
                      </td>
                    </tr>  

                    <tr>                    
                      <td width="50%" style="word-break:break-all; display: inline-block;"><strong>ANY OTHER INFORMATION THAT THE  CANDI-DATE WOULD LIKE TO ADD:</strong></td>
                      <td width="50%">
                        <div style="word-break:break-all;">
                          <?php echo $res['comment'];?>
                        </div>
                      </td>
                    </tr>

                    <tr>                    
                      <td width="50%"><strong>PLACE:</strong></td>
                      <td width="50%"><?php echo $res['place']; ?></td>
                    </tr>

                    <tr>                    
                      <td width="50%"><strong>SUBMIT DATE:</strong></td>
                      <td width="50%"><?php echo $res['submit_date']; ?></td>
                    </tr>
<?php } ?>
                    <!-- <tr>                    
                      <td width="50%"><strong>ACTIVE STATUS:</strong></td>
                      <td width="50%"><?php echo $res['active_status']; ?></td>
                    </tr> -->

                    <!-- <tr>                    
                      <td width="50%"><strong>DATE:</strong></td>
                      <td width="50%"><?php echo $res['createdon']; ?></td>
                    </tr> -->

                  </tbody>
                  </table>
                 <div id="reason_form" style="display: none">
                  </div>    
                
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
                         
                </tbody>
              
              </table>
              <div id="links" class="dataTables_paginate paging_simple_numbers">
             
              </div>
            </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>

    </section>   
</div>
<script type="text/javascript">
 $(document).ready(function() {
    
    $("body").on("contextmenu",function(e){
       return false;
   });


// on click of button take action 
  $(".action").click(function(){
    $name = this.id;
    
    $('#action_id').val($name);
    //$('.reason_class').html($text);
    //alert($('#action_id').val())
    $("#reason_form").show();
  });


 });
</script>
<!-- Data Tables -->
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">
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

<?php $this->load->view('careers_admin/admin/includes/footer');?>