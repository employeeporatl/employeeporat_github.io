<?php
session_start();
include 'config.php';

// Check Admin Role
if (!isset($_SESSION['emp_code']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch Approved Leaves
$sql = "SELECT emp_code, leave_from, leave_to FROM leave_requests WHERE status='Approved'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Approved Leaves</title>
</head>
<body>
    <h2>Approved Leaves</h2>
    <table border="1">
        <tr>
            <th>Employee Code</th>
            <th>Leave From</th>
            <th>Leave To</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['emp_code']; ?></td>
            <td><?php echo $row['leave_from']; ?></td>
            <td><?php echo $row['leave_to']; ?></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>