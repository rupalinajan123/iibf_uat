ERROR - 2025-10-03 12:27:19 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AS description LIKE '%U123NME0008RJG%' ESCAPE '!' OR pt.amount LIKE '%U123NME000' at line 1 - Invalid query: SELECT pt.id, pt.pg_flag, pt.transaction_no AS DispTransactionNo, pt.receipt_no, pt.amount, DATE_FORMAT(pt.date, "%Y-%m-%d %H:%i") AS PaymentDate, IF(pt.status=0, "Fail", IF(pt.status=1, "Success", IF(pt.status=2, "Pending", IF(pt.status=3, "Applied", IF(pt.status=4, "Cancelled", ""))))) AS DispPayStatus, pt.status, IF(pt.pay_type=1, "Membership Enrollment", "") AS TransactionType FROM ncvet_payment_transaction pt   WHERE pt.date != '' AND pt.transaction_no != ''  AND (pt.transaction_no LIKE '%U123NME0008RJG%' ESCAPE '!' OR pt.receipt_no LIKE '%U123NME0008RJG%' ESCAPE '!' OR pt.description AS description LIKE '%U123NME0008RJG%' ESCAPE '!' OR pt.amount LIKE '%U123NME0008RJG%' ESCAPE '!' OR pt.pay_count LIKE '%U123NME0008RJG%' ESCAPE '!' OR DATE_FORMAT(pt.date, "%Y-%m-%d %H:%i") LIKE '%U123NME0008RJG%' ESCAPE '!' OR IF(pt.status=0, "Fail", IF(pt.status=1, "Success", IF(pt.status=2, "Pending", IF(pt.status=3, "Applied", IF(pt.status=4, "Cancelled", ""))))) LIKE '%U123NME0008RJG%' ESCAPE '!' ) GROUP BY pt.id ORDER BY pt.id DESC LIMIT 0, 10 
ERROR - 2025-10-03 12:27:19 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/ncvet/admin/Transaction.php 117
ERROR - 2025-10-03 12:28:21 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-10-03 12:28:21 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-10-03 12:28:21 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-10-03 12:28:21 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-10-03 12:28:39 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 12:29:12 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AS description LIKE '%U123QC00008UAX%' ESCAPE '!' OR pt.amount LIKE '%U123QC0000' at line 1 - Invalid query: SELECT pt.id, pt.pg_flag, pt.transaction_no AS DispTransactionNo, pt.receipt_no, pt.amount, DATE_FORMAT(pt.date, "%Y-%m-%d %H:%i") AS PaymentDate, IF(pt.status=0, "Fail", IF(pt.status=1, "Success", IF(pt.status=2, "Pending", IF(pt.status=3, "Applied", IF(pt.status=4, "Cancelled", ""))))) AS DispPayStatus, pt.status, IF(pt.pay_type=1, "Membership Enrollment", "") AS TransactionType FROM ncvet_payment_transaction pt   WHERE pt.ref_id = 16 AND pt.date != '' AND pt.transaction_no != ''  AND (pt.transaction_no LIKE '%U123QC00008UAX%' ESCAPE '!' OR pt.receipt_no LIKE '%U123QC00008UAX%' ESCAPE '!' OR pt.description AS description LIKE '%U123QC00008UAX%' ESCAPE '!' OR pt.amount LIKE '%U123QC00008UAX%' ESCAPE '!' OR pt.pay_count LIKE '%U123QC00008UAX%' ESCAPE '!' OR DATE_FORMAT(pt.date, "%Y-%m-%d %H:%i") LIKE '%U123QC00008UAX%' ESCAPE '!' OR IF(pt.status=0, "Fail", IF(pt.status=1, "Success", IF(pt.status=2, "Pending", IF(pt.status=3, "Applied", IF(pt.status=4, "Cancelled", ""))))) LIKE '%U123QC00008UAX%' ESCAPE '!' ) GROUP BY pt.id ORDER BY pt.id DESC LIMIT 0, 10 
ERROR - 2025-10-03 12:29:12 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/ncvet/candidate/Transaction.php 100
ERROR - 2025-10-03 12:29:46 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AS description LIKE '%U123QC00008UAX%' ESCAPE '!' OR pt.amount LIKE '%U123QC0000' at line 1 - Invalid query: SELECT pt.id, pt.pg_flag, pt.transaction_no AS DispTransactionNo, pt.receipt_no, pt.amount, DATE_FORMAT(pt.date, "%Y-%m-%d %H:%i") AS PaymentDate, IF(pt.status=0, "Fail", IF(pt.status=1, "Success", IF(pt.status=2, "Pending", IF(pt.status=3, "Applied", IF(pt.status=4, "Cancelled", ""))))) AS DispPayStatus, pt.status, IF(pt.pay_type=1, "Membership Enrollment", "") AS TransactionType FROM ncvet_payment_transaction pt   WHERE pt.ref_id = 16 AND pt.date != '' AND pt.transaction_no != ''  AND (pt.transaction_no LIKE '%U123QC00008UAX%' ESCAPE '!' OR pt.receipt_no LIKE '%U123QC00008UAX%' ESCAPE '!' OR pt.description AS description LIKE '%U123QC00008UAX%' ESCAPE '!' OR pt.amount LIKE '%U123QC00008UAX%' ESCAPE '!' OR pt.pay_count LIKE '%U123QC00008UAX%' ESCAPE '!' OR DATE_FORMAT(pt.date, "%Y-%m-%d %H:%i") LIKE '%U123QC00008UAX%' ESCAPE '!' OR IF(pt.status=0, "Fail", IF(pt.status=1, "Success", IF(pt.status=2, "Pending", IF(pt.status=3, "Applied", IF(pt.status=4, "Cancelled", ""))))) LIKE '%U123QC00008UAX%' ESCAPE '!' ) GROUP BY pt.id ORDER BY pt.id DESC LIMIT 0, 10 
ERROR - 2025-10-03 12:29:46 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/ncvet/candidate/Transaction.php 100
ERROR - 2025-10-03 12:29:49 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-10-03 12:29:49 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-10-03 12:29:49 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-10-03 12:29:49 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-10-03 12:30:18 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-10-03 12:30:18 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-10-03 12:30:18 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-10-03 12:30:18 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-10-03 13:24:57 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 13:32:00 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 13:32:22 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 13:32:33 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 13:32:33 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 13:35:10 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 13:35:11 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 13:36:16 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 13:36:21 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 13:36:48 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 13:36:57 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 13:42:04 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 13:42:20 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 13:43:16 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 13:43:39 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 13:43:50 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 13:44:28 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 13:44:32 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 13:44:55 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 13:45:08 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 13:59:01 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 14:01:33 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 14:01:41 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 14:02:25 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 14:02:34 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 14:04:38 --> Severity: Warning --> rename(uploads/ncvet/institute_idproof/,uploads/ncvet/institute_idproof/inst_id_9000061.): Invalid argument /home/supp0rttest/public_html/staging/application/controllers/ncvet/candidate/Dashboard_candidate.php 968
ERROR - 2025-10-03 14:04:38 --> Severity: Warning --> rename(uploads/ncvet/disability/,uploads/ncvet/disability/o_9000061.): Invalid argument /home/supp0rttest/public_html/staging/application/controllers/ncvet/candidate/Dashboard_candidate.php 990
ERROR - 2025-10-03 14:04:38 --> Severity: Warning --> rename(uploads/ncvet/disability/,uploads/ncvet/disability/c_9000061.): Invalid argument /home/supp0rttest/public_html/staging/application/controllers/ncvet/candidate/Dashboard_candidate.php 1001
ERROR - 2025-10-03 14:04:46 --> Severity: Warning --> rename(uploads/ncvet/institute_idproof/,uploads/ncvet/institute_idproof/inst_id_9000061.): Invalid argument /home/supp0rttest/public_html/staging/application/controllers/ncvet/candidate/Dashboard_candidate.php 968
ERROR - 2025-10-03 14:04:46 --> Severity: Warning --> rename(uploads/ncvet/disability/,uploads/ncvet/disability/o_9000061.): Invalid argument /home/supp0rttest/public_html/staging/application/controllers/ncvet/candidate/Dashboard_candidate.php 990
ERROR - 2025-10-03 14:04:46 --> Severity: Warning --> rename(uploads/ncvet/disability/,uploads/ncvet/disability/c_9000061.): Invalid argument /home/supp0rttest/public_html/staging/application/controllers/ncvet/candidate/Dashboard_candidate.php 1001
ERROR - 2025-10-03 14:05:05 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 14:05:15 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 14:07:40 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 14:07:53 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 14:11:32 --> Severity: Warning --> rename(uploads/ncvet/institute_idproof/,uploads/ncvet/institute_idproof/inst_id_9000061.): Invalid argument /home/supp0rttest/public_html/staging/application/controllers/ncvet/candidate/Dashboard_candidate.php 968
ERROR - 2025-10-03 14:11:32 --> Severity: Warning --> rename(uploads/ncvet/disability/,uploads/ncvet/disability/o_9000061.): Invalid argument /home/supp0rttest/public_html/staging/application/controllers/ncvet/candidate/Dashboard_candidate.php 990
ERROR - 2025-10-03 14:11:32 --> Severity: Warning --> rename(uploads/ncvet/disability/,uploads/ncvet/disability/c_9000061.): Invalid argument /home/supp0rttest/public_html/staging/application/controllers/ncvet/candidate/Dashboard_candidate.php 1001
ERROR - 2025-10-03 14:12:45 --> 404 Page Not Found: ncvet/Candidate_registration/favicon.ico
ERROR - 2025-10-03 14:13:13 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 14:13:21 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 14:13:35 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 14:13:37 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 14:13:40 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 14:13:43 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 14:14:03 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 14:14:11 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 14:16:33 --> Severity: Warning --> rename(uploads/ncvet/disability/,uploads/ncvet/disability/o_9000062.): Invalid argument /home/supp0rttest/public_html/staging/application/controllers/ncvet/candidate/Dashboard_candidate.php 990
ERROR - 2025-10-03 14:16:33 --> Severity: Warning --> rename(uploads/ncvet/disability/,uploads/ncvet/disability/c_9000062.): Invalid argument /home/supp0rttest/public_html/staging/application/controllers/ncvet/candidate/Dashboard_candidate.php 1001
ERROR - 2025-10-03 14:16:48 --> Severity: Warning --> rename(uploads/ncvet/disability/,uploads/ncvet/disability/o_9000062.): Invalid argument /home/supp0rttest/public_html/staging/application/controllers/ncvet/candidate/Dashboard_candidate.php 990
ERROR - 2025-10-03 14:16:48 --> Severity: Warning --> rename(uploads/ncvet/disability/,uploads/ncvet/disability/c_9000062.): Invalid argument /home/supp0rttest/public_html/staging/application/controllers/ncvet/candidate/Dashboard_candidate.php 1001
ERROR - 2025-10-03 14:45:23 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 14:45:57 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 14:47:48 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 14:53:13 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 14:53:30 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 14:59:38 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 15:04:24 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 15:05:05 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 15:05:15 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 15:06:57 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 15:07:20 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 15:07:30 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 15:08:15 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 15:08:43 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 15:09:17 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 15:12:02 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 15:12:52 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 15:13:19 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 15:13:28 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 15:18:57 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 15:19:00 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 15:25:01 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 15:26:22 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 15:26:25 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 15:29:18 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 15:31:56 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 15:34:54 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 15:38:58 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 15:39:10 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 15:39:12 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 15:39:51 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 15:40:37 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 15:40:41 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 15:40:45 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 15:41:11 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 15:43:15 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 15:43:20 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 15:51:22 --> Query error: Unknown column 'aadhaar_card' in 'field list' - Invalid query: INSERT INTO `ncvet_scribe_registration` (`exam_code`, `exam_name`, `subject_name`, `regnumber`, `namesub`, `firstname`, `middlename`, `lastname`, `email`, `mobile`, `benchmark_disability`, `visually_impaired`, `orthopedically_handicapped`, `cerebral_palsy`, `center_name`, `center_code`, `exam_date`, `qualification`, `name_of_scribe`, `mobile_scribe`, `scribe_dob`, `scribe_email`, `declaration_img`, `aadhar_no`, `aadhaar_card`, `vis_imp_cert_img`, `orth_han_cert_img`, `cer_palsy_cert_img`, `modified_on`) VALUES ('123456', 'test', 'test', '9000052', 'Mr.', 'Sameer', 'Ashok', 'Shewale', 'gaurav.shewale@esds.co.in', '7620851847', 'Y', 'Y', 'Y', 'N', 'test', '987654', '2025-10-31', 'HSC', 'Rupali Najan', '9857677887', '2007-10-01', 'test@gmail.com', 'declaration_img_20251003_155100_1759486860.pdf', '978967676766', 'aadhaar_card_20251003_155100_1759486860.jpg', '', '', '', '2025-10-03 15:51:00')
ERROR - 2025-10-03 15:51:22 --> DB Insert Error: Array
(
    [code] => 1054
    [message] => Unknown column 'aadhaar_card' in 'field list'
)

ERROR - 2025-10-03 15:51:22 --> Insert Data: Array
(
    [exam_code] => 123456
    [exam_name] => test
    [subject_name] => test
    [regnumber] => 9000052
    [namesub] => Mr.
    [firstname] => Sameer
    [middlename] => Ashok
    [lastname] => Shewale
    [email] => gaurav.shewale@esds.co.in
    [mobile] => 7620851847
    [benchmark_disability] => Y
    [visually_impaired] => Y
    [orthopedically_handicapped] => Y
    [cerebral_palsy] => N
    [center_name] => test
    [center_code] => 987654
    [exam_date] => 2025-10-31
    [qualification] => HSC
    [name_of_scribe] => Rupali Najan
    [mobile_scribe] => 9857677887
    [scribe_dob] => 2007-10-01
    [scribe_email] => test@gmail.com
    [declaration_img] => declaration_img_20251003_155100_1759486860.pdf
    [aadhar_no] => 978967676766
    [aadhaar_card] => aadhaar_card_20251003_155100_1759486860.jpg
    [vis_imp_cert_img] => 
    [orth_han_cert_img] => 
    [cer_palsy_cert_img] => 
    [modified_on] => 2025-10-03 15:51:00
)

ERROR - 2025-10-03 15:53:02 --> Query error: Unknown column 'email_id' in 'field list' - Invalid query: INSERT INTO `ncvet_member_login_otp` (`email_id`, `regnumber`, `otp_type`, `otp`, `is_validate`, `otp_expired_on`, `created_on`) VALUES ('gaurav.shewale@esds.co.in', '9000052', '3', 848417, '0', '2025-10-03 16:03:02', '2025-10-03 15:53:02')
ERROR - 2025-10-03 16:14:42 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 16:14:50 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 16:15:10 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 16:15:36 --> The upload path does not appear to be valid.
ERROR - 2025-10-03 16:15:36 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 16:17:11 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 16:18:18 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 16:18:43 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 16:18:51 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 16:23:41 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 16:26:00 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 16:26:06 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 16:26:08 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 16:27:47 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 16:31:38 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-10-03 16:31:38 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-10-03 16:31:38 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-10-03 16:31:38 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-10-03 16:31:57 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-10-03 16:31:57 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-10-03 16:31:57 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-10-03 16:31:58 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-10-03 16:32:00 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 16:32:18 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 16:32:23 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 16:33:26 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 16:33:47 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 16:34:11 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 16:34:33 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 16:35:54 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 16:43:51 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 16:54:40 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 16:57:22 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:00:05 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:00:40 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:01:01 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:01:45 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:02:14 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:02:15 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:02:58 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:03:54 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:13:18 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:13:42 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:15:56 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:16:29 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:17:33 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:18:07 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:27:54 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:28:09 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:30:55 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:30:56 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:30:56 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:30:56 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:30:56 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:30:56 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:32:04 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:32:06 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:32:07 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:32:07 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:44:42 --> Severity: Error --> Call to undefined function auto_version() /home/supp0rttest/public_html/staging/application/views/ncvet/scribe_form/details_page.php 10
ERROR - 2025-10-03 17:44:42 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:44:50 --> Severity: Error --> Call to undefined function auto_version() /home/supp0rttest/public_html/staging/application/views/ncvet/scribe_form/details_page.php 10
ERROR - 2025-10-03 17:44:52 --> Severity: Error --> Call to undefined function auto_version() /home/supp0rttest/public_html/staging/application/views/ncvet/scribe_form/details_page.php 10
ERROR - 2025-10-03 17:44:53 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:45:42 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:49:09 --> Severity: Error --> Call to undefined function auto_version() /home/supp0rttest/public_html/staging/application/views/ncvet/scribe_form/details_page.php 10
ERROR - 2025-10-03 17:49:18 --> Severity: Error --> Call to undefined function auto_version() /home/supp0rttest/public_html/staging/application/views/ncvet/scribe_form/details_page.php 10
ERROR - 2025-10-03 17:49:18 --> Severity: Error --> Call to undefined function auto_version() /home/supp0rttest/public_html/staging/application/views/ncvet/scribe_form/details_page.php 10
ERROR - 2025-10-03 17:49:22 --> Severity: Error --> Call to undefined function auto_version() /home/supp0rttest/public_html/staging/application/views/ncvet/scribe_form/details_page.php 10
ERROR - 2025-10-03 17:49:23 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:49:52 --> Severity: Error --> Call to undefined function auto_version() /home/supp0rttest/public_html/staging/application/views/ncvet/scribe_form/details_page.php 10
ERROR - 2025-10-03 17:49:52 --> Severity: Error --> Call to undefined function auto_version() /home/supp0rttest/public_html/staging/application/views/ncvet/scribe_form/details_page.php 10
ERROR - 2025-10-03 17:49:52 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:49:56 --> Severity: Error --> Call to undefined function auto_version() /home/supp0rttest/public_html/staging/application/views/ncvet/scribe_form/details_page.php 10
ERROR - 2025-10-03 17:49:56 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:50:17 --> Severity: Error --> Call to undefined function auto_version() /home/supp0rttest/public_html/staging/application/views/ncvet/scribe_form/details_page.php 10
ERROR - 2025-10-03 17:50:17 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:50:19 --> Severity: Error --> Call to undefined function auto_version() /home/supp0rttest/public_html/staging/application/views/ncvet/scribe_form/details_page.php 10
ERROR - 2025-10-03 17:50:19 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:50:20 --> Severity: Error --> Call to undefined function auto_version() /home/supp0rttest/public_html/staging/application/views/ncvet/scribe_form/details_page.php 10
ERROR - 2025-10-03 17:50:20 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:50:20 --> Severity: Error --> Call to undefined function auto_version() /home/supp0rttest/public_html/staging/application/views/ncvet/scribe_form/details_page.php 10
ERROR - 2025-10-03 17:50:20 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:50:39 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:51:18 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 17:57:41 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 18:03:28 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 18:05:45 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 18:05:50 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 18:06:24 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 18:06:49 --> Severity: Parsing Error --> syntax error, unexpected 'if' (T_IF) /home/supp0rttest/public_html/staging/application/controllers/ncvet/Scribe_form.php 195
ERROR - 2025-10-03 18:06:57 --> Severity: Parsing Error --> syntax error, unexpected 'if' (T_IF) /home/supp0rttest/public_html/staging/application/controllers/ncvet/Scribe_form.php 195
ERROR - 2025-10-03 18:07:03 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 18:12:11 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 18:13:58 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 18:14:11 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 18:23:15 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 18:25:48 --> 404 Page Not Found: ncvet/Scribe_form/css
ERROR - 2025-10-03 18:33:35 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2025-10-03 18:39:48 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2025-10-03 19:30:22 --> 404 Page Not Found: iibfbcbf/Kyc/index
ERROR - 2025-10-03 19:30:22 --> 404 Page Not Found: iibfbcbf/Kyc/index
ERROR - 2025-10-03 19:33:10 --> 404 Page Not Found: iibfbcbf/admin//index
