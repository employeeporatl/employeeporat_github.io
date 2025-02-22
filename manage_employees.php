<?php
session_start();
include 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['emp_code']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch employees from database
$result = mysqli_query($conn, "SELECT * FROM employees");

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Employees</title>
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

        .btn {
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            display: inline-block;
            margin: 5px;
        }

        .edit {
            background: #ffc107;
            color: black;
        }

        .delete {
            background: #d32f2f;
            color: white;
        }

        .add-btn {
            background: #2e7d32;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            border-radius: 5px;
        }

        .add-btn:hover {
            background: #1b5e20;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>👥 Manage Employees</h2>
        <a href="add_employee.php" class="add-btn">➕ Add Employee</a>

        <table>
            <tr>
                <th>Emp Code</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['emp_code'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($row['name'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($row['email'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($row['phone'] ?? 'N/A'); ?></td>
                    <td><?php echo !empty($row['role']) ? ucfirst(htmlspecialchars($row['role'])) : 'Not Assigned'; ?></td>
                    <td>
                        <?php if (isset($row['id'])) { ?>
                            <a href="edit_employee.php?id=<?php echo urlencode($row['id']); ?>" class="btn edit">✏ Edit</a>
                            <a href="delete_employee.php?id=<?php echo urlencode($row['id']); ?>" class="btn delete" onclick="return confirm('Are you sure?')">🗑 Delete</a>
                        <?php } else { ?>
                            <span style="color:red;">Error: No ID</span>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>

</body>
</html>