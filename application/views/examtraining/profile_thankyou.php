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
                <label for="roleid" class="col-sm-3 control-label">Registration Number :</label>
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
                	<div class="col-sm-5">
                  <?php 
				 			 echo $exam_info[0]['description'];?>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Fee Amount :</label>
                	<div class="col-sm-5">
                      <?php echo $member_exam[0]['exam_fee'];?>
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
                
               <!-- <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Transaction Number : </label>
                <div class="col-sm-5">
				<?php //echo $payment_info[0]['transaction_no'];?>
             	</div>
                </div>-->
                
             
         
         <div class="form-group">
            <label for="roleid" class="col-sm-3 control-label">Transaction Status :</label>
                <div class="col-sm-5">
             <?php  if($member_exam[0]['pay_status']==0)
			 {
					echo 'Fail';
			}
			else  if($member_exam[0]['pay_status']==1)
			{
				echo 'Success';
			}
			else if($member_exam[0]['pay_status']==2)
			{
				echo 'Pending';
			}?>
                </div>
            </div>
        </div>
        <?php 
		if($member_exam[0]['pay_status']==1)
			{?>
			<div class="box-footer">
          <div class="col-sm-2 col-xs-offset-5">
          <a href="<?php echo  base_url()?>Nonreg/logout/">Logout</a>
          </div>
		  
           <div class="col-sm-2 col-xs-offset-5">
       	   <a href="<?php echo  base_url()?>NonMember/Dashboard/">Home</a>
          </div>
		  
		    <div class="col-sm-2 col-xs-offset-5">
       	  <a href="<?php echo base_url()?>uploads/Publications _List_IT.xlsx" class='publication' data-attr=<?php echo $result[0]['regnumber'];?>  target="_blank">Publications</a>
          </div>
		  
           </div>
			<?php 
			}
		?>
        
          </div>
        </div> 
        </div>
      </div>
    </section>
    </form>
  </div>

<?php $this->load->view('nonmember/front-footer-nm');?>

<script>

function get_loader()
{
	$(".loading").show();
}


(function (global) {


	if(typeof (global) === "undefined")

	{

		throw new Error("window is undefined");

	}



    var _hash = "!";

    var noBackPlease = function () {

        global.location.href += "#";



		// making sure we have the fruit available for juice....

		// 50 milliseconds for just once do not cost much (^__^)

        global.setTimeout(function () {

            global.location.href += "!";

        }, 50);

    };

	

	// Earlier we had setInerval here....

    global.onhashchange = function () {

        if (global.location.hash !== _hash) {

            global.location.hash = _hash;

        }

    };



    global.onload = function () {

        

		noBackPlease();



		// disables backspace on page except on input fields and textarea..

		document.body.onkeydown = function (e) {

            var elm = e.target.nodeName.toLowerCase();

            if (e.which === 8 && (elm !== 'input' && elm  !== 'textarea')) {

                e.preventDefault();

            }

            // stopping event bubbling up the DOM tree..

            e.stopPropagation();

        };

		

    };



})(window);

</script>
