<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2025-11-11 10:18:38 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:18:38 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:18:38 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:18:38 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:19:08 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:19:08 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:19:08 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:19:08 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:25:38 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:25:38 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:25:39 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:25:39 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:25:50 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:25:50 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:25:50 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:25:50 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:45:17 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:45:17 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:45:18 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:45:18 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:45:23 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:45:23 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:45:23 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:45:23 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:46:38 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:46:38 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:46:38 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:46:38 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:46:38 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:46:41 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:46:41 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:46:41 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:46:41 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:47:43 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:47:43 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:47:43 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 10:47:43 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-11 12:26:32 --> Query error: Unknown column 'C' in 'order clause' - Invalid query: SELECT bc.candidate_id, bc.regnumber, IF(bc.reference="BFSI","BFSI SSC","IIBF website") AS reference, bc.created_on, CONCAT(bc.first_name, IF(bc.middle_name != "", CONCAT(" ", bc.middle_name), ""), IF(bc.last_name != "", CONCAT(" ", bc.last_name), "")) AS DispName, IF(bc.gender=1,"Male","Female") AS DispGender, bc.dob, bc.dob, bc.mobile_no, bc.email_id, CASE bc.qualification WHEN 1 THEN "12th Pass with 1.5 years of experience in BFSI (not pursuing graduation / post graduation)" WHEN 2 THEN "Graduate not pursuing Post Graduation" WHEN 3 THEN "Pursuing Graduation" WHEN 4 THEN "Pursuing Postgraduation" END AS qualification, bc.university, bc.collage, sm.state_name AS eligibility_state, bc.id_proof_number, bc.aadhar_no, IF(bc.benchmark_disability="Y","Yes","No") AS benchmark_disability, CASE bc.kyc_status WHEN 0 THEN "Pending" WHEN 1 THEN "In Progress" WHEN 2 THEN "Approved" WHEN 3 THEN "Rejected" END AS kyc_status, CASE bc.benchmark_kyc_status WHEN 0 THEN "Pending" WHEN 1 THEN "In Progress" WHEN 2 THEN "Approved" WHEN 3 THEN "Rejected" END AS benchmark_kyc_status, bc.is_active FROM ncvet_candidates bc LEFT JOIN state_master sm ON bc.qualification_state = sm.state_code  WHERE bc.is_deleted = 0 AND bc.regnumber != ''  ORDER BY C asc LIMIT 0, 10 
ERROR - 2025-11-11 12:26:32 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/ncvet/admin/Candidate.php 220
ERROR - 2025-11-11 12:26:36 --> Query error: Unknown column 'C' in 'order clause' - Invalid query: SELECT bc.candidate_id, bc.regnumber, IF(bc.reference="BFSI","BFSI SSC","IIBF website") AS reference, bc.created_on, CONCAT(bc.first_name, IF(bc.middle_name != "", CONCAT(" ", bc.middle_name), ""), IF(bc.last_name != "", CONCAT(" ", bc.last_name), "")) AS DispName, IF(bc.gender=1,"Male","Female") AS DispGender, bc.dob, bc.dob, bc.mobile_no, bc.email_id, CASE bc.qualification WHEN 1 THEN "12th Pass with 1.5 years of experience in BFSI (not pursuing graduation / post graduation)" WHEN 2 THEN "Graduate not pursuing Post Graduation" WHEN 3 THEN "Pursuing Graduation" WHEN 4 THEN "Pursuing Postgraduation" END AS qualification, bc.university, bc.collage, sm.state_name AS eligibility_state, bc.id_proof_number, bc.aadhar_no, IF(bc.benchmark_disability="Y","Yes","No") AS benchmark_disability, CASE bc.kyc_status WHEN 0 THEN "Pending" WHEN 1 THEN "In Progress" WHEN 2 THEN "Approved" WHEN 3 THEN "Rejected" END AS kyc_status, CASE bc.benchmark_kyc_status WHEN 0 THEN "Pending" WHEN 1 THEN "In Progress" WHEN 2 THEN "Approved" WHEN 3 THEN "Rejected" END AS benchmark_kyc_status, bc.is_active FROM ncvet_candidates bc LEFT JOIN state_master sm ON bc.qualification_state = sm.state_code  WHERE bc.is_deleted = 0 AND bc.regnumber != ''  ORDER BY C asc LIMIT 0, 10 
ERROR - 2025-11-11 12:26:36 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/ncvet/admin/Candidate.php 220
ERROR - 2025-11-11 12:28:59 --> Query error: Unknown column 'C' in 'order clause' - Invalid query: SELECT bc.candidate_id, bc.regnumber, IF(bc.reference="BFSI","BFSI SSC","IIBF website") AS reference, bc.created_on, CONCAT(bc.first_name, IF(bc.middle_name != "", CONCAT(" ", bc.middle_name), ""), IF(bc.last_name != "", CONCAT(" ", bc.last_name), "")) AS DispName, IF(bc.gender=1,"Male","Female") AS DispGender, bc.dob, bc.dob, bc.mobile_no, bc.email_id, CASE bc.qualification WHEN 1 THEN "12th Pass with 1.5 years of experience in BFSI (not pursuing graduation / post graduation)" WHEN 2 THEN "Graduate not pursuing Post Graduation" WHEN 3 THEN "Pursuing Graduation" WHEN 4 THEN "Pursuing Postgraduation" END AS qualification, bc.university, bc.collage, sm.state_name AS eligibility_state, bc.id_proof_number, bc.aadhar_no, IF(bc.benchmark_disability="Y","Yes","No") AS benchmark_disability, CASE bc.kyc_status WHEN 0 THEN "Pending" WHEN 1 THEN "In Progress" WHEN 2 THEN "Approved" WHEN 3 THEN "Rejected" END AS kyc_status, CASE bc.benchmark_kyc_status WHEN 0 THEN "Pending" WHEN 1 THEN "In Progress" WHEN 2 THEN "Approved" WHEN 3 THEN "Rejected" END AS benchmark_kyc_status, bc.is_active FROM ncvet_candidates bc LEFT JOIN state_master sm ON bc.qualification_state = sm.state_code  WHERE bc.is_deleted = 0 AND bc.regnumber != ''  ORDER BY C asc LIMIT 0, 10 
ERROR - 2025-11-11 12:28:59 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/ncvet/admin/Candidate.php 220
ERROR - 2025-11-11 12:50:20 --> Severity: Parsing Error --> syntax error, unexpected '}' /home/supp0rttest/public_html/staging/application/controllers/CSCSpecialMember.php 4216
ERROR - 2025-11-11 13:06:55 --> 404 Page Not Found: iibfbcbf/Uploads/iibfbcbf
ERROR - 2025-11-11 13:07:07 --> 404 Page Not Found: Uploads/iibfbcbf
ERROR - 2025-11-11 15:03:42 --> Query error: Column 'user_agent' cannot be null - Invalid query: INSERT INTO `careers_pages_logs` (`position_id`, `title`, `ip`, `browser`, `user_agent`) VALUES ('3', 'Assistant_Director_Academics', '10.11.38.105', 'UnKnown||UnKnown', NULL)
ERROR - 2025-11-11 18:43:57 --> Query error: Column 'user_agent' cannot be null - Invalid query: INSERT INTO `careers_pages_logs` (`position_id`, `title`, `ip`, `browser`, `user_agent`) VALUES ('3', 'Assistant_Director_Academics', '10.11.38.105', 'UnKnown||UnKnown', NULL)
ERROR - 2025-11-11 19:36:24 --> Could not find the language line "form_validation_check_captcha_userlogin"
