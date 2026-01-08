<!DOCTYPE html>
<?php
// echo "<pre>";
// print_r($candidate);
// echo $data;
// exit;
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Apply For Scribe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f6f6;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 750px;
            margin: 20px auto;
            background-color: #fff;
            border: 1px solid #b3d9ff;
            box-shadow: 0 0 5px rgba(0, 102, 204, 0.2);
            padding: 20px;
        }

        h2 {
            background-color: #0073e6;
            /* blue */
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
            /* white row */
        }

        table tr:nth-child(even) {
            background-color: #e6f2ff;
            /* light blue row */
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

        .note {
            font-size: 12px;
            color: red;
        }

        /* .section-title {
            background-color: #0099ff; */
        /* light blue */
        /* color: #fff;
            padding: 8px;
            margin-top: 20px;
            font-size: 14px;
        } */

        .section-title {
            background-color: #0073e6;
            /* blue */
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
            /* blue */
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button.cancel {
            background-color: #999;
            /* gray for cancel */
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #333;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Apply For Scribe</h2>
        <form method="post">


            <table>
                <tr>
                    <td>First Name *</td>
                    <td><input type="text" value="<?php echo $candidate->first_name; ?>" readonly></td>
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
                    <td><input type="text" value="Pune" readonly></td>
                </tr>
                <tr>
                    <td>Center Code *</td>
                    <td><input type="text" value="322" readonly></td>
                </tr>
                <tr>
                    <td>Exam Name *</td>
                    <td><input type="text" value="CABR" readonly></td>
                </tr>
                <tr>
                    <td>Subject Name *</td>
                    <td><input type="text" value="Human Resources Management" readonly></td>
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
                            <input type="radio" value="No" <?php echo ($candidate->benchmark_disability == 'N') ? 'checked' : ''; ?> disabled> No
                        </label>
                    </td>
                </tr>

                <!-- <tr>
                    <td style="vertical-align: top;">Attach scan copy of PWD certificate</td>
                    <td>
                        <input type="file" id="pwdFile" accept="image/*">
                        <div id="previewWrapper" style="display:none; margin-top:10px;">
                            <img id="previewImage" src="" alt="Preview will appear here"
                                style="max-width:200px; border:1px solid #ccc; padding:5px;">
                        </div>
                    </td>
                </tr> -->

                <tr>
                    <td>Visually handicapped</td>
                    <td class="radio-group">
                        <label><input type="radio" name="vh" value="yes" onclick="toggleFile(this,'vh_file')"> Yes</label>
                        <label><input type="radio" name="vh" value="no" checked onclick="toggleFile(this,'vh_file')"> No</label>
                    </td>
                </tr>
                <tr id="vh_file" style="display:none; margin-top:5px;">
                    <td colspan="2">
                        Attach scan copy of PWD certificate <span style="color:red">*</span>
                        <input type="file" name="vh_document" accept="image/*" onchange="showPreview(this,'vh_preview')">
                        <div id="vh_preview" class="preview"></div>
                    </td>
                </tr>

                <tr>
                    <td>Orthopedically handicapped</td>
                    <td class="radio-group">
                        <label><input type="radio" name="oh" value="yes" onclick="toggleFile(this,'oh_file')"> Yes</label>
                        <label><input type="radio" name="oh" value="no" checked onclick="toggleFile(this,'oh_file')"> No</label>
                    </td>
                </tr>
                <tr id="oh_file" style="display:none; margin-top:5px;">
                    <td colspan="2">
                        Attach scan copy of PWD certificate <span style="color:red">*</span>
                        <input type="file" name="oh_document" accept="image/*" onchange="showPreview(this,'oh_preview')">
                        <div id="oh_preview" class="preview"></div>
                    </td>
                </tr>

                <tr>
                    <td>Cerebral palsy</td>
                    <td class="radio-group">
                        <label><input type="radio" name="cp" value="yes" onclick="toggleFile(this,'cp_file')"> Yes</label>
                        <label><input type="radio" name="cp" value="no" checked onclick="toggleFile(this,'cp_file')"> No</label>
                    </td>
                </tr>
                <tr id="cp_file" style="display:none; margin-top:5px;">
                    <td colspan="2">
                        Attach scan copy of PWD certificate <span style="color:red">*</span>
                        <input type="file" name="cp_document" accept="image/*" onchange="showPreview(this,'cp_preview')">
                        <div id="cp_preview" class="preview"></div>
                    </td>
                </tr>

            </table>

            <div style="background:#e5f4fb; padding:8px; border:1px solid #c9e2ef; font-size:14px; margin-bottom:10px;">
                <strong>Declaration Form</strong> : it is mandatory to upload your Declaration Form. <br>
                <a href="https://iibf.esdsconnect.com/uploads/Scribe_Guideline_2024.pdf" target="_blank" style="color:#d00; font-weight:bold; text-decoration:none;">
                    Click here to download the Scribe Guidelines and Declaration form...
                </a>
            </div>

            <label for="declarationForm">Upload your Declaration Form <span style="color:red;">**</span></label>
            <input type="file" id="declarationForm">
            <br>
            <small style="color:#d00; font-size:12px;">Please Upload only .jpg, .jpeg file from 8KB to 300KB</small>


            <div class="section-title">Scribe Details</div>
            <table>
                <tr>
                    <td>Name of Scribe *</td>
                    <td><input type="text"></td>
                </tr>
                <tr>
                    <td>Date of Birth *</td>
                    <td><input type="date"></td>
                </tr>
                <tr>
                    <td>Scribe Mobile *</td>
                    <td><input type="text"></td>
                </tr>
                <tr>
                    <td>Email of Scribe *</td>
                    <td><input type="email"></td>
                </tr>
                <tr>
                    <td>Qualification *</td>
                    <td>
                        <label><input type="radio"> SSC</label>
                        <label><input type="radio"> HSC</label>
                        <label><input type="radio"> Graduate</label>
                    </td>
                </tr>
            </table>

            <div class="section-title">ID Proof</div>
            <table>
                <tr>
                    <td>Enter Aadhaar Number *</td>
                    <td><input type="text"></td>
                </tr>
                <tr>
                    <td>Upload Aadhaar Card *</td>
                    <td><input type="file"></td>
                </tr>
                <tr>
                    <td>Security Code *</td>
                    <td class="security-code">
                        <input type="text">
                        <img src="captcha.png" alt="captcha">
                        <button type="button">Change Image</button>
                    </td>
                </tr>
            </table>

            <br>
            <button type="submit">Submit</button>
            <button type="button" class="cancel">Cancel</button>
        </form>

        <div class="footer">
            Copyright © 2025 BDS. All rights reserved. Powered by BDS
        </div>
    </div>

    <script>
        function toggleFile(radio, fileId) {
            var fileDiv = document.getElementById(fileId);
            if (radio.value === "yes") {
                fileDiv.style.display = "block";
            } else {
                fileDiv.style.display = "none";
            }
        }

        function toggleFile(radio, fileId) {
            let fileRow = document.getElementById(fileId);
            if (radio.value === "yes") {
                fileRow.style.display = "block";
            } else {
                fileRow.style.display = "none";
                fileRow.querySelector("input[type='file']").value = ""; // reset file
                let preview = fileRow.querySelector(".preview");
                if (preview) preview.innerHTML = ""; // clear preview
            }
        }

        function showPreview(input, previewId) {
            let preview = document.getElementById(previewId);
            preview.innerHTML = ""; // clear old preview
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
    </script>
</body>

</html>