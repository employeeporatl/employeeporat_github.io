<?php
session_start();
include('db.php');
// Check if admin is logged in
if (!isset($_SESSION['emp_code']) || $_SESSION['role'] !== 'Admin') {
    die("❌ Access Denied! Only Admins can manage levels.");
}

// Fetch all employees
$employees = mysqli_query($conn, "SELECT emp_code, level FROM employee_levels");

// Handle Level Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_level'])) {
    $emp_code = $_POST['emp_code'];
    $new_level = $_POST['new_level'];

    $update_query = "UPDATE employee_levels SET level='$new_level' WHERE emp_code='$emp_code'";
    if (mysqli_query($conn, $update_query)) {
        $message = "✅ Level updated successfully!";
    } else {
        $message = "❌ Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Level Management</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; background: #f4f4f4; }
        .container { width: 50%; margin: auto; background: white; padding: 20px; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 10px; }
        select, button { padding: 8px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Employee Level Management</h2>
    <?php if (isset($message)) echo "<p>$message</p>"; ?>
    
    <table>
        <tr>
            <th>Employee Code</th>
            <th>Current Level</th>
            <th>Change Level</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($employees)): ?>
            <tr>
                <td><?php echo $row['emp_code']; ?></td>
                <td><?php echo $row['level']; ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="emp_code" value="<?php echo $row['emp_code']; ?>">
                        <select name="new_level">
                            <option value="Junior">Junior</option>
                            <option value="Mid">Mid</option>
                            <option value="Senior">Senior</option>
                            <option value="Lead">Lead</option>
                            <option value="Manager">Manager</option>
                        </select>
                        <button type="submit" name="update_level">Update</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>