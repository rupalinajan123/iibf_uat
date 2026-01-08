<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2025-01-17 09:39:38 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 09:41:11 --> Query error: Column 'status' in field list is ambiguous - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000363" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1" OR `dra_payment_transaction`.`status` = "2" OR `dra_payment_transaction`.`status` = "3") AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226) AND date(dra_payment_transaction.date) > "2017-01-08"
ORDER BY `date` DESC
ERROR - 2025-01-17 09:48:15 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 09:51:32 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near '(dra_payment_transaction.status = "0" OR `dra_payment_transaction`.`status` = "1' at line 7 - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000363" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1" OR `dra_payment_transaction`.`status` = "2" OR dra_payment_transaction.status = "3")(dra_payment_transaction.status = "0" OR `dra_payment_transaction`.`status` = "1") AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ERROR - 2025-01-17 09:51:32 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near '(dra_payment_transaction.status = "0" OR `dra_payment_transaction`.`status` = "1' at line 7 - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000363" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1" OR `dra_payment_transaction`.`status` = "2" OR dra_payment_transaction.status = "3")(dra_payment_transaction.status = "0" OR `dra_payment_transaction`.`status` = "1") AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ORDER BY `date` DESC
ERROR - 2025-01-17 09:52:04 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`stat' at line 4 - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1") AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ERROR - 2025-01-17 09:52:04 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`stat' at line 4 - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1") AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ORDER BY `date` DESC
 LIMIT 10
ERROR - 2025-01-17 09:52:08 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`stat' at line 4 - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1") AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ERROR - 2025-01-17 09:52:08 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`stat' at line 4 - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1") AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ORDER BY `date` DESC
 LIMIT 10
ERROR - 2025-01-17 09:52:52 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transacti' at line 4 - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226) AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")
ERROR - 2025-01-17 09:52:52 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transacti' at line 4 - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226) AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")
ORDER BY `date` DESC
 LIMIT 10
ERROR - 2025-01-17 09:52:56 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transacti' at line 4 - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226) AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")
ERROR - 2025-01-17 09:52:56 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transacti' at line 4 - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226) AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")
ORDER BY `date` DESC
 LIMIT 10
ERROR - 2025-01-17 09:53:15 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transacti' at line 4 - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226) AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1") 
ERROR - 2025-01-17 09:53:15 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transacti' at line 4 - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226) AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1") 
ORDER BY `date` DESC
 LIMIT 10
ERROR - 2025-01-17 09:53:36 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transacti' at line 4 - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226) AND (`dra_payment_transaction`.`status` =0 OR `dra_payment_transaction`.`status` = 1)
ERROR - 2025-01-17 09:53:36 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transacti' at line 4 - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226) AND (`dra_payment_transaction`.`status` =0 OR `dra_payment_transaction`.`status` = 1)
ORDER BY `date` DESC
 LIMIT 10
ERROR - 2025-01-17 09:53:56 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transacti' at line 4 - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226) AND (`dra_payment_transaction`.`status` =0 OR `dra_payment_transaction`.`status` = 1)
ERROR - 2025-01-17 09:53:56 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transacti' at line 4 - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226) AND (`dra_payment_transaction`.`status` =0 OR `dra_payment_transaction`.`status` = 1)
ORDER BY `date` DESC
 LIMIT 10
ERROR - 2025-01-17 09:54:00 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 09:54:04 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 09:54:05 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transacti' at line 4 - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226) AND (`dra_payment_transaction`.`status` =0 OR `dra_payment_transaction`.`status` = 1)
ERROR - 2025-01-17 09:54:05 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transacti' at line 4 - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226) AND (`dra_payment_transaction`.`status` =0 OR `dra_payment_transaction`.`status` = 1)
ORDER BY `date` DESC
 LIMIT 10
ERROR - 2025-01-17 09:56:06 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 09:56:20 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 09:58:22 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 09:59:10 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near '(status = "0" OR `status` = "1") AND date(dra_payment_transaction.date) > "2017-' at line 7 - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000363" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1" OR `dra_payment_transaction`.`status` = "2" OR dra_payment_transaction.status = "3")(status = "0" OR `status` = "1") AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ERROR - 2025-01-17 09:59:10 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near '(status = "0" OR `status` = "1") AND date(dra_payment_transaction.date) > "2017-' at line 7 - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000363" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1" OR `dra_payment_transaction`.`status` = "2" OR dra_payment_transaction.status = "3")(status = "0" OR `status` = "1") AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ORDER BY `date` DESC
ERROR - 2025-01-17 09:59:15 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 09:59:27 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 09:59:38 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near '(status = "0" OR `status` = "1") AND date(dra_payment_transaction.date) > "2017-' at line 7 - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000363" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1" OR `dra_payment_transaction`.`status` = "2" OR dra_payment_transaction.status = "3")(status = "0" OR `status` = "1") AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ERROR - 2025-01-17 09:59:38 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near '(status = "0" OR `status` = "1") AND date(dra_payment_transaction.date) > "2017-' at line 7 - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000363" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1" OR `dra_payment_transaction`.`status` = "2" OR dra_payment_transaction.status = "3")(status = "0" OR `status` = "1") AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ORDER BY `date` DESC
ERROR - 2025-01-17 10:02:26 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 10:02:38 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near '(status = "0" OR `status` = "1") AND date(dra_payment_transaction.date) > "2017-' at line 7 - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE dra_members.regnumber = "800000363"(status = "0" OR `status` = "1") AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ERROR - 2025-01-17 10:02:38 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near '(status = "0" OR `status` = "1") AND date(dra_payment_transaction.date) > "2017-' at line 7 - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE dra_members.regnumber = "800000363"(status = "0" OR `status` = "1") AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ORDER BY `date` DESC
ERROR - 2025-01-17 10:05:00 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 10:10:55 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 10:29:33 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 10:29:40 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 10:41:31 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 10:41:45 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 10:42:01 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 10:43:09 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 10:44:15 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 10:44:19 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 10:45:33 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 10:46:54 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 10:57:00 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 10:57:20 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 10:59:51 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 12:06:16 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 12:06:25 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 12:21:23 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 12:22:47 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 12:25:31 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 12:26:02 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 12:26:39 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 12:26:53 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 12:27:03 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 12:28:13 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 12:28:15 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 12:29:05 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 12:29:07 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 12:29:34 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 14:15:15 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 14:15:17 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 14:15:40 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 14:16:28 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 14:20:43 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 14:21:09 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 14:24:11 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 14:30:10 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 14:37:03 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 14:37:22 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 14:38:35 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 14:38:44 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 14:38:54 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 14:40:08 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 14:41:06 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 14:42:29 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 14:42:50 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 14:43:06 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 14:43:45 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 14:44:39 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 14:44:45 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 14:44:52 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 14:45:06 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 14:45:58 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 14:46:50 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 14:50:22 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 14:52:28 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 14:52:35 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`stat' at line 4 - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE  AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ERROR - 2025-01-17 14:52:35 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`stat' at line 4 - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE  AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ORDER BY `date` DESC
 LIMIT 10, 10
ERROR - 2025-01-17 14:53:10 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`stat' at line 4 - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE  AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ERROR - 2025-01-17 14:53:10 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`stat' at line 4 - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE  AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ORDER BY `date` DESC
 LIMIT 10, 10
ERROR - 2025-01-17 14:58:33 --> Query error: Column 'exam_period' in where clause is ambiguous - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000299" AND `gateway` = "2" AND `dra_payment_transaction`.`status` = "1" AND `dra_payment_transaction`.`inst_code` = "8216" AND `exam_period` = "10" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ERROR - 2025-01-17 14:58:33 --> Query error: Column 'exam_period' in where clause is ambiguous - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000299" AND `gateway` = "2" AND `dra_payment_transaction`.`status` = "1" AND `dra_payment_transaction`.`inst_code` = "8216" AND `exam_period` = "10" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ORDER BY `date` DESC
 LIMIT 10
ERROR - 2025-01-17 14:59:13 --> Query error: Column 'exam_period' in where clause is ambiguous - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000359" AND `gateway` = "2" AND `exam_period` = "10" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ERROR - 2025-01-17 14:59:13 --> Query error: Column 'exam_period' in where clause is ambiguous - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000359" AND `gateway` = "2" AND `exam_period` = "10" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ORDER BY `date` DESC
 LIMIT 10
ERROR - 2025-01-17 14:59:16 --> Query error: Column 'exam_period' in where clause is ambiguous - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000359" AND `gateway` = "2" AND `exam_period` = "10" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ERROR - 2025-01-17 14:59:16 --> Query error: Column 'exam_period' in where clause is ambiguous - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000359" AND `gateway` = "2" AND `exam_period` = "10" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ORDER BY `date` DESC
 LIMIT 10
ERROR - 2025-01-17 14:59:21 --> Query error: Column 'exam_period' in where clause is ambiguous - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000359" AND `exam_period` = "10" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ERROR - 2025-01-17 14:59:21 --> Query error: Column 'exam_period' in where clause is ambiguous - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000359" AND `exam_period` = "10" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ORDER BY `date` DESC
 LIMIT 10
ERROR - 2025-01-17 15:00:33 --> Query error: Column 'exam_code' in where clause is ambiguous - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000359" AND `exam_code` = "455" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ERROR - 2025-01-17 15:00:33 --> Query error: Column 'exam_code' in where clause is ambiguous - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000359" AND `exam_code` = "455" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ORDER BY `date` DESC
 LIMIT 10
ERROR - 2025-01-17 15:00:36 --> Query error: Column 'exam_code' in where clause is ambiguous - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000359" AND `exam_code` = "1036" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ERROR - 2025-01-17 15:00:36 --> Query error: Column 'exam_code' in where clause is ambiguous - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000359" AND `exam_code` = "1036" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ORDER BY `date` DESC
 LIMIT 10
ERROR - 2025-01-17 15:00:40 --> Query error: Column 'exam_code' in where clause is ambiguous - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000359" AND `exam_code` = "1036" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ERROR - 2025-01-17 15:00:40 --> Query error: Column 'exam_code' in where clause is ambiguous - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000359" AND `exam_code` = "1036" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ORDER BY `date` DESC
 LIMIT 10
ERROR - 2025-01-17 15:00:42 --> Query error: Column 'exam_code' in where clause is ambiguous - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000359" AND `exam_code` = "45" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ERROR - 2025-01-17 15:00:42 --> Query error: Column 'exam_code' in where clause is ambiguous - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000359" AND `exam_code` = "45" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ORDER BY `date` DESC
 LIMIT 10
ERROR - 2025-01-17 15:00:44 --> Query error: Column 'exam_code' in where clause is ambiguous - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000359" AND `exam_code` = "45" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ERROR - 2025-01-17 15:00:44 --> Query error: Column 'exam_code' in where clause is ambiguous - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000359" AND `exam_code` = "45" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ORDER BY `date` DESC
 LIMIT 10
ERROR - 2025-01-17 15:00:47 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 15:00:57 --> Query error: Column 'exam_code' in where clause is ambiguous - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000359" AND `exam_code` = "1036" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ERROR - 2025-01-17 15:00:57 --> Query error: Column 'exam_code' in where clause is ambiguous - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000359" AND `exam_code` = "1036" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ORDER BY `date` DESC
 LIMIT 10
ERROR - 2025-01-17 15:01:05 --> Query error: Column 'exam_code' in where clause is ambiguous - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000359" AND `exam_code` = "1036" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ERROR - 2025-01-17 15:01:05 --> Query error: Column 'exam_code' in where clause is ambiguous - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000359" AND `exam_code` = "1036" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ORDER BY `date` DESC
 LIMIT 10
ERROR - 2025-01-17 15:02:50 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`stat' at line 4 - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE  AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ERROR - 2025-01-17 15:02:50 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`stat' at line 4 - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE  AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ORDER BY `date` DESC
 LIMIT 10, 10
ERROR - 2025-01-17 15:02:58 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 15:03:04 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`stat' at line 4 - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE  AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ERROR - 2025-01-17 15:03:04 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`stat' at line 4 - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE  AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ORDER BY `date` DESC
 LIMIT 10, 10
ERROR - 2025-01-17 15:03:36 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 15:04:53 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2025-01-17 15:06:42 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 15:06:55 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 15:07:04 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`stat' at line 4 - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE  AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ERROR - 2025-01-17 15:07:04 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`stat' at line 4 - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE  AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ORDER BY `date` DESC
 LIMIT 10, 10
ERROR - 2025-01-17 15:08:29 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`stat' at line 4 - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE  AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ERROR - 2025-01-17 15:08:29 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`stat' at line 4 - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE  AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ORDER BY `date` DESC
 LIMIT 10, 10
ERROR - 2025-01-17 15:12:36 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`stat' at line 4 - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE  AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ERROR - 2025-01-17 15:12:36 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`stat' at line 4 - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE  AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ORDER BY `date` DESC
 LIMIT 10, 10
ERROR - 2025-01-17 15:13:02 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`stat' at line 4 - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE  AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ERROR - 2025-01-17 15:13:02 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`stat' at line 4 - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE  AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ORDER BY `date` DESC
 LIMIT 10, 10
ERROR - 2025-01-17 15:13:49 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`stat' at line 4 - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE  AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ERROR - 2025-01-17 15:13:49 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`stat' at line 4 - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
WHERE  AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ORDER BY `date` DESC
 LIMIT 10, 10
ERROR - 2025-01-17 15:16:21 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 15:16:29 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 15:19:12 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 15:23:02 --> Query error: Column 'exam_period' in where clause is ambiguous - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "U123R9G0006ZAT" AND (DATE(dra_payment_transaction.date) BETWEEN "2023-01-01" AND "2025-01-16") AND `gateway` = "2" AND `dra_payment_transaction`.`inst_code` = "8216" AND `exam_period` = "10" AND `exam_code` = "1036" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ERROR - 2025-01-17 15:23:02 --> Query error: Column 'exam_period' in where clause is ambiguous - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "U123R9G0006ZAT" AND (DATE(dra_payment_transaction.date) BETWEEN "2023-01-01" AND "2025-01-16") AND `gateway` = "2" AND `dra_payment_transaction`.`inst_code` = "8216" AND `exam_period` = "10" AND `exam_code` = "1036" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ORDER BY `date` DESC
 LIMIT 10
ERROR - 2025-01-17 15:23:16 --> Query error: Column 'exam_period' in where clause is ambiguous - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000299" AND (DATE(dra_payment_transaction.date) BETWEEN "2023-01-01" AND "2025-01-16") AND `gateway` = "2" AND `dra_payment_transaction`.`inst_code` = "8216" AND `exam_period` = "10" AND `exam_code` = "1036" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ERROR - 2025-01-17 15:23:16 --> Query error: Column 'exam_period' in where clause is ambiguous - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000299" AND (DATE(dra_payment_transaction.date) BETWEEN "2023-01-01" AND "2025-01-16") AND `gateway` = "2" AND `dra_payment_transaction`.`inst_code` = "8216" AND `exam_period` = "10" AND `exam_code` = "1036" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ORDER BY `date` DESC
 LIMIT 10
ERROR - 2025-01-17 15:25:03 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 15:25:28 --> Query error: Column 'exam_period' in where clause is ambiguous - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000299" AND (DATE(dra_payment_transaction.date) BETWEEN "2023-01-01" AND "2025-01-16") AND `gateway` = "2" AND `dra_payment_transaction`.`inst_code` = "8216" AND `exam_period` = "10" AND `exam_code` = "1036" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ERROR - 2025-01-17 15:25:28 --> Query error: Column 'exam_period' in where clause is ambiguous - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000299" AND (DATE(dra_payment_transaction.date) BETWEEN "2023-01-01" AND "2025-01-16") AND `gateway` = "2" AND `dra_payment_transaction`.`inst_code` = "8216" AND `exam_period` = "10" AND `exam_code` = "1036" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ORDER BY `date` DESC
 LIMIT 10
ERROR - 2025-01-17 15:25:44 --> Query error: Column 'exam_period' in where clause is ambiguous - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000363" AND (DATE(dra_payment_transaction.date) BETWEEN "2023-01-01" AND "2025-01-16") AND `gateway` = "2" AND `dra_payment_transaction`.`inst_code` = "8216" AND `exam_period` = "10" AND `exam_code` = "1036" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ERROR - 2025-01-17 15:25:44 --> Query error: Column 'exam_period' in where clause is ambiguous - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000363" AND (DATE(dra_payment_transaction.date) BETWEEN "2023-01-01" AND "2025-01-16") AND `gateway` = "2" AND `dra_payment_transaction`.`inst_code` = "8216" AND `exam_period` = "10" AND `exam_code` = "1036" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ORDER BY `date` DESC
 LIMIT 10
ERROR - 2025-01-17 15:25:50 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 15:25:57 --> Query error: Column 'exam_period' in where clause is ambiguous - Invalid query: SELECT count(*) as tot
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000363" AND (DATE(dra_payment_transaction.date) BETWEEN "2023-01-01" AND "2025-01-16") AND `gateway` = "2" AND `dra_payment_transaction`.`inst_code` = "8216" AND `exam_period` = "10" AND `exam_code` = "1036" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ERROR - 2025-01-17 15:25:57 --> Query error: Column 'exam_period' in where clause is ambiguous - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000363" AND (DATE(dra_payment_transaction.date) BETWEEN "2023-01-01" AND "2025-01-16") AND `gateway` = "2" AND `dra_payment_transaction`.`inst_code` = "8216" AND `exam_period` = "10" AND `exam_code` = "1036" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` IN (8214,1122,8120,8217,8014,8007,1100,835,624,8218,8219,179,796,8087,8212,735,1123,8204,516,1059,8024,182,257,586,1141,8102,8216,8217,8218,8219,8220,8221,8222,8223,8224,8225,8226)
ORDER BY `date` DESC
 LIMIT 10
ERROR - 2025-01-17 15:49:36 --> Query error: Column 'exam_code' in field list is ambiguous - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `exam_code`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000299" AND (DATE(dra_payment_transaction.date) BETWEEN "2023-02-07" AND "2025-01-16") AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND `dra_payment_transaction`.`inst_code` = 8216 AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` = 8216
ORDER BY `date` DESC
 LIMIT 10
ERROR - 2025-01-17 15:49:46 --> Query error: Column 'exam_code' in field list is ambiguous - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `exam_code`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000299" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND `dra_payment_transaction`.`inst_code` = 8216 AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` = 8216
ORDER BY `date` DESC
 LIMIT 10
ERROR - 2025-01-17 15:49:55 --> Query error: Column 'exam_code' in field list is ambiguous - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `exam_code`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000299" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND `dra_payment_transaction`.`inst_code` = 8216 AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` = 8216
ORDER BY `date` DESC
 LIMIT 10
ERROR - 2025-01-17 15:51:16 --> Query error: Column 'exam_code' in field list is ambiguous - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `exam_code`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000363" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND `dra_payment_transaction`.`inst_code` = 8216 AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` = 8216
ORDER BY `date` DESC
 LIMIT 10
ERROR - 2025-01-17 15:51:24 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 15:51:24 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 15:51:25 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 15:53:26 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 15:53:26 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 15:54:20 --> Query error: Column 'exam_code' in field list is ambiguous - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `exam_code`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "sadasd" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND `dra_payment_transaction`.`inst_code` = 8216 AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` = 8216
ORDER BY `date` DESC
 LIMIT 10
ERROR - 2025-01-17 15:56:10 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 15:56:11 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 15:56:29 --> Query error: Column 'exam_code' in field list is ambiguous - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `exam_code`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "ssds" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND `dra_payment_transaction`.`inst_code` = 8216 AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` = 8216
ORDER BY `date` DESC
 LIMIT 10
ERROR - 2025-01-17 15:57:25 --> Query error: Column 'exam_code' in field list is ambiguous - Invalid query: SELECT `dra_payment_transaction`.`id`, `gateway`, `exam_code`, `dra_payment_transaction`.`inst_code`, `receipt_no`, `dra_payment_transaction`.`status`, `transaction_no`, `UTR_no`, DATE_FORMAT(date, "%Y-%m-%d") As pay_date, `bankcode`, `pay_count` AS `member_count`, `amount`, `tds_amount`, `dra_accerdited_master`.`institute_name` AS `inst_name`
FROM `dra_payment_transaction`
LEFT JOIN `dra_accerdited_master` ON `dra_accerdited_master`.`institute_code` = `dra_payment_transaction`.`inst_code`
LEFT JOIN `dra_member_payment_transaction` ON `dra_member_payment_transaction`.`ptid` = `dra_payment_transaction`.`id`
LEFT JOIN `dra_member_exam` ON `dra_member_exam`.`id` = `dra_member_payment_transaction`.`memexamid`
LEFT JOIN `dra_members` ON `dra_members`.`regid` = `dra_member_exam`.`regid`
WHERE `dra_members`.`regnumber` = "800000363" AND (`dra_payment_transaction`.`status` = "0" OR `dra_payment_transaction`.`status` = "1")  AND `dra_payment_transaction`.`inst_code` = 8216 AND date(dra_payment_transaction.date) > "2017-01-08" AND `dra_payment_transaction`.`inst_code` = 8216
ORDER BY `date` DESC
 LIMIT 10
ERROR - 2025-01-17 15:59:05 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 15:59:06 --> 404 Page Not Found: Assets/js
ERROR - 2025-01-17 16:18:40 --> 404 Page Not Found: iibfdra/Version_2/TrainingBatches/favicon.ico
ERROR - 2025-01-17 16:27:46 --> Severity: Notice --> Undefined variable: pending_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 163
ERROR - 2025-01-17 16:27:46 --> Severity: Notice --> Undefined variable: approved_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 164
ERROR - 2025-01-17 16:27:46 --> Severity: Notice --> Undefined variable: pending_dra_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 165
ERROR - 2025-01-17 16:27:46 --> Severity: Notice --> Undefined variable: approved_dra_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 166
ERROR - 2025-01-17 16:27:46 --> Severity: Notice --> Undefined variable: approved_dra_reg_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 168
ERROR - 2025-01-17 16:27:46 --> Severity: Notice --> Undefined variable: pending_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 169
ERROR - 2025-01-17 16:27:46 --> Severity: Notice --> Undefined variable: approved_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 170
ERROR - 2025-01-17 16:27:46 --> Severity: Notice --> Undefined variable: from_date /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 27
ERROR - 2025-01-17 16:27:46 --> Severity: Notice --> Undefined variable: to_date /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 34
ERROR - 2025-01-17 16:27:46 --> Severity: Notice --> Undefined variable: from_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 101
ERROR - 2025-01-17 16:27:46 --> Severity: Notice --> Undefined variable: to_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 108
ERROR - 2025-01-17 16:28:23 --> Severity: Notice --> Undefined variable: pending_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 163
ERROR - 2025-01-17 16:28:23 --> Severity: Notice --> Undefined variable: approved_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 164
ERROR - 2025-01-17 16:28:23 --> Severity: Notice --> Undefined variable: approved_dra_reg_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 168
ERROR - 2025-01-17 16:28:23 --> Severity: Notice --> Undefined variable: pending_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 169
ERROR - 2025-01-17 16:28:23 --> Severity: Notice --> Undefined variable: approved_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 170
ERROR - 2025-01-17 16:28:23 --> Severity: Notice --> Undefined variable: from_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 101
ERROR - 2025-01-17 16:28:23 --> Severity: Notice --> Undefined variable: to_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 108
ERROR - 2025-01-17 16:28:53 --> Severity: Notice --> Undefined variable: pending_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 163
ERROR - 2025-01-17 16:28:53 --> Severity: Notice --> Undefined variable: approved_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 164
ERROR - 2025-01-17 16:28:53 --> Severity: Notice --> Undefined variable: approved_dra_reg_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 168
ERROR - 2025-01-17 16:28:53 --> Severity: Notice --> Undefined variable: pending_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 169
ERROR - 2025-01-17 16:28:53 --> Severity: Notice --> Undefined variable: approved_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 170
ERROR - 2025-01-17 16:28:53 --> Severity: Notice --> Undefined variable: from_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 101
ERROR - 2025-01-17 16:28:53 --> Severity: Notice --> Undefined variable: to_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 108
ERROR - 2025-01-17 16:29:44 --> Severity: Notice --> Undefined variable: pending_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 163
ERROR - 2025-01-17 16:29:44 --> Severity: Notice --> Undefined variable: approved_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 164
ERROR - 2025-01-17 16:29:44 --> Severity: Notice --> Undefined variable: approved_dra_reg_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 168
ERROR - 2025-01-17 16:29:44 --> Severity: Notice --> Undefined variable: pending_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 169
ERROR - 2025-01-17 16:29:44 --> Severity: Notice --> Undefined variable: approved_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 170
ERROR - 2025-01-17 16:29:44 --> Severity: Notice --> Undefined variable: from_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 101
ERROR - 2025-01-17 16:29:44 --> Severity: Notice --> Undefined variable: to_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 108
ERROR - 2025-01-17 16:30:38 --> Severity: Notice --> Undefined variable: pending_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 163
ERROR - 2025-01-17 16:30:38 --> Severity: Notice --> Undefined variable: approved_bulk_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 164
ERROR - 2025-01-17 16:30:38 --> Severity: Notice --> Undefined variable: approved_dra_reg_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 168
ERROR - 2025-01-17 16:30:38 --> Severity: Notice --> Undefined variable: pending_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 169
ERROR - 2025-01-17 16:30:38 --> Severity: Notice --> Undefined variable: approved_agn_renewal_count /home/supp0rttest/public_html/staging/application/controllers/admin/GstB2BDashboard.php 170
ERROR - 2025-01-17 16:30:38 --> Severity: Notice --> Undefined variable: from_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 101
ERROR - 2025-01-17 16:30:38 --> Severity: Notice --> Undefined variable: to_date_cn /home/supp0rttest/public_html/staging/application/views/gstb2bdashboard/gst_dashboard.php 108
ERROR - 2025-01-17 16:45:12 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2025-01-17 17:06:17 --> 404 Page Not Found: Assets/js
