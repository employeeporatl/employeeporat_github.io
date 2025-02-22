<?php
session_start();
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['emp_code'])) {
    die("Access Denied! Please <a href='login.php'>Login</a> first.");
}

// Fetch all employees from the database
$query = "SELECT * FROM employees";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("SQL Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            text-align: center;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #4caf50;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #4caf50;
            color: white;
        }

        .button {
            background-color: #4caf50;
            color: white;
            padding: 15px 30px;
            margin-top: 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 16px;
        }

        .button:hover {
            background-color: #388e3c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Employee Management</h2>

        <table>
            <thead>
                <tr>
                    <th>Employee Code</th>
                    <th>Employee Name</th>
                    <th>Role</th>
                    <th>Salary</th>
                    <th>Attendance</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    <?php
    while ($row = mysqli_fetch_assoc($result)) {
        $emp_code = htmlspecialchars($row['emp_code']);
        $emp_name = htmlspecialchars($row['name']);
        $role = isset($row['role']) ? htmlspecialchars($row['role']) : "Not Assigned"; // 🔹 Fix applied

        echo "<tr>
                <td>{$emp_code}</td>
                <td>{$emp_name}</td>
                <td>{$role}</td>
                <td><a href='attendance_records.php?emp_code=" . urlencode($emp_code) . "'>View Attendance</a></td>
                <td>
                    <a href='edit_employee.php?emp_code=" . urlencode($emp_code) . "'>Edit</a> |
                    <a href='delete_employee.php?emp_code=" . urlencode($emp_code) . "' onclick=\"return confirm('Are you sure?');\">Delete</a>
                </td>
            </tr>";
    }
    ?>
</tbody>
        </table>

        <a href="admin_dashboard.php" class="button">Back to Dashboard</a>
    </div>
</body>
</html>