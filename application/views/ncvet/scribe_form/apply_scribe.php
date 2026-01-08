<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Apply For Scribe (NCVET)</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #fff;
            height: 100vh;
        }

        .form-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding-top: 90px;
            text-align: center;
        }

        .form-header {
            background: #b3d7f5;
            padding: 15px;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        table {
            margin: 0 auto;
        }

        td {
            padding: 10px;
            text-align: left;
        }

        input[type="text"] {
            padding: 8px;
            width: 250px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .btn {
            background: #00bfff;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn:hover {
            background: #009acd;
        }

        .guidelines {
            margin-top: 20px;
        }

        .guidelines a {
            color: #00bfff;
            text-decoration: none;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <div class="form-header">Apply For Scribe (NCVET)</div>
        <?php if ($this->session->flashdata('error')): ?>
            <div style="color:red; font-weight:bold; margin:10px 0;">
                <?= $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>
        <form id="scribeForm" method="post" autocomplete="off">
            <table>
                <tr>
                    <td>Enrollment Number *</td>
                    <td><input type="text" name="regnumber" placeholder="Enter Enrollment Number"></td>
                    <td><button type="button" class="btn btn-get-otp">Get OTP</button></td>
                </tr>
                <tr>
                    <td>Enter OTP *</td>
                    <td>
                        <input type="text" name="otp" id="otp" placeholder="Enter OTP" oncopy="return false" onpaste="return false" oncut="return false" ondrag="return false" ondrop="return false"
                            autocomplete="off">
                    </td>
                    <td>
                        <button type="button" class="btn btn-verify-otp">Verify OTP</button>
                    </td>
                </tr>
                <tr>
                    <td>Exam Name *</td>
                    <td>Certificate Course on Fundamentals of Retail Banking</td>
                    <!-- <td><select class="form-control chosen-select" id="exam_code1" name="exam_code" required autofocus data-placeholder="<?php if (!empty($active_exam_data) && count($active_exam_data) > 0) {
                                                                                                                                                    echo 'Select Exam';
                                                                                                                                                } else {
                                                                                                                                                    echo 'No Exam Available';
                                                                                                                                                } ?>">
                            <?php
                            if (!empty($active_exam_data) && count($active_exam_data) > 0) {
                                foreach ($active_exam_data as $active_exam_res) { ?>
                                    <option value="<?php echo $active_exam_res['exam_code']; ?>" <?php if (set_value('exam_code') != '') {
                                                                                                        $exam_code_arr = set_value('exam_code');
                                                                                                    } else {
                                                                                                        $exam_code_arr = $exam_code;
                                                                                                    }
                                                                                                    if (is_array($exam_code_arr) && in_array($active_exam_res['exam_code'], $exam_code_arr)) {
                                                                                                        echo "selected = 'selected'";
                                                                                                    } ?>><?php if ($active_exam_res['description'] != "") {
                                                                                                                echo $active_exam_res['description'] . " - ";
                                                                                                            }
                                                                                                            if ($active_exam_res['exam_code'] == '2027') {
                                                                                                                echo '1017';
                                                                                                            } else {
                                                                                                                echo $active_exam_res['exam_code'];
                                                                                                            } ?></option>
                            <?php    }
                            } ?>
                        </select></td> -->
                </tr>
                <tr>
                    <td>Security Code *</td>
                    <td><input type="text" name="captcha_code" id="captcha_code" placeholder="Security Code" maxlength="5">
                        <br>
                        <span id="captcha_error" style="color:red; font-size:14px;"></span>

                    </td>
                    <td>
                        <div id="captcha_img" style="display:inline-block;"></div>
                        <button type="button" onclick="refresh_captcha_img();" class="btn"><i class="fa fa-refresh"></i></button>
                    </td>
                </tr>
            </table>
            <div style="margin-top:15px;">
                <button type="submit" class="btn">Get Details</button>
                <button type="reset" class="btn">Cancel</button>
            </div>
        </form>
        <br><br>
        <div class="guidelines">
            <a href="https://iibf.esdsconnect.com/uploads/Scribe_Guideline_2024.pdf" target="_blank" style="text-decoration: underline;">GUIDELINES/RULES FOR USING SCRIBE BY VISUALLY IMPAIRED & ORTHOPEDICALLY CHALLENGED CANDIDATES</a>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let otpVerified = false;

        function refresh_captcha_img() {
            $.post("<?= site_url('ncvet/Scribe_form/generate_captcha_ajax'); ?>", {
                session_name: "LOGIN_SCRIBE"
            }, function(res) {
                $('#captcha_img').html(res);
                $("#captcha_code").val("");
            });
        }

        $(document).ready(function() {
            refresh_captcha_img();

            // Get OTP functionality with button toggle
            $(".btn-get-otp").click(function() {
                let enrollNoInput = $("input[name='regnumber']");
                let enrollNo = enrollNoInput.val().trim();
                let btn = $(this);

                // If button currently says "Change Enrollment Number"
                if (btn.text() === "Change Enrollment Number") {
                    enrollNoInput.prop("readonly", false).val("");
                    otpVerified = false;
                    $("input[name='otp']").prop("readonly", false).val("");
                    $(".btn-verify-otp").show();
                    btn.text("Get OTP");
                    return;
                }

                // If button says "Get OTP" - validate enrollment number
                if (!enrollNo) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Missing Info',
                        text: 'Please enter Enrollment Number',
                    });
                    return;
                }

                // Send OTP request
                $.post("<?= site_url('ncvet/Scribe_form/send_otp'); ?>", {
                    regnumber: enrollNo
                }, function(res) {
                    if (res.status === "success") {
                        Swal.fire({
                            icon: 'success',
                            title: 'OTP Sent!',
                            html: `OTP has been sent to your registered mobile no: <b>${res.masked_mobile}</b><br>
                       and email: <b>${res.masked_email}</b>`,
                            confirmButtonText: 'OK'
                        });
                        // Make input readonly and change button text
                        enrollNoInput.prop("readonly", true);
                        btn.text("Change Enrollment Number");
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: res.message
                        });
                    }
                }, "json");
            });



            // Verify OTP
            $(".btn-verify-otp").click(function() {
                let enteredOtp = $("input[name='otp']").val().trim();
                if (!enteredOtp) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Missing Info',
                        text: 'Please enter OTP.'
                    });
                    return;
                }
                $.post("<?= site_url('ncvet/Scribe_form/verify_otp'); ?>", {
                    otp: enteredOtp
                }, function(res) {
                    if (res.status === "success") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Verified!',
                            text: 'OTP verified successfully.',
                            confirmButtonText: 'OK'
                        });
                        otpVerified = true;
                        $("input[name='otp']").prop("readonly", true);
                        $(".btn-verify-otp").hide();

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid OTP',
                            text: res.message
                        });
                    }
                }, "json");
            });


            $("#scribeForm").submit(function(e) {
                e.preventDefault();
                let enrollNo = $("input[name='regnumber']").val().trim();
                let captcha = $("#captcha_code").val().trim();

                // Clear old messages
                $("#captcha_error").text("");

                if (!otpVerified) {
                    $("#otp_error").text("Please verify OTP first.");
                    return;
                }
                if (!captcha) {
                    $("#captcha_error").text("Please enter captcha.");
                    return;
                }

                $.post("<?= site_url('ncvet/Scribe_form/check_captcha_code_ajax'); ?>", {
                    captcha_code: captcha,
                    session_name: "LOGIN_SCRIBE"
                }, function(res) {
                    if (res === "true") {
                        window.location.href = "<?= site_url('ncvet/Scribe_form/details_page'); ?>?regnumber=" + encodeURIComponent(enrollNo);
                    } else {
                        $("#captcha_error").text("Invalid Captcha.");
                        refresh_captcha_img();
                    }
                });
            });

        });

        // Extra layer of protection: disable keyboard shortcuts like Ctrl+V
        document.getElementById("otp").addEventListener("keydown", function(e) {
            if ((e.ctrlKey && (e.key === "v" || e.key === "V")) || (e.key === "Insert" && e.shiftKey)) {
                e.preventDefault();
            }
        });

        $('#exam_code1').change(function() {
            var exam_code = $('#exam_code1').val();
            //alert(exam_code);
            // AJAX request
            $.ajax({
                url: '<?= base_url() ?>Scribe_form/getSubjects',
                method: 'post',
                data: {
                    exam_code: exam_code
                },
                dataType: 'json',
                success: function(response) {
                    //alert(response);

                    // Remove options 
                    $('#sel_subject1').find('option').not(':first').remove();
                    // Add options
                    $.each(response, function(index, subjects) {
                        $('#sel_subject1').append('<option value="' + subjects['subject_code'] + '">' + subjects['subject_description'] + '</option>');
                    });
                }
            });
        });
    </script>

</body>

</html>