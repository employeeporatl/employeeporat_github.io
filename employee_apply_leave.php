<?php
session_start();
include 'config.php'; // Database Connection

// ✅ Check if Employee is Logged In
if (!isset($_SESSION['emp_code'])) {
    die("Error: Please login first!");
}

$emp_code = $_SESSION['emp_code']; // Employee Code from session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $leave_from = $_POST['leave_from'];
    $leave_to = $_POST['leave_to'];
    $is_emergency = isset($_POST['is_emergency']) ? 1 : 0;

    // ✅ Insert Leave Request into Database
    $sql = "INSERT INTO leave_requests (emp_code, leave_from, leave_to, is_emergency, status) 
            VALUES ('$emp_code', '$leave_from', '$leave_to', '$is_emergency', 'Pending')";

    if (mysqli_query($conn, $sql)) {
        echo "<p style='color:green;'>Leave Applied Successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply Leave</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: center; }
        .container { width: 50%; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); }
        h2 { color: #007BFF; }
        .message { font-weight: bold; margin: 10px 0; }
        .success { color: green; }
        .error { color: red; }
        input, button { width: 80%; max-width: 300px; padding: 10px; margin: 5px 0; border-radius: 5px; }
        button { background-color: #007BFF; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .checkbox-label { display: block; margin: 10px 0; }
    </style>
</head>
<body>

<div class="container">
    <h2>Apply for Leave</h2>
    <form action="" method="POST">
        <label>Leave From:</label>
        <input type="date" name="leave_from" required>
        
        <label>Leave To:</label>
        <input type="date" name="leave_to" required>
        
        <label class="checkbox-label">
            <input type="checkbox" name="is_emergency"> Emergency Leave
        </label>
        
        <button type="submit">Apply Leave</button>
    </form>
</div>

</body>
</html>