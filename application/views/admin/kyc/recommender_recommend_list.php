<?php $this->load->view('admin/kyc/includes/header');?>
<?php $this->load->view('admin/kyc/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
    Recommended member list
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
  <?php /*?>    <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
            	<div class="col-md-12">
                    <h3 class="box-title">Select List:</h3>
                    <div class="form-group">
                      <form class="form-horizontal" name="btnSearch" id="btnSearch" action="<?php echo base_url();?>/admin/kyc/Kyc/recommended_list"  method="post">      
                     
                                  <label for="to_date" class="col-sm-2">Membership No:</label>
                            <div class="col-sm-3">
                            <input type="text" class="form-control" id="regnumber" name="regnumber" placeholder="Membership No."/>                        </div>
                            
                                    <label for="to_date" class="col-sm-2">Registration type</label>
                            <div class="col-sm-3">
                            <input type="text" class="form-control" id="registrationtype" name="registrationtype" placeholder="Registration typ"/>                        </div>
                            
                            
                        <input type="submit" class="btn btn-info" name="btnSearch" id="btnSearch" value="Search"> 
                    </form> 
                    </div>
                   
              </div>
          
            </div>
           <div class="box-body">
            
            </div>
          </div>
        </div>
      </div><?php */?>
      
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Recommended members</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
    	
			<table id="listitems" class="table table-bordered table-striped dataTables-example">
                <thead>
                <tr>
                
                  <th id="">No</th> 
                  <th id="">Membership/<br>Registration No</th> 
                  <th id=""> Name</th>
                    <th id=""> D.O.B</th>
                      <th id=""> Employer</th>
                  <th id="">Registration <br>type</th>
                  <th id="">Kyc status</th>
                    <th id="">Record Source</th>
                   <th id=""> Recommended Fields</th>
                    <th id="">Recommended date</th>
                </tr>
                </thead>
                <tbody class="no-bd-y" id="list">
                
                  <?php
				
				  if(count($result ))
				  {	
					  $row_count = 1;
						foreach($result as $row1)
						{ 
				
						$date=date('Y-m-d');
						 $fields=$r_list =$sql=array();
						$r_list =  $this->master_model->getRecords("member_registration",array('regnumber'=>$row1['regnumber'],'isactive'=>'1'));
						
						foreach($r_list as $row)
						{ 
						/*   $sql='kyc_id in (SELECT max(kyc_id) FROM member_kyc GROUP BY regnumber )';
						$this->db->where($sql);
						//$member_kyc_lastest_record = $this->master_model->getRecords("member_kyc",array('kyc_status'=>'0'),'regnumber,kyc_state,kyc_id',array('kyc_id'=>'DESC'));   
						$r_list1 = $this->master_model->getRecords("member_kyc",array('regnumber'=>$row['regnumber'],'recommended_by'=>$this->session->userdata('kyc_id'),'DATE(recommended_date)'=>$date),'kyc_id,old_data',array('kyc_id'=>'DESC'),'','1'); 
						*/  // echo $this->db->last_query();exit;
						
						$var=array();			
						//echo $r_list[0]['old_data']; 	
						$var=unserialize($row1['old_data']);
	  ?>
      
                    <tr>
               			<td><?php echo $row_count;?></td>
					  <td><?php echo $row['regnumber'];?></td>
                      
                    
                        <td><?php 
							 if( $row['firstname']=='' && $row['middlename']=='' && $row['lastname']=='' )
							{	
									if(isset($var[0]['namesub']) && (isset($var[0]['firstname']) || isset($var[0]['middlename']) || isset($var[0]['lastname'])))
									{
												
											echo $var[0]['namesub']." ".$var[0]['firstname']." ".$var[0]['middlename']." ".$var[0]['lastname'];	
									}
									
							}
							else
							{
								echo  $row['namesub']." ".$row['firstname']." ".$row['middlename']." ".$row['lastname'];
							}
						?></td>
                     
                         <td>
						 <?php 
						// echo $var[0]['dateofbirth'];exit;
						 if($row['dateofbirth']=='00-00-0000' || $row['dateofbirth']=='0000-00-00')
						{	
						
								if(isset($var[0]['dateofbirth']))
								{
									
											if($var[0]['dateofbirth']!='0000-00-00')
											{
												echo date('d-m-Y',strtotime($var[0]['dateofbirth']));
											}
											else
											{
												echo '00-00-0000';
											}
										//echo $var[0]['dateofbirth'];	
								}else
								{
									echo '00-00-0000';
								}
							
						}else
						{
							echo date('d-m-Y',strtotime($row['dateofbirth']));
							//echo $row['dateofbirth'];
						}?></td>
                        <td>
                        <?php 
						 if($row['associatedinstitute']=='' )
						{		
							if(isset($var[0]['associatedinstitute']) &&  $var[0]['associatedinstitute']!='' )
							{ 
								$employer= $this->master_model->getRecords("institution_master", array('institude_id'=>$var[0]['associatedinstitute']),'institude_id,name');
								if(count($employer))
								{
									echo $employer[0]['name'];
								}
							}
						}else 
						{
							if($row['associatedinstitute']!='')
							{ 
								$employer= $this->master_model->getRecords("institution_master", array('institude_id'=>$row['associatedinstitute']),'institude_id,name');
								if(count($employer))
								{
									echo $employer[0]['name'];
								}
							}
						}
					?>
                        </td>
  						<td><?php echo $row['registrationtype'];?></td>                 
                        <td><?php if($row['kyc_status']==1)
						            {
										echo 'Complete';
									}else
									{
										echo 'Incomplete';
									}
									?></td>
                             <td><?php echo $row1['record_source'];?></td>       
                           <td><?php 
						            if($row1['mem_name']==0)
						            {
										$fields[]='Name';
									}if($row1['mem_dob']==0)
									{
										$fields[]='DOB';
									}if($row1['mem_sign']==0)
									{
										$fields[]='signature';
									}
									if($row1['mem_proof']==0)
									{
										$fields[]='Id-proof';
									}if($row1['mem_photo']==0)
									{
										$fields[]='Photo';
									}
									if($row1['employee_proof']==0 && $row['excode']== '1009')
									{
										$fields[]='Fedai Id-proof';
									}
									if($row1['mem_bank_bc_id_card']==0 && $row['date_of_commenc_bc'] !='')
									{
										$fields[]='Bank BC ID Card';
									}
									/*
									- SAGAR WALZADE : Code start here
									- Changes : added condition of declaration is exist or not of the user.
									*/
									if($row1['mem_type'] == 'O'){
										if ($row1['mem_declaration'] == 0) {
											$fields[] = 'Declaration';
										}
									}
									/* SAGAR WALZADE : Code end here */
									if($row1['mem_associate_inst']==0)
									{
										$fields[]='Associate Institute';
									}
									if(count($fields) > 0)
									{
									 	echo implode(' , ',$fields);
									}else
									{ ?>
										
										<span style="color:green;"><?php echo 'Record found ok'; ?></span>
								
										
							<?php		}
									?>
                                    </td>   
                                      <td><?php if($row1['recommended_date']!='' && $row1['recommended_date']!='0000-00-00'){echo date('d-m-Y ',strtotime($row1['recommended_date']));}?></td>          
                        <?php /*?><td>
                        	<a href="<?php echo base_url(); ?>admin/kyc/Kyc/details/<?php echo $row['regnumber']; ?>">View </a>
                        </td><?php */?>
                    </tr>
                  <?php $row_count++; 
						}}} ?>                  
                </tbody>
            </table>
            </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
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
<script src="<?php echo base_url()?>js/validation.js?<?php echo time(); ?>"></script>
<script type="text/javascript">
  $('#search').parsley('validate');
</script>

<!--<script src="<?php echo base_url()?>js/js-paginate.js"></script>-->
<script type="application/javascript">
$(document).ready(function() 
{
/*	$('#from_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
		$('#to_date').datepicker('setStartDate', new Date($(this).val()));
	}); 
	
	$('#to_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
		$('#from_date').datepicker('setEndDate', new Date($(this).val()));
	});*/
	
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
	$("#listitems").DataTable();
	/*var base_url = '<?php // echo base_url(); ?>';
	var listing_url = base_url+'admin/kyc/Kyc/recommended_list/';
	
	// Pagination function call
	paginate(listing_url,'','','');
	$("#base_url_val").val(listing_url);*/
});
		

		
</script>
 
<?php $this->load->view('admin/kyc/includes/footer');?>