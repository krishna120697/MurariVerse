<?php
session_start();
$conn = new mysqli("localhost", "root", "", "friends");

if (!isset($_SESSION['userid'])) {
    header("Location: ../task3/login.php");
    exit();
}

$sender_id = $_SESSION['userid'];
$receiver_id = (int)$_POST['friend_id'];
$message = trim($_POST['message']);

if (!empty($message)) {
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $sender_id, $receiver_id, $message);
    $stmt->execute();
}

header("Location:chat.php?friend_id=$receiver_id");
exit();
