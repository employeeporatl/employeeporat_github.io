<?php
$host = "localhost";
$dbname = "employee_portal_mark"; // अपने डेटाबेस का सही नाम डालें
$username = "root"; // XAMPP में डिफ़ॉल्ट 'root' होता है
$password = ""; // XAMPP में डिफ़ॉल्ट पासवर्ड ब्लैंक होता है

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
?>