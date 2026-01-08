<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2025-08-18 15:27:45 --> Severity: Parsing Error --> syntax error, unexpected '=' /home/supp0rttest/public_html/staging/application/controllers/ncvet/kyc/Kyc_all.php 270
ERROR - 2025-08-18 16:50:27 --> Query error: Unknown column 'declaration' in 'field list' - Invalid query: SELECT `candidate_id`, `regnumber`, `training_id`, `salutation`, `first_name`, `middle_name`, `last_name`, `dob`, `gender`, `mobile_no`, `email_id`, `id_proof_file`, `qualification_certificate_file` as `cert_file`, `candidate_photo` as `photo_file`, `candidate_sign` as `sign_file`, `declaration`, `registration_type`, `exam_code`, `kyc_eligible_date`, `img_ediited_on`, `kyc_photo_flag`, `kyc_sign_flag`, `kyc_id_card_flag` AS `kyc_id_proof_flag`, `kyc_declaration_flag`, `kyc_status`, `kyc_recommender_status`, `recommender_id`, `kyc_approver_status`, `approver_id`, `kyc_recommender_date`, `kyc_approver_date`
FROM `ncvet_candidates`
WHERE `kyc_eligible_date` != '' AND `kyc_eligible_date` IS NOT NULL AND `kyc_eligible_date` != '0000-00-00 00:00:00' 
AND `recommender_id` = '1' AND `kyc_recommender_status` = '1' AND `approver_id` = '0' 
AND `kyc_status` IN(1)
AND `regnumber` != ''
AND `is_deleted` = '0'
AND DATE(kyc_eligible_date) <= '2025-08-18'
ERROR - 2025-08-18 16:50:27 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 310
ERROR - 2025-08-18 16:51:05 --> Query error: Unknown column 'declaration' in 'field list' - Invalid query: SELECT `candidate_id`, `regnumber`, `training_id`, `salutation`, `first_name`, `middle_name`, `last_name`, `dob`, `gender`, `mobile_no`, `email_id`, `id_proof_file`, `qualification_certificate_file` as `cert_file`, `candidate_photo` as `photo_file`, `candidate_sign` as `sign_file`, `declaration`, `registration_type`, `exam_code`, `kyc_eligible_date`, `img_ediited_on`, `kyc_photo_flag`, `kyc_sign_flag`, `kyc_id_card_flag` AS `kyc_id_proof_flag`, `kyc_declaration_flag`, `kyc_status`, `kyc_recommender_status`, `recommender_id`, `kyc_approver_status`, `approver_id`, `kyc_recommender_date`, `kyc_approver_date`
FROM `ncvet_candidates`
WHERE `kyc_eligible_date` != '' AND `kyc_eligible_date` IS NOT NULL AND `kyc_eligible_date` != '0000-00-00 00:00:00' 
AND `recommender_id` = '1' AND `kyc_recommender_status` = '1' AND `approver_id` = '0' 
AND `kyc_status` IN(1)
AND `regnumber` != ''
AND `is_deleted` = '0'
AND DATE(kyc_eligible_date) <= '2025-08-18'
ERROR - 2025-08-18 16:51:05 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 310
ERROR - 2025-08-18 16:51:06 --> Query error: Unknown column 'declaration' in 'field list' - Invalid query: SELECT `candidate_id`, `regnumber`, `training_id`, `salutation`, `first_name`, `middle_name`, `last_name`, `dob`, `gender`, `mobile_no`, `email_id`, `id_proof_file`, `qualification_certificate_file` as `cert_file`, `candidate_photo` as `photo_file`, `candidate_sign` as `sign_file`, `declaration`, `registration_type`, `exam_code`, `kyc_eligible_date`, `img_ediited_on`, `kyc_photo_flag`, `kyc_sign_flag`, `kyc_id_card_flag` AS `kyc_id_proof_flag`, `kyc_declaration_flag`, `kyc_status`, `kyc_recommender_status`, `recommender_id`, `kyc_approver_status`, `approver_id`, `kyc_recommender_date`, `kyc_approver_date`
FROM `ncvet_candidates`
WHERE `kyc_eligible_date` != '' AND `kyc_eligible_date` IS NOT NULL AND `kyc_eligible_date` != '0000-00-00 00:00:00' 
AND `recommender_id` = '1' AND `kyc_recommender_status` = '1' AND `approver_id` = '0' 
AND `kyc_status` IN(1)
AND `regnumber` != ''
AND `is_deleted` = '0'
AND DATE(kyc_eligible_date) <= '2025-08-18'
ERROR - 2025-08-18 16:51:06 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 310
ERROR - 2025-08-18 16:51:07 --> Query error: Unknown column 'declaration' in 'field list' - Invalid query: SELECT `candidate_id`, `regnumber`, `training_id`, `salutation`, `first_name`, `middle_name`, `last_name`, `dob`, `gender`, `mobile_no`, `email_id`, `id_proof_file`, `qualification_certificate_file` as `cert_file`, `candidate_photo` as `photo_file`, `candidate_sign` as `sign_file`, `declaration`, `registration_type`, `exam_code`, `kyc_eligible_date`, `img_ediited_on`, `kyc_photo_flag`, `kyc_sign_flag`, `kyc_id_card_flag` AS `kyc_id_proof_flag`, `kyc_declaration_flag`, `kyc_status`, `kyc_recommender_status`, `recommender_id`, `kyc_approver_status`, `approver_id`, `kyc_recommender_date`, `kyc_approver_date`
FROM `ncvet_candidates`
WHERE `kyc_eligible_date` != '' AND `kyc_eligible_date` IS NOT NULL AND `kyc_eligible_date` != '0000-00-00 00:00:00' 
AND `recommender_id` = '1' AND `kyc_recommender_status` = '1' AND `approver_id` = '0' 
AND `kyc_status` IN(1)
AND `regnumber` != ''
AND `is_deleted` = '0'
AND DATE(kyc_eligible_date) <= '2025-08-18'
ERROR - 2025-08-18 16:51:07 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 310
ERROR - 2025-08-18 16:51:08 --> Query error: Unknown column 'declaration' in 'field list' - Invalid query: SELECT `candidate_id`, `regnumber`, `training_id`, `salutation`, `first_name`, `middle_name`, `last_name`, `dob`, `gender`, `mobile_no`, `email_id`, `id_proof_file`, `qualification_certificate_file` as `cert_file`, `candidate_photo` as `photo_file`, `candidate_sign` as `sign_file`, `declaration`, `registration_type`, `exam_code`, `kyc_eligible_date`, `img_ediited_on`, `kyc_photo_flag`, `kyc_sign_flag`, `kyc_id_card_flag` AS `kyc_id_proof_flag`, `kyc_declaration_flag`, `kyc_status`, `kyc_recommender_status`, `recommender_id`, `kyc_approver_status`, `approver_id`, `kyc_recommender_date`, `kyc_approver_date`
FROM `ncvet_candidates`
WHERE `kyc_eligible_date` != '' AND `kyc_eligible_date` IS NOT NULL AND `kyc_eligible_date` != '0000-00-00 00:00:00' 
AND `recommender_id` = '1' AND `kyc_recommender_status` = '1' AND `approver_id` = '0' 
AND `kyc_status` IN(1)
AND `regnumber` != ''
AND `is_deleted` = '0'
AND DATE(kyc_eligible_date) <= '2025-08-18'
ERROR - 2025-08-18 16:51:08 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 310
ERROR - 2025-08-18 16:51:09 --> Query error: Unknown column 'declaration' in 'field list' - Invalid query: SELECT `candidate_id`, `regnumber`, `training_id`, `salutation`, `first_name`, `middle_name`, `last_name`, `dob`, `gender`, `mobile_no`, `email_id`, `id_proof_file`, `qualification_certificate_file` as `cert_file`, `candidate_photo` as `photo_file`, `candidate_sign` as `sign_file`, `declaration`, `registration_type`, `exam_code`, `kyc_eligible_date`, `img_ediited_on`, `kyc_photo_flag`, `kyc_sign_flag`, `kyc_id_card_flag` AS `kyc_id_proof_flag`, `kyc_declaration_flag`, `kyc_status`, `kyc_recommender_status`, `recommender_id`, `kyc_approver_status`, `approver_id`, `kyc_recommender_date`, `kyc_approver_date`
FROM `ncvet_candidates`
WHERE `kyc_eligible_date` != '' AND `kyc_eligible_date` IS NOT NULL AND `kyc_eligible_date` != '0000-00-00 00:00:00' 
AND `recommender_id` = '1' AND `kyc_recommender_status` = '1' AND `approver_id` = '0' 
AND `kyc_status` IN(1)
AND `regnumber` != ''
AND `is_deleted` = '0'
AND DATE(kyc_eligible_date) <= '2025-08-18'
ERROR - 2025-08-18 16:51:09 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 310
ERROR - 2025-08-18 16:55:59 --> Query error: Unknown column 'declaration' in 'field list' - Invalid query: SELECT `candidate_id`, `regnumber`, `training_id`, `salutation`, `first_name`, `middle_name`, `last_name`, `dob`, `gender`, `mobile_no`, `email_id`, `id_proof_file`, `qualification_certificate_file` as `cert_file`, `candidate_photo` as `photo_file`, `candidate_sign` as `sign_file`, `declaration`, `registration_type`, `exam_code`, `kyc_eligible_date`, `img_ediited_on`, `kyc_photo_flag`, `kyc_sign_flag`, `kyc_id_card_flag` AS `kyc_id_proof_flag`, `kyc_declaration_flag`, `kyc_status`, `kyc_recommender_status`, `recommender_id`, `kyc_approver_status`, `approver_id`, `kyc_recommender_date`, `kyc_approver_date`
FROM `ncvet_candidates`
WHERE `kyc_eligible_date` != '' AND `kyc_eligible_date` IS NOT NULL AND `kyc_eligible_date` != '0000-00-00 00:00:00' 
AND `recommender_id` = '1' AND `kyc_recommender_status` = '1' AND `approver_id` = '0' 
AND `kyc_status` IN(1)
AND `regnumber` != ''
AND `is_deleted` = '0'
AND DATE(kyc_eligible_date) <= '2025-08-18'
ERROR - 2025-08-18 16:55:59 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 310
ERROR - 2025-08-18 16:59:24 --> Query error: Unknown column 'declaration' in 'field list' - Invalid query: SELECT `candidate_id`, `regnumber`, `training_id`, `salutation`, `first_name`, `middle_name`, `last_name`, `dob`, `gender`, `mobile_no`, `email_id`, `id_proof_file`, `qualification_certificate_file` as `cert_file`, `candidate_photo` as `photo_file`, `candidate_sign` as `sign_file`, `declaration`, `registration_type`, `exam_code`, `kyc_eligible_date`, `img_ediited_on`, `kyc_photo_flag`, `kyc_sign_flag`, `kyc_id_card_flag` AS `kyc_id_proof_flag`, `kyc_declaration_flag`, `kyc_status`, `kyc_recommender_status`, `recommender_id`, `kyc_approver_status`, `approver_id`, `kyc_recommender_date`, `kyc_approver_date`
FROM `ncvet_candidates`
WHERE `kyc_eligible_date` != '' AND `kyc_eligible_date` IS NOT NULL AND `kyc_eligible_date` != '0000-00-00 00:00:00' 
AND `recommender_id` = '1' AND `kyc_recommender_status` = '1' AND `approver_id` = '0' 
AND `kyc_status` IN(1)
AND `regnumber` != ''
AND `is_deleted` = '0'
AND DATE(kyc_eligible_date) <= '2025-08-18'
ERROR - 2025-08-18 16:59:24 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 310
ERROR - 2025-08-18 17:23:34 --> 404 Page Not Found: Assets/ncvetimages
ERROR - 2025-08-18 17:23:59 --> Severity: Error --> Call to a member function datatable_record_cnt() on null /home/supp0rttest/public_html/staging/application/controllers/ncvet/kyc/Kyc_log.php 71
ERROR - 2025-08-18 17:24:03 --> Severity: Error --> Call to a member function datatable_record_cnt() on null /home/supp0rttest/public_html/staging/application/controllers/ncvet/kyc/Kyc_log.php 71
ERROR - 2025-08-18 17:24:56 --> 404 Page Not Found: Assets/ncvetimages
ERROR - 2025-08-18 17:35:03 --> 404 Page Not Found: Uploads/ncvet
ERROR - 2025-08-18 17:35:11 --> 404 Page Not Found: Uploads/ncvet
ERROR - 2025-08-18 17:35:16 --> 404 Page Not Found: Uploads/ncvet
ERROR - 2025-08-18 17:36:03 --> 404 Page Not Found: Uploads/ncvet
ERROR - 2025-08-18 18:02:29 --> Severity: Error --> Call to a member function datatable_record_cnt() on null /home/supp0rttest/public_html/staging/application/controllers/ncvet/kyc/Kyc_log.php 71
ERROR - 2025-08-18 18:05:39 --> Severity: Error --> Call to a member function datatable_record_cnt() on null /home/supp0rttest/public_html/staging/application/controllers/ncvet/kyc/Kyc_log.php 71
ERROR - 2025-08-18 18:06:34 --> Severity: Error --> Call to a member function datatable_record_cnt() on null /home/supp0rttest/public_html/staging/application/controllers/ncvet/kyc/Kyc_log.php 71
ERROR - 2025-08-18 18:06:51 --> Severity: Error --> Call to a member function datatable_record_cnt() on null /home/supp0rttest/public_html/staging/application/controllers/ncvet/kyc/Kyc_log.php 71
ERROR - 2025-08-18 18:16:54 --> 404 Page Not Found: Assets/ncvetimages
ERROR - 2025-08-18 18:20:36 --> 404 Page Not Found: Assets/ncvetimages
ERROR - 2025-08-18 18:21:44 --> 404 Page Not Found: Assets/ncvetimages
ERROR - 2025-08-18 21:37:37 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2025-08-18 21:38:28 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2025-08-18 21:39:15 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2025-08-18 22:19:35 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2025-08-18 22:19:35 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2025-08-18 22:21:47 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2025-08-18 23:13:31 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2025-08-18 23:50:47 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2025-08-18 23:51:12 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2025-08-18 23:51:12 --> 404 Page Not Found: Uploads/exam_instruction
