<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2025-07-17 10:43:22 --> Query error: Unknown column 'proforma_inv_no' in 'field list' - Invalid query: SELECT `bulk_payment_transaction`.`id`, `bulk_payment_transaction`.`exam_code`, `bulk_payment_transaction`.`exam_period`, `bulk_payment_transaction`.`receipt_no`, `proforma_inv_no`, `bulk_payment_transaction`.`status`, `bulk_payment_transaction`.`transaction_no`, `exam_invoice`.`created_on` as `exam_inv_date`, `pay_count` AS `member_count`, `amount`
FROM `bulk_payment_transaction`
LEFT JOIN `exam_invoice` ON `exam_invoice`.`pay_txn_id` = `bulk_payment_transaction`.`id`
LEFT JOIN `bulk_accerdited_master` ON `bulk_accerdited_master`.`institute_code` = `bulk_payment_transaction`.`inst_code`
LEFT JOIN `exam_master` ON `exam_master`.`exam_code` = `bulk_payment_transaction`.`exam_code`
WHERE `gateway` = "1"
AND `bulk_payment_transaction`.`exam_code` = "420"
AND `exam_invoice`.`app_type` = "Z"
AND `bulk_payment_transaction`.`inst_code` = "10092"
AND `bulk_payment_transaction`.`exam_code` != '997'
ORDER BY `created_date` DESC
 LIMIT 10
ERROR - 2025-07-17 10:45:23 --> Severity: Warning --> mdecrypt_generic(): An empty string was passed /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 106
ERROR - 2025-07-17 10:55:06 --> Query error: Unknown column 'proforma_inv_no' in 'field list' - Invalid query: SELECT `bulk_payment_transaction`.`id`, `bulk_payment_transaction`.`exam_code`, `bulk_payment_transaction`.`exam_period`, `bulk_payment_transaction`.`receipt_no`, `proforma_inv_no`, `bulk_payment_transaction`.`status`, `bulk_payment_transaction`.`transaction_no`, `exam_invoice`.`created_on` as `exam_inv_date`, `pay_count` AS `member_count`, `amount`
FROM `bulk_payment_transaction`
LEFT JOIN `exam_invoice` ON `exam_invoice`.`pay_txn_id` = `bulk_payment_transaction`.`id`
LEFT JOIN `bulk_accerdited_master` ON `bulk_accerdited_master`.`institute_code` = `bulk_payment_transaction`.`inst_code`
LEFT JOIN `exam_master` ON `exam_master`.`exam_code` = `bulk_payment_transaction`.`exam_code`
WHERE `gateway` = "1"
AND `bulk_payment_transaction`.`exam_code` = "420"
AND `exam_invoice`.`app_type` = "Z"
AND `bulk_payment_transaction`.`inst_code` = "10092"
AND `bulk_payment_transaction`.`exam_code` != '997'
ORDER BY `created_date` DESC
 LIMIT 10
ERROR - 2025-07-17 11:13:02 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 11:13:06 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 11:13:06 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 11:13:06 --> Query error: Unknown column 'proforma_inv_no' in 'field list' - Invalid query: SELECT `bulk_payment_transaction`.`id`, `bulk_payment_transaction`.`exam_code`, `bulk_payment_transaction`.`exam_period`, `bulk_payment_transaction`.`receipt_no`, `proforma_inv_no`, `bulk_payment_transaction`.`status`, `bulk_payment_transaction`.`transaction_no`, `exam_invoice`.`created_on` as `exam_inv_date`, `pay_count` AS `member_count`, `amount`
FROM `bulk_payment_transaction`
LEFT JOIN `exam_invoice` ON `exam_invoice`.`pay_txn_id` = `bulk_payment_transaction`.`id`
LEFT JOIN `bulk_accerdited_master` ON `bulk_accerdited_master`.`institute_code` = `bulk_payment_transaction`.`inst_code`
LEFT JOIN `exam_master` ON `exam_master`.`exam_code` = `bulk_payment_transaction`.`exam_code`
WHERE `gateway` = "1"
AND `bulk_payment_transaction`.`exam_code` = "420"
AND `exam_invoice`.`app_type` = "Z"
AND `bulk_payment_transaction`.`inst_code` = "10092"
AND `bulk_payment_transaction`.`exam_code` != '997'
ORDER BY `created_date` DESC
 LIMIT 10
ERROR - 2025-07-17 11:16:28 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 11:16:28 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 11:16:33 --> Query error: Column 'userid' cannot be null - Invalid query: INSERT INTO `dra_userlogs` (`title`, `description`, `userid`, `ip`) VALUES ('Send data to training_make_online_payment_page View', 'a:8:{s:22:\"inst_registration_info\";a:1:{i:0;a:30:{s:2:\"id\";s:3:\"158\";s:14:\"institute_code\";s:5:\"10092\";s:14:\"institute_name\";s:13:\"RK UNIVERSITY\";s:17:\"institute_secname\";s:0:\"\";s:8:\"address1\";s:13:\"RK University\";s:8:\"address2\";s:17:\"Bhavnagar Highway\";s:8:\"address3\";s:12:\"Kasturbadham\";s:8:\"address4\";s:6:\"Rajkot\";s:8:\"address5\";s:7:\"Gujarat\";s:8:\"address6\";s:6:\"India \";s:8:\"ste_code\";s:3:\"GUJ\";s:8:\"pin_code\";s:6:\"360020\";s:5:\"phone\";s:0:\"\";s:6:\"mobile\";s:10:\"9099063666\";s:5:\"email\";s:22:\"hemali.tanna@rku.ac.in\";s:8:\"a_i_flag\";s:0:\"\";s:9:\"zone_code\";s:0:\"\";s:10:\"coord_name\";s:16:\"Dr. Hemali Tanna\";s:11:\"designation\";s:0:\"\";s:13:\"category_code\";s:0:\"\";s:8:\"gstin_no\";s:15:\"24AACTS8845F2Z9\";s:10:\"created_by\";s:1:\"0\";s:10:\"created_on\";s:19:\"2025-01-29 12:46:41\";s:11:\"modified_by\";s:1:\"0\";s:11:\"modified_on\";s:19:\"2025-07-10 15:37:37\";s:17:\"accerdited_delete\";s:1:\"0\";s:8:\"password\";s:24:\"qaR4HcGLeJl6hlwYowuLcw==\";s:16:\"decrypt_password\";s:8:\"YhfF64c3\";s:8:\"is_admin\";s:2:\"no\";s:7:\"mou_flg\";s:1:\"1\";}}s:11:\"regNosToPay\";s:12:\"ODU0NTk2Nw==\";s:8:\"instdata\";a:30:{s:2:\"id\";s:3:\"158\";s:14:\"institute_code\";s:5:\"10092\";s:14:\"institute_name\";s:13:\"RK UNIVERSITY\";s:17:\"institute_secname\";s:0:\"\";s:8:\"address1\";s:13:\"RK University\";s:8:\"address2\";s:17:\"Bhavnagar Highway\";s:8:\"address3\";s:12:\"Kasturbadham\";s:8:\"address4\";s:6:\"Rajkot\";s:8:\"address5\";s:7:\"Gujarat\";s:8:\"address6\";s:6:\"India \";s:8:\"ste_code\";s:3:\"GUJ\";s:8:\"pin_code\";s:6:\"360020\";s:5:\"phone\";s:0:\"\";s:6:\"mobile\";s:10:\"9099063666\";s:5:\"email\";s:22:\"hemali.tanna@rku.ac.in\";s:8:\"a_i_flag\";s:0:\"\";s:9:\"zone_code\";s:0:\"\";s:10:\"coord_name\";s:16:\"Dr. Hemali Tanna\";s:11:\"designation\";s:0:\"\";s:13:\"category_code\";s:0:\"\";s:8:\"gstin_no\";s:15:\"24AACTS8845F2Z9\";s:10:\"created_by\";s:1:\"0\";s:10:\"created_on\";s:19:\"2025-01-29 12:46:41\";s:11:\"modified_by\";s:1:\"0\";s:11:\"modified_on\";s:19:\"2025-07-10 15:37:37\";s:17:\"accerdited_delete\";s:1:\"0\";s:8:\"password\";s:24:\"qaR4HcGLeJl6hlwYowuLcw==\";s:16:\"decrypt_password\";s:8:\"YhfF64c3\";s:8:\"is_admin\";s:2:\"no\";s:7:\"mou_flg\";s:1:\"1\";}s:7:\"tot_fee\";s:12:\"Mzc3Ni4wMA==\";s:9:\"exam_code\";s:4:\"NDIw\";s:11:\"exam_period\";s:4:\"MTI1\";s:10:\"payTransId\";s:4:\"Nzk=\";s:14:\"middle_content\";s:46:\"bulk/transaction/bulk_make_online_payment_page\";}', NULL, '10.11.38.105')
ERROR - 2025-07-17 11:16:33 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 12:34:40 --> Query error: Table 'supp0rttest_iibf_staging.profilelogs' doesn't exist - Invalid query: INSERT INTO `profilelogs` (`title`, `description`, `type`, `regid`, `regnumber`, `editedby`, `editedbyid`, `date`, `ip`) VALUES ('Profile updated successfully', 'PHOTO || SIGNATURE || PROOF || DECLARATION ', 'image', '9468', '510013544', 'User', '9468', '2025-07-17 12:34:40', '10.11.38.105')
ERROR - 2025-07-17 12:35:23 --> Could not find the language line "form_validation_file_size_max"
ERROR - 2025-07-17 12:35:34 --> Query error: Table 'supp0rttest_iibf_staging.profilelogs' doesn't exist - Invalid query: INSERT INTO `profilelogs` (`title`, `description`, `type`, `regid`, `regnumber`, `editedby`, `editedbyid`, `date`, `ip`) VALUES ('Profile updated successfully', 'PHOTO || SIGNATURE || PROOF ', 'image', '8545972', '700034066', 'User', '8545972', '2025-07-17 12:35:34', '10.11.38.105')
ERROR - 2025-07-17 12:38:22 --> Query error: Table 'supp0rttest_iibf_staging.profilelogs' doesn't exist - Invalid query: INSERT INTO `profilelogs` (`title`, `description`, `type`, `regid`, `regnumber`, `editedby`, `editedbyid`, `date`, `ip`) VALUES ('Profile updated successfully', 'PHOTO || SIGNATURE || PROOF ', 'image', '8545974', '75178', 'User', '8545974', '2025-07-17 12:38:22', '10.11.38.105')
ERROR - 2025-07-17 12:39:12 --> Query error: Table 'supp0rttest_iibf_staging.profilelogs' doesn't exist - Invalid query: INSERT INTO `profilelogs` (`title`, `description`, `type`, `regid`, `regnumber`, `editedby`, `editedbyid`, `date`, `ip`) VALUES ('Profile updated successfully', 'PHOTO || SIGNATURE || PROOF || DECLARATION ', 'image', '8676', '510013139', 'User', '8676', '2025-07-17 12:39:12', '10.11.38.105')
ERROR - 2025-07-17 14:41:34 --> Severity: Parsing Error --> syntax error, unexpected 'elseif' (T_ELSEIF) /home/supp0rttest/public_html/staging/application/controllers/DraRegister.php 484
ERROR - 2025-07-17 14:41:47 --> Severity: Parsing Error --> syntax error, unexpected 'elseif' (T_ELSEIF) /home/supp0rttest/public_html/staging/application/controllers/DraRegister.php 484
ERROR - 2025-07-17 14:42:07 --> Severity: Parsing Error --> syntax error, unexpected 'elseif' (T_ELSEIF) /home/supp0rttest/public_html/staging/application/controllers/DraRegister.php 484
ERROR - 2025-07-17 14:42:12 --> Severity: Parsing Error --> syntax error, unexpected 'elseif' (T_ELSEIF) /home/supp0rttest/public_html/staging/application/controllers/DraRegister.php 484
ERROR - 2025-07-17 14:42:15 --> Severity: Parsing Error --> syntax error, unexpected 'elseif' (T_ELSEIF) /home/supp0rttest/public_html/staging/application/controllers/DraRegister.php 484
ERROR - 2025-07-17 14:42:32 --> Severity: Parsing Error --> syntax error, unexpected 'elseif' (T_ELSEIF) /home/supp0rttest/public_html/staging/application/controllers/DraRegister.php 484
ERROR - 2025-07-17 14:42:37 --> Severity: Parsing Error --> syntax error, unexpected 'elseif' (T_ELSEIF) /home/supp0rttest/public_html/staging/application/controllers/DraRegister.php 484
ERROR - 2025-07-17 14:42:44 --> Severity: Parsing Error --> syntax error, unexpected 'elseif' (T_ELSEIF) /home/supp0rttest/public_html/staging/application/controllers/DraRegister.php 484
ERROR - 2025-07-17 14:42:46 --> Severity: Parsing Error --> syntax error, unexpected 'elseif' (T_ELSEIF) /home/supp0rttest/public_html/staging/application/controllers/DraRegister.php 484
ERROR - 2025-07-17 14:42:48 --> Severity: Parsing Error --> syntax error, unexpected 'elseif' (T_ELSEIF) /home/supp0rttest/public_html/staging/application/controllers/DraRegister.php 484
ERROR - 2025-07-17 14:42:57 --> Severity: Parsing Error --> syntax error, unexpected 'elseif' (T_ELSEIF) /home/supp0rttest/public_html/staging/application/controllers/DraRegister.php 484
ERROR - 2025-07-17 14:42:57 --> Severity: Parsing Error --> syntax error, unexpected 'elseif' (T_ELSEIF) /home/supp0rttest/public_html/staging/application/controllers/DraRegister.php 484
ERROR - 2025-07-17 14:42:59 --> Severity: Parsing Error --> syntax error, unexpected 'elseif' (T_ELSEIF) /home/supp0rttest/public_html/staging/application/controllers/DraRegister.php 484
ERROR - 2025-07-17 14:43:10 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 14:43:16 --> Severity: Parsing Error --> syntax error, unexpected 'elseif' (T_ELSEIF) /home/supp0rttest/public_html/staging/application/controllers/DraRegister.php 484
ERROR - 2025-07-17 14:50:15 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-17 14:50:21 --> Query error: Table 'supp0rttest_iibf_staging.dra_agency_adminlogs' doesn't exist - Invalid query: INSERT INTO `dra_agency_adminlogs` (`title`, `description`, `userid`, `date`, `ip`) VALUES ('DRA Admin Approved Agency Center', 'a:7:{s:13:\"center_status\";s:1:\"A\";s:7:\"user_id\";s:1:\"1\";s:11:\"modified_on\";s:19:\"2025-07-17 14:50:21\";s:16:\"date_of_approved\";s:19:\"2025-07-17 14:50:21\";s:10:\"pay_status\";s:1:\"1\";s:16:\"payment_required\";s:15:\"without_payment\";s:10:\"updated_by\";s:1:\"1\";}', '1', '2025-07-17 14:50:21', '10.11.38.105')
ERROR - 2025-07-17 14:50:22 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-17 14:52:55 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-17 15:04:54 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-17 15:04:58 --> Query error: Table 'supp0rttest_iibf_staging.dra_agency_adminlogs' doesn't exist - Invalid query: INSERT INTO `dra_agency_adminlogs` (`title`, `description`, `userid`, `date`, `ip`) VALUES ('DRA Admin Approved Agency Center', 'a:7:{s:13:\"center_status\";s:1:\"A\";s:7:\"user_id\";s:1:\"1\";s:11:\"modified_on\";s:19:\"2025-07-17 15:04:58\";s:16:\"date_of_approved\";s:19:\"2025-07-17 15:04:58\";s:10:\"pay_status\";s:1:\"1\";s:16:\"payment_required\";s:15:\"without_payment\";s:10:\"updated_by\";s:1:\"1\";}', '1', '2025-07-17 15:04:58', '10.11.38.105')
ERROR - 2025-07-17 15:04:58 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-17 15:10:24 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-17 15:10:45 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-17 15:10:57 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-17 15:13:31 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-17 15:14:56 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-17 15:15:03 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-17 15:15:19 --> Query error: Table 'supp0rttest_iibf_staging.dra_agency_adminlogs' doesn't exist - Invalid query: INSERT INTO `dra_agency_adminlogs` (`title`, `description`, `userid`, `date`, `ip`) VALUES ('DRA Admin Approved Agency Center', 'a:7:{s:13:\"center_status\";s:1:\"A\";s:7:\"user_id\";s:1:\"1\";s:11:\"modified_on\";s:19:\"2025-07-17 15:15:19\";s:16:\"date_of_approved\";s:19:\"2025-07-17 15:15:19\";s:10:\"pay_status\";s:1:\"2\";s:16:\"payment_required\";s:12:\"with_payment\";s:10:\"updated_by\";s:1:\"1\";}', '1', '2025-07-17 15:15:19', '10.11.38.105')
ERROR - 2025-07-17 15:15:19 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-17 15:20:21 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-17 15:20:24 --> Query error: Table 'supp0rttest_iibf_staging.dra_agency_adminlogs' doesn't exist - Invalid query: INSERT INTO `dra_agency_adminlogs` (`title`, `description`, `userid`, `date`, `ip`) VALUES ('DRA Admin Approved Agency Center', 'a:7:{s:13:\"center_status\";s:1:\"A\";s:7:\"user_id\";s:1:\"1\";s:11:\"modified_on\";s:19:\"2025-07-17 15:20:24\";s:16:\"date_of_approved\";s:19:\"2025-07-17 15:20:24\";s:10:\"pay_status\";s:1:\"2\";s:16:\"payment_required\";s:12:\"with_payment\";s:10:\"updated_by\";s:1:\"1\";}', '1', '2025-07-17 15:20:24', '10.11.38.105')
ERROR - 2025-07-17 15:20:24 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-17 15:20:59 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-17 15:21:07 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-17 15:21:15 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-17 15:21:20 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-17 15:21:31 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-17 15:21:41 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-17 15:23:18 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-17 15:23:18 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-17 15:23:18 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-17 15:23:18 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-17 16:09:17 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-17 16:09:20 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-17 16:10:25 --> Severity: Notice --> Undefined index: pg_other_details /home/supp0rttest/public_html/staging/application/helpers/dra_reg_invoice_helper.php 66
ERROR - 2025-07-17 16:10:25 --> Severity: Notice --> Undefined offset: 4 /home/supp0rttest/public_html/staging/application/helpers/dra_reg_invoice_helper.php 69
ERROR - 2025-07-17 16:10:25 --> Severity: Notice --> Undefined index: ref_id /home/supp0rttest/public_html/staging/application/helpers/dra_reg_invoice_helper.php 74
ERROR - 2025-07-17 16:16:40 --> 404 Page Not Found: DraRegister/favicon.ico
ERROR - 2025-07-17 16:21:03 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-17 16:21:38 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-17 16:22:56 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:23:04 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:23:06 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:23:06 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:23:08 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:23:14 --> Query error: Column 'regnumber' in field list is ambiguous - Invalid query: SELECT `regnumber`, `free_paid_flag`
FROM `member_registration`
JOIN `member_exam` ON `member_exam`.`regnumber` = `member_registration`.`regnumber`
WHERE `member_exam`.`regnumber` = '8545967'
AND `member_exam`.`exam_code` = '420'
AND `member_exam`.`exam_period` = '125'
ERROR - 2025-07-17 16:23:14 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 310
ERROR - 2025-07-17 16:24:12 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:24:12 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:24:32 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:24:32 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:24:36 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:24:36 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:25:14 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:25:14 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:25:17 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:25:48 --> Query error: Unknown column 'free_paid_flag' in 'field list' - Invalid query: SELECT `member_registration`.`regnumber`, `free_paid_flag`
FROM `member_registration`
JOIN `member_exam` ON `member_exam`.`regnumber` = `member_registration`.`regnumber`
WHERE `member_exam`.`regnumber` = '8545967'
AND `member_exam`.`exam_code` = '420'
AND `member_exam`.`exam_period` = '125'
ERROR - 2025-07-17 16:25:48 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 310
ERROR - 2025-07-17 16:27:53 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:27:53 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:29:17 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:29:17 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:42:06 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:42:06 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:44:49 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:44:49 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:44:59 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:45:07 --> Severity: Error --> Call to undefined function daye() /home/supp0rttest/public_html/staging/application/controllers/bulk/BulkTransaction.php 1316
ERROR - 2025-07-17 16:45:41 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:45:41 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:46:05 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:46:05 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:46:06 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:46:06 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:49:24 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:49:24 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:51:36 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:51:36 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:52:03 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:52:03 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:52:05 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:52:10 --> Query error: Unknown column 'regid' in 'field list' - Invalid query: SELECT `regid`, `free_paid_flg`
FROM `member_exam`
WHERE `regid` = '8545967'
AND `exam_code` = '420'
AND `exam_period` = '125'
ERROR - 2025-07-17 16:52:10 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 310
ERROR - 2025-07-17 16:53:18 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:53:18 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:53:27 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:53:27 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:53:30 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:57:56 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:57:56 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:58:07 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:58:07 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:58:10 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:58:10 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:58:22 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:58:22 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 16:58:24 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:00:24 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:00:24 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:00:34 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:00:34 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:00:35 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:01:45 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:01:45 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:02:02 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:02:02 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:02:03 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:06:02 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:06:02 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:06:15 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:06:16 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:06:17 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:13:24 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:13:24 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:13:35 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:13:35 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:13:36 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:14:25 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:14:25 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:14:34 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:14:34 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:14:36 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:32:04 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:32:04 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:32:15 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:32:15 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:32:17 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:32:20 --> Query error: Duplicate entry '50077455' for key 'PRIMARY' - Invalid query: INSERT INTO `exam_invoice` (`invoice_id`, `exam_code`, `exam_period`, `center_code`, `center_name`, `state_of_center`, `invoice_flag`, `member_no`, `pay_txn_id`, `receipt_no`, `transaction_no`, `gstin_no`, `service_code`, `qty`, `fresh_fee`, `rep_fee`, `fresh_count`, `rep_count`, `cess`, `institute_code`, `institute_name`, `state_code`, `state_name`, `invoice_no`, `invoice_image`, `fee_amt`, `total_el_amount`, `total_el_base_amount`, `total_el_gst_amount`, `cgst_rate`, `cgst_amt`, `sgst_rate`, `sgst_amt`, `cs_total`, `igst_rate`, `igst_amt`, `igst_total`, `disc_rate`, `disc_amt`, `tds_amt`, `date_of_invoice`, `created_on`, `modified_on`, `tax_type`, `app_type`, `exempt`) VALUES ('50077455', '420', '125', '0', '', '', NULL, '', '79', '79', 'TEMP-UTR-IIBF', '24AACTS8845F2Z9', '999294', '1', '0.00', '0.00', '0', '0', '0.00', '10092', 'RK UNIVERSITY', '24', 'GUJARAT', '', '', '3200.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '18.00', '576.00', '3776.00', '15.00', '500.00', '0.00', '0000-00-00 00:00:00', '2025-07-14 14:56:51', '0000-00-00 00:00:00', 'Inter', 'Z', 'NE')
ERROR - 2025-07-17 17:32:20 --> Severity: Error --> Call to a member function init_payment_request() on null /home/supp0rttest/public_html/staging/application/controllers/bulk/BulkTransaction.php 1608
ERROR - 2025-07-17 17:33:11 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:33:11 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:33:20 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:33:20 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:33:21 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:33:24 --> Severity: Error --> Call to a member function init_payment_request() on null /home/supp0rttest/public_html/staging/application/controllers/bulk/BulkTransaction.php 1608
ERROR - 2025-07-17 17:35:18 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:35:18 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:35:28 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:35:28 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:35:30 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-17 17:35:34 --> 404 Page Not Found: bulk/BulkTransaction/favicon.ico
ERROR - 2025-07-17 18:37:54 --> 404 Page Not Found: Assets/js
