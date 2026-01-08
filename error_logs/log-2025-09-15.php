<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2025-09-15 10:23:15 --> Query error: Unknown column 'email_id' in 'field list' - Invalid query: INSERT INTO `ncvet_member_login_otp` (`email_id`, `regnumber`, `otp_type`, `otp`, `is_validate`, `otp_expired_on`, `created_on`) VALUES ('gaurav.shewale@esds.co.in', '9000052', '3', 729634, '0', '2025-09-15 10:33:15', '2025-09-15 10:23:15')
ERROR - 2025-09-15 10:26:25 --> Query error: Column 'visually_impaired' cannot be null - Invalid query: INSERT INTO `ncvet_scribe_registration` (`exam_code`, `exam_name`, `subject_name`, `regnumber`, `namesub`, `firstname`, `middlename`, `lastname`, `email`, `mobile`, `benchmark_disability`, `visually_impaired`, `orthopedically_handicapped`, `cerebral_palsy`, `center_name`, `center_code`, `exam_date`, `qualification`, `name_of_scribe`, `mobile_scribe`, `scribe_dob`, `scribe_email`, `declaration_img`, `aadhar_no`, `aadhaar_card`, `vis_imp_cert_img`, `orth_han_cert_img`, `cer_palsy_cert_img`, `modified_on`) VALUES ('123456', 'test', 'test', '9000052', 'Mr.', 'Sameer', 'Ashok', 'Shewale', 'gaurav.shewale@esds.co.in', '7620851847', 'Y', NULL, NULL, NULL, 'test', '987654', '2025-09-30', 'SSC', 'Rupali Najan', '9857677882', '2004-06-05', 'test@gmail.com', 'uploads/ncvet/scribe/declaration/declaration_img_1757912179.pdf', '978967676665', 'uploads/ncvet/scribe/idproof/aadhaar_card_1757912179.jpg', '', '', '', '2025-09-15 10:26:19')
ERROR - 2025-09-15 10:26:25 --> DB Insert Error: Array
(
    [code] => 1048
    [message] => Column 'visually_impaired' cannot be null
)

ERROR - 2025-09-15 10:26:25 --> Insert Data: Array
(
    [exam_code] => 123456
    [exam_name] => test
    [subject_name] => test
    [regnumber] => 9000052
    [namesub] => Mr.
    [firstname] => Sameer
    [middlename] => Ashok
    [lastname] => Shewale
    [email] => gaurav.shewale@esds.co.in
    [mobile] => 7620851847
    [benchmark_disability] => Y
    [visually_impaired] => 
    [orthopedically_handicapped] => 
    [cerebral_palsy] => 
    [center_name] => test
    [center_code] => 987654
    [exam_date] => 2025-09-30
    [qualification] => SSC
    [name_of_scribe] => Rupali Najan
    [mobile_scribe] => 9857677882
    [scribe_dob] => 2004-06-05
    [scribe_email] => test@gmail.com
    [declaration_img] => uploads/ncvet/scribe/declaration/declaration_img_1757912179.pdf
    [aadhar_no] => 978967676665
    [aadhaar_card] => uploads/ncvet/scribe/idproof/aadhaar_card_1757912179.jpg
    [vis_imp_cert_img] => 
    [orth_han_cert_img] => 
    [cer_palsy_cert_img] => 
    [modified_on] => 2025-09-15 10:26:19
)

ERROR - 2025-09-15 10:57:30 --> Query error: Table 'supp0rttest_iibf_staging.ncvet_bank_associated_master' doesn't exist - Invalid query: SELECT `bank_name`
FROM `ncvet_bank_associated_master`
WHERE `bank_code` IS NULL
AND `is_active` = '1'
AND `is_deleted` = '0'
ERROR - 2025-09-15 10:57:30 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 309
ERROR - 2025-09-15 11:01:06 --> Query error: Table 'supp0rttest_iibf_staging.ncvet_bank_associated_master' doesn't exist - Invalid query: SELECT `bank_name`
FROM `ncvet_bank_associated_master`
WHERE `bank_code` IS NULL
AND `is_active` = '1'
AND `is_deleted` = '0'
ERROR - 2025-09-15 11:01:06 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 309
ERROR - 2025-09-15 11:05:11 --> Query error: Table 'supp0rttest_iibf_staging.ncvet_bank_associated_master' doesn't exist - Invalid query: SELECT `bank_name`
FROM `ncvet_bank_associated_master`
WHERE `bank_code` IS NULL
AND `is_active` = '1'
AND `is_deleted` = '0'
ERROR - 2025-09-15 11:05:11 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 309
ERROR - 2025-09-15 11:05:31 --> Query error: Table 'supp0rttest_iibf_staging.ncvet_bank_associated_master' doesn't exist - Invalid query: SELECT `bank_name`
FROM `ncvet_bank_associated_master`
WHERE `bank_code` IS NULL
AND `is_active` = '1'
AND `is_deleted` = '0'
ERROR - 2025-09-15 11:05:31 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 309
ERROR - 2025-09-15 11:07:22 --> Query error: Table 'supp0rttest_iibf_staging.ncvet_bank_associated_master' doesn't exist - Invalid query: SELECT `bank_name`
FROM `ncvet_bank_associated_master`
WHERE `bank_code` IS NULL
AND `is_active` = '1'
AND `is_deleted` = '0'
ERROR - 2025-09-15 11:07:22 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 309
ERROR - 2025-09-15 11:08:38 --> Query error: Table 'supp0rttest_iibf_staging.ncvet_bank_associated_master' doesn't exist - Invalid query: SELECT `bank_name`
FROM `ncvet_bank_associated_master`
WHERE `bank_code` IS NULL
AND `is_active` = '1'
AND `is_deleted` = '0'
ERROR - 2025-09-15 11:08:38 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 309
ERROR - 2025-09-15 11:12:54 --> Severity: Error --> Call to undefined function batch_reject_mail_V2() /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/Batch.php 1793
ERROR - 2025-09-15 12:13:12 --> 404 Page Not Found: iibfdra/Version_2/Agency/clear_data
ERROR - 2025-09-15 12:13:29 --> 404 Page Not Found: iibfdra/Version_2/Agency/clear_data
ERROR - 2025-09-15 12:13:29 --> 404 Page Not Found: iibfdra/Version_2/Agency/clear_data
ERROR - 2025-09-15 12:13:32 --> 404 Page Not Found: iibfdra/Version_2/Agency/clear_data
ERROR - 2025-09-15 12:13:32 --> 404 Page Not Found: iibfdra/Version_2/Agency/clear_data
ERROR - 2025-09-15 12:13:33 --> 404 Page Not Found: iibfdra/Version_2/Agency/clear_data
ERROR - 2025-09-15 12:13:33 --> 404 Page Not Found: iibfdra/Version_2/Agency/clear_data
ERROR - 2025-09-15 12:13:33 --> 404 Page Not Found: iibfdra/Version_2/Agency/clear_data
ERROR - 2025-09-15 12:13:34 --> 404 Page Not Found: iibfdra/Version_2/Agency/clear_data
ERROR - 2025-09-15 12:14:00 --> 404 Page Not Found: iibfdra/Version_2/Agency/clear_data
ERROR - 2025-09-15 12:14:01 --> 404 Page Not Found: iibfdra/Version_2/Agency/clear_data
ERROR - 2025-09-15 12:14:38 --> 404 Page Not Found: iibfdra/Version_2/Agency/clear_data
ERROR - 2025-09-15 12:17:15 --> Severity: Warning --> session_start(): Cannot send session cookie - headers already sent by (output started at /home/supp0rttest/public_html/staging/application/controllers/Amc.php:2) /home/supp0rttest/public_html/staging/system/libraries/Session/Session.php 140
ERROR - 2025-09-15 12:17:15 --> Severity: Warning --> session_start(): Cannot send session cache limiter - headers already sent (output started at /home/supp0rttest/public_html/staging/application/controllers/Amc.php:2) /home/supp0rttest/public_html/staging/system/libraries/Session/Session.php 140
ERROR - 2025-09-15 12:19:41 --> Query error: Table 'supp0rttest_iibf_staging.ncvet_bank_associated_master' doesn't exist - Invalid query: SELECT `bank_name`
FROM `ncvet_bank_associated_master`
WHERE `bank_code` IS NULL
AND `is_active` = '1'
AND `is_deleted` = '0'
ERROR - 2025-09-15 12:19:41 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 309
ERROR - 2025-09-15 12:20:38 --> Query error: Table 'supp0rttest_iibf_staging.ncvet_bank_associated_master' doesn't exist - Invalid query: SELECT `bank_name`
FROM `ncvet_bank_associated_master`
WHERE `bank_code` IS NULL
AND `is_active` = '1'
AND `is_deleted` = '0'
ERROR - 2025-09-15 12:20:38 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 309
ERROR - 2025-09-15 12:21:20 --> Severity: Warning --> mcrypt_generic_init(): Key size is 0 /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 83
ERROR - 2025-09-15 12:21:20 --> Severity: Warning --> mcrypt_generic_init(): Key length incorrect /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 83
ERROR - 2025-09-15 12:21:20 --> Severity: Warning --> mcrypt_generic(): 7 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 84
ERROR - 2025-09-15 12:21:20 --> Severity: Warning --> mcrypt_generic_deinit(): 7 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 87
ERROR - 2025-09-15 12:21:20 --> Severity: Warning --> mcrypt_module_close(): 7 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 88
ERROR - 2025-09-15 12:21:38 --> Query error: Table 'supp0rttest_iibf_staging.ncvet_bank_associated_master' doesn't exist - Invalid query: SELECT `bank_name`
FROM `ncvet_bank_associated_master`
WHERE `bank_code` IS NULL
AND `is_active` = '1'
AND `is_deleted` = '0'
ERROR - 2025-09-15 12:21:38 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 309
ERROR - 2025-09-15 12:21:49 --> Severity: Error --> Call to undefined function batch_reject_mail_V2() /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/Batch.php 1793
ERROR - 2025-09-15 12:26:21 --> Query error: Table 'supp0rttest_iibf_staging.ncvet_bank_associated_master' doesn't exist - Invalid query: SELECT `bank_name`
FROM `ncvet_bank_associated_master`
WHERE `bank_code` IS NULL
AND `is_active` = '1'
AND `is_deleted` = '0'
ERROR - 2025-09-15 12:26:21 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 309
ERROR - 2025-09-15 12:26:56 --> Query error: Table 'supp0rttest_iibf_staging.ncvet_bank_associated_master' doesn't exist - Invalid query: SELECT `bank_name`
FROM `ncvet_bank_associated_master`
WHERE `bank_code` IS NULL
AND `is_active` = '1'
AND `is_deleted` = '0'
ERROR - 2025-09-15 12:26:56 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 309
ERROR - 2025-09-15 12:27:18 --> Query error: Table 'supp0rttest_iibf_staging.ncvet_bank_associated_master' doesn't exist - Invalid query: SELECT `bank_name`
FROM `ncvet_bank_associated_master`
WHERE `bank_code` IS NULL
AND `is_active` = '1'
AND `is_deleted` = '0'
ERROR - 2025-09-15 12:27:18 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 309
ERROR - 2025-09-15 12:27:36 --> Query error: Table 'supp0rttest_iibf_staging.ncvet_bank_associated_master' doesn't exist - Invalid query: SELECT `bank_name`
FROM `ncvet_bank_associated_master`
WHERE `bank_code` IS NULL
AND `is_active` = '1'
AND `is_deleted` = '0'
ERROR - 2025-09-15 12:27:36 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 309
ERROR - 2025-09-15 12:27:45 --> Severity: Error --> Call to undefined function batch_approve_mail_V2() /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/Batch.php 1589
ERROR - 2025-09-15 12:43:05 --> 404 Page Not Found: iibfdra/Version_2/Agency/clear_data
ERROR - 2025-09-15 12:55:54 --> Severity: Warning --> session_start(): Cannot send session cookie - headers already sent by (output started at /home/supp0rttest/public_html/staging/application/controllers/Amc.php:2) /home/supp0rttest/public_html/staging/system/libraries/Session/Session.php 140
ERROR - 2025-09-15 12:55:54 --> Severity: Warning --> session_start(): Cannot send session cache limiter - headers already sent (output started at /home/supp0rttest/public_html/staging/application/controllers/Amc.php:2) /home/supp0rttest/public_html/staging/system/libraries/Session/Session.php 140
ERROR - 2025-09-15 13:13:09 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 13:13:09 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 13:13:09 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 13:13:09 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 13:18:47 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 13:18:47 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 13:18:47 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 13:18:47 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 13:19:00 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 13:19:00 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 13:19:01 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 13:19:01 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 13:30:30 --> Query error: Table 'supp0rttest_iibf_staging.ncvet_bank_associated_master' doesn't exist - Invalid query: SELECT `bank_name`
FROM `ncvet_bank_associated_master`
WHERE `bank_code` IS NULL
AND `is_active` = '1'
AND `is_deleted` = '0'
ERROR - 2025-09-15 13:30:30 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 309
ERROR - 2025-09-15 13:30:34 --> Query error: Table 'supp0rttest_iibf_staging.ncvet_bank_associated_master' doesn't exist - Invalid query: SELECT `bank_name`
FROM `ncvet_bank_associated_master`
WHERE `bank_code` IS NULL
AND `is_active` = '1'
AND `is_deleted` = '0'
ERROR - 2025-09-15 13:30:34 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 309
ERROR - 2025-09-15 13:40:06 --> Query error: Column 'visually_impaired' cannot be null - Invalid query: INSERT INTO `ncvet_scribe_registration` (`exam_code`, `exam_name`, `subject_name`, `regnumber`, `namesub`, `firstname`, `middlename`, `lastname`, `email`, `mobile`, `benchmark_disability`, `visually_impaired`, `orthopedically_handicapped`, `cerebral_palsy`, `center_name`, `center_code`, `exam_date`, `qualification`, `name_of_scribe`, `mobile_scribe`, `scribe_dob`, `scribe_email`, `declaration_img`, `aadhar_no`, `aadhaar_card`, `vis_imp_cert_img`, `orth_han_cert_img`, `cer_palsy_cert_img`, `modified_on`) VALUES ('123456', 'test', 'test', '9000052', 'Mr.', 'Sameer', 'Ashok', 'Shewale', 'gaurav.shewale@esds.co.in', '7620851847', 'Y', NULL, NULL, NULL, 'test', '987654', '2025-09-30', 'HSC', '777', '9857677882', '2007-09-05', 'test@gmail.com', 'uploads/ncvet/scribe/declaration/declaration_img_1757923802.pdf', '978967676665', 'uploads/ncvet/scribe/idproof/aadhaar_card_1757923802.jpg', '', '', '', '2025-09-15 13:40:02')
ERROR - 2025-09-15 13:40:06 --> DB Insert Error: Array
(
    [code] => 1048
    [message] => Column 'visually_impaired' cannot be null
)

ERROR - 2025-09-15 13:40:06 --> Insert Data: Array
(
    [exam_code] => 123456
    [exam_name] => test
    [subject_name] => test
    [regnumber] => 9000052
    [namesub] => Mr.
    [firstname] => Sameer
    [middlename] => Ashok
    [lastname] => Shewale
    [email] => gaurav.shewale@esds.co.in
    [mobile] => 7620851847
    [benchmark_disability] => Y
    [visually_impaired] => 
    [orthopedically_handicapped] => 
    [cerebral_palsy] => 
    [center_name] => test
    [center_code] => 987654
    [exam_date] => 2025-09-30
    [qualification] => HSC
    [name_of_scribe] => 777
    [mobile_scribe] => 9857677882
    [scribe_dob] => 2007-09-05
    [scribe_email] => test@gmail.com
    [declaration_img] => uploads/ncvet/scribe/declaration/declaration_img_1757923802.pdf
    [aadhar_no] => 978967676665
    [aadhaar_card] => uploads/ncvet/scribe/idproof/aadhaar_card_1757923802.jpg
    [vis_imp_cert_img] => 
    [orth_han_cert_img] => 
    [cer_palsy_cert_img] => 
    [modified_on] => 2025-09-15 13:40:02
)

ERROR - 2025-09-15 13:50:20 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 13:50:20 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 13:50:20 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 13:50:20 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 14:11:44 --> 404 Page Not Found: Aadhaar_card_1757925704jpg/index
ERROR - 2025-09-15 14:12:49 --> 404 Page Not Found: Aadhaar_card_1757925769jpg/index
ERROR - 2025-09-15 14:12:51 --> 404 Page Not Found: Declaration_img_1757925769pdf/index
ERROR - 2025-09-15 14:19:29 --> Severity: Parsing Error --> syntax error, unexpected '?' /home/supp0rttest/public_html/staging/application/views/ncvet/scribe_form/preview.php 203
ERROR - 2025-09-15 14:19:31 --> Severity: Parsing Error --> syntax error, unexpected '?' /home/supp0rttest/public_html/staging/application/views/ncvet/scribe_form/preview.php 203
ERROR - 2025-09-15 14:19:41 --> 404 Page Not Found: Aadhaar_card_1757925769jpg/index
ERROR - 2025-09-15 14:21:32 --> 404 Page Not Found: Aadhaar_card_1757925769jpg/index
ERROR - 2025-09-15 14:21:35 --> 404 Page Not Found: Declaration_img_1757925769pdf/index
ERROR - 2025-09-15 14:21:40 --> 404 Page Not Found: Aadhaar_card_1757925769jpg/index
ERROR - 2025-09-15 14:21:42 --> 404 Page Not Found: Aadhaar_card_1757925769jpg/index
ERROR - 2025-09-15 14:21:47 --> 404 Page Not Found: Declaration_img_1757925769pdf/index
ERROR - 2025-09-15 14:21:56 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 14:21:57 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 14:21:57 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 14:21:57 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 14:25:16 --> 404 Page Not Found: Aadhaar_card_1757925769jpg/index
ERROR - 2025-09-15 14:28:47 --> 404 Page Not Found: Aadhaar_card_1757925769jpg/index
ERROR - 2025-09-15 14:28:50 --> 404 Page Not Found: Aadhaar_card_1757925769jpg/index
ERROR - 2025-09-15 14:47:55 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 's crystal Apartment,','Kamalanagar colony, Medipally,','','Medchal','Hyderabad',' at line 1 - Invalid query: INSERT INTO bk_adm_info(center_code, mem_type, mem_mem_no, g_1, mam_nam_1, mem_adr_1, mem_adr_2, mem_adr_3, mem_adr_4, mem_adr_5, mem_adr_6, mem_pin_cd, zo, state, exm_cd, sub_cd, m_1, inscd, insname, venueid, venueadd1, venueadd2, venueadd3, venueadd4, venueadd5, venpin, stat, pwd, date, time, mode, seat_identification) VALUES ('13','NM','802727469','F','YAMUNA BUKYA','Flat. No: 403,','Srikara's crystal Apartment,','Kamalanagar colony, Medipally,','','Medchal','Hyderabad','500098','SZ','TEL','1037','545','ENGLISH','','','500001','DEXIT GLOBAL LIMITED - HYDERABAD, 5-9-211/2, 4TH FLOOR, P.M. HOUSE, CHIRAG ALI LANE, ABIDS, LANDMARK: ADJACENT TO HDFC BANK / ABOVE LIC OFFICE, HYDERABAD, TELANGANA-500001, India.','','','','','','','X84NZ7','2025-09-20','2:00 PM','Online','1005352')
ERROR - 2025-09-15 14:48:12 --> 404 Page Not Found: Aadhaar_card_1757925769jpg/index
ERROR - 2025-09-15 14:48:12 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 14:48:13 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 14:48:13 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 14:48:13 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 14:48:14 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 14:48:14 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 14:48:14 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 14:48:14 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 14:49:43 --> Severity: Parsing Error --> syntax error, unexpected '?' /home/supp0rttest/public_html/staging/application/views/ncvet/scribe_form/preview.php 216
ERROR - 2025-09-15 14:50:13 --> 404 Page Not Found: Aadhaar_card_1757925769jpg/index
ERROR - 2025-09-15 14:54:18 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 14:54:18 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 14:54:18 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 14:54:18 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 14:54:23 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 14:54:23 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 14:54:23 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 14:54:23 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 14:59:12 --> 404 Page Not Found: Aadhaar_card_1757925769jpg/index
ERROR - 2025-09-15 14:59:14 --> 404 Page Not Found: Aadhaar_card_1757925769jpg/index
ERROR - 2025-09-15 15:18:17 --> 404 Page Not Found: Uploads/ncvet
ERROR - 2025-09-15 15:23:04 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:23:04 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:23:04 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:23:04 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:30:21 --> Severity: Warning --> Missing argument 1 for Scribe_form::getDetails_Scribe() /home/supp0rttest/public_html/staging/application/controllers/Scribe_form.php 165
ERROR - 2025-09-15 15:30:21 --> Severity: Warning --> Missing argument 2 for Scribe_form::getDetails_Scribe() /home/supp0rttest/public_html/staging/application/controllers/Scribe_form.php 165
ERROR - 2025-09-15 15:30:21 --> Severity: Warning --> Missing argument 3 for Scribe_form::getDetails_Scribe() /home/supp0rttest/public_html/staging/application/controllers/Scribe_form.php 165
ERROR - 2025-09-15 15:49:09 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:49:09 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:49:09 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:49:09 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:49:11 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:49:11 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:49:11 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:49:11 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:52:23 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:52:23 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:52:23 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:52:23 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:52:38 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:52:38 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:52:38 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:52:38 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:54:45 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:54:45 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:54:45 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:54:45 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:57:23 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:57:23 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:57:23 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:57:23 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:57:32 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:57:33 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:57:33 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:57:33 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:59:34 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:59:34 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:59:34 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:59:34 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:59:36 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:59:36 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:59:36 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 15:59:36 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:18:38 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:18:38 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:18:38 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:18:38 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:18:41 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:18:41 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:18:41 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:18:41 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:18:44 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:18:45 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:18:45 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:18:45 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:21:11 --> Query error: Unknown column 'email_id' in 'field list' - Invalid query: INSERT INTO `ncvet_member_login_otp` (`email_id`, `regnumber`, `otp_type`, `otp`, `is_validate`, `otp_expired_on`, `created_on`) VALUES ('gaurav.shewale@esds.co.in', '9000052', '3', 718777, '0', '2025-09-15 16:31:11', '2025-09-15 16:21:11')
ERROR - 2025-09-15 16:24:51 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:24:51 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:24:51 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:24:51 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:32:08 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:32:08 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:32:08 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:32:08 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:32:11 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:32:11 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:32:11 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:32:11 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:37:37 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:37:37 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:37:37 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:37:37 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:37:58 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:37:58 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:37:58 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:37:58 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:49:21 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:49:21 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:49:21 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:49:21 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:51:46 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:51:46 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:51:47 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:51:47 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:51:47 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:51:47 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:51:58 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:51:58 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:51:58 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:51:58 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:52:38 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:52:38 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:52:38 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 16:52:38 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:30:38 --> Severity: Error --> Call to undefined function batch_approve_mail_V2() /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/Batch.php 1589
ERROR - 2025-09-15 17:31:31 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:31:31 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:31:31 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:31:31 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:33:12 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:33:12 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:33:13 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:33:13 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:38:52 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:38:52 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:38:52 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:38:52 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:40:48 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:40:48 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:40:49 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:40:49 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:41:32 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:41:32 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:41:32 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:41:32 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:41:34 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:41:34 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:41:34 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:41:34 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:44:13 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:44:13 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:44:13 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:44:13 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:45:01 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:45:01 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:45:01 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:45:01 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:45:42 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:45:42 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:45:42 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:45:42 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:48:50 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:48:50 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:48:50 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:48:50 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:54:22 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:54:22 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:54:22 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:54:22 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:57:22 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:57:22 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:57:22 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:57:22 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:58:12 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:58:12 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:58:12 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:58:12 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:58:22 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:58:22 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:58:22 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 17:58:22 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:03:09 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:03:09 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:03:09 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:03:09 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:04:09 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:04:09 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:04:09 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:04:09 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:05:00 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:05:00 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:05:00 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:05:00 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:06:17 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:06:17 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:06:17 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:06:17 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:06:19 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:06:19 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:06:19 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:06:19 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:06:40 --> Severity: Error --> Call to undefined function batch_approve_mail_V2() /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/Batch.php 1589
ERROR - 2025-09-15 18:07:55 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:07:55 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:07:55 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:07:55 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:09:53 --> Query error: Table 'supp0rttest_iibf_staging.bulk_exam_activation' doesn't exist - Invalid query: INSERT INTO bulk_exam_activation(exam_code, exam_period, exam_from_date, exam_to_date,original_val) VALUES('1051','525','2025-09-16','2025-09-21','');
ERROR - 2025-09-15 18:10:04 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:10:04 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:10:04 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:10:04 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:11:20 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:11:20 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:11:20 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:11:20 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:11:26 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:11:26 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:11:26 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:11:26 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:12:03 --> Severity: error --> Exception: DateTime::__construct(): Failed to parse time string (27/09/2025) at position 0 (2): Unexpected character /home/supp0rttest/public_html/staging/application/controllers/Automation.php 4499
ERROR - 2025-09-15 18:15:39 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:15:39 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:15:39 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:15:39 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:16:59 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:16:59 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:16:59 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:16:59 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:18:19 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:18:19 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:18:19 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:18:19 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:19:43 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:19:43 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:19:43 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:19:43 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:23:21 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:23:21 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:23:21 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 18:23:21 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 19:02:30 --> Severity: Warning --> imagepng(uploads/bulk_proforma_examinvoice/bulk_126.jpg): failed to open stream: No such file or directory /home/supp0rttest/public_html/staging/application/helpers/bulk_proforma_invoice_helper.php 226
ERROR - 2025-09-15 19:02:30 --> Severity: Warning --> imagepng(uploads/bulk_proforma_examinvoice/bulk_126.jpg): failed to open stream: No such file or directory /home/supp0rttest/public_html/staging/application/helpers/bulk_proforma_invoice_helper.php 230
ERROR - 2025-09-15 19:04:29 --> Severity: Warning --> array_unique() expects parameter 1 to be array, null given /home/supp0rttest/public_html/staging/application/controllers/bulk/BulkTransaction.php 221
ERROR - 2025-09-15 19:04:29 --> Severity: Warning --> implode(): Invalid arguments passed /home/supp0rttest/public_html/staging/application/controllers/bulk/BulkTransaction.php 221
ERROR - 2025-09-15 19:14:28 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 19:14:28 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 19:14:28 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 19:14:28 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 19:15:03 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 19:15:03 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 19:15:03 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 19:15:03 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-15 19:17:54 --> Severity: Warning --> array_unique() expects parameter 1 to be array, null given /home/supp0rttest/public_html/staging/application/controllers/bulk/BulkTransaction.php 221
ERROR - 2025-09-15 19:17:54 --> Severity: Warning --> implode(): Invalid arguments passed /home/supp0rttest/public_html/staging/application/controllers/bulk/BulkTransaction.php 221
ERROR - 2025-09-15 19:26:14 --> Severity: Warning --> array_unique() expects parameter 1 to be array, null given /home/supp0rttest/public_html/staging/application/controllers/bulk/BulkTransaction.php 221
ERROR - 2025-09-15 19:26:14 --> Severity: Warning --> implode(): Invalid arguments passed /home/supp0rttest/public_html/staging/application/controllers/bulk/BulkTransaction.php 221
ERROR - 2025-09-15 19:27:12 --> Severity: Warning --> array_unique() expects parameter 1 to be array, null given /home/supp0rttest/public_html/staging/application/controllers/bulk/BulkTransaction.php 221
ERROR - 2025-09-15 19:27:12 --> Severity: Warning --> implode(): Invalid arguments passed /home/supp0rttest/public_html/staging/application/controllers/bulk/BulkTransaction.php 221
ERROR - 2025-09-15 19:34:32 --> Severity: Warning --> array_unique() expects parameter 1 to be array, null given /home/supp0rttest/public_html/staging/application/controllers/bulk/BulkTransaction.php 221
ERROR - 2025-09-15 19:34:32 --> Severity: Warning --> implode(): Invalid arguments passed /home/supp0rttest/public_html/staging/application/controllers/bulk/BulkTransaction.php 221
