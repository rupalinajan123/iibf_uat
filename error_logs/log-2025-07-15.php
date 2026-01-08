<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2025-07-15 10:33:09 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-15 10:33:15 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-15 10:38:24 --> 404 Page Not Found: iibfdra/Version_2/Center/favicon.ico
ERROR - 2025-07-15 11:51:42 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-15 12:15:47 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-15 12:15:58 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-15 12:16:21 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-15 12:16:58 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-15 12:17:17 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-15 14:45:03 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-15 14:45:37 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-15 14:45:59 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-15 14:46:36 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-15 14:46:45 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-15 14:47:05 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-15 14:47:26 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-15 14:47:42 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-15 14:47:50 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-15 14:58:37 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-15 15:07:15 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-15 15:07:37 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-15 15:14:28 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-15 15:25:53 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-15 15:26:16 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-15 15:27:06 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-15 15:27:37 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-15 15:27:56 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-15 16:50:32 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-15 17:05:31 --> Query error: Column 'transaction_no' in field list is ambiguous - Invalid query: SELECT `bulk_payment_transaction`.`id`, `bulk_payment_transaction`.`exam_code`, `bulk_payment_transaction`.`exam_period`, `bulk_payment_transaction`.`receipt_no`, `proforma_inv_no`, `bulk_payment_transaction`.`status`, `transaction_no`, `exam_invoice`.`created_on` as `exam_inv_date`, `pay_count` AS `member_count`, `amount`
FROM `bulk_payment_transaction`
LEFT JOIN `exam_invoice` ON `exam_invoice`.`pay_txn_id` = `bulk_payment_transaction`.`id`
LEFT JOIN `bulk_accerdited_master` ON `bulk_accerdited_master`.`institute_code` = `bulk_payment_transaction`.`inst_code`
LEFT JOIN `exam_master` ON `exam_master`.`exam_code` = `bulk_payment_transaction`.`exam_code`
WHERE `gateway` = "1"
AND `exam_invoice`.`app_type` = "Z"
AND `bulk_payment_transaction`.`inst_code` = "10092"
AND `bulk_payment_transaction`.`exam_code` != '997'
ORDER BY `created_date` DESC
 LIMIT 10
ERROR - 2025-07-15 17:06:44 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-15 17:06:47 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-15 17:06:47 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-15 17:06:47 --> Query error: Column 'transaction_no' in field list is ambiguous - Invalid query: SELECT `bulk_payment_transaction`.`id`, `bulk_payment_transaction`.`exam_code`, `bulk_payment_transaction`.`exam_period`, `bulk_payment_transaction`.`receipt_no`, `proforma_inv_no`, `bulk_payment_transaction`.`status`, `transaction_no`, `exam_invoice`.`created_on` as `exam_inv_date`, `pay_count` AS `member_count`, `amount`
FROM `bulk_payment_transaction`
LEFT JOIN `exam_invoice` ON `exam_invoice`.`pay_txn_id` = `bulk_payment_transaction`.`id`
LEFT JOIN `bulk_accerdited_master` ON `bulk_accerdited_master`.`institute_code` = `bulk_payment_transaction`.`inst_code`
LEFT JOIN `exam_master` ON `exam_master`.`exam_code` = `bulk_payment_transaction`.`exam_code`
WHERE `gateway` = "1"
AND `bulk_payment_transaction`.`exam_code` = "420"
AND `exam_invoice`.`app_type` = "Z"
AND `bulk_payment_transaction`.`inst_code` = "10092"
AND `bulk_payment_transaction`.`exam_code` != '997'
ORDER BY `created_date` DESC
 LIMIT 10
ERROR - 2025-07-15 17:08:49 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-15 17:08:49 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-15 18:06:59 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-15 18:07:03 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-07-15 18:23:31 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-15 18:23:31 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-15 18:27:38 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-15 18:27:38 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-15 18:30:02 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-15 18:30:02 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-15 18:30:26 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-15 18:30:26 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-15 18:30:55 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-15 18:30:55 --> 404 Page Not Found: Assets/js
ERROR - 2025-07-15 18:49:24 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 18:49:25 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 18:49:25 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 18:49:25 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 18:49:35 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 18:49:36 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 18:49:36 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 18:49:36 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 18:49:38 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 18:49:39 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 18:49:39 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 18:49:39 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 18:49:44 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 18:49:44 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 18:49:44 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 18:49:44 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 20:00:17 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 20:00:18 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 20:00:18 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 20:00:18 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 20:00:28 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 20:00:29 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 20:00:29 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 20:00:29 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 20:00:51 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 20:00:52 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 20:00:52 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 20:00:52 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 20:04:29 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 20:04:30 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 20:04:30 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 20:04:30 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 20:06:12 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 20:06:12 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 20:06:12 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 20:06:12 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 20:12:10 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 20:12:11 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 20:12:11 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 20:12:11 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 20:14:30 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 20:14:31 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 20:14:31 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 20:14:31 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 20:31:42 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 20:31:42 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 20:31:42 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 20:31:43 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 21:03:01 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 21:03:01 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 21:03:01 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 21:03:01 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 21:33:28 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 21:33:28 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 21:33:28 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 21:33:28 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 21:36:07 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 21:36:07 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 21:36:07 --> 404 Page Not Found: Assets/iibfbcbf
ERROR - 2025-07-15 21:36:07 --> 404 Page Not Found: Assets/iibfbcbf
