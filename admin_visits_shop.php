<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "employee_portal_mark";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM shop_visits";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Shop Visits</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1000px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .no-records {
            text-align: center;
            font-size: 18px;
            color: #555;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Admin Panel - Shop Visits</h1>

    <table>
        <tr>
            <th>Shop Name</th>
            <th>Employee Code</th>
            <th>Location Name</th>
            <th>Latitude</th>
            <th>Longitude</th>
            <th>Visit Time</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["shop_name"]. "</td><td>" . $row["employee_code"]. "</td><td>" . $row["location_name"]. "</td><td>" . $row["latitude"]. "</td><td>" . $row["longitude"]. "</td><td>" . $row["visit_time"]. "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='6' class='no-records'>No visits recorded</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</div>

</body>
</html>