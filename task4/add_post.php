<?php
session_start();
$conn = new mysqli("localhost", "root", "", "friends");

if (!isset($_SESSION["userid"])) {
    die("Not logged in.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userId = $_SESSION["userid"];
    $content = trim($_POST["content"]);
    $photoPath = null;

    // Handle file upload
    if (!empty($_FILES["photo"]["name"])) {
        $filename = time() . "_" . basename($_FILES["photo"]["name"]);
        $targetPath = "uploads/" . $filename;

        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetPath)) {
            $photoPath = $targetPath;
        }
    }

    // Insert if there's content or a photo
    if ($content !== "" || $photoPath !== null) {
        $stmt = $conn->prepare("INSERT INTO posts (user_id, content, photo) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $userId, $content, $photoPath);
        $stmt->execute();
        header("Location: feed.php");
        exit();
    } else {
        echo "Empty post not allowed.";
    }
}
?>