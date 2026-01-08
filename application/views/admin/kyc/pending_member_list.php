<?php $this->load->view('admin/kyc/includes/header');?>
<?php $this->load->view('admin/kyc/includes/sidebar');?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Pending member list
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
    <?php ?>
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Pending member list</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table id="listitems" class="table table-bordered table-striped dataTables-example">
              <thead>
                <tr>
                  <th id="">No</th>
                  <th id="">Membership/<br>Registration No</th>
                  <th id=""> Name</th>
                  <!-- <th id=""> D.O.B</th> -->
                  <th id="">Registration <br>type</th>
                  <th id="">Kyc status</th>
                  <th id="">Member type</th>
                </tr>
              </thead>
              <tbody class="no-bd-y" id="list">
                <?php
                  // $result = ($str_regnumber !='' ? explode(',', $str_regnumber) : array());
                  $result = $str_regnumber;
                  // print_r($result);die;
                  if(count($result ))
                  {	
                    $row_count = 1;
                    foreach($result as $row1)
                    { 
                      
                      $date=date('Y-m-d');
                      $fields=$r_list =$sql=array();
                      $r_list =  $this->master_model->getRecords("member_registration",array('regnumber'=>$row1,'isactive'=>'1'));
                            // print_r($r_list);exit;

                      foreach($r_list as $row)
                      { 
                        
                        $var=array();			
                        
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
                        <!-- <td> -->
                        <?php 
                          // echo $var[0]['dateofbirth'];exit;
                          //  if($row['dateofbirth']=='00-00-0000' || $row['dateofbirth']=='0000-00-00')
                          // {	
                          
                          // 		if(isset($var[0]['dateofbirth']))
                          // 		{
                          
                          // 					if($var[0]['dateofbirth']!='0000-00-00')
                          // 					{
                          // 						echo date('d-m-Y',strtotime($var[0]['dateofbirth']));
                          // 					}
                          // 					else
                          // 					{
                          // 						echo '00-00-0000';
                          // 					}
                          // 				//echo $var[0]['dateofbirth'];	
                          // 		}else
                          // 		{
                          // 			echo '00-00-0000';
                          // 		}
                          
                          // }else
                          // {
                          // 	echo date('d-m-Y',strtotime($row['dateofbirth']));
                          // 	//echo $row['dateofbirth'];
                        // }?>
                        <!-- </td> -->
                        <!-- <td> -->
                        <?php 
                          // if($row['associatedinstitute']=='' )
                          // {		
                          // if(isset($var[0]['associatedinstitute']) &&  $var[0]['associatedinstitute']!='' )
                          // { 
                          // 	$employer= $this->master_model->getRecords("institution_master", array('institude_id'=>$var[0]['associatedinstitute']),'institude_id,name');
                          // 	if(count($employer))
                          // 	{
                          // 		echo $employer[0]['name'];
                          // 	}
                          // }
                          // }else 
                          // {
                          // if($row['associatedinstitute']!='')
                          // { 
                          // 	$employer= $this->master_model->getRecords("institution_master", array('institude_id'=>$row['associatedinstitute']),'institude_id,name');
                          // 	if(count($employer))
                          // 	{
                          // 		echo $employer[0]['name'];
                          // 	}
                          // }
                          // }
                        ?>
                        <!-- </td> -->
                        <td><?php echo $row['registrationtype'];?></td>
                        <td><?php if($row['kyc_status']==1)
                          {
                            echo 'Complete';
                          }else
                          {
                            echo 'Incomplete';
                          }
                        ?>
                        </td>
                        <td><?php if($row['kyc_edit']==1)
                          {
                            echo 'Edit';
                          }else
                          {
                            echo 'New';
                          }
                        ?>
                        </td>
                        
                        <?php /*?>
                          <td>
                          <a href="<?php echo base_url(); ?>admin/kyc/Kyc/details/<?php echo $row['regnumber']; ?>">View </a>
                          </td>
                        <?php */?>
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
    
  });
  
  $(function () {
   	$("#listitems").DataTable();
  });
  
  
  
</script>
<?php $this->load->view('admin/kyc/includes/footer');?>