<?php
include 'db.php'; // Database Connection

// हर महीने Admin को 3 leaves ऑटोमैटिक ऐड करना
$valid_from = date("Y-m-01");
$valid_to = date("Y-m-t");

mysqli_query($conn, "UPDATE admin_leaves SET 
        casual_leaves = casual_leaves + 2, 
        other_leaves = other_leaves + 1, 
        total_leaves = total_leaves + 3,
        valid_from = '$valid_from',
        valid_to = '$valid_to',
        updated_at = NOW()");

// यदि Admin Leave Apply कर रहा है
if (isset($_POST['apply_leave'])) {
    $admin_id = $_POST['admin_id'];
    $leave_from = $_POST['leave_from'];
    $leave_to = $_POST['leave_to'];
    $days = (strtotime($leave_to) - strtotime($leave_from)) / (60 * 60 * 24) + 1;

    $leave_query = mysqli_query($conn, "SELECT total_leaves, used_leaves FROM admin_leaves WHERE admin_id = '$admin_id'");
    $leave_data = mysqli_fetch_assoc($leave_query);
    $remaining_leaves = $leave_data['total_leaves'] - $leave_data['used_leaves'];

    if ($days <= $remaining_leaves) {
        mysqli_query($conn, "UPDATE admin_leaves SET used_leaves = used_leaves + $days WHERE admin_id = '$admin_id'");
        mysqli_query($conn, "INSERT INTO leave_requests (admin_id, role, leave_from, leave_to, total_days, status) VALUES ('$admin_id', 'Admin', '$leave_from', '$leave_to', '$days', 'Pending')");
        $message = "✅ Leave Requested Successfully: $days days from $leave_from to $leave_to";
    } else {
        $message = "❌ Not Enough Leaves! You have only $remaining_leaves days left.";
    }
}

// Admin Leave List
$result = mysqli_query($conn, "SELECT * FROM admin_leaves");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Leave Management</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            width: 90%;
            max-width: 900px;
            background: white;
            padding: 30px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }
        h2 {
            color: #007BFF;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .leave-form {
            margin-top: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }
        input, button {
            padding: 10px;
            width: 80%;
            max-width: 300px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background-color: #007BFF;
            color: white;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            margin: 10px 0;
            font-weight: bold;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Admin Leave Details</h2>
    <?php if (isset($message)): ?>
        <p class="message <?php echo strpos($message, 'Successfully') !== false ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </p>
    <?php endif; ?>

    <table>
        <tr>
            <th>Admin ID</th>
            <th>Total Leaves</th>
            <th>Used Leaves</th>
            <th>Remaining Leaves</th>
            <th>Valid Period</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['admin_id']; ?></td>
                <td><?php echo $row['total_leaves']; ?></td>
                <td><?php echo $row['used_leaves']; ?></td>
                <td><?php echo ($row['total_leaves'] - $row['used_leaves']); ?></td>
                <td><?php echo date("d-M-Y", strtotime($row['valid_from'])) . " - " . date("d-M-Y", strtotime($row['valid_to'])); ?></td>
            </tr>
        <?php } ?>
    </table>

    <h2>Apply for Leave</h2>
    <form method="POST" action="" class="leave-form">
        <input type="text" name="admin_id" placeholder="Enter Admin ID" required>
        <label>Leave From:</label>
        <input type="date" name="leave_from" required>
        <label>Leave To:</label>
        <input type="date" name="leave_to" required>
        <button type="submit" name="apply_leave">Apply Leave</button>
    </form>
</div>
</body>
</html>