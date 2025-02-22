<?php
include 'config.php';

// Fetch Approved Leaves for All Employees
$sql = "SELECT * FROM leave_requests WHERE status='Approved'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Approved Leaves</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: center; }
        .container { width: 80%; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); }
        h2 { color: #007BFF; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background-color: #007BFF; color: white; }
    </style>
</head>
<body>

<div class="container">
    <h2>All Approved Leaves</h2>
    <table>
        <tr>
            <th>Employee Code</th>
            <th>Leave From</th>
            <th>Leave To</th>
            <th>Days</th>
            <th>Emergency</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $row['emp_code'] ?></td>
            <td><?= $row['leave_from'] ?></td>
            <td><?= $row['leave_to'] ?></td>
            <td><?= (strtotime($row['leave_to']) - strtotime($row['leave_from'])) / (60 * 60 * 24) + 1 ?></td>
            <td><?= $row['is_emergency'] ? 'Yes' : 'No' ?></td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>