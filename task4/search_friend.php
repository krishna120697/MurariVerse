<?php
session_start();

if (!isset($_SESSION["userid"])) {
    echo "User not logged in.";
    exit();
}

$currentuserid = $_SESSION["userid"];
$conn = new mysqli("localhost", "root", "", "friends");


if(isset($_POST['search']))
{
    $search=$conn->real_escape_string($_POST['search']);
    $query="
    SELECT * from users WHERE name LIKE '%$search%' AND
    id!=$currentuserid AND id NOT IN(
    SELECT receiver_id from friend_requests WHERE
    sender_id=$currentuserid AND status='blocked' UNION
    SELECT sender_id from friend_requests WHERE
    receiver_id=$currentuserid AND status='blocked')
    ";
    $result=$conn->query($query);
    while($row=$result->fetch_assoc())
    {
      $friendId = $row['id'];
      $photo=$row['photo'];

$statusQuery = "
  SELECT sender_id, receiver_id, status 
  FROM friend_requests 
  WHERE (sender_id = $currentuserid AND receiver_id = $friendId) 
     OR (sender_id = $friendId AND receiver_id = $currentuserid)
";

$statusResult = $conn->query($statusQuery);

if ($statusResult->num_rows) {
    $statusRow = $statusResult->fetch_assoc();
    $status = $statusRow['status'];
    $senderId = $statusRow['sender_id'];
    $receiverId = $statusRow['receiver_id'];
} else {
    $status = 'none';
}

if ($status === 'pending' && $receiverId == $currentuserid) {
    echo "<div id='friend-{$friendId}' class='display'>
    <img src='$photo' alt='{$row['name']}' class='friend-photo'>
            <strong>{$row['name']}</strong> - Request Pending
            <button onclick=\"handleRequest($friendId, 'accept')\" class='btn'>Accept</button>
            <button onclick=\"handleRequest($friendId, 'reject')\" class='btn'>Reject</button>
            <button onclick=\"handleRequest($friendId, 'block')\" class='btn'>Block</button>
          </div>";
} else {
    echo "<div id='friend-{$friendId}' class='display'>
    <img src='$photo' alt='{$row['name']}' class='friend-photo'>
        <strong>{$row['name']}</strong> - Status: $status";

if ($status === 'none' || $status === 'rejected') {
    echo "<button onclick=\"sendRequest($friendId)\" class='btn'>Send Request</button>";
} elseif ($status === 'pending') {
    echo "<button class='btn' disabled>Pending</button>";
} elseif ($status === 'accepted') {
    echo "<a href='chat.php?friend_id=$friendId' class='btn'>Message</a>";
} elseif ($status === 'blocked') {
    echo "<button class='btn' disabled>Blocked</button>";
}

echo "</div>";
}
    }
  }
  ?>