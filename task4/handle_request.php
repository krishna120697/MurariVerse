<?php
session_start();
$conn = new mysqli("localhost", "root", "", "friends");
$currentuserid = $_SESSION["userid"];


if (isset($_POST['friend_id']) && isset($_POST['action'])) {
    $friend_id = (int)$_POST['friend_id'];
    $action = $_POST['action'];

    if (!in_array($action, ['accept', 'reject', 'block'])) {
        echo "Invalid action.";
        exit;
    }

    if ($action == 'accept') {
        $query = "
            UPDATE friend_requests 
            SET status = 'accepted' 
            WHERE (sender_id = $friend_id AND receiver_id = $currentuserid)
               OR (sender_id = $currentuserid AND receiver_id = $friend_id)";
    } elseif ($action == 'reject') {
        $query = "
            DELETE FROM friend_requests 
            WHERE (sender_id = $friend_id AND receiver_id = $currentuserid)
               OR (sender_id = $currentuserid AND receiver_id = $friend_id)";
    } elseif ($action == 'block') {
        $check = $conn->query("
            SELECT * FROM friend_requests 
            WHERE (sender_id = $currentuserid AND receiver_id = $friend_id)
               OR (sender_id = $friend_id AND receiver_id = $currentuserid)
        ");

        if ($check->num_rows > 0) {
            $query = "
                UPDATE friend_requests 
                SET status = 'blocked' 
                WHERE (sender_id = $friend_id AND receiver_id = $currentuserid)
                   OR (sender_id = $currentuserid AND receiver_id = $friend_id)";
        } else {
            $query = "
                INSERT INTO friend_requests (sender_id, receiver_id, status)
                VALUES ($currentuserid, $friend_id, 'blocked')";
        }
    }

    if ($conn->query($query)) {
        echo ucfirst($action) . " successful.";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Missing data.";
}
?>
