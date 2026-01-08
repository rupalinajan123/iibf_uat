<?php $this->load->view('iibfdra/admin/includes/header');?>
<?php $this->load->view('iibfdra/admin/includes/sidebar');?>
  <!-- Content Wrapper. Contains page content -->
  
<style>
.tooltip1 {
  position: relative;
  display: inline-block;
  border-bottom: 1px dotted black;
  text-transform: capitalize;
}

.tooltip1 .tooltiptext {
  visibility: hidden;
  width: 109px;
  background-color: black;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px 0;
  position: absolute;
  z-index: 1;
  bottom: 150%;
  left: 50%;
  margin-left: -60px;
}

.tooltip1 .tooltiptext::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: black transparent transparent transparent;
}

.tooltip1:hover .tooltiptext {
  visibility: visible;
}
</style>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Inspector Master
      </h1>
      <?php echo $breadcrumb; ?>
    </section>
  <form class="form-horizontal" name="usersAddForm" id="usersAddForm" action="" method="post">
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Inspector List</h3>
              <div class="pull-right">
                <a href="<?php echo base_url();?>iibfdra/admin/InspectorMaster/add" class="btn btn-warning">Add New Inspector</a>
                <input type="button" class="btn btn-info" onclick="refreshDiv('');" value="Refresh">
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <?php  // print_r($city);//print_r($inspector_list)?>
              <?php if($this->session->flashdata('error')!=''){?>
                  <div class="alert alert-danger alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <?php echo $this->session->flashdata('error'); ?>
                  </div>
              <?php } if($this->session->flashdata('success')!=''){ ?>
                  <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <?php echo $this->session->flashdata('success'); ?>
                  </div>
              <?php } ?> 
              <div class="table-responsive">
                <table id="isnp_list" class="table table-bordered table-striped dataTables-example">
                  <thead>
                    <tr>
                      <th id="srNo">S.No.</th>
                      <th id="inspector_name">Inspector</th>
                      <th id="inspector_mobile">Contact</th>
                      <th id="inspector_email">Email Id</th>
                      <th id="inspector_designation">Designation</th>
                      <th id="loc_name">Assigned Centers(City)</th>
                      <th width="80px" id="action"> Action </th>
                    </tr>
                  </thead>
                  <tbody class="no-bd-y" id="list">
                  <?php  $i=1; $city=array();
                  foreach($inspector_list as $res)
                  {
                      $this->db->select('agency_inspector_center.*,city_master.city_name');
                      $this->db->join('city_master','agency_inspector_center.city=city_master.id','LEFT');        
                      $city= $this->Master_model->getRecords("agency_inspector_center",array('inspector_id'=>$res['id']));

                      //echo"<pre>"; print_r($city); exit;

                  // -------------------Multiple Select Dropdown------------------
                      $this->db->join('agency_center','agency_center.center_id=agency_inspector_center.center_id','left');
                      $this->db->select('agency_inspector_center.*,city_master.city_name');
                      $this->db->join('city_master','agency_inspector_center.city=city_master.id','LEFT');        
                      
					  $location_name= $this->Master_model->getRecords("agency_inspector_center",array('inspector_id'=>$res['id']),'agency_inspector_center.city');
                     // echo"<pre>"; print_r($location_name); exit;
                    //  $location_name= $this->Master_model->getRecords("agency_inspector_center",array('inspector_id'=>$res['id']),'location_name');
					            $location_array = array();
                      //-------------Multiple Select Dropdown--------------------------

                      foreach($city as $row)
                      {
                        $location_array[]=$row['city_name'];
                      }
                      if(count($location_array) >0)
                      {
                        $location_string=implode(', ',$location_array);
                      }
                      else
                      {
						 $location_string = "-";
					  }
                  ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo strtoupper($res['inspector_name']); ?></td>
                        <td><?php echo $res['inspector_mobile']; ?></td>
                        <td><?php echo $res['inspector_email']; ?></td>
                        <td><?php echo $res['inspector_designation']; ?></td>
                       <?php /*?> <td><?php if($city[0]['city'] != 0 || $city[0]['city']!="" ){echo $city[0]['city_name'];} else{ echo"-";}?></td><?php */?>
                        <td><?php if($location_string != ''  ){echo $location_string ;} else{ echo"-";}?></td>
                        
                        <td><a href="<?php base_url();?>InspectorMaster/edit/<?php echo base64_encode($res['id']);?>">Edit</a> | <a href="javascript:void(0);" class="btn tooltip1 btn-info btn-xs vbtn active_deactive_btn insp_<?php echo $res['id'];?>" data-type="<?php echo $res['is_active']==1 ? 'active' : 'Deactive';  ?>"  data-id ="<?php echo $res['id']; ?>">
                        <?php echo $res['is_active']==1 ? 'active' : 'Deactive';  ?><span class="tooltiptext">Click to <?php echo $res['is_active']==1 ? 'Deactive' : 'active';  ?></span></a></td>
                        <?php $i++;  }  ?>
                    </tr>
                  </tbody>
                </table>
                <div id="links" class="dataTables_paginate paging_simple_numbers">            
                </div>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
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
<script src="<?php echo base_url()?>js/js-paginate.js"></script>
<script type="text/javascript">


 $('#isnp_list').on('click', '.active_deactive_btn', function() {
         data_type = $(this).data('type');
		 data_id =  $(this).data('id');
		 console.log(data_type+'<---- data_id=>d'+data_id);
		
		var ele_class = 'insp_'+data_id;
		var base_url = '<?php echo base_url(); ?>';
		var url = base_url+'iibfdra/admin/InspectorMaster/activate_deactivate';
		
		if(data_type == 'active'){
		var str = 'Deactive';	
			}else{
		var str = 'Active';			
			}
		
		 if(data_type != '' && data_id !=''){
			 
		   if (confirm('Are you sure you want to '+str+' this Inspector?')) {
			$.ajax({
				type:'POST',
				url: url,
				data:'id='+data_id+'&data_type='+data_type,
				success:function(data){
					var data = $.trim(data);
					if(data != 'OK'){
						console.log(data);
						alert("Err Occer");					
					}else{
						console.log(data_type);	
						if(data_type == 'active'){
						
						$('.'+ele_class).data('type','deactive');	
						$('.'+ele_class).html('Deactive <span class="tooltiptext">Click to Active</span>');
						}else{
						$('.'+ele_class).data('type','active');	
						$('.'+ele_class).html('Active <span class="tooltiptext">Click to Deactive</span>');
							
						}
					}				
				}
			});
		}		 
        // $el = $(this);
		}

});

</script>
<?php $this->load->view('iibfdra/admin/includes/footer');?>


