<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome to Indian Institute of Banking &amp; Finance</title>
<link href="<?php echo base_url()?>assets/css/exam-result.css" rel="stylesheet" type="text/css" />
 <script src="<?php echo base_url()?>assets/admin/plugins/jQuery/jQuery-2.2.0.min.js"></script>
 <style>
 .resultlink{
	font-weight:bold;
	font-size:15px;
	line-height:25px;
  }
 </style>
</head>

<body>
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
                                	<?php if($this->session->userdata('result_fucn') == 'caiib118'){?>
                                	<a href="http://iibf.org.in/iib_memberfeedbackApr2013.asp?fname=result/caiib118_result_dashboard">
                                		Click here for Feedback
                                    </a>
                                    <?php }?>
                                    <?php if($this->session->userdata('result_fucn') == 'caiib218'){?>
                                	<a href="http://iibf.org.in/iib_memberfeedbackApr2013.asp?fname=marksheet/caiib218_result_dashboard">
                                		Click here for Feedback
                                    </a>
                                    <?php }?>
                                    <?php if($this->session->userdata('result_fucn') == 'pdc733'){?>
                                	<a href="http://iibf.org.in/iib_memberfeedbackApr2013.asp?fname=result/pdc_dashboard733">
                                		Click here for Feedback
                                    </a>
                                    <?php }?>
                                    <?php if($this->session->userdata('result_fucn') == 'dipcert802'){?>
                                	<a href="http://iibf.org.in/iib_memberfeedbackApr2013.asp?fname=result/dashboard802">
                                		Click here for Feedback
                                    </a>
                                    <?php }?>
                                    <?php if($this->session->userdata('result_fucn') == 'dipcert803'){?>
                                	<a href="http://iibf.org.in/iib_memberfeedbackApr2013.asp?fname=marksheet/dashboard803">
                                		Click here for Feedback
                                    </a>
                                    <?php }?>
                                    <?php if($this->session->userdata('result_fucn') == 'dipcert804'){?>
                                	<a href="http://iibf.org.in/iib_memberfeedbackApr2013.asp?fname=marksheet/dashboard804">
                                		Click here for Feedback
                                    </a>
                                    <?php }?>
                                    
                                    <?php if($this->session->userdata('result_fucn') == 'dipcert'){?>
                                	<a href="http://iibf.org.in/iib_memberfeedbackApr2013.asp?fname=marksheet/dipcert_dashboard">
                                		Click here for Feedback
                                    </a>
                                    <?php }?>

                                     <?php if($this->session->userdata('result_fucn') == 'spel_el'){?>
                                    <a href="http://iibf.org.in/iib_memberfeedbackApr2013.asp?fname=marksheet/dipcert_dashboard">
                                        Click here for Feedback
                                    </a>
                                    <?php }?>

                                    

                                    <?php if($this->session->userdata('result_fucn') == 'pdc732'){?>
                                	<a href="http://iibf.org.in/iib_memberfeedbackApr2013.asp?fname=result/pdc_dashboard732">
                                		Click here for Feedback
                                    </a>
                                    <?php }?>
                                    <?php if($this->session->userdata('result_fucn') == 'bcbfresultjuly'){?>
                                	<a href="http://iibf.org.in/iib_memberfeedbackApr2013.asp?fname=result/bcbf_result_dashboard">
                                		Click here for Feedback
                                    </a>
                                    <?php }?>
                                    <?php if($this->session->userdata('result_fucn') == 'bcbfresultsept'){?>
                                	<a href="http://iibf.org.in/iib_memberfeedbackApr2013.asp?fname=result/bcbf_resultsept_dashboard">
                                		Click here for Feedback
                                    </a>
                                    <?php }?>
                                    <?php if($this->session->userdata('result_fucn') == 'bcbfresultnov'){?>
                                	<a href="http://iibf.org.in/iib_memberfeedbackApr2013.asp?fname=result/bcbf_resultnov_dashboard">
                                		Click here for Feedback
                                    </a>
                                    <?php }?>
                                    <?php if($this->session->userdata('result_fucn') == 'bcbfresultdec'){?>
                                	<a href="http://iibf.org.in/iib_memberfeedbackApr2013.asp?fname=result/bcbf_resultdec_dashboard">
                                		Click here for Feedback
                                    </a>
                                    <?php }?>
                                    
                                      <?php if($this->session->userdata('result_fucn') == 'bcbfresult549'){?>
                                	<a href="http://projects.teamgrowth.net/Test-IIBF/iib_memberfeedbackApr2013.asp?fname=result/bcbf_result549_dashboard">
                                		Click here for Feedback
                                    </a>
                                    <?php }?>
                                    
                                    <?php if($this->session->userdata('result_fucn') == 'pdc'){?>
                                	<a href="http://iibf.org.in/iib_memberfeedbackApr2013.asp?fname=marksheet/pdc_dashboard">
                                		Click here for Feedback
                                    </a>
                                    <?php }?>
                                    <?php if($this->session->userdata('result_fucn') == 'jaiib218'){?>
                                	<a href="http://iibf.org.in/iib_memberfeedbackApr2013.asp?fname=marksheet/jaiibdashboard218">
                                		Click here for Feedback
                                    </a>
                                    <?php }?>
                                    <?php if($this->session->userdata('result_fucn') == 'jaiib'){?>
                                	<a href="http://iibf.org.in/iib_memberfeedbackApr2013.asp?fname=marksheet/jaiibdashboard">
                                		Click here for Feedback
                                    </a>
                                    <?php }?>
                                    <?php if($this->session->userdata('result_fucn') == 'caiib'){?>
                                	<a href="http://iibf.org.in/iib_memberfeedbackApr2013.asp?fname=marksheet/caiibdashboard">
                                		Click here for Feedback
                                    </a>
                                    <?php }?>
                                    <?php if($this->session->userdata('result_fucn') == 'bcbf'){?>
                                	<a href="http://iibf.org.in/iib_memberfeedbackApr2013.asp?fname=marksheet/bcbf_result_dashboard">
                                		Click here for Feedback
                                    </a>
                                    <?php }?>
                                    <?php if($this->session->userdata('result_fucn') == 'naarresult'){?>
                                	<a href="http://projects.teamgrowth.net/Test-IIBF/iib_memberfeedbackApr2013.asp?fname=marksheet/naarresult_dashboard">
                                		Click here for Feedback
                                    </a>
                                    <?php }?>
                                </td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>
                                	
                                	<?php if($this->session->userdata('result_fucn') == 'caiib118'){?>
                                	<a href="<?php echo base_url()?>/result/caiib118_result_dashboard">Result / Will give feedback later</a>
                                    <?php }?>
                                    <?php if($this->session->userdata('result_fucn') == 'caiib218'){?>
                                	<a href="<?php echo base_url()?>/marksheet/caiib218_result_dashboard">Result / Will give feedback later</a>
                                    <?php }?>
                                    <?php if($this->session->userdata('result_fucn') == 'pdc733'){?>
                                	<a href="<?php echo base_url()?>/result/pdc_dashboard733">Result / Will give feedback later</a>
                                    <?php }?>
                                    <?php if($this->session->userdata('result_fucn') == 'dipcert802'){?>
                                	<a href="<?php echo base_url()?>/result/dashboard802">Result / Will give feedback later</a>
                                    <?php }?>
                                    <?php if($this->session->userdata('result_fucn') == 'dipcert803'){?>
                                	<a href="<?php echo base_url()?>/marksheet/dashboard803">Result / Will give feedback later</a>
                                    <?php }?>
                                    <?php if($this->session->userdata('result_fucn') == 'dipcert804'){?>
                                	<a href="<?php echo base_url()?>/marksheet/dashboard804">Result / Will give feedback later</a>
                                    <?php }?>
                                     <?php if($this->session->userdata('result_fucn') == 'dipcert'){?>
                                	<a href="<?php echo base_url()?>/marksheet/dipcert_dashboard">Result / Will give feedback later</a>
                                    <?php }?>

                                    <?php if($this->session->userdata('result_fucn') == 'spel_el'){?>
                                    <a href="<?php echo base_url()?>/marksheet/spel_dashboard">Result / Will give feedback later</a>
                                    <?php }?>


                                    
                                    <?php if($this->session->userdata('result_fucn') == 'pdc732'){?>
                                	<a href="<?php echo base_url()?>/result/pdc_dashboard732">Result / Will give feedback later</a>
                                    <?php }?>
                                    <?php if($this->session->userdata('result_fucn') == 'bcbfresultjuly'){?>
                                	<a href="<?php echo base_url()?>/result/bcbf_result_dashboard">Result / Will give feedback later</a>
                                    <?php }?>
                                    <?php if($this->session->userdata('result_fucn') == 'bcbfresultsept'){?>
                                	<a href="<?php echo base_url()?>/result/bcbf_resultsept_dashboard">Result / Will give feedback later</a>
                                    <?php }?>
                                    <?php if($this->session->userdata('result_fucn') == 'bcbfresultnov'){?>
                                	<a href="<?php echo base_url()?>/result/bcbf_resultnov_dashboard">Result / Will give feedback later</a>
                                    <?php }?>
                                     <?php if($this->session->userdata('result_fucn') == 'bcbfresultdec'){?>
                                	<a href="<?php echo base_url()?>/result/bcbf_resultdec_dashboard">Result / Will give feedback later</a>
                                    <?php }?>
                                    
                                    <?php if($this->session->userdata('result_fucn') == 'bcbfresult549'){?>
                                	<a href="<?php echo base_url()?>/result/bcbf_result549_dashboard">Result / Will give feedback later</a>
                                    <?php }?>
                                    
                                    <?php if($this->session->userdata('result_fucn') == 'pdc'){?>
                                	<a href="<?php echo base_url()?>/marksheet/pdc_dashboard">Result / Will give feedback later</a>
                                    <?php }?> 
                                    <?php if($this->session->userdata('result_fucn') == 'jaiib218'){?>
                                	<a href="<?php echo base_url()?>/marksheet/jaiibdashboard218">Result / Will give feedback later</a>
                                    <?php }?>
                                     <?php if($this->session->userdata('result_fucn') == 'jaiib'){?>
                                	<a href="<?php echo base_url()?>/marksheet/jaiibdashboard">Result / Will give feedback later</a>
                                    <?php }?>
                                    <?php if($this->session->userdata('result_fucn') == 'caiib'){?>
                                	<a href="<?php echo base_url()?>/marksheet/caiibdashboard">Result / Will give feedback later</a>
                                    <?php }?>
                                    <?php if($this->session->userdata('result_fucn') == 'bcbf'){?>
                                	<a href="<?php echo base_url()?>/marksheet/bcbf_result_dashboard">Result / Will give feedback later</a>
                                    <?php }?> 
                                    <?php if($this->session->userdata('result_fucn') == 'naarresult'){?>
                                	<a href="<?php echo base_url()?>/marksheet/naarresult_dashboard">Will give feedback later </a>
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
  </table>
</body>
</html>
