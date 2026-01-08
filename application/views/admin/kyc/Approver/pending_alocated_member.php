<?php $this->load->view('admin/kyc/includes/header');?>
<?php $this->load->view('admin/kyc/includes/approver_sidebar');?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>  Pending member list</h1>
  </section>
  <br />
  <div class="col-md-12" id="flashdata">
    <?php if($this->session->flashdata('error')!=''){?>
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <?php echo $this->session->flashdata('error'); ?> </div>
      <?php } 
      if($this->session->flashdata('success')!=''){ ?>
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
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
    </script>
  <?php } ?>
  <!-- hide flash end by pooja mane on 11-04-23 -->
  <!-- Main content -->
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Allocated pending records</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <?php
              
              if(count($result)>0)
              { ?>
              <div class="table-responsive">
                <table id="listitems" class="table table-bordered table-striped dataTables-example">
                  <thead>
                    <tr>
                      <th id="">No</th> 
                      <th id="">Membership/<br >Registration No</th>
                      <th id="">Candidate Name</th>
                      <!-- <th id="">D.O.B</th> -->
                      <!-- <th id="">Email</th> -->
                      <th id="">Registration Type</th>
                      <?php if($result[0]['registrationtype'] =='NM'){?> 
                        <th id="">Exam Code</th>
                      <?php } ?> 
                      <th id="">Registered Datetime</th>
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
                      
                      if(count($result))
                      {
                        $row_count = 1;
                        foreach($result as $rKey => $row)
                        {  
                          $exam_code = '';
                          $member_exam =$this->db->query("SELECT exam_code from member_exam WHERE regnumber = '".$row['regnumber']."' ORDER BY id DESC LIMIT 1")->result_array();
                          if($member_exam)
                          {
                            $exam_code = $member_exam[0]['exam_code'];
                          }
                        ?>
                        <tr>
                          <td><?php echo $row_count;?></td>
                          <td><?php echo $row['regnumber'];?></td>
                          <td><?php echo $row['namesub']." ".$row['firstname']." ".$row['middlename']." ".$row['lastname'];?></td>
                          <!-- <td><?php 
                            // if($row['dateofbirth']==00-00-0000)
                            // {
                            // 		echo '00-00-0000';
                            // }
                            // else
                            // {
                            // 		echo date('d-m-Y',strtotime($row['dateofbirth']));
                          // }?></td> -->
                          <!-- <td><?php //echo $row['email'];?></td> -->
                          <td><?php echo $row['registrationtype'];?></td>
                          
                          <?php if($row['registrationtype'] =='NM'){ ?>
                            <td><?php echo $exam_code;?></td> 
                          <?php }?>
                          
                          <td><?php echo $row['createdon'];?></td>
                          <?php
                            $memberNo = $row['regnumber'];
                            $updated_list_index = array_search($memberNo, $reversedArr_list);
                            $srno = $updated_list_index;
                          ?>
                          
                          <td><a href="<?php echo base_url(); ?>admin/kyc/Approver/approver_pending_member/<?php echo $row['regnumber'];?>/<?php echo $srno; ?>/<?php echo $totalRecCount;?>">Approve/Recommend</a></td>
                        </tr>
                        <?php 
                          $row_count++;		
                        } 
                      }?>                  
                  </tbody>
                </table>
              </div>
              <?php }else
              {
                ?><center> 
                <?php 
                  echo $emptylistmsg;
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
<script src="<?php echo base_url()?>js/validation.js"></script>
<script type="text/javascript">
  $('#search').parsley('validate');
</script>

<!--<script src="<?php echo base_url()?>js/js-paginate.js"></script>-->
<script type="application/javascript">
  $(document).ready(function() 
  {
    $('#from_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
      $('#to_date').datepicker('setStartDate', new Date($(this).val()));
    }); 
    
    $('#to_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
      $('#from_date').datepicker('setEndDate', new Date($(this).val()));
    });
    
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
    $('.dataTables_empty').html('');
    $("#listitems").DataTable({
      "language": {
        "infoEmpty": "No records available - Got it?",
      }
    });
    var base_url = '<?php echo base_url(); ?>';
    var listing_url = base_url+'admin/kyc/Approver/approver_pending_list/';
    
    
    // Pagination function call
    //paginate(listing_url,'','','');
    //$("#base_url_val").val(listing_url);
    
  });
  /*HIDE ALERT BOX ONCLICK OF SEARCH pooja mane:29-03-2023*/
  
  function hideAlert() {
    alert(hii);
    document.getElementById("alerts").style.display = "none";
  }
  
  
  
</script>
<?php $this->load->view('admin/kyc/includes/footer');?>
