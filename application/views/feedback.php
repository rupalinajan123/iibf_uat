<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('google_analytics_script_common'); ?>
	<meta charset="utf-8">
	<title>Welcome to IIBF</title>
    
</head>
<body style="background-color:#fff; margin:0 auto; padding:0; font-family:Arial, Helvetica, sans-serif; font-size:14px;">
  <table cellpadding="0" cellspacing="0" width="800" border="0" align="center">
    <tr>
      <td style="background-color:#fff;">
        <!--table-1-->
        <table cellpadding="0" cellspacing="0" width="100%" border="0" align="center">
          <tr>
            <td style="border:1px solid #1287c0; padding:5px;">
              <table cellpadding="0" cellspacing="0" width="100%" border="0" align="center">
                <tr>
                  <td align="center"><img src="<?php echo base_url();?>assets/images/logo.jpg" width="400" height="66" /></td>
                </tr>
                <tr>
                  <td height="5"></td>
                </tr>
                <tr>
                  <td style="background-color:#1287c0; color:#fff; text-align:left; font-weight:bold; font-size:14px; padding:5px 5px 5px 5px;">Welcome <?php //echo $name;?>
                  <div style="float:right;">
                  	<?php echo date('d-M-Y');?> &nbsp;
                  	<a href="<?php echo base_url()?>admitcard/logout">Logout</a>
                  </div>
                  </td>
                </tr>
                <tr>
                  <td height="5"></td>
                </tr>
                <tr>
                  <td style="background-color:#7fd1ea; color:#fff; ; font-weight:bold; font-size:14px; padding:7px 0 7px 7px;">
                  We shall be obliged to have your feedback on our services. We endeavour to use the same towards further improving our services to you.<br/>
                  </td>
                </tr>
                <tr>
                	<td style="background-color:#7fd1ea; color:#06F; ; font-weight:bold; font-size:14px; padding:7px 0 7px 7px;"> 
                    	<table>
                        	<tr>
                            	<td>
                                	<?php if($this->session->userdata('feedback_exam_name') == 'bcbf'){?>
                                	<a href="http://iibf.org.in/iib_memberfeedbackApr2013.asp?fname=admitcard/getadmitdashboard">
                                		Click here for Feedback
                                    </a>
                                    <?php }?>
                                    <?php if($this->session->userdata('feedback_exam_name') == 'kerla'){?>
                                	<a href="http://iibf.org.in/iib_memberfeedbackApr2013.asp?fname=admitcard/getadmitdashboardkerla">
                                		Click here for Feedback
                                    </a>
                                    <?php }?>
                                    <?php if($this->session->userdata('feedback_exam_name') == 'kotah'){?>
                                	<a href="http://iibf.org.in/iib_memberfeedbackApr2013.asp?fname=admitcard/getadmitdashboardKotah">
                                		Click here for Feedback
                                    </a>
                                    <?php }?>
                                    <?php if($this->session->userdata('feedback_exam_name') == 'dipcert802'){?>
                                    <a href="http://iibf.org.in/iib_memberfeedbackApr2013.asp?fname=admitcard/dipcertdashboard">
                                		Click here for Feedback
                                    </a>
                                    <?php }?>
                                    <?php if($this->session->userdata('feedback_exam_name') == 'dipcert804'){?>
                                    <a href="http://iibf.org.in/iib_memberfeedbackApr2013.asp?fname=admitcard/dipcertdashboard804">
                                		Click here for Feedback
                                    </a>
                                    <?php }?>
                                    <?php if($this->session->userdata('feedback_exam_name') == 'dipcert808'){?>
                                    <a href="http://iibf.org.in/iib_memberfeedbackApr2013.asp?fname=admitcard/dipcertdashboard808">
                                		Click here for Feedback
                                    </a>
                                    <?php }?>
                                    <?php if($this->session->userdata('feedback_exam_name') == 'dipcert901'){?>
                                    <a href="http://iibf.org.in/iib_memberfeedbackApr2013.asp?fname=admitcard/dipcertdashboard901">
                                		Click here for Feedback
                                    </a>
                                    <?php }?>
                                    <?php if($this->session->userdata('feedback_exam_name') == 'dipcert'){?>
                                    <a href="http://iibf.org.in/iib_memberfeedbackApr2013.asp?fname=admitcard/dipcertdashboard">
                                		Click here for Feedback
                                    </a>
                                    <?php }?>
                                    <?php if($this->session->userdata('feedback_exam_name') == 'caiib118'){?>
                                    <a href="http://iibf.org.in/iib_memberfeedbackApr2013.asp?fname=admitcard/caiib118dashboard">
                                		Click here for Feedback
                                    </a>
                                     <?php }?>
                                     <?php if($this->session->userdata('feedback_exam_name') == 'caiib219'){?>
                                    <a href="http://iibf.org.in/iib_memberfeedbackApr2013.asp?fname=admitcard/caiib220dashboard">
                                		Click here for Feedback
                                    </a>
                                     <?php }?>
                                     <?php if($this->session->userdata('feedback_exam_name') == 'caiibres'){?>
                                    <a href="http://iibf.org.in/iib_memberfeedbackApr2013.asp?fname=admitcard/caiibdashboardres">
                                		Click here for Feedback
                                    </a>
                                     <?php }?>
                                      <?php if($this->session->userdata('feedback_exam_name') == 'jaiib'){?>
                                    <a href="http://projects.teamgrowth.net/Test-IIBF/iib_memberfeedbackApr2013.asp?fname=admitcard/jaiibdashboard">
                                		Click here for Feedback
                                    </a>
                                     <?php }?>
                                     <?php if($this->session->userdata('feedback_exam_name') == 'jaiibres'){?>
                                    <a href="http://projects.teamgrowth.net/Test-IIBF/iib_memberfeedbackApr2013.asp?fname=admitcard/jaiibdashboardres">
                                		Click here for Feedback
                                    </a>
                                     <?php }?>
                                </td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>
                                	
                                	<?php if($this->session->userdata('feedback_exam_name') == 'bcbf'){?>
                                	<a href="<?php echo base_url()?>/admitcard/getadmitdashboard">Admit Letter / Will give feedback later</a>
                                    <?php }?>
                                    <?php if($this->session->userdata('feedback_exam_name') == 'kerla'){?>
                                	<a href="<?php echo base_url()?>/admitcard/getadmitdashboardkerla">Admit Letter / Will give feedback later</a>
                                    <?php }?>
                                    
                                    <?php if($this->session->userdata('feedback_exam_name') == 'kotah'){?>
                                	<a href="<?php echo base_url()?>/admitcard/getadmitdashboardKotah">Admit Letter / Will give feedback later</a>
                                    <?php }?>
                                    
                                    <?php if($this->session->userdata('feedback_exam_name') == 'dipcert802'){?>
                                    <a href="<?php echo base_url()?>/admitcard/dipcertdashboard">Admit Letter / Will give feedback later</a>
                                    <?php }?>
                                    
                                    <?php if($this->session->userdata('feedback_exam_name') == 'dipcert804'){?>
                                    <a href="<?php echo base_url()?>/admitcard/dipcertdashboard804">Admit Letter / Will give feedback later</a>
                                    <?php }?>
                                    
                                    <?php if($this->session->userdata('feedback_exam_name') == 'dipcert808'){?>
                                    <a href="<?php echo base_url()?>/admitcard/dipcertdashboard808">Admit Letter / Will give feedback later</a>
                                    <?php }?>
                                    
                                    <?php if($this->session->userdata('feedback_exam_name') == 'dipcert901'){?>
                                    <a href="<?php echo base_url()?>/admitcard/dipcertdashboard901">Admit Letter / Will give feedback later</a>
                                    <?php }?>
                                    
                                    <?php if($this->session->userdata('feedback_exam_name') == 'dipcert'){?>
                                    <a href="<?php echo base_url()?>/admitcard/dipcertdashboard">Admit Letter / Will give feedback later</a>
                                    <?php }?>
                                    
                                    <?php if($this->session->userdata('feedback_exam_name') == 'caiib118'){?>
                                    <a href="<?php echo base_url()?>/admitcard/caiib118dashboard">Admit Letter / Will give feedback later</a>
                                    <?php }?>
                                    <?php if($this->session->userdata('feedback_exam_name') == 'caiib219'){?>
                                    <a href="<?php echo base_url()?>/admitcard/caiib220dashboard">Admit Letter / Will give feedback later</a>
                                    <?php }?>
                                    <?php if($this->session->userdata('feedback_exam_name') == 'caiibres'){?>
                                    <a href="<?php echo base_url()?>/admitcard/caiibdashboardres">Admit Letter / Will give feedback later</a>
                                    <?php }?>
                                    <?php if($this->session->userdata('feedback_exam_name') == 'jaiib'){?>
                                    <a href="<?php echo base_url()?>/admitcard/jaiibdashboard">Admit Letter / Will give feedback later</a>
                                    <?php }?>
                                    
                                    <?php if($this->session->userdata('feedback_exam_name') == 'jaiibres'){?>
                                    <a href="<?php echo base_url()?>/admitcard/jaiibdashboardres">Admit Letter / Will give feedback later</a>
                                    <?php }?>
                                	
                                    
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
              </table><!--table-2-->
            </td>
          </tr>
        </table><!--table-1-->
      </td>
    </tr>
  </table><!--main-table-->
</body>
</html>