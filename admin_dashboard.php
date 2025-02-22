<?php
session_start();
if (!isset($_SESSION['emp_code']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #f0f4f8, #dfe9f3);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .dashboard-container {
            width: 90%;
            max-width: 1000px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        h2 {
            color: #2e7d32;
            font-size: 28px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .nav-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .nav-buttons a {
            text-decoration: none;
            background: #4caf50;
            color: white;
            padding: 14px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
        }

        .nav-buttons a:hover {
            background: #388e3c;
            transform: scale(1.05);
        }

        .logout-btn {
            margin-top: 20px;
            display: inline-block;
            background: #d32f2f;
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
            text-decoration: none;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background: #b71c1c;
        }
    </style>
</head>
<body>

    <div class="dashboard-container">
        <h2>👨‍💼 Admin Dashboard - Welcome, <b><?php echo $_SESSION['emp_code']; ?></b></h2>

        <div class="nav-buttons">
        <div class="nav-buttons">
            <a href="admin_attendance.php">📅 Mark Attendance</a>
            <a href="admin_attendance_records.php">📋 Attendance Records</a>
            <a href="admin_calendar.php">📆 Attendance Calendar</a>
            <a href="admin_leave.php">📝 Request Leave</a>
            <a href="manage_employees.php">👥 Manage Employees</a>
            <a href="admin_visits_shop.php">📊 Shop Visit Reports</a>
            <a href="attendance_overview.php">📅 Attendance Overview</a>
            <a href="admin_approved_leaves.php">📝 Approve Leaves</a>
            <a href="admin_leave_approval.php">📝 Approve Leave</a>
            <a href="reports.php">📊 Reports & Analytics</a>
            <a href="admin_profile.php">👤 Admin Profile</a>

        </div>

        <a href="logout.php" class="logout-btn">🚪 Logout</a>
    </div>

</body>
</html>

