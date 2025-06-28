<?php
session_start();
$conn = new mysqli("localhost", "root", "", "friends");

if (!isset($_SESSION['userid'])) {
    echo "❌ Not logged in.";
    exit();
}

$currentUserId = $_SESSION['userid'];

if (isset($_POST['receiver_id'])) {
    $receiverId = (int)$_POST['receiver_id'];

    // Debug: log values
    file_put_contents("debug.log", "Sender: $currentUserId, Receiver: $receiverId\n", FILE_APPEND);

    // Check if request already sent
    $check = $conn->prepare("SELECT id FROM friend_requests WHERE sender_id = ? AND receiver_id = ?");
    $check->bind_param("ii", $currentUserId, $receiverId);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "❗ Request already sent.";
    } else {
        $stmt = $conn->prepare("INSERT INTO friend_requests (sender_id, receiver_id, status) VALUES (?, ?, 'pending')");
        $stmt->bind_param("ii", $currentUserId, $receiverId);
        $stmt->execute();
        echo "✅ Request sent.";
    }
}
?>

