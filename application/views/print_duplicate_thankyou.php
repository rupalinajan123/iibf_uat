<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Duplicate ID Card Request Acknowledgment</h1>
        <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>-->
    </section>
    <form class="form-horizontal" name="member_dupliatecard" id="member_dupliatecard" method="post" enctype="multipart/form-data" action="<?php echo base_url();?>Duplicate/card/">
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <!-- Horizontal Form -->
                    <div class="box box-info">
                        <div class="box-header with-border"> </div>
                        <!-- /.box-header -->
                        
                        <!-- form start -->
                        <div class="box-body">
                            <div class="alert alert-success alert-dismissible" style="text-align:center">
                                <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
                                Request for duplicate I-card has been placed successfully !!
                            </div>
                            <b>      <a href="<?php echo base_url()?>Duplicate/cardpdf/" style="float: right;"> Transaction details(Save as pdf)</a></b> &nbsp; &nbsp;
                        </div>
                        <center>You are only allowed maximum 2 downloads of your Membership ID Card free of cost. Hence please Save the file, Print, Laminate and keep it in safe custody. </center><br />
                        <?php
				 if(isset($error) && $error != '')
				{?>
                    <div style="color:#F00">
                        <center> <?php echo $error;?> </center>
                    </div>
                    <?php }?>

                            <div style="text-align:center">
                                <?php  $kyc_status=array();
			    // $pay_status=$this->master_model->getRecords('duplicate_icard',array('regnumber'=>$this->session->userdata('regnumber')),'pay_status,kyc_status,kyc_edit,DATE(createdon)');
                //  print_r($kyc_status[0]['kyc_status']); exit;
				$pay_status=$cnthistory=$hisaarr=array();
				$regnumber = $this->session->userdata('regnumber');
				$where1 = array('regnumber'=> $regnumber);
				$orderby1 = array("did"=>"Desc");
				$pay_status= $this->master_model->getRecords('duplicate_icard',$where1,'pay_status,did',$orderby1);
				//print_r($pay_status);exit;
					//to get the count of download 
				$hisaarr=array('member_number'=> $regnumber);
				$cnthistory = $this->master_model->getRecords('member_idcard_cnt',$hisaarr);
				 ?>
        <?php if($pay_status[0]['pay_status']== 1)
				  {
						?>    <a href="<?php echo base_url();?>idcard/downloadidcard_new/1" class="btn btn-primary" target="_new" onclick="javascript:checkcount()">
				     Download Membership ID Card 
                      </a><br /> <br />

        <?php }
				?>
						</div>
                    </div> <!-- Basic Details box closed-->
                </div>
            </div>
        </section>
        </form>
</div>  <!-- content-wrapper-->
<!-- Data Tables -->

<script>
function checkcount()
	{
		$.ajax({
					url:site_url+'Duplicate/getCount/',
					type:'POST',
					async: false,
					success: function(data) {
					 if(data)
					{
						if(data>=2)
						{
							 $("a").removeAttr("target");
						}
					}
				}
			});
	}
</script>