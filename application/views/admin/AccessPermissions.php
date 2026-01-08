<?php $this->load->view('admin/includes/header');?>
<?php $this->load->view('admin/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Access Permissions
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Locations</li>
      </ol>
    </section>
	<form class="form-horizontal" name="locationAddForm" id="locationAddForm" action="<?php echo base_url();?>admin/MainController/addLocation" method="post">
    <!-- Main content -->
    <section class="content">
      <div class="row">
       
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Add Location</h3>
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
                <input type="hidden" class="form-control" id="LocationID" name="LocationID"  value="<?php echo set_value('LocationID');?>" >
                <label for="StateID" class="col-sm-1 control-label">State</label>
                <div class="col-sm-3">
                      <select class="form-control" id="RoleID" name="RoleID" required >
                        <option value="">Select</option>
                        <?php if(count($ActiveRoles)){
                                foreach($ActiveRoles as $row){ 	?>
                        <option value="<?php echo $row->RoleID;?>" <?php echo  set_select('RoleID', $row->RoleID); ?>><?php echo $row->Role;?></option>
                        <?php } } ?>
                      </select>
                      <span class="error"><?php echo form_error('StateID');?></span>
                    </div>
                    
                    <label for="Position" class="col-sm-1 control-label">Location</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" id="Location" name="Location" placeholder="Name" required value="<?php echo set_value('Location');?>">
                         <span class="error"><?php echo form_error('Location');?></span>
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
              <h3 class="box-title">Location List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            	<div class="col-sm-12">
                    <table class="table table-striped table-bordered table-hover dataTables-example"><!-- dataTables-example  -->
                        <thead class="no-bd">
                            <tr>
                            <th style="width: 10px">Sr.No.</th>
                            <th>Location</th>
                            <th>State</th>
                            <th>Status</th>
                            <th style="">Action</th>
                            </tr>
                        </thead>
                        <tbody class="no-bd-y" id="list">
					<?php if(count($Locations)){
							$i = 1;
							foreach($Locations as $row){?>
							<tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $row->Location;?></td>
                               	<td><?php echo $row->State;?></td>
                                <td>
                                    <div class="col-sm-8">
										<?php if($row->LocationActive==1){?>
                                        	<a href="<?php echo base_url();?>admin/MainController/changeStatus/Locations/LocationActive/<?php echo $row->LocationID;?>" class="btn btn-block btn-success btn-xs">Active</a>
                                        <?php } else{ ?>
                                        	<a href="<?php echo base_url();?>admin/MainController/changeStatus/Locations/LocationActive/<?php echo $row->LocationID;?>" class="btn btn-block btn-danger btn-xs">In-Active</a>
                                        <?php } ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="col-sm-6">
                                        <a href="javascript:void(0);" onclick="editLocation('<?php echo $row->LocationID; ?>','<?php echo $row->Location; ?>','<?php echo $row->StateID; ?>')" class="btn btn-block btn-info btn-xs"><i class="fa fa-edit"></i></a>
                                    </div>
                                       <!-- <div class="">
                                        	<a href="<?php //echo base_url();?>admin/MainController/deleteRecord/Positions/PositionDelete/<?php ///echo $row->LocationID;?>" class="btn btn-block btn-info btn-xs " onclick="return confirm('Are you sure to delete this record?');"><i class="fa fa-trash"></i></a>
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
	
	function editLocation(LocationID,Location,State){
		$('#StateID').val(State);
		$('#Location').val(Location);
		$('#LocationID').val(LocationID);
		$('#btnSubmit').val('Update');
		$('#StateID').focus();
		
	}
	
	function showEditable(field,LocationID,index)
	{
		var prev_value = $("#"+field+"_lbl_"+index).text();
		var value = $("#"+field+"_"+index).val();
		$("#"+field+"_lbl_"+index).hide();
		$("#"+field+"_"+index).show();
	}
	
	function editfunction(field,LocationID,index)
	{
		//StateID_1  StateID_lbl_hidd_1
		
		var base_url = '<?php echo base_url();?>';
		var prev_value = $("#"+field+"_lbl_hidd_"+index).text();
		var value = $("#"+field+"_"+index).val();
		var textval = $("#"+field+"_val_hidd_"+index).val();
		if(field!='' && LocationID!='' && value != '')
		{
			if(prev_value != value)
			{
				$.ajax({
					url: base_url+'admin/post/edit',
					type: 'POST',
					dataType:"text",
					data: {'LocationID' : LocationID , 'field' : field, 'value' : value},
					success: function(res) {
						//alert(res);
						if(res!="0")
						{
							$("#list").html(res);
						}
						else
						{
							$("#"+field+"_lbl_"+index).show();
							$("#"+field+"_"+index).val(prev_value);
							$("#"+field+"_"+index).hide();
						}
						/*if(res=="1")
						{
							$("#"+field+"_lbl_hidd_"+index).val(value);
							$("#"+field+"_lbl_"+index).html(textval);
							$("#"+field+"_lbl_"+index).show();
							$("#"+field+"_"+index).hide();
						}
						else
						{
							$("#"+field+"_lbl_"+index).show();
							$("#"+field+"_"+index).val(prev_value);
							$("#"+field+"_"+index).hide();
						}*/
					}
				});
			}
			else
			{
				$("#"+field+"_lbl_"+index).show();
				$("#"+field+"_"+index).val(prev_value);
				$("#"+field+"_"+index).hide();
			}
		}
	}
	
</script> 
 
<?php $this->load->view('admin/includes/footer');?>