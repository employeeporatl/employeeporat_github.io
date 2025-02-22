<?php
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emp_code = trim($_POST['emp_code']);
    $password = trim($_POST['password']);

    if (!empty($emp_code) && !empty($password)) {
        $sql = "SELECT emp_code, password, role FROM user WHERE emp_code = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("s", $emp_code); // 🛠 *Mistake Fixed*
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                if ($password === $row['password']) { // Change this to password_verify() if using hashed passwords
                    $_SESSION['emp_code'] = $row['emp_code'];
                    $_SESSION['role'] = $row['role'];

                    if ($row['role'] === 'admin') {
                        header("Location: admin_dashboard.php");
                    } else {
                        header("Location: employee_dashboard.php");
                    }
                    exit();
                } else {
                    $error = "Invalid Password!";
                }
            } else {
                $error = "Employee Code not found!";
            }
            $stmt->close();
        } else {
            $error = "SQL Error!";
        }
    } else {
        $error = "All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: center; }
        .container { width: 30%; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); }
        h2 { color: #007BFF; }
        .error { color: red; }
        input, button { width: 80%; padding: 10px; margin: 5px 0; border-radius: 5px; }
        button { background-color: #007BFF; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>

<div class="container">
    <h2>Employee Portal Login</h2>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form action="" method="POST">
        <input type="text" name="emp_code" placeholder="Employee Code" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>