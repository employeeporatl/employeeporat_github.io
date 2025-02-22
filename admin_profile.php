<?php
include 'db.php'; // Database Connection

// Fetch all admin details
$query = "SELECT * FROM admins";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error fetching admin details: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            text-align: center;
            margin: 0;
            padding: 20px;
        }
        .container {
            width: 80%;
            margin: 30px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #4caf50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #4caf50;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Admin Profile</h2>

    <table>
        <tr>
            <th>Admin ID</th>
            <th>Employee Code</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Gender</th>
            <th>Role</th>
            <th>Joining Date</th>
            <th>Status</th>
            <th>Photo</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
        
                <td><?php echo $row['emp_code']; ?></td>  
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><?php echo $row['gender']; ?></td>
                <td><?php echo $row['role']; ?></td>
                <td><?php echo date("d-M-Y", strtotime($row['joining_date'])); ?></td>
                <td><?php echo $row['status']; ?></td>
                <td>
                    <?php if (!empty($row['photo'])) { ?>
                        <img src="uploads/<?php echo $row['photo']; ?>" alt="Admin Photo">
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>