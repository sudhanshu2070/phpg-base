<?php
session_start();
require 'inc/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Log the received username and password (for debugging only, remove in production)
    error_log("Login attempt - Username: " . $username . ", Password: " . $password);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Log the fetched user data (for debugging only, remove in production)
    error_log("Fetched user: " . print_r($user, true));

    // Compare plain text password directly
    if ($user && $password === $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: chat.php?user_id=2"); // Redirect to chat
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body { font-family: Arial; background: #f2f2f2; padding: 50px; }
        form { background: white; padding: 20px; width: 300px; margin: auto; border-radius: 5px; }
        input { display: block; width: 100%; margin-bottom: 10px; padding: 8px; }
        .error { color: red; text-align: center; }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Login</h2>
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <input type="text" name="username" placeholder="Username" required />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit">Login</button>
    </form>
</body>
</html>
