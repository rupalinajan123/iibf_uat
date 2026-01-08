<?php $this->load->view('admin/includes/header');?>
<?php $this->load->view('admin/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Manage Department
        
      </h1>
      <!--<ol class="breadcrumb">
        <li><a href="<?php echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Department</li>
      </ol>-->
    </section>
	<form class="form-horizontal" name="deptAddForm" id="deptAddForm" action="<?php echo base_url();?>admin/MainController/addDepartment" method="post">
    <!-- Main content -->
    <section class="content">
      <div class="row">
       
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Add</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
           <!-- <form class="form-horizontal" name="deptAddForm" id="deptAddForm" action="" method="post">-->
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
                
                  <input type="hidden" name='DepartmentID' id='DepartmentID' value="<?php echo set_value('DepartmentID'); ?>" />
                  
                  <label for="inputEmail3" class="col-sm-1 control-label">Name</label>

                  <div class="col-sm-3">
                    <input type="text" class="form-control" id="Department" name="Department" placeholder="Name" value="<?php echo set_value('Department'); ?>"  required>
                  </div>
                  
                  <label for="inputPassword3" class="col-sm-1 control-label">Code</label>

                  <div class="col-sm-3">
                    <input type="text" class="form-control" id="DepartmentCode" name="DepartmentCode" value="<?php echo set_value('DepartmentCode'); ?>" placeholder="Code" >
                  </div>
                  
                  <div class="col-sm-2">
                    <!--<button type="submit" class="btn btn-info" name="btnAdd" id="btnAdd">Add</button>-->
                    <input type="submit" class="btn btn-info" name="btnAdd" id="btnAdd" value="Add">
                    <button type="reset" class="btn btn-default pull-right">Reset</button>
                  </div>
                </div>
              <!--<div class="box-footer">
                <button type="submit" class="btn btn-default">Cancel</button>
                <button type="submit" class="btn btn-info pull-right">Sign in</button>
              </div>-->
             </div>
            <!--</form>-->
          </div>
         
         
        </div>
        
      </div>
     
      
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            	<div class="col-sm-12">
                    <table class="table table-striped table-bordered table-hover dataTables-example"><!-- dataTables-example  -->
                        <thead class="no-bd">
                            <tr>
                            <th style="width: 10px">Sr.No.</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Status</th>
                            <th style="">Action</th>
                            </tr>
                        </thead>
                        <tbody class="no-bd-y" id="list">
					<?php if(count($Departments)){
							$i = 1;
							foreach($Departments as $row){?>
							<tr>
                                <td><?php echo $i; ?></td>
                                <td>
                                	<a href="javascript:void(0);" id="Department_lbl_<?php echo $i; ?>" onclick="showEditable('Department','<?php echo $row->DepartmentID;?>',<?php echo $i; ?>);"><?php echo $row->Department;?></a>
                                    <input type="text" name="Department1" id="Department_<?php echo $i; ?>" value="<?php echo $row->Department;?>" onblur="return editfunction('Department','<?php echo $row->DepartmentID;?>',<?php echo $i; ?>);" style="display:none;" class="col-md-12"/>
                                </td>
                                <td>
                                	<a href="javascript:void(0);" id="DepartmentCode_lbl_<?php echo $i; ?>" onclick="return showEditable('DepartmentCode','<?php echo $row->DepartmentID;?>',<?php echo $i; ?>);"><?php echo $row->DepartmentCode;?></a>
                                    <input type="text" name="DepartmentCode1" id="DepartmentCode_<?php echo $i; ?>" value="<?php echo $row->DepartmentCode;?>" onblur="return editfunction('DepartmentCode','<?php echo $row->DepartmentID;?>',<?php echo $i; ?>);" style="display:none;"/>
                                </td>
                                <td>
                                    <div class="col-sm-8">
										<?php if($row->DepartmentActive==1){ $status = 'Active';$cls="btn-success";}else{$status = 'In-Active';$cls="btn-danger";}?>
                                        	<a href="<?php echo base_url();?>admin/MainController/changeStatus/Departments/DepartmentActive/<?php echo $row->DepartmentID;?>" class="btn btn-block btn-xs <?php echo $cls;?>"><?php echo $status; ?></a>
                                        
                                    </div>
                                </td>
                                <td>
                                    <div class="col-sm-12">
                                        <div class="col-sm-6">
                                        	<a href="javascript:void(0);" onclick="editDepartment('<?php echo $row->DepartmentID; ?>','<?php echo $row->Department; ?>','<?php echo $row->DepartmentCode; ?>')" class="btn btn-block btn-info btn-xs"><i class="fa fa-edit"></i></a>
                                        </div>
                                       <!-- <div class="col-sm-4">
                                        	<a href="<?php //echo base_url();?>admin/MainController/deleteRecord/Departments/DepartmentDelete/<?php //echo $row->DepartmentID;?>" class="btn btn-block btn-info btn-xs" onclick="return confirm('Are you sure to delete this record?');"><i class="fa fa-trash"></i></a>
                                        </div>-->
                                    </div>
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
  $('#deptAddForm').parsley('validate');
</script>
<script>
	$(document).ready(function() 
	{
		/*var dtable = $('.dataTables-example').DataTable({
			responsive: true,
			autoWidth: false,
			"dom": 'T<"clear">lfrtip',
	   });*/
	   
	   var dtable = $('.dataTables-example').DataTable();
	   
	   //$(".DTTT_button_print")).hide();
	});
	
	function showEditable(field,DepartmentID,index)
	{
		var prev_value = $("#"+field+"_lbl_"+index).text();
		var value = $("#"+field+"_"+index).val();
		$("#"+field+"_lbl_"+index).hide();
		$("#"+field+"_"+index).show();
	}
	
	function editfunction(field,DepartmentID,index)
	{
		var base_url = '<?php echo base_url();?>';
		var prev_value = $("#"+field+"_lbl_"+index).text();
		var value = $("#"+field+"_"+index).val();
		var flag = 0;
		
		if(field!='' && DepartmentID!='' && value != '')
		{
			if(prev_value != value)
			{
				$.ajax({
					url: base_url+'admin/AjaxController/editDepartment',
					type: 'POST',
					dataType:"json",
					data: {'DepartmentID' : DepartmentID , 'field' : field, 'value' : value},
					success: function(res) {
						//alert(res);
						if(res=="1")
						{
							$("#"+field+"_lbl_"+index).html(value);
							$("#"+field+"_lbl_"+index).show();
							$("#"+field+"_"+index).hide();
						}
						else
						{
							var flag = 1;
							/*$("#"+field+"_lbl_"+index).show();
							$("#"+field+"_"+index).val(prev_value);
							$("#"+field+"_"+index).hide();*/
						}
					}
				});
			}
			else
				var flag = 1;
		}
		else
			var flag = 1;
		if(flag==1)
		{
			$("#"+field+"_lbl_"+index).show();
			$("#"+field+"_"+index).val(prev_value);
			$("#"+field+"_"+index).hide();
		}
	}
	
	function editDepartment(DepartmentID,Department,DepartmentCode){
		$('#Department').val(Department);
		$('#DepartmentCode').val(DepartmentCode);
		$('#DepartmentID').val(DepartmentID);
		$('#btnAdd').val('Update');
		
	}
	
</script> 
 
<?php $this->load->view('admin/includes/footer');?>