<?php
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, user_id, start_date, end_date, reason, status FROM leaves";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>User ID</th><th>Start Date</th><th>End Date</th><th>Reason</th><th>Status</th><th>Action</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["user_id"] . "</td>";
        echo "<td>" . $row["start_date"] . "</td>";
        echo "<td>" . $row["end_date"] . "</td>";
        echo "<td>" . $row["reason"] . "</td>";
        echo "<td>" . $row["status"] . "</td>";
        echo "<td><a href='approve_leave.php?leave_id=" . $row["id"] . "&action=approve'>Approve</a> | <a href='approve_leave.php?leave_id=" . $row["id"] . "&action=reject'>Reject</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No leave requests found.";
}

$conn->close();
?>