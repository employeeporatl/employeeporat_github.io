<?php
$servername = "localhost"; // या आपका सर्वर नेम (e.g., 127.0.0.1)
$username = "root"; // आपका MySQL यूज़रनेम
$password = ""; // आपका MySQL पासवर्ड (XAMPP/WAMP में डिफॉल्ट खाली होता है)
$dbname = "employee_portal_mark"; // अपना डेटाबेस नेम डालें

// MySQL कनेक्शन बनाएं
$conn = new mysqli($servername, $username, $password, $dbname);

// कनेक्शन चेक करें
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set UTF-8 character encoding
$conn->set_charset("utf8");

?>