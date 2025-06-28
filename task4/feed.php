<?php
session_start();
$conn = new mysqli("localhost", "root", "", "friends");

if (!isset($_SESSION["userid"])) {
    header("Location: ../task3/login.php");
    exit();
}

$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = max(0, ($page - 1) * $limit);

// Fetch paginated posts
$result = $conn->query("SELECT posts.*, users.name FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.created_at DESC LIMIT $limit OFFSET $offset");

// Total post count for pagination
$totalResult = $conn->query("SELECT COUNT(*) as total FROM posts")->fetch_assoc();
$totalPosts = $totalResult["total"];
$totalPages = ceil($totalPosts / $limit);
?>

<!DOCTYPE html>
<html>
<head>
    <title>MurariVerse Feed</title>
</head>
<body>
<h2>Welcome to MurariVerse Feed</h2>

<!-- Post form -->
<form method="POST" action="add_post.php" enctype="multipart/form-data">
    <textarea name="content" rows="3" cols="50" placeholder="What's on your mind?"></textarea><br>
    <input type="file" name="photo" accept="image/*"><br>
    <input type="submit" value="Post">
</form>

<hr>

<!-- Posts -->
<?php while ($row = $result->fetch_assoc()): ?>
    <div style="border:1px solid #ccc; padding:10px; margin:10px 0;">
        <strong><?= htmlspecialchars($row['name']) ?></strong><br>
        <?= nl2br(htmlspecialchars($row['content'])) ?><br>

        <?php if ($row['photo']): ?>
            <img src="<?= htmlspecialchars($row['photo']) ?>" width="300" style="margin-top:10px;"><br>
        <?php endif; ?>

        <small><?= $row['created_at'] ?></small>
    </div>
<?php endwhile; ?>

<!-- Pagination -->
<div>
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=<?= $i ?>" style="margin-right:10px;">Page <?= $i ?></a>
    <?php endfor; ?>
</div>

</body>
</html>
