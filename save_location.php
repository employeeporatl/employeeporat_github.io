<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "employee_portal_mark";  // Make sure your database name is correct

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $shop_name = $_POST['shop_name'];
    $employee_code = $_POST['employee_code'];
    $location_name = $_POST['location_name'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // Prepare the SQL query
    $sql = "INSERT INTO shop_visits (shop_name, employee_code, location_name, latitude, longitude) 
            VALUES (?, ?, ?, ?, ?)";

    // Prepare statement
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("sssss", $shop_name, $employee_code, $location_name, $latitude, $longitude);

        // Execute the query
        if ($stmt->execute()) {
            echo "Location data saved successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing the statement: " . $conn->error;
    }
}

// Close the connection
$conn->close();
?>