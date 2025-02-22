<?php
$servername = "localhost";
$username = "root"; // अपना यूज़रनेम डालें (अगर बदला हो)
$password = ""; // अपना पासवर्ड डालें (अगर कोई हो)
$dbname = "employee_portal_mark"; // सही Database Name डालें

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
?>