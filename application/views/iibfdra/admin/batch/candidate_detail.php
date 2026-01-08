<?php $this->load->view('iibfdra/admin/includes/header');?>
<?php $this->load->view('iibfdra/admin/includes/sidebar');?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Batch Candidate's Details     
      </h1>
      <?php echo $breadcrumb; ?>
    </section>
	<div class="col-md-12">
    <br />        
    </div>
    <!-- Main content -->
    <section class="content">
      <div class="row">      
      <div class="col-md-12">
          <div class="box box-info box-solid disabled">
            <div class="box-header with-border">
              <h3 class="box-title">Candidate Basic Detail's</h3>
              <div class="box-tools pull-right">
                <button data-widget="collapse" class="btn btn-box-tool" type="button"><i class="fa fa-minus"></i> </button>
              </div>
              <!-- /.box-tools --> 
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="display: block;">
             
			   
              <div class="table-responsive "> 
			  
                  <table class="table table-bordered">
                    <tbody>
                    <tr>
                      <td width="50%"><strong>Registration Number :</strong></td>
                      <td width="50%"><?php echo $result['regnumber']; ?></td>
                    </tr> 
                      <tr>                    
                      <td width="50%"><strong> Candidate Name (CODE)</strong></td>
                      <td width="50%"><?php echo $result['namesub'].' '. $result['firstname'].' '. $result['middlename'].' '. $result['lastname']; 
					 if($result['stdcode'] != ''){
						 echo '( '.$result['stdcode'].' )';
						 } 					  	
					  ?></td>
                    </tr> 
                     <tr>                    
                      <td width="50%"><strong>Phone / Mobile:</strong></td>
                      <td width="50%"><?php if( $result['phone'] !=''){ echo $result['phone'];}else{ echo '--';} ?> / <?php if( $result['mobile'] !=''){ echo $result['mobile'];}else{ echo '--';} ?></td>
                    </tr>          
                    <tr>
                      <td width="50%"><strong> Qualification :</strong></td>
                      <td width="50%"> <?php if( $result['qualification'] !=''){ echo $result['qualification'];}else{ echo '--';} ?></td>
                    </tr>
                    <tr>
                      <td width="50%"><strong>Agency Code :</strong></td>
                      <td width="50%"><?php if( $result['inst_code'] !=''){ echo $result['inst_code'];}else{ echo '--';} ?> 
					 </td>
                    </tr>
                    <tr>
                      <td width="50%"><strong>Image :</strong></td>
                      <td width="50%"> 
                      <?php if( $result['scannedphoto'] !=''){ 
					   $img_path = base_url().'uploads/iibfdra/'.$result['scannedphoto'];
					  //http://iibf.teamgrowth.net/uploads/iibfdra/pr_897089821.jpg
					  if(@getimagesize($img_path)){
							  //base_url().'uploads/iibfdra/photo/
						  	echo '<img width="130px" src="'.base_url().'uploads/iibfdra/'.$result['scannedphoto'].'">';
						  }else{ 
						 	 echo 'Image Missing'; 
						  }
					  
					  }
					  
					  else{ echo '--';} ?>
                     </td>
                    </tr>
                     <tr>
                     <tr>
                      <td width="50%"><strong>Scanned Signature Image :</strong></td>
                      <td width="50%">
					    <?php if( $result['scannedsignaturephoto'] !=''){ 
						//http://iibf.teamgrowth.net/uploads/iibfdra/s_897089821.jpg
						$signature_path = base_url().'uploads/iibfdra/'.$result['scannedsignaturephoto'];
					  if(@getimagesize($signature_path)){
						  //base_url().'uploads/iibfdra/signature/
						echo '<img width="110px" src="'.base_url().'uploads/iibfdra/'.$result['scannedsignaturephoto'].'">';
						}else{ echo 'Image Missing'; }
						
					  } else{ echo '--';} ?>
					  </td>
                    </tr>
                     <tr>
                      <td width="50%"><strong>Training Medium:</strong></td>
                      <td width="50%">
					 <?php if( $result['medium_description'] != '' )	{?>					  
					  <?php echo $result['medium_description']; ?>
                      <?php }else{ echo '--';} ?>
                      </td>
                    </tr>
                     <tr>
                      <td width="50%"><strong>Address :</strong></td>
                      <td width="50%"><?php echo $result['contactdetails']; ?> <?php echo $result['address1']; ?> <?php echo $result['address2']; ?> <?php echo $result['address3']; ?> <?php echo $result['address4']; ?> <?php echo $result['district']; ?> <?php echo $result['city']; ?> <?php if(isset($result['state_name']))  { echo $result['state_name']; }?> <?php echo $result['pincode']; ?></td>
                    </tr>
                    <tr>
                      <td width="50%"><strong>Date of Birth :</strong></td>
                      <td width="50%"><?php if( $result['dateofbirth'] !=''){ echo date_format(date_create($result['dateofbirth']),"d-M-Y"); }else{ echo '--';} ?> 
					  </td>
                    </tr>
                     <tr>
                      <td width="50%"><strong>Aadhar number :</strong></td>
                      <td width="50%"><?php if( $result['aadhar_no'] !=''){ echo $result['aadhar_no'];}else{ echo '--';} ?></td>
                    </tr>
                    
                  </tbody></table>
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
<script type="text/javascript">
  //$('#searchDate').parsley('validate');
</script>
<?php 
function url_exists($url) {
	$file_headers = @get_headers($url);
	if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
		$exists = false;
	}
	else {
		$exists = true;
	}
	
   /* if (!$fp = curl_init($url)){
		return false;
	}else{
    	return true;
	}*/
}
?>

<style>
.err{
 border:1px solid #F00;	
}
.rejection{
 display:none;	
}
#center_validity{
 width:230px;	
}
#center_validity_to_date{
 width:230px;	
}
</style>

<script src="<?php echo base_url()?>js/js-paginate.js"></script>
<?php $this->load->view('iibfdra/admin/includes/footer');?>