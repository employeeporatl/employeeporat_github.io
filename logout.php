<?php
session_start();
session_destroy(); // सेशन को समाप्त करें
header("Location: login.php"); // लॉगिन पेज पर भेजें
exit();
?>