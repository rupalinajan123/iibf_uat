<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2025-12-30 10:00:46 --> 404 Page Not Found: Uploads/exam_instruction
ERROR - 2025-12-30 10:31:05 --> 404 Page Not Found: iibfdra/Version_2/Inspector/login
ERROR - 2025-12-30 10:31:14 --> 404 Page Not Found: iibfdra/Version_2/Inspectorlogin/index
ERROR - 2025-12-30 10:37:24 --> Query error: Unknown column 'status' in 'order clause' - Invalid query: SELECT b.batch_code, COUNT(bi.id) as reported,GROUP_CONCAT(bi.overall_compliance) as overall_compliance_list, b.hours, c1.city_name, b.holidays,b.timing_from, b.timing_to, b.batch_from_date, b.total_candidates, b.training_medium, b.created_on, b.batch_status, b.id, b.agency_id, b.center_id, b.inspector_id,  b.batch_type, b.batch_online_offline_flag,  b.batch_name,  b.batch_to_date, b.batch_type, b.batch_online_offline_flag, b.batch_active_period
            FROM agency_batch b 
            LEFT JOIN dra_batch_inspection bi ON bi.batch_id = b.id AND bi.inspector_id = 169
            LEFT JOIN agency_center c ON b.center_id = c.center_id
            LEFT JOIN city_master c1 ON c1.id = c.location_name
            WHERE is_deleted = 0 AND   b.inspector_id = 169  GROUP BY b.id  ORDER BY status   asc  LIMIT 0 ,10 
ERROR - 2025-12-30 10:37:24 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/InspectorHome.php 128
ERROR - 2025-12-30 10:37:29 --> Query error: Unknown column 'status' in 'order clause' - Invalid query: SELECT b.batch_code, COUNT(bi.id) as reported,GROUP_CONCAT(bi.overall_compliance) as overall_compliance_list, b.hours, c1.city_name, b.holidays,b.timing_from, b.timing_to, b.batch_from_date, b.total_candidates, b.training_medium, b.created_on, b.batch_status, b.id, b.agency_id, b.center_id, b.inspector_id,  b.batch_type, b.batch_online_offline_flag,  b.batch_name,  b.batch_to_date, b.batch_type, b.batch_online_offline_flag, b.batch_active_period
            FROM agency_batch b 
            LEFT JOIN dra_batch_inspection bi ON bi.batch_id = b.id AND bi.inspector_id = 169
            LEFT JOIN agency_center c ON b.center_id = c.center_id
            LEFT JOIN city_master c1 ON c1.id = c.location_name
            WHERE is_deleted = 0 AND   b.inspector_id = 169  GROUP BY b.id  ORDER BY status   asc  LIMIT 20 ,10 
ERROR - 2025-12-30 10:37:29 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/InspectorHome.php 128
ERROR - 2025-12-30 10:37:33 --> Query error: Unknown column 'status' in 'order clause' - Invalid query: SELECT b.batch_code, COUNT(bi.id) as reported,GROUP_CONCAT(bi.overall_compliance) as overall_compliance_list, b.hours, c1.city_name, b.holidays,b.timing_from, b.timing_to, b.batch_from_date, b.total_candidates, b.training_medium, b.created_on, b.batch_status, b.id, b.agency_id, b.center_id, b.inspector_id,  b.batch_type, b.batch_online_offline_flag,  b.batch_name,  b.batch_to_date, b.batch_type, b.batch_online_offline_flag, b.batch_active_period
            FROM agency_batch b 
            LEFT JOIN dra_batch_inspection bi ON bi.batch_id = b.id AND bi.inspector_id = 169
            LEFT JOIN agency_center c ON b.center_id = c.center_id
            LEFT JOIN city_master c1 ON c1.id = c.location_name
            WHERE is_deleted = 0 AND   b.inspector_id = 169  GROUP BY b.id  ORDER BY status   asc  LIMIT 10 ,10 
ERROR - 2025-12-30 10:37:33 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/InspectorHome.php 128
ERROR - 2025-12-30 10:37:36 --> Query error: Unknown column 'status' in 'order clause' - Invalid query: SELECT b.batch_code, COUNT(bi.id) as reported,GROUP_CONCAT(bi.overall_compliance) as overall_compliance_list, b.hours, c1.city_name, b.holidays,b.timing_from, b.timing_to, b.batch_from_date, b.total_candidates, b.training_medium, b.created_on, b.batch_status, b.id, b.agency_id, b.center_id, b.inspector_id,  b.batch_type, b.batch_online_offline_flag,  b.batch_name,  b.batch_to_date, b.batch_type, b.batch_online_offline_flag, b.batch_active_period
            FROM agency_batch b 
            LEFT JOIN dra_batch_inspection bi ON bi.batch_id = b.id AND bi.inspector_id = 169
            LEFT JOIN agency_center c ON b.center_id = c.center_id
            LEFT JOIN city_master c1 ON c1.id = c.location_name
            WHERE is_deleted = 0 AND   b.inspector_id = 169  GROUP BY b.id  ORDER BY status   asc  LIMIT 20 ,10 
ERROR - 2025-12-30 10:37:36 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/InspectorHome.php 128
ERROR - 2025-12-30 10:37:42 --> Query error: Unknown column 'status' in 'order clause' - Invalid query: SELECT b.batch_code, COUNT(bi.id) as reported,GROUP_CONCAT(bi.overall_compliance) as overall_compliance_list, b.hours, c1.city_name, b.holidays,b.timing_from, b.timing_to, b.batch_from_date, b.total_candidates, b.training_medium, b.created_on, b.batch_status, b.id, b.agency_id, b.center_id, b.inspector_id,  b.batch_type, b.batch_online_offline_flag,  b.batch_name,  b.batch_to_date, b.batch_type, b.batch_online_offline_flag, b.batch_active_period
            FROM agency_batch b 
            LEFT JOIN dra_batch_inspection bi ON bi.batch_id = b.id AND bi.inspector_id = 169
            LEFT JOIN agency_center c ON b.center_id = c.center_id
            LEFT JOIN city_master c1 ON c1.id = c.location_name
            WHERE is_deleted = 0 AND   b.inspector_id = 169  GROUP BY b.id  ORDER BY status   desc  LIMIT 0 ,10 
ERROR - 2025-12-30 10:37:42 --> Severity: Error --> Call to a member function result_array() on boolean /home/supp0rttest/public_html/staging/application/controllers/iibfdra/Version_2/InspectorHome.php 128
ERROR - 2025-12-30 10:46:17 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-12-30 10:46:17 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-12-30 10:46:17 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-12-30 10:46:17 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-12-30 10:46:40 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-12-30 10:46:40 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-12-30 10:46:40 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-12-30 10:46:40 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-12-30 14:08:39 --> Query error: Column count doesn't match value count at row 1 - Invalid query: INSERT INTO dra_center_master(exam_name, exam_period, center_code, center_name, state_code, state_description, exammode) VALUES('1036','22','13','HYDERABAD','TEL','TELANGANA','ON','',''),('1036','22','18','KURNOOL','AND','ANDHRA PRADESH','ON','',''),('1036','22','29','RAJAHMUNDRY','AND','ANDHRA PRADESH','ON','',''),('1036','22','34','TIRUPATI','AND','ANDHRA PRADESH','ON','',''),('1036','22','35','VIJAYAWADA','AND','ANDHRA PRADESH','ON','',''),('1036','22','36','VISAKHAPATNAM','AND','ANDHRA PRADESH','ON','',''),('1036','22','37','VIZIANAGARAM','AND','ANDHRA PRADESH','ON','',''),('1036','22','48','GUWAHATI','ASS','ASSAM','ON','',''),('1036','22','73','DHANBAD','JHA','JHARKHAND','ON','',''),('1036','22','78','JAMSHEDPUR','JHA','JHARKHAND','ON','',''),('1036','22','80','KATIHAR','BIH','BIHAR','ON','',''),('1036','22','85','PATNA','BIH','BIHAR','ON','',''),('1036','22','87','RANCHI','JHA','JHARKHAND','ON','',''),('1036','22','93','DEOGHAR','JHA','JHARKHAND','ON','',''),('1036','22','98','AHMEDABAD','GUJ','GUJARAT','ON','',''),('1036','22','100','VADODARA','GUJ','GUJARAT','ON','',''),('1036','22','119','RAJKOT','GUJ','GUJARAT','ON','',''),('1036','22','120','SURAT','GUJ','GUJARAT','ON','',''),('1036','22','134','FARIDABAD','HAR','HARYANA','ON','',''),('1036','22','136','GURUGRAM','HAR','HARYANA','ON','',''),('1036','22','140','KARNAL','HAR','HARYANA','ON','',''),('1036','22','177','JAMMU','JAM','JAMMU & KASHMIR','ON','',''),('1036','22','182','SRINAGAR','JAM','JAMMU & KASHMIR','ON','',''),('1036','22','191','BENGALURU','KAR','KARNATAKA','ON','',''),('1036','22','193','BALLARI','KAR','KARNATAKA','ON','',''),('1036','22','195','BIJAPUR','KAR','KARNATAKA','ON','',''),('1036','22','207','HUBBALLI','KAR','KARNATAKA','ON','',''),('1036','22','214','MANGALURU','KAR','KARNATAKA','ON','',''),('1036','22','216','MYSURU','KAR','KARNATAKA','ON','',''),('1036','22','233','KOZHIKODE','KER','KERALA','ON','',''),('1036','22','236','ERNAKULAM','KER','KERALA','ON','',''),('1036','22','243','KOLLAM','KER','KERALA','ON','',''),('1036','22','257','BHOPAL','MAD','MADHYA PRADESH','ON','',''),('1036','22','258','BILASPUR CHH','CHH','CHHATTISGARH','ON','',''),('1036','22','266','GWALIOR','MAD','MADHYA PRADESH','ON','',''),('1036','22','268','INDORE','MAD','MADHYA PRADESH','ON','',''),('1036','22','269','JABALPUR','MAD','MADHYA PRADESH','ON','',''),('1036','22','279','RAIPUR','CHH','CHHATTISGARH','ON','',''),('1036','22','297','AKOLA','MAH','MAHARASHTRA','ON','',''),('1036','22','301','AURANGABAD','MAH','MAHARASHTRA','ON','',''),('1036','22','306','MUMBAI','MAH','MAHARASHTRA','ON','',''),('1036','22','314','KOLHAPUR','MAH','MAHARASHTRA','ON','',''),('1036','22','317','NAGPUR','MAH','MAHARASHTRA','ON','',''),('1036','22','319','NASHIK','MAH','MAHARASHTRA','ON','',''),('1036','22','322','PUNE','MAH','MAHARASHTRA','ON','',''),('1036','22','327','SOLAPUR','MAH','MAHARASHTRA','ON','',''),('1036','22','338','IMPHAL','MAN','MANIPUR','ON','',''),('1036','22','351','AIZAWL','MIZ','MIZORAM','ON','',''),('1036','22','370','BHUBANESHWAR','ORI','ODISHA','ON','',''),('1036','22','380','SAMBALPUR','ORI','ODISHA','ON','',''),('1036','22','387','AMRITSAR','PUN','PUNJAB','ON','',''),('1036','22','390','BHATINDA','PUN','PUNJAB','ON','',''),('1036','22','395','JALANDHAR','PUN','PUNJAB','ON','',''),('1036','22','397','LUDHIANA','PUN','PUNJAB','ON','',''),('1036','22','413','AJMER','RAJ','RAJASTHAN','ON','',''),('1036','22','418','BHILWARA','RAJ','RAJASTHAN','ON','',''),('1036','22','428','JAIPUR','RAJ','RAJASTHAN','ON','',''),('1036','22','433','JODHPUR','RAJ','RAJASTHAN','ON','',''),('1036','22','442','UDAIPUR','RAJ','RAJASTHAN','ON','',''),('1036','22','457','COIMBATORE','TAM','TAMIL NADU','ON','',''),('1036','22','467','CHENNAI','TAM','TAMIL NADU','ON','',''),('1036','22','468','MADURAI','TAM','TAMIL NADU','ON','',''),('1036','22','477','SALEM','TAM','TAMIL NADU','ON','',''),('1036','22','480','TIRUCHIRAPPALLI','TAM','TAMIL NADU','ON','',''),('1036','22','495','AGARTALA','TRI','TRIPURA','ON','',''),('1036','22','501','AGRA','UTT','UTTAR PRADESH','ON','',''),('1036','22','514','DEHRADUN','UTR','UTTARAKHAND','ON','',''),('1036','22','524','GORAKHPUR','UTT','UTTAR PRADESH','ON','',''),('1036','22','530','KANPUR','UTT','UTTAR PRADESH','ON','',''),('1036','22','533','LUCKNOW','UTT','UTTAR PRADESH','ON','',''),('1036','22','555','VARANASI','UTT','UTTAR PRADESH','ON','',''),('1036','22','568','KOLKATA','WES','WEST BENGAL','ON','',''),('1036','22','570','COOCH BEHAR','WES','WEST BENGAL','ON','',''),('1036','22','577','MALDA','WES','WEST BENGAL','ON','',''),('1036','22','582','SILIGURI','WES','WEST BENGAL','ON','',''),('1036','22','603','CHANDIGARH','CHA','CHANDIGARH','ON','',''),('1036','22','610','NEW DELHI','DEL','DELHI','ON','',''),('1036','22','619','PANAJI','GOA','GOA','ON','',''),('1036','22','708','GAYA','BIH','BIHAR','ON','',''),('1036','22','930','BIRBHUM BHOLPUR','WES','WEST BENGAL','ON','','');
ERROR - 2025-12-30 14:17:26 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-12-30 14:17:26 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-12-30 14:17:26 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-12-30 14:17:26 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-12-30 14:22:57 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-12-30 14:22:57 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-12-30 14:22:57 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-12-30 14:22:57 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-12-30 14:30:22 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-12-30 14:30:22 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-12-30 14:30:22 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-12-30 14:30:22 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-12-30 14:33:47 --> 404 Page Not Found: iibfdra/Version_2/TrainingBatches/favicon.ico
ERROR - 2025-12-30 14:38:15 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-12-30 14:38:15 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-12-30 14:38:15 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-12-30 14:38:16 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-12-30 14:39:16 --> 404 Page Not Found: iibfdra/Version_2/TrainingBatches/favicon.ico
ERROR - 2025-12-30 15:00:21 --> Severity: Warning --> mcrypt_generic_init(): Key size is 0 /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 83
ERROR - 2025-12-30 15:00:21 --> Severity: Warning --> mcrypt_generic_init(): Key length incorrect /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 83
ERROR - 2025-12-30 15:00:21 --> Severity: Warning --> mcrypt_generic(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 84
ERROR - 2025-12-30 15:00:21 --> Severity: Warning --> mcrypt_generic_deinit(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 87
ERROR - 2025-12-30 15:00:21 --> Severity: Warning --> mcrypt_module_close(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 88
ERROR - 2025-12-30 15:02:31 --> Severity: Warning --> mcrypt_generic_init(): Key size is 0 /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 83
ERROR - 2025-12-30 15:02:31 --> Severity: Warning --> mcrypt_generic_init(): Key length incorrect /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 83
ERROR - 2025-12-30 15:02:31 --> Severity: Warning --> mcrypt_generic(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 84
ERROR - 2025-12-30 15:02:31 --> Severity: Warning --> mcrypt_generic_deinit(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 87
ERROR - 2025-12-30 15:02:31 --> Severity: Warning --> mcrypt_module_close(): 6 is not a valid MCrypt resource /home/supp0rttest/public_html/staging/application/third_party/SBI_ePay/CryptAES.php 88
ERROR - 2025-12-30 16:22:17 --> 404 Page Not Found: TrainingBatches/export_to_pdf
ERROR - 2025-12-30 18:00:57 --> Severity: Warning --> imagepng(uploads/bulk_proforma_examinvoice/bulk_141.jpg): failed to open stream: No such file or directory /home/supp0rttest/public_html/staging/application/helpers/bulk_proforma_invoice_helper.php 226
ERROR - 2025-12-30 18:00:57 --> Severity: Warning --> imagepng(uploads/bulk_proforma_examinvoice/bulk_141.jpg): failed to open stream: No such file or directory /home/supp0rttest/public_html/staging/application/helpers/bulk_proforma_invoice_helper.php 230
ERROR - 2025-12-30 18:12:22 --> 404 Page Not Found: bulk/Login/index
ERROR - 2025-12-30 18:12:27 --> 404 Page Not Found: bulk/Login/index
ERROR - 2025-12-30 18:12:33 --> 404 Page Not Found: bulk/Login/index
ERROR - 2025-12-30 18:29:52 --> Severity: Warning --> imagepng(uploads/bulk_proforma_examinvoice/bulk_142.jpg): failed to open stream: No such file or directory /home/supp0rttest/public_html/staging/application/helpers/bulk_proforma_invoice_helper.php 226
ERROR - 2025-12-30 18:29:52 --> Severity: Warning --> imagepng(uploads/bulk_proforma_examinvoice/bulk_142.jpg): failed to open stream: No such file or directory /home/supp0rttest/public_html/staging/application/helpers/bulk_proforma_invoice_helper.php 230
ERROR - 2025-12-30 18:31:25 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-12-30 18:31:25 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-12-30 18:31:25 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-12-30 18:31:25 --> 404 Page Not Found: Uploads/iibfdra
ERROR - 2025-12-30 19:10:34 --> 404 Page Not Found: bulk/BulkTransaction/favicon.ico
