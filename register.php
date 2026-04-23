<?php
require 'lib/library.php';

$conn = new TexterConnection();
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (strlen($username) < 3) {
        $error = "Username must be at least 3 characters";
    } elseif (strlen($password) < 4) {
        $error = "Password must be at least 4 characters";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hash);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit;
        } else {
            $error = "Username already exists";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <form method="POST" class="card">
        <h2>Create Account</h2>

        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Register</button>

        <p><a href="login.php">Already have an account?</a></p>
    </form>
</div>
</body>
</html>
