<?php
require 'lib/library.php';
requireLogin();
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Home</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div class="card">
        <h1 class="big"><?= $_SESSION['username'] ?></h1>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>
</div>
</body>
</html>
