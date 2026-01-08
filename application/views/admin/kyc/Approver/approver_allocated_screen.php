<?php $this->load->view('admin/kyc/includes/header');?>
<?php $this->load->view('admin/kyc/includes/approver_sidebar');?>
<link href="<?php echo base_url('assets/css/popup.css')?>" rel="stylesheet">
<link href="<?php echo base_url('assets/dist/css/lightgallery.css')?>" rel="stylesheet">
<script src="<?php echo base_url('assets/dist/js/jquery.min.js')?>"></script>
<script>
  var site_url = "<?php echo base_url(); ?>";//Pooja mane 31-01-2025
</script>
<style>
  .min-height{ min-height:650px;}
</style>
<!-- Added by pooja mane for fedai exam code check 2024-08-13 -->
<?php $fedai_array= array(1009); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      KYC Verification 
    </h1>
  </section>
  <br />
  <div class="col-md-12">
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
    <?php } ?>
    <?php if($error!=''){?>
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
        <?php echo $error; ?>
      </div>
      <?php } if($success!=''){ ?>
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
        <?php echo $success; ?>
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
              if(isset($result[0]['createdon']) && !empty($result[0]['createdon'])){
                $createdon_date = '<i style="font-size:11px;">( Registered on '.$result[0]['createdon'].' )</i>';
                }else{
                $createdon_date = "";
              }
            ?>
            <h3 class="box-title">Member selected <?php echo $createdon_date; ?></h3>
            <!-- - SAGAR WALZADE : Code end here -->
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <?php 
              $selected_srno=$this->uri->segment(6);
              if($this->uri->segment(6) < $this->uri->segment(7))
              {
                $selected_srno+=1;
                if($selected_srno==$this->uri->segment(7))
                {
                  $selected_srno=$this->uri->segment(6);
                }
              }
              else
              {	
                $selected_srno=$this->uri->segment(7);
              }
              $totalCount = $totalRecCount;
            ?>
            <form class="form-horizontal" name="checkmember" id="checkmember" action="" method="post">
              <input type="hidden" name="regnumber" id="regnumber" value="<?php echo $this->uri->segment(5)?>">
              <input type="hidden" name="srno" id="srno" value="<?php echo $this->uri->segment(6)?>">
              <input type="hidden" name="totalRecCount" id="totalRecCount" value="<?php echo $totalCount; ?>">
              <table id="listitems" class="table table-bordered table-striped dataTables-example">
                <thead>
                  <tr>
                    <th id="">Membership/Registration No</th>
                    <th id="">Candidate Name</th>
                    <th id="">D.O.B</th>
                    <th id="">Employer</th>
                    <th id="">Photo</th>
                    <th id="">Sign</th>
                    <th id="">Id-Proof</th>
                    <??>
                    <!-- Pooja mane code starts: 2024-08-13 -->
                      <?php
                          if($result[0]['registrationtype']== 'NM' && in_array($result[0]['excode'], $fedai_array)){?>
                      <th id="">Employment Proof</th>
                      <?php }?>
                    <!-- Pooja mane code ends: 2024-08-13 -->

                    <!-- code starts for bcbf empid 2024-08-13 -->
                      <?php
                          if($result[0]['date_of_commenc_bc'] != ''){?>
                      <th id="">BC Employment Proof</th>
                      <?php }?>

                      <?php
                          if($result[0]['registrationtype']== 'NM' && in_array($result[0]['excode'], $fedai_array)){?>
                      <th id="">Declaration</th>
                      <?php }?>
                    <?php
                      if (!empty($result) && isset($result[0]['registrationtype']) && $result[0]['registrationtype'] == 'O') {
                        if ($result[0]['createdon'] >= '2022-04-01') {
                          $is_required = "<i style='color:red;font-size:11px;' data-createdon='".$result[0]['createdon']."'>Required</i>";
                          }else{
                          $is_required = "<i style='color:red;font-size:11px;' data-createdon='".$result[0]['createdon']."'>Optional</i>";
                        }
                      ?>
                      <th id="">Declaration</th>
                      <?php
                      }
                    ?>
                    <!-- SAGAR WALZADE : Code end here -->
                  </tr>
                </thead>
                <tbody class="no-bd-y" id="list">
                  <?php 
                    if(count($result))
                    {
                      // echo '<pre>';print_r($result);
                      foreach($result as $row)
                      {	
                        
                        
                        $employer=array();
                        if($row['registrationtype']=='O' || $row['registrationtype']=='A' || $row['registrationtype']=='F')
                        {
                          $select = 'institude_id,name';
                          $employer= $this->master_model->getRecords("institution_master", array('institude_id'=>$row['associatedinstitute']),$select);
                        }
                      ?>
                      <tr>
                        <td><?php echo $row['regnumber'];?><?php 
                          
                          $this->db->order_by('kyc_id','DESC');
                          $this->db->limit('1');
                          $edited_hist = $this->master_model->getRecords('member_kyc',array('regnumber' => $row['regnumber'],'field_count >'=>'0','user_edited_date !=' => '0000-00-00 00:00:00'));
                          //echo $this->db->last_query();die;
                        ?>
                        
                        </td>
                        <td>
                          <?php  
                            $username=$row['namesub'].' '.$row['firstname'].' '.$row['middlename'].' '.$row['lastname'];
                            echo  $userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
                          ?>
                        </td>
                        <td><?php if($row['dateofbirth']!='00-00-0000' && $row['dateofbirth']!='' && $row['dateofbirth']!='00-00-0000'){echo date('d-m-Y',strtotime($row['dateofbirth']));}?></td>
                        <td><?php
                          if(count($employer) > 0)
                          {
                            echo $employer[0]['name'];
                          }
                          else
                          {
                            echo '-';	
                          }?></td>
                          <td>
                            <!--scannedphoto -->
                            <?php $actual_idproof = get_img_name($row['regnumber'],'p');
                              if(is_file($actual_idproof)){
                              ?>
                              <!--<div class="demo-gallery">
                                <ul id="lightgallery_photo" class="list-unstyled row">
                                <span class=""  data-src="<?php echo base_url();?><?php echo $actual_idproof;?>" >
                                <a href="">
                                <img class="img-responsive" src="<?php echo base_url();?><?php echo $actual_idproof;?><?php echo '?'.time(); ?>"  name="scannedphoto" id="scannedphoto" height="100" width="100"  />
                                </a>
                                </span>
                                </ul>
                              </div>-->
                              <a href="#openModalscanphoto">
                                <img class="img-responsive" src="<?php echo base_url();?><?php echo $actual_idproof;?><?php echo '?'.time(); ?>"  name="scannedphoto" id="scannedphoto" height="100" width="100"  />
                              </a>
                              <?php }else{ ?>
                              <img src="<?php echo base_url();?>assets/images/default1.png" height="100" width="100" >
                            </p>
                          <?php } ?>
                      </td>
                      <td>
                        <!--scannedsignaturephoto -->
                        <?php $actual_idproof = get_img_name($row['regnumber'],'s');
                          if(is_file($actual_idproof)){
                          ?>
                          <!--<div class="demo-gallery">
                            <ul id="lightgallery_sign" class="list-unstyled row">
                            <span class=""  data-src="<?php echo base_url();?><?php echo $actual_idproof;?>" >
                            <a href="">
                            <img class="img-responsive" src="<?php echo base_url();?><?php echo $actual_idproof;?><?php echo '?'.time(); ?>"  name="scannedsignaturephoto" id="scannedsignaturephoto" height="100" width="100"  />
                            </a>
                            </span>
                            </ul>
                          </div>-->
                          <a href="#openModalscansignature">
                            <img class="img-responsive" src="<?php echo base_url();?><?php echo $actual_idproof;?><?php echo '?'.time(); ?>"  name="scannedsignaturephoto" id="scannedsignaturephoto" height="100" width="100"  />
                          </a>
                          <!--<p>
                            <a class="fancybox-effects-a" href="<?php echo base_url();?><?php echo $actual_idproof;?><?php echo '?'.time(); ?>" title=""> <img src="<?php echo base_url();?><?php echo $actual_idproof;?><?php echo '?'.time(); ?>" name="scannedsignaturephoto" id="scannedsignaturephoto" width="100" height="100" /></a>
                          </p>-->
                          <?php }else{ ?>
                          <p>
                            <img src="<?php echo base_url();?>assets/images/default1.png" height="100" width="100" >
                          </p>
                        <?php } ?>
                      </td>
                      <td>
                        <!--idproofphoto -->
                        <?php $actual_idproof = get_img_name($row['regnumber'],'pr');
                          if(is_file($actual_idproof)){
                          ?>
                          <!--<div class="demo-gallery">
                            <ul id="lightgallery_proof" class="list-unstyled row">
                            <span class=""  data-src="<?php echo base_url();?><?php echo $actual_idproof;?>" >
                            <a href="">
                            <img class="img-responsive" src="<?php echo base_url();?><?php echo $actual_idproof;?><?php echo '?'.time(); ?>"  name="idproofphoto" id="idproofphoto" height="100" width="100"  />
                            </a>
                            </span>
                            </ul>
                          </div>-->
                          <a href="#openModalscanproof">
                            <img class="img-responsive" src="<?php echo base_url();?><?php echo $actual_idproof;?><?php echo '?'.time(); ?>"  name="idproofphoto" id="idproofphoto" height="100" width="100"  />
                          </a>
                          <!--<p>
                            <a class="fancybox-effects-a" href="<?php echo base_url();?><?php echo $actual_idproof;?><?php echo '?'.time(); ?>" title=""><img src="<?php echo base_url();?><?php echo $actual_idproof;?><?php echo '?'.time(); ?>" name="idproofphoto" id="idproofphoto" width="100" height="100" /></a>
                          </p>-->
                          <?php }else{ ?>
                          <p>
                            <img src="<?php echo base_url();?>assets/images/default1.png" height="100" width="100">
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
                      <!-- code to display old bcbf emp id image 2024-10-18 -->
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
                      <!-- code to display old bcbf emp id image ends 2024-10-18 -->
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
                      <!-- SAGAR WALZADE : Code end here -->
                      <?php /*?>     
                        <td>
                        <a href="<?php echo base_url(); ?>admin/Kyc/details/<?php echo base64_encode($row['regid']); ?>/<?php echo base64_encode($row['regnumber']); ?>">View Details</a>
                        </td>
                      <?php */?>
                    </tr>
                    <tr>
                      <td> </td>
                      <td><?php
                      // <input type="checkbox" name="cbox[]" id="cbox" value="name_checkbox"  <?php if($row['namesub']!="" || $row['firstname']!="" || $row['middlename']!="" || $row['lastname']!="" ){echo 'checked="checked"';echo 'disabled=true';}>?>
                      <input style="display:none;" type="checkbox" name="cbox[]" id="cbox" value="name_checkbox" checked="checked" >
                      </td> 
                      <td><?php
                      // <input type="checkbox" name="cbox[]" id="cbox" value="dob_checkbox" <?php  if($row['dateofbirth']!=""  &&  $row['dateofbirth']!='00-00-0000'){echo 'checked="checked"'; echo 'disabled=true';}>?> 
                      <input type="checkbox" name="cbox[]" id="cbox" value="dob_checkbox" checked="checked" style="display:none;">
                      </td>
                      
                      <?php 
                        if($result[0]['registrationtype']=='NM' || $result[0]['registrationtype']=='DB' )
                        {?>
                        <td><input style="display:none;" type="checkbox" name="cbox[]" id="cbox" value="emp_checkbox" disabled ></td>
                        <?php }
                        else
                        {?>
                        <td><input style="display:none;" type="checkbox" name="cbox[]" id="cbox" value="emp_checkbox" <?php if($row['associatedinstitute']!="" ){echo 'checked="checked"'; echo 'disabled=true';}?>></td>
                        <?php 
                        }?>
                        <td>
                          <?php if(is_file(get_img_name($row['regnumber'],'p')))
                            {
                              if($edited_hist[0]['edited_mem_photo'] !='' && $edited_hist[0]['edited_mem_photo'] =='0')
                              {?>
                              <input type="checkbox" name="cbox[]" id="cbox" value="photo_checkbox" checked="checked" disabled=true>
                              <input type="hidden" name="cbox[]" value="photo_checkbox">
                              <?php }else{ ?>
                              <input type="checkbox" name="cbox[]" id="cbox" value="photo_checkbox" checked="checked">
                              <?php }
                            }else {?>
                            <input type="checkbox" name="cbox[]" id="cbox" value="photo_checkbox">
                          <?php } ?>
                        </td>
                        <td>
                          <?php if(is_file(get_img_name($row['regnumber'],'s')))
                            {
                              if($edited_hist[0]['edited_mem_sign'] !='' && $edited_hist[0]['edited_mem_sign'] =='0')
                              {?>
                              <input type="checkbox" name="cbox[]" id="cbox" value="sign_checkbox" checked="checked" disabled=true>
                              <input type="hidden" name="cbox[]" value="sign_checkbox">
                              <?php }else{ ?>
                              <input type="checkbox" name="cbox[]" id="cbox" value="sign_checkbox" checked="checked">
                              <?php }
                            }else {?>
                            <input type="checkbox" name="cbox[]" id="cbox" value="sign_checkbox">
                          <?php } ?>
                        </td>
                        <td>
                          <?php if(is_file(get_img_name($row['regnumber'],'pr')))
                            {
                              if($edited_hist[0]['edited_mem_proof'] !='' && $edited_hist[0]['edited_mem_proof'] =='0')
                              {?>
                              <input type="checkbox" name="cbox[]" id="cbox" value="idprf_checkbox" checked="checked" disabled=true>
                              <input type="hidden" name="cbox[]" value="idprf_checkbox">
                              <?php }else{ ?>
                              <input type="checkbox" name="cbox[]" id="cbox" value="idprf_checkbox" checked="checked">
                              <?php }
                            }else {?>
                            <input type="checkbox" name="cbox[]" id="cbox" value="idprf_checkbox">
                          <?php } ?>
                        </td>

                         <!-- Pooja mane code to display old bcbf emp id starts: 2024-10-18 -->
                          <?php if($result[0]['registrationtype']== 'NM' && in_array($result[0]['excode'], $fedai_array)){?>
                           <td><input type="checkbox" name="cbox[]" id="cbox" value="empidprf_checkbox" <?php if (is_file(get_img_name($row['regnumber'], 'empr'))) {
                            echo 'checked="checked"';
                          } ?>></td>
                        <?php } ?>
                        <!-- Pooja mane code to display old bcbf emp id end : 2024-10-18 -->

                        <!-- Pooja mane code starts: 2024-08-05 -->
                          <?php if($result[0]['date_of_commenc_bc'] != ''){?>
                           <td><input type="checkbox" name="cbox[]" id="cbox" value="bcempidprf_checkbox" <?php if (is_file(get_img_name($row['regnumber'], 'bank_bc_id_card'))) {
                            echo 'checked="checked"';
                          } ?>></td>
                        <?php } ?>
                        <!-- Pooja mane code end : 2024-08-05 -->

                        <!-- 
                          - SAGAR WALZADE : Code start here
                          - Changes : show new column checkbox input field of declaration
                        -->
                        <?php if ($row['registrationtype'] == 'O' || ($row['registrationtype'] == 'NM' && in_array($result[0]['excode'], $fedai_array))) { ?>
                          <td>
                            <input type="checkbox" name="cbox[]" id="cbox" value="declaration_checkbox" <?php if (is_file(get_img_name($row['regnumber'], 'declaration'))) {
                              echo 'checked="checked"';
                            } ?>>
                          </td>
                        <?php } ?>
                        <!-- SAGAR WALZADE : Code end here -->
                    </tr>
                    <?php }
                    }else{
                    
                    echo "No Record Found..............!!!!"; 
                  }
                ?>                  
              </tbody>
            </table>
            <?php 
              if($totalCount != "" && $totalCount != 0)
              {
                
                echo "Showing ".$this->uri->segment(6)." of ".$totalCount." Records"; 
              }
            ?> 
            <?php /*?>  <?php   $arraid=array();
              $total_id = $this->master_model->getRecords("admin_kyc_users ",array('DATE(date)'=>date('Y-m-d'),'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'New'),'allotted_member_id');
              $arraid=explode(',', $total_id[0]['allotted_member_id']);
              
            echo 'Showing '.$this->uri->segment(6).' of '. count($arraid). ' entries' ; ?><?php */?>
            <center>
              <a href="<?php echo base_url()?>admin/kyc/Approver/allocation_type/"  class="btn btn-info"  >Back</a>
              <?php 
                $where2 = ("(user_edited_date = '0000-00-00 00:00:00' OR user_edited_date > '".date('Y-m-d H:i:s')."')");
                $where3 = ("(approved_by = ".$this->session->userdata('kyc_id')." OR recommended_by = ".$this->session->userdata('kyc_id').")");
                $this->db->where($where2);
                $this->db->where($where3);
                // $check_kyc_status = $this->master_model->getRecords("member_kyc", array('regnumber'=>$this->uri->segment(5)));
                $check_kyc_status = $this->master_model->getRecords("member_kyc", array('regnumber'=>$this->uri->segment(5)),'',array('kyc_id'=>'DESC'),'','1');
                //echo $this->db->last_query();exit;
                /*$where2 = ("(user_edited_date = '0000-00-00 00:00:00' OR user_edited_date > '".date('Y-m-d H:i:s')."')");
                  $this->db->where($where2);
                  // $check_kyc_status = $this->master_model->getRecords("member_kyc", array('regnumber'=>$this->uri->segment(5)));
                $check_kyc_status_recomender = $this->master_model->getRecords("member_kyc", array('regnumber'=>$this->uri->segment(5),'recommended_by'=>$this->session->userdata('kyc_id')),'',array('kyc_id'=>'DESC'),'','1');*/
                //echo $this->db->last_query();exit;
              ?>
              <input type="submit" class="btn btn-info" name="btnSubmitkyc" id="btnSubmitkyc" value="KYC Complete" > 
              <input type="submit" class="btn btn-info" name="btnSubmitRecmd" id="btnSubmitRecmd" value="Recommend" > 
              <!--  <a href="<?php echo base_url()?>admin/kyc/Approver/kyc_complete/<?php echo $reg_no?>"  class="btn btn-info" name='btncom' >KYC Complete</a>-->
               
              <!-- START : Send Custom Mail button functionality by pooja mane 30-01-2025 -->
               <?php
                ?> <button type="button" class="btn btn-info" id="button_id" data-toggle="modal" data-target="#myModal">Send Mail</button><?php ?>
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
                                    <input type="text" class="form-control" id="email" name="email" readonly="readonly" required value="<?php $email = $row['email'];
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
              <!-- START : Send Custom Mail button functionality by pooja mane 30-01-2025 -->

              <div style="color:#F00; display:none" id="next_id"> You need to complete the KYC or  Recommend  the current record  to proceed for next record</div>
              <div style="color:#F00; display:none" id="next_id_com"> This record is already  been submitted by you</div>
              <div style="color:#F00; display:none" id="next_id_rec"> This record is already been submitted by you </div>
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
  $actual_photo= get_img_name($row['regnumber'],'p');
  if(is_file($actual_photo))
  {?>
  <div id="openModalscanphoto" class="modalDialog">
    <div>	<a href="#close" title="Close" class="close">X</a>
      <img class="img-responsive" src="<?php echo base_url();?><?php echo $actual_photo;?><?php echo '?'.time(); ?>"  name="scannedphoto" id="scannedphoto" height="500" width="500"  />
    </div>
  </div>
  <?php 
  }
?>
<?php 
  $actual_signature= get_img_name($row['regnumber'],'s');
  if(is_file($actual_signature))
  {?>
  <div id="openModalscansignature" class="modalDialog">
    <div>	<a href="#close" title="Close" class="close">X</a>
      <img class="img-responsive" src="<?php echo base_url();?><?php echo $actual_signature;?><?php echo '?'.time(); ?>"  name="scannedphoto" id="scannedphoto" height="500" width="500"  />
    </div>
  </div>
  <?php 
  }
?>
<?php 
  $actual_proof= get_img_name($row['regnumber'],'pr');
  if(is_file($actual_proof))
  {?>
  <div id="openModalscanproof" class="modalDialog">
    <div>	<a href="#close" title="Close" class="close">X</a>
      <img class="img-responsive" src="<?php echo base_url();?><?php echo $actual_proof;?><?php echo '?'.time(); ?>"  name="scannedphoto" id="scannedphoto" height="500" width="500"  />
    </div>
  </div>
  <?php 
  }
?>
<!-- 
  - SAGAR WALZADE : Code start here
  - Changes : show declaration image in popup
-->
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

<!-- Pooja mane: code to show model for employment proof of BCBF EXAM Members 2024-10-21 -->
<?php
  $actual_bcempidproof = get_img_name($row['regnumber'], 'bank_bc_id_card');
  if (is_file($actual_bcempidproof)) { ?>
  <div id="openModalscanbcempproof" class="modalDialog">
    <div> <a href="#close" title="Close" class="close">X</a>
      <img class="img-responsive" src="<?php echo base_url(); ?><?php echo $actual_bcempidproof; ?><?php echo '?' . time(); ?>" name="scannedphoto" id="scannedphoto" height="500" width="500" />
    </div>
  </div>
  <?php
  }
?>
<!-- Pooja mane: code to show model for employment proof ends here 2024-10-21 -->

<?php
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
?>
<!-- SAGAR WALZADE : Code end here -->
</div>
</div>

<!-- Data Tables -->
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">
<!-- Data Tables -->
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
<script src="<?php echo base_url()?>assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/datepicker/datepicker3.css">
<script src="<?php echo base_url()?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.css">
<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>
<script src="<?php echo base_url()?>js/validation.js"></script>
<script type="text/javascript">
  $('#search').parsley('validate');
</script>
<!--<script src="<?php echo base_url()?>js/js-paginate.js"></script>-->
<script type="application/javascript">
  $(document).ready(function() 
  {
   	$('#from_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
   		$('#to_date').datepicker('setStartDate', new Date($(this).val()));
    }); 
   	
   	$('#to_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
   		$('#from_date').datepicker('setEndDate', new Date($(this).val()));
    });
   	
   	
  });
  
  $(function () {
   	//$("#listitems").DataTable();
   	/*var base_url = '<?php //echo base_url(); ?>';
      var listing_url = base_url+'admin/Report/getList';
      
      // Pagination function call
      paginate(listing_url,'','','');
    $("#base_url_val").val(listing_url);*/
  });
  
  // <!-- START : Send Custom Mail button functionality by pooja mane 30-01-2025 -->
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
     	// alert('***');
     	//document.getElementById('capacitymsg').style.display = 'none';
     	//$("#btnSubmit").prop('disabled', false);
     	var member_no = $('#member_no').val();
     	var email = $('#email').val();
     	var subject = $('#subject').val();
     	var mailtext = $('#mailtext').val();
     	$.ajax({
     		type: "POST",
     		url: site_url + "admin/kyc/Approver/send_mail",
     		data: {
     			'member_no': member_no,
     			'email': email,
     			'subject': subject,
     			'mailtext': mailtext
        },
     		dataType: 'JSON',
     		success: function(data) {
     			if (data != "") {
            alert("Acknowledgment mail is sent successfully");
     				$('#success').css("display", "block");
     				$('#success').html(data.success);
     				$('#success').fadeOut(5000);
     				$('#modal_id').modal('toggle');
          }
        }
      }, "json");
     	$(".loading").hide();
    }

    $(document).ready(function() {
     	//alert('123');
     	$(".textarea").wysihtml5();
    });

  // <!-- START : Send Custom Mail button functionality by pooja mane 30-01-2025 -->
  
  function check_next()
  {
   	$('#next_id').show();
   	
   	$('#next_id_com').hide();
   	$('#next_id_rec').hide();
  }
  
  function check_complete()
  {
   	$('#next_id_com').show();
    $('#next_id').hide();
   	$('#next_id_rec').hide();
  }
  
  function check_recommend()
  {
   	$('#next_id_rec').show();
   	$('#next_id_com').hide();
   	$('#next_id').hide();
  }

</script>
<script type="text/javascript">
  $(document).ready(function(){
    $('#lightgallery_photo,#lightgallery_sign,#lightgallery_proof').lightGallery();
  });
</script>
<script src="<?php echo base_url('assets/dist/js/picturefill.min.js')?>"></script>
<script src="<?php echo base_url('assets/dist/js/lightgallery.js')?>"></script>
<script src="<?php echo base_url('assets/dist/js/lg-zoom.js')?>"></script>
<script src="<?php echo base_url('assets/dist/js/lg-hash.js')?>"></script>
<script src="<?php echo base_url('assets/dist/js/jquery.mousewheel.min.js')?>"></script>
<!-- START : Send Custom Mail button functionality by pooja mane 30-01-2025 -->
<script src="<?php echo base_url(); ?>assets/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<link href="<?php echo base_url(); ?>assets/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.css" rel="stylesheet">
<!-- START : Send Custom Mail button functionality by pooja mane 30-01-2025 -->
<?php $this->load->view('admin/kyc/includes/footer');?>