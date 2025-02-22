<?php
session_start();
include 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['emp_code']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch attendance records
$result = mysqli_query($conn, "SELECT * FROM attendance ORDER BY date DESC");

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Overview</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            text-align: center;
            padding: 20px;
        }

        .container {
            width: 90%;
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
        }

        h2 {
            color: #2e7d32;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #2e7d32;
            color: white;
        }

        .present {
            color: green;
            font-weight: bold;
        }

        .absent {
            color: red;
            font-weight: bold;
        }

        .on-leave {
            color: orange;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>📅 Attendance Overview</h2>

        <table>
            <tr>
                <th>Emp Code</th>
                <th>Name</th>
                <th>Date</th>
                <th>Status</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Working Hours</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['emp_code'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($row['name'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($row['date'] ?? 'N/A'); ?></td>
                    <td>
                        <?php
                        if ($row['status'] == 'Present') {
                            echo '<span class="present">✔ Present</span>';
                        } elseif ($row['status'] == 'Absent') {
                            echo '<span class="absent">✖ Absent</span>';
                        } elseif ($row['status'] == 'Leave') {
                            echo '<span class="on-leave">🟠 On Leave</span>';
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['check_in'] ?? '--'); ?></td>
                    <td><?php echo htmlspecialchars($row['check_out'] ?? '--'); ?></td>
                    <td><?php echo htmlspecialchars($row['working_hours'] ?? '--'); ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>

</body>
</html>