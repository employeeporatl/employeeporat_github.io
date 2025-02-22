<?php
session_start();
require_once 'connection.php';

// यदि कर्मचारी लॉगिन नहीं है तो उसे login.php पर भेजें
if (!isset($_SESSION['emp_code'])) {
    header("Location: login.php");
    exit();
}

$emp_code = $_SESSION['emp_code'];

// उपस्थिति रिकॉर्ड्स प्राप्त करें
$query = "SELECT * FROM attendance WHERE emp_code = '$emp_code' ORDER BY date DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die('Query Failed: ' . mysqli_error($conn));  
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Records</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .attendance-container {
            width: 80%;
            max-width: 1000px;
            background-color: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            text-align: center;
            border: 1px solid #ccc;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        .leave {
            background-color: #ffeb3b; /* Yellow for leave */
        }

        .half-day {
            background-color: #f57c00; /* Orange for half-day */
        }

        .full-day {
            background-color: #388e3c; /* Green for full-day */
        }
    </style>
</head>
<body>

    <div class="attendance-container">
        <h2>Attendance Records</h2>

        <table>
            <tr>
                <th>Date</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Status</th>
            </tr>
            <?php while ($attendance = mysqli_fetch_assoc($result)): ?>
                <?php
                    $check_in_time = strtotime($attendance['check_in']);
                    $check_out_time = strtotime($attendance['check_out']);
                    $work_duration = ($check_out_time - $check_in_time) / 3600; // Hours worked

                    if ($work_duration < 4) {
                        $status_class = "leave";
                        $status_text = "Leave";
                    } elseif ($work_duration >= 4 && $work_duration < 8) {
                        $status_class = "half-day";
                        $status_text = "Half Day";
                    } else {
                        $status_class = "full-day";
                        $status_text = "Full Day";
                    }
                ?>
                <tr class="<?php echo $status_class; ?>">
                    <td><?php echo $attendance['date']; ?></td>
                    <td><?php echo $attendance['check_in']; ?></td>
                    <td><?php echo $attendance['check_out']; ?></td>
                    <td><?php echo $status_text; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

</body>
</html>