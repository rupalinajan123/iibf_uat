<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2025-11-24 10:54:00 --> 404 Page Not Found: Assets/admin
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2025-11-24 10:54:00 --> 404 Page Not Found: Assets/admin
ERROR - 2025-11-24 10:54:00 --> 404 Page Not Found: Assets/admin
ERROR - 2025-11-24 10:54:38 --> 404 Page Not Found: Assets/admin
ERROR - 2025-11-24 10:54:38 --> 404 Page Not Found: Assets/admin
ERROR - 2025-11-24 12:02:21 --> Severity: Warning --> mcrypt_generic_init(): Key size is 0 /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 83
ERROR - 2025-11-24 12:02:21 --> Severity: Warning --> mcrypt_generic_init(): Key length incorrect /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 83
ERROR - 2025-11-24 12:02:22 --> Severity: Warning --> mcrypt_generic(): 8 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 84
ERROR - 2025-11-24 12:02:22 --> Severity: Warning --> mcrypt_generic_deinit(): 8 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 87
ERROR - 2025-11-24 12:02:22 --> Severity: Warning --> mcrypt_module_close(): 8 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 88
ERROR - 2025-11-24 12:06:50 --> Severity: Warning --> mcrypt_generic_init(): Key size is 0 /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 83
ERROR - 2025-11-24 12:06:50 --> Severity: Warning --> mcrypt_generic_init(): Key length incorrect /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 83
ERROR - 2025-11-24 12:06:50 --> Severity: Warning --> mcrypt_generic(): 8 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 84
ERROR - 2025-11-24 12:06:50 --> Severity: Warning --> mcrypt_generic_deinit(): 8 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 87
ERROR - 2025-11-24 12:06:50 --> Severity: Warning --> mcrypt_module_close(): 8 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 88
ERROR - 2025-11-24 12:20:58 --> Query error: Unknown column 'center_master.center_name' in 'field list' - Invalid query: SELECT `ncvet_member_exam`.`exam_code`, `ncvet_member_exam`.`exam_mode`, `ncvet_member_exam`.`exam_medium`, `ncvet_member_exam`.`exam_period`, `center_master`.`center_name`, `ncvet_member_exam`.`exam_center_code`, `ncvet_exam_master`.`description`, `ncvet_misc_master`.`exam_month`, `ncvet_member_exam`.`state_place_of_work`, `ncvet_member_exam`.`place_of_work`, `ncvet_member_exam`.`pin_code_place_of_work`, `ncvet_member_exam`.`examination_date`, `ncvet_member_exam`.`elected_sub_code`
FROM `ncvet_member_exam`
JOIN `ncvet_center_master` ON `ncvet_center_master`.`center_code`=`ncvet_member_exam`.`exam_center_code` AND `ncvet_center_master`.`exam_name`=`ncvet_member_exam`.`exam_code` AND `ncvet_center_master`.`exam_period`=`ncvet_member_exam`.`exam_period`
JOIN `ncvet_exam_master` ON `ncvet_exam_master`.`exam_code`=`ncvet_member_exam`.`exam_code`
JOIN `ncvet_misc_master` ON `ncvet_misc_master`.`exam_code`=`ncvet_member_exam`.`exam_code` AND `ncvet_misc_master`.`exam_period`=`ncvet_member_exam`.`exam_period`
JOIN `ncvet_exam_activation_master` ON `ncvet_exam_activation_master`.`exam_code`=`ncvet_member_exam`.`exam_code` AND `ncvet_exam_activation_master`.`exam_period`=`ncvet_member_exam`.`exam_period`
WHERE `regnumber` = '9000052'
AND `ncvet_member_exam`.`id` = '12'
ERROR - 2025-11-24 12:20:58 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 309
ERROR - 2025-11-24 14:35:17 --> Could not find the language line "form_validation_check_captcha_userlogin"
ERROR - 2025-11-24 14:35:35 --> Could not find the language line "form_validation_check_captcha_userlogin"
ERROR - 2025-11-24 14:52:50 --> 404 Page Not Found: Assets/js
ERROR - 2025-11-24 14:52:50 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-11-24 15:09:46 --> 404 Page Not Found: Assets/js
ERROR - 2025-11-24 15:09:46 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-11-24 16:01:09 --> Query error: Unknown column 'ncvet_member_exam.examination_date' in 'field list' - Invalid query: SELECT `ncvet_member_exam`.`exam_code`, `ncvet_member_exam`.`exam_mode`, `ncvet_member_exam`.`exam_medium`, `ncvet_member_exam`.`exam_period`, `ncvet_center_master`.`center_name`, `ncvet_member_exam`.`exam_center_code`, `ncvet_exam_master`.`description`, `ncvet_misc_master`.`exam_month`, `ncvet_member_exam`.`state_place_of_work`, `ncvet_member_exam`.`place_of_work`, `ncvet_member_exam`.`pin_code_place_of_work`, `ncvet_member_exam`.`examination_date`, `ncvet_member_exam`.`elected_sub_code`
FROM `ncvet_member_exam`
JOIN `ncvet_center_master` ON `ncvet_center_master`.`center_code`=`ncvet_member_exam`.`exam_center_code` AND `ncvet_center_master`.`exam_name`=`ncvet_member_exam`.`exam_code` AND `ncvet_center_master`.`exam_period`=`ncvet_member_exam`.`exam_period`
JOIN `ncvet_exam_master` ON `ncvet_exam_master`.`exam_code`=`ncvet_member_exam`.`exam_code`
JOIN `ncvet_misc_master` ON `ncvet_misc_master`.`exam_code`=`ncvet_member_exam`.`exam_code` AND `ncvet_misc_master`.`exam_period`=`ncvet_member_exam`.`exam_period`
JOIN `ncvet_exam_activation_master` ON `ncvet_exam_activation_master`.`exam_code`=`ncvet_member_exam`.`exam_code` AND `ncvet_exam_activation_master`.`exam_period`=`ncvet_member_exam`.`exam_period`
WHERE `regnumber` = '9000052'
AND `ncvet_member_exam`.`id` = '13'
ERROR - 2025-11-24 16:01:09 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 309
ERROR - 2025-11-24 16:23:17 --> 404 Page Not Found: Uploads/idproof
ERROR - 2025-11-24 16:23:17 --> 404 Page Not Found: Uploads/declaration
ERROR - 2025-11-24 16:23:17 --> 404 Page Not Found: Uploads/scansignature
ERROR - 2025-11-24 16:23:17 --> 404 Page Not Found: Uploads/photograph
ERROR - 2025-11-24 16:24:20 --> 404 Page Not Found: Uploads/scansignature
ERROR - 2025-11-24 16:24:20 --> 404 Page Not Found: Uploads/idproof
ERROR - 2025-11-24 16:24:20 --> 404 Page Not Found: Uploads/declaration
ERROR - 2025-11-24 16:31:12 --> Severity: Warning --> in_array() expects parameter 2 to be array, boolean given /home/supp0rttest/public_html/staging/application/controllers/admin/kyc/Kyc.php 1266
ERROR - 2025-11-24 16:31:42 --> Query error: Column 'mem_bank_bc_id_card' cannot be null - Invalid query: INSERT INTO `member_kyc` (`regnumber`, `mem_type`, `mem_name`, `mem_dob`, `mem_associate_inst`, `mem_photo`, `mem_sign`, `mem_proof`, `employee_proof`, `mem_declaration`, `mem_bank_bc_id_card`, `field_count`, `old_data`, `kyc_status`, `kyc_state`, `recommended_by`, `user_type`, `recommended_date`, `record_source`) VALUES ('510652931', 'O', '1', '1', '1', '1', '0', '0', '0', '0', NULL, 3, 'a:1:{i:0;a:14:{s:7:\"namesub\";s:3:\"Mr.\";s:9:\"firstname\";s:7:\"SHANKER\";s:10:\"middlename\";s:3:\"LAL\";s:8:\"lastname\";s:3:\"JAT\";s:11:\"dateofbirth\";s:10:\"1999-08-18\";s:19:\"associatedinstitute\";s:3:\"161\";s:12:\"scannedphoto\";s:16:\"p_510652931.jpeg\";s:21:\"scannedsignaturephoto\";s:15:\"s_510652931.jpg\";s:12:\"idproofphoto\";s:17:\"pr_510652931.jpeg\";s:11:\"declaration\";s:28:\"declaration_174079899424.jpg\";s:15:\"empidproofphoto\";N;s:6:\"excode\";s:1:\"0\";s:15:\"bank_bc_id_card\";N;s:18:\"date_of_commenc_bc\";N;}}', '0', '1', '126', 'recommender', '2025-11-24 16:31:42', 'New')
ERROR - 2025-11-24 17:00:33 --> 404 Page Not Found: ncvet/candidate/Applyexam/index
ERROR - 2025-11-24 17:13:04 --> Query error: Table 'supp0rttest_iibf_staging.ncvet_config_exam_invoice' doesn't exist - Invalid query: INSERT INTO `ncvet_config_exam_invoice` (`invoice_id`) VALUES ('71')
ERROR - 2025-11-24 17:13:04 --> Severity: Warning --> Missing argument 1 for Applyexam::acknowlodgement() /home/supp0rttest/public_html/staging/application/controllers/ncvet/candidate/Applyexam.php 748
ERROR - 2025-11-24 17:13:04 --> Query error: Unknown column 'ncvet_member_exam.note' in 'field list' - Invalid query: SELECT `ncvet_member_exam`.`id`, `ncvet_member_exam`.`regnumber`, `ncvet_member_exam`.`exam_code`, `ncvet_member_exam`.`exam_mode`, `ncvet_member_exam`.`exam_medium`, `ncvet_member_exam`.`exam_period`, `ncvet_member_exam`.`exam_center_code`, `ncvet_member_exam`.`exam_fee`, `ncvet_member_exam`.`note`, `ncvet_member_exam`.`pay_status`, `ncvet_exam_master`.`description`, `ncvet_misc_master`.`exam_month`, `ncvet_member_exam`.`place_of_work`, `ncvet_member_exam`.`state_place_of_work`, `ncvet_member_exam`.`pin_code_place_of_work`, `ncvet_member_exam`.`elected_sub_code`, `ncvet_exam_master`.`ebook_flag`
FROM `ncvet_member_exam`
JOIN `ncvet_misc_master` ON `ncvet_misc_master`.`exam_code`=`ncvet_member_exam`.`exam_code` AND `ncvet_misc_master`.`exam_period`=`ncvet_member_exam`.`exam_period`
JOIN `ncvet_exam_master` ON `ncvet_exam_master`.`exam_code`=`ncvet_member_exam`.`exam_code`
JOIN `ncvet_exam_activation_master` ON `ncvet_exam_activation_master`.`exam_code`=`ncvet_member_exam`.`exam_code`
WHERE `elg_mem_o` = 'Y'
AND `ncvet_misc_master`.`misc_delete` = '0'
AND '2025-11-24' BETWEEN `ncvet_exam_activation_master`.`exam_from_date` AND ncvet_exam_activation_master.exam_to_date
AND `ncvet_member_exam`.`id` = ''
AND `regnumber` = '9000052'
AND `pay_status` = '1'
ERROR - 2025-11-24 17:13:04 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 309
ERROR - 2025-11-24 17:28:23 --> 404 Page Not Found: Assets/js
ERROR - 2025-11-24 17:28:23 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-11-24 17:35:49 --> Query error: Unknown column 'ncvet_exam_master.ebook_flag' in 'field list' - Invalid query: SELECT `ncvet_member_exam`.`id`, `ncvet_member_exam`.`regnumber`, `ncvet_member_exam`.`exam_code`, `ncvet_member_exam`.`exam_mode`, `ncvet_member_exam`.`exam_medium`, `ncvet_member_exam`.`exam_period`, `ncvet_member_exam`.`exam_center_code`, `ncvet_member_exam`.`exam_fee`, `ncvet_member_exam`.`pay_status`, `ncvet_exam_master`.`description`, `ncvet_misc_master`.`exam_month`, `ncvet_member_exam`.`place_of_work`, `ncvet_member_exam`.`state_place_of_work`, `ncvet_member_exam`.`pin_code_place_of_work`, `ncvet_member_exam`.`elected_sub_code`, `ncvet_exam_master`.`ebook_flag`
FROM `ncvet_member_exam`
JOIN `ncvet_misc_master` ON `ncvet_misc_master`.`exam_code`=`ncvet_member_exam`.`exam_code` AND `ncvet_misc_master`.`exam_period`=`ncvet_member_exam`.`exam_period`
JOIN `ncvet_exam_master` ON `ncvet_exam_master`.`exam_code`=`ncvet_member_exam`.`exam_code`
JOIN `ncvet_exam_activation_master` ON `ncvet_exam_activation_master`.`exam_code`=`ncvet_member_exam`.`exam_code`
WHERE `elg_mem_o` = 'Y'
AND `ncvet_misc_master`.`misc_delete` = '0'
AND '2025-11-24' BETWEEN `ncvet_exam_activation_master`.`exam_from_date` AND ncvet_exam_activation_master.exam_to_date
AND `ncvet_member_exam`.`id` = '18'
AND `regnumber` = '9000052'
AND `pay_status` = '1'
ERROR - 2025-11-24 17:35:49 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 309
