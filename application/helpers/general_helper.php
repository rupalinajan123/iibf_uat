<?php
defined('BASEPATH')||exit('No Direct Allowed Here');
/**
 * Function to create log.
 * @access public 
 * @param String
 * @return String
 */
function careers_logactivity($log_title, $log_message = "", $unique_no, $position_id)
{
    $CI = & get_instance();
    $CI->load->model('Log_model');
    $CI->Log_model->create_careers_log($log_title, $log_message, $unique_no, $position_id);
}
function logactivity($log_title, $log_message = "")
{
    $CI = & get_instance();
    $CI->load->model('Log_model');
    $CI->Log_model->create_log($log_title, $log_message);
}
function bulk_logactivity($log_title, $log_message = "",$inst_id)
{
    $CI = & get_instance();
    $CI->load->model('Log_model');
    $CI->Log_model->bulk_create_log($log_title, $log_message,$inst_id);
}

function log_nm_activity($log_title, $log_message = "")
{
    $CI = & get_instance();
    $CI->load->model('Log_model');
    $CI->Log_model->create_nm_log($log_title, $log_message);
}

function log_dbf_activity($log_title, $log_message = "")
{
    $CI = & get_instance();
    $CI->load->model('Log_model');
    $CI->Log_model->create_dbf_log($log_title, $log_message);
	 }

function storedUserActivity($log_title, $log_message = "", $rId = NULL, $regNo = NULL)
{
    $CI = & get_instance();
    $CI->load->model('Log_model');
    $CI->Log_model->userActivity_log($log_title, $log_message, $rId, $regNo);
}
function bulk_storedUserActivity($log_title, $log_message = "",$inst_id,$rId = NULL, $regNo = NULL)
{
    $CI = & get_instance();
    $CI->load->model('Log_model');
    $CI->Log_model->bulk_userActivity_log($log_title, $log_message, $inst_id, $rId, $regNo);
}

/**
 * Function to admin create log.
 * @access public 
 * @param String
 * @return String
 */
function logadminactivity($log_title, $log_message = "")
{
    $CI = & get_instance();
    $CI->load->model('Log_model');
    $CI->Log_model->create_admin_log($log_title, $log_message);
}

// function to create DRA User (Institute) Log
function log_dra_user($log_title, $log_message = "")
{
    $CI = & get_instance();
    $CI->load->model('Log_model');
    $CI->Log_model->create_dra_userlog($log_title, $log_message);
}

// function to create DRA Admin Log
function log_dra_admin($log_title, $log_message = "")
{
    $CI = & get_instance();
    $CI->load->model('Log_model');
    $CI->Log_model->create_dra_adminlog($log_title, $log_message);
}
// function to create exam Admin Log
function log_exam_admin($log_title, $log_message = "")
{
    $CI = & get_instance();
    $CI->load->model('Log_model');
    $CI->Log_model->create_exam_adminlog($log_title, $log_message);
}

function log_profile_admin($log_title, $log_message = "",$type="",$regid = 0,$regnum = "")
{
    $CI = & get_instance();
    $CI->load->model('Log_model');
    $CI->Log_model->create_admin_profile_log($log_title, $log_message, $type, $regid,$regnum);
}

function log_profile_user($log_title, $log_message = "",$type = "",$regid = 0,$regnum = "")
{
    $CI = & get_instance();
    $CI->load->model('Log_model');
    $CI->Log_model->create_user_profile_log($log_title, $log_message, $type, $regid,$regnum);
}
function bulk_log_profile_user($log_title, $log_message = "",$type = "",$regid = 0,$regnum = "")
{
    $CI = & get_instance();
    $CI->load->model('Log_model');
    $CI->Log_model->bulk_create_user_profile_log($log_title, $log_message, $type, $regid,$regnum);
}

function master_db_backup($basetable)
{
	$CI = & get_instance();
	$backuptable = $basetable."_".$CI->UserID."_".date('Ymd_His');
	$CI->db->query("CREATE TABLE ".$backuptable." LIKE ".$basetable);
	$CI->db->query("INSERT INTO ".$backuptable." SELECT * FROM ".$basetable);
	$CI->db->query("TRUNCATE TABLE ".$basetable);
	
	logadminactivity($log_title = $basetable." imported", $log_message = "Created new table '".$backuptable."' from '".$basetable."' with data,  flush old base table data");
}

function get_calendar_input()
{
	$calendar['year'] = array();
	$calendar['month'] = array();
	$calendar['date'] = array();
	$from_year = date('Y', strtotime("- 60 year"));
	$to_year = date('Y');
	
	for($y=$from_year;$y<=$to_year;$y++)
	{
		$calendar['year'][] = $y;
	}
	
	for($i=1;$i<13;$i++)
	{
		$calendar['month'][] = date('F',strtotime('01-'.$i.'-'.$to_year)); 
	}
	
	for($j=1;$j<32;$j++)
	{
		$calendar['date'][] = $j; 
	}
	
	return $calendar;
}

/**
 * Function to generate order number.
 * @access public 
 * @param String
 * @return String
 */
/*function generate_order_id($field_name)
{
	$CI = & get_instance();
	$CI->db->query("UPDATE site_config SET value = value + 1 WHERE name = '".$field_name."'");
	
	$order_id = "";	
	$sc_query = $CI->db->query("SELECT value FROM site_config WHERE name = '".$field_name."'");

	if ($sc_query->num_rows() > 0)
	{
	   $sc_row = $sc_query->row();
	   $order_id = $sc_row->value;
	}

	return $order_id;
}*/

function generate_order_id($field_name)
{
	echo "ERR 006";exit;
	$CI = & get_instance();
	$order_id = "";

	usleep(rand(1, 10000));

	$sc_query = $CI->db->query("SELECT value FROM site_config WHERE name = '".$field_name."'");

	if ($sc_query->num_rows() > 0)
	{
	   $sc_row = $sc_query->row();
	   $order_id = $sc_row->value;

	   $order_id = $order_id+1;
	   
	   $pt_query = $CI->db->query("SELECT receipt_no FROM payment_transaction WHERE receipt_no = '".$order_id."'");

	   if ($pt_query->num_rows() > 0)
	   {
			usleep(rand(1, 10000));

			if ($field_name == "reg_sbi_order_id")
				$pay_type = 1;
			else if ($field_name == "bd_exam_order_id" || $field_name == "sbi_exam_order_id")
				$pay_type = 2;
			else if ($field_name == "idcard_sbi_order_id")
				$pay_type = 3;

			$pt_max_query = $CI->db->query("SELECT max(receipt_no) as order_id FROM payment_transaction WHERE pay_type = '".$pay_type."'");

			if ($pt_max_query->num_rows() > 0)
			{
			   $pt_max_row = $pt_max_query->row();
			   $order_id = $pt_max_row->order_id;
			}

			$order_id = $order_id+1;
	   }
	   
	   $CI->db->query("UPDATE site_config SET value = '".$order_id."' WHERE name = '".$field_name."'");
	}

	return $order_id;
}

/**
 * Function to generate DRA order number.
 * @access public 
 * @param String
 * @return String
 */
function generate_dra_order_id()
{
	echo "ERR 005";exit;
	$CI = & get_instance();
	
	$field_name = "dra_sbi_order_id";
	$order_id = "";

	usleep(rand(1, 10000));

	$sc_query = $CI->db->query("SELECT value FROM site_config WHERE name = '".$field_name."'");

	if ($sc_query->num_rows() > 0)
	{
	   $sc_row = $sc_query->row();
	   $order_id = $sc_row->value;

	   $order_id = $order_id+1;
	   
	   $pt_query = $CI->db->query("SELECT receipt_no FROM dra_payment_transaction WHERE receipt_no = '".$order_id."'");

	   if ($pt_query->num_rows() > 0)
	   {
			usleep(rand(1, 10000));

			$pt_max_query = $CI->db->query("SELECT max(receipt_no) as order_id FROM dra_payment_transaction");

			if ($pt_max_query->num_rows() > 0)
			{
			   $pt_max_row = $pt_max_query->row();
			   $order_id = $pt_max_row->order_id;
			}

			$order_id = $order_id+1;
	   }
	   
	   $CI->db->query("UPDATE site_config SET value = '".$order_id."' WHERE name = '".$field_name."'");
	}

	return $order_id;
}

/**
 * Function to generate Member Registration number.
 * @access public 
 * @param String
 * @return String
 */
/*function generate_mem_reg_num()
{
	$field_name = "member_id";
	$mem_reg_num = "";
	
	$CI = & get_instance();
	$CI->db->query("UPDATE site_config SET value = value + 1 WHERE name = '".$field_name."'");
	
	$sc_query = $CI->db->query("SELECT value FROM site_config WHERE name = '".$field_name."'");

	if ($sc_query->num_rows() > 0)
	{
	   $sc_row = $sc_query->row();
	   $mem_reg_num = $sc_row->value;
	}

	return $mem_reg_num;
}*/
function generate_mem_reg_num()
{
	echo "ERR 001";exit;
	$CI = & get_instance();
	
	$field_name = "member_id";
	$mem_reg_num = "";

	usleep(rand(1, 10000));

	$sc_query = $CI->db->query("SELECT value FROM site_config WHERE name = '".$field_name."'");

	if ($sc_query->num_rows() > 0)
	{
	   $sc_row = $sc_query->row();
	   $mem_reg_num = $sc_row->value;

	   $mem_reg_num = $mem_reg_num+1;
	   
	   $mr_query = $CI->db->query("SELECT regnumber FROM member_registration WHERE registrationtype = 'O' AND regnumber = '".$mem_reg_num."'");

	   if ($mr_query->num_rows() > 0)
	   {
			usleep(rand(1, 10000));

			$mr_max_query = $CI->db->query("SELECT max(regnumber) as regnumber FROM member_registration WHERE registrationtype = 'O'");

			if ($mr_max_query->num_rows() > 0)
			{
			   $mr_max_row = $mr_max_query->row();
			   $mem_reg_num = $mr_max_row->regnumber;
			}

			$mem_reg_num = $mem_reg_num+1;
	   }
	   
	   $CI->db->query("UPDATE site_config SET value = '".$mem_reg_num."' WHERE name = '".$field_name."'");
	}

	return $mem_reg_num;
}


/**
 * Function to generate Non-Member Registration number.
 * @access public 
 * @param String
 * @return String
 */
/*function generate_nm_reg_num()
{
	$field_name = "non_member_id";
	$nm_reg_num = "";
	
	$CI = & get_instance();
	$CI->db->query("UPDATE site_config SET value = value + 1 WHERE name = '".$field_name."'");
	
	$sc_query = $CI->db->query("SELECT value FROM site_config WHERE name = '".$field_name."'");

	if ($sc_query->num_rows() > 0)
	{
	   $sc_row = $sc_query->row();
	   $nm_reg_num = $sc_row->value;
	}

	return $nm_reg_num;
}*/
function generate_nm_reg_num()
{	
	echo "ERR 002";exit;
	$CI = & get_instance();
	
	$field_name = "non_member_id";
	$nm_reg_num = "";

	usleep(rand(1, 10000));

	$sc_query = $CI->db->query("SELECT value FROM site_config WHERE name = '".$field_name."'");

	if ($sc_query->num_rows() > 0)
	{
	   $sc_row = $sc_query->row();
	   $nm_reg_num = $sc_row->value;

	   $nm_reg_num = $nm_reg_num+1;
	   
	   $mr_query = $CI->db->query("SELECT regnumber FROM member_registration WHERE registrationtype = 'NM' AND regnumber = '".$nm_reg_num."'");

	   if ($mr_query->num_rows() > 0)
	   {
			usleep(rand(1, 10000));

			$mr_max_query = $CI->db->query("SELECT max(regnumber) as regnumber FROM member_registration WHERE registrationtype = 'NM'");

			if ($mr_max_query->num_rows() > 0)
			{
			   $mr_max_row = $mr_max_query->row();
			   $nm_reg_num = $mr_max_row->regnumber;
			}

			$nm_reg_num = $nm_reg_num+1;
	   }
	   
	   $CI->db->query("UPDATE site_config SET value = '".$nm_reg_num."' WHERE name = '".$field_name."'");
	}

	return $nm_reg_num;
}

/**
 * Function to generate DBF-Member Registration number.
 * @access public 
 * @param String
 * @return String
 */
/*function generate_dbf_reg_num()
{
	$field_name = "dbf_member_id";
	$dbf_reg_num = "";
	
	$CI = & get_instance();
	$CI->db->query("UPDATE site_config SET value = value + 1 WHERE name = '".$field_name."'");
	
	$sc_query = $CI->db->query("SELECT value FROM site_config WHERE name = '".$field_name."'");

	if ($sc_query->num_rows() > 0)
	{
	   $sc_row = $sc_query->row();
	   $dbf_reg_num = $sc_row->value;
	}

	return $dbf_reg_num;
}*/
function generate_dbf_reg_num()
{	
	echo "ERR 003";exit;
	$CI = & get_instance();
	
	$field_name = "dbf_member_id";
	$dbf_reg_num = "";

	usleep(rand(1, 10000));

	$sc_query = $CI->db->query("SELECT value FROM site_config WHERE name = '".$field_name."'");

	if ($sc_query->num_rows() > 0)
	{
	   $sc_row = $sc_query->row();
	   $dbf_reg_num = $sc_row->value;

	   $dbf_reg_num = $dbf_reg_num+1;
	   
	   $mr_query = $CI->db->query("SELECT regnumber FROM member_registration WHERE registrationtype = 'DB' AND regnumber = '".$dbf_reg_num."'");

	   if ($mr_query->num_rows() > 0)
	   {
			usleep(rand(1, 10000));

			$mr_max_query = $CI->db->query("SELECT max(regnumber) as regnumber FROM member_registration WHERE registrationtype = 'DB'");

			if ($mr_max_query->num_rows() > 0)
			{
			   $mr_max_row = $mr_max_query->row();
			   $dbf_reg_num = $mr_max_row->regnumber;
			}

			$dbf_reg_num = $dbf_reg_num+1;
	   }
	   
	   $CI->db->query("UPDATE site_config SET value = '".$dbf_reg_num."' WHERE name = '".$field_name."'");
	}

	return $dbf_reg_num;
}


/**
 * Function to generate DRA-Member Registration number.
 * @access public 
 * @param String
 * @return String
 */
/*function generate_dra_reg_num()
{
	$field_name = "dra_member_id";
	$dra_reg_num = "";
	
	$CI = & get_instance();
	$CI->db->query("UPDATE site_config SET value = value + 1 WHERE name = '".$field_name."'");
	
	$sc_query = $CI->db->query("SELECT value FROM site_config WHERE name = '".$field_name."'");

	if ($sc_query->num_rows() > 0)
	{
	   $sc_row = $sc_query->row();
	   $dra_reg_num = $sc_row->value;
	}

	return $dra_reg_num;
}*/
function generate_dra_reg_num()
{	
	echo "ERR 004";exit;
	$CI = & get_instance();
	
	$field_name = "dra_member_id";
	$dra_reg_num = "";

	usleep(rand(1, 10000));

	$sc_query = $CI->db->query("SELECT value FROM site_config WHERE name = '".$field_name."'");

	if ($sc_query->num_rows() > 0)
	{
	   $sc_row = $sc_query->row();
	   $dra_reg_num = $sc_row->value;

	   $dra_reg_num = $dra_reg_num+1;
	   
	   $dm_query = $CI->db->query("SELECT regnumber FROM dra_members WHERE regnumber = '".$dra_reg_num."'");

	   if ($dm_query->num_rows() > 0)
	   {
			usleep(rand(1, 10000));

			$dm_max_query = $CI->db->query("SELECT max(regnumber) as regnumber FROM dra_members");

			if ($dm_max_query->num_rows() > 0)
			{
			   $dm_max_row = $dm_max_query->row();
			   $dra_reg_num = $dm_max_row->regnumber;
			}

			$dra_reg_num = $dra_reg_num+1;
	   }
	   
	   $CI->db->query("UPDATE site_config SET value = '".$dra_reg_num."' WHERE name = '".$field_name."'");
	}

	return $dra_reg_num;
}

/**
 * Function to generate refund_request_id.
 * @access public 
 * @param String
 * @return String
 */
function generate_refund_request_id($field_name)
{
	echo "ERR 007";exit;
	$CI = & get_instance();
	$refund_request_id = "";

	usleep(rand(1, 10000));
	
	if ($field_name == "bd_refund_request_id")
		$gateway = "billdesk";
	else if ($field_name == "sbi_refund_request_id")
		$gateway = "sbiepay";

	$sc_query = $CI->db->query("SELECT value FROM site_config WHERE name = '".$field_name."'");

	if ($sc_query->num_rows() > 0)
	{
	   $sc_row = $sc_query->row();
	   $refund_request_id = $sc_row->value;

	   $refund_request_id = $refund_request_id+1;
	   
	   $pr_query = $CI->db->query("SELECT refund_request_id FROM payment_refund WHERE refund_request_id = '".$refund_request_id."' AND gateway = '".$gateway."'");

	   if ($pr_query->num_rows() > 0)
	   {
			usleep(rand(1, 10000));

			$pr_max_query = $CI->db->query("SELECT max(refund_request_id) as refund_request_id FROM payment_refund WHERE gateway = '".$gateway."'");

			if ($pr_max_query->num_rows() > 0)
			{
			   $pr_max_row = $pr_max_query->row();
			   $refund_request_id = $pr_max_row->refund_request_id;
			}

			$refund_request_id = $refund_request_id+1;
	   }
	   
	   $CI->db->query("UPDATE site_config SET value = '".$refund_request_id."' WHERE name = '".$field_name."'");
	}

	return $refund_request_id;
}

function get_img_name($regnumber,$img_type)
{
	$CI = & get_instance();
	$image_path = "";
	$vals = array(
		'img_path' => './uploads/',
		'img_url' => base_url().'uploads/',
	);

	$mr_query = $CI->db->query("SELECT reg_no,scannedphoto,scannedsignaturephoto,idproofphoto,empidproofphoto,declaration,bank_bc_id_card,image_path FROM member_registration WHERE regnumber = '".$regnumber."' AND isactive = '1' AND isdeleted = 0");
	if ($mr_query->num_rows() > 0)
	{
		$mr_row = $mr_query->row();
		$image_path = $mr_row->image_path;
		if ($image_path)
		{
			if ($img_type == "pr")
			{
				if($mr_row->idproofphoto=='')
				{ 
					$image_path = 'uploads'.$mr_row->image_path."idproof/pr_".$mr_row->reg_no.".jpg";
					if(!is_file($image_path))
					{
						$image_path = $vals["img_path"]."idproof/".$mr_row->idproofphoto."";
					}
				}
				else
				{
					$image_path = $vals["img_path"]."idproof/".$mr_row->idproofphoto."";
					if(!is_file($image_path))
					{
						$image_path = 'uploads'.$mr_row->image_path."idproof/pr_".$mr_row->reg_no.".jpg";
					}
				}
			}
			else if ($img_type == "empr")
			{   
				if($mr_row->empidproofphoto=='')
				{ 
					$image_path = 'uploads'.$mr_row->image_path."empidproof/empr_".$mr_row->reg_no.".jpg";
					if(!is_file($image_path))
					{
						$image_path = $vals["img_path"]."empidproof/".$mr_row->empidproofphoto."";
					}
				}
				else
				{
					$image_path = $vals["img_path"]."empidproof/".$mr_row->empidproofphoto."";
					if(!is_file($image_path))
					{
						$image_path = 'uploads'.$mr_row->image_path."empidproof/empr_".$mr_row->reg_no.".jpg";
					}
				}
			}   
			else if ($img_type == "p")
			{
				if($mr_row->scannedphoto=='')
				{    
					$image_path = 'uploads'.$mr_row->image_path."photo/p_".$mr_row->reg_no.".jpg";
					if(!file_exists($image_path))
					{
						$image_path = $vals["img_path"]."photograph/".$mr_row->scannedphoto."";
					}
				}
				else
				{			
					$image_path = $vals["img_path"]."photograph/".$mr_row->scannedphoto."";
					if(!is_file($image_path))
					{
						$image_path = 'uploads'.$mr_row->image_path."photo/p_".$mr_row->reg_no.".jpg";
					}
				}
			}
			else if ($img_type == "s")
			{
				if($mr_row->scannedsignaturephoto=='')
				{   
					$image_path = 'uploads'.$mr_row->image_path."signature/s_".$mr_row->reg_no.".jpg";
					if(!file_exists($image_path))
					{
						$image_path = $vals["img_path"]."scansignature/".$mr_row->scannedsignaturephoto."";
					}
				}
				else
				{
					$image_path = $vals["img_path"]."scansignature/".$mr_row->scannedsignaturephoto."";
					if(!is_file($image_path))
					{
						$image_path = 'uploads'.$mr_row->image_path."signature/s_".$mr_row->reg_no.".jpg";
					}
				}
			}
			else if ($img_type == "declaration")
			{
				if($mr_row->declaration=='')
				{	 
					$image_path = 'uploads'.$mr_row->image_path."declaration/declaration_".$mr_row->reg_no.".jpg";
					if(!is_file($image_path))
					{
						$image_path = $vals["img_path"]."declaration/".$mr_row->declaration."";
					}
				}
				else
				{
					$image_path = $vals["img_path"]."declaration/".$mr_row->declaration."";
					if(!is_file($image_path))
					{
						$image_path = 'uploads'.$mr_row->image_path."declaration/declaration_".$mr_row->reg_no.".jpg";
					}
				}
			}
			else if ($img_type == "bank_bc_id_card")
			{
				if($mr_row->bank_bc_id_card=='')
				{	 
					$image_path = 'uploads'.$mr_row->image_path."empidproof/bank_bc_id_card_".$mr_row->reg_no.".jpg";
					if(!is_file($image_path))
					{
						$image_path = $vals["img_path"]."empidproof/".$mr_row->bank_bc_id_card."";
					}
				}
				else
				{
					$image_path = $vals["img_path"]."empidproof/".$mr_row->bank_bc_id_card."";
					if(!is_file($image_path))
					{
						$image_path = 'uploads'.$mr_row->image_path."empidproof/bank_bc_id_card_".$mr_row->reg_no.".jpg";
					}
				}
			}
		}
		else
		{
			if ($img_type == "pr" && $mr_row->idproofphoto!='')
			{
				$image_path = $vals["img_path"]."idproof/".$mr_row->idproofphoto."";
			}
			else if ($img_type == "p" && $mr_row->scannedphoto!='') 
			{
				$image_path = $vals["img_path"]."photograph/".$mr_row->scannedphoto."";
			}
			else if ($img_type == "empr" && $mr_row->empidproofphoto!='') 
			{
				$image_path = $vals["img_path"]."empidproof/".$mr_row->empidproofphoto."";
			}
			else if ($img_type == "s" && $mr_row->scannedsignaturephoto!='')
			{
				$image_path = $vals["img_path"]."scansignature/".$mr_row->scannedsignaturephoto."";
			}
			else if ($img_type == "declaration" && $mr_row->declaration!='')
			{
				$image_path = $vals["img_path"]."declaration/".$mr_row->declaration."";
			}
			else if ($img_type == "bank_bc_id_card" && $mr_row->bank_bc_id_card!='')
			{
				$image_path = $vals["img_path"]."empidproof/".$mr_row->bank_bc_id_card."";
			}
		} 
	}

	if(!file_exists($image_path))
	{
		$image_path = "";
	}
	return $image_path;
}

function resize_image($file, $w, $h, $crop=FALSE) {
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }
    }
    $src = imagecreatefromjpeg($file);
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    return $dst;
}


//get actual image name(Prafull)
function get_actual_img_name($regnumber,$img_type)
{
 	$CI = & get_instance();
	$image_name = "";
	$mr_query = $CI->db->query("SELECT reg_no,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,image_path FROM member_registration WHERE regnumber = '".$regnumber."' AND isactive = '1' AND isdeleted = 0");
	if ($mr_query->num_rows() > 0)
	{
	   $mr_row = $mr_query->row();
	   $image_path = $mr_row->image_path;
	   
	   if ($image_path)
	   {
		   if ($img_type == "pr")
		   {
				if($mr_row->idproofphoto=='')
				{ 
					  $image_name = 'pr_'.$mr_row->reg_no.".jpg";
					  $image_path = 'uploads'.$mr_row->image_path."idproof/".$image_name;
					  if(!file_exists($image_path))
					  {
						 $image_name = 'pr_'.$mr_row->reg_no.".jpeg";
						if(!file_exists($image_path = 'uploads'.$image_path."idproof/".$image_name))
						{
							$image_name = "";
						}
				  }
				}
				else
				{
					$image_name =$mr_row->idproofphoto;
				}
		     }
		   else if ($img_type == "p")
		   {
		    if($mr_row->scannedphoto=='')
			{  
			  $image_name = 'p_'.$mr_row->reg_no.".jpg";
			  $image_path = 'uploads'.$mr_row->image_path."photo/".$image_name;
			  if(!file_exists($image_path))
			  {
			  	$image_name = 'p_'.$mr_row->reg_no.".jpeg";
				if(!file_exists($image_path = 'uploads'.$image_path."photo/".$image_name))
				{
					$image_name = "";
				}
			  }
		   
			}
			else
			{
				$image_name =$mr_row->scannedphoto;
			}
		   }
		   else if ($img_type == "s")
		   {
			  if($mr_row->scannedsignaturephoto=='')
			{  
			  $image_name = 's_'.$mr_row->reg_no.".jpg";
			  $image_path = 'uploads'.$mr_row->image_path."signature/".$image_name;
			  if(!file_exists($image_path))
			  {
			  	$image_name = 's_'.$mr_row->reg_no.".jpeg";
				if(!file_exists($image_path = 'uploads'.$image_path."scansignature/".$image_name))
				{
					$image_name = "";
				}
			  }
			}
			else
			{
				$image_name =$mr_row->scannedsignaturephoto;
			}
		   }
		   else if ($img_type == "declaration")
			{
			  	if($mr_row->declaration=='')
				{  
					$image_name = 'declaration_'.$mr_row->reg_no.".jpg";
					$image_path = 'uploads'.$mr_row->image_path."declaration/".$image_name;
					
					if(!file_exists($image_path))
					{
						$image_name = 'declaration_'.$mr_row->reg_no.".jpeg";
						if(!file_exists($image_path = 'uploads'.$image_path."declaration/".$image_name))
						{
							$image_name = "";
						}
					}
				}
				else
				{
					$image_name =$mr_row->declaration;

				}
			}

	   }
	   else
	   {
		   if ($img_type == "pr" && $mr_row->idproofphoto!='')
		   {
			 //$image_name = 'pr_'.$regnumber.".jpg";
			 $image_name = $mr_row->idproofphoto;
		   }
		   else if ($img_type == "p" && $mr_row->scannedphoto!='')
		   {
		     //$image_name = 'p_'.$regnumber.".jpg";
			 $image_name =  $mr_row->scannedphoto;
		   }
		    else if ($img_type == "s" && $mr_row->scannedsignaturephoto!='')
		   {
			  //$image_name = 's_'.$regnumber.".jpg";
			  $image_name = $mr_row->scannedsignaturephoto;
		   }
		   else if ($img_type == "declaration" && $mr_row->declaration!='')
			{
			  //$image_name = 'declaration_'.$regnumber.".jpg";
			  $image_name = $mr_row->declaration;
			}
	   } 
	}
	return $image_name;
}

 	function sbiqueryapi($MerchantOrderNo = NULL)
	{
		$CI = & get_instance();
		if($MerchantOrderNo !=NULL)
		{
			$merchIdVal = $CI->config->item('sbi_merchIdVal');
			$AggregatorId = $CI->config->item('sbi_AggregatorId');
			$atrn  = "";
	
			$queryRequest  = $atrn."|".$merchIdVal."|".$MerchantOrderNo;
			
			//echo "<br><br> Webservice URL : ".$service_url = "https://test.sbiepay.com/payagg/orderStatusQuery/getOrderStatusQuery";
			$service_url = $CI->config->item('sbi_status_query_api');
			$post_param = "queryRequest=".$queryRequest."&aggregatorId=".$AggregatorId."&merchantId=".$merchIdVal;
	
			$ch = curl_init();       
			curl_setopt($ch,CURLOPT_URL,$service_url);                                                 
			curl_setopt($ch, CURLOPT_POST, true); 
			curl_setopt($ch, CURLOPT_POSTFIELDS,$post_param);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3); 			
			$result = curl_exec($ch);
			curl_close($ch);
			
			if($result)
			{
				$response_array = explode("|", $result);
				
				return $response_array;
			}
			else
			{
				return 0;
			}
		}
		else
		{
			return 0;
		}
		//print_r($response_array);
		//var_dump($result);   
	}
	
function get_exam_name($exam_code,$exam_period){
	$CI = & get_instance();	
	$period = array('701','702','703','704','705','706','707','708','709','710','711','712');
	if(in_array($exam_period,$period)){
		
		$get_exam_name = $CI->db->query("SELECT description FROM exam_master WHERE exam_code = '".$exam_code."' ");
		$get_exam_name = $get_exam_name->row();
		$exam_name = $get_exam_name->description; 
		
	}else{
		
		$get_exam_name = $CI->db->query("SELECT description FROM exam_master WHERE exam_code = '".$exam_code."' ");
		$get_exam_name = $get_exam_name->row();
		$exam_name = preg_replace("/\([^)]+\)/","",$get_exam_name->description); 
		
	}	
	return $exam_name;
	
}

/* Bulk Module */
function log_bulk_admin($log_title, $log_message = "")
{
    $CI = & get_instance();
    $CI->load->model('Log_model');
    $CI->Log_model->create_bulk_adminlog($log_title, $log_message);
}

//get dra member image name(Tejasvi-10-09-2018)
function get_img_name_dra($regnumber,$img_type)
{
	$CI = & get_instance();
	$image_name = "";
	$CI->db->select('registration_no,scannedphoto,scannedsignaturephoto,image_path');
	$CI->db->from('dra_members');
	$CI->db->where("regnumber = '".$regnumber."'");
	$rs = $CI->db->get();
	$mr_query_rs = $rs->result();
	
	
    //echo $CI->db->last_query();
  
	if($mr_query_rs)
	{
	   $old_image_path = 'uploads'.$mr_query_rs[0]->image_path;
	   $new_image_path = 'uploads/iibfdra/';

	   if($img_type == "p")
	   {

	   		if($mr_query_rs[0]->scannedphoto=='')
	   		{
	   			
	   			if(file_exists($old_image_path . "photo/p_" . $mr_query_rs[0]->registration_no.'.jpg'))
						{
							$image_name = $old_image_path . "photo/p_" . $mr_query_rs[0]->registration_no.'.jpg';
						}

	   		}
	   		else
			{
				//echo $new_image_path;
				//echo $mr_query_rs[0]->scannedphoto;
				if(file_exists($new_image_path . $mr_query_rs[0]->scannedphoto))
						{

							$image_name = $new_image_path . $mr_query_rs[0]->scannedphoto;
						}
			}

	   }elseif($img_type == "s")
	   {

	   		if($mr_query_rs[0]->scannedsignaturephoto=='')
	   		{
	   			
	   			if(file_exists($old_image_path . "signature/s_" . $mr_query_rs[0]->registration_no.'.jpg'))
						{
							$image_name = $old_image_path . "signature/s_" . $mr_query_rs[0]->registration_no.'.jpg';
						}

	   		}
	   		else
			{

				if(file_exists($new_image_path . $mr_query_rs[0]->scannedsignaturephoto))
						{

							$image_name = $new_image_path . $mr_query_rs[0]->scannedsignaturephoto;
						}
			}

	   }

	   return $image_name;

    }
	
}
/* Get invoice path Module */
function Get_invoice_path($app_type='',$invoice_image='')
{	$invoice_path='';
	$invoice_info[0]['app_type']=$app_type;
	$invoice_info[0]['invoice_image']=$invoice_image;
    if(isset($invoice_info[0]['app_type']))
	{
			                   $app_type=$invoice_info[0]['app_type'];
									 $invoice_path='';
									 if($app_type=='R')
									 {
										 $invoice_path='uploads/reginvoice/user';
									 }
									 elseif($app_type=='O')
									 {
										 $invoice_path='uploads/examinvoice/user';
									 }
									 elseif($app_type=='I')
									 {
										 $invoice_path='uploads/examinvoice/user';
									 }
									 elseif($app_type=='D')
									 {
										
										 $invoice_path='uploads/dupicardinvoice/user';
									 }elseif($app_type=='C')
									 {
										
										 $invoice_path='uploads/dupcertinvoice/user';
									 }elseif($app_type=='N')
									 {
										
										 $invoice_path='uploads/renewal_invoice/user';
									 }
									 elseif($app_type=='V')
									 {
										
										 $invoice_path='uploads/vision_invoice/user';
									 }
									 elseif($app_type=='P')
									 {
										
										 $invoice_path='uploads/cpdinvoice/user';
									 }
									 elseif($app_type=='B')
									 {
										
										 $invoice_path='uploads/bnqinvoice/user';
									 }
									 elseif($app_type=='DJ')
									 {
										
										 $invoice_path='uploads/debftojaiib/user';
									 }
									 elseif($app_type=='T')
									 {//blended
									 
										if (strpos($invoice_info[0]['invoice_image'], 'CO') !== false) {
										$invoice_path='uploads/blended_invoice/user/CO';
										}elseif(strpos($invoice_info[0]['invoice_image'], 'NZ') !== false)																					                                        {
										$invoice_path='uploads/blended_invoice/user/NZ';
										}
										elseif(strpos($invoice_info[0]['invoice_image'], 'SZ') !== false)																					                                        {
										$invoice_path='uploads/blended_invoice/user/SZ';
										}
										elseif(strpos($invoice_info[0]['invoice_image'], 'EZ') !== false)																					                                        {
										$invoice_path='uploads/blended_invoice/user/EZ';
										}
										
										
										 
									 }
									 elseif($app_type=='E')
									 {
										if (strpos($invoice_info[0]['invoice_image'], 'CO') !== false) {
										$invoice_path='uploads/contact_classes_invoice/user/CO';
										}elseif(strpos($invoice_info[0]['invoice_image'], 'NZ') !== false)																					                                        {
										$invoice_path='uploads/contact_classes_invoice/user/NZ';
										}
										elseif(strpos($invoice_info[0]['invoice_image'], 'SZ') !== false)																					                                        {
										$invoice_path='uploads/contact_classes_invoice/user/SZ';
										}
										elseif(strpos($invoice_info[0]['invoice_image'], 'EZ') !== false)																					                                        {
										$invoice_path='uploads/contact_classes_invoice/user/EZ';
										}
									
								    }
									elseif($app_type=='F')
									{
										
										 $invoice_path='uploads/finquestinvoice/user';
									 }elseif($app_type=='A')
									 {
										
										 $invoice_path='uploads/drainvoice/user';
									 }
									 elseif($app_type=='S')
									 {
										
										 $invoice_path='-';
									 }
									 elseif($app_type=='K')
									 {
										
										 $invoice_path='uploads/examinvoice/user/';
									 }
									  elseif($app_type=='L')
									 {
										
										 $invoice_path='uploads/examinvoice/user/';
									 }
									 elseif($app_type=='M')
									 {
										
										 $invoice_path='uploads/ampinvoice/user';
									 }
									  elseif($app_type=='X')
									 {
										
										 $invoice_path='uploads/XLRIinvoice/user';
									 }elseif($app_type=='J')
									 {
										
										 $invoice_path='uploads/JBIMSinvoice/user';
									 }
							
							
							if($invoice_path!='')
							{
							  return $invoice_path=$invoice_path.'/'.$invoice_info[0]['invoice_image'];
							}else
							{
							 return $invoice_path;
							}		
								
						 }
	 else
	 {
	 	return $invoice_path;
	 }
						 
}
### check GST amount is paid or pending
function check_GST($member_num)
{
    $CI = & get_instance();
    $val=0;
	$getstate=$CI->master_model->getRecords('gst_recovery_master',array('member_no'=>$member_num),'pay_status');
   if(count($getstate) > 0)
   {
		foreach($getstate as $row)
		{
			if($row['pay_status']==2)
			{
				$val=$row['pay_status'];	
			}
		}
	}
   return $val;
}


function get_ip_address() // GET IP ADDRESS
{
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



function filterData(&$value, $key = null) 
{
  $value = preg_replace('/[\n\r\t]+/', '', $value); // Remove \n and \r and \t
  $value = trim($value); // Trim spaces from beginning and end
}

/* End of file general_helper.php */
/* Location: ./application/helpers/general_helper.php */