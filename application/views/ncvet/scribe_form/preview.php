<!DOCTYPE html>
<?php
// echo "Hiii";
// exit;
// echo "<pre>";
// print_r($candidate_details);
// exit;
// function scribe_file_url_and_path($filename_or_path, $type = 'declaration')
// {
//     if (strpos($filename_or_path, '/') === false) {
//         // only filename
//         $folder = ($type === 'aadhaar') ? 'uploads/ncvet/scribe/aadhar_file/' : 'uploads/ncvet/scribe/declaration/';
//         $url = base_url($folder . $filename_or_path);
//         $path = FCPATH . $folder . $filename_or_path;
//     } else {
//         $url = base_url($filename_or_path);
//         $path = FCPATH . ltrim($filename_or_path, '/');
//     }
//     return ['url' => $url, 'path' => $path];
// }

?>
<html lang="en">

<head>
    <link href="https://iibf.esdsconnect.com/staging/assets/ncvet/font-awesome/css/font-awesome.css?ver=1754918267" rel="stylesheet">
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

        .section-title {
            background-color: #0073e6;
            color: #fff;
            padding: 8px 10px;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #333;
            margin-top: 20px;
        }

        .action-buttons {
            text-align: center;
            margin-top: 20px;
        }

        .btn-back,
        .btn-submit {
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-back {
            background: #ccc;
            color: #000;
            margin-right: 10px;
        }

        .btn-submit {
            background: #0073e6;
            color: #fff;
        }
    </style>
</head>

<body>

    <div class="container">
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

        <h2>Please go through the given details..</h2>

        <form method="post">
            <!-- Basic Details -->
            <div class="section-title">Basic Details</div>
            <table>
                <tr>
                    <td>Membership No</td>
                    <td><?php echo $scribe['regnumber']; ?></td>
                </tr>
                <tr>
                    <td>First Name</td>
                    <td><?php echo $scribe['namesub'] . ' ' . $scribe['firstname']; ?></td>
                </tr>
                <tr>
                    <td>Middle Name</td>
                    <td><?php echo $scribe['middlename']; ?></td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><?php echo $scribe['lastname']; ?></td>
                </tr>
                <tr>
                    <td>Date of Birth</td>
                    <td><?php echo $candidate_details->dob; ?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><?php echo $scribe['email']; ?></td>
                </tr>
                <tr>
                    <td>Mobile</td>
                    <td><?php echo $scribe['mobile']; ?></td>
                </tr>
                <tr>
                    <td>Center Name</td>
                    <td><?php echo $candidate->center_name; ?></td>
                </tr>
                <tr>
                    <td>Center Code</td>
                    <td><?php echo $candidate->center_code; ?></td>
                </tr>
                <tr>
                    <td>Exam Name</td>
                    <td><?php echo $scribe['exam_name']; ?></td>
                </tr>
                <tr>
                    <td>Subject Name</td>
                    <td><?php echo $scribe['subject_name']; ?></td>
                </tr>
                <tr>
                    <td>Exam Date</td>
                    <td><?php echo $candidate->exam_date; ?></td>
                </tr>
                <tr>
                    <td>Person with Benchmark Disability *</td>
                    <td><?php echo ($scribe['benchmark_disability'] == 'Y') ? 'Yes' : 'No'; ?></td>
                </tr>
                <tr>
                    <td>Visually handicapped</td>
                    <td><?php echo ($candidate_details->visually_impaired == 'Y') ? 'Yes' : 'No'; ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <?php
                        $visImpFile = !empty($candidate_details->vis_imp_cert_img) ? $candidate_details->vis_imp_cert_img : '';

                        if ($visImpFile !== '') {
                            if (strpos($visImpFile, '/') === false) {
                                $fileUrl = base_url('uploads/ncvet/disability/' . $visImpFile);
                            } else {
                                $fileUrl = base_url($visImpFile);
                            }

                            $ext = strtolower(pathinfo($visImpFile, PATHINFO_EXTENSION));

                            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                                echo '<img src="' . $fileUrl . '" style="max-width:200px; height:auto;">';
                            } elseif ($ext === 'pdf') {
                                $pdfThumb = base_url('uploads/ncvet/pdf_image.png'); // <-- PDF thumbnail path
                                echo '<a href="' . $fileUrl . '" target="_blank" title="View / Download PDF">';
                                echo '<img src="' . $pdfThumb . '" alt="PDF File" height="80" width="80">';
                                echo '</a>';
                            } else {
                                echo '<span>Unsupported file type</span>';
                            }
                        } else {
                            echo '<span>Not Uploaded</span>';
                        }
                        ?>
                    </td>
                </tr>


                <tr>
                    <td>Orthopedically handicapped</td>
                    <td><?php echo ($candidate_details->orthopedically_handicapped == 'Y') ? 'Yes' : 'No'; ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <?php
                        $orthFile = !empty($candidate_details->orth_han_cert_img) ? $candidate_details->orth_han_cert_img : '';

                        if ($orthFile !== '') {
                            if (strpos($orthFile, '/') === false) {
                                $fileUrl = base_url('uploads/ncvet/disability/' . $orthFile);
                            } else {
                                $fileUrl = base_url($orthFile);
                            }

                            $ext = strtolower(pathinfo($orthFile, PATHINFO_EXTENSION));

                            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                                echo '<img src="' . $fileUrl . '" style="max-width:200px; height:auto;">';
                            } elseif ($ext === 'pdf') {
                                $pdfThumb = base_url('uploads/ncvet/pdf_image.png'); // <-- PDF thumbnail path
                                echo '<a href="' . $fileUrl . '" target="_blank" title="View / Download PDF">';
                                echo '<img src="' . $pdfThumb . '" alt="PDF File" height="80" width="80">';
                                echo '</a>';
                            } else {
                                echo '<span>Unsupported file type</span>';
                            }
                        } else {
                            echo '<span>Not Uploaded</span>';
                        }
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>Cerebral palsy</td>
                    <td><?php echo ($candidate_details->cerebral_palsy == 'Y') ? 'Yes' : 'No'; ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <?php
                        $cpFile = !empty($candidate_details->cer_palsy_cert_img) ? $candidate_details->cer_palsy_cert_img : '';

                        if ($cpFile !== '') {
                            if (strpos($cpFile, '/') === false) {
                                $fileUrl = base_url('uploads/ncvet/disability/' . $cpFile);
                            } else {
                                $fileUrl = base_url($cpFile);
                            }

                            $ext = strtolower(pathinfo($cpFile, PATHINFO_EXTENSION));

                            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                                echo '<img src="' . $fileUrl . '" style="max-width:200px; height:auto;">';
                            } elseif ($ext === 'pdf') {
                                $pdfThumb = base_url('uploads/ncvet/pdf_image.png'); // <-- PDF thumbnail path
                                echo '<a href="' . $fileUrl . '" target="_blank" title="View / Download PDF">';
                                echo '<img src="' . $pdfThumb . '" alt="PDF File" height="80" width="80">';
                                echo '</a>';
                            } else {
                                echo '<span>Unsupported file type</span>';
                            }
                        } else {
                            echo '<span>Not Uploaded</span>';
                        }
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>Declaration Form</td>
                    <td>
                        <?php
                        $previewData = $this->session->userdata('scribe_preview');
                        $declaration = is_array($previewData) && isset($previewData['declaration_img'])
                            ? $previewData['declaration_img']
                            : '';

                        if ($declaration !== '') {
                            // If only filename stored → prepend path
                            if (strpos($declaration, '/') === false) {
                                $fileUrl = base_url('uploads/ncvet/scribe/declaration/' . $declaration);
                            } else {
                                $fileUrl = base_url($declaration);
                            }

                            $ext = strtolower(pathinfo($declaration, PATHINFO_EXTENSION));

                            if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                                echo '<img src="' . $fileUrl . '" height="100" width="100" alt="Declaration Image">';
                            } elseif ($ext === 'pdf') {
                                // ✅ Show a PDF thumbnail image instead of icon
                                $pdfThumb = base_url('uploads/ncvet/pdf_image.png'); // <-- change path as per your project
                                echo '<a href="' . $fileUrl . '" target="_blank" title="View / Download PDF">';
                                echo '<img src="' . $pdfThumb . '" alt="PDF File" height="80" width="80">';
                                echo '</a>';
                            } else {
                                echo '<span>Unsupported file type</span>';
                            }
                        } else {
                            echo '<span>Not Uploaded</span>';
                        }
                        ?>
                    </td>
                </tr>



            </table>

            <!-- Scribe Details -->
            <div class="section-title">Scribe Details</div>
            <table>
                <tr>
                    <td>Name of Scribe</td>
                    <td><?php echo $scribe['name_of_scribe']; ?></td>
                </tr>
                <tr>
                    <td>Date of Birth</td>
                    <td><?php echo $scribe['scribe_dob']; ?></td>
                </tr>
                <tr>
                    <td>Scribe Mobile</td>
                    <td><?php echo $scribe['mobile_scribe']; ?></td>
                </tr>
                <tr>
                    <td>Email of Scribe</td>
                    <td><?php echo $scribe['scribe_email']; ?></td>
                </tr>
                <tr>
                    <td>Qualification</td>
                    <td><?php echo $scribe['qualification']; ?></td>
                </tr>
            </table>

            <!-- ID Proof -->
            <div class="section-title">ID Proof</div>
            <table>
                <tr>
                    <td>Aadhaar Number *</td>
                    <td><?php echo $scribe['aadhar_no']; ?></td>
                </tr>
                <tr>
                    <td>Aadhaar Card *</td>
                    <td>
                        <?php
                        $previewData = $this->session->userdata('scribe_preview');
                        $aadhaar = is_array($previewData) && isset($previewData['aadhar_file'])
                            ? $previewData['aadhar_file']
                            : '';

                        if ($aadhaar !== '') {
                            // If only filename stored → prepend upload folder
                            if (strpos($aadhaar, '/') === false) {
                                $fileUrl = base_url('uploads/ncvet/scribe/aadhar_file/' . $aadhaar);
                            } else {
                                $fileUrl = base_url($aadhaar);
                            }

                            $ext = strtolower(pathinfo($aadhaar, PATHINFO_EXTENSION));

                            if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                                echo '<img src="' . $fileUrl . '" height="100" width="100">';
                            } elseif ($ext === 'pdf') {
                                echo '<a href="' . $fileUrl . '" target="_blank">View / Download PDF</a>';
                            } else {
                                echo '<span>Unsupported file type</span>';
                            }
                        } else {
                            echo '<span>Not Uploaded</span>';
                        }
                        ?>
                    </td>
                </tr>

            </table>

            <!-- Buttons -->
            <div class="action-buttons">
                <!-- <a href="<?php echo site_url('ncvet/scribe_form/details_page'); ?>" class="btn-back">Back</a> -->
                <a href="javascript:history.back()" class="btn-back">Back</a>
                <button type="submit" name="confirm" value="1" class="btn-submit">Submit</button>
            </div>
        </form>

        <div class="footer">
            Copyright © 2025 ESDS. All rights reserved. Powered by ESDS
        </div>
    </div>

</body>

</html>