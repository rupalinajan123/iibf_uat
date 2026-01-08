ERROR - 2025-09-10 14:30:06 --> Query error: Table 'supp0rttest_iibf_staging.ncvet_agency_master' doesn't exist - Invalid query: SELECT `bc`.*, IF(bc.gender=1, 'Male', 'Female') AS DispGender, IF(bc.qualification=1, 'Under Graduate', IF(bc.qualification=2, 'Graduate', 'Post Graduate')) AS DispQualification, `sm`.`state_name`, `cm1`.`city_name`, IF(bc.id_proof_type=1, 'Aadhar Card', IF(bc.id_proof_type=2, 'Driving Licence', IF(bc.id_proof_type=3, 'Employee ID', IF(bc.id_proof_type=4, 'Pan Card', 'Passport')))) AS DispIdProofType, IF(bc.qualification_certificate_type=1, '10th Pass', IF(bc.qualification_certificate_type=2, '12th Pass', IF(bc.qualification_certificate_type=3, 'Graduation', IF(bc.qualification_certificate_type=4, 'Post Graduation', '')))) AS DispQualificationCertificateType, IF(bc.hold_release_status=1, 'Auto Hold', IF(bc.hold_release_status=2, 'Manual Hold', 'Release')) AS Disphold_release_status, `am`.`agency_name`, `am`.`agency_code`, `am`.`allow_exam_types`, `cm`.`centre_name`, `cm`.`centre_username`, `cm2`.`city_name` AS `centre_city_name`
FROM `ncvet_candidates` `bc`
LEFT JOIN `state_master` `sm` ON `sm`.`state_code` = `bc`.`state`
LEFT JOIN `city_master` `cm1` ON `cm1`.`id` = `bc`.`city`
LEFT JOIN `ncvet_agency_master` `am` ON `am`.`agency_id` = `bc`.`agency_id`
INNER JOIN `ncvet_centre_master` `cm` ON `cm`.`centre_id` = `bc`.`centre_id`
LEFT JOIN `city_master` `cm2` ON `cm2`.`id` = `cm`.`centre_city`
WHERE `bc`.`batch_id` = ''
AND `bc`.`candidate_id` = '1'
AND `bc`.`is_deleted` = '0'
ERROR - 2025-09-10 14:30:06 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 309
ERROR - 2025-09-10 15:30:42 --> Query error: Table 'supp0rttest_iibf_staging.prizewinners_registration' doesn't exist - Invalid query: SELECT * FROM prizewinners_registration WHERE regnumber = '510150128'LIMIT 1
ERROR - 2025-09-10 15:30:42 --> Severity: Error --> Call to a member function row_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/PrizeWinner.php 66
ERROR - 2025-09-10 15:31:25 --> 404 Page Not Found: Env/index
ERROR - 2025-09-10 15:31:25 --> 404 Page Not Found: Envlocal/index
ERROR - 2025-09-10 15:31:26 --> 404 Page Not Found: Envstaging/index
ERROR - 2025-09-10 15:31:26 --> 404 Page Not Found: Envproduction/index
ERROR - 2025-09-10 16:49:23 --> Severity: Parsing Error --> syntax error, unexpected '')); }' (T_CONSTANT_ENCAPSED_STRING) /home/supp0rttest/public_html/staging/application/controllers/ncvet/admin/Transaction.php 193
ERROR - 2025-09-10 16:49:28 --> Severity: Parsing Error --> syntax error, unexpected '')); }' (T_CONSTANT_ENCAPSED_STRING) /home/supp0rttest/public_html/staging/application/controllers/ncvet/admin/Transaction.php 193
ERROR - 2025-09-10 16:49:28 --> Severity: Parsing Error --> syntax error, unexpected '')); }' (T_CONSTANT_ENCAPSED_STRING) /home/supp0rttest/public_html/staging/application/controllers/ncvet/admin/Transaction.php 193
ERROR - 2025-09-10 16:49:30 --> Severity: Parsing Error --> syntax error, unexpected '')); }' (T_CONSTANT_ENCAPSED_STRING) /home/supp0rttest/public_html/staging/application/controllers/ncvet/admin/Transaction.php 193
ERROR - 2025-09-10 16:52:44 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND pt.date != '' AND pt.transaction_no != ''  GROUP BY pt.id ORDER BY pt.id DES' at line 1 - Invalid query: SELECT pt.id, pt.pg_flag, pt.transaction_no AS DispTransactionNo, pt.receipt_no, pt.amount, DATE_FORMAT(pt.date, "%Y-%m-%d %H:%i") AS PaymentDate, IF(pt.status=0, "Fail", IF(pt.status=1, "Success", IF(pt.status=2, "Pending", IF(pt.status=3, "Applied", IF(pt.status=4, "Cancelled", ""))))) AS DispPayStatus, pt.status, IF(pt.pay_type=1, "Membership Enrollment", "") AS TransactionType FROM ncvet_payment_transaction pt   WHERE pt.ref_id =  AND pt.date != '' AND pt.transaction_no != ''  GROUP BY pt.id ORDER BY pt.id DESC LIMIT 0, 10 
ERROR - 2025-09-10 16:52:44 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/ncvet/admin/Transaction.php 117
ERROR - 2025-09-10 16:52:48 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-10 16:52:48 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-10 16:52:48 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-10 16:52:48 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-10 16:53:44 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-10 16:53:44 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-10 16:53:44 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-10 16:53:44 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-10 16:58:26 --> Could not find the language line "form_validation_requiredcase_study_level_desc_id"
ERROR - 2025-09-10 17:00:05 --> Could not find the language line "form_validation_requiredcase_study_level_desc_id"
ERROR - 2025-09-10 17:03:41 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-10 17:03:41 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-10 17:03:41 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-10 17:03:41 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-10 17:04:01 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-10 17:04:01 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-10 17:04:01 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-10 17:04:01 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-10 17:06:13 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-10 17:06:14 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-10 17:06:14 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-10 17:06:14 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-10 17:06:17 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-10 17:06:17 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-10 17:06:17 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-10 17:06:17 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-10 17:12:24 --> Query error: Unknown column 'dob' in 'field list' - Invalid query: INSERT INTO `ncvet_scribe_registration` (`exam_code`, `exam_name`, `subject_name`, `regnumber`, `namesub`, `firstname`, `middlename`, `dob`, `lastname`, `email`, `mobile`, `benchmark_disability`, `visually_impaired`, `orthopedically_handicapped`, `cerebral_palsy`, `center_name`, `center_code`, `exam_date`, `qualification`, `name_of_scribe`, `mobile_scribe`, `scribe_dob`, `scribe_email`, `declaration_img`, `aadhar_no`, `aadhaar_card`, `vis_imp_cert_img`, `orth_han_cert_img`, `cer_palsy_cert_img`, `modified_on`) VALUES ('123456', 'test', 'test', '9000052', 'Mr.', 'Gaurav', 'Ashok', '2007-09-05', 'Shewale', 'gaurav.shewale@esds.co.in', '7620851847', 'Y', 'v_9000052.pdf', 'o_9000052.jpg', 'c_9000052.pdf', 'test', '987654', '2025-09-30', 'HSC', 'Rupali Najan', '9857677882', '2000-10-13', 'test@gmail.com', 'uploads/ncvet/scribe/declaration/declaration_img_1757504541.pdf', '978967676665', 'uploads/ncvet/scribe/idproof/aadhaar_card_1757504541.jpg', '', '', '', '2025-09-10 17:12:21')
ERROR - 2025-09-10 17:12:40 --> Query error: Unknown column 'dob' in 'field list' - Invalid query: INSERT INTO `ncvet_scribe_registration` (`exam_code`, `exam_name`, `subject_name`, `regnumber`, `namesub`, `firstname`, `middlename`, `dob`, `lastname`, `email`, `mobile`, `benchmark_disability`, `visually_impaired`, `orthopedically_handicapped`, `cerebral_palsy`, `center_name`, `center_code`, `exam_date`, `qualification`, `name_of_scribe`, `mobile_scribe`, `scribe_dob`, `scribe_email`, `declaration_img`, `aadhar_no`, `aadhaar_card`, `vis_imp_cert_img`, `orth_han_cert_img`, `cer_palsy_cert_img`, `modified_on`) VALUES ('123456', 'test', 'test', '9000052', 'Mr.', 'Gaurav', 'Ashok', '2007-09-05', 'Shewale', 'gaurav.shewale@esds.co.in', '7620851847', 'Y', 'v_9000052.pdf', 'o_9000052.jpg', 'c_9000052.pdf', 'test', '987654', '2025-09-30', 'HSC', 'Rupali Najan', '9857677882', '2000-10-13', 'test@gmail.com', 'uploads/ncvet/scribe/declaration/declaration_img_1757504541.pdf', '978967676665', 'uploads/ncvet/scribe/idproof/aadhaar_card_1757504541.jpg', '', '', '', '2025-09-10 17:12:21')
ERROR - 2025-09-10 17:14:40 --> Query error: Unknown column 'dob' in 'field list' - Invalid query: INSERT INTO `ncvet_scribe_registration` (`exam_code`, `exam_name`, `subject_name`, `regnumber`, `namesub`, `firstname`, `middlename`, `dob`, `lastname`, `email`, `mobile`, `benchmark_disability`, `visually_impaired`, `orthopedically_handicapped`, `cerebral_palsy`, `center_name`, `center_code`, `exam_date`, `qualification`, `name_of_scribe`, `mobile_scribe`, `scribe_dob`, `scribe_email`, `declaration_img`, `aadhar_no`, `aadhaar_card`, `vis_imp_cert_img`, `orth_han_cert_img`, `cer_palsy_cert_img`, `modified_on`) VALUES ('123456', 'test', 'test', '9000052', 'Mr.', 'Gaurav', 'Ashok', '2007-09-05', 'Shewale', 'gaurav.shewale@esds.co.in', '7620851847', 'Y', 'v_9000052.pdf', 'o_9000052.jpg', 'c_9000052.pdf', 'test', '987654', '2025-09-30', 'HSC', 'Rupali Najan', '9857677882', '2000-10-13', 'test@gmail.com', 'uploads/ncvet/scribe/declaration/declaration_img_1757504541.pdf', '978967676665', 'uploads/ncvet/scribe/idproof/aadhaar_card_1757504541.jpg', '', '', '', '2025-09-10 17:12:21')
ERROR - 2025-09-10 17:14:40 --> DB Insert Error: Array
(
    [code] => 1054
    [message] => Unknown column 'dob' in 'field list'
)

ERROR - 2025-09-10 17:14:40 --> Insert Data: Array
(
    [exam_code] => 123456
    [exam_name] => test
    [subject_name] => test
    [regnumber] => 9000052
    [namesub] => Mr.
    [firstname] => Gaurav
    [middlename] => Ashok
    [dob] => 2007-09-05
    [lastname] => Shewale
    [email] => gaurav.shewale@esds.co.in
    [mobile] => 7620851847
    [benchmark_disability] => Y
    [visually_impaired] => v_9000052.pdf
    [orthopedically_handicapped] => o_9000052.jpg
    [cerebral_palsy] => c_9000052.pdf
    [center_name] => test
    [center_code] => 987654
    [exam_date] => 2025-09-30
    [qualification] => HSC
    [name_of_scribe] => Rupali Najan
    [mobile_scribe] => 9857677882
    [scribe_dob] => 2000-10-13
    [scribe_email] => test@gmail.com
    [declaration_img] => uploads/ncvet/scribe/declaration/declaration_img_1757504541.pdf
    [aadhar_no] => 978967676665
    [aadhaar_card] => uploads/ncvet/scribe/idproof/aadhaar_card_1757504541.jpg
    [vis_imp_cert_img] => 
    [orth_han_cert_img] => 
    [cer_palsy_cert_img] => 
    [modified_on] => 2025-09-10 17:12:21
)

ERROR - 2025-09-10 17:15:10 --> Query error: Unknown column 'dob' in 'field list' - Invalid query: INSERT INTO `ncvet_scribe_registration` (`exam_code`, `exam_name`, `subject_name`, `regnumber`, `namesub`, `firstname`, `middlename`, `dob`, `lastname`, `email`, `mobile`, `benchmark_disability`, `visually_impaired`, `orthopedically_handicapped`, `cerebral_palsy`, `center_name`, `center_code`, `exam_date`, `qualification`, `name_of_scribe`, `mobile_scribe`, `scribe_dob`, `scribe_email`, `declaration_img`, `aadhar_no`, `aadhaar_card`, `vis_imp_cert_img`, `orth_han_cert_img`, `cer_palsy_cert_img`, `modified_on`) VALUES ('123456', 'test', 'test', '9000052', 'Mr.', 'Gaurav', 'Ashok', '2007-09-05', 'Shewale', 'gaurav.shewale@esds.co.in', '7620851847', 'Y', 'v_9000052.pdf', 'o_9000052.jpg', 'c_9000052.pdf', 'test', '987654', '2025-09-30', 'HSC', 'Rupali Najan', '9857677882', '2000-10-13', 'test@gmail.com', 'uploads/ncvet/scribe/declaration/declaration_img_1757504541.pdf', '978967676665', 'uploads/ncvet/scribe/idproof/aadhaar_card_1757504541.jpg', '', '', '', '2025-09-10 17:12:21')
ERROR - 2025-09-10 17:15:10 --> DB Insert Error: Array
(
    [code] => 1054
    [message] => Unknown column 'dob' in 'field list'
)

ERROR - 2025-09-10 17:15:10 --> Insert Data: Array
(
    [exam_code] => 123456
    [exam_name] => test
    [subject_name] => test
    [regnumber] => 9000052
    [namesub] => Mr.
    [firstname] => Gaurav
    [middlename] => Ashok
    [dob] => 2007-09-05
    [lastname] => Shewale
    [email] => gaurav.shewale@esds.co.in
    [mobile] => 7620851847
    [benchmark_disability] => Y
    [visually_impaired] => v_9000052.pdf
    [orthopedically_handicapped] => o_9000052.jpg
    [cerebral_palsy] => c_9000052.pdf
    [center_name] => test
    [center_code] => 987654
    [exam_date] => 2025-09-30
    [qualification] => HSC
    [name_of_scribe] => Rupali Najan
    [mobile_scribe] => 9857677882
    [scribe_dob] => 2000-10-13
    [scribe_email] => test@gmail.com
    [declaration_img] => uploads/ncvet/scribe/declaration/declaration_img_1757504541.pdf
    [aadhar_no] => 978967676665
    [aadhaar_card] => uploads/ncvet/scribe/idproof/aadhaar_card_1757504541.jpg
    [vis_imp_cert_img] => 
    [orth_han_cert_img] => 
    [cer_palsy_cert_img] => 
    [modified_on] => 2025-09-10 17:12:21
)

ERROR - 2025-09-10 17:18:03 --> Query error: Unknown column 'dob' in 'field list' - Invalid query: INSERT INTO `ncvet_scribe_registration` (`exam_code`, `exam_name`, `subject_name`, `regnumber`, `namesub`, `firstname`, `middlename`, `dob`, `lastname`, `email`, `mobile`, `benchmark_disability`, `visually_impaired`, `orthopedically_handicapped`, `cerebral_palsy`, `center_name`, `center_code`, `exam_date`, `qualification`, `name_of_scribe`, `mobile_scribe`, `scribe_dob`, `scribe_email`, `declaration_img`, `aadhar_no`, `aadhaar_card`, `vis_imp_cert_img`, `orth_han_cert_img`, `cer_palsy_cert_img`, `modified_on`) VALUES ('123456', 'test', 'test', '9000052', 'Mr.', 'Gaurav', 'Ashok', '2007-09-05', 'Shewale', 'gaurav.shewale@esds.co.in', '7620851847', 'Y', 'v_9000052.pdf', 'o_9000052.jpg', 'c_9000052.pdf', 'test', '987654', '2025-09-30', 'HSC', 'Rupali Najan', '9857677882', '2000-10-13', 'test@gmail.com', 'uploads/ncvet/scribe/declaration/declaration_img_1757504541.pdf', '978967676665', 'uploads/ncvet/scribe/idproof/aadhaar_card_1757504541.jpg', '', '', '', '2025-09-10 17:12:21')
ERROR - 2025-09-10 17:18:03 --> DB Insert Error: Array
(
    [code] => 1054
    [message] => Unknown column 'dob' in 'field list'
)

ERROR - 2025-09-10 17:18:03 --> Insert Data: Array
(
    [exam_code] => 123456
    [exam_name] => test
    [subject_name] => test
    [regnumber] => 9000052
    [namesub] => Mr.
    [firstname] => Gaurav
    [middlename] => Ashok
    [dob] => 2007-09-05
    [lastname] => Shewale
    [email] => gaurav.shewale@esds.co.in
    [mobile] => 7620851847
    [benchmark_disability] => Y
    [visually_impaired] => v_9000052.pdf
    [orthopedically_handicapped] => o_9000052.jpg
    [cerebral_palsy] => c_9000052.pdf
    [center_name] => test
    [center_code] => 987654
    [exam_date] => 2025-09-30
    [qualification] => HSC
    [name_of_scribe] => Rupali Najan
    [mobile_scribe] => 9857677882
    [scribe_dob] => 2000-10-13
    [scribe_email] => test@gmail.com
    [declaration_img] => uploads/ncvet/scribe/declaration/declaration_img_1757504541.pdf
    [aadhar_no] => 978967676665
    [aadhaar_card] => uploads/ncvet/scribe/idproof/aadhaar_card_1757504541.jpg
    [vis_imp_cert_img] => 
    [orth_han_cert_img] => 
    [cer_palsy_cert_img] => 
    [modified_on] => 2025-09-10 17:12:21
)

ERROR - 2025-09-10 17:18:12 --> Query error: Unknown column 'dob' in 'field list' - Invalid query: INSERT INTO `ncvet_scribe_registration` (`exam_code`, `exam_name`, `subject_name`, `regnumber`, `namesub`, `firstname`, `middlename`, `dob`, `lastname`, `email`, `mobile`, `benchmark_disability`, `visually_impaired`, `orthopedically_handicapped`, `cerebral_palsy`, `center_name`, `center_code`, `exam_date`, `qualification`, `name_of_scribe`, `mobile_scribe`, `scribe_dob`, `scribe_email`, `declaration_img`, `aadhar_no`, `aadhaar_card`, `vis_imp_cert_img`, `orth_han_cert_img`, `cer_palsy_cert_img`, `modified_on`) VALUES ('123456', 'test', 'test', '9000052', 'Mr.', 'Gaurav', 'Ashok', '2007-09-05', 'Shewale', 'gaurav.shewale@esds.co.in', '7620851847', 'Y', 'v_9000052.pdf', 'o_9000052.jpg', 'c_9000052.pdf', 'test', '987654', '2025-09-30', 'HSC', 'Rupali Najan', '9857677882', '2000-10-13', 'test@gmail.com', 'uploads/ncvet/scribe/declaration/declaration_img_1757504541.pdf', '978967676665', 'uploads/ncvet/scribe/idproof/aadhaar_card_1757504541.jpg', '', '', '', '2025-09-10 17:12:21')
ERROR - 2025-09-10 17:18:12 --> DB Insert Error: Array
(
    [code] => 1054
    [message] => Unknown column 'dob' in 'field list'
)

ERROR - 2025-09-10 17:18:12 --> Insert Data: Array
(
    [exam_code] => 123456
    [exam_name] => test
    [subject_name] => test
    [regnumber] => 9000052
    [namesub] => Mr.
    [firstname] => Gaurav
    [middlename] => Ashok
    [dob] => 2007-09-05
    [lastname] => Shewale
    [email] => gaurav.shewale@esds.co.in
    [mobile] => 7620851847
    [benchmark_disability] => Y
    [visually_impaired] => v_9000052.pdf
    [orthopedically_handicapped] => o_9000052.jpg
    [cerebral_palsy] => c_9000052.pdf
    [center_name] => test
    [center_code] => 987654
    [exam_date] => 2025-09-30
    [qualification] => HSC
    [name_of_scribe] => Rupali Najan
    [mobile_scribe] => 9857677882
    [scribe_dob] => 2000-10-13
    [scribe_email] => test@gmail.com
    [declaration_img] => uploads/ncvet/scribe/declaration/declaration_img_1757504541.pdf
    [aadhar_no] => 978967676665
    [aadhaar_card] => uploads/ncvet/scribe/idproof/aadhaar_card_1757504541.jpg
    [vis_imp_cert_img] => 
    [orth_han_cert_img] => 
    [cer_palsy_cert_img] => 
    [modified_on] => 2025-09-10 17:12:21
)

ERROR - 2025-09-10 17:21:16 --> Query error: Unknown column 'dob' in 'field list' - Invalid query: INSERT INTO `ncvet_scribe_registration` (`exam_code`, `exam_name`, `subject_name`, `regnumber`, `namesub`, `firstname`, `middlename`, `dob`, `lastname`, `email`, `mobile`, `benchmark_disability`, `visually_impaired`, `orthopedically_handicapped`, `cerebral_palsy`, `center_name`, `center_code`, `exam_date`, `qualification`, `name_of_scribe`, `mobile_scribe`, `scribe_dob`, `scribe_email`, `declaration_img`, `aadhar_no`, `aadhaar_card`, `vis_imp_cert_img`, `orth_han_cert_img`, `cer_palsy_cert_img`, `modified_on`) VALUES ('123456', 'test', 'test', '9000052', 'Mr.', 'Gaurav', 'Ashok', '2007-09-05', 'Shewale', 'gaurav.shewale@esds.co.in', '7620851847', 'Y', 'v_9000052.pdf', 'o_9000052.jpg', 'c_9000052.pdf', 'test', '987654', '2025-09-30', 'HSC', 'Rupali Najan', '9857677882', '2000-10-13', 'test@gmail.com', 'uploads/ncvet/scribe/declaration/declaration_img_1757504541.pdf', '978967676665', 'uploads/ncvet/scribe/idproof/aadhaar_card_1757504541.jpg', '', '', '', '2025-09-10 17:12:21')
ERROR - 2025-09-10 17:21:16 --> DB Insert Error: Array
(
    [code] => 1054
    [message] => Unknown column 'dob' in 'field list'
)

ERROR - 2025-09-10 17:21:16 --> Insert Data: Array
(
    [exam_code] => 123456
    [exam_name] => test
    [subject_name] => test
    [regnumber] => 9000052
    [namesub] => Mr.
    [firstname] => Gaurav
    [middlename] => Ashok
    [dob] => 2007-09-05
    [lastname] => Shewale
    [email] => gaurav.shewale@esds.co.in
    [mobile] => 7620851847
    [benchmark_disability] => Y
    [visually_impaired] => v_9000052.pdf
    [orthopedically_handicapped] => o_9000052.jpg
    [cerebral_palsy] => c_9000052.pdf
    [center_name] => test
    [center_code] => 987654
    [exam_date] => 2025-09-30
    [qualification] => HSC
    [name_of_scribe] => Rupali Najan
    [mobile_scribe] => 9857677882
    [scribe_dob] => 2000-10-13
    [scribe_email] => test@gmail.com
    [declaration_img] => uploads/ncvet/scribe/declaration/declaration_img_1757504541.pdf
    [aadhar_no] => 978967676665
    [aadhaar_card] => uploads/ncvet/scribe/idproof/aadhaar_card_1757504541.jpg
    [vis_imp_cert_img] => 
    [orth_han_cert_img] => 
    [cer_palsy_cert_img] => 
    [modified_on] => 2025-09-10 17:12:21
)

ERROR - 2025-09-10 17:22:07 --> Query error: Unknown column 'aadhar_no' in 'field list' - Invalid query: INSERT INTO `ncvet_scribe_registration` (`exam_code`, `exam_name`, `subject_name`, `regnumber`, `namesub`, `firstname`, `middlename`, `lastname`, `email`, `mobile`, `benchmark_disability`, `visually_impaired`, `orthopedically_handicapped`, `cerebral_palsy`, `center_name`, `center_code`, `exam_date`, `qualification`, `name_of_scribe`, `mobile_scribe`, `scribe_dob`, `scribe_email`, `declaration_img`, `aadhar_no`, `aadhaar_card`, `vis_imp_cert_img`, `orth_han_cert_img`, `cer_palsy_cert_img`, `modified_on`) VALUES ('123456', 'test', 'test', '9000052', 'Mr.', 'Gaurav', 'Ashok', 'Shewale', 'gaurav.shewale@esds.co.in', '7620851847', 'Y', 'v_9000052.pdf', 'o_9000052.jpg', 'c_9000052.pdf', 'test', '987654', '2025-09-30', 'HSC', 'Rupali Najan', '9857677882', '2001-09-14', 'test@gmail.com', 'uploads/ncvet/scribe/declaration/declaration_img_1757505125.pdf', '978967676665', 'uploads/ncvet/scribe/idproof/aadhaar_card_1757505125.jpg', '', '', '', '2025-09-10 17:22:05')
ERROR - 2025-09-10 17:22:07 --> DB Insert Error: Array
(
    [code] => 1054
    [message] => Unknown column 'aadhar_no' in 'field list'
)

ERROR - 2025-09-10 17:22:07 --> Insert Data: Array
(
    [exam_code] => 123456
    [exam_name] => test
    [subject_name] => test
    [regnumber] => 9000052
    [namesub] => Mr.
    [firstname] => Gaurav
    [middlename] => Ashok
    [lastname] => Shewale
    [email] => gaurav.shewale@esds.co.in
    [mobile] => 7620851847
    [benchmark_disability] => Y
    [visually_impaired] => v_9000052.pdf
    [orthopedically_handicapped] => o_9000052.jpg
    [cerebral_palsy] => c_9000052.pdf
    [center_name] => test
    [center_code] => 987654
    [exam_date] => 2025-09-30
    [qualification] => HSC
    [name_of_scribe] => Rupali Najan
    [mobile_scribe] => 9857677882
    [scribe_dob] => 2001-09-14
    [scribe_email] => test@gmail.com
    [declaration_img] => uploads/ncvet/scribe/declaration/declaration_img_1757505125.pdf
    [aadhar_no] => 978967676665
    [aadhaar_card] => uploads/ncvet/scribe/idproof/aadhaar_card_1757505125.jpg
    [vis_imp_cert_img] => 
    [orth_han_cert_img] => 
    [cer_palsy_cert_img] => 
    [modified_on] => 2025-09-10 17:22:05
)

ERROR - 2025-09-10 17:22:27 --> Query error: Unknown column 'aadhar_no' in 'field list' - Invalid query: INSERT INTO `ncvet_scribe_registration` (`exam_code`, `exam_name`, `subject_name`, `regnumber`, `namesub`, `firstname`, `middlename`, `lastname`, `email`, `mobile`, `benchmark_disability`, `visually_impaired`, `orthopedically_handicapped`, `cerebral_palsy`, `center_name`, `center_code`, `exam_date`, `qualification`, `name_of_scribe`, `mobile_scribe`, `scribe_dob`, `scribe_email`, `declaration_img`, `aadhar_no`, `aadhaar_card`, `vis_imp_cert_img`, `orth_han_cert_img`, `cer_palsy_cert_img`, `modified_on`) VALUES ('123456', 'test', 'test', '9000052', 'Mr.', 'Gaurav', 'Ashok', 'Shewale', 'gaurav.shewale@esds.co.in', '7620851847', 'Y', 'v_9000052.pdf', 'o_9000052.jpg', 'c_9000052.pdf', 'test', '987654', '2025-09-30', 'HSC', 'Rupali Najan', '9857677882', '2001-09-14', 'test@gmail.com', 'uploads/ncvet/scribe/declaration/declaration_img_1757505125.pdf', '978967676665', 'uploads/ncvet/scribe/idproof/aadhaar_card_1757505125.jpg', '', '', '', '2025-09-10 17:22:05')
ERROR - 2025-09-10 17:22:27 --> DB Insert Error: Array
(
    [code] => 1054
    [message] => Unknown column 'aadhar_no' in 'field list'
)

ERROR - 2025-09-10 17:22:27 --> Insert Data: Array
(
    [exam_code] => 123456
    [exam_name] => test
    [subject_name] => test
    [regnumber] => 9000052
    [namesub] => Mr.
    [firstname] => Gaurav
    [middlename] => Ashok
    [lastname] => Shewale
    [email] => gaurav.shewale@esds.co.in
    [mobile] => 7620851847
    [benchmark_disability] => Y
    [visually_impaired] => v_9000052.pdf
    [orthopedically_handicapped] => o_9000052.jpg
    [cerebral_palsy] => c_9000052.pdf
    [center_name] => test
    [center_code] => 987654
    [exam_date] => 2025-09-30
    [qualification] => HSC
    [name_of_scribe] => Rupali Najan
    [mobile_scribe] => 9857677882
    [scribe_dob] => 2001-09-14
    [scribe_email] => test@gmail.com
    [declaration_img] => uploads/ncvet/scribe/declaration/declaration_img_1757505125.pdf
    [aadhar_no] => 978967676665
    [aadhaar_card] => uploads/ncvet/scribe/idproof/aadhaar_card_1757505125.jpg
    [vis_imp_cert_img] => 
    [orth_han_cert_img] => 
    [cer_palsy_cert_img] => 
    [modified_on] => 2025-09-10 17:22:05
)

ERROR - 2025-09-10 17:30:43 --> 404 Page Not Found: iibfdra/Version_2/Agency/get_tranning_center_list
ERROR - 2025-09-10 17:32:46 --> Query error: Table 'supp0rttest_iibf_staging.prizewinners_registration' doesn't exist - Invalid query: SELECT * FROM prizewinners_registration WHERE regnumber = '51015012 8'LIMIT 1
ERROR - 2025-09-10 17:32:46 --> Severity: Error --> Call to a member function row_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/PrizeWinner.php 66
ERROR - 2025-09-10 17:32:52 --> Query error: Table 'supp0rttest_iibf_staging.prizewinners_registration' doesn't exist - Invalid query: SELECT * FROM prizewinners_registration WHERE regnumber = '51015012 8'LIMIT 1
ERROR - 2025-09-10 17:32:52 --> Severity: Error --> Call to a member function row_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/PrizeWinner.php 66
ERROR - 2025-09-10 17:33:48 --> Query error: Table 'supp0rttest_iibf_staging.prizewinners_registration' doesn't exist - Invalid query: SELECT * FROM prizewinners_registration WHERE regnumber = '80100182 4'LIMIT 1
ERROR - 2025-09-10 17:33:48 --> Severity: Error --> Call to a member function row_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/PrizeWinner.php 66
ERROR - 2025-09-10 17:34:42 --> Query error: Table 'supp0rttest_iibf_staging.prizewinners_registration' doesn't exist - Invalid query: SELECT * FROM prizewinners_registration WHERE regnumber = '80100182 4'LIMIT 1
ERROR - 2025-09-10 17:34:42 --> Severity: Error --> Call to a member function row_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/PrizeWinner.php 66
ERROR - 2025-09-10 17:35:39 --> Query error: Unknown column 'email_id' in 'field list' - Invalid query: INSERT INTO `ncvet_member_login_otp` (`email_id`, `regnumber`, `otp_type`, `otp`, `is_validate`, `otp_expired_on`, `created_on`) VALUES ('gaurav.shewale@esds.co.in', '9000052', '3', 115350, '0', '2025-09-10 17:45:39', '2025-09-10 17:35:39')
ERROR - 2025-09-10 18:02:03 --> Query error: Table 'supp0rttest_iibf_staging.admin_scribe_kyc_users' doesn't exist - Invalid query: SELECT *
FROM `admin_scribe_kyc_users`
WHERE DATE(date) = '2025-09-10'
AND `user_id` = '126'
AND `list_type` = 'New'
AND `allotted_member_id` = ''
ERROR - 2025-09-10 18:02:03 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 309
ERROR - 2025-09-10 18:02:10 --> Query error: Table 'supp0rttest_iibf_staging.admin_scribe_kyc_users' doesn't exist - Invalid query: SELECT *
FROM `admin_scribe_kyc_users`
WHERE DATE(date) = '2025-09-10'
AND `user_id` = '126'
AND `list_type` = 'New'
AND `allotted_member_id` = ''
ERROR - 2025-09-10 18:02:10 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/models/Master_model.php 309
ERROR - 2025-09-10 18:15:15 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-10 18:15:15 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-10 18:15:15 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-10 18:15:15 --> 404 Page Not Found: Assets/ncvet
ERROR - 2025-09-10 18:15:42 --> Severity: Warning --> rename(uploads/ncvet/experience/,uploads/ncvet/experience/exp_9000052.): Invalid argument /home/supp0rttest/public_html/staging/application/controllers/ncvet/candidate/Dashboard_candidate.php 728
ERROR - 2025-09-10 19:24:55 --> 404 Page Not Found: case_writing_competition/Preview/index
