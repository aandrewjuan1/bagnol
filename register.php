<?php
session_start();
require_once 'dbConfig.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if the username already exists
    $checkSql = "SELECT * FROM Users WHERE username = :username";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([':username' => $username]);
    $existingUser = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        echo "Username already exists. Please choose a different one.";
    } else {
        $sql = "INSERT INTO Users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute([':username' => $username, ':email' => $email, ':password' => $password])) {
            echo "Registration successful!";
            header("Location: login.php");
            exit();
        } else {
            echo "An error occurred. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }
        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 320px;
            text-align: center;
        }
        .form-container h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .form-container input[type="text"],
        .form-container input[type="email"],
        .form-container input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .form-container button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 12px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .form-container button:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: red;
            margin-bottom: 10px;
        }
        .form-container a {
            display: block;
            margin-top: 10px;
            color: #007BFF;
            text-decoration: none;
        }
        .form-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Register</h2>
        <form action="register.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" required><br>

            <label for="email">Email:</label>
            <input type="email" name="email" required><br>

            <label for="password">Password:</label>
            <input type="password" name="password" required><br>

            <button type="submit">Register</button>
        </form>
        <a href="login.php">Already have an account? Login here</a>
    </div>
</body>
</html>
