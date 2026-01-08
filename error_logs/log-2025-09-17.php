<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2025-09-17 09:37:18 --> Query error: Disk full (/home/tmp/#sql_3701_0.MAI); waiting for someone to free some space... (errno: 28 "No space left on device") - Invalid query: SELECT *
FROM `exam_master`
JOIN `subject_master` ON `subject_master`.`exam_code`=`exam_master`.`exam_code`
JOIN `center_master` ON `center_master`.`exam_name`=`exam_master`.`exam_code`
JOIN `exam_activation_master` ON `exam_activation_master`.`exam_code`=`exam_master`.`exam_code`
JOIN `medium_master` ON `medium_master`.`exam_code`=`exam_activation_master`.`exam_code` AND `medium_master`.`exam_period`=`exam_activation_master`. `exam_period`
JOIN `misc_master` ON `misc_master`.`exam_code`=`exam_master`.`exam_code` AND `misc_master`.`exam_period`=`exam_activation_master`.`exam_period` AND `misc_master`.`exam_period`=`center_master`.`exam_period` AND `subject_master`.`exam_period`=`misc_master`.`exam_period`
WHERE `elg_mem_nm` = 'Y'
AND `medium_delete` = '0'
AND `exam_type` = '2'
AND `misc_master`.`misc_delete` = '0'
AND '2025-09-17' BETWEEN `exam_activation_master`.`exam_from_date` AND exam_activation_master.exam_to_date
AND `exam_activation_master`.`exam_activation_delete` = '0'
AND `exam_master`.`exam_code` NOT IN('528', '529', '530', '531', '534', '991', '997', '1031', '1032', '1052', '1054', '1053', '1062', '1063')
GROUP BY `medium_master`.`exam_code`
ORDER BY `exam_master`.`description` ASC
ERROR - 2025-09-17 09:37:18 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 309
ERROR - 2025-09-17 09:52:41 --> Query error: Unknown column 'email_id' in 'field list' - Invalid query: INSERT INTO `ncvet_member_login_otp` (`email_id`, `regnumber`, `otp_type`, `otp`, `is_validate`, `otp_expired_on`, `created_on`) VALUES ('gaurav.shewale@esds.co.in', '9000052', '3', 328240, '0', '2025-09-17 10:00:57', '2025-09-17 09:50:57')
ERROR - 2025-09-17 09:52:41 --> Severity: Warning --> mysqli::query(): MySQL server has gone away /home/supp0rttest/public_html/staging/system/database/drivers/mysqli/mysqli_driver.php 305
ERROR - 2025-09-17 09:52:41 --> Severity: Warning --> mysqli::query(): Error reading result set's header /home/supp0rttest/public_html/staging/system/database/drivers/mysqli/mysqli_driver.php 305
ERROR - 2025-09-17 09:52:41 --> Query error: MySQL server has gone away - Invalid query: SELECT *
FROM `ncvet_candidates`
WHERE `regnumber` = '9000052'
ERROR - 2025-09-17 09:52:41 --> Severity: Error --> Call to a member function row() on boolean /home/supp0rttest/public_html/staging/application/controllers/ncvet/Scribe_form.php 261
ERROR - 2025-09-17 09:52:41 --> Severity: Warning --> mysqli::query(): MySQL server has gone away /home/supp0rttest/public_html/staging/system/database/drivers/mysqli/mysqli_driver.php 305
ERROR - 2025-09-17 09:52:41 --> Severity: Warning --> mysqli::query(): Error reading result set's header /home/supp0rttest/public_html/staging/system/database/drivers/mysqli/mysqli_driver.php 305
ERROR - 2025-09-17 09:52:41 --> Query error: MySQL server has gone away - Invalid query: SELECT *
FROM `ncvet_candidates`
WHERE `regnumber` = '9000052'
ERROR - 2025-09-17 09:52:41 --> Severity: Error --> Call to a member function row() on boolean /home/supp0rttest/public_html/staging/application/controllers/ncvet/Scribe_form.php 261
ERROR - 2025-09-17 09:52:41 --> Severity: Warning --> mysqli::query(): MySQL server has gone away /home/supp0rttest/public_html/staging/system/database/drivers/mysqli/mysqli_driver.php 305
ERROR - 2025-09-17 09:52:41 --> Severity: Warning --> mysqli::query(): Error reading result set's header /home/supp0rttest/public_html/staging/system/database/drivers/mysqli/mysqli_driver.php 305
ERROR - 2025-09-17 09:52:41 --> Query error: MySQL server has gone away - Invalid query: SELECT *
FROM `ncvet_candidates`
WHERE `regnumber` = '9000052'
ERROR - 2025-09-17 09:52:41 --> Severity: Error --> Call to a member function row() on boolean /home/supp0rttest/public_html/staging/application/controllers/ncvet/Scribe_form.php 261
ERROR - 2025-09-17 09:53:31 --> Query error: Unknown column 'email_id' in 'field list' - Invalid query: INSERT INTO `ncvet_member_login_otp` (`email_id`, `regnumber`, `otp_type`, `otp`, `is_validate`, `otp_expired_on`, `created_on`) VALUES ('gaurav.shewale@esds.co.in', '9000052', '3', 583515, '0', '2025-09-17 10:03:31', '2025-09-17 09:53:31')
ERROR - 2025-09-17 10:58:36 --> Severity: Warning --> mdecrypt_generic(): An empty string was passed /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 106
ERROR - 2025-09-17 11:31:30 --> 404 Page Not Found: ncvet/candidate/Candidate_login/index
ERROR - 2025-09-17 11:39:34 --> 404 Page Not Found: Uploads/disability
ERROR - 2025-09-17 11:46:55 --> 404 Page Not Found: Uploads/disability
ERROR - 2025-09-17 11:49:49 --> 404 Page Not Found: Uploads/disability
ERROR - 2025-09-17 11:49:58 --> 404 Page Not Found: Uploads/disability
ERROR - 2025-09-17 12:00:36 --> 404 Page Not Found: Exp_20250916142522_8791pdf/index
ERROR - 2025-09-17 12:00:51 --> 404 Page Not Found: Exp_20250916142522_8791pdf/index
ERROR - 2025-09-17 12:01:30 --> 404 Page Not Found: Exp_20250916142522_8791pdf/index
ERROR - 2025-09-17 12:02:08 --> Severity: Warning --> rename(uploads/ncvet/experience/,uploads/ncvet/experience/exp_9000052.): Invalid argument /home/supp0rttest/public_html/staging/application/controllers/ncvet/candidate/Dashboard_candidate.php 728
ERROR - 2025-09-17 12:05:01 --> Severity: Warning --> rename(uploads/ncvet/experience/,uploads/ncvet/experience/exp_9000052.): Invalid argument /home/supp0rttest/public_html/staging/application/controllers/ncvet/candidate/Dashboard_candidate.php 728
ERROR - 2025-09-17 12:05:41 --> Severity: Warning --> rename(uploads/ncvet/experience/,uploads/ncvet/experience/exp_9000052.): Invalid argument /home/supp0rttest/public_html/staging/application/controllers/ncvet/candidate/Dashboard_candidate.php 728
ERROR - 2025-09-17 12:11:15 --> 404 Page Not Found: Uploads/photograph
ERROR - 2025-09-17 12:11:15 --> 404 Page Not Found: Uploads/scansignature
ERROR - 2025-09-17 12:11:15 --> 404 Page Not Found: Uploads/idproof
ERROR - 2025-09-17 12:14:39 --> 404 Page Not Found: Uploads/disability
ERROR - 2025-09-17 12:15:48 --> Severity: Warning --> rename(uploads/ncvet/experience/,uploads/ncvet/experience/exp_9000052.): Invalid argument /home/supp0rttest/public_html/staging/application/controllers/ncvet/candidate/Dashboard_candidate.php 728
ERROR - 2025-09-17 12:16:50 --> Severity: Warning --> rename(uploads/ncvet/experience/,uploads/ncvet/experience/exp_9000052.): Invalid argument /home/supp0rttest/public_html/staging/application/controllers/ncvet/candidate/Dashboard_candidate.php 728
ERROR - 2025-09-17 12:19:20 --> 404 Page Not Found: Uploads/disability
ERROR - 2025-09-17 12:19:24 --> 404 Page Not Found: Uploads/photograph
ERROR - 2025-09-17 12:19:24 --> 404 Page Not Found: Uploads/scansignature
ERROR - 2025-09-17 12:19:24 --> 404 Page Not Found: Uploads/idproof
ERROR - 2025-09-17 12:19:45 --> Severity: Warning --> rename(uploads/ncvet/experience/,uploads/ncvet/experience/exp_9000052.): Invalid argument /home/supp0rttest/public_html/staging/application/controllers/ncvet/candidate/Dashboard_candidate.php 728
ERROR - 2025-09-17 12:20:20 --> 404 Page Not Found: Uploads/disability
ERROR - 2025-09-17 12:21:32 --> Severity: Warning --> rename(uploads/ncvet/experience/,uploads/ncvet/experience/exp_9000052.): Invalid argument /home/supp0rttest/public_html/staging/application/controllers/ncvet/candidate/Dashboard_candidate.php 728
ERROR - 2025-09-17 12:21:38 --> 404 Page Not Found: Uploads/disability
ERROR - 2025-09-17 12:22:12 --> 404 Page Not Found: Uploads/disability
ERROR - 2025-09-17 12:23:31 --> 404 Page Not Found: Uploads/disability
ERROR - 2025-09-17 12:23:46 --> 404 Page Not Found: Uploads/disability
ERROR - 2025-09-17 12:26:44 --> 404 Page Not Found: Uploads/disability
ERROR - 2025-09-17 12:33:10 --> 404 Page Not Found: Uploads/disability
ERROR - 2025-09-17 12:33:36 --> 404 Page Not Found: Uploads/disability
ERROR - 2025-09-17 12:34:20 --> 404 Page Not Found: Uploads/disability
ERROR - 2025-09-17 12:34:21 --> 404 Page Not Found: Uploads/disability
ERROR - 2025-09-17 12:36:33 --> 404 Page Not Found: Uploads/disability
ERROR - 2025-09-17 12:38:26 --> 404 Page Not Found: Uploads/disability
ERROR - 2025-09-17 12:38:32 --> 404 Page Not Found: Uploads/disability
ERROR - 2025-09-17 12:39:05 --> 404 Page Not Found: Uploads/disability
ERROR - 2025-09-17 12:39:06 --> 404 Page Not Found: Uploads/disability
ERROR - 2025-09-17 12:42:22 --> 404 Page Not Found: Exp_9000052png/index
ERROR - 2025-09-17 12:42:39 --> 404 Page Not Found: Exp_9000052png/index
ERROR - 2025-09-17 12:46:02 --> 404 Page Not Found: ncvet/admin/Candidate/candidate
ERROR - 2025-09-17 12:59:23 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-17 12:59:23 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-17 12:59:23 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-17 12:59:23 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-17 13:00:01 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-17 13:00:01 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-17 13:00:01 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-17 13:00:01 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-17 13:00:01 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-17 13:00:01 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-17 13:00:01 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-17 13:00:01 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-17 13:02:18 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-17 13:02:18 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-17 13:02:18 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-17 13:02:18 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-17 13:02:51 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-17 13:02:51 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-17 13:02:51 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-17 13:02:51 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-17 14:03:44 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-17 14:03:44 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-17 14:03:44 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-17 14:03:44 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-17 14:34:47 --> Severity: Parsing Error --> syntax error, unexpected 'if' (T_IF) /home/supp0rttest/public_html/staging/application/views/ncvet/common/inc_candidate_details_common.php 265
ERROR - 2025-09-17 14:34:53 --> Severity: Parsing Error --> syntax error, unexpected 'if' (T_IF) /home/supp0rttest/public_html/staging/application/views/ncvet/common/inc_candidate_details_common.php 265
ERROR - 2025-09-17 14:35:31 --> Severity: Parsing Error --> syntax error, unexpected 'if' (T_IF) /home/supp0rttest/public_html/staging/application/views/ncvet/common/inc_candidate_details_common.php 265
ERROR - 2025-09-17 14:35:33 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-17 14:35:36 --> Severity: Parsing Error --> syntax error, unexpected 'if' (T_IF) /home/supp0rttest/public_html/staging/application/views/ncvet/common/inc_candidate_details_common.php 265
ERROR - 2025-09-17 14:35:36 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-17 14:36:18 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-17 14:36:18 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-17 14:36:18 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-17 14:36:19 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-17 15:19:48 --> 404 Page Not Found: DRA-AdmitCard/DRA_admitcardphp/index
ERROR - 2025-09-17 15:45:40 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2025-09-17 17:19:04 --> Severity: Warning --> session_start(): Cannot send session cookie - headers already sent by (output started at /home/supp0rttest/public_html/staging/application/controllers/Amc.php:2) /home/supp0rttest/public_html/staging/system/libraries/Session/Session.php 140
ERROR - 2025-09-17 17:19:04 --> Severity: Warning --> session_start(): Cannot send session cache limiter - headers already sent (output started at /home/supp0rttest/public_html/staging/application/controllers/Amc.php:2) /home/supp0rttest/public_html/staging/system/libraries/Session/Session.php 140
ERROR - 2025-09-17 17:19:04 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/supp0rttest/public_html/staging/application/controllers/Amc.php:2) /home/supp0rttest/public_html/staging/system/helpers/url_helper.php 564
ERROR - 2025-09-17 17:19:11 --> Severity: Warning --> session_start(): Cannot send session cookie - headers already sent by (output started at /home/supp0rttest/public_html/staging/application/controllers/Amc.php:2) /home/supp0rttest/public_html/staging/system/libraries/Session/Session.php 140
ERROR - 2025-09-17 17:19:11 --> Severity: Warning --> session_start(): Cannot send session cache limiter - headers already sent (output started at /home/supp0rttest/public_html/staging/application/controllers/Amc.php:2) /home/supp0rttest/public_html/staging/system/libraries/Session/Session.php 140
ERROR - 2025-09-17 17:19:11 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/supp0rttest/public_html/staging/application/controllers/Amc.php:2) /home/supp0rttest/public_html/staging/system/helpers/url_helper.php 564
ERROR - 2025-09-17 17:39:08 --> Severity: Parsing Error --> syntax error, unexpected '==' (T_IS_EQUAL) /home/supp0rttest/public_html/staging/application/views/ncvet/admin/candidate_fields_edit.php 62
ERROR - 2025-09-17 17:40:10 --> Severity: Parsing Error --> syntax error, unexpected '==' (T_IS_EQUAL) /home/supp0rttest/public_html/staging/application/views/ncvet/admin/candidate_fields_edit.php 62
ERROR - 2025-09-17 18:32:46 --> Severity: Parsing Error --> syntax error, unexpected end of file /home/supp0rttest/public_html/staging/application/views/ncvet/admin/candidate_fields_edit.php 138
