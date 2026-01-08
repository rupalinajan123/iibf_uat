<?php $this->load->view('admin/kyc/includes/header');?>
<?php $this->load->view('admin/kyc/includes/approver_sidebar');?>
<link href="<?php echo base_url('assets/css/popup.css')?>" rel="stylesheet">	
<link href="<?php echo base_url('assets/dist/css/lightgallery.css')?>" rel="stylesheet">
<script src="<?php echo base_url('assets/dist/js/jquery.min.js')?>"></script>
<style>
  .min-height{ min-height:650px;}
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
    KYC Verification </h1>
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
                    <!-- 
                      - SAGAR WALZADE : Code start here
                      - Changes : one declaration column added and declaration is required or not is displayed : 
                      - it is required for those ordinary users who are registered after 1 april 2022
                    -->
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
                    //print_r($result);exit;
                    if(count($result))
                    {
                      
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
                        <td><?php echo $row['regnumber'];?></td>
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
                          
                          <td><!--scannedphoto -->
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
                      <td><!--scannedsignaturephoto -->
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
                      <td><!--idproofphoto -->
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
                      
                      <!-- 
                        - SAGAR WALZADE : Code start here
                        - Changes : show new column of declaration
                      -->
                      <?php if ($row['registrationtype'] == 'O') { ?>
                        <td>
                          <?php $actual_declaration = get_img_name($row['regnumber'], 'declaration');
                            if (is_file($actual_declaration)) {
                            ?>
                            <a href="#openModaldeclaration">
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
                      
                      <?php /*?>     <td>
                        <a href="<?php echo base_url(); ?>admin/Kyc/details/<?php echo base64_encode($row['regid']); ?>/<?php echo base64_encode($row['regnumber']); ?>">View Details</a>
                      </td><?php */?>
                    </tr>
                    
                    <tr>
                      <td> </td>
                      <td><input type="checkbox" name="cbox[]" id="cbox" value="name_checkbox"  <?php if($row['namesub']!="" || $row['firstname']!="" || $row['middlename']!="" || $row['lastname']!="" ){echo 'checked="checked"';}?>></td>
                      <td><input type="checkbox" name="cbox[]" id="cbox" value="dob_checkbox" <?php  if($row['dateofbirth']!=""  &&  $row['dateofbirth']!='00-00-0000'){echo 'checked="checked"';}?>></td>
                      <?php 
                        if($result[0]['registrationtype']=='NM' || $result[0]['registrationtype']=='DB' )
                        {?>
                        <td><input type="checkbox" name="cbox[]" id="cbox" value="emp_checkbox" disabled></td>
                        <?php }
                        else
                        {?>
                        <td><input type="checkbox" name="cbox[]" id="cbox" value="emp_checkbox" <?php if($row['associatedinstitute']!="" ){echo 'checked="checked"';}?>></td>
                        <?php 
                        }?>
                        <td><input type="checkbox" name="cbox[]" id="cbox" value="photo_checkbox" <?php if(is_file(get_img_name($row['regnumber'],'p'))){echo 'checked="checked"';}?>></td>
                        <td><input type="checkbox" name="cbox[]" id="cbox" value="sign_checkbox" <?php if(is_file(get_img_name($row['regnumber'],'s'))){echo 'checked="checked"';}?>></td>
                        <td><input type="checkbox" name="cbox[]" id="cbox" value="idprf_checkbox" <?php if(is_file(get_img_name($row['regnumber'],'pr'))){echo 'checked="checked"';}?>></td>
                        
                        <!-- 
                          - SAGAR WALZADE : Code start here
                          - Changes : show new column checkbox input field of declaration
                        -->
                        <?php if ($row['registrationtype'] == 'O') { ?>
                          <td><input type="checkbox" name="cbox[]" id="cbox" value="declaration_checkbox" <?php if (is_file(get_img_name($row['regnumber'], 'declaration'))) {
                            echo 'checked="checked"';
                          } ?>></td>
                        <?php } ?>
                        <!-- SAGAR WALZADE : Code end here -->
                        
                    </tr>
                    
                    <?php }
                    
                    }else{
                    
                    echo "No Recode Found..............!!!!"; 
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
            <center>    <a href="<?php echo base_url()?>admin/kyc/Approver/allocation_type/"  class="btn btn-info"  >Back</a>
              
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
              
              <?php /*?>  <?php
                
                //$members = $this->master_model->getRecords("member_kyc", array('regnumber'=>$this->uri->segment(5),'recommended_by'=>$this->session->userdata('kyc_id')));
                //$this->db->where('recommended_by',$this->session->userdata('kyc_id'));
                //$this->db->or_where('approved_by',$this->session->userdata('kyc_id'));
                $where2 = ("(user_edited_date = '0000-00-00 00:00:00' OR user_edited_date > '".date('Y-m-d H:i:s')."')");
                $where3 = ("(approved_by = ".$this->session->userdata('kyc_id')." OR recommended_by = ".$this->session->userdata('kyc_id').")");
                $this->db->where($where2);
                $this->db->where($where3);
                
                ///$members = $this->master_model->getRecords("member_kyc", array('regnumber'=>$this->uri->segment(5)));
                $members = $this->master_model->getRecords("member_kyc", array('regnumber'=>$this->uri->segment(5)),'',array('kyc_id'=>'DESC'),'','1');
                if(count($members) > 0)
                {
                if($next_id!='')
                {?>
                <a href="<?php echo base_url()?>admin/kyc/Approver/approver_edited_member/<?php echo $next_id?>"  class="btn btn-info"  >Next</a>
                <?php 
                }
                else
                {?>
                <!--<a href="javascript:void(0)"  class="btn btn-info" onclick="check_next()">Next2</a>-->
                <a href="<?php echo base_url().'admin/kyc/Approver/next_allocation_type'?>"  class="btn btn-info">Next</a>
                <?php 
                }
                }
                else
                {?>
                <a href="javascript:void(0)"  class="btn btn-info" onclick="check_next()">Next</a>
                <?php 
              }?><?php */?>
              <!--<input type="submit"  class="btn btn-info"   onclick="<?php echo base_url()?>Kyc/next_recode" name="btnExit" id="btnExit" value="Next" >-->
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
<?php
  if (!empty($result) && isset($result[0]['registrationtype']) && $result[0]['registrationtype'] == 'O') {
    $actual_declaration = get_img_name($row['regnumber'], 'declaration');
    if (is_file($actual_declaration)) { ?>
    <div id="openModaldeclaration" class="modalDialog">
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
<?php /*?><div class="box-body">
  
  <table id="listitems" class="table table-bordered table-striped dataTables-example">
  <thead>
  <tr>
  <th id="">Membership No</th>
  <th id="">Candidate Name</th>
  <th id="">D.O.B</th>
  <th id="">Mobile No</th>
  <th id="">Reg Date</th>
  <th id="">Action</th>
  </tr>
  </thead>
  <tbody class="no-bd-y" id="list">
  <?php if(count($result)){
  foreach($result as $row){  
  ?>
  <tr>
  <td><?php echo $row['regnumber'];?></td>
  <td><?php echo $row['firstname']." ".$row['middlename']." ".$row['lastname'];?></td>
  <td><?php echo date('d-m-Y',strtotime($row['dateofbirth']));?></td>
  <td><?php echo $row['mobile'];?></td>
  <td><?php echo date('d-m-Y',strtotime($row['createdon']));?></td>
  <td>
  <a href="<?php echo base_url(); ?>admin/Kyc/details/<?php echo base64_encode($row['regid']); ?>/<?php echo base64_encode($row['regnumber']); ?>">View Details</a>
  </td>
  </tr>
  <?php }} ?>                  
  </tbody>
  </table>
  <div style="width:30%; float:left;">
  <?php echo $info; ?>
  </div>
  <div id="links" class="" style="float:right;"><?php echo $links; ?></div>
  <!--<div id="links" class="dataTables_paginate paging_simple_numbers">
  
  </div>-->
  
  </div>
  <!-- /.box-body -->
  </div>
  <!-- /.box -->
  </div>
<!-- /.col --><?php */?>

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
    
    /*$(".chk").on('click', function(e){
      alert('in');
      
			var status = this.checked; // "select all" checked status
			alert(status);
			$('.chk').each(function(){ //iterate all listed checkbox items
      this.checked = status; //change ".checkbox" checked status
			});
      
    })*/
  });
  
  $(function () {
    //$("#listitems").DataTable();
    /*var base_url = '<?php //echo base_url(); ?>';
      var listing_url = base_url+'admin/Report/getList';
      
      // Pagination function call
      paginate(listing_url,'','','');
    $("#base_url_val").val(listing_url);*/
  });
  
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
  // stopping event bubbling up the DOM tree..
  e.stopPropagation();
  };
  
  };
  
  })(window);
</script>-->
<?php $this->load->view('admin/kyc/includes/footer');?>