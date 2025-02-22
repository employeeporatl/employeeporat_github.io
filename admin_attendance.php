<?php
session_start();
require_once 'connection.php';

// यदि Admin लॉगिन नहीं है तो उसे login.php पर भेजें
if (!isset($_SESSION['emp_code'])) {
    header("Location: login.php");
    exit();
}


$emp_code = $_SESSION['emp_code'];
date_default_timezone_set('Asia/Kolkata');

// वर्तमान समय प्राप्त करें (12-घंटे फॉर्मेट में)
$current_time = date('h:i:s a');
$current_date = date('y-m-d');

// पुराने रिकॉर्ड हटाएं (आज से पहले के सभी रिकॉर्ड हट जाएंगे)
$query_delete_old = "DELETE FROM attendance WHERE date < '$current_date'";
mysqli_query($conn, $query_delete_old);

// डेटाबेस से आज का उपस्थिति रिकॉर्ड चेक करें
$query = "SELECT * FROM attendance WHERE emp_code = '$emp_code' AND date = '$current_date'";
$result = mysqli_query($conn, $query);
$attendance = mysqli_fetch_assoc($result);

// यदि आज के लिए कोई रिकॉर्ड नहीं है तो स्टेटस "Not Checked In Yet" होगा
$last_status = $attendance ? $attendance['status'] : 'Not Checked In Yet';

// ✅ *Check-in & Check-out Logic*
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'check_in' && (!$attendance || empty($attendance['check_in']))) {
        $query_check_in = "INSERT INTO attendance (emp_code, date, check_in, status) 
                           VALUES ('$emp_code', '$current_date', '$current_time', 'Checked In')";
        if (!mysqli_query($conn, $query_check_in)) {
            die("Error: " . mysqli_error($conn));
        }
    } elseif ($_POST['action'] == 'check_out' && $attendance && empty($attendance['check_out'])) {
        $query_check_out = "UPDATE attendance 
                            SET check_out = '$current_time', status = 'Checked Out' 
                            WHERE emp_code = '$emp_code' AND date = '$current_date'";
        if (!mysqli_query($conn, $query_check_out)) {
            die("Error: " . mysqli_error($conn));
        }

        // ✅ *Check-in टाइम को सही से निकालो*  
        $query_get_checkin = "SELECT check_in FROM attendance WHERE emp_code = '$emp_code' AND date = '$current_date'";
        $result_checkin = mysqli_query($conn, $query_get_checkin);
        $row_checkin = mysqli_fetch_assoc($result_checkin);
        $check_in_time = strtotime($row_checkin['check_in']);  // ✅ सही चेक-इन टाइम मिलेगा  
        $check_out_time = strtotime($current_time);  

        $worked_hours = ($check_out_time - $check_in_time) / 3600; // घंटों में बदलें

        // ✅ *सही स्टेटस अपडेट करें:*  
        if ($worked_hours >= 8) {
            $status = 'Full Day';
        } elseif ($worked_hours >= 4) {
            $status = 'Half Day';
        } else {
            $status = 'Leave';
        }

        $query_update_status = "UPDATE attendance SET status = '$status' 
                                WHERE emp_code = '$emp_code' AND date = '$current_date'";
        if (!mysqli_query($conn, $query_update_status)) {
            die("Error: " . mysqli_error($conn));
        }
    }

    header("Location: admin_attendance.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Attendance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .button {
            display: block;
            width: 200px;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            text-align: center;
            margin: 10px auto;
            border-radius: 5px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2>Admin Attendance</h2>
    <div class="container">
        <form method="POST" action="admin_attendance.php">
            <?php if (!$attendance || empty($attendance['check_in'])): ?>
                <button type="submit" name="action" value="check_in" class="button">Check In</button>
            <?php elseif ($attendance && empty($attendance['check_out'])): ?>
                <button type="submit" name="action" value="check_out" class="button">Check Out</button>
            <?php endif; ?>
        </form>

        <h3>Today's Attendance Status</h3>
        <p>Status: <?php echo $last_status; ?></p>
        <p>Check-in Time: <?php echo $attendance['check_in'] ?? 'Not Checked In Yet'; ?></p>
        <p>Check-out Time: <?php echo $attendance['check_out'] ?? 'Not Checked Out Yet'; ?></p>
        <p>Current Time: <?php echo date('h:i:s A'); ?></p>
    </div>
</body>
</html>