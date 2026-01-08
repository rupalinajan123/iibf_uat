<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2024-11-13 02:56:31 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2024-11-13 02:57:42 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2024-11-13 02:57:50 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2024-11-13 06:39:03 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2024-11-13 08:06:02 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2024-11-13 09:21:32 --> Query error: Unknown column 'institute_name' in 'where clause' - Invalid query: SELECT count(*) as tot
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%1%' ESCAPE '!'
OR  `institute_name` LIKE '%1%' ESCAPE '!'
OR  `description` LIKE '%1%' ESCAPE '!'
OR  `exam_code` LIKE '%1%' ESCAPE '!'
OR  `exam_period` LIKE '%1%' ESCAPE '!'
OR  `exam_from_date` LIKE '%1%' ESCAPE '!'
OR  `exam_to_date` LIKE '%1%' ESCAPE '!'
OR  `discount` LIKE '%1%' ESCAPE '!'
 )
ERROR - 2024-11-13 09:21:32 --> Query error: Column 'institute_code' in where clause is ambiguous - Invalid query: SELECT `a`.`id` `exam_active_id`, `a`.`exam_period`, `a`.`exam_code`, `exam_from_date`, `exam_from_time`, `exam_to_date`, `exam_to_time`, `b`.`id` `exam_id`, `b`.`description`, `a`.`tds`, `a`.`discount`, `c`.`institute_code`, `c`.`institute_name`
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
LEFT JOIN `bulk_accerdited_master` `c` ON `a`.`institute_code` = `c`.`institute_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%1%' ESCAPE '!'
OR  `institute_name` LIKE '%1%' ESCAPE '!'
OR  `description` LIKE '%1%' ESCAPE '!'
OR  `exam_code` LIKE '%1%' ESCAPE '!'
OR  `exam_period` LIKE '%1%' ESCAPE '!'
OR  `exam_from_date` LIKE '%1%' ESCAPE '!'
OR  `exam_to_date` LIKE '%1%' ESCAPE '!'
OR  `discount` LIKE '%1%' ESCAPE '!'
 )
 LIMIT 10
ERROR - 2024-11-13 09:21:32 --> Query error: Unknown column 'institute_name' in 'where clause' - Invalid query: SELECT count(*) as tot
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%10%' ESCAPE '!'
OR  `institute_name` LIKE '%10%' ESCAPE '!'
OR  `description` LIKE '%10%' ESCAPE '!'
OR  `exam_code` LIKE '%10%' ESCAPE '!'
OR  `exam_period` LIKE '%10%' ESCAPE '!'
OR  `exam_from_date` LIKE '%10%' ESCAPE '!'
OR  `exam_to_date` LIKE '%10%' ESCAPE '!'
OR  `discount` LIKE '%10%' ESCAPE '!'
 )
ERROR - 2024-11-13 09:21:32 --> Query error: Column 'institute_code' in where clause is ambiguous - Invalid query: SELECT `a`.`id` `exam_active_id`, `a`.`exam_period`, `a`.`exam_code`, `exam_from_date`, `exam_from_time`, `exam_to_date`, `exam_to_time`, `b`.`id` `exam_id`, `b`.`description`, `a`.`tds`, `a`.`discount`, `c`.`institute_code`, `c`.`institute_name`
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
LEFT JOIN `bulk_accerdited_master` `c` ON `a`.`institute_code` = `c`.`institute_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%10%' ESCAPE '!'
OR  `institute_name` LIKE '%10%' ESCAPE '!'
OR  `description` LIKE '%10%' ESCAPE '!'
OR  `exam_code` LIKE '%10%' ESCAPE '!'
OR  `exam_period` LIKE '%10%' ESCAPE '!'
OR  `exam_from_date` LIKE '%10%' ESCAPE '!'
OR  `exam_to_date` LIKE '%10%' ESCAPE '!'
OR  `discount` LIKE '%10%' ESCAPE '!'
 )
 LIMIT 10
ERROR - 2024-11-13 09:21:32 --> Query error: Unknown column 'institute_name' in 'where clause' - Invalid query: SELECT count(*) as tot
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%100%' ESCAPE '!'
OR  `institute_name` LIKE '%100%' ESCAPE '!'
OR  `description` LIKE '%100%' ESCAPE '!'
OR  `exam_code` LIKE '%100%' ESCAPE '!'
OR  `exam_period` LIKE '%100%' ESCAPE '!'
OR  `exam_from_date` LIKE '%100%' ESCAPE '!'
OR  `exam_to_date` LIKE '%100%' ESCAPE '!'
OR  `discount` LIKE '%100%' ESCAPE '!'
 )
ERROR - 2024-11-13 09:21:32 --> Query error: Column 'institute_code' in where clause is ambiguous - Invalid query: SELECT `a`.`id` `exam_active_id`, `a`.`exam_period`, `a`.`exam_code`, `exam_from_date`, `exam_from_time`, `exam_to_date`, `exam_to_time`, `b`.`id` `exam_id`, `b`.`description`, `a`.`tds`, `a`.`discount`, `c`.`institute_code`, `c`.`institute_name`
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
LEFT JOIN `bulk_accerdited_master` `c` ON `a`.`institute_code` = `c`.`institute_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%100%' ESCAPE '!'
OR  `institute_name` LIKE '%100%' ESCAPE '!'
OR  `description` LIKE '%100%' ESCAPE '!'
OR  `exam_code` LIKE '%100%' ESCAPE '!'
OR  `exam_period` LIKE '%100%' ESCAPE '!'
OR  `exam_from_date` LIKE '%100%' ESCAPE '!'
OR  `exam_to_date` LIKE '%100%' ESCAPE '!'
OR  `discount` LIKE '%100%' ESCAPE '!'
 )
 LIMIT 10
ERROR - 2024-11-13 09:21:33 --> Query error: Unknown column 'institute_name' in 'where clause' - Invalid query: SELECT count(*) as tot
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%1001%' ESCAPE '!'
OR  `institute_name` LIKE '%1001%' ESCAPE '!'
OR  `description` LIKE '%1001%' ESCAPE '!'
OR  `exam_code` LIKE '%1001%' ESCAPE '!'
OR  `exam_period` LIKE '%1001%' ESCAPE '!'
OR  `exam_from_date` LIKE '%1001%' ESCAPE '!'
OR  `exam_to_date` LIKE '%1001%' ESCAPE '!'
OR  `discount` LIKE '%1001%' ESCAPE '!'
 )
ERROR - 2024-11-13 09:21:33 --> Query error: Column 'institute_code' in where clause is ambiguous - Invalid query: SELECT `a`.`id` `exam_active_id`, `a`.`exam_period`, `a`.`exam_code`, `exam_from_date`, `exam_from_time`, `exam_to_date`, `exam_to_time`, `b`.`id` `exam_id`, `b`.`description`, `a`.`tds`, `a`.`discount`, `c`.`institute_code`, `c`.`institute_name`
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
LEFT JOIN `bulk_accerdited_master` `c` ON `a`.`institute_code` = `c`.`institute_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%1001%' ESCAPE '!'
OR  `institute_name` LIKE '%1001%' ESCAPE '!'
OR  `description` LIKE '%1001%' ESCAPE '!'
OR  `exam_code` LIKE '%1001%' ESCAPE '!'
OR  `exam_period` LIKE '%1001%' ESCAPE '!'
OR  `exam_from_date` LIKE '%1001%' ESCAPE '!'
OR  `exam_to_date` LIKE '%1001%' ESCAPE '!'
OR  `discount` LIKE '%1001%' ESCAPE '!'
 )
 LIMIT 10
ERROR - 2024-11-13 09:21:34 --> Query error: Unknown column 'institute_name' in 'where clause' - Invalid query: SELECT count(*) as tot
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%10016%' ESCAPE '!'
OR  `institute_name` LIKE '%10016%' ESCAPE '!'
OR  `description` LIKE '%10016%' ESCAPE '!'
OR  `exam_code` LIKE '%10016%' ESCAPE '!'
OR  `exam_period` LIKE '%10016%' ESCAPE '!'
OR  `exam_from_date` LIKE '%10016%' ESCAPE '!'
OR  `exam_to_date` LIKE '%10016%' ESCAPE '!'
OR  `discount` LIKE '%10016%' ESCAPE '!'
 )
ERROR - 2024-11-13 09:21:34 --> Query error: Column 'institute_code' in where clause is ambiguous - Invalid query: SELECT `a`.`id` `exam_active_id`, `a`.`exam_period`, `a`.`exam_code`, `exam_from_date`, `exam_from_time`, `exam_to_date`, `exam_to_time`, `b`.`id` `exam_id`, `b`.`description`, `a`.`tds`, `a`.`discount`, `c`.`institute_code`, `c`.`institute_name`
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
LEFT JOIN `bulk_accerdited_master` `c` ON `a`.`institute_code` = `c`.`institute_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%10016%' ESCAPE '!'
OR  `institute_name` LIKE '%10016%' ESCAPE '!'
OR  `description` LIKE '%10016%' ESCAPE '!'
OR  `exam_code` LIKE '%10016%' ESCAPE '!'
OR  `exam_period` LIKE '%10016%' ESCAPE '!'
OR  `exam_from_date` LIKE '%10016%' ESCAPE '!'
OR  `exam_to_date` LIKE '%10016%' ESCAPE '!'
OR  `discount` LIKE '%10016%' ESCAPE '!'
 )
 LIMIT 10
ERROR - 2024-11-13 09:21:35 --> Query error: Unknown column 'institute_name' in 'where clause' - Invalid query: SELECT count(*) as tot
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%1001%' ESCAPE '!'
OR  `institute_name` LIKE '%1001%' ESCAPE '!'
OR  `description` LIKE '%1001%' ESCAPE '!'
OR  `exam_code` LIKE '%1001%' ESCAPE '!'
OR  `exam_period` LIKE '%1001%' ESCAPE '!'
OR  `exam_from_date` LIKE '%1001%' ESCAPE '!'
OR  `exam_to_date` LIKE '%1001%' ESCAPE '!'
OR  `discount` LIKE '%1001%' ESCAPE '!'
 )
ERROR - 2024-11-13 09:21:35 --> Query error: Column 'institute_code' in where clause is ambiguous - Invalid query: SELECT `a`.`id` `exam_active_id`, `a`.`exam_period`, `a`.`exam_code`, `exam_from_date`, `exam_from_time`, `exam_to_date`, `exam_to_time`, `b`.`id` `exam_id`, `b`.`description`, `a`.`tds`, `a`.`discount`, `c`.`institute_code`, `c`.`institute_name`
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
LEFT JOIN `bulk_accerdited_master` `c` ON `a`.`institute_code` = `c`.`institute_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%1001%' ESCAPE '!'
OR  `institute_name` LIKE '%1001%' ESCAPE '!'
OR  `description` LIKE '%1001%' ESCAPE '!'
OR  `exam_code` LIKE '%1001%' ESCAPE '!'
OR  `exam_period` LIKE '%1001%' ESCAPE '!'
OR  `exam_from_date` LIKE '%1001%' ESCAPE '!'
OR  `exam_to_date` LIKE '%1001%' ESCAPE '!'
OR  `discount` LIKE '%1001%' ESCAPE '!'
 )
 LIMIT 10
ERROR - 2024-11-13 09:21:35 --> Query error: Unknown column 'institute_name' in 'where clause' - Invalid query: SELECT count(*) as tot
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%100%' ESCAPE '!'
OR  `institute_name` LIKE '%100%' ESCAPE '!'
OR  `description` LIKE '%100%' ESCAPE '!'
OR  `exam_code` LIKE '%100%' ESCAPE '!'
OR  `exam_period` LIKE '%100%' ESCAPE '!'
OR  `exam_from_date` LIKE '%100%' ESCAPE '!'
OR  `exam_to_date` LIKE '%100%' ESCAPE '!'
OR  `discount` LIKE '%100%' ESCAPE '!'
 )
ERROR - 2024-11-13 09:21:35 --> Query error: Column 'institute_code' in where clause is ambiguous - Invalid query: SELECT `a`.`id` `exam_active_id`, `a`.`exam_period`, `a`.`exam_code`, `exam_from_date`, `exam_from_time`, `exam_to_date`, `exam_to_time`, `b`.`id` `exam_id`, `b`.`description`, `a`.`tds`, `a`.`discount`, `c`.`institute_code`, `c`.`institute_name`
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
LEFT JOIN `bulk_accerdited_master` `c` ON `a`.`institute_code` = `c`.`institute_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%100%' ESCAPE '!'
OR  `institute_name` LIKE '%100%' ESCAPE '!'
OR  `description` LIKE '%100%' ESCAPE '!'
OR  `exam_code` LIKE '%100%' ESCAPE '!'
OR  `exam_period` LIKE '%100%' ESCAPE '!'
OR  `exam_from_date` LIKE '%100%' ESCAPE '!'
OR  `exam_to_date` LIKE '%100%' ESCAPE '!'
OR  `discount` LIKE '%100%' ESCAPE '!'
 )
 LIMIT 10
ERROR - 2024-11-13 09:21:36 --> Query error: Unknown column 'institute_name' in 'where clause' - Invalid query: SELECT count(*) as tot
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%1004%' ESCAPE '!'
OR  `institute_name` LIKE '%1004%' ESCAPE '!'
OR  `description` LIKE '%1004%' ESCAPE '!'
OR  `exam_code` LIKE '%1004%' ESCAPE '!'
OR  `exam_period` LIKE '%1004%' ESCAPE '!'
OR  `exam_from_date` LIKE '%1004%' ESCAPE '!'
OR  `exam_to_date` LIKE '%1004%' ESCAPE '!'
OR  `discount` LIKE '%1004%' ESCAPE '!'
 )
ERROR - 2024-11-13 09:21:36 --> Query error: Column 'institute_code' in where clause is ambiguous - Invalid query: SELECT `a`.`id` `exam_active_id`, `a`.`exam_period`, `a`.`exam_code`, `exam_from_date`, `exam_from_time`, `exam_to_date`, `exam_to_time`, `b`.`id` `exam_id`, `b`.`description`, `a`.`tds`, `a`.`discount`, `c`.`institute_code`, `c`.`institute_name`
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
LEFT JOIN `bulk_accerdited_master` `c` ON `a`.`institute_code` = `c`.`institute_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%1004%' ESCAPE '!'
OR  `institute_name` LIKE '%1004%' ESCAPE '!'
OR  `description` LIKE '%1004%' ESCAPE '!'
OR  `exam_code` LIKE '%1004%' ESCAPE '!'
OR  `exam_period` LIKE '%1004%' ESCAPE '!'
OR  `exam_from_date` LIKE '%1004%' ESCAPE '!'
OR  `exam_to_date` LIKE '%1004%' ESCAPE '!'
OR  `discount` LIKE '%1004%' ESCAPE '!'
 )
 LIMIT 10
ERROR - 2024-11-13 09:21:37 --> Query error: Unknown column 'institute_name' in 'where clause' - Invalid query: SELECT count(*) as tot
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%10043%' ESCAPE '!'
OR  `institute_name` LIKE '%10043%' ESCAPE '!'
OR  `description` LIKE '%10043%' ESCAPE '!'
OR  `exam_code` LIKE '%10043%' ESCAPE '!'
OR  `exam_period` LIKE '%10043%' ESCAPE '!'
OR  `exam_from_date` LIKE '%10043%' ESCAPE '!'
OR  `exam_to_date` LIKE '%10043%' ESCAPE '!'
OR  `discount` LIKE '%10043%' ESCAPE '!'
 )
ERROR - 2024-11-13 09:21:37 --> Query error: Column 'institute_code' in where clause is ambiguous - Invalid query: SELECT `a`.`id` `exam_active_id`, `a`.`exam_period`, `a`.`exam_code`, `exam_from_date`, `exam_from_time`, `exam_to_date`, `exam_to_time`, `b`.`id` `exam_id`, `b`.`description`, `a`.`tds`, `a`.`discount`, `c`.`institute_code`, `c`.`institute_name`
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
LEFT JOIN `bulk_accerdited_master` `c` ON `a`.`institute_code` = `c`.`institute_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%10043%' ESCAPE '!'
OR  `institute_name` LIKE '%10043%' ESCAPE '!'
OR  `description` LIKE '%10043%' ESCAPE '!'
OR  `exam_code` LIKE '%10043%' ESCAPE '!'
OR  `exam_period` LIKE '%10043%' ESCAPE '!'
OR  `exam_from_date` LIKE '%10043%' ESCAPE '!'
OR  `exam_to_date` LIKE '%10043%' ESCAPE '!'
OR  `discount` LIKE '%10043%' ESCAPE '!'
 )
 LIMIT 10
ERROR - 2024-11-13 09:21:38 --> Query error: Unknown column 'institute_name' in 'where clause' - Invalid query: SELECT count(*) as tot
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%1004%' ESCAPE '!'
OR  `institute_name` LIKE '%1004%' ESCAPE '!'
OR  `description` LIKE '%1004%' ESCAPE '!'
OR  `exam_code` LIKE '%1004%' ESCAPE '!'
OR  `exam_period` LIKE '%1004%' ESCAPE '!'
OR  `exam_from_date` LIKE '%1004%' ESCAPE '!'
OR  `exam_to_date` LIKE '%1004%' ESCAPE '!'
OR  `discount` LIKE '%1004%' ESCAPE '!'
 )
ERROR - 2024-11-13 09:21:38 --> Query error: Column 'institute_code' in where clause is ambiguous - Invalid query: SELECT `a`.`id` `exam_active_id`, `a`.`exam_period`, `a`.`exam_code`, `exam_from_date`, `exam_from_time`, `exam_to_date`, `exam_to_time`, `b`.`id` `exam_id`, `b`.`description`, `a`.`tds`, `a`.`discount`, `c`.`institute_code`, `c`.`institute_name`
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
LEFT JOIN `bulk_accerdited_master` `c` ON `a`.`institute_code` = `c`.`institute_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%1004%' ESCAPE '!'
OR  `institute_name` LIKE '%1004%' ESCAPE '!'
OR  `description` LIKE '%1004%' ESCAPE '!'
OR  `exam_code` LIKE '%1004%' ESCAPE '!'
OR  `exam_period` LIKE '%1004%' ESCAPE '!'
OR  `exam_from_date` LIKE '%1004%' ESCAPE '!'
OR  `exam_to_date` LIKE '%1004%' ESCAPE '!'
OR  `discount` LIKE '%1004%' ESCAPE '!'
 )
 LIMIT 10
ERROR - 2024-11-13 09:21:38 --> Query error: Unknown column 'institute_name' in 'where clause' - Invalid query: SELECT count(*) as tot
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%100%' ESCAPE '!'
OR  `institute_name` LIKE '%100%' ESCAPE '!'
OR  `description` LIKE '%100%' ESCAPE '!'
OR  `exam_code` LIKE '%100%' ESCAPE '!'
OR  `exam_period` LIKE '%100%' ESCAPE '!'
OR  `exam_from_date` LIKE '%100%' ESCAPE '!'
OR  `exam_to_date` LIKE '%100%' ESCAPE '!'
OR  `discount` LIKE '%100%' ESCAPE '!'
 )
ERROR - 2024-11-13 09:21:38 --> Query error: Column 'institute_code' in where clause is ambiguous - Invalid query: SELECT `a`.`id` `exam_active_id`, `a`.`exam_period`, `a`.`exam_code`, `exam_from_date`, `exam_from_time`, `exam_to_date`, `exam_to_time`, `b`.`id` `exam_id`, `b`.`description`, `a`.`tds`, `a`.`discount`, `c`.`institute_code`, `c`.`institute_name`
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
LEFT JOIN `bulk_accerdited_master` `c` ON `a`.`institute_code` = `c`.`institute_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%100%' ESCAPE '!'
OR  `institute_name` LIKE '%100%' ESCAPE '!'
OR  `description` LIKE '%100%' ESCAPE '!'
OR  `exam_code` LIKE '%100%' ESCAPE '!'
OR  `exam_period` LIKE '%100%' ESCAPE '!'
OR  `exam_from_date` LIKE '%100%' ESCAPE '!'
OR  `exam_to_date` LIKE '%100%' ESCAPE '!'
OR  `discount` LIKE '%100%' ESCAPE '!'
 )
 LIMIT 10
ERROR - 2024-11-13 09:22:11 --> Query error: Unknown column 'institute_name' in 'where clause' - Invalid query: SELECT count(*) as tot
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%1001%' ESCAPE '!'
OR  `institute_name` LIKE '%1001%' ESCAPE '!'
OR  `description` LIKE '%1001%' ESCAPE '!'
OR  `exam_code` LIKE '%1001%' ESCAPE '!'
OR  `exam_period` LIKE '%1001%' ESCAPE '!'
OR  `exam_from_date` LIKE '%1001%' ESCAPE '!'
OR  `exam_to_date` LIKE '%1001%' ESCAPE '!'
OR  `discount` LIKE '%1001%' ESCAPE '!'
 )
ERROR - 2024-11-13 09:22:11 --> Query error: Column 'institute_code' in where clause is ambiguous - Invalid query: SELECT `a`.`id` `exam_active_id`, `a`.`exam_period`, `a`.`exam_code`, `exam_from_date`, `exam_from_time`, `exam_to_date`, `exam_to_time`, `b`.`id` `exam_id`, `b`.`description`, `a`.`tds`, `a`.`discount`, `c`.`institute_code`, `c`.`institute_name`
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
LEFT JOIN `bulk_accerdited_master` `c` ON `a`.`institute_code` = `c`.`institute_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%1001%' ESCAPE '!'
OR  `institute_name` LIKE '%1001%' ESCAPE '!'
OR  `description` LIKE '%1001%' ESCAPE '!'
OR  `exam_code` LIKE '%1001%' ESCAPE '!'
OR  `exam_period` LIKE '%1001%' ESCAPE '!'
OR  `exam_from_date` LIKE '%1001%' ESCAPE '!'
OR  `exam_to_date` LIKE '%1001%' ESCAPE '!'
OR  `discount` LIKE '%1001%' ESCAPE '!'
 )
 LIMIT 10
ERROR - 2024-11-13 09:22:11 --> Query error: Unknown column 'institute_name' in 'where clause' - Invalid query: SELECT count(*) as tot
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%10012%' ESCAPE '!'
OR  `institute_name` LIKE '%10012%' ESCAPE '!'
OR  `description` LIKE '%10012%' ESCAPE '!'
OR  `exam_code` LIKE '%10012%' ESCAPE '!'
OR  `exam_period` LIKE '%10012%' ESCAPE '!'
OR  `exam_from_date` LIKE '%10012%' ESCAPE '!'
OR  `exam_to_date` LIKE '%10012%' ESCAPE '!'
OR  `discount` LIKE '%10012%' ESCAPE '!'
 )
ERROR - 2024-11-13 09:22:11 --> Query error: Column 'institute_code' in where clause is ambiguous - Invalid query: SELECT `a`.`id` `exam_active_id`, `a`.`exam_period`, `a`.`exam_code`, `exam_from_date`, `exam_from_time`, `exam_to_date`, `exam_to_time`, `b`.`id` `exam_id`, `b`.`description`, `a`.`tds`, `a`.`discount`, `c`.`institute_code`, `c`.`institute_name`
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
LEFT JOIN `bulk_accerdited_master` `c` ON `a`.`institute_code` = `c`.`institute_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%10012%' ESCAPE '!'
OR  `institute_name` LIKE '%10012%' ESCAPE '!'
OR  `description` LIKE '%10012%' ESCAPE '!'
OR  `exam_code` LIKE '%10012%' ESCAPE '!'
OR  `exam_period` LIKE '%10012%' ESCAPE '!'
OR  `exam_from_date` LIKE '%10012%' ESCAPE '!'
OR  `exam_to_date` LIKE '%10012%' ESCAPE '!'
OR  `discount` LIKE '%10012%' ESCAPE '!'
 )
 LIMIT 10
ERROR - 2024-11-13 09:22:29 --> Query error: Unknown column 'institute_name' in 'where clause' - Invalid query: SELECT count(*) as tot
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%1001%' ESCAPE '!'
OR  `institute_name` LIKE '%1001%' ESCAPE '!'
OR  `description` LIKE '%1001%' ESCAPE '!'
OR  `exam_code` LIKE '%1001%' ESCAPE '!'
OR  `exam_period` LIKE '%1001%' ESCAPE '!'
OR  `exam_from_date` LIKE '%1001%' ESCAPE '!'
OR  `exam_to_date` LIKE '%1001%' ESCAPE '!'
OR  `discount` LIKE '%1001%' ESCAPE '!'
 )
ERROR - 2024-11-13 09:22:29 --> Query error: Column 'institute_code' in where clause is ambiguous - Invalid query: SELECT `a`.`id` `exam_active_id`, `a`.`exam_period`, `a`.`exam_code`, `exam_from_date`, `exam_from_time`, `exam_to_date`, `exam_to_time`, `b`.`id` `exam_id`, `b`.`description`, `a`.`tds`, `a`.`discount`, `c`.`institute_code`, `c`.`institute_name`
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
LEFT JOIN `bulk_accerdited_master` `c` ON `a`.`institute_code` = `c`.`institute_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%1001%' ESCAPE '!'
OR  `institute_name` LIKE '%1001%' ESCAPE '!'
OR  `description` LIKE '%1001%' ESCAPE '!'
OR  `exam_code` LIKE '%1001%' ESCAPE '!'
OR  `exam_period` LIKE '%1001%' ESCAPE '!'
OR  `exam_from_date` LIKE '%1001%' ESCAPE '!'
OR  `exam_to_date` LIKE '%1001%' ESCAPE '!'
OR  `discount` LIKE '%1001%' ESCAPE '!'
 )
 LIMIT 10
ERROR - 2024-11-13 09:22:29 --> Query error: Unknown column 'institute_name' in 'where clause' - Invalid query: SELECT count(*) as tot
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%100%' ESCAPE '!'
OR  `institute_name` LIKE '%100%' ESCAPE '!'
OR  `description` LIKE '%100%' ESCAPE '!'
OR  `exam_code` LIKE '%100%' ESCAPE '!'
OR  `exam_period` LIKE '%100%' ESCAPE '!'
OR  `exam_from_date` LIKE '%100%' ESCAPE '!'
OR  `exam_to_date` LIKE '%100%' ESCAPE '!'
OR  `discount` LIKE '%100%' ESCAPE '!'
 )
ERROR - 2024-11-13 09:22:29 --> Query error: Column 'institute_code' in where clause is ambiguous - Invalid query: SELECT `a`.`id` `exam_active_id`, `a`.`exam_period`, `a`.`exam_code`, `exam_from_date`, `exam_from_time`, `exam_to_date`, `exam_to_time`, `b`.`id` `exam_id`, `b`.`description`, `a`.`tds`, `a`.`discount`, `c`.`institute_code`, `c`.`institute_name`
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
LEFT JOIN `bulk_accerdited_master` `c` ON `a`.`institute_code` = `c`.`institute_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%100%' ESCAPE '!'
OR  `institute_name` LIKE '%100%' ESCAPE '!'
OR  `description` LIKE '%100%' ESCAPE '!'
OR  `exam_code` LIKE '%100%' ESCAPE '!'
OR  `exam_period` LIKE '%100%' ESCAPE '!'
OR  `exam_from_date` LIKE '%100%' ESCAPE '!'
OR  `exam_to_date` LIKE '%100%' ESCAPE '!'
OR  `discount` LIKE '%100%' ESCAPE '!'
 )
 LIMIT 10
ERROR - 2024-11-13 09:22:30 --> Query error: Unknown column 'institute_name' in 'where clause' - Invalid query: SELECT count(*) as tot
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%1005%' ESCAPE '!'
OR  `institute_name` LIKE '%1005%' ESCAPE '!'
OR  `description` LIKE '%1005%' ESCAPE '!'
OR  `exam_code` LIKE '%1005%' ESCAPE '!'
OR  `exam_period` LIKE '%1005%' ESCAPE '!'
OR  `exam_from_date` LIKE '%1005%' ESCAPE '!'
OR  `exam_to_date` LIKE '%1005%' ESCAPE '!'
OR  `discount` LIKE '%1005%' ESCAPE '!'
 )
ERROR - 2024-11-13 09:22:30 --> Query error: Column 'institute_code' in where clause is ambiguous - Invalid query: SELECT `a`.`id` `exam_active_id`, `a`.`exam_period`, `a`.`exam_code`, `exam_from_date`, `exam_from_time`, `exam_to_date`, `exam_to_time`, `b`.`id` `exam_id`, `b`.`description`, `a`.`tds`, `a`.`discount`, `c`.`institute_code`, `c`.`institute_name`
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
LEFT JOIN `bulk_accerdited_master` `c` ON `a`.`institute_code` = `c`.`institute_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%1005%' ESCAPE '!'
OR  `institute_name` LIKE '%1005%' ESCAPE '!'
OR  `description` LIKE '%1005%' ESCAPE '!'
OR  `exam_code` LIKE '%1005%' ESCAPE '!'
OR  `exam_period` LIKE '%1005%' ESCAPE '!'
OR  `exam_from_date` LIKE '%1005%' ESCAPE '!'
OR  `exam_to_date` LIKE '%1005%' ESCAPE '!'
OR  `discount` LIKE '%1005%' ESCAPE '!'
 )
 LIMIT 10
ERROR - 2024-11-13 09:22:30 --> Query error: Unknown column 'institute_name' in 'where clause' - Invalid query: SELECT count(*) as tot
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%10055%' ESCAPE '!'
OR  `institute_name` LIKE '%10055%' ESCAPE '!'
OR  `description` LIKE '%10055%' ESCAPE '!'
OR  `exam_code` LIKE '%10055%' ESCAPE '!'
OR  `exam_period` LIKE '%10055%' ESCAPE '!'
OR  `exam_from_date` LIKE '%10055%' ESCAPE '!'
OR  `exam_to_date` LIKE '%10055%' ESCAPE '!'
OR  `discount` LIKE '%10055%' ESCAPE '!'
 )
ERROR - 2024-11-13 09:22:30 --> Query error: Column 'institute_code' in where clause is ambiguous - Invalid query: SELECT `a`.`id` `exam_active_id`, `a`.`exam_period`, `a`.`exam_code`, `exam_from_date`, `exam_from_time`, `exam_to_date`, `exam_to_time`, `b`.`id` `exam_id`, `b`.`description`, `a`.`tds`, `a`.`discount`, `c`.`institute_code`, `c`.`institute_name`
FROM `bulk_exam_activation_master` `a`
LEFT JOIN `exam_master` `b` ON `b`.`exam_code`=`a`.`exam_code`
LEFT JOIN `bulk_accerdited_master` `c` ON `a`.`institute_code` = `c`.`institute_code`
WHERE `exam_activation_delete` =0
AND `exam_delete` =0
AND   (
`institute_code` LIKE '%10055%' ESCAPE '!'
OR  `institute_name` LIKE '%10055%' ESCAPE '!'
OR  `description` LIKE '%10055%' ESCAPE '!'
OR  `exam_code` LIKE '%10055%' ESCAPE '!'
OR  `exam_period` LIKE '%10055%' ESCAPE '!'
OR  `exam_from_date` LIKE '%10055%' ESCAPE '!'
OR  `exam_to_date` LIKE '%10055%' ESCAPE '!'
OR  `discount` LIKE '%10055%' ESCAPE '!'
 )
 LIMIT 10
ERROR - 2024-11-13 10:13:47 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2024-11-13 10:25:54 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2024-11-13 10:26:51 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 10:26:51 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 10:26:51 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 10:26:51 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 10:58:25 --> Query error: Table 'supp0rttest_iibf_staging.profilelogs' doesn't exist - Invalid query: INSERT INTO `profilelogs` (`title`, `description`, `type`, `regid`, `regnumber`, `editedby`, `editedbyid`, `date`, `ip`) VALUES ('Profile updated successfully', 'BANK BC ID CARD ', 'image', '8432209', '800000281', 'User', '8432209', '2024-11-13 10:58:25', '10.11.38.105')
ERROR - 2024-11-13 10:59:48 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2024-11-13 12:23:24 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2024-11-13 12:42:08 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 12:42:08 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 12:42:08 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 12:42:08 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 14:22:04 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 14:22:04 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 14:22:04 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 14:22:05 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 14:30:17 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 14:30:17 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 14:30:18 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 14:30:18 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 14:33:25 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2024-11-13 14:33:36 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2024-11-13 14:47:37 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 14:47:37 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 14:47:38 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 14:47:38 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 14:52:19 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 14:52:19 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 14:52:19 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 14:52:19 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 14:54:19 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 14:54:20 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 14:54:20 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 14:54:21 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 14:54:54 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 14:54:54 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 14:54:54 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 14:54:55 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 14:58:50 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 14:58:51 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 14:58:51 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 14:58:51 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:00:26 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:00:27 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:00:29 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:00:29 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:02:21 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:02:21 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:02:22 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:02:22 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:04:04 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:04:04 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:04:04 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:04:04 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:05:05 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:05:05 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:05:06 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:05:06 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:05:28 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:05:28 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:05:28 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:05:29 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:06:45 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:06:45 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:06:46 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:06:46 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:07:18 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:07:19 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:07:19 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:07:19 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:07:24 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:07:26 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:07:27 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:07:27 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:10:27 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:10:29 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:10:29 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:10:29 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:11:02 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:11:02 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:11:02 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:11:03 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:12:08 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:12:08 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:12:08 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:12:08 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:12:50 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2024-11-13 15:13:34 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:13:35 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:13:36 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:13:36 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:18:38 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:18:38 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:18:38 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:18:39 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:26:24 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:26:24 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:26:24 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:26:24 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:31:32 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:31:32 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:31:32 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:31:33 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:33:06 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:33:06 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:33:06 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:33:06 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:37:00 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:37:00 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:37:00 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:37:01 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:40:55 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:40:56 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:40:56 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:40:56 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:45:44 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:45:45 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:45:45 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:45:45 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:56:21 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:56:21 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:56:21 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:56:21 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:59:16 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:59:16 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:59:16 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:59:16 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:59:46 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:59:46 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:59:46 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 15:59:46 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:00:38 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:00:38 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:00:39 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:00:39 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:03:05 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:03:05 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:03:05 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:03:05 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:06:43 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:06:43 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:06:43 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:06:43 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:07:38 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:07:38 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:07:38 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:07:38 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:10:16 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:10:16 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:10:16 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:10:16 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:30:12 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:30:12 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:30:12 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:30:12 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:32:39 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2024-11-13 16:33:48 --> 404 Page Not Found: Register/favicon.ico
ERROR - 2024-11-13 16:34:08 --> Query error: Table 'supp0rttest_iibf_staging.config_O_memreg' doesn't exist - Invalid query: INSERT INTO `config_O_memreg` (`regid`) VALUES ('8432751')
ERROR - 2024-11-13 16:34:08 --> Severity: Warning --> mcrypt_generic_init(): Key size is 0 /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 105
ERROR - 2024-11-13 16:34:08 --> Severity: Warning --> mcrypt_generic_init(): Key length incorrect /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 105
ERROR - 2024-11-13 16:34:08 --> Severity: Warning --> mdecrypt_generic(): 15 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 106
ERROR - 2024-11-13 16:34:08 --> Severity: Warning --> mcrypt_generic_deinit(): 15 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 109
ERROR - 2024-11-13 16:34:08 --> Severity: Warning --> mcrypt_module_close(): 15 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 110
ERROR - 2024-11-13 16:34:08 --> Severity: Warning --> imagepng(uploads/reginvoice/user/0_M_24-25_000009.jpg): failed to open stream: No such file or directory /home/supp0rttest/public_html/staging/application/helpers/invoice_helper.php 1605
ERROR - 2024-11-13 16:34:08 --> Severity: Warning --> imagepng(uploads/reginvoice/user/0_M_24-25_000009.jpg): failed to open stream: No such file or directory /home/supp0rttest/public_html/staging/application/helpers/invoice_helper.php 1613
ERROR - 2024-11-13 16:34:08 --> Severity: Warning --> imagepng(uploads/reginvoice/supplier/0_M_24-25_000009.jpg): failed to open stream: No such file or directory /home/supp0rttest/public_html/staging/application/helpers/invoice_helper.php 1742
ERROR - 2024-11-13 16:34:08 --> Severity: Warning --> imagepng(uploads/reginvoice/supplier/0_M_24-25_000009.jpg): failed to open stream: No such file or directory /home/supp0rttest/public_html/staging/application/helpers/invoice_helper.php 1750
ERROR - 2024-11-13 16:48:19 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:48:19 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:48:19 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:48:19 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:48:47 --> 404 Page Not Found: Register/index
ERROR - 2024-11-13 16:55:26 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:55:26 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:55:26 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:55:26 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:56:39 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:56:39 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:56:39 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 16:56:39 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-13 17:10:38 --> 404 Page Not Found: Register/favicon.ico
ERROR - 2024-11-13 17:10:55 --> Severity: Warning --> mcrypt_generic_init(): Key size is 0 /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 105
ERROR - 2024-11-13 17:10:55 --> Severity: Warning --> mcrypt_generic_init(): Key length incorrect /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 105
ERROR - 2024-11-13 17:10:55 --> Severity: Warning --> mdecrypt_generic(): 14 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 106
ERROR - 2024-11-13 17:10:55 --> Severity: Warning --> mcrypt_generic_deinit(): 14 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 109
ERROR - 2024-11-13 17:10:55 --> Severity: Warning --> mcrypt_module_close(): 14 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 110
ERROR - 2024-11-13 17:10:55 --> Severity: Warning --> imagepng(uploads/reginvoice/user/511000000_M_24-25_000010.jpg): failed to open stream: No such file or directory /home/supp0rttest/public_html/staging/application/helpers/invoice_helper.php 1605
ERROR - 2024-11-13 17:10:55 --> Severity: Warning --> imagepng(uploads/reginvoice/user/511000000_M_24-25_000010.jpg): failed to open stream: No such file or directory /home/supp0rttest/public_html/staging/application/helpers/invoice_helper.php 1613
ERROR - 2024-11-13 17:10:55 --> Severity: Warning --> imagepng(uploads/reginvoice/supplier/511000000_M_24-25_000010.jpg): failed to open stream: No such file or directory /home/supp0rttest/public_html/staging/application/helpers/invoice_helper.php 1742
ERROR - 2024-11-13 17:10:55 --> Severity: Warning --> imagepng(uploads/reginvoice/supplier/511000000_M_24-25_000010.jpg): failed to open stream: No such file or directory /home/supp0rttest/public_html/staging/application/helpers/invoice_helper.php 1750
ERROR - 2024-11-13 17:15:36 --> 404 Page Not Found: Uploads/examinvoice
ERROR - 2024-11-13 17:15:49 --> 404 Page Not Found: Uploads/reginvoice
ERROR - 2024-11-13 17:15:54 --> 404 Page Not Found: Uploads/reginvoice
ERROR - 2024-11-13 17:16:02 --> 404 Page Not Found: Uploads/reginvoice
ERROR - 2024-11-13 17:19:51 --> Severity: Warning --> mcrypt_generic_init(): Key size is 0 /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 105
ERROR - 2024-11-13 17:19:51 --> Severity: Warning --> mcrypt_generic_init(): Key length incorrect /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 105
ERROR - 2024-11-13 17:19:51 --> Severity: Warning --> mdecrypt_generic(): 14 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 106
ERROR - 2024-11-13 17:19:51 --> Severity: Warning --> mcrypt_generic_deinit(): 14 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 109
ERROR - 2024-11-13 17:19:51 --> Severity: Warning --> mcrypt_module_close(): 14 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 110
ERROR - 2024-11-13 17:19:51 --> Severity: Warning --> imagepng(uploads/reginvoice/user/511000001_M_24-25_000011.jpg): failed to open stream: No such file or directory /home/supp0rttest/public_html/staging/application/helpers/invoice_helper.php 1605
ERROR - 2024-11-13 17:19:51 --> Severity: Warning --> imagepng(uploads/reginvoice/user/511000001_M_24-25_000011.jpg): failed to open stream: No such file or directory /home/supp0rttest/public_html/staging/application/helpers/invoice_helper.php 1613
ERROR - 2024-11-13 17:19:51 --> Severity: Warning --> imagepng(uploads/reginvoice/supplier/511000001_M_24-25_000011.jpg): failed to open stream: No such file or directory /home/supp0rttest/public_html/staging/application/helpers/invoice_helper.php 1742
ERROR - 2024-11-13 17:19:51 --> Severity: Warning --> imagepng(uploads/reginvoice/supplier/511000001_M_24-25_000011.jpg): failed to open stream: No such file or directory /home/supp0rttest/public_html/staging/application/helpers/invoice_helper.php 1750
ERROR - 2024-11-13 17:51:09 --> 404 Page Not Found: Register/index
ERROR - 2024-11-13 18:08:54 --> 404 Page Not Found: Register/favicon.ico
ERROR - 2024-11-13 18:09:18 --> Severity: Warning --> mcrypt_generic_init(): Key size is 0 /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 105
ERROR - 2024-11-13 18:09:18 --> Severity: Warning --> mcrypt_generic_init(): Key length incorrect /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 105
ERROR - 2024-11-13 18:09:18 --> Severity: Warning --> mdecrypt_generic(): 14 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 106
ERROR - 2024-11-13 18:09:18 --> Severity: Warning --> mcrypt_generic_deinit(): 14 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 109
ERROR - 2024-11-13 18:09:18 --> Severity: Warning --> mcrypt_module_close(): 14 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 110
ERROR - 2024-11-13 18:15:43 --> Severity: Warning --> mcrypt_generic_init(): Key size is 0 /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 105
ERROR - 2024-11-13 18:15:43 --> Severity: Warning --> mcrypt_generic_init(): Key length incorrect /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 105
ERROR - 2024-11-13 18:15:43 --> Severity: Warning --> mdecrypt_generic(): 14 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 106
ERROR - 2024-11-13 18:15:43 --> Severity: Warning --> mcrypt_generic_deinit(): 14 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 109
ERROR - 2024-11-13 18:15:43 --> Severity: Warning --> mcrypt_module_close(): 14 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 110
ERROR - 2024-11-13 18:15:46 --> 404 Page Not Found: Uploads/examinvoice
ERROR - 2024-11-13 18:18:06 --> 404 Page Not Found: Uploads/reginvoice
ERROR - 2024-11-13 18:18:09 --> 404 Page Not Found: Uploads/reginvoice
ERROR - 2024-11-13 18:18:12 --> 404 Page Not Found: Uploads/reginvoice
ERROR - 2024-11-13 18:24:42 --> 404 Page Not Found: Register/favicon.ico
ERROR - 2024-11-13 18:24:53 --> Severity: Warning --> mcrypt_generic_init(): Key size is 0 /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 105
ERROR - 2024-11-13 18:24:53 --> Severity: Warning --> mcrypt_generic_init(): Key length incorrect /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 105
ERROR - 2024-11-13 18:24:53 --> Severity: Warning --> mdecrypt_generic(): 14 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 106
ERROR - 2024-11-13 18:24:53 --> Severity: Warning --> mcrypt_generic_deinit(): 14 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 109
ERROR - 2024-11-13 18:24:53 --> Severity: Warning --> mcrypt_module_close(): 14 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 110
ERROR - 2024-11-13 18:30:56 --> Query error: Table 'supp0rttest_iibf_staging.publication' doesn't exist - Invalid query: INSERT INTO `publication` (`member_no`, `created_on`) VALUES ('511000004', '24-11-13 18:30:56')
ERROR - 2024-11-13 18:35:26 --> 404 Page Not Found: Register/favicon.ico
ERROR - 2024-11-13 18:35:37 --> Severity: Warning --> mcrypt_generic_init(): Key size is 0 /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 105
ERROR - 2024-11-13 18:35:37 --> Severity: Warning --> mcrypt_generic_init(): Key length incorrect /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 105
ERROR - 2024-11-13 18:35:37 --> Severity: Warning --> mdecrypt_generic(): 14 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 106
ERROR - 2024-11-13 18:35:37 --> Severity: Warning --> mcrypt_generic_deinit(): 14 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 109
ERROR - 2024-11-13 18:35:37 --> Severity: Warning --> mcrypt_module_close(): 14 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 110
ERROR - 2024-11-13 18:40:32 --> Severity: Compile Error --> Cannot use isset() on the result of a function call (you can use "null !== func()" instead) /home/supp0rttest/public_html/staging/application/controllers/Register.php 1607
ERROR - 2024-11-13 18:40:36 --> Severity: Compile Error --> Cannot use isset() on the result of a function call (you can use "null !== func()" instead) /home/supp0rttest/public_html/staging/application/controllers/Register.php 1607
ERROR - 2024-11-13 18:40:48 --> Severity: Compile Error --> Cannot use isset() on the result of a function call (you can use "null !== func()" instead) /home/supp0rttest/public_html/staging/application/controllers/Register.php 1607
ERROR - 2024-11-13 18:40:55 --> Severity: Compile Error --> Cannot use isset() on the result of a function call (you can use "null !== func()" instead) /home/supp0rttest/public_html/staging/application/controllers/Register.php 1607
ERROR - 2024-11-13 18:40:57 --> Severity: Compile Error --> Cannot use isset() on the result of a function call (you can use "null !== func()" instead) /home/supp0rttest/public_html/staging/application/controllers/Register.php 1607
ERROR - 2024-11-13 18:41:34 --> Severity: Compile Error --> Cannot use isset() on the result of a function call (you can use "null !== func()" instead) /home/supp0rttest/public_html/staging/application/controllers/Register.php 1607
ERROR - 2024-11-13 18:41:36 --> Severity: Compile Error --> Cannot use isset() on the result of a function call (you can use "null !== func()" instead) /home/supp0rttest/public_html/staging/application/controllers/Register.php 1607
ERROR - 2024-11-13 18:42:21 --> 404 Page Not Found: Register/index
ERROR - 2024-11-13 18:43:47 --> Query error: Table 'supp0rttest_iibf_staging.publication' doesn't exist - Invalid query: INSERT INTO `publication` (`member_no`, `created_on`) VALUES ('511000005', '24-11-13 18:43:47')
ERROR - 2024-11-13 18:44:32 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2024-11-13 18:51:46 --> Query error: Table 'supp0rttest_iibf_staging.publication' doesn't exist - Invalid query: INSERT INTO `publication` (`member_no`, `created_on`) VALUES ('511000005', '24-11-13 18:51:46')
ERROR - 2024-11-13 18:58:46 --> Severity: Warning --> mcrypt_generic_init(): Key size is 0 /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 105
ERROR - 2024-11-13 18:58:46 --> Severity: Warning --> mcrypt_generic_init(): Key length incorrect /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 105
ERROR - 2024-11-13 18:58:46 --> Severity: Warning --> mdecrypt_generic(): 14 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 106
ERROR - 2024-11-13 18:58:46 --> Severity: Warning --> mcrypt_generic_deinit(): 14 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 109
ERROR - 2024-11-13 18:58:46 --> Severity: Warning --> mcrypt_module_close(): 14 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 110
ERROR - 2024-11-13 19:00:57 --> 404 Page Not Found: Register/favicon.ico
ERROR - 2024-11-13 20:14:49 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2024-11-13 20:49:36 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2024-11-13 23:46:59 --> 404 Page Not Found: Uploads/exam_instruction
