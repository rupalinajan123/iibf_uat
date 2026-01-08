<?php
defined("BASEPATH") || exit("No Direct Allowed Here");
/**
 * Function to create log.
 * @access public
 * @param String
 * @return String
 */

// by pawan invoice image genarate code

function contact_classes_word($amt)
{
    $number = $amt;
    $no = round($number);
    $point = round($number - $no, 2) * 100;
    $hundred = null;
    $digits_1 = strlen($no);
    $i = 0;
    $str = [];
    $words = [
        "0" => "",
        "1" => "One",
        "2" => "Two",
        "3" => "Three",
        "4" => "Four",
        "5" => "Five",
        "6" => "Six",
        "7" => "Seven",
        "8" => "Eight",
        "9" => "Nine",
        "10" => "Ten",
        "11" => "Eleven",
        "12" => "Twelve",
        "13" => "Thirteen",
        "14" => "Fourteen",
        "15" => "Fifteen",
        "16" => "Sixteen",
        "17" => "Seventeen",
        "18" => "Eighteen",
        "19" => "Nineteen",
        "20" => "Twenty",
        "30" => "Thirty",
        "40" => "Forty",
        "50" => "Fifty",
        "60" => "Sixty",
        "70" => "Seventy",
        "80" => "Eighty",
        "90" => "Ninety",
    ];
    $digits = ["", "Hundred", "Thousand", "Lakh", "Crore"];
    while ($i < $digits_1) {
        $divider = $i == 2 ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = ($counter = count($str)) && $number > 9 ? "s" : null;
            $hundred = $counter == 1 && $str[0] ? "and " : null;
            $str[] =
                $number < 21
                    ? $words[$number] .
                        " " .
                        $digits[$counter] .
                        $plural .
                        " " .
                        $hundred
                    : $words[floor($number / 10) * 10] .
                        " " .
                        $words[$number % 10] .
                        " " .
                        $digits[$counter] .
                        $plural .
                        " " .
                        $hundred;
        } else {
            $str[] = null;
        }
    }
    $str = array_reverse($str);
    $result = implode("", $str);
    $points = $point
        ? "." . $words[$point / 10] . " " . $words[($point = $point % 10)]
        : "";
    //echo $result . "Rupees  " . $points . " Paise";
    return $result;
}
function genarate_contact_classes_invoice_jk($invoice_no, $id)
{
    $CI = &get_instance();
    $invoice_info = $CI->master_model->getRecords("exam_invoice", [
        "invoice_id" => $invoice_no,
    ]);
    $mem_info = $CI->master_model->getRecords("contact_classes_registration", [
        "contact_classes_id" => $id,
    ]);
    $program_name = $CI->master_model->getRecords(
        "contact_classes_cource_master",
        ["course_code" => $CI->session->userdata["enduserinfo"]["cource_code"]]
    );

    //middle name
    if (isset($mem_info[0]["middlename"])) {
        $mname = $mem_info[0]["middlename"];
    } else {
        $mname = "";
    }
    //last name
    if (isset($mem_info[0]["lastname"])) {
        $lname = $mem_info[0]["lastname"];
    } else {
        $lname = "";
    }
    $member_name = $mem_info[0]["firstname"] . " " . $mname . " " . $lname;

    $wordamt = contact_classes_word($invoice_info[0]["igst_total"]);
    $date_of_invoice = date(
        "d-m-Y",
        strtotime($invoice_info[0]["date_of_invoice"])
    );
    $i = 0;
    foreach ($CI->session->userdata["enduserinfo"]["subjects"] as $val) {
        $array[$i]["subject_code"] = $val;
        $i++;
    }
    //subject 1
    if (isset($array[0]["subject_code"])) {
        $CI->db->distinct("sub_name");
        $sub_1 = $CI->master_model->getRecords(
            "contact_classes_subject_master",
            [
                "course_code" =>
                    $CI->session->userdata["enduserinfo"]["cource_code"],
                "sub_code" => $array[0]["subject_code"],
            ],
            "sub_name"
        );
    }
    //subject 2
    if (isset($array[1]["subject_code"])) {
        $CI->db->distinct("sub_name");
        $sub_2 = $CI->master_model->getRecords(
            "contact_classes_subject_master",
            [
                "course_code" =>
                    $CI->session->userdata["enduserinfo"]["cource_code"],
                "sub_code" => $array[1]["subject_code"],
            ],
            "sub_name"
        );
    }
    //subject 3
    if (isset($array[2]["subject_code"])) {
        $CI->db->distinct("sub_name");
        $sub_3 = $CI->master_model->getRecords(
            "contact_classes_subject_master",
            [
                "course_code" =>
                    $CI->session->userdata["enduserinfo"]["cource_code"],
                "sub_code" => $array[2]["subject_code"],
            ],
            "sub_name"
        );
    }
    /****************************** image for user ***********************************/

    ($im = @imagecreate(1000, 1000)) or
        die("Cannot Initialize new GD image stream");
    $background_color = imagecolorallocate($im, 255, 255, 255); // white

    $black = imagecolorallocate($im, 0, 0, 0); // black

    imageline($im, 20, 20, 980, 20, $black); // line-1
    imageline($im, 20, 980, 980, 980, $black); // line-2
    imageline($im, 20, 20, 20, 980, $black); // line-3
    imageline($im, 980, 20, 980, 980, $black); // line-4
    imageline($im, 20, 60, 980, 60, $black); // line-5
    imageline($im, 20, 100, 980, 100, $black); // line-6
    imageline($im, 20, 250, 980, 250, $black); // line-7
    imageline($im, 20, 400, 980, 400, $black); // line-8
    imageline($im, 20, 450, 980, 450, $black); // line-9
    imageline($im, 20, 550, 980, 550, $black); // line-10
    imageline($im, 20, 800, 980, 800, $black); // line-11
    imageline($im, 490, 100, 490, 400, $black); // line-12
    imageline($im, 80, 450, 80, 800, $black); // line-13
    imageline($im, 560, 450, 560, 800, $black); // line-14
    imageline($im, 660, 450, 660, 800, $black); // line-15
    imageline($im, 760, 450, 760, 800, $black); // line-16
    imageline($im, 860, 450, 860, 800, $black); // line-17
    imageline($im, 20, 835, 490, 835, $black); // line-18
    imageline($im, 20, 770, 980, 770, $black); // line-19

    //imagestring ($im, size, X,  Y, text, $red);
    $year = date("Y");

    imagestring($im, 5, 455, 30, "Bill Of Supply - Services", $black);

    imagestring(
        $im,
        3,
        22,
        100,
        "Name of the Assessee: INDIAN INSTITUTE OF BANKING & FINANCE",
        $black
    );
    imagestring(
        $im,
        3,
        22,
        112,
        "GSTIN: " . $CI->session->userdata["enduserinfo"]["zone_gstin_no"],
        $black
    );
    imagestring($im, 3, 22, 124, "Address: ", $black);
    imagestring(
        $im,
        3,
        22,
        136,
        $CI->session->userdata["invoice_info"]["zone_address1"],
        $black
    );
    imagestring(
        $im,
        3,
        22,
        148,
        $CI->session->userdata["invoice_info"]["zone_address2"],
        $black
    );
    imagestring(
        $im,
        3,
        22,
        160,
        $CI->session->userdata["invoice_info"]["zone_address3"] .
            $CI->session->userdata["invoice_info"]["zone_address4"],
        $black
    );

    imagestring(
        $im,
        3,
        22,
        172,
        "State : " . $CI->session->userdata["invoice_info"]["zone_state_name"],
        $black
    );
    imagestring(
        $im,
        3,
        22,
        184,
        "State Code : " .
            $CI->session->userdata["invoice_info"]["zone_state_code"],
        $black
    );
    imagestring(
        $im,
        3,
        22,
        196,
        "Invoice No : " . $invoice_info[0]["invoice_no"],
        $black
    );
    imagestring(
        $im,
        3,
        22,
        208,
        "Date Of Invoice : " . $date_of_invoice,
        $black
    );
    imagestring(
        $im,
        3,
        22,
        220,
        "Transaction no : " . $invoice_info[0]["transaction_no"],
        $black
    );
    imagestring(
        $im,
        3,
        22,
        232,
        "Course name : " . $program_name[0]["course_name"],
        $black
    );
    imagestring($im, 3, 22, 244, "", $black);
    imagestring($im, 3, 800, 100, "ORIGINAL FOR RECIPIENT ", $black);

    imagestring($im, 3, 22, 250, "Details of service recipient", $black);
    imagestring(
        $im,
        3,
        22,
        262,
        "Member no. : " . $invoice_info[0]["member_no"],
        $black
    );
    imagestring($im, 3, 22, 274, "Member name : " . $member_name, $black);
    imagestring(
        $im,
        3,
        22,
        286,
        "Center code : " . $invoice_info[0]["center_code"],
        $black
    );
    imagestring(
        $im,
        3,
        22,
        298,
        "Center name : " . $invoice_info[0]["center_name"],
        $black
    );
    imagestring(
        $im,
        3,
        22,
        310,
        "State  : " . $invoice_info[0]["state_name"],
        $black
    );
    imagestring(
        $im,
        3,
        22,
        322,
        "State code : " . $invoice_info[0]["state_code"],
        $black
    );
    imagestring($im, 3, 22, 334, "GSTIN / Unique Id : NA", $black);

    imagestring($im, 3, 22, 530, "Sr.No", $black);
    imagestring($im, 3, 100, 530, "Description of goods & Service", $black);
    imagestring($im, 3, 570, 508, "Accounting ", $black);
    imagestring($im, 3, 570, 520, "code", $black);
    imagestring($im, 3, 570, 532, "of Service", $black);
    imagestring($im, 3, 665, 530, "Rate per unit", $black);
    imagestring($im, 3, 780, 530, "Unit", $black);
    imagestring($im, 3, 900, 530, "Total(Rs.)", $black);

    if (!empty($sub_1)) {
        imagestring($im, 3, 45, 560, "1", $black);
        imagestring($im, 3, 100, 560, $sub_1[0]["sub_name"], $black);
        imagestring($im, 3, 590, 560, $invoice_info[0]["service_code"], $black);
        imagestring(
            $im,
            3,
            690,
            560,
            $CI->session->userdata["invoice_info"]["sub_fee1"] . ".00",
            $black
        );
        imagestring($im, 3, 780, 560, $invoice_info[0]["qty"], $black);
        imagestring(
            $im,
            3,
            900,
            560,
            $CI->session->userdata["invoice_info"]["sub_fee1"] . ".00",
            $black
        );
    }
    if (!empty($sub_2)) {
        imagestring($im, 3, 45, 572, "2", $black);
        imagestring($im, 3, 100, 572, $sub_2[0]["sub_name"], $black);
        imagestring($im, 3, 590, 572, $invoice_info[0]["service_code"], $black);
        imagestring(
            $im,
            3,
            690,
            572,
            $CI->session->userdata["invoice_info"]["sub_fee2"] . ".00",
            $black
        );
        imagestring($im, 3, 780, 572, $invoice_info[0]["qty"], $black);
        imagestring(
            $im,
            3,
            900,
            572,
            $CI->session->userdata["invoice_info"]["sub_fee2"] . ".00",
            $black
        );
    }
    if (!empty($sub_3)) {
        imagestring($im, 3, 45, 584, "3", $black);
        imagestring($im, 3, 100, 584, $sub_3[0]["sub_name"], $black);
        imagestring($im, 3, 590, 584, $invoice_info[0]["service_code"], $black);
        imagestring(
            $im,
            3,
            690,
            584,
            $CI->session->userdata["invoice_info"]["sub_fee3"] . ".00",
            $black
        );
        imagestring($im, 3, 780, 584, $invoice_info[0]["qty"], $black);
        imagestring(
            $im,
            3,
            900,
            584,
            $CI->session->userdata["invoice_info"]["sub_fee3"] . ".00",
            $black
        );
    }

    imagestring($im, 3, 500, 780, "Total(Rs.)", $black);
    imagestring($im, 3, 900, 780, $invoice_info[0]["igst_total"], $black);

    imagestring(
        $im,
        3,
        22,
        820,
        "Amount in words : " . $wordamt . " only",
        $black
    );
    imagestring(
        $im,
        3,
        650,
        880,
        "For Indian Institute of Banking & Finance",
        $black
    );
    imagestring($im, 3, 720, 910, "Authorised Signatory", $black);
    imagestring($im, 3, 22, 900, "Reverse charge applicable :", $black);
    imagestring($im, 3, 300, 900, "Y/N", $black);
    imagestring($im, 3, 350, 900, "NO", $black);
    //imagestring($im, 3, 700,  900,   "echo<img src=Face.png>", $black);
    imagestring($im, 3, 22, 920, "% of Tax payable under", $black);
    imagestring($im, 3, 22, 932, "Charge by recepient :", $black);
    imagestring($im, 3, 300, 932, "% ---", $black);
    imagestring($im, 3, 350, 932, "Rs.---", $black);

    if ($CI->session->userdata["invoice_info"]["zone_code"] == "CO") {
        $savepath = base_url() . "uploads/contact_classes_invoice/user/CO/";
        $ino = str_replace("/", "_", $invoice_info[0]["invoice_no"]);
        $imagename = $ino . ".jpg";

        $update_data = ["invoice_image" => $imagename];
        $CI->master_model->updateRecord("exam_invoice", $update_data, [
            "invoice_id" => $invoice_no,
        ]);

        imagepng($im, "uploads/contact_classes_invoice/user/CO/" . $imagename);
        $png = @imagecreatefromjpeg("assets/images/sign.jpg");
        $jpeg = @imagecreatefromjpeg(
            "uploads/contact_classes_invoice/user/CO" . $imagename
        );
        @imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
        imagepng($im, "uploads/contact_classes_invoice/user/CO/" . $imagename);

        imagedestroy($im);
    } elseif ($CI->session->userdata["invoice_info"]["zone_code"] == "NZ") {
        $savepath = base_url() . "uploads/contact_classes_invoice/user/NZ/";
        $ino = str_replace("/", "_", $invoice_info[0]["invoice_no"]);
        $imagename = $ino . ".jpg";

        $update_data = ["invoice_image" => $imagename];
        $CI->master_model->updateRecord("exam_invoice", $update_data, [
            "invoice_id" => $invoice_no,
        ]);

        imagepng($im, "uploads/contact_classes_invoice/user/NZ/" . $imagename);
        $png = @imagecreatefromjpeg("assets/images/sign.jpg");
        $jpeg = @imagecreatefromjpeg(
            "uploads/contact_classes_invoice/user/NZ" . $imagename
        );
        @imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
        imagepng($im, "uploads/contact_classes_invoice/user/NZ/" . $imagename);

        imagedestroy($im);
    } elseif ($CI->session->userdata["invoice_info"]["zone_code"] == "EZ") {
        $savepath = base_url() . "uploads/contact_classes_invoice/user/EZ/";
        $ino = str_replace("/", "_", $invoice_info[0]["invoice_no"]);
        $imagename = $ino . ".jpg";

        $update_data = ["invoice_image" => $imagename];
        $CI->master_model->updateRecord("exam_invoice", $update_data, [
            "invoice_id" => $invoice_no,
        ]);

        imagepng($im, "uploads/contact_classes_invoice/user/EZ/" . $imagename);
        $png = @imagecreatefromjpeg("assets/images/sign.jpg");
        $jpeg = @imagecreatefromjpeg(
            "uploads/contact_classes_invoice/user/EZ" . $imagename
        );
        @imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
        imagepng($im, "uploads/contact_classes_invoice/user/EZ/" . $imagename);

        imagedestroy($im);
    } elseif ($CI->session->userdata["invoice_info"]["zone_code"] == "SZ") {
        $savepath = base_url() . "uploads/contact_classes_invoice/user/SZ/";
        $ino = str_replace("/", "_", $invoice_info[0]["invoice_no"]);
        $imagename = $ino . ".jpg";

        $update_data = ["invoice_image" => $imagename];
        $CI->master_model->updateRecord("exam_invoice", $update_data, [
            "invoice_id" => $invoice_no,
        ]);

        imagepng($im, "uploads/contact_classes_invoice/user/SZ/" . $imagename);
        $png = @imagecreatefromjpeg("assets/images/sign.jpg");
        $jpeg = @imagecreatefromjpeg(
            "uploads/contact_classes_invoice/user/SZ" . $imagename
        );
        @imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
        imagepng($im, "uploads/contact_classes_invoice/user/SZ/" . $imagename);

        imagedestroy($im);
    }
    /****************************** image for supplier ***********************************/

    ($im = @imagecreate(1000, 1000)) or
        die("Cannot Initialize new GD image stream");
    $background_color = imagecolorallocate($im, 255, 255, 255); // white

    $black = imagecolorallocate($im, 0, 0, 0); // black

    imageline($im, 20, 20, 980, 20, $black); // line-1
    imageline($im, 20, 980, 980, 980, $black); // line-2
    imageline($im, 20, 20, 20, 980, $black); // line-3
    imageline($im, 980, 20, 980, 980, $black); // line-4
    imageline($im, 20, 60, 980, 60, $black); // line-5
    imageline($im, 20, 100, 980, 100, $black); // line-6
    imageline($im, 20, 250, 980, 250, $black); // line-7
    imageline($im, 20, 400, 980, 400, $black); // line-8
    imageline($im, 20, 450, 980, 450, $black); // line-9
    imageline($im, 20, 550, 980, 550, $black); // line-10
    imageline($im, 20, 800, 980, 800, $black); // line-11
    imageline($im, 490, 100, 490, 400, $black); // line-12
    imageline($im, 80, 450, 80, 800, $black); // line-13
    imageline($im, 560, 450, 560, 800, $black); // line-14
    imageline($im, 660, 450, 660, 800, $black); // line-15
    imageline($im, 760, 450, 760, 800, $black); // line-16
    imageline($im, 860, 450, 860, 800, $black); // line-17
    imageline($im, 20, 835, 490, 835, $black); // line-18
    imageline($im, 20, 770, 980, 770, $black); // line-19

    //imagestring ($im, size, X,  Y, text, $red);
    $year = date("Y");

    imagestring($im, 5, 455, 30, "Bill Of Supply - Services", $black);

    imagestring(
        $im,
        3,
        22,
        100,
        "Name of the Assessee: INDIAN INSTITUTE OF BANKING & FINANCE",
        $black
    );
    imagestring(
        $im,
        3,
        22,
        112,
        "GSTIN: " . $CI->session->userdata["enduserinfo"]["zone_gstin_no"],
        $black
    );
    imagestring($im, 3, 22, 124, "Address: ", $black);
    imagestring(
        $im,
        3,
        22,
        136,
        $CI->session->userdata["invoice_info"]["zone_address1"],
        $black
    );
    imagestring(
        $im,
        3,
        22,
        148,
        $CI->session->userdata["invoice_info"]["zone_address2"],
        $black
    );
    imagestring(
        $im,
        3,
        22,
        160,
        $CI->session->userdata["invoice_info"]["zone_address3"] .
            $CI->session->userdata["invoice_info"]["zone_address4"],
        $black
    );

    imagestring(
        $im,
        3,
        22,
        172,
        "State : " . $CI->session->userdata["invoice_info"]["zone_state_name"],
        $black
    );
    imagestring(
        $im,
        3,
        22,
        184,
        "State Code : " .
            $CI->session->userdata["invoice_info"]["zone_state_code"],
        $black
    );
    imagestring(
        $im,
        3,
        22,
        196,
        "Invoice No : " . $invoice_info[0]["invoice_no"],
        $black
    );
    imagestring(
        $im,
        3,
        22,
        208,
        "Date Of Invoice : " . $date_of_invoice,
        $black
    );
    imagestring(
        $im,
        3,
        22,
        220,
        "Transaction no : " . $invoice_info[0]["transaction_no"],
        $black
    );
    imagestring(
        $im,
        3,
        22,
        232,
        "Course name : " . $program_name[0]["course_name"],
        $black
    );
    imagestring($im, 3, 22, 244, "", $black);

    imagestring($im, 3, 800, 100, "DUPLICATE FOR SUPPLIER ", $black);

    imagestring($im, 3, 22, 250, "Details of service recipient", $black);
    imagestring(
        $im,
        3,
        22,
        262,
        "Member no. : " . $invoice_info[0]["member_no"],
        $black
    );
    imagestring($im, 3, 22, 274, "Member name : " . $member_name, $black);
    imagestring(
        $im,
        3,
        22,
        286,
        "Center code : " . $invoice_info[0]["center_code"],
        $black
    );
    imagestring(
        $im,
        3,
        22,
        298,
        "Center name : " . $invoice_info[0]["center_name"],
        $black
    );
    imagestring(
        $im,
        3,
        22,
        310,
        "State  : " . $invoice_info[0]["state_name"],
        $black
    );
    imagestring(
        $im,
        3,
        22,
        322,
        "State code : " . $invoice_info[0]["state_code"],
        $black
    );
    imagestring($im, 3, 22, 334, "GSTIN / Unique Id : NA", $black);

    imagestring($im, 3, 22, 530, "Sr.No", $black);
    imagestring($im, 3, 100, 530, "Description of goods & Service", $black);
    imagestring($im, 3, 570, 508, "Accounting ", $black);
    imagestring($im, 3, 570, 520, "code", $black);
    imagestring($im, 3, 570, 532, "of Service", $black);
    imagestring($im, 3, 665, 530, "Rate per unit", $black);
    imagestring($im, 3, 780, 530, "Unit", $black);
    imagestring($im, 3, 900, 530, "Total", $black);

    if (!empty($sub_1)) {
        imagestring($im, 3, 45, 560, "1", $black);
        imagestring($im, 3, 100, 560, $sub_1[0]["sub_name"], $black);
        imagestring($im, 3, 590, 560, $invoice_info[0]["service_code"], $black);
        imagestring(
            $im,
            3,
            690,
            560,
            $CI->session->userdata["invoice_info"]["sub_fee1"] . ".00",
            $black
        );
        imagestring($im, 3, 780, 560, $invoice_info[0]["qty"], $black);
        imagestring(
            $im,
            3,
            900,
            560,
            $CI->session->userdata["invoice_info"]["sub_fee1"] . ".00",
            $black
        );
    }
    if (!empty($sub_2)) {
        imagestring($im, 3, 45, 572, "2", $black);
        imagestring($im, 3, 100, 572, $sub_2[0]["sub_name"], $black);
        imagestring($im, 3, 590, 572, $invoice_info[0]["service_code"], $black);
        imagestring(
            $im,
            3,
            690,
            572,
            $CI->session->userdata["invoice_info"]["sub_fee2"] . ".00",
            $black
        );
        imagestring($im, 3, 780, 572, $invoice_info[0]["qty"], $black);
        imagestring(
            $im,
            3,
            900,
            572,
            $CI->session->userdata["invoice_info"]["sub_fee2"] . ".00",
            $black
        );
    }
    if (!empty($sub_3)) {
        imagestring($im, 3, 45, 584, "3", $black);
        imagestring($im, 3, 100, 584, $sub_3[0]["sub_name"], $black);
        imagestring($im, 3, 590, 584, $invoice_info[0]["service_code"], $black);
        imagestring(
            $im,
            3,
            690,
            584,
            $CI->session->userdata["invoice_info"]["sub_fee3"] . ".00",
            $black
        );
        imagestring($im, 3, 780, 584, $invoice_info[0]["qty"], $black);
        imagestring(
            $im,
            3,
            900,
            584,
            $CI->session->userdata["invoice_info"]["sub_fee3"] . ".00",
            $black
        );
    }

    imagestring($im, 3, 500, 780, "Total(Rs.)", $black);
    imagestring($im, 3, 900, 780, $invoice_info[0]["igst_total"], $black);

    imagestring(
        $im,
        3,
        22,
        820,
        "Amount in words : " . $wordamt . " only",
        $black
    );
    imagestring(
        $im,
        3,
        650,
        880,
        "For Indian Institute of Banking & Finance",
        $black
    );
    imagestring($im, 3, 720, 910, "Authorised Signatory", $black);
    imagestring($im, 3, 22, 900, "Reverse charge applicable :", $black);
    imagestring($im, 3, 300, 900, "Y/N", $black);
    imagestring($im, 3, 350, 900, "NO", $black);
    //imagestring($im, 3, 700,  900,   "echo<img src=Face.png>", $black);
    imagestring($im, 3, 22, 920, "% of Tax payable under", $black);
    imagestring($im, 3, 22, 932, "Charge by recepient :", $black);
    imagestring($im, 3, 300, 932, "% ---", $black);
    imagestring($im, 3, 350, 932, "Rs.---", $black);

    if ($CI->session->userdata["invoice_info"]["zone_code"] == "CO") {
        $savepath = base_url() . "uploads/contact_classes_invoice/supplier/CO";
        $ino = str_replace("/", "_", $invoice_info[0]["invoice_no"]);
        $imagename = $ino . ".jpg";

        imagepng(
            $im,
            "uploads/contact_classes_invoice/supplier/CO/" . $imagename
        );
        $png = @imagecreatefromjpeg("assets/images/sign.jpg");
        $jpeg = @imagecreatefromjpeg(
            "uploads/contact_classes_invoice/supplier/CO/" . $imagename
        );
        @imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
        imagepng(
            $im,
            "uploads/contact_classes_invoice/supplier/CO/" . $imagename
        );

        imagedestroy($im);

        return $attachpath =
            "uploads/contact_classes_invoice/user/CO/" . $imagename;
    } elseif ($CI->session->userdata["invoice_info"]["zone_code"] == "NZ") {
        $savepath = base_url() . "uploads/contact_classes_invoice/supplier/NZ";
        $ino = str_replace("/", "_", $invoice_info[0]["invoice_no"]);
        $imagename = $ino . ".jpg";

        imagepng(
            $im,
            "uploads/contact_classes_invoice/supplier/NZ/" . $imagename
        );
        $png = @imagecreatefromjpeg("assets/images/sign.jpg");
        $jpeg = @imagecreatefromjpeg(
            "uploads/contact_classes_invoice/supplier/NZ/" . $imagename
        );
        @imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
        imagepng(
            $im,
            "uploads/contact_classes_invoice/supplier/NZ/" . $imagename
        );

        imagedestroy($im);

        return $attachpath =
            "uploads/contact_classes_invoice/supplier/NZ/" . $imagename;
    } elseif ($CI->session->userdata["invoice_info"]["zone_code"] == "EZ") {
        $savepath = base_url() . "uploads/contact_classes_invoice/user/EZ";
        $ino = str_replace("/", "_", $invoice_info[0]["invoice_no"]);
        $imagename = $ino . ".jpg";

        imagepng(
            $im,
            "uploads/contact_classes_invoice/supplier/EZ/" . $imagename
        );
        $png = @imagecreatefromjpeg("assets/images/sign.jpg");
        $jpeg = @imagecreatefromjpeg(
            "uploads/contact_classes_invoice/supplier/EZ/" . $imagename
        );
        @imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
        imagepng(
            $im,
            "uploads/contact_classes_invoice/supplier/EZ/" . $imagename
        );

        imagedestroy($im);

        return $attachpath =
            "uploads/contact_classes_invoice/supplier/EZ/" . $imagename;
    } elseif ($CI->session->userdata["invoice_info"]["zone_code"] == "SZ") {
        $savepath = base_url() . "uploads/contact_classes_invoice/user/SZ";
        $ino = str_replace("/", "_", $invoice_info[0]["invoice_no"]);
        $imagename = $ino . ".jpg";

        imagepng(
            $im,
            "uploads/contact_classes_invoice/supplier/SZ/" . $imagename
        );
        $png = @imagecreatefromjpeg("assets/images/sign.jpg");
        $jpeg = @imagecreatefromjpeg(
            "uploads/contact_classes_invoice/supplier/SZ/" . $imagename
        );
        @imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
        imagepng(
            $im,
            "uploads/contact_classes_invoice/supplier/SZ/" . $imagename
        );

        imagedestroy($im);

        return $attachpath =
            "uploads/contact_classes_invoice/user/SZ/" . $imagename;
    }
}

function genarate_contact_classes_invoice_cs2s(
    $invoice_no,
    $id,
    $cource_code,
    $sub_cd,
    $zone_code
) {
    // echo "IN helper"; print_r($sub_cd);die;
    //$invoice_no =   ;
    $CI = &get_instance();
    $invoice_info = $CI->master_model->getRecords("exam_invoice", [
        "invoice_id" => $invoice_no,
    ]);

    $state_info = $CI->master_model->getRecords("state_master", [
        "state_no" => $invoice_info[0]['state_code'],
    ]);

    $mem_info = $CI->master_model->getRecords("contact_classes_registration", [
        "contact_classes_id" => $id,
    ]);
    $program_name = $CI->master_model->getRecords(
        "contact_classes_cource_master",
        ["course_code" => $cource_code]
    );
    // $sub_reg = $CI->master_model->getRecords(
    //     "contact_classes_Subject_registration",
    //     [
    //         "member_no" => $invoice_info[0]["member_no"],
    //         "program_code" => $invoice_info[0]["exam_code"],
    //     ]
    // );
    // $subjects_name = $CI->master_model->getRecords(
    //     "contact_classes_subject_master",
    //     [
    //         "course_code" => $invoice_info[0]["exam_code"],
    //         "exam_prd" => $invoice_info[0]["exam_period"],
    //     ]
    // );

    //middle name
    if (isset($mem_info[0]["middlename"])) {
        $mname = $mem_info[0]["middlename"];
    } else {
        $mname = "";
    }
    //last name
    if (isset($mem_info[0]["lastname"])) {
        $lname = $mem_info[0]["lastname"];
    } else {
        $lname = "";
    }
    $member_name = $mem_info[0]["firstname"] . " " . $mname . " " . $lname;

    //address
    $address_1 = $address_2 = "";
    if (isset($mem_info[0]["address1"])) {
        $address_1 = $mem_info[0]["address1"] . $mem_info[0]["address2"];
    }
    if (isset($mem_info[0]["address2"])) {
        $address_2 = $mem_info[0]["address3"] . $mem_info[0]["address4"];
    }
    /*if($invoice_info[0]['state_of_center'] == 'JAM'){
		return genarate_contact_classes_invoice_jk($invoice_no);
		exit;
	}*/

    $date_of_invoice = date(
        "d-m-Y",
        strtotime($invoice_info[0]["date_of_invoice"])
    );
    $i = 0;
    foreach ($sub_cd as $val) {
        $array[$i]["subject_code"] = $val;
        $i++;
    }

    //subject 1
    if (isset($array[0]["subject_code"])) {
        $CI->db->distinct("sub_name");
        $sub_1 = $CI->master_model->getRecords(
            "contact_classes_subject_master",
            [
                "course_code" => $cource_code,
                "sub_code" => $array[0]["subject_code"],
            ],
            "sub_name"
        );
        //echo "<br/>".$CI->db->last_query();
        //print_r($sub_1);

        $sub_fee_1 = $CI->master_model->getRecords(
            "contact_classes_fee_master",
            [
                "course_code" => $cource_code,
                "sub_code" => $array[0]["subject_code"],
            ]
        );
        //echo "<br/>".$CI->db->last_query();
        //print_r($sub_fee_1);
        //echo $sub_fee_1[0]['fee'];
        //exit;
    }
    //subject 2
    if (isset($array[1]["subject_code"])) {
        $CI->db->distinct("sub_name");
        $sub_2 = $CI->master_model->getRecords(
            "contact_classes_subject_master",
            [
                "course_code" => $cource_code,
                "sub_code" => $array[1]["subject_code"],
            ],
            "sub_name"
        );

        $sub_fee_2 = $CI->master_model->getRecords(
            "contact_classes_fee_master",
            [
                "course_code" => $cource_code,
                "sub_code" => $array[1]["subject_code"],
            ]
        );
    }
    //subject 3
    if (isset($array[2]["subject_code"])) {
        $CI->db->distinct("sub_name");
        $sub_3 = $CI->master_model->getRecords(
            "contact_classes_subject_master",
            [
                "course_code" => $cource_code,
                "sub_code" => $array[2]["subject_code"],
            ],
            "sub_name"
        );

        $sub_fee_3 = $CI->master_model->getRecords(
            "contact_classes_fee_master",
            [
                "course_code" => $cource_code,
                "sub_code" => $array[2]["subject_code"],
            ]
        );
    }

    // Added by Pooja Mane to display 4 subject on invoice - 23-04-2024 //
    //subject 4
    if (isset($array[3]["subject_code"])) {
        $CI->db->distinct("sub_name");
        $sub_4 = $CI->master_model->getRecords(
            "contact_classes_subject_master",
            [
                "course_code" => $cource_code,
                "sub_code" => $array[3]["subject_code"],
            ],
            "sub_name"
        );

        $sub_fee_4 = $CI->master_model->getRecords(
            "contact_classes_fee_master",
            [
                "course_code" => $cource_code,
                "sub_code" => $array[3]["subject_code"],
            ]
        );
    }
    // Added by Pooja Mane to display 4 subject on invoice - 23-04-2024 //
    /****************************** image for user ***********************************/
    // create image for recipeint
    //imagecreate(width, height);
    ($im = @imagecreate(1000, 1000)) or
        die("Cannot Initialize new GD image stream");
    $background_color = imagecolorallocate($im, 255, 255, 255); // white
    $black = imagecolorallocate($im, 0, 0, 0); // black

    //imageline ($im,   x1,  y1, x2, y2, color);
    imageline($im, 20, 20, 980, 20, $black); // line-1
    imageline($im, 20, 980, 980, 980, $black); // line-2
    imageline($im, 20, 20, 20, 980, $black); // line-3
    imageline($im, 980, 20, 980, 980, $black); // line-4
    imageline($im, 20, 160, 980, 160, $black); // line-5
    imageline($im, 20, 200, 980, 200, $black); // line-6
    imageline($im, 20, 480, 980, 480, $black); // line-7
    imageline($im, 20, 520, 980, 520, $black); // line-8
    imageline($im, 20, 580, 980, 580, $black); // line-9
    imageline($im, 20, 850, 980, 850, $black); // line-10
    imageline($im, 650, 200, 650, 480, $black); // line-11
    imageline($im, 85, 520, 85, 850, $black); // line-12
    imageline($im, 500, 520, 500, 850, $black); // line-13
    imageline($im, 650, 520, 650, 850, $black); // line-14
    imageline($im, 785, 520, 785, 850, $black); // line-15
    imageline($im, 860, 520, 860, 850, $black); // line-16
    imageline($im, 40, 880, 625, 880, $black); // line-17

    $year = date("Y");
    //imagestring(image,font,x,y,string,color); imagestring( $im, 5, 100, 40, "INDIAN INSTITUTE OF BANKING & FINANCE", $black );
    imagestring($im, 3, 100, 60, "ISO 21001:2018 Certified", $black);
    imagestring($im, 3, 100, 80, "(CINU9111OMH1928GAP1391)", $black); imagestring( $im, 3, 100, 100, "Registered office Kohinoor City, Commercial - II,  Tower 1, 2nd Floor, Kirole Road,", $black );
    imagestring( $im, 3, 100, 120, "Off LBS Marg, Kurla(West), Mumbai - 400 070 , Maharashtra", $black );
    imagestring($im, 3, 100, 140, "www.iibf.org.in", $black);
    imagestring($im, 5, 400, 170, "TAX INVOICE CUM RECEIPT", $black);

    imagestring($im, 5, 40, 220, "Details of service recipient", $black);
    imagestring($im, 5, 670, 220, "ORIGINAL FOR RECIPIENT", $black);
    imagestring($im, 3, 40, 260, "Name of service Recipient:" . $member_name, $black );
    imagestring($im, 3, 40, 280, "Address: " . $address_1, $black);
    imagestring($im, 3, 40, 300, $address_2, $black);
    imagestring($im, 3, 40, 320, "State: " . $invoice_info[0]["state_name"], $black );
    imagestring($im, 3, 40, 340, "State Code: " . $invoice_info[0]["state_code"], $black );
    imagestring($im, 3, 40, 360, "GST No: " . $invoice_info[0]["gstin_no"], $black );
    imagestring($im, 3, 40, 380, "Transaction Number : " . $invoice_info[0]["transaction_no"], $black );

    imagestring($im, 3, 40, 400, "Course name : " . $program_name[0]["course_name"], $black );
    imagestring($im, 3, 40, 420, "Center code : " . $invoice_info[0]["center_code"], $black );
    imagestring($im, 3, 40, 440, "Center name : " . $invoice_info[0]["center_name"], $black );

    imagestring($im, 3, 670, 260, "Invoice Number: " . $invoice_info[0]["invoice_no"], $black );
    imagestring($im, 3, 670, 280, "Date: " . date("d-m-Y", strtotime($invoice_info[0]["date_of_invoice"])), $black );
    if (
        $invoice_info[0]["gstin_no"] != "" &&
        $invoice_info[0]["gstin_no"] != 0
    ) {
        $gstn = $invoice_info[0]["gstin_no"];
    } else {
        $gstn = "-";
    }
    imagestring($im, 3, 670, 300, "GSTIN - 27AAATT3309D1ZS ", $black);

    imagestring($im, 3, 40, 530, "Sr.No", $black);
    imagestring($im, 3, 118, 530, "Description of Service", $black);
    imagestring($im, 3, 535, 530, "Accounting ", $black);
    imagestring($im, 3, 535, 542, "code", $black);
    imagestring($im, 3, 535, 554, "of Service", $black);
    imagestring($im, 3, 660, 530, "Rate per unit(Rs)", $black);
    imagestring($im, 3, 808, 530, "Unit", $black);
    imagestring($im, 3, 900, 530, "Total", $black);

    //imagestring($im, 3, 118,  596, "Charges Towards Training Program", $black);
    //imagestring($im, 3, 535,  596, $invoice_info[0]['service_code'], $black);
    //imagestring($im, 3, 700,  596, $invoice_info[0]['fee_amt'], $black);
    //imagestring($im, 3, 815,  596, $invoice_info[0]['qty'], $black);
    //imagestring($im, 3, 900,  596, $invoice_info[0]['fee_amt'], $black);
    //imagestring($im, 3, 535,  820, "Total", $black);

    if (!empty($sub_1)) {
        imagestring($im, 3, 60, 596, "1", $black);
        imagestring($im, 3, 118, 596, $sub_1[0]["sub_name"], $black);
        imagestring($im, 3, 535, 596, $invoice_info[0]["service_code"], $black);
        imagestring($im, 3, 690, 596, $sub_fee_1[0]["igst_tot"] . ".00", $black );
        imagestring($im, 3, 815, 596, $invoice_info[0]["qty"], $black);
        imagestring($im, 3, 900, 596, $sub_fee_1[0]["fee"] . ".00", $black);
    }
    if (!empty($sub_2)) {
        imagestring($im, 3, 60, 616, "2", $black);
        imagestring($im, 3, 118, 616, $sub_2[0]["sub_name"], $black);
        imagestring($im, 3, 535, 616, $invoice_info[0]["service_code"], $black);
        imagestring($im, 3, 690, 616, $sub_fee_2[0]["igst_tot"] . ".00", $black );
        imagestring($im, 3, 815, 616, $invoice_info[0]["qty"], $black);
        imagestring($im, 3, 900, 616, $sub_fee_2[0]["fee"] . ".00", $black);
    }
    if (!empty($sub_3)) {
        imagestring($im, 3, 60, 636, "3", $black);
        imagestring($im, 3, 118, 636, $sub_3[0]["sub_name"], $black);
        imagestring($im, 3, 535, 636, $invoice_info[0]["service_code"], $black);
        imagestring($im, 3, 690, 636, $sub_fee_3[0]["igst_tot"] . ".00", $black );
        imagestring($im, 3, 815, 636, $invoice_info[0]["qty"], $black);
        imagestring($im, 3, 900, 636, $sub_fee_3[0]["fee"] . ".00", $black);
    }
    // Added by Pooja Mane to display 4 subject on invoice - 23-04-2024 //
    if (!empty($sub_4)) {
        imagestring($im, 3, 60, 656, "4", $black);
        imagestring($im, 3, 118, 656, $sub_4[0]["sub_name"], $black);
        imagestring($im, 3, 535, 656, $invoice_info[0]["service_code"], $black);
        imagestring($im, 3, 690, 656, $sub_fee_4[0]["igst_tot"] . ".00", $black );
        imagestring($im, 3, 815, 656, $invoice_info[0]["qty"], $black);
        imagestring($im, 3, 900, 656, $sub_fee_4[0]["fee"] . ".00", $black);
    }
    // Added by Pooja Mane to display 4 subject on invoice - 23-04-2024 //
    if ($zone_code == "CO") {
        if ($invoice_info[0]["state_of_center"] == "MAH") {
            imagestring($im, 3, 118, 606, " ", $black);
            imagestring($im, 3, 118, 660, "CGST ", $black);
            imagestring($im, 3, 690, 660, $invoice_info[0]["cgst_rate"] . "%", $black );
            imagestring($im, 3, 900, 660, $invoice_info[0]["cgst_amt"], $black);
            imagestring($im, 3, 118, 680, "SGST ", $black);
            imagestring($im, 3, 690, 680, $invoice_info[0]["sgst_rate"] . "%", $black );
            imagestring($im, 3, 900, 680, $invoice_info[0]["sgst_amt"], $black);

            imagestring($im, 3, 118, 700, "IGST ", $black);
            imagestring($im, 3, 690, 700, "-", $black);
            imagestring($im, 3, 900, 700, "-", $black);
            $wordamt = contact_classes_word($invoice_info[0]["cs_total"]);
        }

        // && $invoice_info[0]['state_of_center'] != 'JAM'
        if ($invoice_info[0]["state_of_center"] != "MAH") {
            imagestring($im, 3, 118, 606, " ", $black);
            imagestring($im, 3, 118, 646, "CGST ", $black);
            imagestring($im, 3, 118, 666, "SGST ", $black);
            imagestring($im, 3, 690, 646, "-", $black);
            imagestring($im, 3, 690, 666, "-", $black);
            imagestring($im, 3, 118, 686, "IGST ", $black);
            imagestring($im, 3, 690, 686, $invoice_info[0]["igst_rate"] . "%", $black );
            imagestring($im, 3, 900, 686, $invoice_info[0]["igst_amt"], $black);
            $wordamt = contact_classes_word($invoice_info[0]["igst_total"]);
        }

        /*if($invoice_info[0]['state_of_center'] == 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "inter-state", $black);
		imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
		$wordamt =contact_classes_word($invoice_info[0]['igst_total']);
	}*/

        imagestring($im, 3, 535, 820, "Total(Rs.)", $black);
        if ($invoice_info[0]["state_of_center"] == "MAH") {
            imagestring($im, 3, 900, 820, $invoice_info[0]["cs_total"], $black);
        } elseif ($invoice_info[0]["state_of_center"] != "MAH") { 
            imagestring($im, 3, 900, 820, $invoice_info[0]["igst_total"], $black );
        }
    } elseif ($zone_code == "NZ") {

        if ($invoice_info[0]["state_of_center"] == "DEL") 
        {
            // UPDATED Y column 4th value Pooja Mane to display 4 subject on invoice - 23-04-2024 //
            if($state_info[0]['state_code'] == "DEL")
            {
                imagestring($im, 3, 118, 700, " ", $black);
                imagestring($im, 3, 118, 740, "CGST ", $black);
                imagestring($im, 3, 690, 740, $invoice_info[0]["cgst_rate"] . "%", $black);
                imagestring($im, 3, 900, 740, $invoice_info[0]["cgst_amt"], $black);
                imagestring($im, 3, 118, 760, "SGST ", $black);
                imagestring($im, 3, 690, 760, $invoice_info[0]["sgst_rate"] . "%", $black);
                imagestring($im, 3, 900, 760, $invoice_info[0]["sgst_amt"], $black);
                imagestring($im, 3, 118, 780, "IGST ", $black);
                imagestring($im, 3, 690, 780, "-", $black);
                imagestring($im, 3, 900, 780, "-", $black);
                $wordamt = contact_classes_word($invoice_info[0]["cs_total"]);
            }
            else{
                imagestring($im, 3, 118, 700, " ", $black);
                imagestring($im, 3, 118, 686, "CGST ", $black);
                imagestring($im, 3, 118, 706, "SGST ", $black);
                imagestring($im, 3, 690, 686, "-", $black);
                imagestring($im, 3, 690, 706, "-", $black);
                imagestring($im, 3, 118, 726, "IGST ", $black);
                imagestring($im, 3, 690, 726, $invoice_info[0]["igst_rate"] . "%", $black);
                imagestring($im, 3, 900, 726, $invoice_info[0]["igst_amt"], $black);
                $wordamt = contact_classes_word($invoice_info[0]["igst_total"]);
            }
        }

        // && $invoice_info[0]['state_of_center'] != 'JAM'
        if ($invoice_info[0]["state_of_center"] != "DEL") {
            imagestring($im, 3, 118, 700, " ", $black);
            imagestring($im, 3, 118, 686, "CGST ", $black);
            imagestring($im, 3, 118, 706, "SGST ", $black);
            imagestring($im, 3, 690, 686, "-", $black);
            imagestring($im, 3, 690, 706, "-", $black);
            imagestring($im, 3, 118, 726, "IGST ", $black);
            imagestring($im, 3, 690, 726, $invoice_info[0]["igst_rate"] . "%", $black);
            imagestring($im, 3, 900, 726, $invoice_info[0]["igst_amt"], $black);
            $wordamt = contact_classes_word($invoice_info[0]["igst_total"]);
        }

        /*if($invoice_info[0]['state_of_center'] == 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "inter-state", $black);
		imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
		$wordamt =contact_classes_word($invoice_info[0]['igst_total']);
	}*/

        imagestring($im, 3, 535, 820, "Total(Rs.)", $black);
        if ($invoice_info[0]["state_of_center"] == "DEL") {
            if($state_info[0]['state_code'] == "DEL")
            {  
                imagestring($im, 3, 900, 820, $invoice_info[0]["cs_total"], $black);
            }else
            { 
                imagestring($im, 3, 900, 820, $invoice_info[0]["igst_total"], $black);
            }
        } elseif ($invoice_info[0]["state_of_center"] != "DEL") {
            imagestring($im, 3, 900, 820, $invoice_info[0]["igst_total"], $black );
        }
    } elseif ($zone_code == "EZ") {
        if ($invoice_info[0]["state_of_center"] == "WES") {
            imagestring($im, 3, 118, 606, " ", $black);
            imagestring($im, 3, 118, 660, "CGST ", $black);
            imagestring(
                $im,
                3,
                690,
                660,
                $invoice_info[0]["cgst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 660, $invoice_info[0]["cgst_amt"], $black);
            imagestring($im, 3, 118, 680, "SGST ", $black);
            imagestring(
                $im,
                3,
                690,
                680,
                $invoice_info[0]["sgst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 680, $invoice_info[0]["sgst_amt"], $black);

            imagestring($im, 3, 118, 700, "IGST ", $black);
            imagestring($im, 3, 690, 700, "-", $black);
            imagestring($im, 3, 900, 700, "-", $black);
            $wordamt = contact_classes_word($invoice_info[0]["cs_total"]);
        }

        // && $invoice_info[0]['state_of_center'] != 'JAM'
        if ($invoice_info[0]["state_of_center"] != "WES") {
            imagestring($im, 3, 118, 606, " ", $black);
            imagestring($im, 3, 118, 646, "CGST ", $black);
            imagestring($im, 3, 118, 666, "SGST ", $black);
            imagestring($im, 3, 690, 646, "-", $black);
            imagestring($im, 3, 690, 666, "-", $black);
            imagestring($im, 3, 118, 686, "IGST ", $black);
            imagestring(
                $im,
                3,
                690,
                686,
                $invoice_info[0]["igst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 686, $invoice_info[0]["igst_amt"], $black);
            $wordamt = contact_classes_word($invoice_info[0]["igst_total"]);
        }

        /*if($invoice_info[0]['state_of_center'] == 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "inter-state", $black);
		imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
		$wordamt =contact_classes_word($invoice_info[0]['igst_total']);
	}*/

        imagestring($im, 3, 535, 820, "Total(Rs.)", $black);
        if ($invoice_info[0]["state_of_center"] == "WES") {
            imagestring($im, 3, 900, 820, $invoice_info[0]["cs_total"], $black);
        } elseif ($invoice_info[0]["state_of_center"] != "WES") {
            imagestring(
                $im,
                3,
                900,
                820,
                $invoice_info[0]["igst_total"],
                $black
            );
        }
    } elseif ($zone_code == "SZ") {
        if ($invoice_info[0]["state_of_center"] == "TAM") {
            imagestring($im, 3, 118, 606, " ", $black);
            imagestring($im, 3, 118, 660, "CGST ", $black);
            imagestring(
                $im,
                3,
                690,
                660,
                $invoice_info[0]["cgst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 660, $invoice_info[0]["cgst_amt"], $black);
            imagestring($im, 3, 118, 680, "SGST ", $black);
            imagestring(
                $im,
                3,
                690,
                680,
                $invoice_info[0]["sgst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 680, $invoice_info[0]["sgst_amt"], $black);

            imagestring($im, 3, 118, 700, "IGST ", $black);
            imagestring($im, 3, 690, 700, "-", $black);
            imagestring($im, 3, 900, 700, "-", $black);
            $wordamt = contact_classes_word($invoice_info[0]["cs_total"]);
        }

        // && $invoice_info[0]['state_of_center'] != 'JAM'
        if ($invoice_info[0]["state_of_center"] != "TAM") {
            imagestring($im, 3, 118, 606, " ", $black);
            imagestring($im, 3, 118, 646, "CGST ", $black);
            imagestring($im, 3, 118, 666, "SGST ", $black);
            imagestring($im, 3, 690, 646, "-", $black);
            imagestring($im, 3, 690, 666, "-", $black);
            imagestring($im, 3, 118, 686, "IGST ", $black);
            imagestring(
                $im,
                3,
                690,
                686,
                $invoice_info[0]["igst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 686, $invoice_info[0]["igst_amt"], $black);
            $wordamt = contact_classes_word($invoice_info[0]["igst_total"]);
        }

        /*if($invoice_info[0]['state_of_center'] == 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "inter-state", $black);
		imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
		$wordamt =contact_classes_word($invoice_info[0]['igst_total']);
	}*/

        imagestring($im, 3, 535, 820, "Total", $black);
        if ($invoice_info[0]["state_of_center"] == "TAM") {
            imagestring($im, 3, 900, 820, $invoice_info[0]["cs_total"], $black);
        } elseif ($invoice_info[0]["state_of_center"] != "TAM") {
            imagestring(
                $im,
                3,
                900,
                820,
                $invoice_info[0]["igst_total"],
                $black
            );
        }
    }
    imagestring(
        $im,
        3,
        40,
        860,
        "Amount in words : " . $wordamt . " only",
        $black
    );
    imagestring(
        $im,
        3,
        650,
        880,
        "For Indian Institute of Banking & Finance",
        $black
    );
    imagestring($im, 3, 720, 950, "Authorised Signatory", $black);
    imagestring($im, 3, 40, 900, "Reverse charge applicable :", $black);
    imagestring($im, 3, 260, 900, "Y/N", $black);
    imagestring($im, 3, 300, 900, "NO", $black);
    imagestring($im, 3, 40, 930, "% of Tax payable under", $black);
    imagestring($im, 3, 280, 930, "% ---", $black);
    imagestring($im, 3, 350, 930, "Rs.---", $black);

    if ($zone_code == "CO") {
        $savepath = base_url() . "uploads/contact_classes_invoice/user/CO/";
        //$imagename = 'new_dra.jpg';
        $ino = str_replace("/", "_", $invoice_info[0]["invoice_no"]);
        $imagename = $ino . ".jpg";
        $update_data = ["invoice_image" => $imagename];
        $CI->master_model->updateRecord("exam_invoice", $update_data, [
            "invoice_id" => $invoice_no,
        ]);

        if ($invoice_info[0]["cgst_rate"] <= 0.0) {
            $cgst_rate = "-";
        } else {
            $cgst_rate = $invoice_info[0]["cgst_rate"] . "%";
        }
        if ($invoice_info[0]["cgst_amt"] <= 0.0) {
            $cgst_amt = "-";
        } else {
            $cgst_amt = $invoice_info[0]["cgst_amt"];
        }
        if ($invoice_info[0]["sgst_rate"] <= 0.0) {
            $sgst_rate = "-";
        } else {
            $sgst_rate = $invoice_info[0]["sgst_rate"] . "%";
        }
        if ($invoice_info[0]["sgst_amt"] <= 0.0) {
            $sgst_amt = "-";
        } else {
            $sgst_amt = $invoice_info[0]["sgst_amt"];
        }
        if ($invoice_info[0]["igst_rate"] <= 0.0) {
            $igst_rate = "-";
        } else {
            $igst_rate = $invoice_info[0]["igst_rate"] . "%";
        }
        if ($invoice_info[0]["igst_amt"] <= 0.0) {
            $igst_amt = "-";
        } else {
            $igst_amt = $invoice_info[0]["igst_amt"];
        }

        imagepng($im, "uploads/contact_classes_invoice/user/CO/" . $imagename);
        $png = @imagecreatefromjpeg("assets/images/sign.jpg");
        $png2 = @imagecreatefromjpeg("assets/images/iibf_logo_short.jpg");
        $jpeg = @imagecreatefromjpeg(
            "uploads/contact_classes_invoice/user/CO/" .
                $zone_code .
                "/" .
                $imagename
        );
        @imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
        @imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
        imagepng($im, "uploads/contact_classes_invoice/user/CO/" . $imagename);
        imagedestroy($im);
    } elseif ($zone_code == "NZ") {
        $savepath = base_url() . "uploads/contact_classes_invoice/user/CO/";
        //$imagename = 'new_dra.jpg';
        $ino = str_replace("/", "_", $invoice_info[0]["invoice_no"]);
        $imagename = $ino . ".jpg";
        $update_data = ["invoice_image" => $imagename];
        $CI->master_model->updateRecord("exam_invoice", $update_data, [
            "invoice_id" => $invoice_no,
        ]);

        if ($invoice_info[0]["cgst_rate"] <= 0.0) {
            $cgst_rate = "-";
        } else {
            $cgst_rate = $invoice_info[0]["cgst_rate"] . "%";
        }
        if ($invoice_info[0]["cgst_amt"] <= 0.0) {
            $cgst_amt = "-";
        } else {
            $cgst_amt = $invoice_info[0]["cgst_amt"];
        }
        if ($invoice_info[0]["sgst_rate"] <= 0.0) {
            $sgst_rate = "-";
        } else {
            $sgst_rate = $invoice_info[0]["sgst_rate"] . "%";
        }
        if ($invoice_info[0]["sgst_amt"] <= 0.0) {
            $sgst_amt = "-";
        } else {
            $sgst_amt = $invoice_info[0]["sgst_amt"];
        }
        if ($invoice_info[0]["igst_rate"] <= 0.0) {
            $igst_rate = "-";
        } else {
            $igst_rate = $invoice_info[0]["igst_rate"] . "%";
        }
        if ($invoice_info[0]["igst_amt"] <= 0.0) {
            $igst_amt = "-";
        } else {
            $igst_amt = $invoice_info[0]["igst_amt"];
        }

        imagepng($im, "uploads/contact_classes_invoice/user/NZ/" . $imagename);
        $png = @imagecreatefromjpeg("assets/images/sign.jpg");
        $png2 = @imagecreatefromjpeg("assets/images/iibf_logo_short.jpg");
        $jpeg = @imagecreatefromjpeg(
            "uploads/contact_classes_invoice/user/NZ/" .
                $zone_code .
                "/" .
                $imagename
        );
        @imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
        @imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
        imagepng($im, "uploads/contact_classes_invoice/user/NZ/" . $imagename);
        imagedestroy($im);
    } elseif ($zone_code == "EZ") {
        $savepath = base_url() . "uploads/contact_classes_invoice/user/CO/";
        //$imagename = 'new_dra.jpg';
        $ino = str_replace("/", "_", $invoice_info[0]["invoice_no"]);
        $imagename = $ino . ".jpg";
        $update_data = ["invoice_image" => $imagename];
        $CI->master_model->updateRecord("exam_invoice", $update_data, [
            "invoice_id" => $invoice_no,
        ]);

        if ($invoice_info[0]["cgst_rate"] <= 0.0) {
            $cgst_rate = "-";
        } else {
            $cgst_rate = $invoice_info[0]["cgst_rate"] . "%";
        }
        if ($invoice_info[0]["cgst_amt"] <= 0.0) {
            $cgst_amt = "-";
        } else {
            $cgst_amt = $invoice_info[0]["cgst_amt"];
        }
        if ($invoice_info[0]["sgst_rate"] <= 0.0) {
            $sgst_rate = "-";
        } else {
            $sgst_rate = $invoice_info[0]["sgst_rate"] . "%";
        }
        if ($invoice_info[0]["sgst_amt"] <= 0.0) {
            $sgst_amt = "-";
        } else {
            $sgst_amt = $invoice_info[0]["sgst_amt"];
        }
        if ($invoice_info[0]["igst_rate"] <= 0.0) {
            $igst_rate = "-";
        } else {
            $igst_rate = $invoice_info[0]["igst_rate"] . "%";
        }
        if ($invoice_info[0]["igst_amt"] <= 0.0) {
            $igst_amt = "-";
        } else {
            $igst_amt = $invoice_info[0]["igst_amt"];
        }

        imagepng($im, "uploads/contact_classes_invoice/user/EZ/" . $imagename);
        $png = @imagecreatefromjpeg("assets/images/sign.jpg");
        $png2 = @imagecreatefromjpeg("assets/images/iibf_logo_short.jpg");
        $jpeg = @imagecreatefromjpeg(
            "uploads/contact_classes_invoice/user/EZ/" .
                $zone_code .
                "/" .
                $imagename
        );
        @imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
        @imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
        imagepng($im, "uploads/contact_classes_invoice/user/EZ/" . $imagename);
        imagedestroy($im);
    } elseif ($zone_code == "SZ") {
        $savepath = base_url() . "uploads/contact_classes_invoice/user/CO/";
        //$imagename = 'new_dra.jpg';
        $ino = str_replace("/", "_", $invoice_info[0]["invoice_no"]);
        $imagename = $ino . ".jpg";
        $update_data = ["invoice_image" => $imagename];
        $CI->master_model->updateRecord("exam_invoice", $update_data, [
            "invoice_id" => $invoice_no,
        ]);

        if ($invoice_info[0]["cgst_rate"] <= 0.0) {
            $cgst_rate = "-";
        } else {
            $cgst_rate = $invoice_info[0]["cgst_rate"] . "%";
        }
        if ($invoice_info[0]["cgst_amt"] <= 0.0) {
            $cgst_amt = "-";
        } else {
            $cgst_amt = $invoice_info[0]["cgst_amt"];
        }
        if ($invoice_info[0]["sgst_rate"] <= 0.0) {
            $sgst_rate = "-";
        } else {
            $sgst_rate = $invoice_info[0]["sgst_rate"] . "%";
        }
        if ($invoice_info[0]["sgst_amt"] <= 0.0) {
            $sgst_amt = "-";
        } else {
            $sgst_amt = $invoice_info[0]["sgst_amt"];
        }
        if ($invoice_info[0]["igst_rate"] <= 0.0) {
            $igst_rate = "-";
        } else {
            $igst_rate = $invoice_info[0]["igst_rate"] . "%";
        }
        if ($invoice_info[0]["igst_amt"] <= 0.0) {
            $igst_amt = "-";
        } else {
            $igst_amt = $invoice_info[0]["igst_amt"];
        }

        imagepng($im, "uploads/contact_classes_invoice/user/SZ/" . $imagename);
        $png = @imagecreatefromjpeg("assets/images/sign.jpg");
        $png2 = @imagecreatefromjpeg("assets/images/iibf_logo_short.jpg");
        $jpeg = @imagecreatefromjpeg(
            "uploads/contact_classes_invoice/user/SZ/" .
                $zone_code .
                "/" .
                $imagename
        );
        @imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
        @imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
        imagepng($im, "uploads/contact_classes_invoice/user/SZ/" . $imagename);
        imagedestroy($im);
    }

    /****************************** image for supplier ***********************************/
    ($im = @imagecreate(1000, 1000)) or
        die("Cannot Initialize new GD image stream");
    $background_color = imagecolorallocate($im, 255, 255, 255); // white
    $black = imagecolorallocate($im, 0, 0, 0); // black

    //imageline ($im,   x1,  y1, x2, y2, color);
    imageline($im, 20, 20, 980, 20, $black); // line-1
    imageline($im, 20, 980, 980, 980, $black); // line-2
    imageline($im, 20, 20, 20, 980, $black); // line-3
    imageline($im, 980, 20, 980, 980, $black); // line-4
    imageline($im, 20, 160, 980, 160, $black); // line-5
    imageline($im, 20, 200, 980, 200, $black); // line-6
    imageline($im, 20, 480, 980, 480, $black); // line-7
    imageline($im, 20, 520, 980, 520, $black); // line-8
    imageline($im, 20, 580, 980, 580, $black); // line-9
    imageline($im, 20, 850, 980, 850, $black); // line-10
    imageline($im, 650, 200, 650, 480, $black); // line-11
    imageline($im, 85, 520, 85, 850, $black); // line-12
    imageline($im, 500, 520, 500, 850, $black); // line-13
    imageline($im, 650, 520, 650, 850, $black); // line-14
    imageline($im, 785, 520, 785, 850, $black); // line-15
    imageline($im, 860, 520, 860, 850, $black); // line-16
    imageline($im, 40, 880, 625, 880, $black); // line-17

    $year = date("Y");
    //imagestring(image,font,x,y,string,color);
    imagestring(
        $im,
        5,
        100,
        40,
        "INDIAN INSTITUTE OF BANKING & FINANCE",
        $black
    );
    imagestring($im, 3, 100, 60, "ISO 21001:2018 Certified", $black);
    imagestring($im, 3, 100, 80, "(CINU9111OMH1928GAP1391)", $black);
    imagestring(
        $im,
        3,
        100,
        100,
        "Registered office Kohinoor City, Commercial - II,  Tower 1, 2nd Floor, Kirole Road,",
        $black
    );
    imagestring(
        $im,
        3,
        100,
        120,
        "Off LBS Marg, Kurla(West), Mumbai - 400 070 , Maharashtra",
        $black
    );
    imagestring($im, 3, 100, 140, "www.iibf.org.in", $black);
    imagestring($im, 5, 400, 170, "TAX INVOICE CUM RECEIPT", $black);

    imagestring($im, 5, 40, 220, "Details of service recipient", $black);
    imagestring($im, 5, 670, 220, "DUPLICATE FOR SUPPLIER", $black);
    imagestring(
        $im,
        3,
        40,
        260,
        "Name of service Recipient:" . $member_name,
        $black
    );
    imagestring($im, 3, 40, 280, "Address: " . $address_1, $black);
    imagestring($im, 3, 40, 300, $address_2, $black);
    imagestring(
        $im,
        3,
        40,
        320,
        "State: " . $invoice_info[0]["state_name"],
        $black
    );
    imagestring(
        $im,
        3,
        40,
        340,
        "State Code: " . $invoice_info[0]["state_code"],
        $black
    );
    imagestring(
        $im,
        3,
        40,
        360,
        "GST No: " . $invoice_info[0]["gstin_no"],
        $black
    );
    imagestring(
        $im,
        3,
        40,
        380,
        "Transaction Number : " . $invoice_info[0]["transaction_no"],
        $black
    );

    imagestring(
        $im,
        3,
        40,
        400,
        "Course name : " . $program_name[0]["course_name"],
        $black
    );

    imagestring(
        $im,
        3,
        40,
        420,
        "Center code : " . $invoice_info[0]["center_code"],
        $black
    );
    imagestring(
        $im,
        3,
        40,
        440,
        "Center name : " . $invoice_info[0]["center_name"],
        $black
    );

    imagestring(
        $im,
        3,
        670,
        260,
        "Invoice Number: " . $invoice_info[0]["invoice_no"],
        $black
    );
    imagestring(
        $im,
        3,
        670,
        280,
        "Date: " .
            date("d-m-Y", strtotime($invoice_info[0]["date_of_invoice"])),
        $black
    );
    if (
        $invoice_info[0]["gstin_no"] != "" &&
        $invoice_info[0]["gstin_no"] != 0
    ) {
        $gstn = $invoice_info[0]["gstin_no"];
    } else {
        $gstn = "-";
    }
    imagestring($im, 3, 670, 300, "GSTIN - 27AAATT3309D1ZS ", $black);

    imagestring($im, 3, 40, 530, "Sr.No", $black);
    imagestring($im, 3, 118, 530, "Description of Service", $black);
    imagestring($im, 3, 535, 530, "Accounting ", $black);
    imagestring($im, 3, 535, 542, "code", $black);
    imagestring($im, 3, 535, 554, "of Service", $black);
    imagestring($im, 3, 660, 530, "Rate per unit(Rs)", $black);
    imagestring($im, 3, 808, 530, "Unit", $black);
    imagestring($im, 3, 900, 530, "Total(Rs.)", $black);

    //imagestring($im, 3, 118,  596, "Charges Towards Training Program", $black);
    //imagestring($im, 3, 535,  596, $invoice_info[0]['service_code'], $black);
    //imagestring($im, 3, 700,  596, $invoice_info[0]['fee_amt'], $black);
    //imagestring($im, 3, 815,  596, $invoice_info[0]['qty'], $black);
    //imagestring($im, 3, 900,  596, $invoice_info[0]['fee_amt'], $black);
    //imagestring($im, 3, 535,  820, "Total", $black);

    if (!empty($sub_1)) {
        imagestring($im, 3, 60, 596, "1", $black);
        imagestring($im, 3, 118, 596, $sub_1[0]["sub_name"], $black);
        imagestring($im, 3, 535, 596, $invoice_info[0]["service_code"], $black);
        imagestring(
            $im,
            3,
            690,
            596,
            $sub_fee_1[0]["igst_tot"] . ".00",
            $black
        );
        imagestring($im, 3, 815, 596, $invoice_info[0]["qty"], $black);
        imagestring(
            $im,
            3,
            900,
            596,
            $sub_fee_1[0]["igst_tot"] . ".00",
            $black
        );
    }
    if (!empty($sub_2)) {
        imagestring($im, 3, 60, 616, "2", $black);
        imagestring($im, 3, 118, 616, $sub_2[0]["sub_name"], $black);
        imagestring($im, 3, 535, 616, $invoice_info[0]["service_code"], $black);
        imagestring(
            $im,
            3,
            690,
            616,
            $sub_fee_2[0]["igst_tot"] . ".00",
            $black
        );
        imagestring($im, 3, 815, 616, $invoice_info[0]["qty"], $black);
        imagestring(
            $im,
            3,
            900,
            616,
            $sub_fee_2[0]["igst_tot"] . ".00",
            $black
        );
    }
    if (!empty($sub_3)) {
        imagestring($im, 3, 60, 636, "3", $black);
        imagestring($im, 3, 118, 636, $sub_3[0]["sub_name"], $black);
        imagestring($im, 3, 535, 636, $invoice_info[0]["service_code"], $black);
        imagestring(
            $im,
            3,
            690,
            636,
            $sub_fee_3[0]["igst_tot"] . ".00",
            $black
        );
        imagestring($im, 3, 815, 636, $invoice_info[0]["qty"], $black);
        imagestring(
            $im,
            3,
            900,
            636,
            $sub_fee_3[0]["igst_tot"] . ".00",
            $black
        );
    }
    // Added by Pooja Mane to display 4 subject on invoice - 23-04-2024 //
    if (!empty($sub_4)) {
        imagestring($im, 3, 60, 656, "4", $black);
        imagestring($im, 3, 118, 656, $sub_4[0]["sub_name"], $black);
        imagestring($im, 3, 535, 656, $invoice_info[0]["service_code"], $black);
        imagestring(
            $im,
            3,
            690,
            656,
            $sub_fee_4[0]["igst_tot"] . ".00",
            $black
        );
        imagestring($im, 3, 815, 656, $invoice_info[0]["qty"], $black);
        imagestring($im, 3, 900, 656, $sub_fee_4[0]["fee"] . ".00", $black);
    }
    // Added by Pooja Mane to display 4 subject on invoice - 23-04-2024 //
    if ($zone_code == "CO") {
        if ($invoice_info[0]["state_of_center"] == "MAH") {
            imagestring($im, 3, 118, 606, " ", $black);
            imagestring($im, 3, 118, 660, "CGST ", $black);
            imagestring(
                $im,
                3,
                690,
                660,
                $invoice_info[0]["cgst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 660, $invoice_info[0]["cgst_amt"], $black);
            imagestring($im, 3, 118, 680, "SGST ", $black);
            imagestring(
                $im,
                3,
                690,
                680,
                $invoice_info[0]["sgst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 680, $invoice_info[0]["sgst_amt"], $black);

            imagestring($im, 3, 118, 700, "IGST ", $black);
            imagestring($im, 3, 690, 700, "-", $black);
            imagestring($im, 3, 900, 700, "-", $black);
            $wordamt = contact_classes_word($invoice_info[0]["cs_total"]);
        }

        // && $invoice_info[0]['state_of_center'] != 'JAM'
        if ($invoice_info[0]["state_of_center"] != "MAH") {
            imagestring($im, 3, 118, 606, " ", $black);
            imagestring($im, 3, 118, 646, "CGST ", $black);
            imagestring($im, 3, 118, 666, "SGST ", $black);
            imagestring($im, 3, 690, 646, "-", $black);
            imagestring($im, 3, 690, 666, "-", $black);
            imagestring($im, 3, 118, 686, "IGST ", $black);
            imagestring(
                $im,
                3,
                690,
                686,
                $invoice_info[0]["igst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 686, $invoice_info[0]["igst_amt"], $black);
            $wordamt = contact_classes_word($invoice_info[0]["igst_total"]);
        }

        /*if($invoice_info[0]['state_of_center'] == 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "inter-state", $black);
		imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
		$wordamt =contact_classes_word($invoice_info[0]['igst_total']);
	}*/

        imagestring($im, 3, 535, 820, "Total", $black);
        if ($invoice_info[0]["state_of_center"] == "MAH") {
            imagestring($im, 3, 900, 820, $invoice_info[0]["cs_total"], $black);
        } elseif ($invoice_info[0]["state_of_center"] != "MAH") {
            imagestring(
                $im,
                3,
                900,
                820,
                $invoice_info[0]["igst_total"],
                $black
            );
        }
    } elseif ($zone_code == "NZ") {
         
         if ($invoice_info[0]["state_of_center"] == "DEL") 
         {
            // UPDATED Y column 4th value Pooja Mane to display 4 subject on invoice - 23-04-2024 //
            if($state_info[0]['state_code'] == "DEL")
            {
                imagestring($im, 3, 118, 700, " ", $black);
                imagestring($im, 3, 118, 740, "CGST ", $black);
                imagestring($im, 3, 690, 740, $invoice_info[0]["cgst_rate"] . "%", $black);
                imagestring($im, 3, 900, 740, $invoice_info[0]["cgst_amt"], $black);
                imagestring($im, 3, 118, 760, "SGST ", $black);
                imagestring($im, 3, 690, 760, $invoice_info[0]["sgst_rate"] . "%", $black);
                imagestring($im, 3, 900, 760, $invoice_info[0]["sgst_amt"], $black);
                imagestring($im, 3, 118, 780, "IGST ", $black);
                imagestring($im, 3, 690, 780, "-", $black);
                imagestring($im, 3, 900, 780, "-", $black);
                $wordamt = contact_classes_word($invoice_info[0]["cs_total"]);
            }
            else{
                imagestring($im, 3, 118, 700, " ", $black);
                imagestring($im, 3, 118, 686, "CGST ", $black);
                imagestring($im, 3, 118, 706, "SGST ", $black);
                imagestring($im, 3, 690, 686, "-", $black);
                imagestring($im, 3, 690, 706, "-", $black);
                imagestring($im, 3, 118, 726, "IGST ", $black);
                imagestring($im, 3, 690, 726, $invoice_info[0]["igst_rate"] . "%", $black);
                imagestring($im, 3, 900, 726, $invoice_info[0]["igst_amt"], $black);
                $wordamt = contact_classes_word($invoice_info[0]["igst_total"]);
            }
        }

        // && $invoice_info[0]['state_of_center'] != 'JAM'
        if ($invoice_info[0]["state_of_center"] != "DEL") {
            imagestring($im, 3, 118, 700, " ", $black);
            imagestring($im, 3, 118, 686, "CGST ", $black);
            imagestring($im, 3, 118, 706, "SGST ", $black);
            imagestring($im, 3, 690, 686, "-", $black);
            imagestring($im, 3, 690, 706, "-", $black);
            imagestring($im, 3, 118, 726, "IGST ", $black);
            imagestring($im, 3, 690, 726, $invoice_info[0]["igst_rate"] . "%", $black);
            imagestring($im, 3, 900, 726, $invoice_info[0]["igst_amt"], $black);
            $wordamt = contact_classes_word($invoice_info[0]["igst_total"]);
        }

        /*if($invoice_info[0]['state_of_center'] == 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "inter-state", $black);
		imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
		$wordamt =contact_classes_word($invoice_info[0]['igst_total']);
	}*/

        imagestring($im, 3, 535, 820, "Total(Rs.)", $black);
        if ($invoice_info[0]["state_of_center"] == "DEL") {
            if($state_info[0]['state_code'] == "DEL")
            {
                imagestring($im, 3, 900, 820, $invoice_info[0]["cs_total"], $black);
            }else{
                imagestring($im, 3, 900, 820, $invoice_info[0]["igst_total"], $black);
            }
        } elseif ($invoice_info[0]["state_of_center"] != "DEL") {
            imagestring($im, 3, 900, 820, $invoice_info[0]["igst_total"], $black );
        }
    } elseif ($zone_code == "EZ") {
        if ($invoice_info[0]["state_of_center"] == "WES") {
            imagestring($im, 3, 118, 606, " ", $black);
            imagestring($im, 3, 118, 660, "CGST ", $black);
            imagestring(
                $im,
                3,
                690,
                660,
                $invoice_info[0]["cgst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 660, $invoice_info[0]["cgst_amt"], $black);
            imagestring($im, 3, 118, 680, "SGST ", $black);
            imagestring(
                $im,
                3,
                690,
                680,
                $invoice_info[0]["sgst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 680, $invoice_info[0]["sgst_amt"], $black);

            imagestring($im, 3, 118, 700, "IGST ", $black);
            imagestring($im, 3, 690, 700, "-", $black);
            imagestring($im, 3, 900, 700, "-", $black);
            $wordamt = contact_classes_word($invoice_info[0]["cs_total"]);
        }

        // && $invoice_info[0]['state_of_center'] != 'JAM'
        if ($invoice_info[0]["state_of_center"] != "WES") {
            imagestring($im, 3, 118, 606, " ", $black);
            imagestring($im, 3, 118, 646, "CGST ", $black);
            imagestring($im, 3, 118, 666, "SGST ", $black);
            imagestring($im, 3, 690, 646, "-", $black);
            imagestring($im, 3, 690, 666, "-", $black);
            imagestring($im, 3, 118, 686, "IGST ", $black);
            imagestring(
                $im,
                3,
                690,
                686,
                $invoice_info[0]["igst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 686, $invoice_info[0]["igst_amt"], $black);
            $wordamt = contact_classes_word($invoice_info[0]["igst_total"]);
        }

        /*if($invoice_info[0]['state_of_center'] == 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "inter-state", $black);
		imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
		$wordamt =contact_classes_word($invoice_info[0]['igst_total']);
	}*/

        imagestring($im, 3, 535, 820, "Total(Rs.)", $black);
        if ($invoice_info[0]["state_of_center"] == "WES") {
            imagestring($im, 3, 900, 820, $invoice_info[0]["cs_total"], $black);
        } elseif ($invoice_info[0]["state_of_center"] != "WES") {
            imagestring(
                $im,
                3,
                900,
                820,
                $invoice_info[0]["igst_total"],
                $black
            );
        }
    } elseif ($zone_code == "SZ") {
        if ($invoice_info[0]["state_of_center"] == "TAM") {
            imagestring($im, 3, 118, 606, " ", $black);
            imagestring($im, 3, 118, 660, "CGST ", $black);
            imagestring(
                $im,
                3,
                690,
                660,
                $invoice_info[0]["cgst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 660, $invoice_info[0]["cgst_amt"], $black);
            imagestring($im, 3, 118, 680, "SGST ", $black);
            imagestring(
                $im,
                3,
                690,
                680,
                $invoice_info[0]["sgst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 680, $invoice_info[0]["sgst_amt"], $black);

            imagestring($im, 3, 118, 700, "IGST ", $black);
            imagestring($im, 3, 690, 700, "-", $black);
            imagestring($im, 3, 900, 700, "-", $black);
            $wordamt = contact_classes_word($invoice_info[0]["cs_total"]);
        }

        // && $invoice_info[0]['state_of_center'] != 'JAM'
        if ($invoice_info[0]["state_of_center"] != "TAM") {
            imagestring($im, 3, 118, 606, " ", $black);
            imagestring($im, 3, 118, 646, "CGST ", $black);
            imagestring($im, 3, 118, 666, "SGST ", $black);
            imagestring($im, 3, 690, 646, "-", $black);
            imagestring($im, 3, 690, 666, "-", $black);
            imagestring($im, 3, 118, 686, "IGST ", $black);
            imagestring(
                $im,
                3,
                690,
                686,
                $invoice_info[0]["igst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 686, $invoice_info[0]["igst_amt"], $black);
            $wordamt = contact_classes_word($invoice_info[0]["igst_total"]);
        }

        /*if($invoice_info[0]['state_of_center'] == 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "inter-state", $black);
		imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
		$wordamt =contact_classes_word($invoice_info[0]['igst_total']);
	}*/

        imagestring($im, 3, 535, 820, "Total(Rs.)", $black);
        if ($invoice_info[0]["state_of_center"] == "TAM") {
            imagestring($im, 3, 900, 820, $invoice_info[0]["cs_total"], $black);
        } elseif ($invoice_info[0]["state_of_center"] != "TAM") {
            imagestring(
                $im,
                3,
                900,
                820,
                $invoice_info[0]["igst_total"],
                $black
            );
        }
    }
    imagestring(
        $im,
        3,
        40,
        860,
        "Amount in words : " . $wordamt . " only",
        $black
    );
    imagestring(
        $im,
        3,
        650,
        880,
        "For Indian Institute of Banking & Finance",
        $black
    );
    imagestring($im, 3, 720, 950, "Authorised Signatory", $black);
    imagestring($im, 3, 40, 900, "Reverse charge applicable :", $black);
    imagestring($im, 3, 260, 900, "Y/N", $black);
    imagestring($im, 3, 300, 900, "NO", $black);
    imagestring($im, 3, 40, 930, "% of Tax payable under", $black);
    imagestring($im, 3, 280, 930, "% ---", $black);
    imagestring($im, 3, 350, 930, "Rs.---", $black);

    if ($zone_code == "CO") {
        $savepath = base_url() . "uploads/contact_classes_invoice/supplier/CO/";
        //$imagename = 'new_dra.jpg';
        $ino = str_replace("/", "_", $invoice_info[0]["invoice_no"]);
        $imagename = $ino . ".jpg";
        $update_data = ["invoice_image" => $imagename];
        $CI->master_model->updateRecord("exam_invoice", $update_data, [
            "invoice_id" => $invoice_no,
        ]);

        if ($invoice_info[0]["cgst_rate"] <= 0.0) {
            $cgst_rate = "-";
        } else {
            $cgst_rate = $invoice_info[0]["cgst_rate"] . "%";
        }
        if ($invoice_info[0]["cgst_amt"] <= 0.0) {
            $cgst_amt = "-";
        } else {
            $cgst_amt = $invoice_info[0]["cgst_amt"];
        }
        if ($invoice_info[0]["sgst_rate"] <= 0.0) {
            $sgst_rate = "-";
        } else {
            $sgst_rate = $invoice_info[0]["sgst_rate"] . "%";
        }
        if ($invoice_info[0]["sgst_amt"] <= 0.0) {
            $sgst_amt = "-";
        } else {
            $sgst_amt = $invoice_info[0]["sgst_amt"];
        }
        if ($invoice_info[0]["igst_rate"] <= 0.0) {
            $igst_rate = "-";
        } else {
            $igst_rate = $invoice_info[0]["igst_rate"] . "%";
        }
        if ($invoice_info[0]["igst_amt"] <= 0.0) {
            $igst_amt = "-";
        } else {
            $igst_amt = $invoice_info[0]["igst_amt"];
        }

        imagepng(
            $im,
            "uploads/contact_classes_invoice/supplier/CO/" . $imagename
        );
        $png = @imagecreatefromjpeg("assets/images/sign.jpg");
        $png2 = @imagecreatefromjpeg("assets/images/iibf_logo_short.jpg");
        $jpeg = @imagecreatefromjpeg(
            "uploads/contact_classes_invoice/supplier/CO/" .
                $zone_code .
                "/" .
                $imagename
        );
        @imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
        @imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
        imagepng(
            $im,
            "uploads/contact_classes_invoice/supplier/CO/" . $imagename
        );
        imagedestroy($im);
        return $attachpath =
            "uploads/contact_classes_invoice/user/CO/" . $imagename;
    } elseif ($zone_code == "NZ") {
        $savepath = base_url() . "uploads/contact_classes_invoice/supplier/CO/";
        //$imagename = 'new_dra.jpg';
        $ino = str_replace("/", "_", $invoice_info[0]["invoice_no"]);
        $imagename = $ino . ".jpg";
        //$update_data = array('invoice_image' => $imagename);
        //$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));

        if ($invoice_info[0]["cgst_rate"] <= 0.0) {
            $cgst_rate = "-";
        } else {
            $cgst_rate = $invoice_info[0]["cgst_rate"] . "%";
        }
        if ($invoice_info[0]["cgst_amt"] <= 0.0) {
            $cgst_amt = "-";
        } else {
            $cgst_amt = $invoice_info[0]["cgst_amt"];
        }
        if ($invoice_info[0]["sgst_rate"] <= 0.0) {
            $sgst_rate = "-";
        } else {
            $sgst_rate = $invoice_info[0]["sgst_rate"] . "%";
        }
        if ($invoice_info[0]["sgst_amt"] <= 0.0) {
            $sgst_amt = "-";
        } else {
            $sgst_amt = $invoice_info[0]["sgst_amt"];
        }
        if ($invoice_info[0]["igst_rate"] <= 0.0) {
            $igst_rate = "-";
        } else {
            $igst_rate = $invoice_info[0]["igst_rate"] . "%";
        }
        if ($invoice_info[0]["igst_amt"] <= 0.0) {
            $igst_amt = "-";
        } else {
            $igst_amt = $invoice_info[0]["igst_amt"];
        }

        imagepng(
            $im,
            "uploads/contact_classes_invoice/supplier/NZ/" . $imagename
        );
        $png = @imagecreatefromjpeg("assets/images/sign.jpg");
        $png2 = @imagecreatefromjpeg("assets/images/iibf_logo_short.jpg");
        $jpeg = @imagecreatefromjpeg(
            "uploads/contact_classes_invoice/supplier/NZ/" .
                $zone_code .
                "/" .
                $imagename
        );
        @imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
        @imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
        imagepng(
            $im,
            "uploads/contact_classes_invoice/supplier/NZ/" . $imagename
        );
        imagedestroy($im);
        return $attachpath =
            "uploads/contact_classes_invoice/user/NZ/" . $imagename;
    } elseif ($zone_code == "EZ") {
        $savepath = base_url() . "uploads/contact_classes_invoice/supplier/CO/";
        //$imagename = 'new_dra.jpg';
        $ino = str_replace("/", "_", $invoice_info[0]["invoice_no"]);
        $imagename = $ino . ".jpg";
        $update_data = ["invoice_image" => $imagename];
        $CI->master_model->updateRecord("exam_invoice", $update_data, [
            "invoice_id" => $invoice_no,
        ]);

        if ($invoice_info[0]["cgst_rate"] <= 0.0) {
            $cgst_rate = "-";
        } else {
            $cgst_rate = $invoice_info[0]["cgst_rate"] . "%";
        }
        if ($invoice_info[0]["cgst_amt"] <= 0.0) {
            $cgst_amt = "-";
        } else {
            $cgst_amt = $invoice_info[0]["cgst_amt"];
        }
        if ($invoice_info[0]["sgst_rate"] <= 0.0) {
            $sgst_rate = "-";
        } else {
            $sgst_rate = $invoice_info[0]["sgst_rate"] . "%";
        }
        if ($invoice_info[0]["sgst_amt"] <= 0.0) {
            $sgst_amt = "-";
        } else {
            $sgst_amt = $invoice_info[0]["sgst_amt"];
        }
        if ($invoice_info[0]["igst_rate"] <= 0.0) {
            $igst_rate = "-";
        } else {
            $igst_rate = $invoice_info[0]["igst_rate"] . "%";
        }
        if ($invoice_info[0]["igst_amt"] <= 0.0) {
            $igst_amt = "-";
        } else {
            $igst_amt = $invoice_info[0]["igst_amt"];
        }

        imagepng(
            $im,
            "uploads/contact_classes_invoice/supplier/EZ/" . $imagename
        );
        $png = @imagecreatefromjpeg("assets/images/sign.jpg");
        $png2 = @imagecreatefromjpeg("assets/images/iibf_logo_short.jpg");
        $jpeg = @imagecreatefromjpeg(
            "uploads/contact_classes_invoice/supplier/EZ/" .
                $zone_code .
                "/" .
                $imagename
        );
        @imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
        @imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
        imagepng(
            $im,
            "uploads/contact_classes_invoice/supplier/EZ/" . $imagename
        );
        imagedestroy($im);
        return $attachpath =
            "uploads/contact_classes_invoice/user/EZ/" . $imagename;
    } elseif ($zone_code == "SZ") {
        $savepath = base_url() . "uploads/contact_classes_invoice/supplier/CO/";
        //$imagename = 'new_dra.jpg';
        $ino = str_replace("/", "_", $invoice_info[0]["invoice_no"]);
        $imagename = $ino . ".jpg";
        $update_data = ["invoice_image" => $imagename];
        $CI->master_model->updateRecord("exam_invoice", $update_data, [
            "invoice_id" => $invoice_no,
        ]);

        if ($invoice_info[0]["cgst_rate"] <= 0.0) {
            $cgst_rate = "-";
        } else {
            $cgst_rate = $invoice_info[0]["cgst_rate"] . "%";
        }
        if ($invoice_info[0]["cgst_amt"] <= 0.0) {
            $cgst_amt = "-";
        } else {
            $cgst_amt = $invoice_info[0]["cgst_amt"];
        }
        if ($invoice_info[0]["sgst_rate"] <= 0.0) {
            $sgst_rate = "-";
        } else {
            $sgst_rate = $invoice_info[0]["sgst_rate"] . "%";
        }
        if ($invoice_info[0]["sgst_amt"] <= 0.0) {
            $sgst_amt = "-";
        } else {
            $sgst_amt = $invoice_info[0]["sgst_amt"];
        }
        if ($invoice_info[0]["igst_rate"] <= 0.0) {
            $igst_rate = "-";
        } else {
            $igst_rate = $invoice_info[0]["igst_rate"] . "%";
        }
        if ($invoice_info[0]["igst_amt"] <= 0.0) {
            $igst_amt = "-";
        } else {
            $igst_amt = $invoice_info[0]["igst_amt"];
        }

        imagepng(
            $im,
            "uploads/contact_classes_invoice/supplier/SZ/" . $imagename
        );
        $png = @imagecreatefromjpeg("assets/images/sign.jpg");
        $png2 = @imagecreatefromjpeg("assets/images/iibf_logo_short.jpg");
        $jpeg = @imagecreatefromjpeg(
            "uploads/contact_classes_invoice/supplier/SZ/" .
                $zone_code .
                "/" .
                $imagename
        );
        @imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
        @imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
        imagepng(
            $im,
            "uploads/contact_classes_invoice/supplier/SZ/" . $imagename
        );
        imagedestroy($im);
        return $attachpath =
            "uploads/contact_classes_invoice/user/SZ/" . $imagename;
    }
}

function genarate_contact_classes_invoice($invoice_no, $id)
{
    //print_r($id);

    $CI = &get_instance();
    $invoice_info = $CI->master_model->getRecords("exam_invoice", [
        "invoice_id" => $invoice_no,
    ]);
    
    $state_info = $CI->master_model->getRecords("state_master", [
        "state_no" => $invoice_info[0]['state_code'],
    ]);

    $mem_info = $CI->master_model->getRecords("contact_classes_registration", [
        "contact_classes_id" => $id,
    ]);
    $program_name = $CI->master_model->getRecords(
        "contact_classes_cource_master",
        ["course_code" => $CI->session->userdata["enduserinfo"]["cource_code"]]
    );
    // Added by Pooja Mane to display 4 subject on invoice - 22-04-2024 //
    $sub_reg = $CI->master_model->getRecords(
        "contact_classes_Subject_registration",
        [
            "member_no" => $invoice_info[0]["member_no"],
            "program_code" => $invoice_info[0]["exam_code"],
        ]
    );
    $subjects_name = $CI->master_model->getRecords(
        "contact_classes_subject_master",
        [
            "course_code" => $invoice_info[0]["exam_code"],
            "exam_prd" => $invoice_info[0]["exam_period"],
        ]
    );
    // Added by Pooja Mane to display 4 subject on invoice - 22-04-2024 //

    //middle name
    if (isset($mem_info[0]["middlename"])) {
        $mname = $mem_info[0]["middlename"];
    } else {
        $mname = "";
    }
    //last name
    if (isset($mem_info[0]["lastname"])) {
        $lname = $mem_info[0]["lastname"];
    } else {
        $lname = "";
    }
    $member_name = $mem_info[0]["firstname"] . " " . $mname . " " . $lname;

    //address
    $address_1 = $address_2 = "";
    if (isset($mem_info[0]["address1"])) {
        $address_1 = $mem_info[0]["address1"] . $mem_info[0]["address2"];
    }
    if (isset($mem_info[0]["address2"])) {
        $address_2 = $mem_info[0]["address3"] . $mem_info[0]["address4"];
    }
    /*if($invoice_info[0]['state_of_center'] == 'JAM'){
		return genarate_contact_classes_invoice_jk($invoice_no);
		exit;
	}*/

    $date_of_invoice = date(
        "d-m-Y",
        strtotime($invoice_info[0]["date_of_invoice"])
    );
    $i = 0;
    foreach ($CI->session->userdata["enduserinfo"]["subjects"] as $val) {
        $array[$i]["subject_code"] = $val;
        $i++;
    }

    //subject 1
    if (isset($array[0]["subject_code"])) {
        $CI->db->distinct("sub_name");
        $sub_1 = $CI->master_model->getRecords(
            "contact_classes_subject_master",
            [
                "course_code" =>
                    $CI->session->userdata["enduserinfo"]["cource_code"],
                "sub_code" => $array[0]["subject_code"],
            ],
            "sub_name"
        );
    }
    //subject 2
    if (isset($array[1]["subject_code"])) {
        $CI->db->distinct("sub_name");
        $sub_2 = $CI->master_model->getRecords(
            "contact_classes_subject_master",
            [
                "course_code" =>
                    $CI->session->userdata["enduserinfo"]["cource_code"],
                "sub_code" => $array[1]["subject_code"],
            ],
            "sub_name"
        );
    }
    //subject 3
    if (isset($array[2]["subject_code"])) {
        $CI->db->distinct("sub_name");
        $sub_3 = $CI->master_model->getRecords(
            "contact_classes_subject_master",
            [
                "course_code" =>
                    $CI->session->userdata["enduserinfo"]["cource_code"],
                "sub_code" => $array[2]["subject_code"],
            ],
            "sub_name"
        );
    }
    // Added by Pooja Mane to display 4 subject on invoice - 22-04-2024 //
    //subject 4
    if (isset($array[3]["subject_code"])) {
        $CI->db->distinct("sub_name");
        $sub_4 = $CI->master_model->getRecords(
            "contact_classes_subject_master",
            [
                "course_code" =>
                    $CI->session->userdata["enduserinfo"]["cource_code"],
                "sub_code" => $array[3]["subject_code"],
            ],
            "sub_name"
        );
    }
    // Added by Pooja Mane to display 4 subject on invoice - 22-04-2024 //
    /****************************** image for user ***********************************/
    // create image for recipeint
    //imagecreate(width, height);
    ($im = @imagecreate(1000, 1000)) or
        die("Cannot Initialize new GD image stream");
    $background_color = imagecolorallocate($im, 255, 255, 255); // white
    $black = imagecolorallocate($im, 0, 0, 0); // black

    //imageline ($im,   x1,  y1, x2, y2, color);
    imageline($im, 20, 20, 980, 20, $black); // line-1
    imageline($im, 20, 980, 980, 980, $black); // line-2
    imageline($im, 20, 20, 20, 980, $black); // line-3
    imageline($im, 980, 20, 980, 980, $black); // line-4
    imageline($im, 20, 160, 980, 160, $black); // line-5
    imageline($im, 20, 200, 980, 200, $black); // line-6
    imageline($im, 20, 480, 980, 480, $black); // line-7
    imageline($im, 20, 520, 980, 520, $black); // line-8
    imageline($im, 20, 580, 980, 580, $black); // line-9
    imageline($im, 20, 850, 980, 850, $black); // line-10
    imageline($im, 650, 200, 650, 480, $black); // line-11
    imageline($im, 85, 520, 85, 850, $black); // line-12
    imageline($im, 500, 520, 500, 850, $black); // line-13
    imageline($im, 650, 520, 650, 850, $black); // line-14
    imageline($im, 785, 520, 785, 850, $black); // line-15
    imageline($im, 860, 520, 860, 850, $black); // line-16
    imageline($im, 40, 880, 625, 880, $black); // line-17

    $year = date("Y");
    //imagestring(image,font,x,y,string,color);
    imagestring(
        $im,
        5,
        100,
        40,
        "INDIAN INSTITUTE OF BANKING & FINANCE",
        $black
    );
    imagestring($im, 3, 100, 60, "ISO 21001:2018 Certified", $black);
    imagestring($im, 3, 100, 80, "(CINU9111OMH1928GAP1391)", $black);
    imagestring(
        $im,
        3,
        100,
        100,
        "Registered office Kohinoor City, Commercial - II,  Tower 1, 2nd Floor, Kirole Road,",
        $black
    );
    imagestring(
        $im,
        3,
        100,
        120,
        "Off LBS Marg, Kurla(West), Mumbai - 400 070 , Maharashtra",
        $black
    );
    imagestring($im, 3, 100, 140, "www.iibf.org.in", $black);
    imagestring($im, 5, 400, 170, "TAX INVOICE CUM RECEIPT", $black);

    imagestring($im, 5, 40, 220, "Details of service recipient", $black);
    imagestring($im, 5, 670, 220, "ORIGINAL FOR RECIPIENT", $black);
    imagestring(
        $im,
        3,
        40,
        260,
        "Name of service Recipient:" . $member_name,
        $black
    );
    imagestring($im, 3, 40, 280, "Address: " . $address_1, $black);
    imagestring($im, 3, 40, 300, $address_2, $black);
    imagestring(
        $im,
        3,
        40,
        320,
        "State: " . $invoice_info[0]["state_name"],
        $black
    );
    imagestring(
        $im,
        3,
        40,
        340,
        "State Code: " . $invoice_info[0]["state_code"],
        $black
    );
    imagestring(
        $im,
        3,
        40,
        360,
        "GST No: " . $invoice_info[0]["gstin_no"],
        $black
    );
    imagestring(
        $im,
        3,
        40,
        380,
        "Transaction Number : " . $invoice_info[0]["transaction_no"],
        $black
    );

    imagestring(
        $im,
        3,
        40,
        400,
        "Course name : " . $program_name[0]["course_name"],
        $black
    );
    imagestring(
        $im,
        3,
        40,
        420,
        "Center code : " . $invoice_info[0]["center_code"],
        $black
    );
    imagestring(
        $im,
        3,
        40,
        440,
        "Center name : " . $invoice_info[0]["center_name"],
        $black
    );

    imagestring(
        $im,
        3,
        670,
        260,
        "Invoice Number: " . $invoice_info[0]["invoice_no"],
        $black
    );
    imagestring(
        $im,
        3,
        670,
        280,
        "Date: " .
            date("d-m-Y", strtotime($invoice_info[0]["date_of_invoice"])),
        $black
    );
    if (
        $invoice_info[0]["gstin_no"] != "" &&
        $invoice_info[0]["gstin_no"] != 0
    ) {
        $gstn = $invoice_info[0]["gstin_no"];
    } else {
        $gstn = "-";
    }
    imagestring($im, 3, 670, 300, "GSTIN - 27AAATT3309D1ZS ", $black);

    imagestring($im, 3, 40, 530, "Sr.No", $black);
    imagestring($im, 3, 118, 530, "Description of Service", $black);
    imagestring($im, 3, 535, 530, "Accounting ", $black);
    imagestring($im, 3, 535, 542, "code", $black);
    imagestring($im, 3, 535, 554, "of Service", $black);
    imagestring($im, 3, 660, 530, "Rate per unit(Rs)", $black);
    imagestring($im, 3, 808, 530, "Unit", $black);
    imagestring($im, 3, 900, 530, "Total", $black);

    //imagestring($im, 3, 118,  596, "Charges Towards Training Program", $black);
    //imagestring($im, 3, 535,  596, $invoice_info[0]['service_code'], $black);
    //imagestring($im, 3, 700,  596, $invoice_info[0]['fee_amt'], $black);
    //imagestring($im, 3, 815,  596, $invoice_info[0]['qty'], $black);
    //imagestring($im, 3, 900,  596, $invoice_info[0]['fee_amt'], $black);
    //imagestring($im, 3, 535,  820, "Total", $black);

    if (!empty($sub_1)) {
        imagestring($im, 3, 60, 596, "1", $black);
        imagestring($im, 3, 118, 596, $sub_1[0]["sub_name"], $black);
        imagestring($im, 3, 535, 596, $invoice_info[0]["service_code"], $black);
        imagestring(
            $im,
            3,
            690,
            596,
            $CI->session->userdata["invoice_info"]["sub_fee1"] . ".00",
            $black
        );
        imagestring($im, 3, 815, 596, $invoice_info[0]["qty"], $black);
        imagestring(
            $im,
            3,
            900,
            596,
            $CI->session->userdata["invoice_info"]["sub_fee1"] . ".00",
            $black
        );
    }
    if (!empty($sub_2)) {
        imagestring($im, 3, 60, 616, "2", $black);
        imagestring($im, 3, 118, 616, $sub_2[0]["sub_name"], $black);
        imagestring($im, 3, 535, 616, $invoice_info[0]["service_code"], $black);
        imagestring(
            $im,
            3,
            690,
            616,
            $CI->session->userdata["invoice_info"]["sub_fee2"] . ".00",
            $black
        );
        imagestring($im, 3, 815, 616, $invoice_info[0]["qty"], $black);
        imagestring(
            $im,
            3,
            900,
            616,
            $CI->session->userdata["invoice_info"]["sub_fee2"] . ".00",
            $black
        );
    }
    if (!empty($sub_3)) {
        imagestring($im, 3, 60, 636, "3", $black);
        imagestring($im, 3, 118, 636, $sub_3[0]["sub_name"], $black);
        imagestring($im, 3, 535, 636, $invoice_info[0]["service_code"], $black);
        imagestring(
            $im,
            3,
            690,
            636,
            $CI->session->userdata["invoice_info"]["sub_fee3"] . ".00",
            $black
        );
        imagestring($im, 3, 815, 636, $invoice_info[0]["qty"], $black);
        imagestring(
            $im,
            3,
            900,
            636,
            $CI->session->userdata["invoice_info"]["sub_fee3"] . ".00",
            $black
        );
    }
    // Added by Pooja Mane to display 4 subject on invoice - 23-04-2024 //
    if (!empty($sub_4)) {
        imagestring($im, 3, 60, 656, "4", $black);
        imagestring($im, 3, 118, 656, $sub_4[0]["sub_name"], $black);
        imagestring($im, 3, 535, 656, $invoice_info[0]["service_code"], $black);
        imagestring(
            $im,
            3,
            690,
            656,
            $CI->session->userdata["invoice_info"]["sub_fee4"] . ".00",
            $black
        );
        imagestring($im, 3, 815, 656, $invoice_info[0]["qty"], $black);
        imagestring(
            $im,
            3,
            900,
            656,
            $CI->session->userdata["invoice_info"]["sub_fee4"] . ".00",
            $black
        );
    }
    // Added by Pooja Mane to display 4 subject on invoice - 23-04-2024 //
    if ($CI->session->userdata["invoice_info"]["zone_code"] == "CO") {
        if ($invoice_info[0]["state_of_center"] == "MAH") {
            imagestring($im, 3, 118, 606, " ", $black);
            imagestring($im, 3, 118, 660, "CGST ", $black);
            imagestring(
                $im,
                3,
                690,
                660,
                $invoice_info[0]["cgst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 660, $invoice_info[0]["cgst_amt"], $black);
            imagestring($im, 3, 118, 680, "SGST ", $black);
            imagestring(
                $im,
                3,
                690,
                680,
                $invoice_info[0]["sgst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 680, $invoice_info[0]["sgst_amt"], $black);

            imagestring($im, 3, 118, 700, "IGST ", $black);
            imagestring($im, 3, 690, 700, "-", $black);
            imagestring($im, 3, 900, 700, "-", $black);
            $wordamt = contact_classes_word($invoice_info[0]["cs_total"]);
        }

        // && $invoice_info[0]['state_of_center'] != 'JAM'
        if ($invoice_info[0]["state_of_center"] != "MAH") {
            imagestring($im, 3, 118, 606, " ", $black);
            imagestring($im, 3, 118, 646, "CGST ", $black);
            imagestring($im, 3, 118, 666, "SGST ", $black);
            imagestring($im, 3, 690, 646, "-", $black);
            imagestring($im, 3, 690, 666, "-", $black);
            imagestring($im, 3, 118, 686, "IGST ", $black);
            imagestring(
                $im,
                3,
                690,
                686,
                $invoice_info[0]["igst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 686, $invoice_info[0]["igst_amt"], $black);
            $wordamt = contact_classes_word($invoice_info[0]["igst_total"]);
        }

        /*if($invoice_info[0]['state_of_center'] == 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "inter-state", $black);
		imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
		$wordamt =contact_classes_word($invoice_info[0]['igst_total']);
	}*/

        imagestring($im, 3, 535, 820, "Total(Rs.)", $black);
        if ($invoice_info[0]["state_of_center"] == "MAH") {
            imagestring($im, 3, 900, 820, $invoice_info[0]["cs_total"], $black);
        } elseif ($invoice_info[0]["state_of_center"] != "MAH") {
            imagestring(
                $im,
                3,
                900,
                820,
                $invoice_info[0]["igst_total"],
                $black
            );
        }
    } elseif ($CI->session->userdata["invoice_info"]["zone_code"] == "NZ") {
        
        if ($invoice_info[0]["state_of_center"] == "DEL") 
        {
            if($state_info[0]['state_code'] == "DEL")
            {
                imagestring($im, 3, 118, 700, " ", $black);
                imagestring($im, 3, 118, 740, "CGST ", $black);
                imagestring($im, 3, 690, 740, $invoice_info[0]["cgst_rate"] . "%", $black);
                imagestring($im, 3, 900, 740, $invoice_info[0]["cgst_amt"], $black);
                imagestring($im, 3, 118, 760, "SGST ", $black);
                imagestring($im, 3, 690, 760, $invoice_info[0]["sgst_rate"] . "%", $black);
                imagestring($im, 3, 900, 760, $invoice_info[0]["sgst_amt"], $black);
                imagestring($im, 3, 118, 780, "IGST ", $black);
                imagestring($im, 3, 690, 780, "-", $black);
                imagestring($im, 3, 900, 780, "-", $black);
                $wordamt = contact_classes_word($invoice_info[0]["cs_total"]);
            }
            else{
                imagestring($im, 3, 118, 700, " ", $black);
                imagestring($im, 3, 118, 686, "CGST ", $black);
                imagestring($im, 3, 118, 706, "SGST ", $black);
                imagestring($im, 3, 690, 686, "-", $black);
                imagestring($im, 3, 690, 706, "-", $black);
                imagestring($im, 3, 118, 726, "IGST ", $black);
                imagestring($im, 3, 690, 726, $invoice_info[0]["igst_rate"] . "%", $black);
                imagestring($im, 3, 900, 726, $invoice_info[0]["igst_amt"], $black);
                $wordamt = contact_classes_word($invoice_info[0]["igst_total"]);
            }
        }

        // && $invoice_info[0]['state_of_center'] != 'JAM'
        if ($invoice_info[0]["state_of_center"] != "DEL") {
            imagestring($im, 3, 118, 700, " ", $black);
            imagestring($im, 3, 118, 686, "CGST ", $black);
            imagestring($im, 3, 118, 706, "SGST ", $black);
            imagestring($im, 3, 690, 686, "-", $black);
            imagestring($im, 3, 690, 706, "-", $black);
            imagestring($im, 3, 118, 726, "IGST ", $black);
            imagestring($im, 3, 690, 726, $invoice_info[0]["igst_rate"] . "%", $black);
            imagestring($im, 3, 900, 726, $invoice_info[0]["igst_amt"], $black);
            $wordamt = contact_classes_word($invoice_info[0]["igst_total"]);
        }

        /*if($invoice_info[0]['state_of_center'] == 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "inter-state", $black);
		imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
		$wordamt =contact_classes_word($invoice_info[0]['igst_total']);
	}*/

        imagestring($im, 3, 535, 820, "Total(Rs.)", $black);
        if ($invoice_info[0]["state_of_center"] == "DEL") {
            if($state_info[0]['state_code'] == "DEL")
            {  
                imagestring($im, 3, 900, 820, $invoice_info[0]["cs_total"], $black);
            }else
            { 
                imagestring($im, 3, 900, 820, $invoice_info[0]["igst_total"], $black);
            }
        } elseif ($invoice_info[0]["state_of_center"] != "DEL") {
            imagestring($im, 3, 900, 820, $invoice_info[0]["igst_total"], $black );
        }
    } elseif ($CI->session->userdata["invoice_info"]["zone_code"] == "EZ") {
        if ($invoice_info[0]["state_of_center"] == "WES") {
            imagestring($im, 3, 118, 606, " ", $black);
            imagestring($im, 3, 118, 660, "CGST ", $black);
            imagestring(
                $im,
                3,
                690,
                660,
                $invoice_info[0]["cgst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 660, $invoice_info[0]["cgst_amt"], $black);
            imagestring($im, 3, 118, 680, "SGST ", $black);
            imagestring(
                $im,
                3,
                690,
                680,
                $invoice_info[0]["sgst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 680, $invoice_info[0]["sgst_amt"], $black);

            imagestring($im, 3, 118, 700, "IGST ", $black);
            imagestring($im, 3, 690, 700, "-", $black);
            imagestring($im, 3, 900, 700, "-", $black);
            $wordamt = contact_classes_word($invoice_info[0]["cs_total"]);
        }

        // && $invoice_info[0]['state_of_center'] != 'JAM'
        if ($invoice_info[0]["state_of_center"] != "WES") {
            imagestring($im, 3, 118, 606, " ", $black);
            imagestring($im, 3, 118, 646, "CGST ", $black);
            imagestring($im, 3, 118, 666, "SGST ", $black);
            imagestring($im, 3, 690, 646, "-", $black);
            imagestring($im, 3, 690, 666, "-", $black);
            imagestring($im, 3, 118, 686, "IGST ", $black);
            imagestring(
                $im,
                3,
                690,
                686,
                $invoice_info[0]["igst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 686, $invoice_info[0]["igst_amt"], $black);
            $wordamt = contact_classes_word($invoice_info[0]["igst_total"]);
        }

        /*if($invoice_info[0]['state_of_center'] == 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "inter-state", $black);
		imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
		$wordamt =contact_classes_word($invoice_info[0]['igst_total']);
	}*/

        imagestring($im, 3, 535, 820, "Total(Rs.)", $black);
        if ($invoice_info[0]["state_of_center"] == "WES") {
            imagestring($im, 3, 900, 820, $invoice_info[0]["cs_total"], $black);
        } elseif ($invoice_info[0]["state_of_center"] != "WES") {
            imagestring(
                $im,
                3,
                900,
                820,
                $invoice_info[0]["igst_total"],
                $black
            );
        }
    } elseif ($CI->session->userdata["invoice_info"]["zone_code"] == "SZ") {
        if ($invoice_info[0]["state_of_center"] == "TAM") {
            imagestring($im, 3, 118, 606, " ", $black);
            imagestring($im, 3, 118, 660, "CGST ", $black);
            imagestring(
                $im,
                3,
                690,
                660,
                $invoice_info[0]["cgst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 660, $invoice_info[0]["cgst_amt"], $black);
            imagestring($im, 3, 118, 680, "SGST ", $black);
            imagestring(
                $im,
                3,
                690,
                680,
                $invoice_info[0]["sgst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 680, $invoice_info[0]["sgst_amt"], $black);

            imagestring($im, 3, 118, 700, "IGST ", $black);
            imagestring($im, 3, 690, 700, "-", $black);
            imagestring($im, 3, 900, 700, "-", $black);
            $wordamt = contact_classes_word($invoice_info[0]["cs_total"]);
        }

        // && $invoice_info[0]['state_of_center'] != 'JAM'
        if ($invoice_info[0]["state_of_center"] != "TAM") {
            imagestring($im, 3, 118, 606, " ", $black);
            imagestring($im, 3, 118, 646, "CGST ", $black);
            imagestring($im, 3, 118, 666, "SGST ", $black);
            imagestring($im, 3, 690, 646, "-", $black);
            imagestring($im, 3, 690, 666, "-", $black);
            imagestring($im, 3, 118, 686, "IGST ", $black);
            imagestring(
                $im,
                3,
                690,
                686,
                $invoice_info[0]["igst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 686, $invoice_info[0]["igst_amt"], $black);
            $wordamt = contact_classes_word($invoice_info[0]["igst_total"]);
        }

        /*if($invoice_info[0]['state_of_center'] == 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "inter-state", $black);
		imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
		$wordamt =contact_classes_word($invoice_info[0]['igst_total']);
	}*/

        imagestring($im, 3, 535, 820, "Total", $black);
        if ($invoice_info[0]["state_of_center"] == "TAM") {
            imagestring($im, 3, 900, 820, $invoice_info[0]["cs_total"], $black);
        } elseif ($invoice_info[0]["state_of_center"] != "TAM") {
            imagestring(
                $im,
                3,
                900,
                820,
                $invoice_info[0]["igst_total"],
                $black
            );
        }
    }
    imagestring(
        $im,
        3,
        40,
        860,
        "Amount in words : " . $wordamt . " only",
        $black
    );
    imagestring(
        $im,
        3,
        650,
        880,
        "For Indian Institute of Banking & Finance",
        $black
    );
    imagestring($im, 3, 720, 950, "Authorised Signatory", $black);
    imagestring($im, 3, 40, 900, "Reverse charge applicable :", $black);
    imagestring($im, 3, 260, 900, "Y/N", $black);
    imagestring($im, 3, 300, 900, "NO", $black);
    imagestring($im, 3, 40, 930, "% of Tax payable under", $black);
    imagestring($im, 3, 280, 930, "% ---", $black);
    imagestring($im, 3, 350, 930, "Rs.---", $black);

    if ($CI->session->userdata["invoice_info"]["zone_code"] == "CO") {
        $savepath = base_url() . "uploads/contact_classes_invoice/user/CO/";
        //$imagename = 'new_dra.jpg';
        $ino = str_replace("/", "_", $invoice_info[0]["invoice_no"]);
        $imagename = $ino . ".jpg";
        $update_data = ["invoice_image" => $imagename];
        $CI->master_model->updateRecord("exam_invoice", $update_data, [
            "invoice_id" => $invoice_no,
        ]);

        if ($invoice_info[0]["cgst_rate"] <= 0.0) {
            $cgst_rate = "-";
        } else {
            $cgst_rate = $invoice_info[0]["cgst_rate"] . "%";
        }
        if ($invoice_info[0]["cgst_amt"] <= 0.0) {
            $cgst_amt = "-";
        } else {
            $cgst_amt = $invoice_info[0]["cgst_amt"];
        }
        if ($invoice_info[0]["sgst_rate"] <= 0.0) {
            $sgst_rate = "-";
        } else {
            $sgst_rate = $invoice_info[0]["sgst_rate"] . "%";
        }
        if ($invoice_info[0]["sgst_amt"] <= 0.0) {
            $sgst_amt = "-";
        } else {
            $sgst_amt = $invoice_info[0]["sgst_amt"];
        }
        if ($invoice_info[0]["igst_rate"] <= 0.0) {
            $igst_rate = "-";
        } else {
            $igst_rate = $invoice_info[0]["igst_rate"] . "%";
        }
        if ($invoice_info[0]["igst_amt"] <= 0.0) {
            $igst_amt = "-";
        } else {
            $igst_amt = $invoice_info[0]["igst_amt"];
        }

        imagepng($im, "uploads/contact_classes_invoice/user/CO/" . $imagename);
        $png = @imagecreatefromjpeg("assets/images/sign.jpg");
        $png2 = @imagecreatefromjpeg("assets/images/iibf_logo_short.jpg");
        $jpeg = @imagecreatefromjpeg(
            "uploads/contact_classes_invoice/user/CO/" .
                $zone_code .
                "/" .
                $imagename
        );
        @imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
        @imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
        imagepng($im, "uploads/contact_classes_invoice/user/CO/" . $imagename);
        imagedestroy($im);
    } elseif ($CI->session->userdata["invoice_info"]["zone_code"] == "NZ") {
        $savepath = base_url() . "uploads/contact_classes_invoice/user/CO/";
        //$imagename = 'new_dra.jpg';
        $ino = str_replace("/", "_", $invoice_info[0]["invoice_no"]);
        $imagename = $ino . ".jpg";
        $update_data = ["invoice_image" => $imagename];
        $CI->master_model->updateRecord("exam_invoice", $update_data, [
            "invoice_id" => $invoice_no,
        ]);

        if ($invoice_info[0]["cgst_rate"] <= 0.0) {
            $cgst_rate = "-";
        } else {
            $cgst_rate = $invoice_info[0]["cgst_rate"] . "%";
        }
        if ($invoice_info[0]["cgst_amt"] <= 0.0) {
            $cgst_amt = "-";
        } else {
            $cgst_amt = $invoice_info[0]["cgst_amt"];
        }
        if ($invoice_info[0]["sgst_rate"] <= 0.0) {
            $sgst_rate = "-";
        } else {
            $sgst_rate = $invoice_info[0]["sgst_rate"] . "%";
        }
        if ($invoice_info[0]["sgst_amt"] <= 0.0) {
            $sgst_amt = "-";
        } else {
            $sgst_amt = $invoice_info[0]["sgst_amt"];
        }
        if ($invoice_info[0]["igst_rate"] <= 0.0) {
            $igst_rate = "-";
        } else {
            $igst_rate = $invoice_info[0]["igst_rate"] . "%";
        }
        if ($invoice_info[0]["igst_amt"] <= 0.0) {
            $igst_amt = "-";
        } else {
            $igst_amt = $invoice_info[0]["igst_amt"];
        }

        imagepng($im, "uploads/contact_classes_invoice/user/NZ/" . $imagename);
        $png = @imagecreatefromjpeg("assets/images/sign.jpg");
        $png2 = @imagecreatefromjpeg("assets/images/iibf_logo_short.jpg");
        $jpeg = @imagecreatefromjpeg(
            "uploads/contact_classes_invoice/user/NZ/" .
                $zone_code .
                "/" .
                $imagename
        );
        @imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
        @imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
        imagepng($im, "uploads/contact_classes_invoice/user/NZ/" . $imagename);
        imagedestroy($im);
    } elseif ($CI->session->userdata["invoice_info"]["zone_code"] == "EZ") {
        $savepath = base_url() . "uploads/contact_classes_invoice/user/CO/";
        //$imagename = 'new_dra.jpg';
        $ino = str_replace("/", "_", $invoice_info[0]["invoice_no"]);
        $imagename = $ino . ".jpg";
        $update_data = ["invoice_image" => $imagename];
        $CI->master_model->updateRecord("exam_invoice", $update_data, [
            "invoice_id" => $invoice_no,
        ]);

        if ($invoice_info[0]["cgst_rate"] <= 0.0) {
            $cgst_rate = "-";
        } else {
            $cgst_rate = $invoice_info[0]["cgst_rate"] . "%";
        }
        if ($invoice_info[0]["cgst_amt"] <= 0.0) {
            $cgst_amt = "-";
        } else {
            $cgst_amt = $invoice_info[0]["cgst_amt"];
        }
        if ($invoice_info[0]["sgst_rate"] <= 0.0) {
            $sgst_rate = "-";
        } else {
            $sgst_rate = $invoice_info[0]["sgst_rate"] . "%";
        }
        if ($invoice_info[0]["sgst_amt"] <= 0.0) {
            $sgst_amt = "-";
        } else {
            $sgst_amt = $invoice_info[0]["sgst_amt"];
        }
        if ($invoice_info[0]["igst_rate"] <= 0.0) {
            $igst_rate = "-";
        } else {
            $igst_rate = $invoice_info[0]["igst_rate"] . "%";
        }
        if ($invoice_info[0]["igst_amt"] <= 0.0) {
            $igst_amt = "-";
        } else {
            $igst_amt = $invoice_info[0]["igst_amt"];
        }

        imagepng($im, "uploads/contact_classes_invoice/user/EZ/" . $imagename);
        $png = @imagecreatefromjpeg("assets/images/sign.jpg");
        $png2 = @imagecreatefromjpeg("assets/images/iibf_logo_short.jpg");
        $jpeg = @imagecreatefromjpeg(
            "uploads/contact_classes_invoice/user/EZ/" .
                $zone_code .
                "/" .
                $imagename
        );
        @imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
        @imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
        imagepng($im, "uploads/contact_classes_invoice/user/EZ/" . $imagename);
        imagedestroy($im);
    } elseif ($CI->session->userdata["invoice_info"]["zone_code"] == "SZ") {
        $savepath = base_url() . "uploads/contact_classes_invoice/user/CO/";
        //$imagename = 'new_dra.jpg';
        $ino = str_replace("/", "_", $invoice_info[0]["invoice_no"]);
        $imagename = $ino . ".jpg";
        $update_data = ["invoice_image" => $imagename];
        $CI->master_model->updateRecord("exam_invoice", $update_data, [
            "invoice_id" => $invoice_no,
        ]);

        if ($invoice_info[0]["cgst_rate"] <= 0.0) {
            $cgst_rate = "-";
        } else {
            $cgst_rate = $invoice_info[0]["cgst_rate"] . "%";
        }
        if ($invoice_info[0]["cgst_amt"] <= 0.0) {
            $cgst_amt = "-";
        } else {
            $cgst_amt = $invoice_info[0]["cgst_amt"];
        }
        if ($invoice_info[0]["sgst_rate"] <= 0.0) {
            $sgst_rate = "-";
        } else {
            $sgst_rate = $invoice_info[0]["sgst_rate"] . "%";
        }
        if ($invoice_info[0]["sgst_amt"] <= 0.0) {
            $sgst_amt = "-";
        } else {
            $sgst_amt = $invoice_info[0]["sgst_amt"];
        }
        if ($invoice_info[0]["igst_rate"] <= 0.0) {
            $igst_rate = "-";
        } else {
            $igst_rate = $invoice_info[0]["igst_rate"] . "%";
        }
        if ($invoice_info[0]["igst_amt"] <= 0.0) {
            $igst_amt = "-";
        } else {
            $igst_amt = $invoice_info[0]["igst_amt"];
        }

        imagepng($im, "uploads/contact_classes_invoice/user/SZ/" . $imagename);
        $png = @imagecreatefromjpeg("assets/images/sign.jpg");
        $png2 = @imagecreatefromjpeg("assets/images/iibf_logo_short.jpg");
        $jpeg = @imagecreatefromjpeg(
            "uploads/contact_classes_invoice/user/SZ/" .
                $zone_code .
                "/" .
                $imagename
        );
        @imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
        @imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
        imagepng($im, "uploads/contact_classes_invoice/user/SZ/" . $imagename);
        imagedestroy($im);
    }

    /****************************** image for supplier ***********************************/
    ($im = @imagecreate(1000, 1000)) or
        die("Cannot Initialize new GD image stream");
    $background_color = imagecolorallocate($im, 255, 255, 255); // white
    $black = imagecolorallocate($im, 0, 0, 0); // black

    //imageline ($im,   x1,  y1, x2, y2, color);
    imageline($im, 20, 20, 980, 20, $black); // line-1
    imageline($im, 20, 980, 980, 980, $black); // line-2
    imageline($im, 20, 20, 20, 980, $black); // line-3
    imageline($im, 980, 20, 980, 980, $black); // line-4
    imageline($im, 20, 160, 980, 160, $black); // line-5
    imageline($im, 20, 200, 980, 200, $black); // line-6
    imageline($im, 20, 480, 980, 480, $black); // line-7
    imageline($im, 20, 520, 980, 520, $black); // line-8
    imageline($im, 20, 580, 980, 580, $black); // line-9
    imageline($im, 20, 850, 980, 850, $black); // line-10
    imageline($im, 650, 200, 650, 480, $black); // line-11
    imageline($im, 85, 520, 85, 850, $black); // line-12
    imageline($im, 500, 520, 500, 850, $black); // line-13
    imageline($im, 650, 520, 650, 850, $black); // line-14
    imageline($im, 785, 520, 785, 850, $black); // line-15
    imageline($im, 860, 520, 860, 850, $black); // line-16
    imageline($im, 40, 880, 625, 880, $black); // line-17

    $year = date("Y");
    //imagestring(image,font,x,y,string,color);
    imagestring(
        $im,
        5,
        100,
        40,
        "INDIAN INSTITUTE OF BANKING & FINANCE",
        $black
    );
    imagestring($im, 3, 100, 60, "ISO 21001:2018 Certified", $black);
    imagestring($im, 3, 100, 80, "(CINU9111OMH1928GAP1391)", $black);
    imagestring(
        $im,
        3,
        100,
        100,
        "Registered office Kohinoor City, Commercial - II,  Tower 1, 2nd Floor, Kirole Road,",
        $black
    );
    imagestring(
        $im,
        3,
        100,
        120,
        "Off LBS Marg, Kurla(West), Mumbai - 400 070 , Maharashtra",
        $black
    );
    imagestring($im, 3, 100, 140, "www.iibf.org.in", $black);
    imagestring($im, 5, 400, 170, "TAX INVOICE CUM RECEIPT", $black);

    imagestring($im, 5, 40, 220, "Details of service recipient", $black);
    imagestring($im, 5, 670, 220, "DUPLICATE FOR SUPPLIER", $black);
    imagestring(
        $im,
        3,
        40,
        260,
        "Name of service Recipient:" . $member_name,
        $black
    );
    imagestring($im, 3, 40, 280, "Address: " . $address_1, $black);
    imagestring($im, 3, 40, 300, $address_2, $black);
    imagestring(
        $im,
        3,
        40,
        320,
        "State: " . $invoice_info[0]["state_name"],
        $black
    );
    imagestring(
        $im,
        3,
        40,
        340,
        "State Code: " . $invoice_info[0]["state_code"],
        $black
    );
    imagestring(
        $im,
        3,
        40,
        360,
        "GST No: " . $invoice_info[0]["gstin_no"],
        $black
    );
    imagestring(
        $im,
        3,
        40,
        380,
        "Transaction Number : " . $invoice_info[0]["transaction_no"],
        $black
    );

    imagestring(
        $im,
        3,
        40,
        400,
        "Course name : " . $program_name[0]["course_name"],
        $black
    );

    imagestring(
        $im,
        3,
        40,
        420,
        "Center code : " . $invoice_info[0]["center_code"],
        $black
    );
    imagestring(
        $im,
        3,
        40,
        440,
        "Center name : " . $invoice_info[0]["center_name"],
        $black
    );

    imagestring(
        $im,
        3,
        670,
        260,
        "Invoice Number: " . $invoice_info[0]["invoice_no"],
        $black
    );
    imagestring(
        $im,
        3,
        670,
        280,
        "Date: " .
            date("d-m-Y", strtotime($invoice_info[0]["date_of_invoice"])),
        $black
    );
    if (
        $invoice_info[0]["gstin_no"] != "" &&
        $invoice_info[0]["gstin_no"] != 0
    ) {
        $gstn = $invoice_info[0]["gstin_no"];
    } else {
        $gstn = "-";
    }
    imagestring($im, 3, 670, 300, "GSTIN - 27AAATT3309D1ZS ", $black);

    imagestring($im, 3, 40, 530, "Sr.No", $black);
    imagestring($im, 3, 118, 530, "Description of Service", $black);
    imagestring($im, 3, 535, 530, "Accounting ", $black);
    imagestring($im, 3, 535, 542, "code", $black);
    imagestring($im, 3, 535, 554, "of Service", $black);
    imagestring($im, 3, 660, 530, "Rate per unit(Rs)", $black);
    imagestring($im, 3, 808, 530, "Unit", $black);
    imagestring($im, 3, 900, 530, "Total(Rs.)", $black);

    //imagestring($im, 3, 118,  596, "Charges Towards Training Program", $black);
    //imagestring($im, 3, 535,  596, $invoice_info[0]['service_code'], $black);
    //imagestring($im, 3, 700,  596, $invoice_info[0]['fee_amt'], $black);
    //imagestring($im, 3, 815,  596, $invoice_info[0]['qty'], $black);
    //imagestring($im, 3, 900,  596, $invoice_info[0]['fee_amt'], $black);
    //imagestring($im, 3, 535,  820, "Total", $black);

    if (!empty($sub_1)) {
        imagestring($im, 3, 60, 596, "1", $black);
        imagestring($im, 3, 118, 596, $sub_1[0]["sub_name"], $black);
        imagestring($im, 3, 535, 596, $invoice_info[0]["service_code"], $black);
        imagestring(
            $im,
            3,
            690,
            596,
            $CI->session->userdata["invoice_info"]["sub_fee1"] . ".00",
            $black
        );
        imagestring($im, 3, 815, 596, $invoice_info[0]["qty"], $black);
        imagestring(
            $im,
            3,
            900,
            596,
            $CI->session->userdata["invoice_info"]["sub_fee1"] . ".00",
            $black
        );
    }
    if (!empty($sub_2)) {
        imagestring($im, 3, 60, 616, "2", $black);
        imagestring($im, 3, 118, 616, $sub_2[0]["sub_name"], $black);
        imagestring($im, 3, 535, 616, $invoice_info[0]["service_code"], $black);
        imagestring(
            $im,
            3,
            690,
            616,
            $CI->session->userdata["invoice_info"]["sub_fee2"] . ".00",
            $black
        );
        imagestring($im, 3, 815, 616, $invoice_info[0]["qty"], $black);
        imagestring(
            $im,
            3,
            900,
            616,
            $CI->session->userdata["invoice_info"]["sub_fee2"] . ".00",
            $black
        );
    }
    if (!empty($sub_3)) {
        imagestring($im, 3, 60, 636, "3", $black);
        imagestring($im, 3, 118, 636, $sub_3[0]["sub_name"], $black);
        imagestring($im, 3, 535, 636, $invoice_info[0]["service_code"], $black);
        imagestring(
            $im,
            3,
            690,
            636,
            $CI->session->userdata["invoice_info"]["sub_fee3"] . ".00",
            $black
        );
        imagestring($im, 3, 815, 636, $invoice_info[0]["qty"], $black);
        imagestring(
            $im,
            3,
            900,
            636,
            $CI->session->userdata["invoice_info"]["sub_fee3"] . ".00",
            $black
        );
    }

    if ($CI->session->userdata["invoice_info"]["zone_code"] == "CO") {
        if ($invoice_info[0]["state_of_center"] == "MAH") {
            imagestring($im, 3, 118, 606, " ", $black);
            imagestring($im, 3, 118, 660, "CGST ", $black);
            imagestring(
                $im,
                3,
                690,
                660,
                $invoice_info[0]["cgst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 660, $invoice_info[0]["cgst_amt"], $black);
            imagestring($im, 3, 118, 680, "SGST ", $black);
            imagestring(
                $im,
                3,
                690,
                680,
                $invoice_info[0]["sgst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 680, $invoice_info[0]["sgst_amt"], $black);

            imagestring($im, 3, 118, 700, "IGST ", $black);
            imagestring($im, 3, 690, 700, "-", $black);
            imagestring($im, 3, 900, 700, "-", $black);
            $wordamt = contact_classes_word($invoice_info[0]["cs_total"]);
        }

        // && $invoice_info[0]['state_of_center'] != 'JAM'
        if ($invoice_info[0]["state_of_center"] != "MAH") {
            imagestring($im, 3, 118, 606, " ", $black);
            imagestring($im, 3, 118, 646, "CGST ", $black);
            imagestring($im, 3, 118, 666, "SGST ", $black);
            imagestring($im, 3, 690, 646, "-", $black);
            imagestring($im, 3, 690, 666, "-", $black);
            imagestring($im, 3, 118, 686, "IGST ", $black);
            imagestring(
                $im,
                3,
                690,
                686,
                $invoice_info[0]["igst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 686, $invoice_info[0]["igst_amt"], $black);
            $wordamt = contact_classes_word($invoice_info[0]["igst_total"]);
        }

        /*if($invoice_info[0]['state_of_center'] == 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "inter-state", $black);
		imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
		$wordamt =contact_classes_word($invoice_info[0]['igst_total']);
	}*/

        imagestring($im, 3, 535, 820, "Total", $black);
        if ($invoice_info[0]["state_of_center"] == "MAH") {
            imagestring($im, 3, 900, 820, $invoice_info[0]["cs_total"], $black);
        } elseif ($invoice_info[0]["state_of_center"] != "MAH") {
            imagestring(
                $im,
                3,
                900,
                820,
                $invoice_info[0]["igst_total"],
                $black
            );
        }
    } elseif ($CI->session->userdata["invoice_info"]["zone_code"] == "NZ") {

        if ($invoice_info[0]["state_of_center"] == "DEL") 
        {
            if($state_info[0]['state_code'] == "DEL")
            {
                imagestring($im, 3, 118, 700, " ", $black);
                imagestring($im, 3, 118, 740, "CGST ", $black);
                imagestring($im, 3, 690, 740, $invoice_info[0]["cgst_rate"] . "%", $black);
                imagestring($im, 3, 900, 740, $invoice_info[0]["cgst_amt"], $black);
                imagestring($im, 3, 118, 760, "SGST ", $black);
                imagestring($im, 3, 690, 760, $invoice_info[0]["sgst_rate"] . "%", $black);
                imagestring($im, 3, 900, 760, $invoice_info[0]["sgst_amt"], $black);
                imagestring($im, 3, 118, 780, "IGST ", $black);
                imagestring($im, 3, 690, 780, "-", $black);
                imagestring($im, 3, 900, 780, "-", $black);
                $wordamt = contact_classes_word($invoice_info[0]["cs_total"]);
            }
            else{
                imagestring($im, 3, 118, 700, " ", $black);
                imagestring($im, 3, 118, 686, "CGST ", $black);
                imagestring($im, 3, 118, 706, "SGST ", $black);
                imagestring($im, 3, 690, 686, "-", $black);
                imagestring($im, 3, 690, 706, "-", $black);
                imagestring($im, 3, 118, 726, "IGST ", $black);
                imagestring($im, 3, 690, 726, $invoice_info[0]["igst_rate"] . "%", $black);
                imagestring($im, 3, 900, 726, $invoice_info[0]["igst_amt"], $black);
                $wordamt = contact_classes_word($invoice_info[0]["igst_total"]);
            }
        }

        // && $invoice_info[0]['state_of_center'] != 'JAM'
        if ($invoice_info[0]["state_of_center"] != "DEL") {
            imagestring($im, 3, 118, 700, " ", $black);
            imagestring($im, 3, 118, 686, "CGST ", $black);
            imagestring($im, 3, 118, 706, "SGST ", $black);
            imagestring($im, 3, 690, 686, "-", $black);
            imagestring($im, 3, 690, 706, "-", $black);
            imagestring($im, 3, 118, 726, "IGST ", $black);
            imagestring($im, 3, 690, 726, $invoice_info[0]["igst_rate"] . "%", $black);
            imagestring($im, 3, 900, 726, $invoice_info[0]["igst_amt"], $black);
            $wordamt = contact_classes_word($invoice_info[0]["igst_total"]);
        }

        /*if($invoice_info[0]['state_of_center'] == 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "inter-state", $black);
		imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
		$wordamt =contact_classes_word($invoice_info[0]['igst_total']);
	}*/

        imagestring($im, 3, 535, 820, "Total(Rs.)", $black);
        if ($invoice_info[0]["state_of_center"] == "DEL") {
            if($state_info[0]['state_code'] == "DEL")
            {  
                imagestring($im, 3, 900, 820, $invoice_info[0]["cs_total"], $black);
            }else
            { 
                imagestring($im, 3, 900, 820, $invoice_info[0]["igst_total"], $black);
            }
        } elseif ($invoice_info[0]["state_of_center"] != "DEL") {
            imagestring($im, 3, 900, 820, $invoice_info[0]["igst_total"], $black );
        }
    } elseif ($CI->session->userdata["invoice_info"]["zone_code"] == "EZ") {
        if ($invoice_info[0]["state_of_center"] == "WES") {
            imagestring($im, 3, 118, 606, " ", $black);
            imagestring($im, 3, 118, 660, "CGST ", $black);
            imagestring(
                $im,
                3,
                690,
                660,
                $invoice_info[0]["cgst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 660, $invoice_info[0]["cgst_amt"], $black);
            imagestring($im, 3, 118, 680, "SGST ", $black);
            imagestring(
                $im,
                3,
                690,
                680,
                $invoice_info[0]["sgst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 680, $invoice_info[0]["sgst_amt"], $black);

            imagestring($im, 3, 118, 700, "IGST ", $black);
            imagestring($im, 3, 690, 700, "-", $black);
            imagestring($im, 3, 900, 700, "-", $black);
            $wordamt = contact_classes_word($invoice_info[0]["cs_total"]);
        }

        // && $invoice_info[0]['state_of_center'] != 'JAM'
        if ($invoice_info[0]["state_of_center"] != "WES") {
            imagestring($im, 3, 118, 606, " ", $black);
            imagestring($im, 3, 118, 646, "CGST ", $black);
            imagestring($im, 3, 118, 666, "SGST ", $black);
            imagestring($im, 3, 690, 646, "-", $black);
            imagestring($im, 3, 690, 666, "-", $black);
            imagestring($im, 3, 118, 686, "IGST ", $black);
            imagestring(
                $im,
                3,
                690,
                686,
                $invoice_info[0]["igst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 686, $invoice_info[0]["igst_amt"], $black);
            $wordamt = contact_classes_word($invoice_info[0]["igst_total"]);
        }

        /*if($invoice_info[0]['state_of_center'] == 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "inter-state", $black);
		imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
		$wordamt =contact_classes_word($invoice_info[0]['igst_total']);
	}*/

        imagestring($im, 3, 535, 820, "Total(Rs.)", $black);
        if ($invoice_info[0]["state_of_center"] == "WES") {
            imagestring($im, 3, 900, 820, $invoice_info[0]["cs_total"], $black);
        } elseif ($invoice_info[0]["state_of_center"] != "WES") {
            imagestring(
                $im,
                3,
                900,
                820,
                $invoice_info[0]["igst_total"],
                $black
            );
        }
    } elseif ($CI->session->userdata["invoice_info"]["zone_code"] == "SZ") {
        if ($invoice_info[0]["state_of_center"] == "TAM") {
            imagestring($im, 3, 118, 606, " ", $black);
            imagestring($im, 3, 118, 660, "CGST ", $black);
            imagestring(
                $im,
                3,
                690,
                660,
                $invoice_info[0]["cgst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 660, $invoice_info[0]["cgst_amt"], $black);
            imagestring($im, 3, 118, 680, "SGST ", $black);
            imagestring(
                $im,
                3,
                690,
                680,
                $invoice_info[0]["sgst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 680, $invoice_info[0]["sgst_amt"], $black);

            imagestring($im, 3, 118, 700, "IGST ", $black);
            imagestring($im, 3, 690, 700, "-", $black);
            imagestring($im, 3, 900, 700, "-", $black);
            $wordamt = contact_classes_word($invoice_info[0]["cs_total"]);
        }

        // && $invoice_info[0]['state_of_center'] != 'JAM'
        if ($invoice_info[0]["state_of_center"] != "TAM") {
            imagestring($im, 3, 118, 606, " ", $black);
            imagestring($im, 3, 118, 646, "CGST ", $black);
            imagestring($im, 3, 118, 666, "SGST ", $black);
            imagestring($im, 3, 690, 646, "-", $black);
            imagestring($im, 3, 690, 666, "-", $black);
            imagestring($im, 3, 118, 686, "IGST ", $black);
            imagestring(
                $im,
                3,
                690,
                686,
                $invoice_info[0]["igst_rate"] . "%",
                $black
            );
            imagestring($im, 3, 900, 686, $invoice_info[0]["igst_amt"], $black);
            $wordamt = contact_classes_word($invoice_info[0]["igst_total"]);
        }

        /*if($invoice_info[0]['state_of_center'] == 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "inter-state", $black);
		imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
		$wordamt =contact_classes_word($invoice_info[0]['igst_total']);
	}*/

        imagestring($im, 3, 535, 820, "Total(Rs.)", $black);
        if ($invoice_info[0]["state_of_center"] == "TAM") {
            imagestring($im, 3, 900, 820, $invoice_info[0]["cs_total"], $black);
        } elseif ($invoice_info[0]["state_of_center"] != "TAM") {
            imagestring(
                $im,
                3,
                900,
                820,
                $invoice_info[0]["igst_total"],
                $black
            );
        }
    }
    imagestring(
        $im,
        3,
        40,
        860,
        "Amount in words : " . $wordamt . " only",
        $black
    );
    imagestring(
        $im,
        3,
        650,
        880,
        "For Indian Institute of Banking & Finance",
        $black
    );
    imagestring($im, 3, 720, 950, "Authorised Signatory", $black);
    imagestring($im, 3, 40, 900, "Reverse charge applicable :", $black);
    imagestring($im, 3, 260, 900, "Y/N", $black);
    imagestring($im, 3, 300, 900, "NO", $black);
    imagestring($im, 3, 40, 930, "% of Tax payable under", $black);
    imagestring($im, 3, 280, 930, "% ---", $black);
    imagestring($im, 3, 350, 930, "Rs.---", $black);

    if ($CI->session->userdata["invoice_info"]["zone_code"] == "CO") {
        $savepath = base_url() . "uploads/contact_classes_invoice/supplier/CO/";
        //$imagename = 'new_dra.jpg';
        $ino = str_replace("/", "_", $invoice_info[0]["invoice_no"]);
        $imagename = $ino . ".jpg";
        $update_data = ["invoice_image" => $imagename];
        $CI->master_model->updateRecord("exam_invoice", $update_data, [
            "invoice_id" => $invoice_no,
        ]);

        if ($invoice_info[0]["cgst_rate"] <= 0.0) {
            $cgst_rate = "-";
        } else {
            $cgst_rate = $invoice_info[0]["cgst_rate"] . "%";
        }
        if ($invoice_info[0]["cgst_amt"] <= 0.0) {
            $cgst_amt = "-";
        } else {
            $cgst_amt = $invoice_info[0]["cgst_amt"];
        }
        if ($invoice_info[0]["sgst_rate"] <= 0.0) {
            $sgst_rate = "-";
        } else {
            $sgst_rate = $invoice_info[0]["sgst_rate"] . "%";
        }
        if ($invoice_info[0]["sgst_amt"] <= 0.0) {
            $sgst_amt = "-";
        } else {
            $sgst_amt = $invoice_info[0]["sgst_amt"];
        }
        if ($invoice_info[0]["igst_rate"] <= 0.0) {
            $igst_rate = "-";
        } else {
            $igst_rate = $invoice_info[0]["igst_rate"] . "%";
        }
        if ($invoice_info[0]["igst_amt"] <= 0.0) {
            $igst_amt = "-";
        } else {
            $igst_amt = $invoice_info[0]["igst_amt"];
        }

        imagepng(
            $im,
            "uploads/contact_classes_invoice/supplier/CO/" . $imagename
        );
        $png = @imagecreatefromjpeg("assets/images/sign.jpg");
        $png2 = @imagecreatefromjpeg("assets/images/iibf_logo_short.jpg");
        $jpeg = @imagecreatefromjpeg(
            "uploads/contact_classes_invoice/supplier/CO/" .
                $zone_code .
                "/" .
                $imagename
        );
        @imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
        @imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
        imagepng(
            $im,
            "uploads/contact_classes_invoice/supplier/CO/" . $imagename
        );
        imagedestroy($im);
        return $attachpath =
            "uploads/contact_classes_invoice/user/CO/" . $imagename;
    } elseif ($CI->session->userdata["invoice_info"]["zone_code"] == "NZ") {
        $savepath = base_url() . "uploads/contact_classes_invoice/supplier/CO/";
        //$imagename = 'new_dra.jpg';
        $ino = str_replace("/", "_", $invoice_info[0]["invoice_no"]);
        $imagename = $ino . ".jpg";
        //$update_data = array('invoice_image' => $imagename);
        //$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));

        if ($invoice_info[0]["cgst_rate"] <= 0.0) {
            $cgst_rate = "-";
        } else {
            $cgst_rate = $invoice_info[0]["cgst_rate"] . "%";
        }
        if ($invoice_info[0]["cgst_amt"] <= 0.0) {
            $cgst_amt = "-";
        } else {
            $cgst_amt = $invoice_info[0]["cgst_amt"];
        }
        if ($invoice_info[0]["sgst_rate"] <= 0.0) {
            $sgst_rate = "-";
        } else {
            $sgst_rate = $invoice_info[0]["sgst_rate"] . "%";
        }
        if ($invoice_info[0]["sgst_amt"] <= 0.0) {
            $sgst_amt = "-";
        } else {
            $sgst_amt = $invoice_info[0]["sgst_amt"];
        }
        if ($invoice_info[0]["igst_rate"] <= 0.0) {
            $igst_rate = "-";
        } else {
            $igst_rate = $invoice_info[0]["igst_rate"] . "%";
        }
        if ($invoice_info[0]["igst_amt"] <= 0.0) {
            $igst_amt = "-";
        } else {
            $igst_amt = $invoice_info[0]["igst_amt"];
        }

        imagepng(
            $im,
            "uploads/contact_classes_invoice/supplier/NZ/" . $imagename
        );
        $png = @imagecreatefromjpeg("assets/images/sign.jpg");
        $png2 = @imagecreatefromjpeg("assets/images/iibf_logo_short.jpg");
        $jpeg = @imagecreatefromjpeg(
            "uploads/contact_classes_invoice/supplier/NZ/" .
                $zone_code .
                "/" .
                $imagename
        );
        @imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
        @imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
        imagepng(
            $im,
            "uploads/contact_classes_invoice/supplier/NZ/" . $imagename
        );
        imagedestroy($im);
        return $attachpath =
            "uploads/contact_classes_invoice/user/NZ/" . $imagename;
    } elseif ($CI->session->userdata["invoice_info"]["zone_code"] == "EZ") {
        $savepath = base_url() . "uploads/contact_classes_invoice/supplier/CO/";
        //$imagename = 'new_dra.jpg';
        $ino = str_replace("/", "_", $invoice_info[0]["invoice_no"]);
        $imagename = $ino . ".jpg";
        $update_data = ["invoice_image" => $imagename];
        $CI->master_model->updateRecord("exam_invoice", $update_data, [
            "invoice_id" => $invoice_no,
        ]);

        if ($invoice_info[0]["cgst_rate"] <= 0.0) {
            $cgst_rate = "-";
        } else {
            $cgst_rate = $invoice_info[0]["cgst_rate"] . "%";
        }
        if ($invoice_info[0]["cgst_amt"] <= 0.0) {
            $cgst_amt = "-";
        } else {
            $cgst_amt = $invoice_info[0]["cgst_amt"];
        }
        if ($invoice_info[0]["sgst_rate"] <= 0.0) {
            $sgst_rate = "-";
        } else {
            $sgst_rate = $invoice_info[0]["sgst_rate"] . "%";
        }
        if ($invoice_info[0]["sgst_amt"] <= 0.0) {
            $sgst_amt = "-";
        } else {
            $sgst_amt = $invoice_info[0]["sgst_amt"];
        }
        if ($invoice_info[0]["igst_rate"] <= 0.0) {
            $igst_rate = "-";
        } else {
            $igst_rate = $invoice_info[0]["igst_rate"] . "%";
        }
        if ($invoice_info[0]["igst_amt"] <= 0.0) {
            $igst_amt = "-";
        } else {
            $igst_amt = $invoice_info[0]["igst_amt"];
        }

        imagepng(
            $im,
            "uploads/contact_classes_invoice/supplier/EZ/" . $imagename
        );
        $png = @imagecreatefromjpeg("assets/images/sign.jpg");
        $png2 = @imagecreatefromjpeg("assets/images/iibf_logo_short.jpg");
        $jpeg = @imagecreatefromjpeg(
            "uploads/contact_classes_invoice/supplier/EZ/" .
                $zone_code .
                "/" .
                $imagename
        );
        @imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
        @imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
        imagepng(
            $im,
            "uploads/contact_classes_invoice/supplier/EZ/" . $imagename
        );
        imagedestroy($im);
        return $attachpath =
            "uploads/contact_classes_invoice/user/EZ/" . $imagename;
    } elseif ($CI->session->userdata["invoice_info"]["zone_code"] == "SZ") {
        $savepath = base_url() . "uploads/contact_classes_invoice/supplier/CO/";
        //$imagename = 'new_dra.jpg';
        $ino = str_replace("/", "_", $invoice_info[0]["invoice_no"]);
        $imagename = $ino . ".jpg";
        $update_data = ["invoice_image" => $imagename];
        $CI->master_model->updateRecord("exam_invoice", $update_data, [
            "invoice_id" => $invoice_no,
        ]);

        if ($invoice_info[0]["cgst_rate"] <= 0.0) {
            $cgst_rate = "-";
        } else {
            $cgst_rate = $invoice_info[0]["cgst_rate"] . "%";
        }
        if ($invoice_info[0]["cgst_amt"] <= 0.0) {
            $cgst_amt = "-";
        } else {
            $cgst_amt = $invoice_info[0]["cgst_amt"];
        }
        if ($invoice_info[0]["sgst_rate"] <= 0.0) {
            $sgst_rate = "-";
        } else {
            $sgst_rate = $invoice_info[0]["sgst_rate"] . "%";
        }
        if ($invoice_info[0]["sgst_amt"] <= 0.0) {
            $sgst_amt = "-";
        } else {
            $sgst_amt = $invoice_info[0]["sgst_amt"];
        }
        if ($invoice_info[0]["igst_rate"] <= 0.0) {
            $igst_rate = "-";
        } else {
            $igst_rate = $invoice_info[0]["igst_rate"] . "%";
        }
        if ($invoice_info[0]["igst_amt"] <= 0.0) {
            $igst_amt = "-";
        } else {
            $igst_amt = $invoice_info[0]["igst_amt"];
        }

        imagepng(
            $im,
            "uploads/contact_classes_invoice/supplier/SZ/" . $imagename
        );
        $png = @imagecreatefromjpeg("assets/images/sign.jpg");
        $png2 = @imagecreatefromjpeg("assets/images/iibf_logo_short.jpg");
        $jpeg = @imagecreatefromjpeg(
            "uploads/contact_classes_invoice/supplier/SZ/" .
                $zone_code .
                "/" .
                $imagename
        );
        @imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
        @imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
        imagepng(
            $im,
            "uploads/contact_classes_invoice/supplier/SZ/" . $imagename
        );
        imagedestroy($im);
        return $attachpath =
            "uploads/contact_classes_invoice/user/SZ/" . $imagename;
    }
}

function generate_cc_invoice_number($zone_code, $invoice_id)
{
    $last_id = "";
    $CI = &get_instance();

    //$CI->load->model('my_model');
    if ($zone_code == "CO") {
        if ($invoice_id != null) {
            $insert_info = ["invoice_id" => $invoice_id];
            $last_id = str_pad(
                $CI->master_model->insertRecord(
                    "config_contact_classes_CO_invoice",
                    $insert_info,
                    true
                ),
                5,
                "0",
                STR_PAD_LEFT
            );
        }
    } elseif ($zone_code == "NZ") {
        if ($invoice_id != null) {
            $insert_info = ["invoice_id" => $invoice_id];
            echo $last_id = str_pad(
                $CI->master_model->insertRecord(
                    "config_contact_classes_NZ_invoice",
                    $insert_info,
                    true
                ),
                5,
                "0",
                STR_PAD_LEFT
            );
        }
    } elseif ($zone_code == "EZ") {
        if ($invoice_id != null) {
            $insert_info = ["invoice_id" => $invoice_id];
            $last_id = str_pad(
                $CI->master_model->insertRecord(
                    "config_contact_classes_EZ_invoice",
                    $insert_info,
                    true
                ),
                5,
                "0",
                STR_PAD_LEFT
            );
        }
    } elseif ($zone_code == "SZ") {
        if ($invoice_id != null) {
            $insert_info = ["invoice_id" => $invoice_id];
            $last_id = str_pad(
                $CI->master_model->insertRecord(
                    "config_contact_classes_SZ_invoice",
                    $insert_info,
                    true
                ),
                5,
                "0",
                STR_PAD_LEFT
            );
        }
    }
    return $last_id;
}

function generate_contact_classes_invoice_number($invoice_id)
{
    $last_id = "";
    $CI = &get_instance();

    //$CI->load->model('my_model');
    if ($CI->session->userdata["invoice_info"]["zone_code"] == "CO") {
        if ($invoice_id != null) {
            $insert_info = ["invoice_id" => $invoice_id];
            $last_id = str_pad(
                $CI->master_model->insertRecord(
                    "config_contact_classes_CO_invoice",
                    $insert_info,
                    true
                ),
                5,
                "0",
                STR_PAD_LEFT
            );
        }
    } elseif ($CI->session->userdata["invoice_info"]["zone_code"] == "NZ") {
        if ($invoice_id != null) {
            $insert_info = ["invoice_id" => $invoice_id];
            $last_id = str_pad(
                $CI->master_model->insertRecord(
                    "config_contact_classes_NZ_invoice",
                    $insert_info,
                    true
                ),
                5,
                "0",
                STR_PAD_LEFT
            );
        }
    } elseif ($CI->session->userdata["invoice_info"]["zone_code"] == "EZ") {
        if ($invoice_id != null) {
            $insert_info = ["invoice_id" => $invoice_id];
            $last_id = str_pad(
                $CI->master_model->insertRecord(
                    "config_contact_classes_EZ_invoice",
                    $insert_info,
                    true
                ),
                5,
                "0",
                STR_PAD_LEFT
            );
        }
    } elseif ($CI->session->userdata["invoice_info"]["zone_code"] == "SZ") {
        if ($invoice_id != null) {
            $insert_info = ["invoice_id" => $invoice_id];
            $last_id = str_pad(
                $CI->master_model->insertRecord(
                    "config_contact_classes_SZ_invoice",
                    $insert_info,
                    true
                ),
                5,
                "0",
                STR_PAD_LEFT
            );
        }
    }
    return $last_id;
}

function generate_contact_classes_invoice_number_jammu($invoice_id)
{
    $last_id = "";
    $CI = &get_instance();
    //$CI->load->model('my_model');
    if ($CI->session->userdata["invoice_info"]["zone_code"] == "CO") {
        if ($invoice_id != null) {
            $insert_info = ["invoice_id" => $invoice_id];
            $last_id = str_pad(
                $CI->master_model->insertRecord(
                    "config_contact_classes_CO_invoice_jk",
                    $insert_info,
                    true
                ),
                4,
                "0",
                STR_PAD_LEFT
            );
        }
    } elseif ($CI->session->userdata["invoice_info"]["zone_code"] == "NZ") {
        if ($invoice_id != null) {
            $insert_info = ["invoice_id" => $invoice_id];
            $last_id = str_pad(
                $CI->master_model->insertRecord(
                    "config_contact_classes_NZ_invoice_jk",
                    $insert_info,
                    true
                ),
                4,
                "0",
                STR_PAD_LEFT
            );
        }
    } elseif ($CI->session->userdata["invoice_info"]["zone_code"] == "EZ") {
        if ($invoice_id != null) {
            $insert_info = ["invoice_id" => $invoice_id];
            $last_id = str_pad(
                $CI->master_model->insertRecord(
                    "config_contact_classes_EZ_invoice_jk",
                    $insert_info,
                    true
                ),
                4,
                "0",
                STR_PAD_LEFT
            );
        }
    } elseif ($CI->session->userdata["invoice_info"]["zone_code"] == "SZ") {
        if ($invoice_id != null) {
            $insert_info = ["invoice_id" => $invoice_id];
            $last_id = str_pad(
                $CI->master_model->insertRecord(
                    "config_contact_classes_SZ_invoice_jk",
                    $insert_info,
                    true
                ),
                4,
                "0",
                STR_PAD_LEFT
            );
        }
    }
    return $last_id;
}

/* Location: ./application/helpers/bankquest_invice_helper.php */
