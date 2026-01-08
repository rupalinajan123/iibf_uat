<?php $this->load->view('admin/kyc/includes/header');?>
<?php $this->load->view('admin/kyc/includes/approver_sidebar');?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>  KYC member list </h1>
  </section>
  <br />
  <div class="col-md-12" id="flashdata">
    <?php if($this->session->flashdata('error')!=''){?>
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>--> 
      <?php echo $this->session->flashdata('error'); ?> </div>
      <?php } if($this->session->flashdata('success')!=''){ ?>
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>--> 
      <?php echo $this->session->flashdata('success'); ?> </div>
    <?php } ?>
  </div>
  <!-- hide flash added by pooja mane on 11-04-23 -->
  <?php 
    if($reset != ''){ ?>
    <script> 
      setTimeout(function() {
        $('#flashdata').hide('fast');
      }, 100);
      $(document).ready(function() {
        $('#SearchVal').val(''); //empty the serach value
      });
    </script>
  <?php } ?>
  <!-- hide flash end by pooja mane on 11-04-23 -->
  
  <!-- Main content -->
  
  <!-- search functionality by pooja mane 04-04-2023 -->
  <div class="collapsee" id="collapseExample">
    
    <form class="form-control" name="searchExamDetails" id="searchExamDetails" action="
    <?php echo base_url('admin/kyc/Approver/allocation_type'); ?>" method="post">
      <div class="row">
        <div class="col-sm-3">
          <div class="form-group">
            <label>Search By</label>
            <select class="custom_filter form-control" name="searchBy" id="searchBy" required="">
              <option value="">Select</option>
              <option value="01" selected="">Registration No</option>
              <!-- <option value="02">Created Date</option>
              <option value="03">Email</option> -->
              <!-- <option value="04">ALL</option> -->
            </select>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label>Search Value</label>
            <input type="text" class="form-control" id="SearchVal" name="SearchVal" placeholder="" value=" <?php if(set_value('SearchVal')) { echo set_value('SearchVal'); }?>">
          </div>
        </div>
        
        <div class="col-md-1">
          <div class="form-group">
            <label>Search</label>
            <input type="submit" class="mb-2 float-right btn btn-primary" name="btnSearch" id="btnSearch" value="Search" >
          </div>
        </div>
        
        <div class="col-md-1">
          <div class="form-group">
            <label>Reset</label>
            <input type="submit" class="mb-2 float-right btn btn-primary" name="reset" id="reset" value="Reset">
          </div>
        </div>
        
        <!--  <div class="col-md-1">
          <div class="form-group">
          <label>Reset</label>
          <input type="submit" class="mb-2 float-right btn btn-primary" name="reset" id="reset" value="Reset" onclick="location.href='<?php echo base_url('admin/kyc/Approver/allocation_type'); ?>';">
          </div>
        </div> -->
        
      </div>     
    </form>
  </div>
  <!-- search functionality end pooja mane 04-04-2023 -->
  
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Allocated  records </h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <?php /*?>           <form class="form-horizontal" name="checkmember" id="checkmember" action="<?php echo base_url();?>/admin/kyc/Approver/allocation_type/" method="post">
            <?php */?>           <?php
            if(count($result)>0)
            {?>
            
            <table id="listitems" class="table table-bordered table-striped dataTables-example">
              <thead>
                <tr>
                	
                  <th id="">No</th> 
                  <th id="">Membership/<br >Registration No</th>
                  <th id="">Candidate Name</th>
                  <th id="">D.O.B</th>
                  <th id="">Employer</th>
                  <th id="">Member Type</th>
                  <th id=""> Recommended Fields</th>
                  <?php /*?>'      <th id="">Photo</th>
                    '                        <th id="">Signature</th>
                  '                        <th id="">Id-proof</th><?php */?>
                  <th id="">Recommended Date</th>
                  <th id="">Created Datetime</th>
                  <th id="">Action</th>
                </tr>
              </thead>
              <tbody class="no-bd-y" id="list">
                <?php 
                  /* Here array key to start from 1 instead of 0 for showing counts functionality */
                  $result = array_combine(range(1, count($result)), array_values($result));
                  //$totalRecCount = count($result);
                  
                  $original_allotted_Arr = explode(',', $original_allotted_member_id);
                  $arr = array_slice($original_allotted_Arr, -$totalRecCount);
                  $Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
                  $reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
                  //echo "<pre>"; print_r($reversedArr_list); echo "</pre>";
                  
                  if(count($result)){
                    
                    $row_count = 1;
                    foreach($result as  $rKey => $row)
                    {  
                      $employer=array();
                      if($row['registrationtype']=='O' || $row['registrationtype']=='A' || $row['registrationtype']=='F')
                      {
                        $select = 'institude_id,name';
                        $employer= $this->master_model->getRecords("institution_master", array('institude_id'=>$row['associatedinstitute']),$select);
                      }
                      
                      $fields=array();
                    ?>
                    <tr>
                      <?php /*?>              <td><input type="checkbox" name="checkbox[]" id="checkbox" value="<?php echo $row['regnumber'];?>" ></td>
                      <?php */?>                       <td><?php echo $row_count; echo ' '.$row['list_type'];?></td>
                      
                      <td><?php echo $row['regnumber'];?></td>
                      <td>
                        <?php  
                          $username=$row['namesub'].' '.$row['firstname'].' '.$row['middlename'].' '.$row['lastname'];
                          echo  $userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
                        ?>
                      </td>
                      <td><?php if($row['dateofbirth']!='0000-00-00' && $row['dateofbirth']!='' && $row['dateofbirth']!='00-00-0000'){echo date('d-m-Y',strtotime($row['dateofbirth']));}?></td>
                      <td><?php
                        if(count($employer) > 0)
                        {
                          echo $employer[0]['name'];
                        }
                        else
                        {
                          echo '';	
                        }?></td>
                        <td><?php echo $row['registrationtype'];?></td>
                        <td><?php 
                          if($row['registrationtype']=='DB' || $row['registrationtype']=='NM' )
                          {	
                            
                            if($row['mem_name']==0)
                            {
                              $fields[]='Name';
                            }if($row['mem_dob']==0)
                            {
                              $fields[]='DOB';
                            }if($row['mem_sign']==0)
                            {
                              $fields[]='signature';
                            }
                            if($row['mem_proof']==0)
                            {
                              $fields[]='Id-proof';
                            }if($row['mem_photo']==0)
                            {
                              $fields[]='Photo';
                            }
                            if(count($fields) > 0)
                            {
                              echo implode(' , ',$fields);
                            }elseif(count($fields) ==0)
                            {?>
                            <span style="color:green;"><?php echo 'Record found ok'; ?></span>
                            <?php 
                            }
                          }elseif($row['registrationtype']=='O' || $row['registrationtype']=='A'|| $row['registrationtype']=='F'  )
                          {
                            
                            if($row['mem_name']==0)
                            {
                              $fields[]='Name';
                            }if($row['mem_dob']==0)
                            {
                              $fields[]='DOB';
                            }if($row['mem_sign']==0)
                            {
                              $fields[]='signature';
                            }
                            if($row['mem_proof']==0)
                            {
                              $fields[]='Id-proof';
                            }if($row['mem_photo']==0)
                            {
                              $fields[]='Photo';
                            }if($row['mem_associate_inst']==0)
                            {
                              $fields[]='Associate Institute';
                            }
                            if(count($fields) > 0)
                            {
                              echo implode(' , ',$fields);
                            }elseif(count($fields) ==0)
                            {?>
                            <span style="color:green;"><?php echo 'Record found ok'; ?></span>
                            <?php }
                          }
                        ?>
                        </td>
                        <td><?php if($row['recommended_date']!='' && $row['recommended_date']!='0000-00-00'){echo date('d-m-Y',strtotime($row['recommended_date']));}?></td>  
                        <td><?php echo $row['createdon'];?></td>									 
                        <?php
                          $memberNo = $row['regnumber'];
                          $updated_list_index = array_search($memberNo, $reversedArr_list);
                          $srno = $updated_list_index;
                        ?>      
                        <td><a href="<?php echo base_url(); ?>admin/kyc/Approver/approver_edited_member/<?php echo $row['regnumber'];?>/<?php echo $srno; ?>/<?php echo $totalRecCount;?>">Approve/Recommend</a></td>
                    </tr>
                    <?php 
                      $row_count++;				
                    } ?>
                    
                    <?php 
                    }?>           
              </tbody>
            </table>
            
            <?php /*?>   <center> <input type="submit" class="btn btn-info" name="btnSubmitkyc" id="btnSubmitkyc" value="KYC Complete" >  </center>
            </form> <?php */?>
            <?php }else
            {
              ?>  <center> 
              <?php 
                if(isset($emptylistmsg))
                {
                  echo  $emptylistmsg;
                }
              ?>
            </center>
            <?php }?>
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
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>

<script type="application/javascript">
  $(function () {
    $("#listitems").DataTable();
  });
</script>
<?php /*?> <script type="text/javascript">
  $(document).ready(function(){
  $('#lightgallery_photo,#lightgallery_sign,#lightgallery_proof').lightGallery();
  });
</script><?php */?>
<?php $this->load->view('admin/kyc/includes/footer');?>
