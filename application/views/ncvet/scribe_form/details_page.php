<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Apply For Scribe</title>
    <link rel="stylesheet" href="css/sweetalert2.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="<?php echo auto_version(base_url('assets/ncvet/css/cropper.css')); ?>" rel="stylesheet">
    <link href="<?php echo auto_version(base_url('assets/ncvet/css/cropper_style.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
    <link href="https://iibf.esdsconnect.com/staging/assets/ncvet/font-awesome/css/font-awesome.css?ver=1754918267" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f6f6;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 950px;
            margin: 20px auto;
            background-color: #fff;
            border: 1px solid #b3d9ff;
            box-shadow: 0 0 5px rgba(0, 102, 204, 0.2);
            padding: 20px;
        }

        h2 {
            background-color: #0073e6;
            color: #fff;
            padding: 10px;
            margin: -20px -20px 20px -20px;
            font-size: 18px;
        }

        table {
            width: 100%;
            border-spacing: 0;
        }

        td {
            padding: 10px;
            vertical-align: middle;
        }

        table tr:nth-child(odd) {
            background-color: #ffffff;
        }

        table tr:nth-child(even) {
            background-color: #e6f2ff;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        select {
            width: 98%;
            padding: 6px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[readonly] {
            background-color: #f3f3f3;
        }

        .radio-group {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        input[type="file"] {
            border: none;
        }

        .section-title {
            background-color: #0073e6;
            color: #fff;
            padding: 8px 10px;
            font-weight: bold;
        }

        .security-code {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        button {
            padding: 8px 15px;
            background-color: #0073e6;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button.cancel {
            background-color: #999;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #333;
            margin-top: 20px;
        }

        .error {
            color: red;
            display: block;
            margin-top: 4px;
            font-size: 13px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Apply For Scribe</h2>
        <?php echo validation_errors(); ?>
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?= $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?= $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <form id="myForm" method="post" enctype="multipart/form-data" onsubmit="return validateForm();" action="/staging/ncvet/Scribe_form/details_page">

            <table>
                <tr>
                    <td>First Name *</td>
                    <td><input type="text" value="<?php echo $candidate->salutation . ' ' . $candidate->first_name; ?>" readonly></td>
                </tr>
                <tr>
                    <td>Middle Name</td>
                    <td><input type="text" value="<?php echo $candidate->middle_name; ?>" readonly></td>
                </tr>
                <tr>
                    <td>Last Name *</td>
                    <td><input type="text" value="<?php echo $candidate->last_name; ?>" readonly></td>
                </tr>
                <tr>
                    <td>Email *</td>
                    <td><input type="email" value="<?php echo $candidate->email_id; ?>" readonly></td>
                </tr>
                <tr>
                    <td>Mobile *</td>
                    <td><input type="text" value="<?php echo $candidate->mobile_no; ?>" readonly></td>
                </tr>
                <tr>
                    <td>Center Name *</td>
                    <td><input type="text" value="<?php echo $admit_details->center_name; ?>" readonly></td>
                </tr>
                <tr>
                    <td>Center Code *</td>
                    <td><input type="text" value="<?php echo $admit_details->center_code; ?>" readonly></td>
                </tr>
                <tr>
                    <td>Exam Name *</td>
                    <td><input type="text" value="Certificate Course on Fundamentals of Retail Banking" readonly></td>
                </tr>
                <tr>
                    <td>Subject Name *</td>
                    <td><input type="text" value="<?php echo $admit_details->sub_dsc; ?>" readonly></td>
                </tr>
                <tr>
                    <td>Exam Date *</td>
                    <td><input type="text" value="2025-08-25" readonly></td>
                </tr>
                <tr>
                    <td>Person with Benchmark Disability *</td>
                    <td class="radio-group">
                        <label>
                            <input type="radio" value="Yes" <?php echo ($candidate->benchmark_disability == 'Y') ? 'checked' : ''; ?> disabled> Yes
                        </label>
                        <label>
                            <input type="radio" value="No" <?php echo ($candidate->benchmark_disability == 'N' || empty($candidate->benchmark_disability)) ? 'checked' : ''; ?> disabled> No
                        </label>
                    </td>
                </tr>

                <tr>
                    <td>Visually handicapped</td>
                    <td class="radio-group">
                        <label>
                            <input type="radio" value="Yes" <?php echo ($candidate->visually_impaired == 'Y') ? 'checked' : ''; ?> disabled> Yes
                        </label>
                        <label>
                            <input type="radio" value="No" <?php echo ($candidate->visually_impaired == 'N' || empty($candidate->visually_impaired)) ? 'checked' : ''; ?> disabled> No
                        </label>
                    </td>
                </tr>

                <tr>
                    <td>Orthopedically handicapped</td>
                    <td class="radio-group">
                        <label>
                            <input type="radio" value="Yes" <?php echo ($candidate->orthopedically_handicapped == 'Y') ? 'checked' : ''; ?> disabled> Yes
                        </label>
                        <label>
                            <input type="radio" value="No" <?php echo ($candidate->orthopedically_handicapped == 'N' || empty($candidate->orthopedically_handicapped)) ? 'checked' : ''; ?> disabled> No
                        </label>
                    </td>
                </tr>

                <tr>
                    <td>Cerebral palsy</td>
                    <td class="radio-group">
                        <label>
                            <input type="radio" value="Yes" <?php echo ($candidate->cerebral_palsy == 'Y') ? 'checked' : ''; ?> disabled> Yes
                        </label>
                        <label>
                            <input type="radio" value="No" <?php echo ($candidate->cerebral_palsy == 'N' || empty($candidate->cerebral_palsy)) ? 'checked' : ''; ?> disabled> No
                        </label>
                    </td>
                </tr>

            </table>

            <div style="background:#e5f4fb; padding:8px; border:1px solid #c9e2ef; font-size:14px; margin-bottom:10px;">
                <strong>Declaration Form</strong> : it is mandatory to upload your Declaration Form. <br>
                <a href="https://iibf.esdsconnect.com/uploads/Scribe_Guideline_2024.pdf" target="_blank" style="color:#d00; font-weight:bold; text-decoration:underline;">
                    Click here to download the Scribe Guidelines and Declaration form...
                </a>
            </div>

            <tr>
                <td>Upload Declaration Form *</td>
                <td>
                    <label id="declaration_trigger"
                        style="display:inline-block; background:#007bce; color:#fff; padding:5px 10px; border-radius:5px; cursor:pointer; font-weight:bold;">
                        Upload your Declaration Form
                    </label>

                    <input type="file" id="declaration_img" name="declaration_img"
                        accept=".pdf" style="display:none;">
                    <span class="error" id="declaration_error"></span>

                    <small id="declarationNote" style="color:#d00#555;">
                        Note: Please upload only .pdf file (50KB to 2MB).
                    </small>

                    <div id="declaration_preview" style="margin-top:10px; display:none;">
                        <span id="declaration_file_name" style="font-size:13px; color:#333; font-weight:bold;"></span>
                    </div>

                </td>
            </tr>

            <div class="section-title">Scribe Details</div>
            <table>
                <tr>
                    <td>Name of Scribe *</td>
                    <td>
                        <input type="text" name="scribe_name" id="scribe_name" pattern="[A-Za-z\s]+" maxlength="100"
                            value="<?php echo isset($post_data['scribe_name']) ? htmlspecialchars($post_data['scribe_name']) : set_value('scribe_name'); ?>">

                        <span class="error" id="err_scribe_name"></span>
                    </td>
                </tr>

                <tr>
                    <td>Date of Birth *</td>

                    <?php
                    // Determine value for scribe_dob
                    $dob_value = '';
                    if (isset($post_data['scribe_dob'])) {
                        $dob_value = htmlspecialchars($post_data['scribe_dob']);
                    } elseif (!empty($form_data[0]['scribe_dob']) && $form_data[0]['scribe_dob'] != '0000-00-00') {
                        $dob_value = $form_data[0]['scribe_dob'];
                    }
                    ?>

                    <td>
                        <input type="text" name="scribe_dob" id="scribe_dob" placeholder="YYYY-MM-DD" class="form-control datepicker"
                            value="<?php echo $dob_value; ?>">
                        <span class="error" id="err_scribe_dob"></span>
                    </td>

                </tr>
                <tr>
                    <td>Scribe Mobile *</td>
                    <td><input type="text" id="mobile_scribe" name="mobile_scribe" value="<?php echo isset($post_data['mobile_scribe']) ? htmlspecialchars($post_data['mobile_scribe']) : set_value('mobile_scribe'); ?>" pattern="\d{10}" maxlength="10" oninput="this.value=this.value.replace(/[^0-9]/g,'');"
                            title="Mobile number must be exactly 10 digits">
                        <span class="error" id="err_mobile_scribe"></span>

                    </td>
                </tr>
                <tr>
                    <td>Email of Scribe *</td>
                    <td><input type="email" name="scribe_email" value="<?php echo isset($post_data['scribe_email']) ? htmlspecialchars($post_data['scribe_email']) : set_value('scribe_email'); ?>" id="scribe_email">
                        <span class="error" id="err_scribe_email"></span>
                    </td>
                </tr>
                <tr>
                    <td>Qualification *</td>
                    <td>
                        <?php
                        $qualification = isset($post_data['qualification']) ? $post_data['qualification'] : set_value('qualification');
                        ?>
                        <label><input type="radio" name="qualification" value="SSC" <?php echo ($qualification == 'SSC') ? 'checked' : ''; ?>> SSC</label>
                        <label><input type="radio" name="qualification" value="HSC" <?php echo ($qualification == 'HSC') ? 'checked' : ''; ?>> HSC</label>
                        <label><input type="radio" name="qualification" value="Graduate" <?php echo ($qualification == 'Graduate') ? 'checked' : ''; ?>> Graduate</label>
                        <span class="error" id="err_qualification"></span>
                    </td>

                </tr>
            </table>

            <div class="section-title">ID Proof</div>
            <table>
                <tr>
                    <td>Enter Aadhaar Number *</td>
                    <td>
                        <input type="text" name="aadhar_no" id="aadhar_no" pattern="\d{12}" maxlength="12" oninput="this.value=this.value.replace(/[^0-9]/g,'');"
                            title="Aadhaar number must be exactly 12 digits"
                            value="<?php echo isset($post_data['aadhar_no']) ? htmlspecialchars($post_data['aadhar_no']) : set_value('aadhar_no'); ?>">
                        <span class="error" id="err_aadhar_no"></span>
                    </td>
                </tr>

                <tr>
                    <td>Upload Aadhaar Card *</td>
                    <td>
                        <div class="img_preview_input_outer pull-left">
                            <input type="file" id="aadhar_file" name="aadhar_file" class="form-control hide_input_file_cropper" />
                            <span class="error" id="err_aadhar_file"></span>

                            <div class="image-input image-input-outline image-input-circle image-input-empty">
                                <div class="profile-progress"></div>
                                <button type="button" class="btn btn-sm btn-primary w-100 mb-1 aadhar_file_btn" onclick="open_img_upload_modal('aadhar_file', 'ncvet_candidates', 'Aadhar Card')">Upload Aadhar Card</button>
                            </div>

                            <small id="aadhaarNote" style="color:#555;">Note: Please select only .jpg, .jpeg, .png file upto 5MB.</small>
                            <br>

                            <input type="hidden" class="uploaded_hidden_file" datafile="aadhar_file" name="aadhar_file_cropper" id="aadhar_file_cropper"
                                value="<?php echo isset($post_data['aadhar_file_cropper']) ? htmlspecialchars($post_data['aadhar_file_cropper']) : set_value('aadhar_file_cropper'); ?>" />

                            <input type="hidden" name="aadhar_file_old" id="aadhar_file_old"
                                value="<?php echo isset($post_data['aadhar_file_old']) ? htmlspecialchars($post_data['aadhar_file_old']) : set_value('aadhar_file_old'); ?>" />

                            <!-- Preview Box -->
                            <div class="upload_img_preview pull-left" id="aadhar_file_preview" style="margin-top:10px;">
                                <?php
                                // Show Aadhaar preview if file exists (old/cropped)
                                $aadhaar_preview_url = '';
                                if (!empty($post_data['aadhar_file_cropper'])) {
                                    $aadhaar_preview_url = htmlspecialchars($post_data['aadhar_file_cropper']);
                                } elseif (!empty($post_data['aadhar_file_old'])) {
                                    $aadhaar_preview_url = htmlspecialchars($post_data['aadhar_file_old']);
                                }
                                if ($aadhaar_preview_url) {
                                    echo '<img src="' . $aadhaar_preview_url . '" alt="Aadhaar Preview" style="max-width:120px;max-height:120px;border:1px solid #ccc;" />';
                                } else {
                                    echo '<i class="fa fa-picture-o"></i>';
                                }
                                ?>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>Security Code *</td>
                    <td class="security-code">
                        <div style="display:inline-block; vertical-align:top;">
                            <input type="text" name="captcha" id="captcha_code">
                            <span class="error" id="err_captcha_code"></span>
                        </div>

                        <div id="captcha_img" style="display:inline-block; margin-left:10px;"></div>
                        <button type="button" onclick="refresh_captcha_img()">Change Image</button>
                    </td>
                </tr>
            </table>

            <br>
            <button type="submit" name="submit" value="1">Submit</button>
            <button type="button" class="cancel">Cancel</button>
        </form>

        <div class="footer">
            Copyright © 2025 ESDS. All rights reserved. Powered by ESDS
        </div>
    </div>


    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://iibf.esdsconnect.com/staging/assets/ncvet/jquery_validation/jquery.validate.js?ver=1754918266"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function sweet_alert_error(message) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: message
            });
        }

        function sweet_alert_success(message) {
            Swal.fire({
                icon: "success",
                title: "Success",
                text: message
            });
        }
    </script>

    <?php $this->load->view('ncvet/common/inc_cropper_script', array('page_name' => 'candidate_enrollment')); ?>

    <script>
        function handleSubmit(e) {
            e.preventDefault();
            if (validateForm()) {
                document.getElementById("myForm").submit();
            } else return false;
        }

        function validateForm() {
            let isValid = true;

            document.querySelectorAll(".error").forEach(el => el.innerText = "");

            let scribeName = document.getElementById("scribe_name").value.trim();
            let dob = document.getElementById("scribe_dob").value;
            let mobile = document.getElementById("mobile_scribe").value.trim();
            let email = document.getElementById("scribe_email").value.trim();
            let aadhaar = document.getElementById("aadhar_no").value.trim();
            let qualification = document.querySelector('input[name="qualification"]:checked');
            let declarationFile = document.getElementById("declaration_img").files.length;
            let aadhaarFile = document.getElementById("aadhar_file").files.length;
            let captcha = document.getElementById("captcha_code").value.trim();

            if (scribeName === "") {
                document.getElementById("err_scribe_name").innerText = "Scribe Name is required.";
                isValid = false;
            }



            if (dob === "") {
                document.getElementById("err_scribe_dob").innerText = "Scribe Date of birth is required.";
                isValid = false;
            }

            // Aadhaar file validation 
            let aadhaarInput = document.getElementById("aadhar_file");
            let aadhaarCropper = document.getElementById("aadhar_file_cropper");
            let fileError = document.getElementById("err_aadhar_file");

            let aadhaarFilePresent = aadhaarInput && aadhaarInput.files && aadhaarInput.files.length > 0;
            let aadhaarCropperPresent = aadhaarCropper && aadhaarCropper.value && aadhaarCropper.value.trim() !== "";

            if (!aadhaarFilePresent && !aadhaarCropperPresent) {
                fileError.innerText = "Aadhaar Card upload is required.";
                isValid = false;
            } else {
                fileError.innerText = "";

                if (aadhaarFilePresent) {
                    const file = aadhaarInput.files[0];
                    const fileName = file.name.toLowerCase();
                    const fileSize = file.size;

                    if (!(/\.(jpg|jpeg|png)$/i).test(fileName)) {
                        fileError.innerText = "Only JPG, JPEG, or PNG files are allowed.";
                        isValid = false;
                    } else if (fileSize < 40 * 1024 || fileSize > 5 * 1024 * 1024) {
                        fileError.innerText = "File size must be between 40KB and 5MB.";
                        isValid = false;
                    }
                }
            }


            let mobilePattern = /^[6-9]\d{9}$/;
            if (!mobilePattern.test(mobile)) {
                document.getElementById("err_mobile_scribe").innerText = "Enter valid 10-digit mobile number.";
                isValid = false;
            }

            let emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,}$/;
            if (!emailPattern.test(email)) {
                document.getElementById("err_scribe_email").innerText = "Enter valid email address.";
                isValid = false;
            }

            let aadhaarPattern = /^\d{12}$/;
            if (!aadhaarPattern.test(aadhaar)) {
                document.getElementById("err_aadhar_no").innerText = "Enter valid 12-digit Aadhaar number.";
                isValid = false;
            }

            if (!qualification) {
                document.getElementById("err_qualification").innerText = "Please select Scribe Qualification.";
                isValid = false;
            }

            if (declarationFile === 0) {
                document.getElementById("declaration_error").innerText = "Declaration Form upload is required.";
                isValid = false;
            }

            if (captcha === "") {
                document.getElementById("err_captcha_code").innerText = "Enter valid Captcha.";
                isValid = false;
            }


            return isValid;
        }

        document.getElementById("declaration_trigger").addEventListener("click", function() {
            document.getElementById("declaration_img").click();
        });

        document.getElementById("declaration_img").addEventListener("change", function() {
            const file = this.files[0];
            const errorMsg = document.getElementById("declaration_error");
            const previewBox = document.getElementById("declaration_preview");
            const fileNameSpan = document.getElementById("declaration_file_name");

            errorMsg.style.display = "none";
            previewBox.style.display = "none";
            fileNameSpan.textContent = "";

            if (file) {
                const fileName = file.name.toLowerCase();
                const fileSize = file.size;

                if (!fileName.endsWith(".pdf")) {
                    errorMsg.textContent = "Only PDF files are allowed.";
                    errorMsg.style.display = "block";
                    this.value = "";
                    return;
                }

                if (fileSize < 50 * 1024 || fileSize > 2 * 1024 * 1024) {
                    errorMsg.textContent = "File size must be between 50KB and 2MB.";
                    errorMsg.style.display = "block";
                    this.value = "";
                    return;
                }

                fileNameSpan.textContent = "Selected File: " + file.name;
                previewBox.style.display = "block";
            }
        });

        function refresh_captcha_img() {
            $.post("<?= site_url('ncvet/Scribe_form/generate_captcha_ajax'); ?>", {
                session_name: "LOGIN_SCRIBE"
            }, function(res) {
                $('#captcha_img').html(res);
                $("#captcha_code").val("");
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            refresh_captcha_img();
        });

        function toggleFile(radio, fileId) {
            let fileRow = document.getElementById(fileId);
            if (radio.value === "yes") {
                fileRow.style.display = "block";
            } else {
                fileRow.style.display = "none";
                let input = fileRow.querySelector("input[type='file']");
                if (input) input.value = "";
                let preview = fileRow.querySelector(".preview");
                if (preview) preview.innerHTML = "";
            }
        }

        function showPreview(input, previewId) {
            let preview = document.getElementById(previewId);
            preview.innerHTML = "";
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    let img = document.createElement("img");
                    img.src = e.target.result;
                    img.style.maxWidth = "200px";
                    img.style.marginTop = "10px";
                    preview.appendChild(img);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function getCompressedBlobFromCanvas(canvas, mime = "image/jpeg", maxSize = 300 * 1024) {
            return new Promise((resolve, reject) => {
                let quality = 0.92;

                function tryQuality(q) {
                    canvas.toBlob(function(blob) {
                        if (!blob) return reject("Unable to create image blob.");
                        if (blob.size <= maxSize || q <= 0.5) {
                            resolve(blob);
                        } else {

                            tryQuality(q - 0.12);
                        }
                    }, mime, q);
                }
                tryQuality(quality);
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            const fields = [{
                    id: "scribe_name",
                    errorId: "err_scribe_name"
                },
                {
                    id: "scribe_dob",
                    errorId: "err_scribe_dob"
                },
                {
                    id: "mobile_scribe",
                    errorId: "err_mobile_scribe"
                },
                {
                    id: "scribe_email",
                    errorId: "err_scribe_email"
                },
                {
                    id: "aadhar_no",
                    errorId: "err_aadhar_no"
                }
            ];

            fields.forEach(f => {
                const input = document.getElementById(f.id);
                const errorSpan = document.getElementById(f.errorId);

                if (input && errorSpan) {
                    input.addEventListener("input", function() {
                        if (errorSpan.innerText !== "") {
                            errorSpan.innerText = "";
                        }
                    });

                    input.addEventListener("blur", function() {
                        if (this.value.trim() !== "") {
                            errorSpan.innerText = "";
                        }
                    });
                }
            });

            const qualificationRadios = document.querySelectorAll("input[name='qualification']");
            qualificationRadios.forEach(radio => {
                radio.addEventListener("change", function() {
                    const err = document.getElementById("err_qualification");
                    if (err) err.innerText = "";
                });
            });

            const declarationInput = document.getElementById("declaration_img");
            if (declarationInput) {
                declarationInput.addEventListener("change", function() {
                    const err = document.getElementById("err_declaration_img");
                    if (err) err.innerText = "";
                });
            }


        });

        function parseYMD(dateStr) {
            var parts = String(dateStr).split('-');
            if (parts.length !== 3) return null;
            var y = parseInt(parts[0], 10),
                m = parseInt(parts[1], 10),
                d = parseInt(parts[2], 10);
            if (isNaN(y) || isNaN(m) || isNaN(d)) return null;
            return new Date(y, m - 1, d);
        }

        function calculate_age(elem) {
            var val = $(elem).val();
            if (!val) {
                var ai = $('input#age');
                if (ai.length) ai.val('');
                return;
            }
            var scribe_dob = parseYMD(val);
            if (!scribe_dob) return;

            var today = new Date();
            var age = today.getFullYear() - scribe_dob.getFullYear();
            var m = today.getMonth() - scribe_dob.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < scribe_dob.getDate())) {
                age--;
            }

            var ageInput = $('input#age');
            if (ageInput.length) ageInput.val(age);
        }

        // initialize datepicker after libs are loaded and DOM ready
        $(function() {
            let today = new Date();
            let maxDob = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate()); // must be 18+
            let minDob = new Date(today.getFullYear() - 80, today.getMonth(), today.getDate()); // not older than 80

            $('#scribe_dob').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayHighlight: true,
                startDate: minDob,
                endDate: maxDob,
                clearBtn: true
            }).on('changeDate', function() {
                $('#err_scribe_dob').text('');
                calculate_age(this);
            });
        });


        // DOB validation
        $.validator.addMethod("validate_scribe_dob", function(value, element) {
            if ($.trim(value).length === 0) return true;

            let dob = new Date(value);
            let today = new Date();
            let maxDob = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
            let minDob = new Date(today.getFullYear() - 80, today.getMonth(), today.getDate());

            if (dob >= minDob && dob <= maxDob) {
                return true;
            } else {
                $.validator.messages.validate_scribe_dob =
                    "Select DOB between " + minDob.toISOString().split('T')[0] +
                    " and " + maxDob.toISOString().split('T')[0];
                return false;
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const aadhaarFile = document.getElementById('aadhar_file');
            const aadhaarCropper = document.getElementById('aadhar_file_cropper'); // hidden field set by cropper
            const fileError = document.getElementById('err_aadhar_file');
            const preview = document.getElementById('aadhar_file_preview');
            const oldFile = document.getElementById("aadhar_file_old");

            function clearIfValid() {
                const file = (aadhaarFile && aadhaarFile.files && aadhaarFile.files[0]) ? aadhaarFile.files[0] : null;
                const cropVal = aadhaarCropper ? aadhaarCropper.value.trim() : '';

                if (file) {
                    const name = file.name.toLowerCase();
                    const size = file.size;
                    if (/\.(jpg|jpeg|png)$/i.test(name) && size >= 40 * 1024 && size <= 5 * 1024 * 1024) {
                        fileError.innerText = '';
                        return;
                    }
                }
                if (cropVal) {
                    fileError.innerText = '';
                    return;
                }

            }

            if (aadhaarFile) {
                aadhaarFile.addEventListener('change', function() {
                    if (this.files && this.files[0] && aadhaarCropper) aadhaarCropper.value = this.files[0].name;
                    clearIfValid();
                });
            }

            if (aadhaarCropper) {
                aadhaarCropper.addEventListener('input', clearIfValid);
                aadhaarCropper.addEventListener('change', clearIfValid);
            }

            if (preview) {
                const mo = new MutationObserver(function() {
                    clearIfValid();
                });
                mo.observe(preview, {
                    childList: true,
                    subtree: true
                });
            }

            preview.addEventListener("click", function(e) {
                e.preventDefault(); // prevent default behavior like form submit or link
                e.stopPropagation(); // stop event from bubbling up

                let imageUrl = "";

                // Priority: new uploaded file
                if (aadhaarFile.files && aadhaarFile.files[0]) {
                    imageUrl = URL.createObjectURL(aadhaarFile.files[0]);
                }
                // Then check cropped version
                else if (aadhaarCropper && aadhaarCropper.value) {
                    imageUrl = aadhaarCropper.value;
                }
                // Then check old file
                else if (oldFile && oldFile.value) {
                    imageUrl = oldFile.value;
                }

                if (imageUrl) {
                    window.open(imageUrl, "_blank");
                } else {
                    alert("No Aadhaar image uploaded yet.");
                }
            });

        });
    </script>

    <script>
        window.addEventListener("pageshow", function(event) {

            // Clear Declaration Form file input
            var dec = document.getElementById("declaration_img");
            if (dec) dec.value = "";

            // Clear displayed file name
            var nameBox = document.getElementById("declaration_file_name");
            if (nameBox) nameBox.innerHTML = "";

            // Hide preview box
            var prev = document.getElementById("declaration_preview");
            if (prev) prev.style.display = "none";

        }, false);
    </script>

</body>

</html>