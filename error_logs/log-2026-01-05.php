<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2026-01-05 11:19:14 --> 404 Page Not Found: bulk/MOUlogin/index
ERROR - 2026-01-05 11:57:24 --> Severity: Warning --> imagepng(uploads/bulk_proforma_examinvoice/bulk_145.jpg): failed to open stream: No such file or directory /home/supp0rttest/public_html/staging/application/helpers/bulk_proforma_invoice_helper.php 226
ERROR - 2026-01-05 11:57:24 --> Severity: Warning --> imagepng(uploads/bulk_proforma_examinvoice/bulk_145.jpg): failed to open stream: No such file or directory /home/supp0rttest/public_html/staging/application/helpers/bulk_proforma_invoice_helper.php 230
ERROR - 2026-01-05 14:38:09 --> Query error: Unknown column 'status' in 'order clause' - Invalid query: SELECT b.batch_code, COUNT(bi.id) as reported,GROUP_CONCAT(bi.overall_compliance) as overall_compliance_list, b.hours, c1.city_name, b.holidays,b.timing_from, b.timing_to, b.batch_from_date, b.total_candidates, b.training_medium, b.created_on, b.batch_status, b.id, b.agency_id, b.center_id, b.inspector_id,  b.batch_type, b.batch_online_offline_flag,  b.batch_name,  b.batch_to_date, b.batch_type, b.batch_online_offline_flag, b.batch_active_period
            FROM agency_batch b 
            LEFT JOIN dra_batch_inspection bi ON bi.batch_id = b.id AND bi.inspector_id = 169
            LEFT JOIN agency_center c ON b.center_id = c.center_id
            LEFT JOIN city_master c1 ON c1.id = c.location_name
            WHERE is_deleted = 0 AND   b.inspector_id = 169  GROUP BY b.id  ORDER BY status   asc  LIMIT 0 ,10 
ERROR - 2026-01-05 14:38:09 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/InspectorHome.php 128
ERROR - 2026-01-05 14:38:56 --> Severity: Warning --> trim() expects parameter 1 to be string, array given /home/supp0rttest/public_html/staging/application/libraries/MY_Form_validation.php 265
ERROR - 2026-01-05 14:40:59 --> Severity: Warning --> trim() expects parameter 1 to be string, array given /home/supp0rttest/public_html/staging/application/libraries/MY_Form_validation.php 265
ERROR - 2026-01-05 14:53:42 --> Severity: Error --> Call to undefined function batch_approve_mail_V2() /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/Batch.php 1589
ERROR - 2026-01-05 16:01:51 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2026-01-05 16:01:51 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2026-01-05 16:01:51 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2026-01-05 16:01:51 --> 404 Page Not Found: Uploads/iibfdra
