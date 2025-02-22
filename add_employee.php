<?php
session_start();
include 'config.php';

if (!isset($_SESSION['emp_code']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emp_code = trim($_POST['emp_code']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $role = trim($_POST['role']);
    $password = trim($_POST['password']);

    if (empty($emp_code) || empty($name) || empty($email) || empty($phone) || empty($role) || empty($password)) {
        $error = "⚠ All fields are required!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO employees (emp_code, name, email, phone, role, password) 
                  VALUES ('$emp_code', '$name', '$email', '$phone', '$role', '$hashed_password')";
        if (mysqli_query($conn, $query)) {
            $success = "✅ Employee added successfully!";
        } else {
            $error = "⚠ Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #f8f9fa, #e9ecef);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            width: 400px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        h2 {
            color: #2e7d32;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
        }

        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            font-weight: bold;
            color: #555;
        }

        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            margin-top: 5px;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #2e7d32;
            box-shadow: 0px 0px 5px rgba(46, 125, 50, 0.5);
        }

        .btn {
            background: #2e7d32;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            transition: 0.3s;
        }

        .btn:hover {
            background: #1b5e20;
        }

        .message {
            margin-top: 15px;
            font-size: 14px;
            padding: 10px;
            border-radius: 5px;
            display: none;
        }

        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>➕ Add New Employee</h2>

        <?php if ($success) echo "<div class='message success'>$success</div>"; ?>
        <?php if ($error) echo "<div class='message error'>$error</div>"; ?>

        <form method="POST" action="">
            <div class="input-group">
                <label>Employee Code</label>
                <input type="text" name="emp_code" required>
            </div>

            <div class="input-group">
                <label>Name</label>
                <input type="text" name="name" required>
            </div>

            <div class="input-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

            <div class="input-group">
                <label>Phone</label>
                <input type="text" name="phone" required>
            </div>

            <div class="input-group">
                <label>Role</label>
                <select name="role" required>
                    <option value="employee">Employee</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit" class="btn">✔ Add Employee</button>
        </form>
    </div>

    <script>
        setTimeout(() => {
            document.querySelectorAll('.message').forEach(msg => msg.style.display = 'none');
        }, 3000);
    </script>

</body>
</html>