ERROR - 2025-08-14 18:18:47 --> Query error: Table 'supp0rttest_iibf_staging.ncvet_batch_candidates' doesn't exist - Invalid query: SELECT `bc`.`candidate_id`, `acb`.`batch_start_date`, `acb`.`batch_end_date`
FROM `ncvet_batch_candidates` `bc`
INNER JOIN `ncvet_agency_centre_batch` `acb` ON `acb`.`batch_id` = `bc`.`batch_id`
WHERE `bc`.`candidate_id` = '1'
AND `bc`.`is_deleted` = '0'
ERROR - 2025-08-14 18:18:47 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 310
ERROR - 2025-08-14 18:21:16 --> Query error: Table 'supp0rttest_iibf_staging.ncvet_batch_candidates' doesn't exist - Invalid query: SELECT `bc`.`candidate_id`, `acb`.`batch_start_date`, `acb`.`batch_end_date`
FROM `ncvet_batch_candidates` `bc`
INNER JOIN `ncvet_agency_centre_batch` `acb` ON `acb`.`batch_id` = `bc`.`batch_id`
WHERE `bc`.`candidate_id` = '1'
AND `bc`.`is_deleted` = '0'
ERROR - 2025-08-14 18:21:16 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 310
ERROR - 2025-08-14 18:21:18 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-08-14 18:21:21 --> Query error: Table 'supp0rttest_iibf_staging.ncvet_batch_candidates' doesn't exist - Invalid query: SELECT `bc`.`candidate_id`, `acb`.`batch_start_date`, `acb`.`batch_end_date`
FROM `ncvet_batch_candidates` `bc`
INNER JOIN `ncvet_agency_centre_batch` `acb` ON `acb`.`batch_id` = `bc`.`batch_id`
WHERE `bc`.`candidate_id` = '1'
AND `bc`.`is_deleted` = '0'
ERROR - 2025-08-14 18:21:21 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 310
ERROR - 2025-08-14 18:21:21 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-08-14 18:22:30 --> Query error: Table 'supp0rttest_iibf_staging.ncvet_batch_candidates' doesn't exist - Invalid query: SELECT `bc`.`candidate_id`, `acb`.`batch_start_date`, `acb`.`batch_end_date`
FROM `ncvet_batch_candidates` `bc`
INNER JOIN `ncvet_agency_centre_batch` `acb` ON `acb`.`batch_id` = `bc`.`batch_id`
WHERE `bc`.`candidate_id` = '1'
AND `bc`.`is_deleted` = '0'
ERROR - 2025-08-14 18:22:30 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 310
ERROR - 2025-08-14 18:22:30 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-08-14 18:22:52 --> Query error: Table 'supp0rttest_iibf_staging.ncvet_batch_candidates' doesn't exist - Invalid query: SELECT `bc`.`candidate_id`, `acb`.`batch_start_date`, `acb`.`batch_end_date`
FROM `ncvet_batch_candidates` `bc`
INNER JOIN `ncvet_agency_centre_batch` `acb` ON `acb`.`batch_id` = `bc`.`batch_id`
WHERE `bc`.`candidate_id` = '1'
AND `bc`.`is_deleted` = '0'
ERROR - 2025-08-14 18:22:52 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 310
ERROR - 2025-08-14 18:28:01 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-08-14 18:28:01 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-08-14 18:28:01 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-08-14 18:28:01 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-08-14 18:28:11 --> Query error: Table 'supp0rttest_iibf_staging.ncvet_agency_centre_batch' doesn't exist - Invalid query: SELECT `bc`.*, IF(bc.gender=1, 'Male', 'Female') AS DispGender, IF(bc.qualification=1, 'Under Graduate', IF(bc.qualification=2, 'Graduate', 'Post Graduate')) AS DispQualification, `acb`.`batch_start_date`, `acb`.`batch_end_date`
FROM `ncvet_candidates` `bc`
INNER JOIN `ncvet_agency_centre_batch` `acb` ON `acb`.`batch_id` = `bc`.`batch_id`
WHERE `bc`.`candidate_id` = '1'
AND `bc`.`is_deleted` = '0'
ERROR - 2025-08-14 18:28:11 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 310
ERROR - 2025-08-14 18:28:14 --> Query error: Table 'supp0rttest_iibf_staging.ncvet_agency_centre_batch' doesn't exist - Invalid query: SELECT `bc`.*, IF(bc.gender=1, 'Male', 'Female') AS DispGender, IF(bc.qualification=1, 'Under Graduate', IF(bc.qualification=2, 'Graduate', 'Post Graduate')) AS DispQualification, `acb`.`batch_start_date`, `acb`.`batch_end_date`
FROM `ncvet_candidates` `bc`
INNER JOIN `ncvet_agency_centre_batch` `acb` ON `acb`.`batch_id` = `bc`.`batch_id`
WHERE `bc`.`candidate_id` = '1'
AND `bc`.`is_deleted` = '0'
ERROR - 2025-08-14 18:28:14 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 310
ERROR - 2025-08-14 18:36:43 --> Query error: Table 'supp0rttest_iibf_staging.ncvet_bank_associated_master' doesn't exist - Invalid query: SELECT `bank_id`, `bank_name`, `bank_code`
FROM `ncvet_bank_associated_master`
WHERE `is_active` = '1'
AND `is_deleted` = '0'
ORDER BY bank_name = 'Other' ASC, `bank_name` ASC
ERROR - 2025-08-14 18:36:43 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 310
ERROR - 2025-08-14 18:40:24 --> Severity: Error --> Call to a member function getLoggedInUserDetails() on null /home/supp0rttest/public_html/staging/application/views/iibfbcbf/candidate/inc_topbar_candidate.php 2
ERROR - 2025-08-14 18:42:53 --> Query error: Table 'supp0rttest_iibf_staging.ncvet_agency_centre_batch' doesn't exist - Invalid query: SELECT `cand`.`candidate_id`, `cand`.`agency_id`, `cand`.`centre_id`, `cand`.`batch_id`, `cand`.`mobile_no`, `cand`.`email_id`, `cand`.`id_proof_number`, `cand`.`aadhar_no`, `cand`.`created_on`, `cand`.`is_active`, `btch`.`batch_end_date`, `btch`.`batch_type`
FROM `ncvet_candidates` `cand`
INNER JOIN `ncvet_agency_centre_batch` `btch` ON `btch`.`batch_id` = `cand`.`batch_id`
WHERE `cand`.`mobile_no` = '8308318490'
AND  (DATE(cand.created_on) >= '2024-10-28' OR `btch`.`batch_end_date` >= '2024-10-28') 
AND `cand`.`is_deleted` = '0'
AND `cand`.`candidate_id` != '1'
AND `btch`.`batch_status` != '7'
AND `cand`.`re_attempt` < '3'
ORDER BY `cand`.`candidate_id` DESC
ERROR - 2025-08-14 18:42:53 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 310
ERROR - 2025-08-14 18:42:59 --> 404 Page Not Found: Assets/ncvetimages
ERROR - 2025-08-14 18:43:32 --> 404 Page Not Found: Assets/ncvetimages
ERROR - 2025-08-14 18:47:15 --> 404 Page Not Found: Assets/ncvetimages
ERROR - 2025-08-14 18:48:26 --> 404 Page Not Found: Assets/ncvetimages
ERROR - 2025-08-14 18:48:33 --> 404 Page Not Found: Assets/ncvetimages
ERROR - 2025-08-14 18:49:05 --> 404 Page Not Found: Assets/ncvetimages
ERROR - 2025-08-14 18:52:40 --> 404 Page Not Found: Assets/ncvetimages
ERROR - 2025-08-14 18:54:31 --> Severity: Warning --> rename(uploads/ncvet/photo/photo_800000113.jpg,uploads/ncvet/photo/k_photo_800000113.jpg): No such file or directory /home/supp0rttest/public_html/staging/application/controllers/ncvet/kyc/Kyc_all.php 551
ERROR - 2025-08-14 18:54:31 --> Severity: Warning --> rename(uploads/ncvet/sign/sign_800000113.jpg,uploads/ncvet/sign/k_sign_800000113.jpg): No such file or directory /home/supp0rttest/public_html/staging/application/controllers/ncvet/kyc/Kyc_all.php 571
ERROR - 2025-08-14 18:54:31 --> Severity: Warning --> rename(uploads/ncvet/id_proof/id_proof_800000113.jpg,uploads/ncvet/id_proof/k_id_proof_800000113.jpg): No such file or directory /home/supp0rttest/public_html/staging/application/controllers/ncvet/kyc/Kyc_all.php 592
