<?php
session_start();
include "db.php";

$message = "";
if (isset($_SESSION['msg'])) {
    $message = $_SESSION['msg'];
    unset($_SESSION['msg']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // ✅ Fix: fetch both id and password
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE name = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password); // ✅ bind id also
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION["userid"] = $id; // ✅ this now works
            $_SESSION["msg"] = "🎉 Login successful! Welcome, $username";
            header("Location: ../task4/index.php");
            exit();
        } else {
            $message = "❌ Invalid password.";
        }
    } else {
        $message = "❌ No user found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1 class="main-title">Welcome to MurariVerse</h1>
    <h2 class="page-title">Login</h2>
    <?php if ($message) echo "<p><strong>$message</strong></p>"; ?>

    <form method="post" action="">
        <input type="text" name="username" placeholder="Username" required><br/>
        <input type="password" name="password" placeholder="Password" required><br/>
        <input type="submit" value="Login">
    </form>

    <a class="bottom-link" href="register.php">Click for registration</a>
</body>
</html>
