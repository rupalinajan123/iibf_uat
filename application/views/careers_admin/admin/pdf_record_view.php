<?php
// Extract common data
$position_id = $records[0]['position_id'] ?? 0;
?>

<style>
    .wikitable tbody tr th,
    table.jquery-tablesorter thead tr th.headerSort,
    .header-cell {
        background: #009999;
        color: white;
        font-family: "Courier New", Courier, monospace;
        font-weight: bold;
        font-size: 100pt;
    }

    .wikitable,
    table.jquery-tablesorter {
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
    }

    .tabela,
    .wikitable {
        border: 1px solid #A2A9B1;
        border-collapse: collapse;
    }

    .tabela tbody tr td,
    .wikitable tbody tr td {
        padding: 5px 10px;
        border: 1px solid #A2A9B1;
        border-collapse: collapse;
    }

    .config-value {
        font-family: "Courier New", Courier, monospace;
        font-size: 13pt;
        background: white;
        font-weight: bold;
    }

    .column {
        float: right;
    }

    img {
        text-align: right;
    }
</style>

<?php foreach ($records as $rec): ?>
    <?php
    $r = $rec['rst'];
    $q = $rec['qualification_arr'];
    $e = $rec['emp_hist_arr'];
    $s = $rec['stateDetails'][0] ?? [];
    $p = $rec['payment_transaction'][0] ?? [];
    $o = $rec['other'];
    $d = $rec['desirable'];
    $pos = $rec['position_id'];
    ?>

    <h1 style="text-align:center"><?= $rec['head_title'] ?></h1>

    <div class="table-responsive">
        <table class="table table-bordered wikitable tabela" style="overflow:wrap">
            <tbody>
                <tr>
                    <td colspan="2" style="color:#66d9ff">
                        <h4><strong>BASIC DETAILS</strong></h4>
                    </td>
                </tr>
                <tr>
                    <td width="50%"><strong>Application for the post of:</strong></td>
                    <td width="50%"><?= $rec['application_title'] ?></td>
                </tr>
                <tr>
                    <td><strong>PHOTO:</strong></td>
                    <td><img width="70" height="70" align="right"
                            src="<?= base_url('uploads/photograph/' . $r['scannedphoto']) ?>"></td>
                </tr>

                <?php if ($pos == 1): ?>
                    <tr>
                        <td><strong>ID:</strong></td>
                        <td><?= $r['reg_id'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Amount:</strong></td>
                        <td>Rs. <?= $p['amount'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>TRANSACTION ID:</strong></td>
                        <td><?= $p['transaction_no'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>RECEIPT ID:</strong></td>
                        <td><?= $p['receipt_no'] ?></td>
                    </tr>
                <?php endif; ?>

                <tr>
                    <td><strong>Name:</strong></td>
                    <td><?= $r['sel_namesub'] . ' ' . $r['firstname'] . ' ' . $r['middlename'] . ' ' . $r['lastname'] ?></td>
                </tr>
                <tr>
                    <td><strong>Marital Status:</strong></td>
                    <td><?= $r['marital_status'] ?></td>
                </tr>
                <tr>
                    <td><strong>Spouse's Name:</strong></td>
                    <td><?= $o['spouse_name'] ?? '-' ?></td>
                </tr>
                <tr>
                    <td><strong>Father's Name:</strong></td>
                    <td><?= $r['father_husband_name'] ?></td>
                </tr>

                <?php if (in_array($pos, [7, 14, 12, 1, 15, 16, 17])): ?>
                    <tr>
                        <td><strong>Mother's Name:</strong></td>
                        <td><?= $o['mother_name'] ?? '' ?></td>
                    </tr>
                <?php endif; ?>

                <tr>
                    <td><strong>Age as on 01.02.2025:</strong></td>
                    <td><?= $r['dateofbirth'] ?></td>
                </tr>
                <tr>
                    <td><strong>Gender:</strong></td>
                    <td><?= $r['gender'] ?></td>
                </tr>

                <?php if (in_array($pos, [7, 14, 12, 1, 15, 16, 17])): ?>
                    <tr>
                        <td><strong>Religion:</strong></td>
                        <td><?= $o['religion'] ?? '' ?></td>
                    </tr>
                <?php endif; ?>

                <tr>
                    <td><strong>Email Id:</strong></td>
                    <td><?= $r['email'] ?></td>
                </tr>

                <?php if (in_array($pos, [7, 14, 12, 1, 15, 16, 17])): ?>
                    <tr>
                        <td><strong>Are you a person with Physical Disability:</strong></td>
                        <td><?= ucwords($o['physical_disbaility'] ?? '') ?></td>
                    </tr>
                    <?php if (($o['physical_disbaility'] ?? '') == 'yes'): ?>
                        <tr>
                            <td><strong>Type of Disability:</strong></td>
                            <td><?= $o['physical_disbaility_desc'] ?? '' ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endif; ?>

                <tr>
                    <td><strong>Mobile Number:</strong></td>
                    <td><?= $r['mobile'] ?></td>
                </tr>
                <tr>
                    <td><strong>Alternate Mobile Number:</strong></td>
                    <td><?= $r['alternate_mobile'] ?></td>
                </tr>
                <tr>
                    <td><strong>PAN Number:</strong></td>
                    <td><?= $r['pan_no'] ?></td>
                </tr>
                <tr>
                    <td><strong>Aadhar Card Number:</strong></td>
                    <td><?= $r['aadhar_card_no'] ?></td>
                </tr>

                <tr>
                    <td colspan="2" style="color:#66d9ff">
                        <h4><strong>COMMUNICATION ADDRESS</strong></h4>
                    </td>
                </tr>
                <tr>
                    <td><strong>COMMUNICATION ADDRESS:</strong></td>
                    <td><?= $r['addressline1'] . ', ' . $r['addressline2'] . ', ' . $r['addressline3'] . ', ' . $r['addressline4'] . ' ' . $r['district'] . ', ' . $r['city'] . ' ' . $r['state'] . ' ' . $r['pincode'] ?></td>
                </tr>

                <tr>
                    <td colspan="2" style="color:#66d9ff">
                        <h4><strong>PERMANENT ADDRESS</strong></h4>
                    </td>
                </tr>
                <tr>
                    <td><strong>PERMANENT ADDRESS:</strong></td>
                    <td><?= $r['addressline1_pr'] . ', ' . $r['addressline2_pr'] . ', ' . $r['addressline3_pr'] . ', ' . $r['addressline4_pr'] . ' ' . $r['district_pr'] . ', ' . $r['city_pr'] . ' ' . $s['state_name'] . ' ' . $r['pincode_pr'] ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- === EDUCATIONAL QUALIFICATION === -->
    <table class="table table-bordered wikitable tabela" style="overflow:wrap">
        <tbody>
            <tr>
                <td colspan="10" style="color:#66d9ff">
                    <h4 style="text-align:center"><strong>EDUCATIONAL QUALIFICATION</strong></h4>
                    <strong style="color:#444">ESSENTIAL</strong>
                </td>
            </tr>
            <tr>
                <td><strong>Name</strong></td>
                <td><strong>Graduation degree Name</strong></td>
                <td><strong>Graduation Stream & Subject</strong></td>
                <td><strong>College/Institution Name</strong></td>
                <td><strong>University Name</strong></td>
                <td><strong>Period</strong></td>
                <td><strong>Aggregate Marks Obtained</strong></td>
                <td><strong>Aggregate Maximum Marks</strong></td>
                <td><strong>Final Percentage</strong></td>
                <td><strong>Class/Grade</strong></td>
            </tr>
            <tr>
                <td>EDUCATIONAL Qualification 1 - Academic (Graduation Onwards)</td>
                <td><?= $r['ess_course_name'] ?></td>
                <td><?= $r['ess_pg_stream_subject'] ?></td>
                <td><?= $r['ess_college_name'] ?></td>
                <td><?= $r['ess_university'] ?></td>
                <td><?= date("d-m-Y", strtotime($r['ess_from_date'])) ?> TO <?= date("d-m-Y", strtotime($r['ess_to_date'])) ?></td>
                <td><?= $r['ess_aggregate_marks_obtained'] ?></td>
                <td><?= $r['ess_aggregate_max_marks'] ?></td>
                <td><?= $r['ess_percentage'] ?></td>
                <td><?= $r['ess_class'] ?></td>
            </tr>

            <?php if (!empty($o['post_qua_name'])): ?>
                <tr>
                    <td>Educational Qualification 2 - Post Graduation</td>
                    <td><?= $o['post_qua_name'] ?></td>
                    <td><?= $o['post_gra_sub'] ?></td>
                    <td><?= $o['post_gra_college_name'] ?></td>
                    <td><?= $o['post_gra_university'] ?></td>
                    <td><?= $o['post_gra_from_date'] ?> To <?= $o['post_gra_to_date'] ?></td>
                    <td><?= $o['post_aggregate_marks_obtained'] ?></td>
                    <td><?= $o['post_gra_aggregate_max_marks'] ?></td>
                    <td><?= $o['post_gra_percentage'] ?></td>
                    <td><?= $o['post_gra_class'] ?></td>
                </tr>
            <?php endif; ?>

            <!-- Add more rows as needed (certification, desirable, etc.) -->
            <!-- Just copy-paste from your original $html -->
        </tbody>
    </table>

    <!-- === EMPLOYMENT, LANGUAGES, REFERENCES, etc. === -->
    <!-- Paste the rest of your original HTML here using <?= $var ?> -->

    <!-- Example: Employment -->
    <?php if ($e): ?>
        <table class="table table-bordered wikitable tabela" style="margin-top:10px">
            <tbody>
                <tr>
                    <td colspan="2" style="color:#66d9ff">
                        <h4><strong>EMPLOYMENT HISTORY</strong></h4>
                    </td>
                </tr>
                <?php foreach ($e as $job): ?>
                    <tr>
                        <td><strong>Organization:</strong></td>
                        <td><?= $job['organization'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Period:</strong></td>
                        <td><?= date("d-m-Y", strtotime($job['job_from_date'])) ?> To <?= date("d-m-Y", strtotime($job['job_to_date'])) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Designation:</strong></td>
                        <td><?= $job['designation'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Responsibilities:</strong></td>
                        <td><?= $job['responsibilities'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- === SIGNATURE & PLACE/DATE === -->
    <table class="table table-bordered wikitable tabela">
        <tbody>
            <tr>
                <td colspan="2" style="color:#66d9ff">
                    <h4><strong>UPLOAD</strong></h4>
                </td>
            </tr>
            <tr>
                <td><strong>Signature:</strong></td>
                <td><img width="70" height="70" src="<?= base_url('uploads/scansignature/' . $r['scannedsignaturephoto']) ?>"></td>
            </tr>
            <tr>
                <td colspan="2" style="color:#66d9ff">
                    <h4><strong>Place and Date</strong></h4>
                </td>
            </tr>
            <tr>
                <td><strong>Place:</strong></td>
                <td><?= $r['place'] ?></td>
            </tr>
            <tr>
                <td><strong>Date:</strong></td>
                <td><?= date("d-m-Y", strtotime($r['submit_date'])) ?></td>
            </tr>
        </tbody>
    </table>

<?php endforeach; ?>