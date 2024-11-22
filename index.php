<?php
session_start();
require_once('db.inc.php');

// Check if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $user = checkLogin($username, $password);
    if ($user) {
        $_SESSION['user_id'] = $user['officer_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = ($user['credentials'] == 'admin');
        header('Location: dashboard.php');
        exit();
    } else {
        $message = "Invalid login";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="mvp.css">
    <title>Police Traffic Database</title>
</head>
<body>
    <main>
        <h1>Police Traffic Database</h1>
        
        <?php if ($message): ?>
            <p style="color: <?php echo strpos($message, 'Invalid') !== false ? 'red' : 'green'; ?>">
                <?php echo $message; ?>
            </p>
        <?php endif; ?>
        
        <form method="POST">
            <div>
                <label>Username:</label>
                <input type="text" name="username" required>
            </div>
            <div>
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </main>
</body>
</html>