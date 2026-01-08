<?php $this->load->view('admin/kyc/includes/header');?>
<?php $this->load->view('admin/kyc/includes/approver_sidebar');?>
<link href="<?php echo base_url('assets/css/popup.css')?>" rel="stylesheet">	
<?php $fedai_array= array(1009); ?>
<script src="<?php echo base_url('assets/dist/js/jquery.min.js')?>"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> KYC completed </h1>
  </section>
  <br />
  <div class="col-md-12">
    <?php if($this->session->flashdata('error')!=''){?>
    <div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>--> 
      <?php echo $this->session->flashdata('error'); ?> </div>
    <?php } if($this->session->flashdata('success')!=''){ ?>
    <div class="alert alert-success alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>--> 
      <?php echo $this->session->flashdata('success'); ?> </div>
    <?php } ?>
  </div>
  <!-- Main content -->
  <section class="content" style="min-height: 500px;">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Member selected</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
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
                    <?php if( isset($result[0]['registrationtype']) && $result[0]['registrationtype']== 'NM' && in_array($result[0]['excode'], $fedai_array)){ ?>
                    <th id="">Employment Proof</th>
                    <?php } ?>
                    <?php
                          if(isset($result[0]['date_of_commenc_bc']) && $result[0]['date_of_commenc_bc'] != ''){?>
                      <th id="">Bank BC Id Card</th>
                      <?php }?>
                    <!-- 
                    - SAGAR WALZADE : Code start here
                    - Changes : one declaration column added
                    -->
                    <?php
                    if (!empty($result) && isset($result[0]['registrationtype']) && $result[0]['registrationtype'] == 'O') {
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


				 	if(count($result)){
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
                    <td><?php echo $row['namesub']." ".$row['firstname']." ".$row['middlename']." ".$row['lastname'];?></td>
                    <td><?php
							if($row['dateofbirth']!=00-00-000)
							{
								echo date('d-m-Y',strtotime($row['dateofbirth']));
							}else
							{
								echo '00-00-0000';
							}
							
						 ?></td>
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
                   <?php /*?>     <td>
                        	<a href="<?php echo base_url(); ?>admin/Kyc/details/<?php echo base64_encode($row['regid']); ?>/<?php echo base64_encode($row['regnumber']); ?>">View Details</a>
                        </td><?php */?>


                    <!-- 
                    - SAGAR WALZADE : Code start here
                    - Changes : show new column of declaration
                    -->
                    <?php if ($row['registrationtype'] == 'O' || ($row['registrationtype'] == 'NM' && in_array($result[0]['excode'], $fedai_array))) { ?>
                        <td>
                            <?php $actual_declaration = get_img_name($row['regnumber'], 'declaration');
                            if (is_file($actual_declaration)) {
                            ?>
                                <a href="#openModaldeclaration">
                                    <img class="img-responsive" src="<?php echo base_url(); ?><?php echo $actual_declaration; ?><?php echo '?' . time(); ?>" name="idproofphoto" id="idproofphoto" height="100" width="100" />
                                </a>
                            <?php } else { ?>
                                <p>
                                    <img src="<?php echo base_url(); ?>assets/images/default1.png" height="100" width="100">
                                </p>
                            <?php } ?>
                        </td>
                    <?php } ?>
                    <!-- SAGAR WALZADE : Code end here -->

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

                    </tr>
               
                 
			
                  <?php }
				  
				  }else{
				
					   echo "No Recode Found..............!!!!"; 
					  }
			  ?>                  
                </tbody>
              </table>
         <?php /*?>       <?php
			  $arraid=array();
		
		$this->db->join('member_registration', 'member_registration.regnumber= member_kyc.regnumber', 'LEFT');
		$this->db->where('member_registration.kyc_status','1');
	    $this->db->where('member_kyc.kyc_status','1');
		$this->db->group_by('member_kyc.regnumber');
		$total_id = $this->master_model->getRecords("member_kyc",array('field_count'=>'0'),'MAX(kyc_id),member_kyc.regnumber',array('kyc_id'=>'DESC'));

			   echo 'Showing '.$this->uri->segment(6).' of '. count($total_id ). ' entries' ; ?><?php */?>
              <center>
               <a href="<?php echo base_url()?>admin/kyc/Approver/kyccomplete_newlist/"  class="btn btn-info"  >Back</a>
              </center>
       
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
                            <h2>Photo</h2>
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
                            <h2>Signature</h2>
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
                            <h2>ID-Proof</h2>
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
<script type="text/javascript">
$(document).ready(function(){
$('#lightgallery_photo,#lightgallery_sign,#lightgallery_proof').lightGallery();
});
</script>
	
<?php $this->load->view('admin/kyc/includes/footer');?>
