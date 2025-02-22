<?php
session_start();
require_once 'connection.php';

// यदि कर्मचारी लॉगिन नहीं है तो उसे login.php पर भेजें
if (!isset($_SESSION['emp_code'])) {
    header("Location: login.php");
    exit();
}

$emp_code = $_SESSION['emp_code'];
date_default_timezone_set('Asia/Kolkata');

// वर्तमान समय प्राप्त करें (12-घंटे फॉर्मेट में)
$current_time = date('h:i:s a');
$current_date = date('y-m-d');

// डेटाबेस से आज का उपस्थिति रिकॉर्ड चेक करें
$query = "SELECT * FROM attendance WHERE emp_code = '$emp_code' AND date = '$current_date'";
$result = mysqli_query($conn, $query);
$attendance = mysqli_fetch_assoc($result);

// यदि आज के लिए कोई रिकॉर्ड नहीं है तो स्टेटस "Not Checked In Yet" होगा
$last_status = $attendance ? $attendance['status'] : 'Not Checked In Yet';

// चेक-इन या चेक-आउट पर एक्शन करें
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'check_in' && !$attendance) {
        // यदि आज चेक-इन नहीं हुआ है, तो नया एंट्री डालें
        $query_check_in = "INSERT INTO attendance (emp_code, date, check_in, status) VALUES ('$emp_code', '$current_date', '$current_time', 'Checked In')";
        mysqli_query($conn, $query_check_in);
    } elseif ($_POST['action'] == 'check_out' && $attendance && !$attendance['check_out']) {
        // यदि आज चेक-इन हुआ है और चेक-आउट नहीं हुआ है, तो चेक-आउट करें
        $query_check_out = "UPDATE attendance SET check_out = '$current_time', status = 'Checked Out' WHERE emp_code = '$emp_code' AND date = '$current_date'";
        mysqli_query($conn, $query_check_out);

        // चेक-इन और चेक-आउट का टाइम निकालकर वर्किंग आवर्स निकालें
        $check_in_time = strtotime($attendance['check_in']);
        $check_out_time = strtotime($current_time);
        $worked_hours = ($check_out_time - $check_in_time) / 3600; // घंटों में बदलें

        // स्टेटस अपडेट करें: 8 घंटे = Full Day, 4-8 घंटे = Half Day, 4 घंटे से कम = Leave
        $status = ($worked_hours >= 8) ? 'Full Day' : (($worked_hours >= 4) ? 'Half Day' : 'Leave');

        $query_update_status = "UPDATE attendance SET status = '$status' WHERE emp_code = '$emp_code' AND date = '$current_date'";
        mysqli_query($conn, $query_update_status);
    }

    header("Location: employee_attendance.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Attendance</title>
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

    <h2>Employee Attendance</h2>
    
    <div class="container">
        <form method="POST" action="employee_attendance.php">
            <?php if (!$attendance): ?>
                <button type="submit" name="action" value="check_in" class="button">Check In</button>
            <?php elseif ($attendance && !$attendance['check_out']): ?>
                <button type="submit" name="action" value="check_out" class="button">Check Out</button>
            <?php endif; ?>
        </form>

        <h3>Today's Attendance Status</h3>
        <p>Status: <?php echo $last_status; ?></p>
        <p>Check-in Time: <?php echo $attendance['check_in'] ?? 'Not Checked In Yet'; ?></p>
        <p>Check-out Time: <?php echo $attendance['check_out'] ?? 'Not Checked Out Yet'; ?></p>

        <h3>Attendance Records</h3>
        <table>
            <tr>
                <th>Date</th>
                <th>Check-in Time</th>
                <th>Check-out Time</th>
                <th>Status</th>
            </tr>
            <?php
            // सभी अटेंडेंस रिकॉर्ड्स लोड करें
            $query_records = "SELECT * FROM attendance WHERE emp_code = '$emp_code' ORDER BY date DESC";
            $result_records = mysqli_query($conn, $query_records);

            while ($record = mysqli_fetch_assoc($result_records)):
                ?>
                <tr>
                    <td><?php echo $record['date']; ?></td>
                    <td><?php echo $record['check_in'] ?? 'Not Checked In Yet'; ?></td>
                    <td><?php echo $record['check_out'] ?? 'Not Checked Out Yet'; ?></td>
                    <td><?php echo $record['status']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

</body>
</html>
