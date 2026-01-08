<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2025-11-06 09:55:50 --> Query error: Unknown column 'bc.reference' in 'field list' - Invalid query: SELECT bc.candidate_id, bc.regnumber, bc.reference, CONCAT(bc.first_name, IF(bc.middle_name != "", CONCAT(" ", bc.middle_name), ""), IF(bc.last_name != "", CONCAT(" ", bc.last_name), "")) AS DispName, bc.dob, bc.mobile_no, bc.email_id, IF(bc.benchmark_disability="Y","Yes","No") AS benchmark_disability, CASE bc.kyc_status WHEN 0 THEN "Pending" WHEN 1 THEN "In Progress" WHEN 2 THEN "Approved" WHEN 3 THEN "Rejected" END AS kyc_status, CASE bc.benchmark_kyc_status WHEN 0 THEN "Pending" WHEN 1 THEN "In Progress" WHEN 2 THEN "Approved" WHEN 3 THEN "Rejected" END AS benchmark_kyc_status, bc.is_active FROM ncvet_candidates bc  WHERE bc.is_deleted = 0 AND bc.regnumber != ''  ORDER BY bc.candidate_id ASC LIMIT 0, 10 
ERROR - 2025-11-06 09:55:50 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/ncvet/admin/Candidate.php 172
ERROR - 2025-11-06 09:55:53 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 09:55:53 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 09:55:53 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 09:55:53 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 09:57:27 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 09:57:27 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 09:57:27 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 09:57:27 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 09:57:27 --> Query error: Unknown column 'bc.reference' in 'field list' - Invalid query: SELECT bc.candidate_id, bc.regnumber, bc.reference, CONCAT(bc.first_name, IF(bc.middle_name != "", CONCAT(" ", bc.middle_name), ""), IF(bc.last_name != "", CONCAT(" ", bc.last_name), "")) AS DispName, bc.dob, bc.mobile_no, bc.email_id, IF(bc.benchmark_disability="Y","Yes","No") AS benchmark_disability, CASE bc.kyc_status WHEN 0 THEN "Pending" WHEN 1 THEN "In Progress" WHEN 2 THEN "Approved" WHEN 3 THEN "Rejected" END AS kyc_status, CASE bc.benchmark_kyc_status WHEN 0 THEN "Pending" WHEN 1 THEN "In Progress" WHEN 2 THEN "Approved" WHEN 3 THEN "Rejected" END AS benchmark_kyc_status, bc.is_active FROM ncvet_candidates bc  WHERE bc.is_deleted = 0 AND bc.regnumber != ''  ORDER BY bc.candidate_id ASC LIMIT 0, 10 
ERROR - 2025-11-06 09:57:27 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/ncvet/admin/Candidate.php 172
ERROR - 2025-11-06 09:58:48 --> Query error: Unknown column 'bc.reference' in 'field list' - Invalid query: SELECT bc.candidate_id, bc.regnumber, bc.reference, CONCAT(bc.first_name, IF(bc.middle_name != "", CONCAT(" ", bc.middle_name), ""), IF(bc.last_name != "", CONCAT(" ", bc.last_name), "")) AS DispName, bc.dob, bc.mobile_no, bc.email_id, IF(bc.benchmark_disability="Y","Yes","No") AS benchmark_disability, CASE bc.kyc_status WHEN 0 THEN "Pending" WHEN 1 THEN "In Progress" WHEN 2 THEN "Approved" WHEN 3 THEN "Rejected" END AS kyc_status, CASE bc.benchmark_kyc_status WHEN 0 THEN "Pending" WHEN 1 THEN "In Progress" WHEN 2 THEN "Approved" WHEN 3 THEN "Rejected" END AS benchmark_kyc_status, bc.is_active FROM ncvet_candidates bc  WHERE bc.is_deleted = 0 AND bc.regnumber != ''  ORDER BY bc.candidate_id ASC LIMIT 0, 10 
ERROR - 2025-11-06 09:58:48 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/ncvet/admin/Candidate.php 172
ERROR - 2025-11-06 09:58:48 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 09:58:48 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 09:58:48 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 09:58:48 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 10:02:10 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 10:02:10 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 10:02:10 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 10:02:10 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 10:02:55 --> Query error: Unknown column 'BFSI' in 'where clause' - Invalid query: SELECT bc.candidate_id, bc.regnumber, bc.reference, CONCAT(bc.first_name, IF(bc.middle_name != "", CONCAT(" ", bc.middle_name), ""), IF(bc.last_name != "", CONCAT(" ", bc.last_name), "")) AS DispName, bc.dob, bc.mobile_no, bc.email_id, IF(bc.benchmark_disability="Y","Yes","No") AS benchmark_disability, CASE bc.kyc_status WHEN 0 THEN "Pending" WHEN 1 THEN "In Progress" WHEN 2 THEN "Approved" WHEN 3 THEN "Rejected" END AS kyc_status, CASE bc.benchmark_kyc_status WHEN 0 THEN "Pending" WHEN 1 THEN "In Progress" WHEN 2 THEN "Approved" WHEN 3 THEN "Rejected" END AS benchmark_kyc_status, bc.is_active FROM ncvet_candidates bc  WHERE bc.is_deleted = 0 AND bc.regnumber != ''  AND bc.reference = BFSI ORDER BY bc.reference desc LIMIT 0, 10 
ERROR - 2025-11-06 10:02:55 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/ncvet/admin/Candidate.php 172
ERROR - 2025-11-06 10:06:54 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 10:06:54 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 10:06:54 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 10:06:54 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 10:06:57 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 10:06:57 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 10:06:57 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 10:06:57 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 10:19:01 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 10:19:01 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 10:19:01 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 10:19:01 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 10:22:07 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'OR bc.reference IS NULL) ORDER BY bc.candidate_id ASC LIMIT 0, 10' at line 1 - Invalid query: SELECT bc.candidate_id, bc.regnumber, bc.reference, CONCAT(bc.first_name, IF(bc.middle_name != "", CONCAT(" ", bc.middle_name), ""), IF(bc.last_name != "", CONCAT(" ", bc.last_name), "")) AS DispName, bc.dob, bc.mobile_no, bc.email_id, IF(bc.benchmark_disability="Y","Yes","No") AS benchmark_disability, CASE bc.kyc_status WHEN 0 THEN "Pending" WHEN 1 THEN "In Progress" WHEN 2 THEN "Approved" WHEN 3 THEN "Rejected" END AS kyc_status, CASE bc.benchmark_kyc_status WHEN 0 THEN "Pending" WHEN 1 THEN "In Progress" WHEN 2 THEN "Approved" WHEN 3 THEN "Rejected" END AS benchmark_kyc_status, bc.is_active FROM ncvet_candidates bc  WHERE bc.is_deleted = 0 AND bc.regnumber != ''  AND (bc.reference != 'BFSI' OR bc.reference = '' OR OR bc.reference IS NULL) ORDER BY bc.candidate_id ASC LIMIT 0, 10 
ERROR - 2025-11-06 10:22:07 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/ncvet/admin/Candidate.php 172
ERROR - 2025-11-06 10:24:50 --> Severity: Parsing Error --> syntax error, unexpected ',', expecting ')' /home/supp0rttest/public_html/staging/application/models/ncvet/Ncvet_model.php 1940
ERROR - 2025-11-06 10:24:57 --> Severity: Parsing Error --> syntax error, unexpected ',', expecting ')' /home/supp0rttest/public_html/staging/application/models/ncvet/Ncvet_model.php 1940
ERROR - 2025-11-06 10:25:05 --> Severity: Parsing Error --> syntax error, unexpected ',', expecting ')' /home/supp0rttest/public_html/staging/application/models/ncvet/Ncvet_model.php 1940
ERROR - 2025-11-06 10:26:09 --> Severity: error --> Exception: /home/supp0rttest/public_html/staging/application/models/ncvet/Ncvet_model.php exists, but doesn't declare class Ncvet_model /home/supp0rttest/public_html/staging/system/core/Loader.php 336
ERROR - 2025-11-06 10:26:11 --> Severity: error --> Exception: /home/supp0rttest/public_html/staging/application/models/ncvet/Ncvet_model.php exists, but doesn't declare class Ncvet_model /home/supp0rttest/public_html/staging/system/core/Loader.php 336
ERROR - 2025-11-06 10:26:24 --> Severity: Parsing Error --> syntax error, unexpected ',', expecting ')' /home/supp0rttest/public_html/staging/application/models/ncvet/Ncvet_model.php 1940
ERROR - 2025-11-06 10:26:30 --> Severity: Parsing Error --> syntax error, unexpected ',', expecting ')' /home/supp0rttest/public_html/staging/application/models/ncvet/Ncvet_model.php 1940
ERROR - 2025-11-06 10:26:34 --> Severity: Parsing Error --> syntax error, unexpected ',', expecting ')' /home/supp0rttest/public_html/staging/application/models/ncvet/Ncvet_model.php 1940
ERROR - 2025-11-06 10:26:38 --> Severity: Parsing Error --> syntax error, unexpected ',', expecting ')' /home/supp0rttest/public_html/staging/application/models/ncvet/Ncvet_model.php 1940
ERROR - 2025-11-06 10:26:44 --> Severity: Parsing Error --> syntax error, unexpected ',', expecting ')' /home/supp0rttest/public_html/staging/application/models/ncvet/Ncvet_model.php 1940
ERROR - 2025-11-06 10:26:47 --> Severity: Parsing Error --> syntax error, unexpected ',', expecting ')' /home/supp0rttest/public_html/staging/application/models/ncvet/Ncvet_model.php 1940
ERROR - 2025-11-06 10:27:40 --> Severity: Parsing Error --> syntax error, unexpected ',', expecting ')' /home/supp0rttest/public_html/staging/application/models/ncvet/Ncvet_model.php 2041
ERROR - 2025-11-06 10:27:43 --> Severity: Parsing Error --> syntax error, unexpected ',', expecting ')' /home/supp0rttest/public_html/staging/application/models/ncvet/Ncvet_model.php 2041
ERROR - 2025-11-06 10:27:44 --> Severity: Parsing Error --> syntax error, unexpected ',', expecting ')' /home/supp0rttest/public_html/staging/application/models/ncvet/Ncvet_model.php 2041
ERROR - 2025-11-06 10:28:09 --> Severity: Parsing Error --> syntax error, unexpected ',', expecting ')' /home/supp0rttest/public_html/staging/application/models/ncvet/Ncvet_model.php 2041
ERROR - 2025-11-06 10:28:11 --> Severity: Parsing Error --> syntax error, unexpected ',', expecting ')' /home/supp0rttest/public_html/staging/application/models/ncvet/Ncvet_model.php 2041
ERROR - 2025-11-06 10:37:39 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-11-06 10:37:39 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-11-06 10:37:39 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-11-06 10:37:39 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-11-06 11:00:38 --> 404 Page Not Found: DraRegister/favicon.ico
ERROR - 2025-11-06 12:29:54 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 12:29:54 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 12:29:54 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 12:29:54 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 12:30:22 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 12:30:22 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 12:30:22 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 12:30:22 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-11-06 15:29:26 --> Query error: Column 'user_agent' cannot be null - Invalid query: INSERT INTO `careers_pages_logs` (`position_id`, `title`, `ip`, `browser`, `user_agent`) VALUES ('7', 'head_pdc_nz', '10.11.38.105', 'UnKnown||UnKnown', NULL)
ERROR - 2025-11-06 15:41:14 --> Query error: Column 'user_agent' cannot be null - Invalid query: INSERT INTO `careers_pages_logs` (`position_id`, `title`, `ip`, `browser`, `user_agent`) VALUES ('3', 'Assistant_Director_Academics', '10.11.38.105', 'UnKnown||UnKnown', NULL)
ERROR - 2025-11-06 15:54:28 --> 404 Page Not Found: CSCSpecialMember/index
ERROR - 2025-11-06 15:58:44 --> Query error: Column 'user_agent' cannot be null - Invalid query: INSERT INTO `careers_pages_logs` (`position_id`, `title`, `ip`, `browser`, `user_agent`) VALUES ('7', 'head_pdc_nz', '10.11.38.105', 'UnKnown||UnKnown', NULL)
ERROR - 2025-11-06 16:24:12 --> Query error: Column 'user_agent' cannot be null - Invalid query: INSERT INTO `careers_pages_logs` (`position_id`, `title`, `ip`, `browser`, `user_agent`) VALUES ('7', 'Faculty_Member', '10.11.38.105', 'UnKnown||UnKnown', NULL)
ERROR - 2025-11-06 16:29:07 --> Query error: Column 'user_agent' cannot be null - Invalid query: INSERT INTO `careers_pages_logs` (`position_id`, `title`, `ip`, `browser`, `user_agent`) VALUES ('14', 'Faculty_Member_mumbai', '10.11.38.105', 'UnKnown||UnKnown', NULL)
ERROR - 2025-11-06 17:06:19 --> 404 Page Not Found: Assets/js
ERROR - 2025-11-06 17:56:30 --> 404 Page Not Found: Assets/js
ERROR - 2025-11-06 17:58:07 --> 404 Page Not Found: Assets/js
ERROR - 2025-11-06 17:58:15 --> 404 Page Not Found: Career/faculty
ERROR - 2025-11-06 18:18:16 --> Could not find the language line "form_validation_file_size_max"
ERROR - 2025-11-06 18:18:16 --> Could not find the language line "form_validation_file_size_max"
ERROR - 2025-11-06 18:19:34 --> 404 Page Not Found: Assets/js
ERROR - 2025-11-06 18:23:14 --> 404 Page Not Found: Assets/js
ERROR - 2025-11-06 18:26:31 --> 404 Page Not Found: Assets/js
ERROR - 2025-11-06 18:27:15 --> 404 Page Not Found: Assets/js
ERROR - 2025-11-06 18:28:05 --> 404 Page Not Found: Assets/js
ERROR - 2025-11-06 18:33:37 --> Severity: Warning --> Invalid argument supplied for foreach() /home/supp0rttest/public_html/staging/application/controllers/Careers.php 10874
ERROR - 2025-11-06 18:33:37 --> Severity: Warning --> Illegal string offset 'org_organization' /home/supp0rttest/public_html/staging/application/controllers/Careers.php 10902
ERROR - 2025-11-06 18:33:37 --> Severity: Warning --> Illegal string offset 'org_from_date' /home/supp0rttest/public_html/staging/application/controllers/Careers.php 10903
ERROR - 2025-11-06 18:33:37 --> Severity: Warning --> Illegal string offset 'org_to_date' /home/supp0rttest/public_html/staging/application/controllers/Careers.php 10904
ERROR - 2025-11-06 18:33:37 --> Severity: Warning --> Illegal string offset 'org_designation' /home/supp0rttest/public_html/staging/application/controllers/Careers.php 10905
ERROR - 2025-11-06 18:33:37 --> Severity: Warning --> Illegal string offset 'org_responsibilities' /home/supp0rttest/public_html/staging/application/controllers/Careers.php 10906
ERROR - 2025-11-06 18:33:37 --> Severity: Warning --> Illegal string offset 'org_organization' /home/supp0rttest/public_html/staging/application/controllers/Careers.php 10910
ERROR - 2025-11-06 18:33:37 --> Severity: Warning --> Illegal string offset 'org_organization' /home/supp0rttest/public_html/staging/application/controllers/Careers.php 10902
ERROR - 2025-11-06 18:33:37 --> Severity: Warning --> Illegal string offset 'org_from_date' /home/supp0rttest/public_html/staging/application/controllers/Careers.php 10903
ERROR - 2025-11-06 18:33:37 --> Severity: Warning --> Illegal string offset 'org_to_date' /home/supp0rttest/public_html/staging/application/controllers/Careers.php 10904
ERROR - 2025-11-06 18:33:37 --> Severity: Warning --> Illegal string offset 'org_designation' /home/supp0rttest/public_html/staging/application/controllers/Careers.php 10905
ERROR - 2025-11-06 18:33:37 --> Severity: Warning --> Illegal string offset 'org_responsibilities' /home/supp0rttest/public_html/staging/application/controllers/Careers.php 10906
ERROR - 2025-11-06 18:33:37 --> Severity: Warning --> Illegal string offset 'org_organization' /home/supp0rttest/public_html/staging/application/controllers/Careers.php 10910
ERROR - 2025-11-06 18:33:37 --> Severity: Warning --> Invalid argument supplied for foreach() /home/supp0rttest/public_html/staging/application/controllers/Careers.php 10916
ERROR - 2025-11-06 18:33:37 --> Severity: Warning --> Invalid argument supplied for foreach() /home/supp0rttest/public_html/staging/application/controllers/Careers.php 10936
ERROR - 2025-11-06 18:33:37 --> 404 Page Not Found: Assets/js
ERROR - 2025-11-06 18:48:23 --> 404 Page Not Found: Assets/js
