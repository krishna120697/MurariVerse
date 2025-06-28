<?php
session_start();
$conn = new mysqli("localhost", "root", "", "friends");

if (!isset($_SESSION['userid'])) {
    header("Location: task3/login.php");
    exit();
}

$currentUserId = $_SESSION['userid'];
$friendId = (int)$_GET['friend_id'];

$friendResult = $conn->query("SELECT name FROM users WHERE id = $friendId");
$friend = $friendResult->fetch_assoc();
$friendName = $friend['name'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Chat with <?= htmlspecialchars($friendName) ?></title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .chat-box { height: 300px; overflow-y: scroll; border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; }
        .message { margin: 5px 0; }
        .self { text-align: right; color: blue; }
        .other { text-align: left; color: green; }
    </style>
</head>
<body>

<h2>Chat with <?= htmlspecialchars($friendName) ?></h2>

<div class="chat-box" id="chatBox">
<?php
$query = "SELECT * FROM messages 
          WHERE (sender_id = $currentUserId AND receiver_id = $friendId)
             OR (sender_id = $friendId AND receiver_id = $currentUserId)
          ORDER BY timestamp ASC";

$result = $conn->query($query);

while ($row = $result->fetch_assoc()) {
    $class = $row['sender_id'] == $currentUserId ? "self" : "other";
    echo "<div class='message $class'>" . htmlspecialchars($row['message']) . "</div>";
}
?>
</div>

<form method="POST" action="send_message.php">
    <input type="hidden" name="friend_id" value="<?= $friendId ?>">
    <input type="text" name="message" required placeholder="Type your message">
    <input type="submit" value="Send">
</form>

</body>
</html>
