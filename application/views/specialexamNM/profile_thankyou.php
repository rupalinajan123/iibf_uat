<?php $this->load->view('nonmember/front-header-nm');?>
   <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Thank You for using IIBF Online Services. Your registration number and transaction details are forwarded to your registered mail id.
      </h1>
      <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>-->
    </section>

<form class="form-horizontal" name="usersAddForm" id="usersAddForm"  method="post"  enctype="multipart/form-data" 
    action="">
    <section class="content">
      <div class="row">
       
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info" style=" border: solid 1px #000;">
            <div class="box-header with-border">
              <h3 class="box-title">  <img src="<?php echo base_url()?>assets/images/logo1.png"></h3>
              <br>
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
             <?php } 
			 if(validation_errors()!=''){?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                    <?php echo validation_errors(); ?>
                </div>
              <?php } 
			 ?> 
            
             <div class="col-sm-9">
              
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">registration Number :</label>
                	<div class="col-sm-5">
                  <?php if(isset($result[0]['regnumber'])){echo $result[0]['regnumber'];}?>
                    </div>
                </div>
                
                 <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Password : </label>
                	<div class="col-sm-5">
                 	<?php echo $password;?>
                    </div>
                </div>
                
                
                
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Name :</label>
                	<div class="col-sm-6">
                  <?php 
				 			 echo $exam_info[0]['description'];?>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Fee Amount :</label>
                	<div class="col-sm-5">
                      <?php echo $payment_info[0]['amount'];?>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Mode :</label>
                	<div class="col-sm-2">
                    <?php 
					if($exam_info[0]['exam_mode']=='ON')
					{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
					{$mode='Offline';}
					else{$mode='';}
					echo $mode?>
                    </div>
                </div>
                
                
                  <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Examination Date :</label>
       		    <div class="col-sm-2">
					<?
                        echo date('d-M-Y',strtotime($exam_info[0]['examination_date']));
                    ?>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Medium :</label>
       		    <div class="col-sm-2">
					<?php echo $medium[0]['medium_description'];?>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Center :</label>
                	<div class="col-sm-4">
                 		<?php echo $exam_info[0]['center_name'];?>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Transaction Number : </label>
                <div class="col-sm-5">
				<?php echo $payment_info[0]['transaction_no'];?>
             	</div>
                </div>
                
             
         
         <div class="form-group">
            <label for="roleid" class="col-sm-3 control-label">Transaction Status </label>
                <div class="col-sm-5">
             <?php  if($payment_info[0]['status']==0)
			 {
					echo 'Fail';
			}
			else  if($payment_info[0]['status']==1)
			{
				echo 'Success';
			}
			else if($payment_info[0]['status']==2)
			{
				echo 'Pending';
			}?>
                </div>
            </div>
        </div>
       <?php 
	   if($payment_info[0]['status']==1)
	   {
		  $this->db->join('admit_exam_master', 'admit_card_details.exm_cd = admit_exam_master.exam_code');
					$this->db->group_by('exm_cd');
					$admit_card_details = $this->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$result[0]['regnumber'],'remark'=>1),'admitcard_image,description,exm_cd',array('admitcard_id'=>'desc')); 
		   
		   ?>
       	 <div class="box-footer">
          <div class="col-sm-2 col-xs-offset-5">
          <a href="<?php echo  base_url()?>Nonreg/logout/">Logout</a>
          </div>
           <div class="col-sm-2 col-xs-offset-5">
       	   <a href="<?php echo  base_url()?>NonMember/Dashboard/">Home</a>
          </div>
          <div class="col-sm-2 col-xs-offset-5">
          <?php if($admit_card_details[0]['admitcard_image'] != ''){?>
          <a href="<?php echo base_url()?>/uploads/admitcardpdf/<?php echo $admit_card_details[0]['admitcard_image']?>" target="_blank">Download admitcard</a>
          <?php }?>
          </a>
          &nbsp; &nbsp;
          <?php
							if($admit_card_details[0]['exm_cd'] == 1002){
								$link = 'https://www.amazon.in/dp/B088FXM796';
							}elseif($admit_card_details[0]['exm_cd'] == 1003){
								$link = 'https://www.amazon.in/Micro-Small-Medium-Enterprises-India-ebook/dp/B07Z9CJTDJ/ref=sr_1_1?dchild=1&keywords=9789386394071&qid=1589462229&sr=8-1';
							}elseif($admit_card_details[0]['exm_cd'] == 1004){
								$link = 'https://www.amazon.in/dp/B088FXM796';
							}
						?>
						
						<a href="<?php echo $link;?>" target="_blank">Ebook</a>
          
           </div>
       <?php 
	   }?> 
		  </div>
        </div> 
        </div>
      </div>
    </section>
    </form>
  </div>


<?php $this->load->view('nonmember/front-footer-nm');?>