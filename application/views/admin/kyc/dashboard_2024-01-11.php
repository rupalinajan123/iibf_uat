<?php $this->load->view('admin/kyc/includes/header');?>
<?php $this->load->view('admin/kyc/includes/sidebar');?>
<div class="wrapper">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1> Home </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <!-- Info boxes -->
      <div class="row mar30">
        <div class="col-md-12">
          <div class="box box-info box-solid disabled">
            <div class="box-header with-border">
              <h3 class="box-title">Welcome</h3>
              <div class="box-tools pull-right">
                <button data-widget="collapse" class="btn btn-box-tool" type="button"><i class="fa fa-minus"></i> </button>
              </div>
              <!-- /.box-tools --> 
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="display: block;">
              
              <div class="table-responsive">
                <table class="table table-bordered">
                  <tr>
                    <td>Members</td>
                    <td>
                      <?php /*?>  <a href="<?php echo base_url();?>admin/kyc/Kyc/allocation_type" class="" >New member list</a> &nbsp;&nbsp;&nbsp;&nbsp;<?php */?>
                      <?php
                        $new_allocated_member_list= $this->master_model->getRecords("admin_kyc_users ",array('DATE(date)'=>date('Y-m-d'),'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'New','allotted_member_id'=>'' ));
                        
                        if(count($new_allocated_member_list) >0 )
                        {
                          if($new_allocated_member_list[0]['allotted_member_id']=='')
                          {?>
                          >><a href="<?php echo base_url();?>admin/kyc/Kyc/next_allocation_type">New member list</a>&nbsp;&nbsp;&nbsp;&nbsp;
                          <?php 
                          }
                          else
                          {?>
                          >><a href="<?php echo base_url();?>admin/kyc/Kyc/allocation_type">New member list</a>&nbsp;&nbsp;&nbsp;&nbsp;
                          <?php 
                          }
                        }
                        else
                        {?>
                        >><a href="<?php echo base_url();?>admin/kyc/Kyc/allocation_type">New member list</a>&nbsp;&nbsp;&nbsp;&nbsp;
                        <?php 
                        }
                      ?>
                      <?php /*?>    >> <a href="<?php echo base_url();?>admin/kyc/Kyc/edited_allocation_type" class="" >Edited member list</a> &nbsp;&nbsp;&nbsp;&nbsp;<?php */?>
                      
                      <?php 
                        $edit_allocated_member_list= $this->master_model->getRecords("admin_kyc_users ",array('DATE(date)'=>date('Y-m-d'),'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'Edit','allotted_member_id'=>'' ));				
                        if(count($edit_allocated_member_list) > 0)
                        {
                          if($edit_allocated_member_list[0]['allotted_member_id']=='')
                          {?>
                          >><a href="<?php echo base_url();?>admin/kyc/Kyc/next_edited_allocation_type"> Edited member list</a>&nbsp;&nbsp;&nbsp;&nbsp;
                          <?php
                          }
                          else
                          {?>
                          >><a href="<?php echo base_url();?>admin/kyc/Kyc/edited_allocation_type">Edited member list</a>&nbsp;&nbsp;&nbsp;&nbsp;	
                          <?php 
                          }  
                        }else
                        {?>
                        >><a href="<?php echo base_url();?>admin/kyc/Kyc/edited_allocation_type"> Edited member list</a>&nbsp;&nbsp;&nbsp;&nbsp;
                        <?php 
                        }?>
                        >> <a href="<?php echo base_url();?>admin/kyc/Kyc/recommended_list" class="" >Recommended member list</a> &nbsp;&nbsp;&nbsp;&nbsp;
                        >> <a href="<?php echo base_url();?>admin/kyc/login/Logout" class="" >Logout</a> &nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                  </tr>
                  
                </table>
              </div>
              
            </div>
            <!-- /.box-body --> 
          </div>
          <!-- /.box --> 
        </div>
        
      </div>
      
      
    </section>
    <!-- /.content --> 
  </div>
  <!-- /.content-wrapper -->
  
<?php $this->load->view('admin/includes/footer');?>