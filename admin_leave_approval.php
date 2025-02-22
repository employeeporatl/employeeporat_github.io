<?php
include 'config.php';

if (isset($_POST['approve']) || isset($_POST['reject'])) {
    $id = $_POST['leave_id'];
    $status = isset($_POST['approve']) ? 'Approved' : 'Rejected';

    // Update leave request status
    $query = "UPDATE leave_requests SET status = '$status' WHERE id = $id";
    mysqli_query($conn, $query);

    // If approved, update leave balance
    if ($status == 'Approved') {
        $leave_query = "SELECT emp_code, DATEDIFF(leave_to, leave_from) + 1 AS days FROM leave_requests WHERE id = $id";
        $leave_result = mysqli_query($conn, $leave_query);
        $leave_row = mysqli_fetch_assoc($leave_result);
        $emp_code = $leave_row['emp_code'];
        $days = $leave_row['days'];

        $update_balance = "UPDATE leave_balance SET used_leaves = used_leaves + $days WHERE emp_code = '$emp_code'";
        mysqli_query($conn, $update_balance);
    }
}

$result = mysqli_query($conn, "SELECT * FROM leave_requests WHERE status = 'Pending'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Approval</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #f4f4f4, #e0e0e0);
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
        }

        h2 {
            color: #2c3e50;
            font-size: 24px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background: #3498db;
            color: white;
            font-size: 16px;
        }

        td {
            background: #f9f9f9;
        }

        .btn {
            padding: 8px 16px;
            font-size: 14px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
        }

        .approve {
            background: #2ecc71;
            color: white;
        }

        .approve:hover {
            background: #27ae60;
        }

        .reject {
            background: #e74c3c;
            color: white;
        }

        .reject:hover {
            background: #c0392b;
        }

        .emergency {
            font-weight: bold;
            color: #e74c3c;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>📝 Pending Leave Requests</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Employee Code</th>
                <th>Leave From</th>
                <th>Leave To</th>
                <th>Emergency</th>
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['emp_code'] ?></td>
                <td><?= $row['leave_from'] ?></td>
                <td><?= $row['leave_to'] ?></td>
                <td class="<?= $row['is_emergency'] ? 'emergency' : '' ?>">
                    <?= $row['is_emergency'] ? '🔥 Yes' : 'No' ?>
                </td>
                <td>
                    <form method="post">
                        <input type="hidden" name="leave_id" value="<?= $row['id'] ?>">
                        <button type="submit" name="approve" class="btn approve">✅ Approve</button>
                        <button type="submit" name="reject" class="btn reject">❌ Reject</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>

</body>
</html>