<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2025-03-24 00:38:15 --> 404 Page Not Found: Git/config
ERROR - 2025-03-24 05:11:20 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2025-03-24 15:33:32 --> Severity: Warning --> mysqli::query(): MySQL server has gone away /home/supp0rttest/public_html/staging/system/database/drivers/mysqli/mysqli_driver.php 305
ERROR - 2025-03-24 15:33:32 --> Severity: Warning --> mysqli::query(): Error reading result set's header /home/supp0rttest/public_html/staging/system/database/drivers/mysqli/mysqli_driver.php 305
ERROR - 2025-03-24 15:33:32 --> Query error: MySQL server has gone away - Invalid query: SELECT a.id, ac.institute_code, ac.institute_name
    FROM agency_batch a 
    LEFT JOIN dra_accerdited_master ac ON a.agency_id = ac.dra_inst_registration_id 
    WHERE a.is_deleted = 0  GROUP BY a.id ORDER BY a.batch_from_date DESC
ERROR - 2025-03-24 15:33:32 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/admin/BatchSummary.php 44
ERROR - 2025-03-24 15:33:32 --> Severity: Warning --> mysqli::query(): MySQL server has gone away /home/supp0rttest/public_html/staging/system/database/drivers/mysqli/mysqli_driver.php 305
ERROR - 2025-03-24 15:33:32 --> Severity: Warning --> mysqli::query(): Error reading result set's header /home/supp0rttest/public_html/staging/system/database/drivers/mysqli/mysqli_driver.php 305
ERROR - 2025-03-24 15:33:32 --> Query error: MySQL server has gone away - Invalid query: SELECT a.id, ac.institute_code, ac.institute_name
    FROM agency_batch a 
    LEFT JOIN dra_accerdited_master ac ON a.agency_id = ac.dra_inst_registration_id 
    WHERE a.is_deleted = 0  GROUP BY a.id ORDER BY a.batch_from_date DESC
ERROR - 2025-03-24 15:33:32 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/admin/BatchSummary.php 44
