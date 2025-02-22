<?php
// सत्र शुरू करें
session_start();
// व्यवस्थापक प्रमाणीकरण जाँचें
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}
include 'config.php'; // डेटाबेस कनेक्शन

// लंबित अवकाश अनुरोध प्राप्त करें
$sql = "SELECT lr.id, lr.emp_code, e.name, lr.leave_from, lr.leave_to, lr.days_requested, lr.is_emergency, lr.applied_on
        FROM leave_requests lr
        JOIN employees e ON lr.emp_code = e.emp_code
        WHERE lr.status = 'pending'";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>व्यवस्थापक डैशबोर्ड</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f4f4f4; }
        .approve-btn { background-color: #4CAF50; color: white; padding: 5px 10px; text-decoration: none; }
        .reject-btn { background-color: #f44336; color: white; padding: 5px 10px; text-decoration: none; }
    </style>
</head>
<body>
    <h2>लंबित अवकाश अनुरोध</h2>
    <table>
        <thead>
            <tr>
                <th>कर्मचारी कोड</th>
                <th>नाम</th>
                <th>अवकाश से</th>
                <th>अवकाश तक</th>
                <th>कुल दिन</th>
                <th>आपातकालीन</th>
                <th>आवेदन तिथि</th>
                <th>कार्रवाई</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['emp_code']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['leave_from']; ?></td>
                    <td><?php echo $row['leave_to']; ?></td>
                    <td><?php echo $row['days_requested']; ?></td>
                    <td><?php echo $row['is_emergency'] ? 'हाँ' : 'नहीं'; ?></td>
                    <td><?php echo $row['applied_on']; ?></td>
                    <td>
                        <a href="process_leave.php?id=<?php echo $row['id']; ?>&action=approve" class="approve-btn">स्वीकृत</a>
                        <a href="process_leave.php?id=<?php echo $row['id']; ?>&action=reject" class="reject-btn">अस्वीकृत</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>