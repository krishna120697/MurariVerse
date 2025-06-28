<?php
// Show any PHP errors for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if username already exists
    $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $_SESSION['msg'] = "Username already exists. Try another.";
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);

        if ($stmt->execute()) {
            $_SESSION['msg'] = "Registration successful! Please login.";
        } else {
            $_SESSION['msg'] = "Error: " . $stmt->error;
        }
    }

    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <h1 class="main-title">Welcome to MurariVerse</h1>
    <h2 class="page-title">Register</h2>
    <form method="post" action="">
        <input type="text" name="username" placeholder="Username" required><br/>
        <input type="email" name="email" placeholder="Email" required><br/>
        <input type="password" name="password" placeholder="Password" required><br/>
        <input type="submit" value="Register">
    </form>
    <a class="bottom-link" href="login.php">Back to Login</a>
</body>
</html>

