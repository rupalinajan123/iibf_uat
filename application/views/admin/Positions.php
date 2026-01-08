<?php $this->load->view('admin/includes/header');?>
<?php $this->load->view('admin/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Manage Job Titles
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Job Titles</li>
      </ol>
    </section>
	<form class="form-horizontal" name="postAddForm" id="postAddForm" action="<?php echo base_url();?>admin/MainController/addPosition" method="post">
    <!-- Main content -->
    <section class="content">
      <div class="row">
       
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Add Job Title</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
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
                <div class="form-group">
                <input type="hidden" class="form-control" id="PositionID" name="PositionID" placeholder="Name" value="<?php echo set_value('PositionID');?>" >
                <label for="DepartmentID" class="col-sm-1 control-label">Department</label>
                <div class="col-sm-3">
                      <select class="form-control" id="DepartmentID" name="DepartmentID" required >
                        <option value="">Select</option>
                        <?php if(count($ActiveDepartments)){
                                foreach($ActiveDepartments as $row){ 	?>
                        <option value="<?php echo $row->DepartmentID;?>" <?php echo  set_select('DepartmentID', $row->DepartmentID); ?>><?php echo $row->Department;?></option>
                        <?php } } ?>
                      </select>
                      <span class="error"><?php echo form_error('DepartmentID');?></span>
                    </div>
                    
                    <label for="Position" class="col-sm-1 control-label">Title</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" id="Position" name="Position" placeholder="Name" required value="<?php echo set_value('Position');?>">
                         <span class="error"><?php echo form_error('Position');?></span>
                    </div>
                    
                    <div class="col-sm-2">
                   <!-- <button type="submit" class="btn btn-info" name="btnAdd" id="btnAdd">Add</button>-->
                   <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="<?php if(isset($_POST['btnSubmit']) && $_POST['btnSubmit']!=''){echo $_POST['btnSubmit'];}else{echo "Add";}?>">
                    <button type="reset" class="btn btn-default pull-right">Reset</button>
                	</div>
                </div>
                
               
             </div>
          </div>
         
         
        </div>
        
      </div>
     
      
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Job Title List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            	<div class="col-sm-12">
                    <table class="table table-striped table-bordered table-hover dataTables-example"><!-- dataTables-example  -->
                        <thead class="no-bd">
                            <tr>
                            <th style="width: 10px">Sr.No.</th>
                            <th>Title</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th style="">Action</th>
                            </tr>
                        </thead>
                        <tbody class="no-bd-y" id="list">
					<?php if(count($Positions)){
							$i = 1;
							foreach($Positions as $row){?>
							<tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $row->Position;?></td>
                               	<td><?php echo $row->Department;?></td>
                                <td>
                                    <div class="col-sm-8">
										<?php if($row->PositionActive==1){?>
                                        	<a href="<?php echo base_url();?>admin/MainController/changeStatus/Positions/PositionActive/<?php echo $row->PositionID;?>" class="btn btn-block btn-success btn-xs">Active</a>
                                        <?php } else{ ?>
                                        	<a href="<?php echo base_url();?>admin/MainController/changeStatus/Positions/PositionActive/<?php echo $row->PositionID;?>" class="btn btn-block btn-danger btn-xs">In-Active</a>
                                        <?php } ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="col-sm-6">
                                        <a href="javascript:void(0);" onclick="editPosition('<?php echo $row->PositionID; ?>','<?php echo $row->Position; ?>','<?php echo $row->DepartmentID; ?>')" class="btn btn-block btn-info btn-xs"><i class="fa fa-edit"></i></a>
                                    </div>
                                       <!-- <div class="">
                                        	<a href="<?php //echo base_url();?>admin/MainController/deleteRecord/Positions/PositionDelete/<?php ///echo $row->PositionID;?>" class="btn btn-block btn-info btn-xs " onclick="return confirm('Are you sure to delete this record?');"><i class="fa fa-trash"></i></a>
                                        </div>-->
                                </td>
							</tr>
							<?php
							$i++; 
							} 
							}else{ ?>
							<!--<tr>
								<td colspan="5" align="center">No data available</td>
							</tr>-->	
							<?php } ?>                 
                        </tbody>
                    </table>
              	</div>
            </div>
           
            <div class="box-footer clearfix">
            	<div id="links"></div>
            
              <!--<ul class="pagination pagination-sm no-margin pull-right">
                <li><a href="#">&laquo;</a></li>
                <li><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">&raquo;</a></li>
              </ul>-->
            </div>
          </div>
         </div>
      </div>
    </section>
    </form>
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
  
<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>
<script type="text/javascript">
  $('#postAddForm').parsley('validate');
</script>
<script>
	$(document).ready(function() 
	{
		var dtable = $('.dataTables-example').DataTable();
	   
	   //$(".DTTT_button_print")).hide();
	});
	
	function editPosition(PositionID,Position,Department){
		$('#DepartmentID').val(Department);
		$('#Position').val(Position);
		$('#PositionID').val(PositionID);
		$('#btnSubmit').val('Update');
		
	}
	
</script> 
 
<?php $this->load->view('admin/includes/footer');?>