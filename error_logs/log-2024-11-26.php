ERROR - 2024-11-26 18:54:01 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'id   desc  LIMIT 0 ,10' at line 6 - Invalid query: SELECT b.id, b.batch_code, COUNT(bi.id) as reported, b.hours, c1.city_name, b.holidays,b.timing_from, b.timing_to, b.batch_from_date, b.total_candidates, b.training_medium, b.created_on, b.batch_status, b.id, b.agency_id, b.center_id, b.inspector_id,  b.batch_type, b.batch_online_offline_flag,  b.batch_name,  b.batch_to_date, b.batch_type, b.batch_online_offline_flag, b.batch_active_period
            FROM agency_batch b 
            LEFT JOIN dra_batch_inspection bi ON bi.batch_id = b.id
            LEFT JOIN agency_center c ON b.center_id = c.center_id
            LEFT JOIN city_master c1 ON c1.id = c.location_name
            WHERE is_deleted = 0   GROUP BY bi.id id   desc  LIMIT 0 ,10 
ERROR - 2024-11-26 18:54:01 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/InspectorHome.php 125
ERROR - 2024-11-26 18:54:24 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-26 18:54:24 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-26 18:54:24 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-26 18:54:24 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-26 18:54:26 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-26 18:54:26 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-26 18:54:26 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-26 18:54:26 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-26 18:54:35 --> Query error: Table 'supp0rttest_iibf_staging.profilelogs' doesn't exist - Invalid query: INSERT INTO `profilelogs` (`title`, `description`, `type`, `regid`, `regnumber`, `editedby`, `editedbyid`, `date`, `ip`) VALUES ('Profile updated successfully', 'PHOTO || PROOF ', 'image', '537', '500007751', 'User', '537', '2024-11-26 18:54:35', '10.11.38.105')
ERROR - 2024-11-26 18:55:55 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-26 18:55:55 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-26 18:55:55 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-26 18:55:55 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-26 18:55:56 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-26 18:55:56 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-26 18:55:56 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-26 18:55:56 --> 404 Page Not Found: Assets/macroresearch
ERROR - 2024-11-26 19:01:17 --> Severity: Warning --> Invalid argument supplied for foreach() /home/supp0rttest/public_html/staging/application/views/kyc/kyc_all.php 59
ERROR - 2024-11-26 19:04:11 --> Severity: Warning --> Invalid argument supplied for foreach() /home/supp0rttest/public_html/staging/application/views/kyc/kyc_all.php 59
ERROR - 2024-11-26 19:14:42 --> 404 Page Not Found: Assets/js
ERROR - 2024-11-26 19:45:31 --> Query error: Unknown column 'Array' in 'where clause' - Invalid query: SELECT `candidate_id`, `exam_code`
FROM `iibfbcbf_batch_candidates`
WHERE (img_ediited_on = '' OR `img_ediited_on` IS NULL OR `img_ediited_on` = '0000-00-00 00:00:00') 
AND  `exam_code` IN (Array)
AND `regnumber` != ''
AND `hold_release_status` = '3'
AND `is_deleted` = '0'
AND DATE(kyc_eligible_date) >= '2024-11-26'
ORDER BY `candidate_id` ASC
ERROR - 2024-11-26 19:45:31 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 238
ERROR - 2024-11-26 19:47:05 --> Query error: Unknown column 'Array' in 'where clause' - Invalid query: SELECT `candidate_id`, `exam_code`
FROM `iibfbcbf_batch_candidates`
WHERE (img_ediited_on != '' AND `img_ediited_on` IS NOT NULL AND `img_ediited_on` != '0000-00-00 00:00:00') 
AND  `exam_code` IN (Array)
AND `regnumber` != ''
AND `hold_release_status` = '3'
AND `is_deleted` = '0'
AND DATE(kyc_eligible_date) >= '2024-11-26'
ORDER BY `candidate_id` ASC
ERROR - 2024-11-26 19:47:05 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 238
ERROR - 2024-11-26 20:02:33 --> Query error: Unknown column 'exam_code' in 'where clause' - Invalid query: SELECT *
FROM `dra_members`
WHERE `exam_code` IN (Array)
ERROR - 2024-11-26 20:02:33 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 238
ERROR - 2024-11-26 20:02:35 --> Query error: Unknown column 'exam_code' in 'where clause' - Invalid query: SELECT *
FROM `dra_members`
WHERE `exam_code` IN (Array)
ERROR - 2024-11-26 20:02:35 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 238
