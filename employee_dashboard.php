<?php
session_start();
if (!isset($_SESSION['emp_code']) || $_SESSION['role'] != 'employee') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
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
            width: 80%;
            max-width: 800px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        h2 {
            color: #2e7d32;
            font-size: 26px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .nav-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .nav-buttons a {
            text-decoration: none;
            background: #4caf50;
            color: white;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
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
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background: #b71c1c;
        }

        .approved-leaves-btn {
            margin-top: 20px;
            display: inline-block;
            background: #007BFF;
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            transition: 0.3s;
        }

        .approved-leaves-btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

    <div class="dashboard-container">
        <h2>Welcome, <b><?php echo $_SESSION['emp_code']; ?></b> 👋</h2>

        <div class="nav-buttons">
            <a href="employee_attendance.php">📅 Mark Attendance</a>
            <a href="employee_attendance_records.php">📋 Attendance Records</a>
            <a href="employee_calendar.php">📆 Attendance Calendar</a>
            <a href="employee_leave.php">📝 Request Leave</a>
            <a href="employee_profile.php">👤 My Profile</a>
            <a href="shop_visit_form.php">🏪 Shop Visit</a>
        </div>

        <a href="approved_leaves.php" class="approved-leaves-btn">✅ View Approved Leaves</a>
        <a href="logout.php" class="logout-btn">🚪 Logout</a>
    </div>

</body>
</html>