<?php $this->load->view('admin/includes/header');?>
<?php $this->load->view('admin/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Manage Users
        
      </h1>
      <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>-->
    </section>
	<form class="form-horizontal" name="usersAddForm" id="usersAddForm" action="<?php echo base_url();?>admin/MainController/addUser" method="post">
    <!-- Main content -->
    <section class="content">
      <div class="row">
       
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Add User</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
            <?php //echo validation_errors(); ?>
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
                <input type="hidden" class="form-control" id="id" name="id"  value="<?php echo set_value('id');?>" >
               
                <label for="roleid" class="col-sm-2 control-label">Role * :</label>
                	<div class="col-sm-3">
                      <select class="form-control" id="roleid" name="roleid" required >
                        <option value="">Select</option>
                        <?php if(count($ActiveRoles)){
                                foreach($ActiveRoles as $row1){ 	?>
                        <option value="<?php echo $row1->roleid;?>" <?php echo  set_select('roleid', $row1->roleid); ?>><?php echo $row1->role;?></option>
                        <?php } } ?>
                      </select>
                      <span class="error"><?php echo form_error('roleid');?></span>
                    </div>
                    
                    <label for="name" class="col-sm-2 control-label">Name * :</label>
                     <div class="col-sm-3">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name" required value="<?php echo set_value('name');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/">
                         <span class="error"><?php echo form_error('name');?></span>
                    </div>
                    
                </div>
                
                <div class="form-group">
                	<label for="name" class="col-sm-2 control-label">Email Id * :</label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control" id="emailid" name="emailid" placeholder="Email" required value="<?php echo set_value('emailid');?>" data-parsley-type="email">
                             <span class="error"><?php echo form_error('emailid');?></span>
                        </div>
                    
                    <label for="username" class="col-sm-2 control-label">Username * :</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required value="<?php echo set_value('username');?>" data-parsley-pattern="/^\S*$/">
                             <span class="error"><?php echo form_error('username');?></span>
                        </div>
                </div>
                
                <div class="form-group">
                	<label for="password" class="col-sm-2 control-label">Password * :</label>
                         <div class="col-sm-3">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="<?php echo set_value('password');?>" required data-parsley-equalt="#confirmPassword" data-parsley-pattern="/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[^\w\s]).{6,10}$/">
                             <span class="error"><?php echo form_error('password');?></span>
                             <small>( Note: Password must be 6-10 characters long and must include a number, a symbol, a small case letter and a capital case letter. )</small> 
                        </div>
                    
                    <label for="confirmPassword" class="col-sm-2 control-label">Confirm Password * :</label>
                        <div class="col-sm-3">
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password"  value="<?php echo set_value('confirmPassword');?>" required data-parsley-equalto="#password">
                             <span class="error"><?php echo form_error('confirmPassword');?></span>
                        </div>
                </div>
                
               
             </div>
             
              <div class="box-footer">
                  <div class="col-sm-2 col-xs-offset-5">
                    <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="<?php if(isset($_POST['btnSubmit']) && $_POST['btnSubmit']!=''){echo $_POST['btnSubmit'];}else{echo "Add";}?>">
                     <button type="reset" class="btn btn-default pull-right"  name="btnReset" id="btnReset">Reset</button>
                    </div>
              </div>
           </div>
        </div>
      </div>

      <!-- DOWNLAOD USERS -->
               <!--  <form class="form-control" name="searchScribeDetails" id="searchScribeDetails" action="
                  <?php echo base_url('scribe_dashboard/Scribe_list/scribe'); ?>" method="post">
                  <div class="row">
                    <div class="col-md-1">
                      <div class="form-group">
                        <input type="button" style="float: right;" name="download" id="download" value="Download" class="mb-2 btn btn-warning">
                      </div>
                    </div>
                  </div><button type="submit" name="download" id="download" value="Download" class="mb-2 btn btn-warning" href=" ">Download</button>
                </form> -->
      <!-- USER MANAGEMENT DOWNLAOD -->
     
      
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">User List</h3>
               <? // Download user data button added by Pooja Mane :8-9-2023 ?>
              <a href="<?php echo base_url('admin/MainController/Userdownload'); ?>" class="mb-2 btn btn-warning" style="float: right;">Download</a>
               <? // Download user data button added by Pooja Mane :8-9-2023 ?>
            </div>
          
            <!-- /.box-header -->
            <div class="box-body">
            	<div class="col-sm-12">
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover table-reResponsive dataTables-example"><!-- dataTables-example  -->
                        <thead class="no-bd">
                            <tr>
                            <th style="width: 10px">Sr.No.</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Email Id</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th style="">Action</th>
                            </tr>
                        </thead>
                        <tbody class="no-bd-y" id="list">
              					   <?php if(count($Users)){
              							$i = 1;
              							foreach($Users as $row){ ?>
              							<tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $row->name;?></td>
                                <td><?php echo $row->username;?></td>
                                <td><?php echo $row->password;?></td>
                                <td><?php echo $row->emailid;?></td>
                               	<td><?php echo $row->role;?></td>
                                <td>
                                    <div class="col-sm-10">
										          <?php if($row->active==1){?>
                                        	<a href="<?php echo base_url();?>admin/MainController/changeStatus/Users/active/<?php echo $row->id;?>" class="btn btn-block btn-success btn-xs">Active</a>
                                        <?php } else{ ?>
                                        	<a href="<?php echo base_url();?>admin/MainController/changeStatus/Users/active/<?php echo $row->id;?>" class="btn btn-block btn-danger btn-xs">In-Active</a>
                                        <?php } ?>
                                    </div>
                                </td>
                                <td>
                                    <a href="javascript:void(0);" onclick="editUser('<?php echo $row->id; ?>','<?php echo $row->roleid; ?>','<?php echo $row->name; ?>','<?php echo $row->username; ?>','<?php echo $row->emailid; ?>')" class=""><!--<i class="fa fa-edit"></i>-->Edit</a>
                                    
                                    <?php if($row->default == 0){?>
                                    	<a href="<?php echo base_url();?>admin/MainController/deleteRecord/administrators/deleted/<?php echo $row->id;?>" class="" onclick="return confirm('Are you sure to delete this record?');"><!--<i class="fa fa-trash"></i>--> | Delete</a>
                                    <?php } ?>
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
  $('#usersAddForm').parsley('validate');
</script>
<script>
	$(document).ready(function() 
	{
		var dtable = $('.dataTables-example').DataTable();
	   
	   //$(".DTTT_button_print")).hide();
	});
	
	function editUser(id,roleid,Name,Username,Email){
		$('#id').val(id);
		$('#roleid').val(roleid);
		$('#name').val(Name);
		$('#username').val(Username);
		$('#emailid').val(Email);
		$('#btnSubmit').val('Update');
		$('#roleid').focus();
		$('#password').removeAttr('required');
		$('#confirmPassword').removeAttr('required');
		
	}
	
</script> 
 
<?php $this->load->view('admin/includes/footer');?>