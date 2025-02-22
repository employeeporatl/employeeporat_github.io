<?php
session_start();  // ✅ यह जरूरी है, ताकि session values सही से मिलें
include 'config.php';

if (!isset($_SESSION['emp_code'])) {
    die("Error: Employee not logged in.");
}

$emp_code = $_SESSION['emp_code']; // ✅ सही से Session से emp_code लें

// ✅ सही SQL Query जिससे सिर्फ Logged-in Employee की Approved Leaves दिखेंगी
$sql = "SELECT * FROM leave_requests WHERE emp_code='$emp_code' AND status='Approved'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approved Leaves</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: center; }
        .container { width: 60%; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); }
        h2 { color: #007BFF; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background-color: #007BFF; color: white; }
    </style>
</head>
<body>

<div class="container">
    <h2>Your Approved Leaves</h2>
    <?php if (mysqli_num_rows($result) > 0) { ?>
        <table>
            <tr>
                <th>Leave From</th>
                <th>Leave To</th>
                <th>Days</th>
                <th>Emergency</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= $row['leave_from'] ?></td>
                <td><?= $row['leave_to'] ?></td>
                <td><?= (strtotime($row['leave_to']) - strtotime($row['leave_from'])) / (60 * 60 * 24) + 1 ?></td>
                <td><?= $row['is_emergency'] ? 'Yes' : 'No' ?></td>
            </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p>No approved leaves found.</p>
    <?php } ?>
</div>

</body>
</html>