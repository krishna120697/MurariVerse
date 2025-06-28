<?php
session_start();
$conn = new mysqli("localhost", "root", "", "friends");

if (!isset($_SESSION['userid'])) {
    header("Location: ../task3/login.php");
    exit();
}

$currentUserId = $_SESSION['userid'];

$query = "
    SELECT u.id, u.name 
    FROM users u
    INNER JOIN friend_requests f
      ON ((f.sender_id = u.id AND f.receiver_id = $currentUserId)
       OR (f.receiver_id = u.id AND f.sender_id = $currentUserId))
    WHERE f.status = 'accepted' AND u.id != $currentUserId
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Chats</title>
</head>
<body>
    <h2>💬 Your Chats</h2>

    <?php if ($result->num_rows > 0): ?>
        <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li>
                <a href="chat.php?friend_id=<?= $row['id'] ?>">
                    <?= htmlspecialchars($row['name']) ?>
                </a>
            </li>
        <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No friends to chat with yet.</p>
    <?php endif; ?>

    <a href="index.php">← Back to Friend Requests</a>
</body>
</html>
