<?php $this->load->view('admin/kyc/includes/header'); ?>
<?php $this->load->view('admin/kyc/includes/sidebar'); ?>
<link href="<?php echo base_url('assets/css/popup.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/dist/css/lightgallery.css') ?>" rel="stylesheet">
<script src="<?php echo base_url('assets/dist/js/jquery.min.js') ?>"></script>
<script>
  var site_url = "<?php echo base_url(); ?>";
</script>
<style>
  .min-height {
  min-height: 650px;
  }
  .form-group .wysihtml5-toolbar li::before {
  content: unset;
  }
  .form-group .wysihtml5-toolbar li {
  padding-left: 0;
  }
  .wysihtml5-sandbox {
  float: left !important;
  width: 100% !important;
  }
</style>
<!-- Added by pooja mane for fedai exam code check 2024-08-05 -->
<?php $fedai_array= array(1009); $bcbf_array= array(1046,1047,1052,1053,1054,1055,1056); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      KYC verification
    </h1>
  </section>
  <br />
  <div class="col-md-12">
    <?php if ($this->session->flashdata('error') != '') { ?>
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
        <?php echo $this->session->flashdata('error'); ?>
      </div>
      <?php }
      if ($this->session->flashdata('success') != '') { ?>
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
        <?php echo $this->session->flashdata('success'); ?>
      </div>
    <?php } ?>
    <?php if ($success != '') { ?>
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
        <?php echo $success ?>
      </div>
    <?php } ?>
  </div>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box min-height">
          <div class="box-header">
            <!-- 
              - SAGAR WALZADE : Code start here
              - Changes : registered date displayed in title
            -->
            <?php
              if (isset($result[0]['createdon']) && !empty($result[0]['createdon'])) {
                $createdon_date = '<i style="font-size:11px;">( Registered on ' . $result[0]['createdon'] . ' )</i>';
                } else {
                $createdon_date = "";
              }
            ?>
            <h3 class="box-title">Member selected <?php echo $createdon_date; ?></h3>
            <!-- - SAGAR WALZADE : Code end here -->
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <?php
              $selected_srno = $this->uri->segment(6);
              if ($this->uri->segment(6) < $this->uri->segment(7)) {
                $selected_srno += 1;
                if ($selected_srno == $this->uri->segment(7)) {
                  $selected_srno = $this->uri->segment(6);
                }
                } else {
                $selected_srno = $this->uri->segment(7);
              }
              $totalCount = $totalRecCount;
            ?>
            <form class="form-horizontal" name="checkmember" id="checkmember" action="<?php echo base_url(); ?>admin/kyc/Kyc/member/<?php echo $reg_no ?>/<?php echo $selected_srno; ?>/<?php echo $totalCount; ?>" method="post">
              <?php
                /*?> $allocated_member_list = $this->master_model->getRecords("admin_kyc_users ",array('DATE(date)'=>date('Y-m-d'),'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'New','allotted_member_id !='=>'' ));
                  $array=$allocated_member_list[0]['allotted_member_id'];
                  if(!empty($array))
                  {	
                  $alloction_count= $allocated_member_list[0]['pagination_total_count'];
                  $allocates_arr1= explode(',', $array);
                  $current_count=	count($allocates_arr1);
                  $final_remaning_count=$alloction_count-$current_count;
                  $final_remaning_count1=$final_remaning_count+1;
                  echo 'Showing '.$final_remaning_count1.' of '.$alloction_count.'  Records' ;
                }?><?php */ ?>
                <table id="listitems" class="table table-bordered table-striped dataTables-example">
                  <thead>
                    <tr>
                      <th id="">Membership/<br>Registration No</th>
                      <th id="">Candidate Name</th>
                      <th id="">D.O.B</th>
                      <th id="">Employer</th>
                      <th id="">Photo</th>
                      <th id="">Sign</th>
                      <th id="">Id-Proof</th>
                      <!-- Pooja mane code starts: 2024-08-05 -->
                      <?php 
                          if($result[0]['registrationtype']== 'NM' && in_array($result[0]['excode'], $fedai_array)){?>
                          <th id="">Employment Proof</th>
                      <?php }?>
                      <!-- Pooja mane code ends: 2024-08-05 -->
                      <!-- Pooja mane BCBF code starts: 2024-10-08 -->
                      <?php
                      if($result[0]['date_of_commenc_bc'] != ''){?>
                        <th id="">Bank BC ID Card</th>
                      <?php }?>
                       <?php 
                          if($result[0]['registrationtype']== 'NM' && in_array($result[0]['excode'], $fedai_array)){?>
                          <th id="">Declaration</th>
                      <?php }?>
                      <!-- Pooja mane BCBF code ends: 2024-10-08 -->
                      <!-- 
                        - SAGAR WALZADE : Code start here
                        - Changes : one declaration column added and declaration is required or not is displayed : 
                        - it is required for those ordinary users who are registered after 1 april 2022
                      -->
                      <?php
                        if ((!empty($result) && isset($result[0]['registrationtype'])) && ($result[0]['registrationtype'] == 'O' || ($row['registrationtype'] == 'NM' && in_array($result[0]['excode'], $fedai_array)))) {
                          if ($result[0]['createdon'] >= '2022-04-01') {
                            $is_required = "<i style='color:red;font-size:11px;' data-createdon='" . $result[0]['createdon'] . "'>Required</i>";
                            } else {
                            $is_required = "<i style='color:red;font-size:11px;' data-createdon='" . $result[0]['createdon'] . "'>Optional</i>";
                          }
                        ?>
                        <th id="">Declaration <?php echo $is_required; ?></th>
                        <?php
                        }
                      ?>
                      <!-- SAGAR WALZADE : Code end here -->
                    </tr>
                  </thead>
                  <tbody class="no-bd-y" id="list">
                    <?php $regtype = '';
                      if (count($result)) {
                        foreach ($result as $row) {
                          //print_r($row);
                          $employer = array();
                          if ($row['registrationtype'] == 'O' || $row['registrationtype'] == 'A' || $row['registrationtype'] == 'F') {
                            $select = 'institude_id,name';
                            $employer = $this->master_model->getRecords("institution_master", array('institude_id' => $row['associatedinstitute']), $select);
                          }
                        ?>
                        <tr>
                          <td><?php echo $row['regnumber']; ?></td>
                          <td><?php echo $row['namesub'] . " " . $row['firstname'] . " " . $row['middlename'] . " " . $row['lastname']; ?></td>
                          <td><?php if ($row['dateofbirth'] != '0000-00-00' && $row['dateofbirth'] != '' && $row['dateofbirth'] != '00-00-0000') {
                            echo date('d-m-Y', strtotime($row['dateofbirth']));
                          } ?></td>
                          <td><?php if (count($employer) > 0) {
                            echo $employer[0]['name'];
                            } else {
                            echo '';
                          } ?></td>
                          <td>
                            <!--scannedphoto -->
                            <?php $actual_idproof = get_img_name($row['regnumber'], 'p');
                              if (is_file($actual_idproof)) {
                              ?>
                              <!--<div class="demo-gallery">
                                <ul id="lightgallery_photo" class="list-unstyled row">
                                <span class=""  data-src="<?php echo base_url(); ?><?php echo $actual_idproof; ?>" >
                                <a href="">
                                <img class="img-responsive" src="<?php echo base_url(); ?><?php echo $actual_idproof; ?><?php echo '?' . time(); ?>"  name="scannedphoto" id="scannedphoto" height="100" width="100"  />
                                </a>
                                </span>
                                </ul>
                              </div>-->
                              <a href="#openModalscanphoto">
                                <img class="img-responsive" src="<?php echo base_url(); ?><?php echo $actual_idproof; ?><?php echo '?' . time(); ?>" name="scannedphoto" id="scannedphoto" height="100" width="100" />
                              </a>
                              <?php } else { ?>
                              <img src="<?php echo base_url(); ?>assets/images/default1.png" height="100" width="100">
                            </p>
                          <?php } ?>
                        </td>
                        <td>
                          <!--scannedsignaturephoto -->
                          <?php $actual_idproof = get_img_name($row['regnumber'], 's');
                            if (is_file($actual_idproof)) {
                            ?>
                            <!--<div class="demo-gallery">
                              <ul id="lightgallery_sign" class="list-unstyled row">
                              <span class=""  data-src="<?php echo base_url(); ?><?php echo $actual_idproof; ?>" >
                              <a href="">
                              <img class="img-responsive" src="<?php echo base_url(); ?><?php echo $actual_idproof; ?><?php echo '?' . time(); ?>"  name="scannedsignaturephoto" id="scannedsignaturephoto" height="100" width="100"  />
                              </a>
                              </span>
                              </ul>
                            </div>-->
                            <a href="#openModalscansignature">
                              <img class="img-responsive" src="<?php echo base_url(); ?><?php echo $actual_idproof; ?><?php echo '?' . time(); ?>" name="scannedsignaturephoto" id="scannedsignaturephoto" height="100" width="100" />
                            </a>
                            <!--<p>
                              <a class="fancybox-effects-a" href="<?php echo base_url(); ?><?php echo $actual_idproof; ?><?php echo '?' . time(); ?>" title=""> <img src="<?php echo base_url(); ?><?php echo $actual_idproof; ?><?php echo '?' . time(); ?>" name="scannedsignaturephoto" id="scannedsignaturephoto" width="100" height="100" /></a>
                            </p>-->
                            <?php } else { ?>
                            <p>
                              <img src="<?php echo base_url(); ?>assets/images/default1.png" height="100" width="100">
                            </p>
                          <?php } ?>
                        </td>
                        <td>
                          <!--idproofphoto -->
                          <?php $actual_idproof = get_img_name($row['regnumber'], 'pr');
                            if (is_file($actual_idproof)) {
                            ?>
                            <!--<div class="demo-gallery">
                              <ul id="lightgallery_proof" class="list-unstyled row">
                              <span class=""  data-src="<?php echo base_url(); ?><?php echo $actual_idproof; ?>" >
                              <a href="">
                              <img class="img-responsive" src="<?php echo base_url(); ?><?php echo $actual_idproof; ?><?php echo '?' . time(); ?>"  name="idproofphoto" id="idproofphoto" height="100" width="100"  />
                              </a>
                              </span>
                              </ul>
                            </div>-->
                            <a href="#openModalscanproof">
                              <img class="img-responsive" src="<?php echo base_url(); ?><?php echo $actual_idproof; ?><?php echo '?' . time(); ?>" name="idproofphoto" id="idproofphoto" height="100" width="100" />
                            </a>
                            <!--<p>
                              <a class="fancybox-effects-a" href="<?php echo base_url(); ?><?php echo $actual_idproof; ?><?php echo '?' . time(); ?>" title=""><img src="<?php echo base_url(); ?><?php echo $actual_idproof; ?><?php echo '?' . time(); ?>" name="idproofphoto" id="idproofphoto" width="100" height="100" /></a>
                            </p>-->
                            <?php } else { ?>
                            <p>
                              <img src="<?php echo base_url(); ?>assets/images/default1.png" height="100" width="100">
                            </p>
                          <?php } ?>
                        </td>
                        <!-- Pooja Mane : code to display employee id proof for FEDAI Members -->
                        <!--employee idproofphoto -->
                        <?php if($result[0]['registrationtype']== 'NM' && in_array($result[0]['excode'], $fedai_array)){?>
                        <td>
                          <?php $actual_empidproof = get_img_name($row['regnumber'], 'empr');
                            if (is_file($actual_empidproof)) {
                            ?>
                            <a href="#openModalscanempproof">
                              <img class="img-responsive" src="<?php echo base_url(); ?><?php echo $actual_empidproof; ?><?php echo '?' . time(); ?>" name="empidproofphoto" id="empidproofphoto" height="100" width="100" />
                            </a>
                            <?php } else { ?>
                            <p>
                              <img src="<?php echo base_url(); ?>assets/images/default1.png" height="100" width="100">
                            </p>
                          <?php } ?>
                        </td>
                      <?php }?>
                        <!-- Pooja Mane : code to display employee id proof for FEDAI Members ends -->

                        <!-- Pooja Mane : code to display employee id proof for BCBF Members 2024-10-17-->
                        <!--bcbf employee idproofphoto -->
                        <?php if($result[0]['date_of_commenc_bc'] != ''){?>
                        <td>
                          <?php $actual_bcempidproof = get_img_name($row['regnumber'], 'bank_bc_id_card');
                            if (is_file($actual_bcempidproof)) {
                            ?>
                            <a href="#openModalscanbcempproof">
                              <img class="img-responsive" src="<?php echo base_url(); ?><?php echo $actual_bcempidproof; ?><?php echo '?' . time(); ?>" name="bcempidproofphoto" id="bcempidproofphoto" height="100" width="100" />
                            </a>
                            <?php } else { ?>
                            <p>
                              <img src="<?php echo base_url(); ?>assets/images/default1.png" height="100" width="100">
                            </p>
                          <?php } ?>
                        </td>
                        <?php }?>
                        <!-- Pooja Mane : code to display employee id proof for BCBF Members ends 2024-10-17-->
                        <!-- 
                          - SAGAR WALZADE : Code start here
                          - Changes : adding new field and data that is : "declaration"
                        -->
                        <?php if ($row['registrationtype'] == 'O' || ($row['registrationtype'] == 'NM' && in_array($result[0]['excode'], $fedai_array))) { ?>
                          <td>
                            <?php $actual_declaration = get_img_name($row['regnumber'], 'declaration');
                              if (is_file($actual_declaration)) {
                              ?>
                              <a href="#openModalscandeclaration">
                                <img class="img-responsive" src="<?php echo base_url(); ?><?php echo $actual_declaration; ?><?php echo '?' . time(); ?>" name="declarationphoto" id="declarationphoto" height="100" width="100" />
                              </a>
                              <?php } else { ?>
                              <p>
                                <img src="<?php echo base_url(); ?>assets/images/default1.png" height="100" width="100">
                              </p>
                            <?php } ?>
                          </td>
                        <?php } ?>
                        <!-- Pooja Mane : code to display employee id proof for FEDAI Members ends -->
                        <!-- SAGAR WALZADE : Code end here -->
                        <?php /*?>     
                          <td>
                          <a href="<?php echo base_url(); ?>admin/Kyc/details/<?php echo base64_encode($row['regid']); ?>/<?php echo base64_encode($row['regnumber']); ?>">View Details</a>
                          </td>
                        <?php */ ?>
                      </tr>
                      <tr>
                        <td> </td>
                        <td>
                          <?php /*<input type="checkbox" name="cbox[]" id="cbox" value="name_checkbox" <?php if ($row['namesub'] != "" || $row['firstname'] != "" || $row['middlename'] != "" || $row['lastname'] != "") { echo 'checked="checked"'; } ?>> */ ?>
                          <input type="checkbox" name="cbox[]" id="cbox" value="name_checkbox" checked="checked" style="display:none;">
                        </td>
                        <td>
                          <?php /* <input type="checkbox" name="cbox[]" id="cbox" value="dob_checkbox" <?php if ($row['dateofbirth'] != ""  &&  $row['dateofbirth'] != '00-00-0000') { echo 'checked="checked"'; } ?>> */ ?>
                          <input type="checkbox" name="cbox[]" id="cbox" value="dob_checkbox" checked="checked" style="display:none;">
                        </td>
                        <?php
                          if ($result[0]['registrationtype'] == 'NM' || $result[0]['registrationtype'] == 'DB') { ?>
                          <td><input type="checkbox" name="cbox[]" id="cbox" value="emp_checkbox" disabled style="display:none"></td>
                          <?php } else { ?>
                          <td><input type="checkbox" name="cbox[]" id="cbox" value="emp_checkbox" <?php if ($row['associatedinstitute'] != "") {
                            echo 'checked="checked"';
                          } ?>></td>
                          <?php
                          } ?>
                          <td><input type="checkbox" name="cbox[]" id="cbox" value="photo_checkbox" <?php if (is_file(get_img_name($row['regnumber'], 'p'))) {
                            echo 'checked="checked"';
                          } ?>></td>
                          <td><input type="checkbox" name="cbox[]" id="cbox" value="sign_checkbox" <?php if (is_file(get_img_name($row['regnumber'], 's'))) {
                            echo 'checked="checked"';
                          } ?>></td>
                          <td><input type="checkbox" name="cbox[]" id="cbox" value="idprf_checkbox" <?php if (is_file(get_img_name($row['regnumber'], 'pr'))) {
                            echo 'checked="checked"';
                          } ?>></td>
                          <!-- Pooja mane code starts: 2024-08-05 -->
                          <?php if($result[0]['registrationtype']== 'NM' && in_array($result[0]['excode'], $fedai_array)){?>
                           <td><input type="checkbox" name="cbox[]" id="cbox" value="empidprf_checkbox" <?php if (is_file(get_img_name($row['regnumber'], 'empr'))) {
                            echo 'checked="checked"';
                          } ?>></td>
                        <?php } ?>
                        <!-- Pooja mane code end : 2024-08-05 -->

                        <!-- Pooja mane code starts: 2024-10-17 -->
                          <?php if($result[0]['date_of_commenc_bc'] != ''){?>
                           <td><input type="checkbox" name="cbox[]" id="cbox" value="bcempidprf_checkbox" <?php if (is_file(get_img_name($row['regnumber'], 'bank_bc_id_card'))) {
                            echo 'checked="checked"';
                          } ?>></td>
                        <?php } ?>
                        <!-- Pooja mane code end : 2024-10-17 -->

                          <!-- 
                            - SAGAR WALZADE : Code start here
                            - Changes : adding new field and data that is : "declaration"
                          -->
                          <?php if ($row['registrationtype'] == 'O'|| ($row['registrationtype'] == 'NM' && in_array($result[0]['excode'], $fedai_array))) { ?>
                            <td>
                              <input type="checkbox" name="cbox[]" id="cbox" value="declaration_checkbox" <?php if (is_file(get_img_name($row['regnumber'], 'declaration'))) {
                                echo 'checked="checked"';
                              } ?>>
                            </td>
                          <?php } ?>
                          <!-- SAGAR WALZADE : Code end here -->
                      </tr>
                      <?php }
                      } else {
                      
                      echo "No Recode Found..............!!!!";
                    }
                  ?>
                </tbody>
            </table>
            <?php
              if ($totalCount != "" && $totalCount != 0) {
                
                echo "Showing " . $this->uri->segment(6) . " of " . $totalCount . " Records";
              }
            ?>
            <?php /*?>     <?php
              $arraid=array();
              $total_id = $this->master_model->getRecords("admin_kyc_users ",array('DATE(date)'=>date('Y-m-d'),'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'New'),'allotted_member_id');
              $arraid=explode(',', $total_id[0]['allotted_member_id']);
              
            echo 'Showing '.$this->uri->segment(6).' of '. count($arraid). ' entries' ; ?><?php */ ?>
            <center>
              <a href="<?php echo base_url() ?>admin/kyc/Kyc/allocation_type/" class="btn btn-info">Back</a>
              <?php
                $members = $this->master_model->getRecords("member_kyc", array('regnumber' => $this->uri->segment(5), 'recommended_by' => $this->session->userdata('kyc_id'), 'record_source ' => 'New'));
                // echo $this->db->last_query();exit;
                if (count($members) > 0) {
                ?>
                <a href="javascript:void(0)" class="btn btn-info" onclick="check_submit()">Submit</a>
                <!--<a href="<?php echo base_url() ?>admin/kyc/Kyc/next_recode/<?php echo $this->uri->segment(5) ?>"  class="btn btn-info"  >Next</a>-->
                <?php /*?><?php if($next_id!='')
                  {?>
                  <a href="<?php echo base_url()?>admin/kyc/Kyc/member/<?php echo $next_id?>"  class="btn btn-info"  >Next</a>
                  <?php 
                  }
                  else
                  {?>
                  <a href="<?php echo base_url().'admin/kyc/Kyc/next_allocation_type'?>"  class="btn btn-info">Next</a>
                  <?php 
                }?><?php */ ?>
                <?php
                } else { ?>
                <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Submit"><br /> <br />
                <!-- <input type="button" class="btn btn-info" name="btnSubmit" value="Next"  id="check_next"> -->
                <?php
                ?> <button type="button" class="btn btn-info btn-sm" id="button_id" data-toggle="modal" data-target="#myModal">Send Mail</button><?php ?>
                <div class="modal fade" id="modal_id" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                  <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <div class="alert alert-danger alert-dismissible" id="error" style="display:none;">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                        </div>
                        <div class="alert alert-success alert-dismissible" id="success" style="display:none;">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="sd_master_label">Send Mail to Member<small></small> </h4>
                      </div>
                      <form role="form" method="post" id="frm_sd_master">
                        <div class="box box-primary">
                          <div class="row">
                            <!-- left column -->
                            <div class="col-md-12 col-sm-12 col-xs-12">
                              <div class="box-body">
                                <div class="form-group">
                                  <label class="col-sm-4 control-label" for="effective_from">Member Mail Id</label>
                                  <div class="col-sm-5">
                                    <input type="text" class="form-control" id="email" name="email" readonly="readonly" required value="<?php $email = $result[0]['email'];
                                    echo $email; ?>">
                                    <input type="hidden" name="form_type" id="form_type">
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-sm-4 control-label" for="st">Subject</label>
                                  <div class="col-sm-5">
                                    <input type="text" class="form-control" id="subject" name="subject" value="IIBF:- Member Profile.<?php echo $row['regnumber']; ?>">
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-sm-4 control-label" for="st">Mail Content</label>
                                  <div class="col-sm-6">
                                    <textarea id="mailtext" class="textarea" name="mailtext">Hello <?php echo $row['firstname'] . " " . $row['lastname']; ?>,
                                    </textarea>
                                  </div>
                                </div>
                                <input type="hidden" name="member_no" id="member_no" value="<?php echo $member_no = $this->uri->segment(5); ?>">
                              </div>
                              <!-- /.box-body -->
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12 col-md-offset-3">
                              <div class="box-footer">
                                <button type="button" tabindex="14" class="btn btn-info" onclick="send_mail();">Submit</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <?php
                } ?>
                <!--<input type="submit"  class="btn btn-info"   onclick="<?php echo base_url() ?>Kyc/next_recode" name="btnExit" id="btnExit" value="Next" >-->
                <div style="color:#F00; display:none" id="next_id"> You need to submit the record to proceed for next </div>
                <div style="color:#F00; display:none" id="next_id_sub">Kyc for this record is already submitted</div>
            </center>
          </form>
        </table>
      </div>
    </div>
    <!-- /.box-body -->
  </div>
  <!-- /.box -->
</div>
</section>
<?php
  $actual_photo = get_img_name($row['regnumber'], 'p');
  if (is_file($actual_photo)) { ?>
  <div id="openModalscanphoto" class="modalDialog">
    <div> <a href="#close" title="Close" class="close">X</a>
      <img class="img-responsive" src="<?php echo base_url(); ?><?php echo $actual_photo; ?><?php echo '?' . time(); ?>" name="scannedphoto" id="scannedphoto" height="500" width="500" />
    </div>
  </div>
  <?php
  }
?>
<?php
  $actual_signature = get_img_name($row['regnumber'], 's');
  if (is_file($actual_signature)) { ?>
  <div id="openModalscansignature" class="modalDialog">
    <div> <a href="#close" title="Close" class="close">X</a>
      <img class="img-responsive" src="<?php echo base_url(); ?><?php echo $actual_signature; ?><?php echo '?' . time(); ?>" name="scannedphoto" id="scannedphoto" height="500" width="500" />
    </div>
  </div>
  <?php
  }
?>
<?php
  $actual_proof = get_img_name($row['regnumber'], 'pr');
  if (is_file($actual_proof)) { ?>
  <div id="openModalscanproof" class="modalDialog">
    <div> <a href="#close" title="Close" class="close">X</a>
      <img class="img-responsive" src="<?php echo base_url(); ?><?php echo $actual_proof; ?><?php echo '?' . time(); ?>" name="scannedphoto" id="scannedphoto" height="500" width="500" />
    </div>
  </div>
  <?php
  }
?>
<!-- Pooja mane: code to show model for employment proof of FEDAI EXAM Members -->
<?php
  $actual_empidproof = get_img_name($row['regnumber'], 'empr');
  if (is_file($actual_empidproof)) { ?>
  <div id="openModalscanempproof" class="modalDialog">
    <div> <a href="#close" title="Close" class="close">X</a>
      <img class="img-responsive" src="<?php echo base_url(); ?><?php echo $actual_empidproof; ?><?php echo '?' . time(); ?>" name="scannedphoto" id="scannedphoto" height="500" width="500" />
    </div>
  </div>
  <?php
  }
?>
<!-- Pooja mane: code to show model for employment proof ends here -->

<!-- Pooja mane: code to show model for employment proof of BCBF EXAM Members 2024-10-17-->
<?php
  $actual_bcempidproof = get_img_name($row['regnumber'], 'bank_bc_id_card');
  if (is_file($actual_bcempidproof)) { ?>
  <div id="openModalscanbcempproof" class="modalDialog">
    <div> <a href="#close" title="Close" class="close">X</a>
      <img class="img-responsive" src="<?php echo base_url(); ?><?php echo $actual_bcempidproof; ?><?php echo '?' . time(); ?>" name="bank_bc_id_card" id="bank_bc_id_card" height="500" width="500" />
    </div>
  </div>
  <?php
  }
?>
<!-- Pooja mane: code to show model for BCBF employment proof ends here 2024-10-17-->

<?php
  /*
    - SAGAR WALZADE : Code start here
    - Changes : show declaration image in popup
  */
  if ((!empty($result) && isset($result[0]['registrationtype'])) && ($result[0]['registrationtype'] == 'O' || ($row['registrationtype'] == 'NM' && in_array($result[0]['excode'], $fedai_array)))) {//Added fedai member check for declaration by pooja mane 2024-08-14
    $actual_declaration = get_img_name($row['regnumber'], 'declaration');
    if (is_file($actual_declaration)) { ?>
    <div id="openModalscandeclaration" class="modalDialog">
      <div> <a href="#close" title="Close" class="close">X</a>
        <img class="img-responsive" src="<?php echo base_url(); ?><?php echo $actual_declaration; ?><?php echo '?' . time(); ?>" name="scannedphoto" id="scannedphoto" height="500" width="500" />
      </div>
    </div>
    <?php
    }
  }
  /* SAGAR WALZADE : Code end here */
?>
</div>
</div>
<!-- Data Tables -->
<link href="<?php echo base_url(); ?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">
<!-- Data Tables -->
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
<script src="<?php echo base_url() ?>assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/plugins/datepicker/datepicker3.css">
<script src="<?php echo base_url() ?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.css">
<script src="<?php echo base_url() ?>assets/js/parsley.min.js"></script>
<script src="<?php echo base_url() ?>js/validation.js?<?php echo time(); ?>"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
  $('#search').parsley('validate');
</script>
<!--<script src="<?php echo base_url() ?>js/js-paginate.js"></script>-->
<script type="application/javascript">
  $(document).ready(function() {
   	$('#from_date').datepicker({
   		format: 'yyyy-mm-dd',
   		endDate: '+0d',
   		autoclose: true
      }).on('changeDate', function() {
   		$('#to_date').datepicker('setStartDate', new Date($(this).val()));
    });
    
   	$('#to_date').datepicker({
   		format: 'yyyy-mm-dd',
   		endDate: '+0d',
   		autoclose: true
      }).on('changeDate', function() {
   		$('#from_date').datepicker('setEndDate', new Date($(this).val()));
    });
    
   	/*$(".chk").on('click', function(e){
   		alert('in');
   		
      var status = this.checked; // "select all" checked status
      alert(status);
      $('.chk').each(function(){ //iterate all listed checkbox items
      this.checked = status; //change ".checkbox" checked status
      });
   		
    })*/
  });
  
  $(function() {
    
    
   	//$("#listitems").DataTable();
   	/*var base_url = '<?php //echo base_url(); 
      ?>';
      var listing_url = base_url+'admin/Report/getList';
      
      // Pagination function call
      paginate(listing_url,'','','');
    $("#base_url_val").val(listing_url);*/
  });
  
  /*Show modal */
  function show_modal(modal_id) {
   	$(modal_id).modal({
   		backdrop: 'static',
   		keyboard: false,
   		show: true
    });
  }
  /*show toastr message */
  function show_message(type, content) {
   	toastr.options = {
   		"closeButton": true,
   		"debug": false,
   		"newestOnTop": false,
   		"progressBar": false,
   		"positionClass": "toast-top-right",
   		"preventDuplicates": false,
   		"onclick": null,
   		"showDuration": "300",
   		"hideDuration": "1000",
   		"timeOut": "5000",
   		"extendedTimeOut": "1000",
   		"showEasing": "swing",
   		"hideEasing": "linear",
   		"showMethod": "fadeIn",
   		"hideMethod": "fadeOut"
    }
   	toastr[type](content);
  }
  
  $(document).on('click', '#button_id', function(e) {
   	e.preventDefault();
   	var button_id = $(this).data('value');
   	//var button_id = $(this).data('id');
   	show_modal('#modal_id');
   	return false;
  });
  
  function send_mail(member_no, email, subject, mailtext) {
   	//alert('***');
   	//document.getElementById('capacitymsg').style.display = 'none';
   	//$("#btnSubmit").prop('disabled', false);
   	var member_no = $('#member_no').val();
   	var email = $('#email').val();
   	var subject = $('#subject').val();
   	var mailtext = $('#mailtext').val();
   	$.ajax({
   		type: "POST",
   		url: site_url + "admin/kyc/Kyc/send_mail",
   		data: {
   			'member_no': member_no,
   			'email': email,
   			'subject': subject,
   			'mailtext': mailtext
      },
   		dataType: 'JSON',
   		success: function(data) {
   			if (data != "") {
   				$('#success').css("display", "block");
   				$('#success').html(data.success);
   				$('#success').fadeOut(5000);
   				$('#modal_id').modal('toggle');
        }
        
        
      }
    }, "json");
   	$(".loading").hide();
  }
  
  function check_next() {
   	$('#next_id').show();
  }
  
  function check_submit() {
   	$('#next_id_sub').show();
  }
</script>
<script type="text/javascript">
  $(document).ready(function() {
   	$('#lightgallery_photo,#lightgallery_sign,#lightgallery_proof').lightGallery();
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {
   	//alert('123');
   	$(".textarea").wysihtml5();
  });
</script>
<script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<link href="<?php echo base_url(); ?>assets/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.css" rel="stylesheet">
<script src="<?php echo base_url('assets/dist/js/picturefill.min.js') ?>"></script>
<script src="<?php echo base_url('assets/dist/js/lightgallery.js') ?>"></script>
<script src="<?php echo base_url('assets/dist/js/lg-zoom.js') ?>"></script>
<script src="<?php echo base_url('assets/dist/js/lg-hash.js') ?>"></script>
<script src="<?php echo base_url('assets/dist/js/jquery.mousewheel.min.js') ?>"></script>
<!--<script>
  (function (global) {
  
  if(typeof (global) === "undefined")
  {
  throw new Error("window is undefined");
  }
  
  var _hash = "!";
  var noBackPlease = function () {
  global.location.href += "#";
  
  // making sure we have the fruit available for juice....
  // 50 milliseconds for just once do not cost much (^__^)
  global.setTimeout(function () {
  global.location.href += "!";
  }, 50);
  };
  
  // Earlier we had setInerval here....
  global.onhashchange = function () {
  if (global.location.hash !== _hash) {
  global.location.hash = _hash;
  }
  };
  
  global.onload = function () {
  
  noBackPlease();
  
  // disables backspace on page except on input fields and textarea..
  document.body.onkeydown = function (e) {
  var elm = e.target.nodeName.toLowerCase();
  if (e.which === 8 && (elm !== 'input' && elm  !== 'textarea')) {
  e.preventDefault();
  }
  // stopping event bubbling up the DOM tree..* 
  e.stopPropagation();
  };
  
  };
  
  })(window);
</script>-->
<?php $this->load->view('admin/kyc/includes/footer'); ?>