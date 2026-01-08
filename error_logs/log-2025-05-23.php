ERROR - 2025-05-23 17:51:27 --> Query error: Column 'gstin_no' in field list is ambiguous - Invalid query: SELECT `inst_name`, `main_address1`, `main_address2`, `main_address3`, `main_address4`, `main_city`, `main_state`, `gstin_no`
FROM `dra_inst_registration`
JOIN `agency_center` ON `agency_center`.`agency_id` = `dra_inst_registration`.`id`
WHERE `id` = '217'
ERROR - 2025-05-23 17:51:27 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 240
ERROR - 2025-05-23 19:31:21 --> Severity: Warning --> trim() expects parameter 1 to be string, array given /home/supp0rttest/public_html/staging/application/libraries/MY_Form_validation.php 265
ERROR - 2025-05-23 20:44:01 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2025-05-23 23:59:31 --> 404 Page Not Found: Uploads/exam_instruction
