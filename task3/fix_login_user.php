<?php
$conn = new mysqli("localhost", "root", "", "friends");

$password = 'test123';
$hash = password_hash($password, PASSWORD_DEFAULT);

// Update ALL users to have this password
$stmt = $conn->prepare("UPDATE users SET `password` = ?");
$stmt->bind_param("s", $hash);
$stmt->execute();

echo "✅ All users now have password: test123";
