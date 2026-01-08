<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2024-12-23 00:49:09 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2024-12-23 00:49:17 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2024-12-23 00:49:59 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2024-12-23 00:51:17 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2024-12-23 01:11:24 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2024-12-23 01:15:27 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2024-12-23 01:17:14 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2024-12-23 01:17:48 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2024-12-23 10:19:54 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-12-23 10:19:54 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-12-23 10:19:54 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-12-23 10:19:54 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-12-23 11:20:02 --> 404 Page Not Found: Assets/js
ERROR - 2024-12-23 11:23:57 --> 404 Page Not Found: Assets/js
ERROR - 2024-12-23 11:23:57 --> 404 Page Not Found: Assets/js
ERROR - 2024-12-23 11:25:48 --> 404 Page Not Found: iibfdra/Version_2/TrainingBatches/favicon.ico
ERROR - 2024-12-23 11:27:06 --> Query error: Table 'supp0rttest_iibf_staging.dra_admit_card_details' doesn't exist - Invalid query: UPDATE `dra_admit_card_details` SET `mem_mem_no` = 800000347
WHERE `mem_exam_id` = '548858'
ERROR - 2024-12-23 11:27:06 --> Severity: Warning --> imagepng(uploads/draexaminvoice/user/dra_0_EDN_24-25_000161.jpg): failed to open stream: No such file or directory /home/supp0rttest/public_html/staging/application/helpers/invoice_helper.php 1983
ERROR - 2024-12-23 11:27:06 --> Severity: Warning --> imagepng(uploads/draexaminvoice/user/dra_0_EDN_24-25_000161.jpg): failed to open stream: No such file or directory /home/supp0rttest/public_html/staging/application/helpers/invoice_helper.php 1991
ERROR - 2024-12-23 11:27:06 --> Severity: Warning --> imagepng(uploads/draexaminvoice/supplier/dra_0_EDN_24-25_000161.jpg): failed to open stream: No such file or directory /home/supp0rttest/public_html/staging/application/helpers/invoice_helper.php 2156
ERROR - 2024-12-23 11:27:06 --> Severity: Warning --> imagepng(uploads/draexaminvoice/supplier/dra_0_EDN_24-25_000161.jpg): failed to open stream: No such file or directory /home/supp0rttest/public_html/staging/application/helpers/invoice_helper.php 2164
ERROR - 2024-12-23 12:52:29 --> 404 Page Not Found: iibfdra/Version_2/InspectorHome/batch_inspection_report
ERROR - 2024-12-23 12:52:33 --> 404 Page Not Found: iibfdra/Version_2/InspectorHome/batch_inspection_report
ERROR - 2024-12-23 13:06:11 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND   b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,18' at line 12 - Invalid query: SELECT a.institute_name,a.short_inst_name,COUNT(bi.id) as reported,GROUP_CONCAT(bi.overall_compliance) as overall_compliance_list, ai.inspector_name, b.agency_id,b.id,b.batch_code,  b.batch_name, b.batch_from_date, b.batch_to_date, b.online_training_platform, b.timing_from, b.timing_to, b.hours, b.training_medium, b.total_candidates, f1.faculty_id as first_faculty_id, f1.faculty_name as first_faculty_name,f1.faculty_code as first_faculty_code,f1.faculty_photo as first_faculty_photo, f2.faculty_id as sec_faculty_id, f2.faculty_name as sec_faculty_name, f2.faculty_code as sec_faculty_code,f2.faculty_photo as sec_faculty_photo, f3.faculty_id as add_first_faculty_id, f3.faculty_name as add_first_faculty_name, f3.faculty_code as add_first_faculty_code,f3.faculty_photo as add_first_faculty_photo, f4.faculty_id as add_sec_faculty_id, f4.faculty_name as add_sec_faculty_name, f4.faculty_code as add_sec_faculty_code,f4.faculty_photo as add_sec_faculty_photo, b.contact_person_name, b.contact_person_phone, b.alt_contact_person_name, b.alt_contact_person_phone, b.platform_link, b.batch_online_offline_flag, b.training_schedule, b.created_on
	        FROM agency_batch b 
          LEFT JOIN  dra_accerdited_master a ON a.dra_inst_registration_id = b.agency_id
          LEFT JOIN  dra_batch_inspection bi ON bi.batch_id = b.id
          LEFT JOIN agency_inspector_master ai ON ai.id = b.inspector_id
          LEFT JOIN faculty_master f1 ON b.first_faculty=f1.faculty_id 
          LEFT JOIN faculty_master f2 ON b.sec_faculty=f2.faculty_id
	        LEFT JOIN faculty_master f3 ON b.additional_first_faculty=f3.faculty_id
	        LEFT JOIN faculty_master f4 ON b.additional_sec_faculty=f4.faculty_id
	        WHERE (b.batch_status = 'Approved' OR b.batch_status = 'Cancelled' OR b.batch_status = 'Hold')
	        --- AND b.is_deleted = 0
	        AND   b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,180,205,123,6,189,183,146,188,182,209,130,158,217,58,221,222,223,224,225,226,227,228,229,230,231) AND b.is_deleted = 0  ORDER BY b.id DESC
ERROR - 2024-12-23 13:06:11 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/InspectorHome.php 1902
ERROR - 2024-12-23 13:06:14 --> 404 Page Not Found: iibfdra/Version_2/InspectorHome/batch_inspection_report
ERROR - 2024-12-23 13:06:16 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND   b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,18' at line 12 - Invalid query: SELECT a.institute_name,a.short_inst_name,COUNT(bi.id) as reported,GROUP_CONCAT(bi.overall_compliance) as overall_compliance_list, ai.inspector_name, b.agency_id,b.id,b.batch_code,  b.batch_name, b.batch_from_date, b.batch_to_date, b.online_training_platform, b.timing_from, b.timing_to, b.hours, b.training_medium, b.total_candidates, f1.faculty_id as first_faculty_id, f1.faculty_name as first_faculty_name,f1.faculty_code as first_faculty_code,f1.faculty_photo as first_faculty_photo, f2.faculty_id as sec_faculty_id, f2.faculty_name as sec_faculty_name, f2.faculty_code as sec_faculty_code,f2.faculty_photo as sec_faculty_photo, f3.faculty_id as add_first_faculty_id, f3.faculty_name as add_first_faculty_name, f3.faculty_code as add_first_faculty_code,f3.faculty_photo as add_first_faculty_photo, f4.faculty_id as add_sec_faculty_id, f4.faculty_name as add_sec_faculty_name, f4.faculty_code as add_sec_faculty_code,f4.faculty_photo as add_sec_faculty_photo, b.contact_person_name, b.contact_person_phone, b.alt_contact_person_name, b.alt_contact_person_phone, b.platform_link, b.batch_online_offline_flag, b.training_schedule, b.created_on
	        FROM agency_batch b 
          LEFT JOIN agency_inspector_master ai ON ai.id = b.inspector_id
          LEFT JOIN  dra_batch_inspection bi ON bi.batch_id = b.id
          LEFT JOIN  dra_accerdited_master a ON a.dra_inst_registration_id = b.agency_id
          LEFT JOIN faculty_master f1 ON b.first_faculty=f1.faculty_id 
          LEFT JOIN faculty_master f2 ON b.sec_faculty=f2.faculty_id
	        LEFT JOIN faculty_master f3 ON b.additional_first_faculty=f3.faculty_id
	        LEFT JOIN faculty_master f4 ON b.additional_sec_faculty=f4.faculty_id
	        WHERE (b.batch_status = 'Approved' OR b.batch_status = 'Cancelled' OR b.batch_status = 'Hold')
	        --- AND b.is_deleted = 0
	        AND   b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,180,205,123,6,189,183,146,188,182,209,130,158,217,58,221,222,223,224,225,226,227,228,229,230,231) AND b.is_deleted = 0  ORDER BY b.id DESC
ERROR - 2024-12-23 13:06:16 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/InspectorHome.php 1902
ERROR - 2024-12-23 13:06:18 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND   b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,18' at line 12 - Invalid query: SELECT a.institute_name,a.short_inst_name,COUNT(bi.id) as reported,GROUP_CONCAT(bi.overall_compliance) as overall_compliance_list, ai.inspector_name, b.agency_id,b.id,b.batch_code,  b.batch_name, b.batch_from_date, b.batch_to_date, b.online_training_platform, b.timing_from, b.timing_to, b.hours, b.training_medium, b.total_candidates, f1.faculty_id as first_faculty_id, f1.faculty_name as first_faculty_name,f1.faculty_code as first_faculty_code,f1.faculty_photo as first_faculty_photo, f2.faculty_id as sec_faculty_id, f2.faculty_name as sec_faculty_name, f2.faculty_code as sec_faculty_code,f2.faculty_photo as sec_faculty_photo, f3.faculty_id as add_first_faculty_id, f3.faculty_name as add_first_faculty_name, f3.faculty_code as add_first_faculty_code,f3.faculty_photo as add_first_faculty_photo, f4.faculty_id as add_sec_faculty_id, f4.faculty_name as add_sec_faculty_name, f4.faculty_code as add_sec_faculty_code,f4.faculty_photo as add_sec_faculty_photo, b.contact_person_name, b.contact_person_phone, b.alt_contact_person_name, b.alt_contact_person_phone, b.platform_link, b.batch_online_offline_flag, b.training_schedule, b.created_on
	        FROM agency_batch b 
          LEFT JOIN agency_inspector_master ai ON ai.id = b.inspector_id
          LEFT JOIN  dra_batch_inspection bi ON bi.batch_id = b.id
          LEFT JOIN  dra_accerdited_master a ON a.dra_inst_registration_id = b.agency_id
          LEFT JOIN faculty_master f1 ON b.first_faculty=f1.faculty_id 
          LEFT JOIN faculty_master f2 ON b.sec_faculty=f2.faculty_id
	        LEFT JOIN faculty_master f3 ON b.additional_first_faculty=f3.faculty_id
	        LEFT JOIN faculty_master f4 ON b.additional_sec_faculty=f4.faculty_id
	        WHERE (b.batch_status = 'Approved' OR b.batch_status = 'Cancelled' OR b.batch_status = 'Hold')
	        --- AND b.is_deleted = 0
	        AND   b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,180,205,123,6,189,183,146,188,182,209,130,158,217,58,221,222,223,224,225,226,227,228,229,230,231) AND b.is_deleted = 0  ORDER BY b.id DESC
ERROR - 2024-12-23 13:06:18 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/InspectorHome.php 1902
ERROR - 2024-12-23 13:06:20 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND   b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,18' at line 12 - Invalid query: SELECT a.institute_name,a.short_inst_name,COUNT(bi.id) as reported,GROUP_CONCAT(bi.overall_compliance) as overall_compliance_list, ai.inspector_name, b.agency_id,b.id,b.batch_code,  b.batch_name, b.batch_from_date, b.batch_to_date, b.online_training_platform, b.timing_from, b.timing_to, b.hours, b.training_medium, b.total_candidates, f1.faculty_id as first_faculty_id, f1.faculty_name as first_faculty_name,f1.faculty_code as first_faculty_code,f1.faculty_photo as first_faculty_photo, f2.faculty_id as sec_faculty_id, f2.faculty_name as sec_faculty_name, f2.faculty_code as sec_faculty_code,f2.faculty_photo as sec_faculty_photo, f3.faculty_id as add_first_faculty_id, f3.faculty_name as add_first_faculty_name, f3.faculty_code as add_first_faculty_code,f3.faculty_photo as add_first_faculty_photo, f4.faculty_id as add_sec_faculty_id, f4.faculty_name as add_sec_faculty_name, f4.faculty_code as add_sec_faculty_code,f4.faculty_photo as add_sec_faculty_photo, b.contact_person_name, b.contact_person_phone, b.alt_contact_person_name, b.alt_contact_person_phone, b.platform_link, b.batch_online_offline_flag, b.training_schedule, b.created_on
	        FROM agency_batch b 
          LEFT JOIN agency_inspector_master ai ON ai.id = b.inspector_id
          LEFT JOIN  dra_batch_inspection bi ON bi.batch_id = b.id
          LEFT JOIN  dra_accerdited_master a ON a.dra_inst_registration_id = b.agency_id
          LEFT JOIN faculty_master f1 ON b.first_faculty=f1.faculty_id 
          LEFT JOIN faculty_master f2 ON b.sec_faculty=f2.faculty_id
	        LEFT JOIN faculty_master f3 ON b.additional_first_faculty=f3.faculty_id
	        LEFT JOIN faculty_master f4 ON b.additional_sec_faculty=f4.faculty_id
	        WHERE (b.batch_status = 'Approved' OR b.batch_status = 'Cancelled' OR b.batch_status = 'Hold')
	        --- AND b.is_deleted = 0
	        AND   b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,180,205,123,6,189,183,146,188,182,209,130,158,217,58,221,222,223,224,225,226,227,228,229,230,231) AND b.is_deleted = 0  ORDER BY b.id DESC
ERROR - 2024-12-23 13:06:20 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/InspectorHome.php 1902
ERROR - 2024-12-23 13:06:22 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND   b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,18' at line 12 - Invalid query: SELECT a.institute_name,a.short_inst_name,COUNT(bi.id) as reported,GROUP_CONCAT(bi.overall_compliance) as overall_compliance_list, ai.inspector_name, b.agency_id,b.id,b.batch_code,  b.batch_name, b.batch_from_date, b.batch_to_date, b.online_training_platform, b.timing_from, b.timing_to, b.hours, b.training_medium, b.total_candidates, f1.faculty_id as first_faculty_id, f1.faculty_name as first_faculty_name,f1.faculty_code as first_faculty_code,f1.faculty_photo as first_faculty_photo, f2.faculty_id as sec_faculty_id, f2.faculty_name as sec_faculty_name, f2.faculty_code as sec_faculty_code,f2.faculty_photo as sec_faculty_photo, f3.faculty_id as add_first_faculty_id, f3.faculty_name as add_first_faculty_name, f3.faculty_code as add_first_faculty_code,f3.faculty_photo as add_first_faculty_photo, f4.faculty_id as add_sec_faculty_id, f4.faculty_name as add_sec_faculty_name, f4.faculty_code as add_sec_faculty_code,f4.faculty_photo as add_sec_faculty_photo, b.contact_person_name, b.contact_person_phone, b.alt_contact_person_name, b.alt_contact_person_phone, b.platform_link, b.batch_online_offline_flag, b.training_schedule, b.created_on
	        FROM agency_batch b 
          LEFT JOIN agency_inspector_master ai ON ai.id = b.inspector_id
          LEFT JOIN  dra_batch_inspection bi ON bi.batch_id = b.id
          LEFT JOIN  dra_accerdited_master a ON a.dra_inst_registration_id = b.agency_id
          LEFT JOIN faculty_master f1 ON b.first_faculty=f1.faculty_id 
          LEFT JOIN faculty_master f2 ON b.sec_faculty=f2.faculty_id
	        LEFT JOIN faculty_master f3 ON b.additional_first_faculty=f3.faculty_id
	        LEFT JOIN faculty_master f4 ON b.additional_sec_faculty=f4.faculty_id
	        WHERE (b.batch_status = 'Approved' OR b.batch_status = 'Cancelled' OR b.batch_status = 'Hold')
	        --- AND b.is_deleted = 0
	        AND   b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,180,205,123,6,189,183,146,188,182,209,130,158,217,58,221,222,223,224,225,226,227,228,229,230,231) AND b.is_deleted = 0  ORDER BY b.id DESC
ERROR - 2024-12-23 13:06:22 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/InspectorHome.php 1902
ERROR - 2024-12-23 13:07:18 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND   b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,18' at line 12 - Invalid query: SELECT a.institute_name,a.short_inst_name,GROUP_CONCAT(bi.overall_compliance) as overall_compliance_list, ai.inspector_name, b.agency_id,b.id,b.batch_code,  b.batch_name, b.batch_from_date, b.batch_to_date, b.online_training_platform, b.timing_from, b.timing_to, b.hours, b.training_medium, b.total_candidates, f1.faculty_id as first_faculty_id, f1.faculty_name as first_faculty_name,f1.faculty_code as first_faculty_code,f1.faculty_photo as first_faculty_photo, f2.faculty_id as sec_faculty_id, f2.faculty_name as sec_faculty_name, f2.faculty_code as sec_faculty_code,f2.faculty_photo as sec_faculty_photo, f3.faculty_id as add_first_faculty_id, f3.faculty_name as add_first_faculty_name, f3.faculty_code as add_first_faculty_code,f3.faculty_photo as add_first_faculty_photo, f4.faculty_id as add_sec_faculty_id, f4.faculty_name as add_sec_faculty_name, f4.faculty_code as add_sec_faculty_code,f4.faculty_photo as add_sec_faculty_photo, b.contact_person_name, b.contact_person_phone, b.alt_contact_person_name, b.alt_contact_person_phone, b.platform_link, b.batch_online_offline_flag, b.training_schedule, b.created_on
	        FROM agency_batch b 
          LEFT JOIN agency_inspector_master ai ON ai.id = b.inspector_id
          LEFT JOIN  dra_batch_inspection bi ON bi.batch_id = b.id
          LEFT JOIN  dra_accerdited_master a ON a.dra_inst_registration_id = b.agency_id
          LEFT JOIN faculty_master f1 ON b.first_faculty=f1.faculty_id 
          LEFT JOIN faculty_master f2 ON b.sec_faculty=f2.faculty_id
	        LEFT JOIN faculty_master f3 ON b.additional_first_faculty=f3.faculty_id
	        LEFT JOIN faculty_master f4 ON b.additional_sec_faculty=f4.faculty_id
	        WHERE (b.batch_status = 'Approved' OR b.batch_status = 'Cancelled' OR b.batch_status = 'Hold')
	        --- AND b.is_deleted = 0
	        AND   b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,180,205,123,6,189,183,146,188,182,209,130,158,217,58,221,222,223,224,225,226,227,228,229,230,231) AND b.is_deleted = 0  ORDER BY b.id DESC
ERROR - 2024-12-23 13:07:18 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/InspectorHome.php 1902
ERROR - 2024-12-23 13:07:43 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND   b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,18' at line 12 - Invalid query: SELECT a.institute_name,a.short_inst_name,GROUP_CONCAT(bi.overall_compliance) as overall_compliance_list, ai.inspector_name, b.agency_id,b.id,b.batch_code,  b.batch_name, b.batch_from_date, b.batch_to_date, b.online_training_platform, b.timing_from, b.timing_to, b.hours, b.training_medium, b.total_candidates, f1.faculty_id as first_faculty_id, f1.faculty_name as first_faculty_name,f1.faculty_code as first_faculty_code,f1.faculty_photo as first_faculty_photo, f2.faculty_id as sec_faculty_id, f2.faculty_name as sec_faculty_name, f2.faculty_code as sec_faculty_code,f2.faculty_photo as sec_faculty_photo, f3.faculty_id as add_first_faculty_id, f3.faculty_name as add_first_faculty_name, f3.faculty_code as add_first_faculty_code,f3.faculty_photo as add_first_faculty_photo, f4.faculty_id as add_sec_faculty_id, f4.faculty_name as add_sec_faculty_name, f4.faculty_code as add_sec_faculty_code,f4.faculty_photo as add_sec_faculty_photo, b.contact_person_name, b.contact_person_phone, b.alt_contact_person_name, b.alt_contact_person_phone, b.platform_link, b.batch_online_offline_flag, b.training_schedule, b.created_on
	        FROM agency_batch b 
          LEFT JOIN agency_inspector_master ai ON ai.id = b.inspector_id
          LEFT JOIN  dra_batch_inspection bi ON bi.batch_id = b.id
          LEFT JOIN  dra_accerdited_master a ON a.dra_inst_registration_id = b.agency_id
          LEFT JOIN faculty_master f1 ON b.first_faculty=f1.faculty_id 
          LEFT JOIN faculty_master f2 ON b.sec_faculty=f2.faculty_id
	        LEFT JOIN faculty_master f3 ON b.additional_first_faculty=f3.faculty_id
	        LEFT JOIN faculty_master f4 ON b.additional_sec_faculty=f4.faculty_id
	        WHERE (b.batch_status = 'Approved' OR b.batch_status = 'Cancelled' OR b.batch_status = 'Hold')
	        --- AND b.is_deleted = 0
	        AND   b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,180,205,123,6,189,183,146,188,182,209,130,158,217,58,221,222,223,224,225,226,227,228,229,230,231) AND b.is_deleted = 0  ORDER BY b.id DESC
ERROR - 2024-12-23 13:07:43 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/InspectorHome.php 1902
ERROR - 2024-12-23 13:07:46 --> 404 Page Not Found: iibfdra/Version_2/InspectorHome/batch_inspection_report
ERROR - 2024-12-23 13:07:49 --> 404 Page Not Found: iibfdra/Version_2/InspectorHome/batch_inspection_report
ERROR - 2024-12-23 13:07:51 --> 404 Page Not Found: iibfdra/Version_2/InspectorHome/batch_inspection_report
ERROR - 2024-12-23 13:07:55 --> 404 Page Not Found: iibfdra/Version_2/InspectorHome/batch_inspection_report
ERROR - 2024-12-23 13:08:31 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND   b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,18' at line 12 - Invalid query: SELECT a.institute_name,a.short_inst_name,COUNT(bi.id) as reported,GROUP_CONCAT(bi.overall_compliance) as overall_compliance_list, ai.inspector_name, b.agency_id,b.id,b.batch_code,  b.batch_name, b.batch_from_date, b.batch_to_date, b.online_training_platform, b.timing_from, b.timing_to, b.hours, b.training_medium, b.total_candidates, f1.faculty_id as first_faculty_id, f1.faculty_name as first_faculty_name,f1.faculty_code as first_faculty_code,f1.faculty_photo as first_faculty_photo, f2.faculty_id as sec_faculty_id, f2.faculty_name as sec_faculty_name, f2.faculty_code as sec_faculty_code,f2.faculty_photo as sec_faculty_photo, f3.faculty_id as add_first_faculty_id, f3.faculty_name as add_first_faculty_name, f3.faculty_code as add_first_faculty_code,f3.faculty_photo as add_first_faculty_photo, f4.faculty_id as add_sec_faculty_id, f4.faculty_name as add_sec_faculty_name, f4.faculty_code as add_sec_faculty_code,f4.faculty_photo as add_sec_faculty_photo, b.contact_person_name, b.contact_person_phone, b.alt_contact_person_name, b.alt_contact_person_phone, b.platform_link, b.batch_online_offline_flag, b.training_schedule, b.created_on
	        FROM agency_batch b 
          LEFT JOIN  dra_accerdited_master a ON a.dra_inst_registration_id = b.agency_id
          LEFT JOIN  dra_batch_inspection bi ON bi.batch_id = b.id
          LEFT JOIN agency_inspector_master ai ON ai.id = b.inspector_id
          LEFT JOIN faculty_master f1 ON b.first_faculty=f1.faculty_id 
          LEFT JOIN faculty_master f2 ON b.sec_faculty=f2.faculty_id
	        LEFT JOIN faculty_master f3 ON b.additional_first_faculty=f3.faculty_id
	        LEFT JOIN faculty_master f4 ON b.additional_sec_faculty=f4.faculty_id
	        WHERE (b.batch_status = 'Approved' OR b.batch_status = 'Cancelled' OR b.batch_status = 'Hold')
	        --- AND b.is_deleted = 0
	        AND   b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,180,205,123,6,189,183,146,188,182,209,130,158,217,58,221,222,223,224,225,226,227,228,229,230,231) AND b.is_deleted = 0  ORDER BY b.id DESC
ERROR - 2024-12-23 13:08:31 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/InspectorHome.php 1902
ERROR - 2024-12-23 13:10:30 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,180,' at line 12 - Invalid query: SELECT a.institute_name,a.short_inst_name,COUNT(bi.id) as reported,GROUP_CONCAT(bi.overall_compliance) as overall_compliance_list, ai.inspector_name, b.agency_id,b.id,b.batch_code,  b.batch_name, b.batch_from_date, b.batch_to_date, b.online_training_platform, b.timing_from, b.timing_to, b.hours, b.training_medium, b.total_candidates, f1.faculty_id as first_faculty_id, f1.faculty_name as first_faculty_name,f1.faculty_code as first_faculty_code,f1.faculty_photo as first_faculty_photo, f2.faculty_id as sec_faculty_id, f2.faculty_name as sec_faculty_name, f2.faculty_code as sec_faculty_code,f2.faculty_photo as sec_faculty_photo, f3.faculty_id as add_first_faculty_id, f3.faculty_name as add_first_faculty_name, f3.faculty_code as add_first_faculty_code,f3.faculty_photo as add_first_faculty_photo, f4.faculty_id as add_sec_faculty_id, f4.faculty_name as add_sec_faculty_name, f4.faculty_code as add_sec_faculty_code,f4.faculty_photo as add_sec_faculty_photo, b.contact_person_name, b.contact_person_phone, b.alt_contact_person_name, b.alt_contact_person_phone, b.platform_link, b.batch_online_offline_flag, b.training_schedule, b.created_on
	        FROM agency_batch b 
          LEFT JOIN  dra_accerdited_master a ON a.dra_inst_registration_id = b.agency_id
          LEFT JOIN  dra_batch_inspection bi ON bi.batch_id = b.id
          LEFT JOIN agency_inspector_master ai ON ai.id = b.inspector_id
          LEFT JOIN faculty_master f1 ON b.first_faculty=f1.faculty_id 
          LEFT JOIN faculty_master f2 ON b.sec_faculty=f2.faculty_id
	        LEFT JOIN faculty_master f3 ON b.additional_first_faculty=f3.faculty_id
	        LEFT JOIN faculty_master f4 ON b.additional_sec_faculty=f4.faculty_id
	        WHERE (b.batch_status = 'Approved' OR b.batch_status = 'Cancelled' OR b.batch_status = 'Hold')
	        --- AND b.is_deleted = 0
	        AND b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,180,205,123,6,189,183,146,188,182,209,130,158,217,58,221,222,223,224,225,226,227,228,229,230,231) AND b.is_deleted = 0  ORDER BY b.id DESC
ERROR - 2024-12-23 13:10:30 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/InspectorHome.php 1902
ERROR - 2024-12-23 13:10:33 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,180,' at line 12 - Invalid query: SELECT a.institute_name,a.short_inst_name,COUNT(bi.id) as reported,GROUP_CONCAT(bi.overall_compliance) as overall_compliance_list, ai.inspector_name, b.agency_id,b.id,b.batch_code,  b.batch_name, b.batch_from_date, b.batch_to_date, b.online_training_platform, b.timing_from, b.timing_to, b.hours, b.training_medium, b.total_candidates, f1.faculty_id as first_faculty_id, f1.faculty_name as first_faculty_name,f1.faculty_code as first_faculty_code,f1.faculty_photo as first_faculty_photo, f2.faculty_id as sec_faculty_id, f2.faculty_name as sec_faculty_name, f2.faculty_code as sec_faculty_code,f2.faculty_photo as sec_faculty_photo, f3.faculty_id as add_first_faculty_id, f3.faculty_name as add_first_faculty_name, f3.faculty_code as add_first_faculty_code,f3.faculty_photo as add_first_faculty_photo, f4.faculty_id as add_sec_faculty_id, f4.faculty_name as add_sec_faculty_name, f4.faculty_code as add_sec_faculty_code,f4.faculty_photo as add_sec_faculty_photo, b.contact_person_name, b.contact_person_phone, b.alt_contact_person_name, b.alt_contact_person_phone, b.platform_link, b.batch_online_offline_flag, b.training_schedule, b.created_on
	        FROM agency_batch b 
          LEFT JOIN  dra_accerdited_master a ON a.dra_inst_registration_id = b.agency_id
          LEFT JOIN  dra_batch_inspection bi ON bi.batch_id = b.id
          LEFT JOIN agency_inspector_master ai ON ai.id = b.inspector_id
          LEFT JOIN faculty_master f1 ON b.first_faculty=f1.faculty_id 
          LEFT JOIN faculty_master f2 ON b.sec_faculty=f2.faculty_id
	        LEFT JOIN faculty_master f3 ON b.additional_first_faculty=f3.faculty_id
	        LEFT JOIN faculty_master f4 ON b.additional_sec_faculty=f4.faculty_id
	        WHERE (b.batch_status = 'Approved' OR b.batch_status = 'Cancelled' OR b.batch_status = 'Hold')
	        --- AND b.is_deleted = 0
	        AND b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,180,205,123,6,189,183,146,188,182,209,130,158,217,58,221,222,223,224,225,226,227,228,229,230,231) AND b.is_deleted = 0  ORDER BY b.id DESC
ERROR - 2024-12-23 13:10:33 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/InspectorHome.php 1902
ERROR - 2024-12-23 14:18:18 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,180,' at line 12 - Invalid query: SELECT a.institute_name,a.short_inst_name,COUNT(bi.id) as reported,GROUP_CONCAT(bi.overall_compliance) as overall_compliance_list, ai.inspector_name, b.agency_id,b.id,b.batch_code,  b.batch_name, b.batch_from_date, b.batch_to_date, b.online_training_platform, b.timing_from, b.timing_to, b.hours, b.training_medium, b.total_candidates, f1.faculty_id as first_faculty_id, f1.faculty_name as first_faculty_name,f1.faculty_code as first_faculty_code,f1.faculty_photo as first_faculty_photo, f2.faculty_id as sec_faculty_id, f2.faculty_name as sec_faculty_name, f2.faculty_code as sec_faculty_code,f2.faculty_photo as sec_faculty_photo, f3.faculty_id as add_first_faculty_id, f3.faculty_name as add_first_faculty_name, f3.faculty_code as add_first_faculty_code,f3.faculty_photo as add_first_faculty_photo, f4.faculty_id as add_sec_faculty_id, f4.faculty_name as add_sec_faculty_name, f4.faculty_code as add_sec_faculty_code,f4.faculty_photo as add_sec_faculty_photo, b.contact_person_name, b.contact_person_phone, b.alt_contact_person_name, b.alt_contact_person_phone, b.platform_link, b.batch_online_offline_flag, b.training_schedule, b.created_on
	        FROM agency_batch b 
          LEFT JOIN  dra_accerdited_master a ON a.dra_inst_registration_id = b.agency_id
          LEFT JOIN  dra_batch_inspection bi ON bi.batch_id = b.id
          LEFT JOIN agency_inspector_master ai ON ai.id = b.inspector_id
          LEFT JOIN faculty_master f1 ON b.first_faculty=f1.faculty_id 
          LEFT JOIN faculty_master f2 ON b.sec_faculty=f2.faculty_id
	        LEFT JOIN faculty_master f3 ON b.additional_first_faculty=f3.faculty_id
	        LEFT JOIN faculty_master f4 ON b.additional_sec_faculty=f4.faculty_id
	        WHERE (b.batch_status = 'Approved' OR b.batch_status = 'Cancelled' OR b.batch_status = 'Hold')
	        --- AND b.is_deleted = 0
	        AND b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,180,205,123,6,189,183,146,188,182,209,130,158,217,58,221,222,223,224,225,226,227,228,229,230,231) AND b.is_deleted = 0  ORDER BY b.id DESC
ERROR - 2024-12-23 14:18:18 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/InspectorHome.php 1902
ERROR - 2024-12-23 14:22:02 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,180,' at line 12 - Invalid query: SELECT a.institute_name,a.short_inst_name,COUNT(bi.id) as reported,GROUP_CONCAT(bi.overall_compliance) as overall_compliance_list, ai.inspector_name, b.agency_id,b.id,b.batch_code,  b.batch_name, b.batch_from_date, b.batch_to_date, b.online_training_platform, b.timing_from, b.timing_to, b.hours, b.training_medium, b.total_candidates, f1.faculty_id as first_faculty_id, f1.faculty_name as first_faculty_name,f1.faculty_code as first_faculty_code,f1.faculty_photo as first_faculty_photo, f2.faculty_id as sec_faculty_id, f2.faculty_name as sec_faculty_name, f2.faculty_code as sec_faculty_code,f2.faculty_photo as sec_faculty_photo, f3.faculty_id as add_first_faculty_id, f3.faculty_name as add_first_faculty_name, f3.faculty_code as add_first_faculty_code,f3.faculty_photo as add_first_faculty_photo, f4.faculty_id as add_sec_faculty_id, f4.faculty_name as add_sec_faculty_name, f4.faculty_code as add_sec_faculty_code,f4.faculty_photo as add_sec_faculty_photo, b.contact_person_name, b.contact_person_phone, b.alt_contact_person_name, b.alt_contact_person_phone, b.platform_link, b.batch_online_offline_flag, b.training_schedule, b.created_on
	        FROM agency_batch b 
          LEFT JOIN  dra_accerdited_master a ON a.dra_inst_registration_id = b.agency_id
          LEFT JOIN  dra_batch_inspection bi ON bi.batch_id = b.id
          LEFT JOIN agency_inspector_master ai ON ai.id = b.inspector_id
          LEFT JOIN faculty_master f1 ON b.first_faculty=f1.faculty_id 
          LEFT JOIN faculty_master f2 ON b.sec_faculty=f2.faculty_id
	        LEFT JOIN faculty_master f3 ON b.additional_first_faculty=f3.faculty_id
	        LEFT JOIN faculty_master f4 ON b.additional_sec_faculty=f4.faculty_id
	        WHERE (b.batch_status = 'Approved' OR b.batch_status = 'Cancelled' OR b.batch_status = 'Hold')
	        --- AND b.is_deleted = 0
	        AND b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,180,205,123,6,189,183,146,188,182,209,130,158,217,58,221,222,223,224,225,226,227,228,229,230,231) AND b.is_deleted = 0  ORDER BY b.id DESC
ERROR - 2024-12-23 14:22:02 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/InspectorHome.php 1902
ERROR - 2024-12-23 14:22:05 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,180,' at line 12 - Invalid query: SELECT a.institute_name,a.short_inst_name,COUNT(bi.id) as reported,GROUP_CONCAT(bi.overall_compliance) as overall_compliance_list, ai.inspector_name, b.agency_id,b.id,b.batch_code,  b.batch_name, b.batch_from_date, b.batch_to_date, b.online_training_platform, b.timing_from, b.timing_to, b.hours, b.training_medium, b.total_candidates, f1.faculty_id as first_faculty_id, f1.faculty_name as first_faculty_name,f1.faculty_code as first_faculty_code,f1.faculty_photo as first_faculty_photo, f2.faculty_id as sec_faculty_id, f2.faculty_name as sec_faculty_name, f2.faculty_code as sec_faculty_code,f2.faculty_photo as sec_faculty_photo, f3.faculty_id as add_first_faculty_id, f3.faculty_name as add_first_faculty_name, f3.faculty_code as add_first_faculty_code,f3.faculty_photo as add_first_faculty_photo, f4.faculty_id as add_sec_faculty_id, f4.faculty_name as add_sec_faculty_name, f4.faculty_code as add_sec_faculty_code,f4.faculty_photo as add_sec_faculty_photo, b.contact_person_name, b.contact_person_phone, b.alt_contact_person_name, b.alt_contact_person_phone, b.platform_link, b.batch_online_offline_flag, b.training_schedule, b.created_on
	        FROM agency_batch b 
          LEFT JOIN  dra_accerdited_master a ON a.dra_inst_registration_id = b.agency_id
          LEFT JOIN  dra_batch_inspection bi ON bi.batch_id = b.id
          LEFT JOIN agency_inspector_master ai ON ai.id = b.inspector_id
          LEFT JOIN faculty_master f1 ON b.first_faculty=f1.faculty_id 
          LEFT JOIN faculty_master f2 ON b.sec_faculty=f2.faculty_id
	        LEFT JOIN faculty_master f3 ON b.additional_first_faculty=f3.faculty_id
	        LEFT JOIN faculty_master f4 ON b.additional_sec_faculty=f4.faculty_id
	        WHERE (b.batch_status = 'Approved' OR b.batch_status = 'Cancelled' OR b.batch_status = 'Hold')
	        --- AND b.is_deleted = 0
	        AND b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,180,205,123,6,189,183,146,188,182,209,130,158,217,58,221,222,223,224,225,226,227,228,229,230,231) AND b.is_deleted = 0  ORDER BY b.id DESC
ERROR - 2024-12-23 14:22:05 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/InspectorHome.php 1902
ERROR - 2024-12-23 14:23:07 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,180,' at line 12 - Invalid query: SELECT a.institute_name,a.short_inst_name,COUNT(bi.id) as reported,GROUP_CONCAT(bi.overall_compliance) as overall_compliance_list, ai.inspector_name, b.agency_id,b.id,b.batch_code,  b.batch_name, b.batch_from_date, b.batch_to_date, b.online_training_platform, b.timing_from, b.timing_to, b.hours, b.training_medium, b.total_candidates, f1.faculty_id as first_faculty_id, f1.faculty_name as first_faculty_name,f1.faculty_code as first_faculty_code,f1.faculty_photo as first_faculty_photo, f2.faculty_id as sec_faculty_id, f2.faculty_name as sec_faculty_name, f2.faculty_code as sec_faculty_code,f2.faculty_photo as sec_faculty_photo, f3.faculty_id as add_first_faculty_id, f3.faculty_name as add_first_faculty_name, f3.faculty_code as add_first_faculty_code,f3.faculty_photo as add_first_faculty_photo, f4.faculty_id as add_sec_faculty_id, f4.faculty_name as add_sec_faculty_name, f4.faculty_code as add_sec_faculty_code,f4.faculty_photo as add_sec_faculty_photo, b.contact_person_name, b.contact_person_phone, b.alt_contact_person_name, b.alt_contact_person_phone, b.platform_link, b.batch_online_offline_flag, b.training_schedule, b.created_on
	        FROM agency_batch b 
          LEFT JOIN  dra_accerdited_master a ON a.dra_inst_registration_id = b.agency_id
          LEFT JOIN  dra_batch_inspection bi ON bi.batch_id = b.id
          LEFT JOIN agency_inspector_master ai ON ai.id = b.inspector_id
          LEFT JOIN faculty_master f1 ON b.first_faculty=f1.faculty_id 
          LEFT JOIN faculty_master f2 ON b.sec_faculty=f2.faculty_id
	        LEFT JOIN faculty_master f3 ON b.additional_first_faculty=f3.faculty_id
	        LEFT JOIN faculty_master f4 ON b.additional_sec_faculty=f4.faculty_id
	        WHERE (b.batch_status = 'Approved' OR b.batch_status = 'Cancelled' OR b.batch_status = 'Hold')
	        --- AND b.is_deleted = 0
	        AND b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,180,205,123,6,189,183,146,188,182,209,130,158,217,58,221,222,223,224,225,226,227,228,229,230,231) AND b.is_deleted = 0  ORDER BY b.id DESC
ERROR - 2024-12-23 14:23:07 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/InspectorHome.php 1903
ERROR - 2024-12-23 14:23:12 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,180,' at line 12 - Invalid query: SELECT a.institute_name,a.short_inst_name,COUNT(bi.id) as reported,GROUP_CONCAT(bi.overall_compliance) as overall_compliance_list, ai.inspector_name, b.agency_id,b.id,b.batch_code,  b.batch_name, b.batch_from_date, b.batch_to_date, b.online_training_platform, b.timing_from, b.timing_to, b.hours, b.training_medium, b.total_candidates, f1.faculty_id as first_faculty_id, f1.faculty_name as first_faculty_name,f1.faculty_code as first_faculty_code,f1.faculty_photo as first_faculty_photo, f2.faculty_id as sec_faculty_id, f2.faculty_name as sec_faculty_name, f2.faculty_code as sec_faculty_code,f2.faculty_photo as sec_faculty_photo, f3.faculty_id as add_first_faculty_id, f3.faculty_name as add_first_faculty_name, f3.faculty_code as add_first_faculty_code,f3.faculty_photo as add_first_faculty_photo, f4.faculty_id as add_sec_faculty_id, f4.faculty_name as add_sec_faculty_name, f4.faculty_code as add_sec_faculty_code,f4.faculty_photo as add_sec_faculty_photo, b.contact_person_name, b.contact_person_phone, b.alt_contact_person_name, b.alt_contact_person_phone, b.platform_link, b.batch_online_offline_flag, b.training_schedule, b.created_on
	        FROM agency_batch b 
          LEFT JOIN  dra_accerdited_master a ON a.dra_inst_registration_id = b.agency_id
          LEFT JOIN  dra_batch_inspection bi ON bi.batch_id = b.id
          LEFT JOIN agency_inspector_master ai ON ai.id = b.inspector_id
          LEFT JOIN faculty_master f1 ON b.first_faculty=f1.faculty_id 
          LEFT JOIN faculty_master f2 ON b.sec_faculty=f2.faculty_id
	        LEFT JOIN faculty_master f3 ON b.additional_first_faculty=f3.faculty_id
	        LEFT JOIN faculty_master f4 ON b.additional_sec_faculty=f4.faculty_id
	        WHERE (b.batch_status = 'Approved' OR b.batch_status = 'Cancelled' OR b.batch_status = 'Hold')
	        --- AND b.is_deleted = 0
	        AND b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,180,205,123,6,189,183,146,188,182,209,130,158,217,58,221,222,223,224,225,226,227,228,229,230,231) AND b.is_deleted = 0  ORDER BY b.id DESC
ERROR - 2024-12-23 14:23:12 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/InspectorHome.php 1903
ERROR - 2024-12-23 14:23:50 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,180,' at line 12 - Invalid query: SELECT a.institute_name,a.short_inst_name,COUNT(bi.id) as reported,GROUP_CONCAT(bi.overall_compliance) as overall_compliance_list, ai.inspector_name, b.agency_id,b.id,b.batch_code,  b.batch_name, b.batch_from_date, b.batch_to_date, b.online_training_platform, b.timing_from, b.timing_to, b.hours, b.training_medium, b.total_candidates, f1.faculty_id as first_faculty_id, f1.faculty_name as first_faculty_name,f1.faculty_code as first_faculty_code,f1.faculty_photo as first_faculty_photo, f2.faculty_id as sec_faculty_id, f2.faculty_name as sec_faculty_name, f2.faculty_code as sec_faculty_code,f2.faculty_photo as sec_faculty_photo, f3.faculty_id as add_first_faculty_id, f3.faculty_name as add_first_faculty_name, f3.faculty_code as add_first_faculty_code,f3.faculty_photo as add_first_faculty_photo, f4.faculty_id as add_sec_faculty_id, f4.faculty_name as add_sec_faculty_name, f4.faculty_code as add_sec_faculty_code,f4.faculty_photo as add_sec_faculty_photo, b.contact_person_name, b.contact_person_phone, b.alt_contact_person_name, b.alt_contact_person_phone, b.platform_link, b.batch_online_offline_flag, b.training_schedule, b.created_on
	        FROM agency_batch b 
          LEFT JOIN  dra_accerdited_master a ON a.dra_inst_registration_id = b.agency_id
          LEFT JOIN  dra_batch_inspection bi ON bi.batch_id = b.id
          LEFT JOIN agency_inspector_master ai ON ai.id = b.inspector_id
          LEFT JOIN faculty_master f1 ON b.first_faculty=f1.faculty_id 
          LEFT JOIN faculty_master f2 ON b.sec_faculty=f2.faculty_id
	        LEFT JOIN faculty_master f3 ON b.additional_first_faculty=f3.faculty_id
	        LEFT JOIN faculty_master f4 ON b.additional_sec_faculty=f4.faculty_id
	        WHERE (b.batch_status = 'Approved' OR b.batch_status = 'Cancelled' OR b.batch_status = 'Hold')
	        --- AND b.is_deleted = 0
	        AND b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,180,205,123,6,189,183,146,188,182,209,130,158,217,58,221,222,223,224,225,226,227,228,229,230,231) AND b.is_deleted = 0  ORDER BY b.id DESC
ERROR - 2024-12-23 14:23:50 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/InspectorHome.php 1903
ERROR - 2024-12-23 14:24:50 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,180,' at line 12 - Invalid query: SELECT a.institute_name,a.short_inst_name,COUNT(bi.id) as reported,GROUP_CONCAT(bi.overall_compliance) as overall_compliance_list, ai.inspector_name, b.agency_id,b.id,b.batch_code,  b.batch_name, b.batch_from_date, b.batch_to_date, b.online_training_platform, b.timing_from, b.timing_to, b.hours, b.training_medium, b.total_candidates, f1.faculty_id as first_faculty_id, f1.faculty_name as first_faculty_name,f1.faculty_code as first_faculty_code,f1.faculty_photo as first_faculty_photo, f2.faculty_id as sec_faculty_id, f2.faculty_name as sec_faculty_name, f2.faculty_code as sec_faculty_code,f2.faculty_photo as sec_faculty_photo, f3.faculty_id as add_first_faculty_id, f3.faculty_name as add_first_faculty_name, f3.faculty_code as add_first_faculty_code,f3.faculty_photo as add_first_faculty_photo, f4.faculty_id as add_sec_faculty_id, f4.faculty_name as add_sec_faculty_name, f4.faculty_code as add_sec_faculty_code,f4.faculty_photo as add_sec_faculty_photo, b.contact_person_name, b.contact_person_phone, b.alt_contact_person_name, b.alt_contact_person_phone, b.platform_link, b.batch_online_offline_flag, b.training_schedule, b.created_on
	        FROM agency_batch b 
          LEFT JOIN  dra_accerdited_master a ON a.dra_inst_registration_id = b.agency_id
          LEFT JOIN  dra_batch_inspection bi ON bi.batch_id = b.id
          LEFT JOIN agency_inspector_master ai ON ai.id = b.inspector_id
          LEFT JOIN faculty_master f1 ON b.first_faculty=f1.faculty_id 
          LEFT JOIN faculty_master f2 ON b.sec_faculty=f2.faculty_id
	        LEFT JOIN faculty_master f3 ON b.additional_first_faculty=f3.faculty_id
	        LEFT JOIN faculty_master f4 ON b.additional_sec_faculty=f4.faculty_id
	        WHERE (b.batch_status = 'Approved' OR b.batch_status = 'Cancelled' OR b.batch_status = 'Hold') 
	        --- AND b.is_deleted = 0
	        AND b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,180,205,123,6,189,183,146,188,182,209,130,158,217,58,221,222,223,224,225,226,227,228,229,230,231) AND b.is_deleted = 0  ORDER BY b.id DESC
ERROR - 2024-12-23 14:24:50 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/InspectorHome.php 1903
ERROR - 2024-12-23 14:24:52 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,180,' at line 12 - Invalid query: SELECT a.institute_name,a.short_inst_name,COUNT(bi.id) as reported,GROUP_CONCAT(bi.overall_compliance) as overall_compliance_list, ai.inspector_name, b.agency_id,b.id,b.batch_code,  b.batch_name, b.batch_from_date, b.batch_to_date, b.online_training_platform, b.timing_from, b.timing_to, b.hours, b.training_medium, b.total_candidates, f1.faculty_id as first_faculty_id, f1.faculty_name as first_faculty_name,f1.faculty_code as first_faculty_code,f1.faculty_photo as first_faculty_photo, f2.faculty_id as sec_faculty_id, f2.faculty_name as sec_faculty_name, f2.faculty_code as sec_faculty_code,f2.faculty_photo as sec_faculty_photo, f3.faculty_id as add_first_faculty_id, f3.faculty_name as add_first_faculty_name, f3.faculty_code as add_first_faculty_code,f3.faculty_photo as add_first_faculty_photo, f4.faculty_id as add_sec_faculty_id, f4.faculty_name as add_sec_faculty_name, f4.faculty_code as add_sec_faculty_code,f4.faculty_photo as add_sec_faculty_photo, b.contact_person_name, b.contact_person_phone, b.alt_contact_person_name, b.alt_contact_person_phone, b.platform_link, b.batch_online_offline_flag, b.training_schedule, b.created_on
	        FROM agency_batch b 
          LEFT JOIN  dra_accerdited_master a ON a.dra_inst_registration_id = b.agency_id
          LEFT JOIN  dra_batch_inspection bi ON bi.batch_id = b.id
          LEFT JOIN agency_inspector_master ai ON ai.id = b.inspector_id
          LEFT JOIN faculty_master f1 ON b.first_faculty=f1.faculty_id 
          LEFT JOIN faculty_master f2 ON b.sec_faculty=f2.faculty_id
	        LEFT JOIN faculty_master f3 ON b.additional_first_faculty=f3.faculty_id
	        LEFT JOIN faculty_master f4 ON b.additional_sec_faculty=f4.faculty_id
	        WHERE (b.batch_status = 'Approved' OR b.batch_status = 'Cancelled' OR b.batch_status = 'Hold') 
	        --- AND b.is_deleted = 0
	        AND b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,180,205,123,6,189,183,146,188,182,209,130,158,217,58,221,222,223,224,225,226,227,228,229,230,231) AND b.is_deleted = 0  ORDER BY b.id DESC
ERROR - 2024-12-23 14:24:52 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/InspectorHome.php 1903
ERROR - 2024-12-23 14:25:00 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,180,' at line 12 - Invalid query: SELECT a.institute_name,a.short_inst_name,COUNT(bi.id) as reported,GROUP_CONCAT(bi.overall_compliance) as overall_compliance_list, ai.inspector_name, b.agency_id,b.id,b.batch_code,  b.batch_name, b.batch_from_date, b.batch_to_date, b.online_training_platform, b.timing_from, b.timing_to, b.hours, b.training_medium, b.total_candidates, f1.faculty_id as first_faculty_id, f1.faculty_name as first_faculty_name,f1.faculty_code as first_faculty_code,f1.faculty_photo as first_faculty_photo, f2.faculty_id as sec_faculty_id, f2.faculty_name as sec_faculty_name, f2.faculty_code as sec_faculty_code,f2.faculty_photo as sec_faculty_photo, f3.faculty_id as add_first_faculty_id, f3.faculty_name as add_first_faculty_name, f3.faculty_code as add_first_faculty_code,f3.faculty_photo as add_first_faculty_photo, f4.faculty_id as add_sec_faculty_id, f4.faculty_name as add_sec_faculty_name, f4.faculty_code as add_sec_faculty_code,f4.faculty_photo as add_sec_faculty_photo, b.contact_person_name, b.contact_person_phone, b.alt_contact_person_name, b.alt_contact_person_phone, b.platform_link, b.batch_online_offline_flag, b.training_schedule, b.created_on
	        FROM agency_batch b 
          LEFT JOIN  dra_accerdited_master a ON a.dra_inst_registration_id = b.agency_id
          LEFT JOIN  dra_batch_inspection bi ON bi.batch_id = b.id
          LEFT JOIN agency_inspector_master ai ON ai.id = b.inspector_id
          LEFT JOIN faculty_master f1 ON b.first_faculty=f1.faculty_id 
          LEFT JOIN faculty_master f2 ON b.sec_faculty=f2.faculty_id
	        LEFT JOIN faculty_master f3 ON b.additional_first_faculty=f3.faculty_id
	        LEFT JOIN faculty_master f4 ON b.additional_sec_faculty=f4.faculty_id
	        WHERE (b.batch_status = 'Approved' OR b.batch_status = 'Cancelled' OR b.batch_status = 'Hold') 
	        --- AND b.is_deleted = 0
	        AND b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,180,205,123,6,189,183,146,188,182,209,130,158,217,58,221,222,223,224,225,226,227,228,229,230,231) AND b.is_deleted = 0  ORDER BY b.id DESC
ERROR - 2024-12-23 14:25:00 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/InspectorHome.php 1903
ERROR - 2024-12-23 15:03:44 --> 404 Page Not Found: iibfdra/Version_2/InspectorHome/inspection_report
ERROR - 2024-12-23 15:03:46 --> 404 Page Not Found: iibfdra/Version_2/InspectorHome/inspection_report
ERROR - 2024-12-23 15:33:16 --> Severity: Warning --> mcrypt_generic_init(): Key size is 0 /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 83
ERROR - 2024-12-23 15:33:16 --> Severity: Warning --> mcrypt_generic_init(): Key length incorrect /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 83
ERROR - 2024-12-23 15:33:16 --> Severity: Warning --> mcrypt_generic(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 84
ERROR - 2024-12-23 15:33:16 --> Severity: Warning --> mcrypt_generic_deinit(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 87
ERROR - 2024-12-23 15:33:16 --> Severity: Warning --> mcrypt_module_close(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 88
ERROR - 2024-12-23 15:39:49 --> Severity: Warning --> mcrypt_generic_init(): Key size is 0 /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 83
ERROR - 2024-12-23 15:39:49 --> Severity: Warning --> mcrypt_generic_init(): Key length incorrect /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 83
ERROR - 2024-12-23 15:39:49 --> Severity: Warning --> mcrypt_generic(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 84
ERROR - 2024-12-23 15:39:49 --> Severity: Warning --> mcrypt_generic_deinit(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 87
ERROR - 2024-12-23 15:39:49 --> Severity: Warning --> mcrypt_module_close(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 88
ERROR - 2024-12-23 15:45:49 --> Severity: Warning --> mcrypt_generic_init(): Key size is 0 /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 83
ERROR - 2024-12-23 15:45:49 --> Severity: Warning --> mcrypt_generic_init(): Key length incorrect /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 83
ERROR - 2024-12-23 15:45:49 --> Severity: Warning --> mcrypt_generic(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 84
ERROR - 2024-12-23 15:45:49 --> Severity: Warning --> mcrypt_generic_deinit(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 87
ERROR - 2024-12-23 15:45:49 --> Severity: Warning --> mcrypt_module_close(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 88
ERROR - 2024-12-23 15:56:29 --> Severity: Warning --> mcrypt_generic_init(): Key size is 0 /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 83
ERROR - 2024-12-23 15:56:29 --> Severity: Warning --> mcrypt_generic_init(): Key length incorrect /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 83
ERROR - 2024-12-23 15:56:29 --> Severity: Warning --> mcrypt_generic(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 84
ERROR - 2024-12-23 15:56:29 --> Severity: Warning --> mcrypt_generic_deinit(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 87
ERROR - 2024-12-23 15:56:29 --> Severity: Warning --> mcrypt_module_close(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 88
ERROR - 2024-12-23 15:59:59 --> Severity: Warning --> mcrypt_generic_init(): Key size is 0 /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 83
ERROR - 2024-12-23 15:59:59 --> Severity: Warning --> mcrypt_generic_init(): Key length incorrect /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 83
ERROR - 2024-12-23 15:59:59 --> Severity: Warning --> mcrypt_generic(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 84
ERROR - 2024-12-23 15:59:59 --> Severity: Warning --> mcrypt_generic_deinit(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 87
ERROR - 2024-12-23 15:59:59 --> Severity: Warning --> mcrypt_module_close(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 88
ERROR - 2024-12-23 16:04:14 --> Severity: Warning --> mcrypt_generic_init(): Key size is 0 /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 83
ERROR - 2024-12-23 16:04:14 --> Severity: Warning --> mcrypt_generic_init(): Key length incorrect /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 83
ERROR - 2024-12-23 16:04:14 --> Severity: Warning --> mcrypt_generic(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 84
ERROR - 2024-12-23 16:04:14 --> Severity: Warning --> mcrypt_generic_deinit(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 87
ERROR - 2024-12-23 16:04:14 --> Severity: Warning --> mcrypt_module_close(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 88
ERROR - 2024-12-23 16:05:23 --> Severity: Warning --> array_unique() expects parameter 1 to be array, null given /home/supp0rttest/public_html/staging/application/helpers/admitcard_helper.php 92
ERROR - 2024-12-23 16:05:23 --> Severity: Warning --> implode(): Invalid arguments passed /home/supp0rttest/public_html/staging/application/helpers/admitcard_helper.php 92
ERROR - 2024-12-23 16:08:23 --> Severity: Warning --> mcrypt_generic_init(): Key size is 0 /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 83
ERROR - 2024-12-23 16:08:23 --> Severity: Warning --> mcrypt_generic_init(): Key length incorrect /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 83
ERROR - 2024-12-23 16:08:23 --> Severity: Warning --> mcrypt_generic(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 84
ERROR - 2024-12-23 16:08:23 --> Severity: Warning --> mcrypt_generic_deinit(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 87
ERROR - 2024-12-23 16:08:23 --> Severity: Warning --> mcrypt_module_close(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 88
ERROR - 2024-12-23 16:11:16 --> Severity: Warning --> mcrypt_generic_init(): Key size is 0 /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 83
ERROR - 2024-12-23 16:11:16 --> Severity: Warning --> mcrypt_generic_init(): Key length incorrect /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 83
ERROR - 2024-12-23 16:11:16 --> Severity: Warning --> mcrypt_generic(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 84
ERROR - 2024-12-23 16:11:16 --> Severity: Warning --> mcrypt_generic_deinit(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 87
ERROR - 2024-12-23 16:11:16 --> Severity: Warning --> mcrypt_module_close(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 88
ERROR - 2024-12-23 16:13:44 --> Severity: Warning --> mcrypt_generic_init(): Key size is 0 /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 83
ERROR - 2024-12-23 16:13:44 --> Severity: Warning --> mcrypt_generic_init(): Key length incorrect /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 83
ERROR - 2024-12-23 16:13:44 --> Severity: Warning --> mcrypt_generic(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 84
ERROR - 2024-12-23 16:13:44 --> Severity: Warning --> mcrypt_generic_deinit(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 87
ERROR - 2024-12-23 16:13:44 --> Severity: Warning --> mcrypt_module_close(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 88
ERROR - 2024-12-23 16:14:09 --> Severity: Warning --> array_unique() expects parameter 1 to be array, null given /home/supp0rttest/public_html/staging/application/helpers/admitcard_helper.php 92
ERROR - 2024-12-23 16:14:09 --> Severity: Warning --> implode(): Invalid arguments passed /home/supp0rttest/public_html/staging/application/helpers/admitcard_helper.php 92
ERROR - 2024-12-23 17:38:42 --> 404 Page Not Found: iibfdra/Version_2/TrainingBatches/favicon.ico
ERROR - 2024-12-23 17:39:03 --> Severity: Warning --> imagepng(uploads/draexaminvoice/user/dra_0_EDN_24-25_000162.jpg): failed to open stream: No such file or directory /home/supp0rttest/public_html/staging/application/helpers/invoice_helper.php 1983
ERROR - 2024-12-23 17:39:03 --> Severity: Warning --> imagepng(uploads/draexaminvoice/user/dra_0_EDN_24-25_000162.jpg): failed to open stream: No such file or directory /home/supp0rttest/public_html/staging/application/helpers/invoice_helper.php 1991
ERROR - 2024-12-23 17:39:03 --> Severity: Warning --> imagepng(uploads/draexaminvoice/supplier/dra_0_EDN_24-25_000162.jpg): failed to open stream: No such file or directory /home/supp0rttest/public_html/staging/application/helpers/invoice_helper.php 2156
ERROR - 2024-12-23 17:39:03 --> Severity: Warning --> imagepng(uploads/draexaminvoice/supplier/dra_0_EDN_24-25_000162.jpg): failed to open stream: No such file or directory /home/supp0rttest/public_html/staging/application/helpers/invoice_helper.php 2164
ERROR - 2024-12-23 17:40:50 --> 404 Page Not Found: Uploads/draexaminvoice
