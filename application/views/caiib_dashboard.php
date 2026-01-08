<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('google_analytics_script_common'); ?>
	<meta charset="utf-8">
	<title>Welcome to IIBF</title>
    <style>
    .btn.btn-info {
  background: #1287c0;
  color: #fff;
  padding: 10px; margin:0 5px;
  text-decoration: none;
}
    .btn.btn-info:hover {
  background: #0378b1;
  color: #fff;
  padding: 10px; margin:0 5px;
  text-decoration: none;
}

    .btn2.btn-info2 {
 /* background: #CCC;*/
  color: #767676;
  padding: 10px; margin:0 5px;
  text-decoration: none;
}
    .btn2.btn-info:hover2 {
  background: #CCC;
  color: #000;
  padding: 10px; margin:0 5px;
  text-decoration: none;
}

    </style>
    
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
                  <td style="background-color:#7fd1ea; color:#fff; text-align:center;  font-weight:bold; font-size:40px; padding:7px 0 7px 7px;">
                  We shall be obliged to have your feedback on our services. We endeavour to use the same towards further improving our services to you.<br/>
                  </td>
                </tr>
                <tr>
                  <td style="background-color:#7fd1ea; color:#06F; ; font-weight:bold; font-size:14px; text-align:center; padding:7px 0 7px 7px;">&nbsp;</td>
                </tr>
                <tr>
                  <td style="background-color:#7fd1ea; color:#06F; ; font-weight:bold; font-size:14px; text-align:center; padding:7px 0 7px 7px;">
                  <a href="http://projects.teamgrowth.net/Test-IIBF/iib_memberfeedbackApr2013.asp" class="btn btn-info">
                                		Click here for Feedback
                                    </a>
                                    
                                    <a href="<?php echo base_url()?>/admitcard/getadmitdashboard"  class="btn2 btn-info2">
                                	Will give feedback later
                                    </a>
                  </td>
                </tr>
                <tr>
                	<td style="background-color:#7fd1ea; color:#06F; ; font-weight:bold; font-size:14px; text-align:center; padding:7px 0 7px 7px;">
                    	<table >
                        	<tr>
                            	<td>
                                	
                                </td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>
                                	
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