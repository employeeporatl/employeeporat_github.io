<?php
session_start();
include 'config.php';

$emp_code = $_SESSION['emp_code']; // Employee Code from session

// *Fetch Leave Balance*
$sql_balance = "SELECT total_leaves, used_leaves FROM leave_balance WHERE emp_code='$emp_code'";
$result_balance = mysqli_query($conn, $sql_balance);
$leave_data = mysqli_fetch_assoc($result_balance);

// *अगर डेटा नहीं मिला, तो Default Value सेट करो*
if (!$leave_data) {
    $leave_data = ['total_leaves' => 24, 'used_leaves' => 0];
}

// *Calculate Remaining Leaves*
$remaining_leaves = max(0, $leave_data['total_leaves'] - $leave_data['used_leaves']); // Negative Value को 0 कर देगा

// *Handle Leave Apply Form*
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $leave_from = $_POST['leave_from'];
    $leave_to = $_POST['leave_to'];
    $reason = $_POST['reason'];
    $is_emergency = isset($_POST['is_emergency']) ? 1 : 0;

    // *Requested Days Calculate*
    $requested_days = (strtotime($leave_to) - strtotime($leave_from)) / (60 * 60 * 24) + 1;

    if ($requested_days <= $remaining_leaves && $requested_days > 0) {
        // *Leave Request Insert*
        $query = "INSERT INTO leave_requests (emp_code, leave_from, leave_to, reason, is_emergency, status) 
                  VALUES ('$emp_code', '$leave_from', '$leave_to', '$reason', '$is_emergency', 'Pending')";
        if (mysqli_query($conn, $query)) {
            // *Update Used Leaves*
            $update_leave = "UPDATE leave_balance SET used_leaves = used_leaves + $requested_days WHERE emp_code='$emp_code'";
            mysqli_query($conn, $update_leave);
            $message = "<p class='success'>✅ Leave Applied Successfully!</p>";
        } else {
            $message = "<p class='error'>❌ Error: " . mysqli_error($conn) . "</p>";
        }
    } else {
        $message = "<p class='error'>⚠ Not Enough Leave Balance or Invalid Dates!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Leave Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            text-align: center;
        }
        .container {
            max-width: 500px;
            background: white;
            padding: 20px;
            margin: 50px auto;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
        }
        h2 {
            color: #333;
        }
        .leave-box {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
        }
        .total { background: #007bff; color: white; }
        .used { background: #dc3545; color: white; }
        .remaining { background: #28a745; color: white; }
        .form-group {
            margin: 10px 0;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input, textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
    </style>
</head>
<body>

    <div class="container">
        <h2>📅 Employee Leave Management</h2>

        <!-- Show Leave Balance -->
        <div class="leave-box total">Total Leaves: <?php echo $leave_data['total_leaves']; ?></div>
        <div class="leave-box used">Used Leaves: <?php echo $leave_data['used_leaves']; ?></div>
        <div class="leave-box remaining">Remaining Leaves: <?php echo $remaining_leaves; ?></div>

        <?php echo $message; ?>

        <!-- Apply Leave Form -->
        <h3>Apply for Leave</h3>
        <form action="" method="POST">
            <div class="form-group">
                <label>Leave From:</label>
                <input type="date" name="leave_from" required>
            </div>
            <div class="form-group">
                <label>Leave To:</label>
                <input type="date" name="leave_to" required>
            </div>
            <div class="form-group">
                <label>Reason for Leave:</label>
                <textarea name="reason" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_emergency"> 🚨 Emergency Leave
                </label>
            </div>
            <button type="submit">Apply Leave</button>
        </form>
    </div>

</body>
</html>