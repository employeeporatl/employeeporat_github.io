<?php
$host = "localhost"; 
$dbname = "employee_portal_mark"; // यहाँ सही डेटाबेस नाम डालें
$username = "root";  // XAMPP में डिफ़ॉल्ट यूज़रनेम root
$password = "";  // XAMPP में डिफ़ॉल्ट पासवर्ड खाली होता है

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>