<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION["userid"])) {
    header("Location: ../task3/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friend Search</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Welcome, Krishna Murari !</h2>
    <a href="chat_list.php" style="padding: 10px 15px; background: #dc2743; color: white; text-decoration: none; border-radius: 5px;">💬 Go to Chat</a>
<br>
<a href="feed.php" style="
    display: inline-block;
    padding: 10px 20px;
    background: #3897f0;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-weight: bold;
    margin-top: 20px;
">➕ Post Something</a>

    <h2>Search Friends</h2>
    <div id="input">
          <i class="fas fa-search"></i>
    <input type="text" id="searchinput" placeholder="Search by name...">
    </div>
    <div id="searchresults"></div>
<script src="search.js"></script>
</body>
</html>