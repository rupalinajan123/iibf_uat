<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>IIBF - Membership ID</title>
</head>

<body style="margin:0 auto; padding:0; font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:24px;">
<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><table border="0" cellpadding="0" cellspacing="0" width="56%" align="center">
        <tr style=" background:#FFF;">
          <td><img src="<?php echo base_url();?>assets/images/top-img.png" alt="top-img" style=" width:100%;" /></td>
        </tr>
        <tr style="background:#cdd5fc;">
          <td style="text-align:center; padding:10px 0; font-weight:bold; color:#034faf; font-size:18px;">Member's  Identity  Card</td>
        </tr>
        <tr style="background:#cdd5fc;">
          <td style="padding:0 15px 0px 5px;"><table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" style="background:#cdd5fc;">
              <tr>
                <td rowspan="6" width="26%" valign="top">
                  <?php $this->master_model->resize_admitcard_images(get_img_name($member_number,'p')); ?>
                  <img src="<?php echo base_url();?><?php echo get_img_name($member_number,'p');?>" height="150" width="140"/>
</td>
                <td width="74%" style="padding-left:15px; padding:5px 0 5px 0; font-size:16px;"><strong>Membership No:</strong> <?php echo $member_number;?></td>
               
              </tr>
              <tr>
                <td style="padding-left:15px; padding:5px 0 5px 0; font-size:16px" ><strong>Name:</strong> <?php echo strtoupper($name);?></td>
              </tr>
              <tr>
                <td style="padding-left:15px; padding:5px 0 5px 0; font-size:16px">
                <strong>Date of Birth:</strong>
				<?php 
					if($dob != '0000-00-00'){
						echo date('d-M-Y',strtotime($dob));
					}else{
						echo "-";
					}
				?>
                </td>
              </tr>
              <tr>
                <td style="padding-left:15px; padding:5px 0 5px 0; font-size:16px"><strong>Employer Name:</strong> <?php echo $place_of_work;?></td>
              </tr>
              <tr>
                <td style="padding-left:15px; padding:5px 0 5px 0; font-size:16px"><strong>Date of Issue:</strong> <?php echo date('d-M-Y',strtotime($dateofissue));?></td>
              </tr>
              <tr>
                <td><table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
                    <tr>
                      <td align="left" style="padding-left:50px;">
                        <?php $this->master_model->resize_admitcard_images(get_img_name($member_number,'s')); ?>
                        <img src="<?php echo base_url();?><?php echo get_img_name($member_number,'s');?>" height="40" width="40"/>
                      </td>
                      <td align="center"><img src="<?php echo base_url();?>assets/images/kavan_sign.png" width="40" height="40" /></td>
                    </tr>
                    <tr>
                      <td style="padding-left:15px; font-size:16px">Member's Signature</td>
                      <td align="center" style=" font-size:16px">Deputy Director</td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td><img src="<?php echo base_url();?>assets/images/bottom.png" style=" width:100%;" /></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>
