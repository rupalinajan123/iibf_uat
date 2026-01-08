<!DOCTYPE html>
<html>

<head>
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

        .column {
            float: right;
        }

        img {
            text-align: right;
        }
    </style>
</head>

<body>

    <h1 style="text-align:center">APPLICATION</h1>

    <div class="table-responsive">
        <table class="table table-bordered wikitable tabela" style="overflow-wrap:break-word">
            <tbody>

                <!-- Example of PHP variables inside HTML -->
                <tr>
                    <td width="50%"><strong>APPLIED FOR POSITION:</strong></td>
                    <td width="50%">
                        <?php
                        if ($position_id == 1) echo "Junior Executive";
                        elseif ($position_id == 2) echo "Assistant Director (IT)";
                        elseif ($position_id == 3) echo "Assistant Director (Accounts)";
                        elseif ($position_id == 4) echo "Director (Training) on Contract";
                        ?>
                    </td>
                </tr>

                <tr>
                    <td width="50%"><strong>PHOTO:</strong></td>
                    <td width="50%">
                        <img width="70px" height="70px" src="<?= base_url('uploads/photograph/' . $rst[0]['scannedphoto']) ?>" />
                    </td>
                </tr>

                <tr>
                    <td><strong>NAME:</strong></td>
                    <td><?= $rst[0]["sel_namesub"] . $rst[0]["firstname"] . ' ' . $rst[0]['middlename'] . ' ' . $rst[0]['lastname'] ?></td>
                </tr>

                <tr>
                    <td><strong>EMAIL:</strong></td>
                    <td><?= $rst[0]['email'] ?></td>
                </tr>

                <!-- Add the rest of your fields below as they were -->
                <!-- Example for looping -->
                <?php foreach ($qualification_arr as $row): ?>
                    <tr>
                        <td><strong>COURSE NAME:</strong></td>
                        <td><?= $row['course_name'] ?></td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    </div>

</body>

</html>