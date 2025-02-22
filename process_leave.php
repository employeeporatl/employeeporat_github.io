<?php
session_start();
include('config.php');

if (!isset($_SESSION['emp_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];

    if ($action == 'approve' || $action == 'reject') {
        $status = $action;
        $query = "UPDATE leave_requests SET status = '$status' WHERE id = $id";
        if (mysqli_query($conn, $query)) {
            echo "Leave request $status successfully.";
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    } else {
        echo "Invalid action.";
    }
} else {
    echo "Invalid request.";
}
?>