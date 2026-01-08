<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2024-10-11 11:47:19 --> 404 Page Not Found: bulk/BulkApply/mouexamlist
ERROR - 2024-10-11 11:47:21 --> 404 Page Not Found: bulk/BulkApply/mouexamlist
ERROR - 2024-10-11 12:03:05 --> 404 Page Not Found: Vendor_registration/index
ERROR - 2024-10-11 12:31:50 --> 404 Page Not Found: Assets/js
ERROR - 2024-10-11 12:32:02 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near '`NULL`
AND `batch_from_date` < `IS` `NULL`
OR `batch_to_date` > `IS` `NULL`
AND ' at line 7 - Invalid query: SELECT `id`, `batch_code`, `batch_from_date`, `batch_to_date`, `hours`
FROM `agency_batch`
WHERE   (
`batch_status` != 'Cancelled'
AND `batch_status` != 'Rejected'
 )
AND `batch_from_date` > `IS` `NULL`
AND `batch_from_date` < `IS` `NULL`
OR `batch_to_date` > `IS` `NULL`
AND `batch_to_date` < `IS` `NULL`
AND `agency_id` = '180'
ERROR - 2024-10-11 12:32:02 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/TrainingBatches.php 109
ERROR - 2024-10-11 13:04:32 --> 404 Page Not Found: Vendor_registration/index
ERROR - 2024-10-11 13:51:19 --> Severity: Parsing Error --> syntax error, unexpected '.' /home/supp0rttest/public_html/staging/application/views/iibfdra/Version_2/admin/batch/batch_detail.php 289
ERROR - 2024-10-11 13:51:40 --> Severity: Parsing Error --> syntax error, unexpected '.' /home/supp0rttest/public_html/staging/application/views/iibfdra/Version_2/admin/batch/batch_detail.php 289
ERROR - 2024-10-11 13:54:04 --> Severity: Parsing Error --> syntax error, unexpected '.' /home/supp0rttest/public_html/staging/application/views/iibfdra/Version_2/admin/batch/batch_detail.php 289
ERROR - 2024-10-11 13:54:10 --> Severity: Parsing Error --> syntax error, unexpected '.' /home/supp0rttest/public_html/staging/application/views/iibfdra/Version_2/admin/batch/batch_detail.php 289
ERROR - 2024-10-11 14:15:48 --> Severity: Warning --> mysqli::query(): MySQL server has gone away /home/supp0rttest/public_html/staging/system/database/drivers/mysqli/mysqli_driver.php 305
ERROR - 2024-10-11 14:15:48 --> Severity: Warning --> mysqli::query(): Error reading result set's header /home/supp0rttest/public_html/staging/system/database/drivers/mysqli/mysqli_driver.php 305
ERROR - 2024-10-11 14:15:49 --> Query error: MySQL server has gone away - Invalid query: SELECT `agency_batch`.*, `dra_inst_registration`.`inst_name`, `agency_center`.`location_name`, `agency_center`.`state`, `agency_center`.`district`, `agency_center`.`city`, `agency_inspector_master`.`inspector_name`, `state_master`.`state_name`, `city_master`.`city_name`, `f1`.`salutation` as `first_faculty_salutation`, `f1`.`faculty_code` as `first_faculty_code`, `f1`.`faculty_name` as `first_faculty_name`, `f1`.`academic_qualification` as `first_faculty_qualification`, `f2`.`salutation` as `sec_faculty_salutation`, `f2`.`faculty_code` as `sec_faculty_code`, `f2`.`faculty_name` as `sec_faculty_name`, `f2`.`academic_qualification` as `sec_faculty_qualification`, `f3`.`faculty_code` as `add_first_faculty_code`, `f3`.`salutation` as `add_first_faculty_salutation`, `f3`.`faculty_name` as `add_first_faculty_name`, `f3`.`academic_qualification` as `add_first_faculty_qualification`, `f4`.`faculty_code` as `add_sec_faculty_code`, `f4`.`salutation` as `add_sec_faculty_salutation`, `f4`.`faculty_name` as `add_sec_faculty_name`, `f4`.`academic_qualification` as `add_sec_faculty_qualification`
FROM `agency_batch`
LEFT JOIN `agency_center` ON `agency_batch`.`center_id`=`agency_center`.`center_id`
LEFT JOIN `dra_inst_registration` ON `agency_batch`.`agency_id`=`dra_inst_registration`.`id`
LEFT JOIN `state_master` ON `agency_center`.`state`=`state_master`.`state_code`
LEFT JOIN `city_master` ON `city_master`.`id`=`agency_center`.`location_name`
LEFT JOIN `agency_inspector_master` ON `agency_inspector_master`.`id`=`agency_batch`.`inspector_id`
LEFT JOIN `faculty_master` `f1` ON `agency_batch`.`first_faculty`=`f1`.`faculty_id`
LEFT JOIN `faculty_master` `f2` ON `agency_batch`.`sec_faculty`=`f2`.`faculty_id`
LEFT JOIN `faculty_master` `f3` ON `agency_batch`.`additional_first_faculty`=`f3`.`faculty_id`
LEFT JOIN `faculty_master` `f4` ON `agency_batch`.`additional_sec_faculty`=`f4`.`faculty_id`
WHERE `agency_center`.`center_display_status` = '1'
AND `agency_batch`.`id` = 13684
ERROR - 2024-10-11 14:15:53 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 238
ERROR - 2024-10-11 14:15:54 --> Severity: Warning --> mysqli::query(): MySQL server has gone away /home/supp0rttest/public_html/staging/system/database/drivers/mysqli/mysqli_driver.php 305
ERROR - 2024-10-11 14:15:54 --> Severity: Warning --> mysqli::query(): Error reading result set's header /home/supp0rttest/public_html/staging/system/database/drivers/mysqli/mysqli_driver.php 305
ERROR - 2024-10-11 14:15:54 --> Query error: MySQL server has gone away - Invalid query: SELECT `agency_batch`.*, `dra_inst_registration`.`inst_name`, `agency_center`.`location_name`, `agency_center`.`state`, `agency_center`.`district`, `agency_center`.`city`, `agency_inspector_master`.`inspector_name`, `state_master`.`state_name`, `city_master`.`city_name`, `f1`.`salutation` as `first_faculty_salutation`, `f1`.`faculty_code` as `first_faculty_code`, `f1`.`faculty_name` as `first_faculty_name`, `f1`.`academic_qualification` as `first_faculty_qualification`, `f2`.`salutation` as `sec_faculty_salutation`, `f2`.`faculty_code` as `sec_faculty_code`, `f2`.`faculty_name` as `sec_faculty_name`, `f2`.`academic_qualification` as `sec_faculty_qualification`, `f3`.`faculty_code` as `add_first_faculty_code`, `f3`.`salutation` as `add_first_faculty_salutation`, `f3`.`faculty_name` as `add_first_faculty_name`, `f3`.`academic_qualification` as `add_first_faculty_qualification`, `f4`.`faculty_code` as `add_sec_faculty_code`, `f4`.`salutation` as `add_sec_faculty_salutation`, `f4`.`faculty_name` as `add_sec_faculty_name`, `f4`.`academic_qualification` as `add_sec_faculty_qualification`
FROM `agency_batch`
LEFT JOIN `agency_center` ON `agency_batch`.`center_id`=`agency_center`.`center_id`
LEFT JOIN `dra_inst_registration` ON `agency_batch`.`agency_id`=`dra_inst_registration`.`id`
LEFT JOIN `state_master` ON `agency_center`.`state`=`state_master`.`state_code`
LEFT JOIN `city_master` ON `city_master`.`id`=`agency_center`.`location_name`
LEFT JOIN `agency_inspector_master` ON `agency_inspector_master`.`id`=`agency_batch`.`inspector_id`
LEFT JOIN `faculty_master` `f1` ON `agency_batch`.`first_faculty`=`f1`.`faculty_id`
LEFT JOIN `faculty_master` `f2` ON `agency_batch`.`sec_faculty`=`f2`.`faculty_id`
LEFT JOIN `faculty_master` `f3` ON `agency_batch`.`additional_first_faculty`=`f3`.`faculty_id`
LEFT JOIN `faculty_master` `f4` ON `agency_batch`.`additional_sec_faculty`=`f4`.`faculty_id`
WHERE `agency_center`.`center_display_status` = '1'
AND `agency_batch`.`id` = 13684
ERROR - 2024-10-11 14:15:54 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 238
ERROR - 2024-10-11 14:15:54 --> Severity: Warning --> mysqli::query(): MySQL server has gone away /home/supp0rttest/public_html/staging/system/database/drivers/mysqli/mysqli_driver.php 305
ERROR - 2024-10-11 14:15:54 --> Severity: Warning --> mysqli::query(): Error reading result set's header /home/supp0rttest/public_html/staging/system/database/drivers/mysqli/mysqli_driver.php 305
ERROR - 2024-10-11 14:15:54 --> Query error: MySQL server has gone away - Invalid query: SELECT `agency_batch`.*, `dra_inst_registration`.`inst_name`, `agency_center`.`location_name`, `agency_center`.`state`, `agency_center`.`district`, `agency_center`.`city`, `agency_inspector_master`.`inspector_name`, `state_master`.`state_name`, `city_master`.`city_name`, `f1`.`salutation` as `first_faculty_salutation`, `f1`.`faculty_code` as `first_faculty_code`, `f1`.`faculty_name` as `first_faculty_name`, `f1`.`academic_qualification` as `first_faculty_qualification`, `f2`.`salutation` as `sec_faculty_salutation`, `f2`.`faculty_code` as `sec_faculty_code`, `f2`.`faculty_name` as `sec_faculty_name`, `f2`.`academic_qualification` as `sec_faculty_qualification`, `f3`.`faculty_code` as `add_first_faculty_code`, `f3`.`salutation` as `add_first_faculty_salutation`, `f3`.`faculty_name` as `add_first_faculty_name`, `f3`.`academic_qualification` as `add_first_faculty_qualification`, `f4`.`faculty_code` as `add_sec_faculty_code`, `f4`.`salutation` as `add_sec_faculty_salutation`, `f4`.`faculty_name` as `add_sec_faculty_name`, `f4`.`academic_qualification` as `add_sec_faculty_qualification`
FROM `agency_batch`
LEFT JOIN `agency_center` ON `agency_batch`.`center_id`=`agency_center`.`center_id`
LEFT JOIN `dra_inst_registration` ON `agency_batch`.`agency_id`=`dra_inst_registration`.`id`
LEFT JOIN `state_master` ON `agency_center`.`state`=`state_master`.`state_code`
LEFT JOIN `city_master` ON `city_master`.`id`=`agency_center`.`location_name`
LEFT JOIN `agency_inspector_master` ON `agency_inspector_master`.`id`=`agency_batch`.`inspector_id`
LEFT JOIN `faculty_master` `f1` ON `agency_batch`.`first_faculty`=`f1`.`faculty_id`
LEFT JOIN `faculty_master` `f2` ON `agency_batch`.`sec_faculty`=`f2`.`faculty_id`
LEFT JOIN `faculty_master` `f3` ON `agency_batch`.`additional_first_faculty`=`f3`.`faculty_id`
LEFT JOIN `faculty_master` `f4` ON `agency_batch`.`additional_sec_faculty`=`f4`.`faculty_id`
WHERE `agency_center`.`center_display_status` = '1'
AND `agency_batch`.`id` = 13684
ERROR - 2024-10-11 14:15:54 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 238
ERROR - 2024-10-11 14:15:54 --> Severity: Warning --> mysqli::query(): MySQL server has gone away /home/supp0rttest/public_html/staging/system/database/drivers/mysqli/mysqli_driver.php 305
ERROR - 2024-10-11 14:15:54 --> Severity: Warning --> mysqli::query(): Error reading result set's header /home/supp0rttest/public_html/staging/system/database/drivers/mysqli/mysqli_driver.php 305
ERROR - 2024-10-11 14:15:54 --> Query error: MySQL server has gone away - Invalid query: SELECT `agency_batch`.*, `dra_inst_registration`.`inst_name`, `agency_center`.`location_name`, `agency_center`.`state`, `agency_center`.`district`, `agency_center`.`city`, `agency_inspector_master`.`inspector_name`, `state_master`.`state_name`, `city_master`.`city_name`, `f1`.`salutation` as `first_faculty_salutation`, `f1`.`faculty_code` as `first_faculty_code`, `f1`.`faculty_name` as `first_faculty_name`, `f1`.`academic_qualification` as `first_faculty_qualification`, `f2`.`salutation` as `sec_faculty_salutation`, `f2`.`faculty_code` as `sec_faculty_code`, `f2`.`faculty_name` as `sec_faculty_name`, `f2`.`academic_qualification` as `sec_faculty_qualification`, `f3`.`faculty_code` as `add_first_faculty_code`, `f3`.`salutation` as `add_first_faculty_salutation`, `f3`.`faculty_name` as `add_first_faculty_name`, `f3`.`academic_qualification` as `add_first_faculty_qualification`, `f4`.`faculty_code` as `add_sec_faculty_code`, `f4`.`salutation` as `add_sec_faculty_salutation`, `f4`.`faculty_name` as `add_sec_faculty_name`, `f4`.`academic_qualification` as `add_sec_faculty_qualification`
FROM `agency_batch`
LEFT JOIN `agency_center` ON `agency_batch`.`center_id`=`agency_center`.`center_id`
LEFT JOIN `dra_inst_registration` ON `agency_batch`.`agency_id`=`dra_inst_registration`.`id`
LEFT JOIN `state_master` ON `agency_center`.`state`=`state_master`.`state_code`
LEFT JOIN `city_master` ON `city_master`.`id`=`agency_center`.`location_name`
LEFT JOIN `agency_inspector_master` ON `agency_inspector_master`.`id`=`agency_batch`.`inspector_id`
LEFT JOIN `faculty_master` `f1` ON `agency_batch`.`first_faculty`=`f1`.`faculty_id`
LEFT JOIN `faculty_master` `f2` ON `agency_batch`.`sec_faculty`=`f2`.`faculty_id`
LEFT JOIN `faculty_master` `f3` ON `agency_batch`.`additional_first_faculty`=`f3`.`faculty_id`
LEFT JOIN `faculty_master` `f4` ON `agency_batch`.`additional_sec_faculty`=`f4`.`faculty_id`
WHERE `agency_center`.`center_display_status` = '1'
AND `agency_batch`.`id` = 13684
ERROR - 2024-10-11 14:15:54 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 238
ERROR - 2024-10-11 14:15:54 --> Severity: Warning --> mysqli::query(): MySQL server has gone away /home/supp0rttest/public_html/staging/system/database/drivers/mysqli/mysqli_driver.php 305
ERROR - 2024-10-11 14:15:54 --> Severity: Warning --> mysqli::query(): Error reading result set's header /home/supp0rttest/public_html/staging/system/database/drivers/mysqli/mysqli_driver.php 305
ERROR - 2024-10-11 14:15:54 --> Query error: MySQL server has gone away - Invalid query: SELECT `agency_batch`.*, `dra_inst_registration`.`inst_name`, `agency_center`.`location_name`, `agency_center`.`state`, `agency_center`.`district`, `agency_center`.`city`, `agency_inspector_master`.`inspector_name`, `state_master`.`state_name`, `city_master`.`city_name`, `f1`.`salutation` as `first_faculty_salutation`, `f1`.`faculty_code` as `first_faculty_code`, `f1`.`faculty_name` as `first_faculty_name`, `f1`.`academic_qualification` as `first_faculty_qualification`, `f2`.`salutation` as `sec_faculty_salutation`, `f2`.`faculty_code` as `sec_faculty_code`, `f2`.`faculty_name` as `sec_faculty_name`, `f2`.`academic_qualification` as `sec_faculty_qualification`, `f3`.`faculty_code` as `add_first_faculty_code`, `f3`.`salutation` as `add_first_faculty_salutation`, `f3`.`faculty_name` as `add_first_faculty_name`, `f3`.`academic_qualification` as `add_first_faculty_qualification`, `f4`.`faculty_code` as `add_sec_faculty_code`, `f4`.`salutation` as `add_sec_faculty_salutation`, `f4`.`faculty_name` as `add_sec_faculty_name`, `f4`.`academic_qualification` as `add_sec_faculty_qualification`
FROM `agency_batch`
LEFT JOIN `agency_center` ON `agency_batch`.`center_id`=`agency_center`.`center_id`
LEFT JOIN `dra_inst_registration` ON `agency_batch`.`agency_id`=`dra_inst_registration`.`id`
LEFT JOIN `state_master` ON `agency_center`.`state`=`state_master`.`state_code`
LEFT JOIN `city_master` ON `city_master`.`id`=`agency_center`.`location_name`
LEFT JOIN `agency_inspector_master` ON `agency_inspector_master`.`id`=`agency_batch`.`inspector_id`
LEFT JOIN `faculty_master` `f1` ON `agency_batch`.`first_faculty`=`f1`.`faculty_id`
LEFT JOIN `faculty_master` `f2` ON `agency_batch`.`sec_faculty`=`f2`.`faculty_id`
LEFT JOIN `faculty_master` `f3` ON `agency_batch`.`additional_first_faculty`=`f3`.`faculty_id`
LEFT JOIN `faculty_master` `f4` ON `agency_batch`.`additional_sec_faculty`=`f4`.`faculty_id`
WHERE `agency_center`.`center_display_status` = '1'
AND `agency_batch`.`id` = 13684
ERROR - 2024-10-11 14:15:54 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 238
ERROR - 2024-10-11 14:16:16 --> 404 Page Not Found: iibfbcbf/Test_bcbf_api/index
ERROR - 2024-10-11 14:16:31 --> 404 Page Not Found: iibfbcbf/Test_bcbf_api/index
ERROR - 2024-10-11 14:18:34 --> Severity: Warning --> Missing argument 1 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 14:18:34 --> Severity: Warning --> Missing argument 2 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 14:18:34 --> Severity: Warning --> Missing argument 3 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 14:23:25 --> Severity: Warning --> Missing argument 1 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 14:23:25 --> Severity: Warning --> Missing argument 2 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 14:23:26 --> Severity: Warning --> Missing argument 3 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 14:48:14 --> Severity: Warning --> Missing argument 1 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 14:48:14 --> Severity: Warning --> Missing argument 2 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 14:48:14 --> Severity: Warning --> Missing argument 3 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 14:52:53 --> Query error: Table 'supp0rttest_iibf_staging.profilelogs' doesn't exist - Invalid query: INSERT INTO `profilelogs` (`title`, `description`, `type`, `regid`, `regnumber`, `editedby`, `editedbyid`, `date`, `ip`) VALUES ('Profile updated successfully', 'AADHAR_CARD = 521077288599 && ', 'data', '8432445', '802626802', 'Admin', '5', '2024-10-11 14:52:53', '10.11.38.105')
ERROR - 2024-10-11 14:52:53 --> Query error: Table 'supp0rttest_iibf_staging.adminlogs' doesn't exist - Invalid query: INSERT INTO `adminlogs` (`title`, `description`, `userid`, `ip`) VALUES ('Profile updated id:8432445', 'a:2:{s:12:\"updated_data\";a:4:{s:11:\"aadhar_card\";s:12:\"521077288599\";s:8:\"editedon\";s:19:\"2024-10-11 14:52:53\";s:8:\"editedby\";s:9:\"iibfadmin\";s:13:\"editedbyadmin\";s:1:\"5\";}s:8:\"old_data\";a:106:{s:5:\"regid\";s:7:\"8432445\";s:6:\"reg_no\";s:0:\"\";s:9:\"regnumber\";s:9:\"802626802\";s:11:\"usrpassword\";s:24:\"F78jcEwngywlhbocfkWKig==\";s:7:\"namesub\";s:3:\"MR.\";s:9:\"firstname\";s:8:\"VIRENDRA\";s:10:\"middlename\";s:5:\"KUMAR\";s:8:\"lastname\";s:6:\"MISHRA\";s:11:\"displayname\";s:0:\"\";s:14:\"contactdetails\";s:0:\"\";s:8:\"address1\";s:5:\"1156R\";s:8:\"address2\";s:19:\"G BLOCK GAUR CITY 1\";s:8:\"address3\";s:15:\"NOIDA EXTENSION\";s:8:\"address4\";s:0:\"\";s:7:\"country\";s:0:\"\";s:8:\"district\";s:5:\"NOIDA\";s:4:\"city\";s:5:\"NOIDA\";s:5:\"state\";s:3:\"UTT\";s:7:\"pincode\";s:6:\"201309\";s:11:\"address1_pr\";s:0:\"\";s:11:\"address2_pr\";s:0:\"\";s:11:\"address3_pr\";s:0:\"\";s:11:\"address4_pr\";s:0:\"\";s:10:\"country_pr\";s:0:\"\";s:11:\"district_pr\";s:0:\"\";s:7:\"city_pr\";s:0:\"\";s:8:\"state_pr\";s:0:\"\";s:10:\"pincode_pr\";s:0:\"\";s:8:\"centerid\";s:3:\"610\";s:11:\"dateofbirth\";s:10:\"1988-03-02\";s:6:\"gender\";s:4:\"male\";s:13:\"qualification\";s:1:\"P\";s:21:\"specify_qualification\";s:2:\"72\";s:19:\"associatedinstitute\";s:0:\"\";s:6:\"branch\";s:0:\"\";s:6:\"office\";s:0:\"\";s:11:\"designation\";s:0:\"\";s:10:\"dateofjoin\";s:0:\"\";s:11:\"staffnumber\";s:0:\"\";s:5:\"email\";s:30:\"virendra.asdff35sdfs@kotak.com\";s:16:\"registrationtype\";s:2:\"NM\";s:7:\"stdcode\";s:3:\"011\";s:12:\"office_phone\";s:8:\"43521490\";s:6:\"mobile\";s:10:\"9547452013\";s:3:\"fax\";s:0:\"\";s:11:\"nationality\";s:0:\"\";s:12:\"scannedphoto\";s:15:\"p_802669708.jpg\";s:21:\"scannedsignaturephoto\";s:15:\"s_802669708.jpg\";s:7:\"idproof\";s:1:\"5\";s:4:\"idNo\";s:10:\"AMQPM8071M\";s:12:\"idproofphoto\";s:16:\"pr_802669708.jpg\";s:15:\"empidproofphoto\";s:18:\"empr_800000249.jpg\";s:10:\"optnletter\";s:1:\"N\";s:11:\"declaration\";s:25:\"declaration_800000249.jpg\";s:6:\"excode\";s:4:\"1009\";s:3:\"fee\";s:24:\"6000 + GST as applicable\";s:11:\"exam_medium\";s:0:\"\";s:11:\"exam_period\";s:3:\"817\";s:10:\"centercode\";s:3:\"610\";s:6:\"exmode\";s:2:\"ON\";s:7:\"paymode\";s:0:\"\";s:19:\"registration_status\";s:0:\"\";s:8:\"isactive\";s:1:\"1\";s:9:\"isdeleted\";s:1:\"0\";s:10:\"zonal_code\";s:1:\"0\";s:9:\"createdon\";s:19:\"2018-02-08 12:15:35\";s:8:\"editedon\";s:19:\"2024-10-09 17:01:31\";s:15:\"images_editedon\";s:19:\"2024-10-09 16:10:00\";s:8:\"editedby\";s:9:\"iibfadmin\";s:15:\"images_editedby\";s:9:\"iibfadmin\";s:13:\"editedbyadmin\";s:1:\"5\";s:20:\"images_editedbyadmin\";s:1:\"5\";s:9:\"photo_flg\";s:1:\"N\";s:13:\"signature_flg\";s:1:\"N\";s:6:\"id_flg\";s:1:\"N\";s:9:\"empid_flg\";s:1:\"Y\";s:15:\"declaration_flg\";s:1:\"Y\";s:10:\"image_path\";s:0:\"\";s:17:\"old_member_number\";s:0:\"\";s:11:\"bank_branch\";s:1:\"0\";s:9:\"bank_zone\";s:1:\"0\";s:16:\"bank_designation\";s:1:\"0\";s:10:\"bank_scale\";s:1:\"0\";s:11:\"bank_emp_id\";s:8:\"BANK0111\";s:11:\"aadhar_card\";s:12:\"521077288500\";s:13:\"id_proof_flag\";s:0:\"\";s:10:\"kyc_status\";s:1:\"0\";s:8:\"kyc_edit\";s:1:\"1\";s:10:\"is_renewal\";s:1:\"0\";s:18:\"benchmark_edit_flg\";s:1:\"Y\";s:19:\"benchmark_edit_date\";s:19:\"2020-07-25 11:35:34\";s:20:\"benchmark_disability\";s:1:\"N\";s:17:\"visually_impaired\";s:1:\"N\";s:16:\"vis_imp_cert_img\";s:0:\"\";s:26:\"orthopedically_handicapped\";s:1:\"N\";s:17:\"orth_han_cert_img\";s:0:\"\";s:14:\"cerebral_palsy\";s:1:\"N\";s:18:\"cer_palsy_cert_img\";s:0:\"\";s:20:\"benchmark_kyc_status\";s:1:\"0\";s:18:\"benchmark_kyc_edit\";s:1:\"0\";s:11:\"ippb_emp_id\";s:0:\"\";s:13:\"bank_bc_id_no\";N;s:15:\"name_of_bank_bc\";s:0:\"\";s:18:\"date_of_commenc_bc\";s:0:\"\";s:15:\"bank_bc_id_card\";s:0:\"\";s:19:\"bank_bc_id_card_flg\";s:1:\"N\";}}', '5', '10.11.38.105')
ERROR - 2024-10-11 14:54:05 --> Query error: Table 'supp0rttest_iibf_staging.profilelogs' doesn't exist - Invalid query: INSERT INTO `profilelogs` (`title`, `description`, `type`, `regid`, `regnumber`, `editedby`, `editedbyid`, `date`, `ip`) VALUES ('Profile images updated successfully', 'DECLARATION ', 'image', '8432445', '802626802', 'Admin', '5', '2024-10-11 14:54:05', '10.11.38.105')
ERROR - 2024-10-11 14:54:05 --> Query error: Table 'supp0rttest_iibf_staging.adminlogs' doesn't exist - Invalid query: INSERT INTO `adminlogs` (`title`, `description`, `userid`, `ip`) VALUES ('kyc member edited images id : 8432445', 'a:15:{s:12:\"scannedphoto\";s:15:\"p_802669708.jpg\";s:21:\"scannedsignaturephoto\";s:15:\"s_802669708.jpg\";s:12:\"idproofphoto\";s:16:\"pr_802669708.jpg\";s:11:\"declaration\";s:25:\"declaration_802626802.jpg\";s:15:\"images_editedon\";s:19:\"2024-10-11 14:54:05\";s:15:\"images_editedby\";s:9:\"iibfadmin\";s:20:\"images_editedbyadmin\";s:1:\"5\";s:9:\"photo_flg\";s:1:\"N\";s:13:\"signature_flg\";s:1:\"N\";s:6:\"id_flg\";s:1:\"N\";s:15:\"declaration_flg\";s:1:\"Y\";s:8:\"kyc_edit\";i:1;s:10:\"kyc_status\";s:1:\"0\";s:15:\"empidproofphoto\";s:18:\"empr_800000249.jpg\";s:9:\"empid_flg\";s:1:\"N\";}', '5', '10.11.38.105')
ERROR - 2024-10-11 14:55:39 --> Severity: Warning --> Missing argument 1 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 14:55:39 --> Severity: Warning --> Missing argument 2 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 14:55:39 --> Severity: Warning --> Missing argument 3 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 15:12:38 --> Severity: Warning --> Missing argument 1 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 15:12:38 --> Severity: Warning --> Missing argument 2 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 15:12:38 --> Severity: Warning --> Missing argument 3 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 15:21:47 --> Severity: Warning --> Missing argument 1 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 15:21:47 --> Severity: Warning --> Missing argument 2 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 15:21:47 --> Severity: Warning --> Missing argument 3 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 15:25:36 --> 404 Page Not Found: bulk/BulkApply/exam_applicantlst
ERROR - 2024-10-11 15:32:04 --> Severity: Parsing Error --> syntax error, unexpected 'echo' (T_ECHO) /home/supp0rttest/public_html/staging/application/views/bulk/includes/sidebar.php 9
ERROR - 2024-10-11 15:33:05 --> Severity: Parsing Error --> syntax error, unexpected 'echo' (T_ECHO) /home/supp0rttest/public_html/staging/application/views/bulk/includes/sidebar.php 9
ERROR - 2024-10-11 15:33:13 --> Severity: Parsing Error --> syntax error, unexpected 'echo' (T_ECHO) /home/supp0rttest/public_html/staging/application/views/bulk/includes/sidebar.php 9
ERROR - 2024-10-11 15:33:38 --> Severity: Parsing Error --> syntax error, unexpected '}' /home/supp0rttest/public_html/staging/application/views/bulk/includes/sidebar.php 28
ERROR - 2024-10-11 16:23:02 --> 404 Page Not Found: Assets/js
ERROR - 2024-10-11 16:27:17 --> Severity: Warning --> Missing argument 1 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 16:27:17 --> Severity: Warning --> Missing argument 2 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 16:27:17 --> Severity: Warning --> Missing argument 3 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 16:36:55 --> Severity: Warning --> Missing argument 1 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 16:36:55 --> Severity: Warning --> Missing argument 2 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 16:36:55 --> Severity: Warning --> Missing argument 3 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 16:51:36 --> Severity: Warning --> Missing argument 1 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 16:51:36 --> Severity: Warning --> Missing argument 2 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 16:51:36 --> Severity: Warning --> Missing argument 3 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 16:53:50 --> Severity: Warning --> Missing argument 1 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 16:53:50 --> Severity: Warning --> Missing argument 2 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 16:53:50 --> Severity: Warning --> Missing argument 3 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 19:24:32 --> Query error: Unknown column 'exam_period' in 'where clause' - Invalid query: DELETE FROM `iibfbcbf_exam_master`
WHERE `exam_code` = '1057'
AND `exam_period` = '1'
ERROR - 2024-10-11 19:39:06 --> Severity: Warning --> Missing argument 1 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 19:39:06 --> Severity: Warning --> Missing argument 2 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 19:39:06 --> Severity: Warning --> Missing argument 3 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 19:41:35 --> Severity: Warning --> Missing argument 3 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 19:41:47 --> Severity: Warning --> Missing argument 1 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 19:41:47 --> Severity: Warning --> Missing argument 2 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 19:41:47 --> Severity: Warning --> Missing argument 3 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 19:41:53 --> Severity: Warning --> Missing argument 1 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 19:41:53 --> Severity: Warning --> Missing argument 2 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
ERROR - 2024-10-11 19:41:53 --> Severity: Warning --> Missing argument 3 for Test_bcbf_api::test_eligible_api_bcbf_nar() /home/supp0rttest/public_html/staging/application/controllers/iibfbcbf/Test_bcbf_api.php 50
