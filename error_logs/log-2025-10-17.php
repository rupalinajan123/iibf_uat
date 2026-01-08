<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2025-10-17 03:12:07 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2025-10-17 09:21:25 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-10-17 09:21:38 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-10-17 09:21:41 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-10-17 09:52:32 --> Severity: Warning --> Missing argument 1 for Scribe_form::getDetails_Scribe() /home/supp0rttest/public_html/staging/application/controllers/Scribe_form.php 165
ERROR - 2025-10-17 09:52:32 --> Severity: Warning --> Missing argument 2 for Scribe_form::getDetails_Scribe() /home/supp0rttest/public_html/staging/application/controllers/Scribe_form.php 165
ERROR - 2025-10-17 09:52:32 --> Severity: Warning --> Missing argument 3 for Scribe_form::getDetails_Scribe() /home/supp0rttest/public_html/staging/application/controllers/Scribe_form.php 165
ERROR - 2025-10-17 09:54:17 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-17 10:02:44 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-17 10:09:46 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-10-17 10:09:50 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-10-17 10:50:42 --> 404 Page Not Found: Jaiib/index
ERROR - 2025-10-17 10:56:56 --> Query error: Table 'supp0rttest_iibf_staging.scribe_member_kyc' doesn't exist - Invalid query: SELECT scribe_member_kyc.regnumber,kyc_id
        FROM scribe_member_kyc
        JOIN scribe_registration ON scribe_registration.regnumber = scribe_member_kyc.regnumber
        WHERE 
        scribe_registration.scribe_edit_date < scribe_member_kyc.recommended_date
        AND scribe_registration.scribe_kyc_status = '0'
        AND scribe_member_kyc.kyc_id IN (
        SELECT MAX(kyc_id)
        FROM scribe_member_kyc
        GROUP BY regnumber
        ) 
ERROR - 2025-10-17 10:56:56 --> Severity: Error --> Call to a member function num_rows() on boolean /home/supp0rttest/public_html/staging/application/controllers/admin/kyc/Kyc.php 8817
ERROR - 2025-10-17 11:42:13 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-17 11:42:17 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-17 12:11:22 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-17 14:47:19 --> Severity: Notice --> Undefined index: cc /home/supp0rttest/public_html/staging/application/models/Emailsending.php 233
ERROR - 2025-10-17 14:47:19 --> Severity: Notice --> Undefined index: cc /home/supp0rttest/public_html/staging/application/models/Emailsending.php 235
ERROR - 2025-10-17 19:39:42 --> Severity: Warning --> Missing argument 1 for Scribe_form::getDetails_Scribe() /home/supp0rttest/public_html/staging/application/controllers/Scribe_form.php 165
ERROR - 2025-10-17 19:39:42 --> Severity: Warning --> Missing argument 2 for Scribe_form::getDetails_Scribe() /home/supp0rttest/public_html/staging/application/controllers/Scribe_form.php 165
ERROR - 2025-10-17 19:39:42 --> Severity: Warning --> Missing argument 3 for Scribe_form::getDetails_Scribe() /home/supp0rttest/public_html/staging/application/controllers/Scribe_form.php 165
