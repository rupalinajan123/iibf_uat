<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2025-01-15 10:51:56 --> Severity: Notice --> Undefined variable: pending_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 159
ERROR - 2025-01-15 10:51:56 --> Severity: Notice --> Undefined variable: approved_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 160
ERROR - 2025-01-15 10:51:56 --> Severity: Notice --> Undefined variable: pending_dra_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 161
ERROR - 2025-01-15 10:51:56 --> Severity: Notice --> Undefined variable: approved_dra_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 162
ERROR - 2025-01-15 10:51:56 --> Severity: Notice --> Undefined variable: approved_dra_reg_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 164
ERROR - 2025-01-15 10:51:56 --> Severity: Notice --> Undefined variable: pending_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 165
ERROR - 2025-01-15 10:51:56 --> Severity: Notice --> Undefined variable: approved_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 166
ERROR - 2025-01-15 10:51:56 --> Severity: Notice --> Undefined variable: from_date /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 27
ERROR - 2025-01-15 10:51:56 --> Severity: Notice --> Undefined variable: to_date /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 34
ERROR - 2025-01-15 10:51:56 --> Severity: Notice --> Undefined variable: from_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 101
ERROR - 2025-01-15 10:51:56 --> Severity: Notice --> Undefined variable: to_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 108
ERROR - 2025-01-15 11:41:30 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2025-01-15 11:47:01 --> Severity: Notice --> Undefined variable: pending_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 159
ERROR - 2025-01-15 11:47:01 --> Severity: Notice --> Undefined variable: approved_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 160
ERROR - 2025-01-15 11:47:01 --> Severity: Notice --> Undefined variable: pending_dra_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 161
ERROR - 2025-01-15 11:47:01 --> Severity: Notice --> Undefined variable: approved_dra_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 162
ERROR - 2025-01-15 11:47:01 --> Severity: Notice --> Undefined variable: approved_dra_reg_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 164
ERROR - 2025-01-15 11:47:01 --> Severity: Notice --> Undefined variable: pending_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 165
ERROR - 2025-01-15 11:47:01 --> Severity: Notice --> Undefined variable: approved_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 166
ERROR - 2025-01-15 11:47:01 --> Severity: Notice --> Undefined variable: from_date /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 27
ERROR - 2025-01-15 11:47:01 --> Severity: Notice --> Undefined variable: to_date /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 34
ERROR - 2025-01-15 11:47:01 --> Severity: Notice --> Undefined variable: from_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 101
ERROR - 2025-01-15 11:47:01 --> Severity: Notice --> Undefined variable: to_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 108
ERROR - 2025-01-15 13:14:07 --> 404 Page Not Found: iibfdra/Version_2/TrainingBatches/favicon.ico
ERROR - 2025-01-15 13:15:32 --> 404 Page Not Found: ApplyElearning/favicon.ico
ERROR - 2025-01-15 13:27:06 --> Query error: Column 'status' in field list is ambiguous - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000299" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1" OR `dra_payment_transaction`.`status` = "2" OR `dra_payment_transaction`.`status` = "3") AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226) AND date(dra_payment_transaction.date) > "2017-01-08"
ORDER BY `date` DESC
ERROR - 2025-01-15 13:27:37 --> Query error: Column 'status' in field list is ambiguous - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000346" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1" OR `dra_payment_transaction`.`status` = "2" OR `dra_payment_transaction`.`status` = "3") AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226) AND date(dra_payment_transaction.date) > "2017-01-08"
ORDER BY `date` DESC
ERROR - 2025-01-15 16:08:38 --> Severity: Notice --> Undefined variable: pending_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 159
ERROR - 2025-01-15 16:08:38 --> Severity: Notice --> Undefined variable: approved_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 160
ERROR - 2025-01-15 16:08:38 --> Severity: Notice --> Undefined variable: pending_dra_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 161
ERROR - 2025-01-15 16:08:38 --> Severity: Notice --> Undefined variable: approved_dra_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 162
ERROR - 2025-01-15 16:08:38 --> Severity: Notice --> Undefined variable: approved_dra_reg_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 164
ERROR - 2025-01-15 16:08:38 --> Severity: Notice --> Undefined variable: pending_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 165
ERROR - 2025-01-15 16:08:38 --> Severity: Notice --> Undefined variable: approved_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 166
ERROR - 2025-01-15 16:08:38 --> Severity: Notice --> Undefined variable: from_date /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 27
ERROR - 2025-01-15 16:08:38 --> Severity: Notice --> Undefined variable: to_date /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 34
ERROR - 2025-01-15 16:08:38 --> Severity: Notice --> Undefined variable: from_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 101
ERROR - 2025-01-15 16:08:38 --> Severity: Notice --> Undefined variable: to_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 108
ERROR - 2025-01-15 16:32:57 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-01-15 16:32:57 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-01-15 16:32:57 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-01-15 16:32:57 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-01-15 16:33:20 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-01-15 16:33:20 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-01-15 16:33:20 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-01-15 16:33:20 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-01-15 16:46:11 --> 404 Page Not Found: BULK/admin
ERROR - 2025-01-15 16:59:18 --> Severity: Notice --> Undefined variable: pending_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 159
ERROR - 2025-01-15 16:59:18 --> Severity: Notice --> Undefined variable: approved_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 160
ERROR - 2025-01-15 16:59:18 --> Severity: Notice --> Undefined variable: pending_dra_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 161
ERROR - 2025-01-15 16:59:18 --> Severity: Notice --> Undefined variable: approved_dra_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 162
ERROR - 2025-01-15 16:59:18 --> Severity: Notice --> Undefined variable: approved_dra_reg_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 164
ERROR - 2025-01-15 16:59:18 --> Severity: Notice --> Undefined variable: pending_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 165
ERROR - 2025-01-15 16:59:18 --> Severity: Notice --> Undefined variable: approved_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 166
ERROR - 2025-01-15 16:59:18 --> Severity: Notice --> Undefined variable: from_date /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 27
ERROR - 2025-01-15 16:59:18 --> Severity: Notice --> Undefined variable: to_date /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 34
ERROR - 2025-01-15 16:59:18 --> Severity: Notice --> Undefined variable: from_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 101
ERROR - 2025-01-15 16:59:18 --> Severity: Notice --> Undefined variable: to_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 108
ERROR - 2025-01-15 17:55:31 --> Severity: Notice --> Undefined variable: pending_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 159
ERROR - 2025-01-15 17:55:31 --> Severity: Notice --> Undefined variable: approved_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 160
ERROR - 2025-01-15 17:55:31 --> Severity: Notice --> Undefined variable: approved_dra_reg_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 164
ERROR - 2025-01-15 17:55:31 --> Severity: Notice --> Undefined variable: pending_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 165
ERROR - 2025-01-15 17:55:31 --> Severity: Notice --> Undefined variable: approved_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 166
ERROR - 2025-01-15 17:55:31 --> Severity: Notice --> Undefined variable: from_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 101
ERROR - 2025-01-15 17:55:31 --> Severity: Notice --> Undefined variable: to_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 108
ERROR - 2025-01-15 17:59:56 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-15 18:09:30 --> Query error: Unknown column 'app_type' in 'where clause' - Invalid query: SELECT count(dra_payment_transaction.receipt_no) AS `numrows` FROM dra_payment_transaction WHERE dra_payment_transaction.exam_code IN (45,57,1036) AND dra_payment_transaction.status = 3 AND Date(`date`) BETWEEN '2025-01-01' and '2025-01-12' AND app_type='I'
ERROR - 2025-01-15 18:09:30 --> Severity: Error --> Call to a member function result() on boolean /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 100
ERROR - 2025-01-15 18:10:42 --> Query error: Unknown column 'app_type' in 'where clause' - Invalid query: SELECT count(dra_payment_transaction.receipt_no) AS `numrows` FROM dra_payment_transaction WHERE dra_payment_transaction.exam_code IN (45,57,1036) AND dra_payment_transaction.status = 3 AND Date(`date`) BETWEEN '2025-01-01' and '2025-01-12' AND app_type='I'
ERROR - 2025-01-15 18:10:42 --> Severity: Error --> Call to a member function result() on boolean /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 100
ERROR - 2025-01-15 18:10:44 --> Severity: Notice --> Undefined variable: pending_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 163
ERROR - 2025-01-15 18:10:44 --> Severity: Notice --> Undefined variable: approved_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 164
ERROR - 2025-01-15 18:10:44 --> Severity: Notice --> Undefined variable: pending_dra_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 165
ERROR - 2025-01-15 18:10:44 --> Severity: Notice --> Undefined variable: approved_dra_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 166
ERROR - 2025-01-15 18:10:44 --> Severity: Notice --> Undefined variable: approved_dra_reg_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 168
ERROR - 2025-01-15 18:10:44 --> Severity: Notice --> Undefined variable: pending_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 169
ERROR - 2025-01-15 18:10:44 --> Severity: Notice --> Undefined variable: approved_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 170
ERROR - 2025-01-15 18:10:44 --> Severity: Notice --> Undefined variable: from_date /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 27
ERROR - 2025-01-15 18:10:44 --> Severity: Notice --> Undefined variable: to_date /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 34
ERROR - 2025-01-15 18:10:44 --> Severity: Notice --> Undefined variable: from_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 101
ERROR - 2025-01-15 18:10:44 --> Severity: Notice --> Undefined variable: to_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 108
ERROR - 2025-01-15 18:10:46 --> Severity: Notice --> Undefined variable: pending_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 163
ERROR - 2025-01-15 18:10:46 --> Severity: Notice --> Undefined variable: approved_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 164
ERROR - 2025-01-15 18:10:46 --> Severity: Notice --> Undefined variable: pending_dra_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 165
ERROR - 2025-01-15 18:10:46 --> Severity: Notice --> Undefined variable: approved_dra_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 166
ERROR - 2025-01-15 18:10:46 --> Severity: Notice --> Undefined variable: approved_dra_reg_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 168
ERROR - 2025-01-15 18:10:46 --> Severity: Notice --> Undefined variable: pending_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 169
ERROR - 2025-01-15 18:10:46 --> Severity: Notice --> Undefined variable: approved_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 170
ERROR - 2025-01-15 18:10:46 --> Severity: Notice --> Undefined variable: from_date /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 27
ERROR - 2025-01-15 18:10:46 --> Severity: Notice --> Undefined variable: to_date /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 34
ERROR - 2025-01-15 18:10:46 --> Severity: Notice --> Undefined variable: from_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 101
ERROR - 2025-01-15 18:10:46 --> Severity: Notice --> Undefined variable: to_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 108
ERROR - 2025-01-15 18:10:57 --> Query error: Unknown column 'app_type' in 'where clause' - Invalid query: SELECT count(dra_payment_transaction.receipt_no) AS `numrows` FROM dra_payment_transaction WHERE dra_payment_transaction.exam_code IN (45,57,1036) AND dra_payment_transaction.status = 3 AND Date(`date`) BETWEEN '2025-01-01' and '2025-01-12' AND app_type='I'
ERROR - 2025-01-15 18:10:57 --> Severity: Error --> Call to a member function result() on boolean /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 100
ERROR - 2025-01-15 18:12:04 --> Severity: Notice --> Undefined variable: pending_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 163
ERROR - 2025-01-15 18:12:04 --> Severity: Notice --> Undefined variable: approved_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 164
ERROR - 2025-01-15 18:12:04 --> Severity: Notice --> Undefined variable: approved_dra_reg_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 168
ERROR - 2025-01-15 18:12:04 --> Severity: Notice --> Undefined variable: pending_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 169
ERROR - 2025-01-15 18:12:04 --> Severity: Notice --> Undefined variable: approved_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 170
ERROR - 2025-01-15 18:12:04 --> Severity: Notice --> Undefined variable: from_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 101
ERROR - 2025-01-15 18:12:04 --> Severity: Notice --> Undefined variable: to_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 108
ERROR - 2025-01-15 18:13:03 --> Severity: Notice --> Undefined variable: pending_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 163
ERROR - 2025-01-15 18:13:03 --> Severity: Notice --> Undefined variable: approved_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 164
ERROR - 2025-01-15 18:13:03 --> Severity: Notice --> Undefined variable: approved_dra_reg_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 168
ERROR - 2025-01-15 18:13:03 --> Severity: Notice --> Undefined variable: pending_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 169
ERROR - 2025-01-15 18:13:03 --> Severity: Notice --> Undefined variable: approved_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 170
ERROR - 2025-01-15 18:13:03 --> Severity: Notice --> Undefined variable: from_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 101
ERROR - 2025-01-15 18:13:03 --> Severity: Notice --> Undefined variable: to_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 108
ERROR - 2025-01-15 18:14:29 --> Severity: Notice --> Undefined property: stdClass::$numrows /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 114
ERROR - 2025-01-15 18:15:47 --> Severity: Notice --> Undefined property: stdClass::$numrows /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 114
ERROR - 2025-01-15 18:30:13 --> Severity: Notice --> Undefined variable: pending_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 163
ERROR - 2025-01-15 18:30:13 --> Severity: Notice --> Undefined variable: approved_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 164
ERROR - 2025-01-15 18:30:13 --> Severity: Notice --> Undefined variable: approved_dra_reg_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 168
ERROR - 2025-01-15 18:30:13 --> Severity: Notice --> Undefined variable: pending_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 169
ERROR - 2025-01-15 18:30:13 --> Severity: Notice --> Undefined variable: approved_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 170
ERROR - 2025-01-15 18:30:13 --> Severity: Notice --> Undefined variable: from_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 101
ERROR - 2025-01-15 18:30:13 --> Severity: Notice --> Undefined variable: to_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 108
ERROR - 2025-01-15 18:40:33 --> Severity: Notice --> Undefined variable: pending_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 163
ERROR - 2025-01-15 18:40:33 --> Severity: Notice --> Undefined variable: approved_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 164
ERROR - 2025-01-15 18:40:33 --> Severity: Notice --> Undefined variable: approved_dra_reg_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 168
ERROR - 2025-01-15 18:40:33 --> Severity: Notice --> Undefined variable: pending_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 169
ERROR - 2025-01-15 18:40:33 --> Severity: Notice --> Undefined variable: approved_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 170
ERROR - 2025-01-15 18:40:33 --> Severity: Notice --> Undefined variable: from_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 101
ERROR - 2025-01-15 18:40:33 --> Severity: Notice --> Undefined variable: to_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 108
ERROR - 2025-01-15 18:41:38 --> Severity: Notice --> Undefined variable: pending_dra_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 165
ERROR - 2025-01-15 18:41:38 --> Severity: Notice --> Undefined variable: approved_dra_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 166
ERROR - 2025-01-15 18:41:38 --> Severity: Notice --> Undefined variable: approved_dra_reg_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 168
ERROR - 2025-01-15 18:41:38 --> Severity: Notice --> Undefined variable: pending_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 169
ERROR - 2025-01-15 18:41:38 --> Severity: Notice --> Undefined variable: approved_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 170
ERROR - 2025-01-15 18:41:38 --> Severity: Notice --> Undefined variable: from_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 101
ERROR - 2025-01-15 18:41:38 --> Severity: Notice --> Undefined variable: to_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 108
ERROR - 2025-01-15 18:41:42 --> Severity: Notice --> Undefined variable: pending_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 163
ERROR - 2025-01-15 18:41:42 --> Severity: Notice --> Undefined variable: approved_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 164
ERROR - 2025-01-15 18:41:42 --> Severity: Notice --> Undefined variable: approved_dra_reg_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 168
ERROR - 2025-01-15 18:41:42 --> Severity: Notice --> Undefined variable: pending_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 169
ERROR - 2025-01-15 18:41:42 --> Severity: Notice --> Undefined variable: approved_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 170
ERROR - 2025-01-15 18:41:42 --> Severity: Notice --> Undefined variable: from_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 101
ERROR - 2025-01-15 18:41:42 --> Severity: Notice --> Undefined variable: to_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 108
ERROR - 2025-01-15 18:45:50 --> Severity: Notice --> Undefined variable: pending_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 163
ERROR - 2025-01-15 18:45:51 --> Severity: Notice --> Undefined variable: approved_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 164
ERROR - 2025-01-15 18:45:51 --> Severity: Notice --> Undefined variable: approved_dra_reg_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 168
ERROR - 2025-01-15 18:45:51 --> Severity: Notice --> Undefined variable: pending_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 169
ERROR - 2025-01-15 18:45:51 --> Severity: Notice --> Undefined variable: approved_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 170
ERROR - 2025-01-15 18:45:51 --> Severity: Notice --> Undefined variable: from_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 101
ERROR - 2025-01-15 18:45:51 --> Severity: Notice --> Undefined variable: to_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 108
ERROR - 2025-01-15 18:46:11 --> 404 Page Not Found: Assets/chosen
ERROR - 2025-01-15 18:46:12 --> Severity: Notice --> Undefined variable: pending_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 163
ERROR - 2025-01-15 18:46:12 --> Severity: Notice --> Undefined variable: approved_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 164
ERROR - 2025-01-15 18:46:12 --> Severity: Notice --> Undefined variable: approved_dra_reg_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 168
ERROR - 2025-01-15 18:46:12 --> Severity: Notice --> Undefined variable: pending_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 169
ERROR - 2025-01-15 18:46:12 --> Severity: Notice --> Undefined variable: approved_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 170
ERROR - 2025-01-15 18:46:12 --> Severity: Notice --> Undefined variable: from_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 101
ERROR - 2025-01-15 18:46:12 --> Severity: Notice --> Undefined variable: to_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 108
ERROR - 2025-01-15 18:46:12 --> 404 Page Not Found: Assets/chosen
ERROR - 2025-01-15 18:47:16 --> Severity: Notice --> Undefined variable: pending_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 163
ERROR - 2025-01-15 18:47:16 --> Severity: Notice --> Undefined variable: approved_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 164
ERROR - 2025-01-15 18:47:16 --> Severity: Notice --> Undefined variable: approved_dra_reg_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 168
ERROR - 2025-01-15 18:47:16 --> Severity: Notice --> Undefined variable: pending_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 169
ERROR - 2025-01-15 18:47:16 --> Severity: Notice --> Undefined variable: approved_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 170
ERROR - 2025-01-15 18:47:16 --> Severity: Notice --> Undefined variable: from_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 101
ERROR - 2025-01-15 18:47:16 --> Severity: Notice --> Undefined variable: to_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 108
ERROR - 2025-01-15 18:47:54 --> Severity: Notice --> Undefined variable: pending_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 163
ERROR - 2025-01-15 18:47:54 --> Severity: Notice --> Undefined variable: approved_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 164
ERROR - 2025-01-15 18:47:54 --> Severity: Notice --> Undefined variable: approved_dra_reg_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 168
ERROR - 2025-01-15 18:47:54 --> Severity: Notice --> Undefined variable: pending_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 169
ERROR - 2025-01-15 18:47:54 --> Severity: Notice --> Undefined variable: approved_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 170
ERROR - 2025-01-15 18:47:54 --> Severity: Notice --> Undefined variable: from_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 101
ERROR - 2025-01-15 18:47:54 --> Severity: Notice --> Undefined variable: to_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 108
