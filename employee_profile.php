<?php
session_start();
include 'config.php';

$emp_code = $_SESSION['emp_code']; // लॉगिन किया हुआ कर्मचारी

// कर्मचारी की जानकारी लाने की Query  
$query = "SELECT * FROM employees WHERE emp_code = '$emp_code'";
$result = mysqli_query($conn, $query);

if ($row = mysqli_fetch_assoc($result)) { 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            text-align: center;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #4caf50;
        }
        p {
            font-size: 18px;
            line-height: 1.6;
        }
        .button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4caf50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .button:hover {
            background-color: #388e3c;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Employee Profile</h2>
    <div class="profile-info">
        <p><strong>Employee Code:</strong> <?php echo htmlspecialchars($row['emp_code']); ?></p>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($row['name']); ?></p>
        <p><strong>Father's Name:</strong> <?php echo htmlspecialchars($row['father_name']); ?></p>
        <p><strong>Gender:</strong> <?php echo htmlspecialchars($row['gender']); ?></p>
        <p><strong>DOB:</strong> <?php echo htmlspecialchars($row['dob']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($row['phone']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($row['address']); ?></p>	
        <p><strong>Department:</strong> <?php echo htmlspecialchars($row['department']); ?></p>	
        <p><strong>Designation:</strong> <?php echo htmlspecialchars($row['designation']); ?></p>	
        <p><strong>Status:</strong> <?php echo htmlspecialchars($row['status']); ?></p>
        <p><strong>Role:</strong> <?php echo htmlspecialchars($row['role']); ?></p>
        <p><strong>Joining Date:</strong> <?php echo htmlspecialchars($row['joining_date']); ?></p>
    </div>

    <a href="employee_dashboard.php" class="button">Back to Dashboard</a>
</div>

</body>
</html>

<?php
} else {
    echo "<p>Employee not found!</p>";
}
?>