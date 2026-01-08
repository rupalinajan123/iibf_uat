<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2025-06-23 04:56:25 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2025-06-23 07:57:31 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2025-06-23 16:16:28 --> Query error: Table 'supp0rttest_iibf_staging.config_DISA_invoice' doesn't exist - Invalid query: INSERT INTO `config_DISA_invoice` (`invoice_id`) VALUES ('50077358')
ERROR - 2025-06-23 17:16:23 --> Query error: Unknown column 'firstname' in 'field list' - Invalid query: SELECT `exam_recovery_master`.*, `iibfbcbf_exam_master`.`description`, `regnumber`, `firstname`, `middlename`, `lastname`, `email`, `mobile`
FROM `exam_recovery_master`
LEFT JOIN `iibfbcbf_exam_master` ON `exam_recovery_master`.`exam_code` = `iibfbcbf_exam_master`.`exam_code`
LEFT JOIN `iibfbcbf_batch_candidates` ON `exam_recovery_master`.`member_no` = `iibfbcbf_batch_candidates`.`regnumber`
WHERE `member_no` = '802795477'
ERROR - 2025-06-23 17:16:23 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 240
