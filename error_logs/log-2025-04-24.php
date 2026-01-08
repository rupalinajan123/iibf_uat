<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2025-04-24 10:27:47 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-04-24 11:23:42 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-04-24 11:27:17 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-04-24 11:28:37 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-04-24 11:28:52 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-04-24 11:30:03 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-04-24 11:30:32 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-04-24 11:52:47 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-04-24 13:28:30 --> Severity: Warning --> imagepng(uploads/bulk_proforma_examinvoice/bulk_54.jpg): failed to open stream: No such file or directory /home/supp0rttest/public_html/staging/application/helpers/bulk_proforma_invoice_helper.php 226
ERROR - 2025-04-24 13:28:30 --> Severity: Warning --> imagepng(uploads/bulk_proforma_examinvoice/bulk_54.jpg): failed to open stream: No such file or directory /home/supp0rttest/public_html/staging/application/helpers/bulk_proforma_invoice_helper.php 230
ERROR - 2025-04-24 15:38:17 --> 404 Page Not Found: Juniorexecutive/index
ERROR - 2025-04-24 15:38:22 --> 404 Page Not Found: Juniorexecutive/index
ERROR - 2025-04-24 15:59:30 --> 404 Page Not Found: Cdo/index
ERROR - 2025-04-24 16:16:20 --> Severity: Warning --> mysqli::query(): MySQL server has gone away /home/supp0rttest/public_html/staging/system/database/drivers/mysqli/mysqli_driver.php 305
ERROR - 2025-04-24 16:16:20 --> Severity: Warning --> mysqli::query(): Error reading result set's header /home/supp0rttest/public_html/staging/system/database/drivers/mysqli/mysqli_driver.php 305
ERROR - 2025-04-24 16:16:20 --> Query error: MySQL server has gone away - Invalid query: SELECT `a`.`institute_name`, `a`.`short_inst_name`, COUNT(bi.id) as reported, GROUP_CONCAT(bi.overall_compliance) as overall_compliance_list, `ai`.`inspector_name`, `b`.`id`, `b`.`agency_id`, `b`.`batch_code`, `b`.`batch_name`, `b`.`batch_from_date`, `b`.`batch_to_date`, `b`.`online_training_platform`, `b`.`timing_from`, `b`.`timing_to`, `b`.`hours`, `b`.`training_medium`, `b`.`total_candidates`, `f1`.`faculty_id` as `first_faculty_id`, `f1`.`faculty_name` as `first_faculty_name`, `f2`.`faculty_id` as `sec_faculty_id`, `f2`.`faculty_name` as `sec_faculty_name`, `f3`.`faculty_id` as `add_first_faculty_id`, `f3`.`faculty_name` as `add_first_faculty_name`, `f4`.`faculty_id` as `add_sec_faculty_id`, `f4`.`faculty_name` as `add_sec_faculty_name`, `b`.`contact_person_name`, `b`.`contact_person_phone`, `b`.`alt_contact_person_name`, `b`.`alt_contact_person_phone`, `b`.`platform_link`, `b`.`batch_online_offline_flag`, `b`.`training_schedule`, `b`.`created_on`
FROM `agency_batch` `b`
LEFT JOIN `agency_inspector_master` `ai` ON `b`.`inspector_id` = `ai`.`id`
LEFT JOIN `dra_batch_inspection` `bi` ON `b`.`id` = `bi`.`batch_id`
LEFT JOIN `dra_accerdited_master` `a` ON `b`.`agency_id` = `a`.`dra_inst_registration_id`
LEFT JOIN `faculty_master` `f1` ON `b`.`first_faculty`=`f1`.`faculty_id`
LEFT JOIN `faculty_master` `f2` ON `b`.`sec_faculty`=`f2`.`faculty_id`
LEFT JOIN `faculty_master` `f3` ON `b`.`additional_first_faculty`=`f3`.`faculty_id`
LEFT JOIN `faculty_master` `f4` ON `b`.`additional_sec_faculty`=`f4`.`faculty_id`
WHERE (b.batch_status = 'Approved' OR `b`.`batch_status` = 'Hold') 
AND `b`.`batch_from_date` <= CURDATE() AND `b`.`batch_to_date` >= CURDATE()
AND `b`.`agency_id` IN('213', '199', '186', '220', '126', '198', '200', '202', '201', '231', '232', '129', '167', '193', '180', '205', '123', '6', '189', '183', '146', '188', '182', '209', '130', '158', '217', '58', '221', '222', '223', '224', '225', '226', '227', '228', '229', '230', '231')
AND `b`.`is_deleted` =0
GROUP BY `b`.`id`
ORDER BY `b`.`id` DESC
ERROR - 2025-04-24 16:16:20 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 240
ERROR - 2025-04-24 16:16:22 --> Severity: Warning --> mysqli::query(): MySQL server has gone away /home/supp0rttest/public_html/staging/system/database/drivers/mysqli/mysqli_driver.php 305
ERROR - 2025-04-24 16:16:22 --> Severity: Warning --> mysqli::query(): Error reading result set's header /home/supp0rttest/public_html/staging/system/database/drivers/mysqli/mysqli_driver.php 305
ERROR - 2025-04-24 16:16:22 --> Query error: MySQL server has gone away - Invalid query: SELECT *
FROM `dra_batch_communication`
WHERE `batch_id` = '13697'
ERROR - 2025-04-24 16:16:22 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 240
ERROR - 2025-04-24 16:16:26 --> Severity: Warning --> mysqli::query(): MySQL server has gone away /home/supp0rttest/public_html/staging/system/database/drivers/mysqli/mysqli_driver.php 305
ERROR - 2025-04-24 16:16:26 --> Severity: Warning --> mysqli::query(): Error reading result set's header /home/supp0rttest/public_html/staging/system/database/drivers/mysqli/mysqli_driver.php 305
ERROR - 2025-04-24 16:16:26 --> Query error: MySQL server has gone away - Invalid query: SELECT a.id, ac.institute_code, ac.institute_name
    FROM agency_batch a 
    LEFT JOIN dra_accerdited_master ac ON a.agency_id = ac.dra_inst_registration_id 
    WHERE a.is_deleted = 0  GROUP BY a.id ORDER BY a.batch_from_date DESC
ERROR - 2025-04-24 16:16:26 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/admin/BatchSummary.php 44
ERROR - 2025-04-24 16:16:26 --> Severity: Warning --> mysqli::query(): MySQL server has gone away /home/supp0rttest/public_html/staging/system/database/drivers/mysqli/mysqli_driver.php 305
ERROR - 2025-04-24 16:16:26 --> Severity: Warning --> mysqli::query(): Error reading result set's header /home/supp0rttest/public_html/staging/system/database/drivers/mysqli/mysqli_driver.php 305
ERROR - 2025-04-24 16:16:26 --> Query error: MySQL server has gone away - Invalid query: SELECT a.id, ac.institute_code, ac.institute_name
    FROM agency_batch a 
    LEFT JOIN dra_accerdited_master ac ON a.agency_id = ac.dra_inst_registration_id 
    WHERE a.is_deleted = 0  GROUP BY a.id ORDER BY a.batch_from_date DESC
ERROR - 2025-04-24 16:16:26 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/admin/BatchSummary.php 44
ERROR - 2025-04-24 16:16:47 --> 404 Page Not Found: Careers/Faculty_Member_IT
ERROR - 2025-04-24 16:26:15 --> Severity: Error --> Call to undefined function batch_approve_mail_V2() /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/Batch.php 1589
ERROR - 2025-04-24 18:24:29 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2025-04-24 18:31:20 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2025-04-24 18:31:33 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2025-04-24 18:34:53 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2025-04-24 18:43:46 --> 404 Page Not Found: Uploads/SCRIBE_Guidelines_2022.pdf
ERROR - 2025-04-24 18:54:31 --> 404 Page Not Found: Uploads/exam_instruction
