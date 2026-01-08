<?php $this->load->view('admin/kyc/includes/header');?>
<?php $this->load->view('admin/kyc/includes/sidebar');?>
<!--fancybox--

	<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="<?php echo base_url();?>source/jquery.fancybox.pack.js?v=2.1.5"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>source/jquery.fancybox.css?v=2.1.5" media="screen" />

	
	

	<script type="text/javascript">
		$(document).ready(function() {
			/*
			 *  Simple image gallery. Uses default settings
			 */

			$('.fancybox').fancybox();

			/*
			 *  Different effects
			 */

			// Change title type, overlay closing speed
			$(".fancybox-effects-a").fancybox({
				helpers: {
					title : {
						type : 'outside'
					},
					overlay : {
						speedOut : 0
					}
				}
			});

	

			$("#fancybox-manual-a").click(function() {
				$.fancybox.open('1_b.jpg');
			});

			


		});
	</script>
	<style type="text/css">
		.fancybox-custom .fancybox-skin {
			box-shadow: 0 0 50px #222;
		}

		body {
			/*max-width: 700px;*/
			margin: 0 auto;
		}
	</style>
 <!--end of fancybox-->   


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
    </div>
    <!-- Main content -->
    <section class="content">
  
      
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Member selected</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
 <form class="form-horizontal" name="checkmember" id="checkmember" action="<?php echo base_url();?>/admin/kyc/Kyc/checkmember/<?php echo $reg_no?>" method="post">
			<table id="listitems" class="table table-bordered table-striped dataTables-example">
                <thead>
                <tr>
                  <th id="">Membership No</th>
                  <th id="">Candidate Name</th>
                  <th id="">D.O.B</th>
                  <th id="">Employer</th>
                  <th id="">photo</th>
                  <th id="">Sign</th>
                  <th id="">Id</th>
                </tr>
                </thead>
                 <tbody class="no-bd-y" id="list">
                
                 <?php 
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
                        <td><?php echo $row['namesub']." ".$row['firstname']." ".$row['middlename']." ".$row['lastname'];?></td>
                        <td><?php echo date('d-m-Y',strtotime($row['dateofbirth']));?></td>
                        <td><?php if(count($employer) > 0)
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
                           	<p>
		<a class="fancybox-effects-a" href="<?php echo base_url();?><?php echo $actual_idproof;?><?php echo '?'.time(); ?>" title=""><img src="<?php echo base_url();?><?php echo $actual_idproof;?><?php echo '?'.time(); ?>" name="scannedphoto" id="scannedphoto" width="100" height="100" /></a>
	</p>
                       
                          <?php }else{ ?>
                          
                          	<a class="fancybox-effects-a" href="<?php echo base_url();?>assets/images/default1.png" title=""><img src="<?php echo base_url();?>assets/images/default1.png" height="100" width="100" ></a>
	</p>
                    
                          <?php } ?>
                        </td>
                         <td><!--scannedsignaturephoto -->
                             <?php $actual_idproof = get_img_name($row['regnumber'],'s');
						   if(is_file($actual_idproof)){
						   ?>
                           
                            <p>
                            <a class="fancybox-effects-a" href="<?php echo base_url();?><?php echo $actual_idproof;?><?php echo '?'.time(); ?>" title=""> <img src="<?php echo base_url();?><?php echo $actual_idproof;?><?php echo '?'.time(); ?>" name="scannedsignaturephoto" id="scannedsignaturephoto" width="100" height="100" /></a>
                            </p>

              
                          <?php }else{ ?>
                          
                            <p>
                            <a class="fancybox-effects-a" href="<?php echo base_url();?>assets/images/default1.png" title="Lorem ipsum dolor sit amet, consectetur adipiscing elit">  <img src="<?php echo base_url();?>assets/images/default1.png" height="100" width="100" ></a>
                            </p>
                    
                          <?php } ?>
                          </td>
                          <td><!--idproofphoto -->
                           <?php $actual_idproof = get_img_name($row['regnumber'],'pr');
						   if(is_file($actual_idproof)){
						   ?>
                        <p>
                        <a class="fancybox-effects-a" href="<?php echo base_url();?><?php echo $actual_idproof;?><?php echo '?'.time(); ?>" title=""><img src="<?php echo base_url();?><?php echo $actual_idproof;?><?php echo '?'.time(); ?>" name="idproofphoto" id="idproofphoto" width="100" height="100" /></a>
                        </p>
         
                          <?php }else{ ?>
                        <p>
                        <a class="fancybox-effects-a" href="<?php echo base_url();?>assets/images/default1.png" title="">  <img src="<?php echo base_url();?>assets/images/default1.png" height="100" width="100" ></a>
                        </p> 
                        
                          <?php } ?>
                          </td>
                   <?php /*?>     <td>
                        	<a href="<?php echo base_url(); ?>admin/Kyc/details/<?php echo base64_encode($row['regid']); ?>/<?php echo base64_encode($row['regnumber']); ?>">View Details</a>
                        </td><?php */?>
                    </tr>
                <?php if(count($recomended_mem_data) > 0)
				{?>
					<tr>
                 	<td> </td>
                	<td><input type="checkbox" name="cbox[]" id="cbox" value="name_checkbox" <?php if($recomended_mem_data[0]['mem_name']==1){echo 'checked="checked"';}?>></td>
                    <td><input type="checkbox" name="cbox[]" id="cbox" value="dob_checkbox" <?php if($recomended_mem_data[0]['mem_dob']==1){echo 'checked="checked"';}?>></td>
                  <?php
				 if($result[0]['registrationtype']=='NM' || $result[0]['registrationtype']=='DB' )
				{?>
					    <td>  <input type="checkbox" name="cbox[]" id="cbox" value="emp_checkbox" disabled>  </td>
				<?php 
				}else
				{
				?>
                        <td>
                        <input type="checkbox" name="cbox[]" id="cbox" value="emp_checkbox" <?php if($recomended_mem_data[0]['mem_associate_inst']==1){echo 'checked="checked"';}?>>
                        </td>
           <?php }?>
                    <td><input type="checkbox" name="cbox[]" id="cbox" value="photo_checkbox" <?php if($recomended_mem_data[0]['mem_photo']==1){echo 'checked="checked"';}?>></td>
                    <td><input type="checkbox" name="cbox[]" id="cbox" value="sign_checkbox" <?php if($recomended_mem_data[0]['mem_sign']==1){echo 'checked="checked"';}?>></td>
                    <td><input type="checkbox" name="cbox[]" id="cbox" value="idprf_checkbox" <?php if($recomended_mem_data[0]['mem_proof']==1){echo 'checked="checked"';}?>></td>
                    </tr>
				<?php 
				}
				else
				{?>
                 	<tr>
                 	<td> </td>
                	<td><input type="checkbox" name="cbox[]" id="cbox" value="name_checkbox" checked="checked"></td>
                    <td><input type="checkbox" name="cbox[]" id="cbox" value="dob_checkbox" checked="checked"></td>
                          <?php 
				if($result[0]['registrationtype']=='NM' || $result[0]['registrationtype']=='DB' )
				{?>
                    <td><input type="checkbox" name="cbox[]" id="cbox" value="emp_checkbox" disabled></td>
                   <?php }
				   else
				   {?>
					  <td><input type="checkbox" name="cbox[]" id="cbox" value="emp_checkbox" checked="checked"></td>
					<?php 
                     }?>
                	
                    <td><input type="checkbox" name="cbox[]" id="cbox" value="photo_checkbox" checked="checked"></td>
                    <td><input type="checkbox" name="cbox[]" id="cbox" value="sign_checkbox" checked="checked"></td>
                    <td><input type="checkbox" name="cbox[]" id="cbox" value="idprf_checkbox" checked="checked"></td>
                    </tr>
			<?php 
				}?>
                  <?php }
				  
				  }else{
				
					   echo "No Recode Found..............!!!!"; 
					  }
					  
					  
				   ?>                  
                </tbody>
              </table>
           <center>
           
	            <a href="<?php echo base_url()?>admin/kyc/Kyc/recommended_list/"  class="btn btn-info"  >Back</a>
             
              
              <!--<input type="submit"  class="btn btn-info"   onclick="<?php echo base_url()?>Kyc/next_recode" name="btnExit" id="btnExit" value="Next" >-->
              
              </center> 
               
               </form> 
            </table>
            </div>
                  
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
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
      
      
    </section>
   
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
		

		
</script>
 
<?php $this->load->view('admin/kyc/includes/footer');?>