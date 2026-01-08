<?php $drainstdata = $this->session->userdata('dra_institute');
if( $drainstdata ) {
?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
    <!-- Sidebar user panel -->
    	<div class="user-panel">
        	<p><span style="color: #b8c7ce;"><?php echo $drainstdata['institute_name'];?></span></p>
            <p></p>
        </div>
     	<!--  sidebar menu: : style can be found in sidebar.less -->
		<ul class="sidebar-menu">
        	<li class="treeview">
                <a href="<?php echo base_url();?>iibfdra/Version_2/InstituteHome/dashboard">
                    <i class="fa fa-list"></i><span>Home</span>
                </a> 
            </li>
            <li class="treeview">
                <a href="<?php echo base_url();?>iibfdra/Version_2/InstituteHome/editprofile">
                    <i class="fa fa-user"></i><span>View Profile</span>
                </a> 
            </li>
             
            <li class="treeview">
                <a href="<?php echo base_url();?>iibfdra/Version_2/Center/listing">
                    <i class="fa fa-book"></i><span>Centers</span>
                </a> 
            </li>

            <!-- Added by Priyanka on 27-10-2022 -->
            <li class="treeview">
                <a href="<?php echo base_url();?>iibfdra/Version_2/Faculty">
                    <i class="fa fa-book"></i><span>Faculty Master</span>
                </a> 
            </li>
            <!-- Added by Priyanka on 27-10-2022 -->
        
            <li class="treeview">
                <a href="<?php echo base_url();?>iibfdra/Version_2/TrainingBatches">
                    <i class="fa fa-book"></i><span>Training Batches</span>
                </a> 
            </li>

            <!-- Added by Priyanka W on 27-10-2022 -->
                <li class="treeview">
                    <a href="<?php echo base_url('iibfdra/Version_2/TrainingBatches/batch_checklist');?>">
                        <i class="fa fa-book"></i><span>Review Batch and Submit to IIBF</span>
                    </a> 
                </li>
            <!-- Added by Priyanka W on 27-10-2022 -->

            <!-- Added by Priyanka w on 06-07-2023 -->
                <li class="treeview">
                    <a href="<?php echo base_url('iibfdra/Version_2/TrainingBatches/batch_applicant_checklist');?>">
                        <i class="fa fa-book"></i><span>Batch Applicant Checklist</span>
                    </a> 
                </li>
            <!-- Added by Priyanka on 06-07-2023 -->
           
            <?php 
           /* function get_client_ip() {
             $ipaddress = '';
             if (getenv('HTTP_CLIENT_IP'))
                $ipaddress = getenv('HTTP_CLIENT_IP');
             else if(getenv('HTTP_X_FORWARDED_FOR'))
                $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
             else if(getenv('HTTP_X_FORWARDED'))
                $ipaddress = getenv('HTTP_X_FORWARDED');
             else if(getenv('HTTP_FORWARDED_FOR'))
                $ipaddress = getenv('HTTP_FORWARDED_FOR');
             else if(getenv('HTTP_FORWARDED'))
                $ipaddress = getenv('HTTP_FORWARDED');
             else if(getenv('REMOTE_ADDR'))
                $ipaddress = getenv('REMOTE_ADDR');
             else
                $ipaddress = 'UNKNOWN';
                return $ipaddress;
            }
            $ip = get_client_ip();*/
            //echo $ip.'---'.count( $active_exams ); // 115.124.115.69
            //if( $ip == '182.73.101.70'){
                $this->db->join('dra_exam_activation_master b', 'a.exam_code = b.exam_code', 'right');
                $active_exams  = $this->master_model->getRecords("dra_exam_master a",array('a.exam_code !='=>'57'));
                
                if( count( $active_exams )  > 0 ) { 
                    $comp_currdate = date('Y-m-d H:i:s');
                   // $comp_currdate = date('Y-m-d');
                    foreach( $active_exams as $exam ) { 
                        $comp_frmdate = $exam['exam_from_date'].' '.$exam['exam_from_time'];
                        $comp_todate = $exam['exam_to_date'].' '.$exam['exam_to_time'];
                        //$comp_frmdate = $exam['exam_from_date'];
                       // $comp_todate = $exam['exam_to_date'];
                        if( strtotime($comp_currdate) >= strtotime($comp_frmdate) && strtotime($comp_currdate) <= strtotime($comp_todate) ) { ?>
                            <li class="treeview">
                                <a href="<?php echo base_url('iibfdra/Version_2/TrainingBatches/allapplicants/'.base64_encode($exam['exam_code']));?>">
                                    <?php 
                                    /* Make exam name short to display in sidebar menu */
                                    $desc = strtolower($exam['description']);
                                    $desc = str_ireplace('debt recovery agent','DRA',$desc);
                                    $desc = str_ireplace('examination','Exam',$desc);
                                    $desc = str_ireplace('-','',$desc);
                                    ?>
                                    <!-- <i class="fa fa-book"></i> <span>Apply for <?php echo ucwords($desc);?></span> -->
                                    <?php 
                                    if($exam['exam_code'] == 1036) {
                                    ?>
                                    <i class="fa fa-book"></i> <span>Apply for <?php echo ucwords($desc);?></span>
                                    <?php } else { ?>
                                    <i class="fa fa-book"></i> <span>Apply for <?php echo ucwords($desc).' ( Old<br> Syllabus)';?></span>
                                    <?php } ?>
                                </a>
                            </li>
                        <?php 
                        }
                    }
                } 
            //}
            ?>
            
            
            <li class="treeview">
                <a href="<?php echo base_url();?>iibfdra/Version_2/TrainingBatches/reason_search">
                    <i class="fa fa-book"></i> <span>Member Exam Status</span>
                </a>
            </li>  
            <!-- <li class="treeview">
                <a href="<?php echo base_url();?>iibfdra/Version_2/Result/DRA_Result">
                    <i class="fa fa-book"></i> <span>DRA Result</span>
                </a>
            </li>   -->
            <li class="treeview">
                <a href="<?php echo base_url();?>iibfdra/Version_2/Result/DRA_Revised_Result">
                    <i class="fa fa-book"></i> <span>DRA(Revised Syllabus) Result</span>
                </a>
            </li>
            <!-- <li class="treeview">
                <a href="<?php echo base_url();?>iibfdra/Version_2/Result/DRATC_Result">
                    <i class="fa fa-book"></i> <span>DRA-TC Result</span>
                </a>
            </li> -->
            <li class="treeview">
                <a href="<?php echo base_url();?>iibfdra/Version_2/Admitcard">
                    <i class="fa fa-book"></i> <span>Admitcard PDF</span>
                </a>
            </li>
            <li class="treeview">
                <a href="<?php echo base_url();?>iibfdra/Version_2/TrainingBatches/proforma_invoice_payment/">
                    <i class="fa fa-book"></i> <span>Proforma Invoice Payment</span>
                </a>
            </li>    
            <li class="treeview">
                <a href="<?php echo base_url();?>iibfdra/Version_2/transaction/transactions/">
                    <i class="fa fa-book"></i> <span>Transaction Details</span>
                </a>
            </li>
           <!--  <li class="treeview">
                <a href="<?php ///echo base_url();?>iibfdra/Version_2/DraExam/search_candidate">
                    <i class="fa fa-pencil"></i> <span>Edit Candidate Profile</span>
                </a>
            </li> -->
           <!--  <li class="treeview">
                <a href="javascript:void(0);">
                    <i class="fa fa-book"></i> <span>Training Certificate Format</span>
                </a>
            </li> -->             
            <li class="treeview">
                 <a href="<?php echo base_url();?>iibfdra/Version_2/InstituteHome/changepass">
                    <i class="fa fa-book"></i> <span>Change Password</span>
                </a>
            </li>
            <li class="treeview">
                <a href="<?php echo  base_url()?>iibfdra/Version_2/InstituteLogin/logout">
                    <i class="fa fa-book"></i> <span>Logout</span>
                </a>
            </li>
      </ul>
	</section>
    <!-- /.sidebar -->
</aside>
<?php } ?>