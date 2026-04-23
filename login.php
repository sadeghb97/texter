<?php
require 'lib/library.php';

$conn = new TexterConnection();
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$user = $result->fetch_assoc()) {
        $error = "Username not found";
    } elseif (!password_verify($password, $user['password'])) {
        $error = "Wrong password";
    } else {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $username;

        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <form method="POST" class="card">
        <h2>Login</h2>

        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Login</button>

        <p><a href="register.php">Create account</a></p>
    </form>
</div>
</body>
</html>
