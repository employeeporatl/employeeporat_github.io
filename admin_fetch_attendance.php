<?php
header('Content-Type: application/json');
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

// डेटाबेस से Admin की अटेंडेंस निकालना
$sql = "SELECT date, status FROM admin_attendance";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$attendance_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// अटेंडेंस को डेट-वाइज सेव करना
$attendance_records = [];
foreach ($attendance_data as $row) {
    $attendance_records[$row['date']] = $row['status'];
}

$events = [];

foreach ($period as $date) {
    $current_date = $date->format("Y-m-d");
    $day_of_week = $date->format("l");

    // अगर अटेंडेंस मौजूद है तो वही दिखेगी
    if (isset($attendance_records[$current_date])) {
        $status = $attendance_records[$current_date];
    } else {
        // संडे को हमेशा "Sunday" सेट करो
        if ($day_of_week == "Sunday") {
            $status = "Sunday";
        }
        // भविष्य की तारीखें ऑरेंज में दिखेंगी
        elseif ($current_date > $today) {
            $status = "Upcoming";
        }
        // अगर अटेंडेंस नहीं भरी गई तो अनुपस्थित मानें
        else {
            $status = "Absent";
        }
    }

    // कलर सेट करना
    $color = match ($status) {
        "Sunday", "Absent" => "red",  // संडे और अनुपस्थित रेड
        "Half Day" => "yellow", // हाफ डे येलो
        "Present" => "green", // उपस्थित हरा
        "Upcoming" => "orange", // आने वाले दिन ऑरेंज
        "Approved Leave" => "blue", // छुट्टी ब्लू
        default => "gray", // अन्य मामलों के लिए ग्रे
    };

    $events[] = [
        'title' => $status,
        'start' => $current_date,
        'color' => $color
    ];
}

echo json_encode($events);
?>