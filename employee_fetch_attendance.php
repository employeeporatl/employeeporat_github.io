<?php
header('Content-Type: application/json');
session_start();
include 'config.php';

$emp_code = $_SESSION['emp_code']; // Current Logged-in Employee

$pdo = new PDO("mysql:host=localhost;dbname=employee_portal_mark", "root", "");

// पूरे महीने की तारीखें निकालना
$start_date = date("Y-m-01");
$end_date = date("Y-m-t");
$today = date("Y-m-d");

$period = new DatePeriod(
    new DateTime($start_date),
    new DateInterval('P1D'),
    new DateTime($end_date . ' 23:59:59')
);

// *Employee की Attendance लाना*
$sql_attendance = "SELECT date, status FROM attendance WHERE emp_code = ?";
$stmt = $pdo->prepare($sql_attendance);
$stmt->execute([$emp_code]);
$attendance_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// *Approved Leaves लाना*
$sql_leaves = "SELECT leave_from, leave_to FROM leave_requests WHERE emp_code = ? AND status = 'Approved'";
$stmt = $pdo->prepare($sql_leaves);
$stmt->execute([$emp_code]);
$leave_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// *Attendance को डेट-वाइज सेव करना*
$attendance_records = [];
foreach ($attendance_data as $row) {
    $attendance_records[$row['date']] = $row['status'];
}

// *Leaves को डेट-वाइज सेव करना*
$leaves = [];
foreach ($leave_data as $row) {
    $start = strtotime($row['leave_from']);
    $end = strtotime($row['leave_to']);
    for ($date = $start; $date <= $end; $date += 86400) {
        $leaves[date('Y-m-d', $date)] = 'Approved Leave';
    }
}

$events = [];

foreach ($period as $date) {
    $current_date = $date->format("Y-m-d");
    $day_of_week = $date->format("l");

    // *अगर Leave Approved है*
    if (isset($leaves[$current_date])) {
        $status = "Approved Leave";
        $color = "blue";
    }
    // *अगर अटेंडेंस मौजूद है तो वही दिखेगी*
    elseif (isset($attendance_records[$current_date])) {
        $status = $attendance_records[$current_date];
        $color = match ($status) {
            "Present" => "green",
            "Half Day" => "yellow",
            "Absent" => "red",
            default => "gray"
        };
    }
    // *अगर संडे है*
    elseif ($day_of_week == "Sunday") {
        $status = "Sunday";
        $color = "red";
    }
    // *अगर भविष्य की तारीख है*
    elseif ($current_date > $today) {
        $status = "Upcoming";
        $color = "orange";
    }
    // *अगर Attendance नहीं भरी गई तो Absent*
    else {
        $status = "Absent";
        $color = "red";
    }

    $events[] = [
        'title' => $status,
        'start' => $current_date,
        'color' => $color
    ];
}

echo json_encode($events);
?>