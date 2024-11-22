<?php
require_once('db.inc.php');
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Check if user is admin
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: dashboard.php');
    exit();
}

// Get database connection
$pdo = getConnection();

// Handle form submissions for adding new officers
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_officer'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $credentials = 'officer'; // New officers are always regular officers
        
        $query = "INSERT INTO Officers (username, password, credentials) 
                  VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$username, $password, $credentials]);
        
        $_SESSION['message'] = "Officer added successfully";
        header('Location: manage_officers.php');
        exit();
    }
}

// Get list of all officers
$query = "SELECT * FROM Officers WHERE credentials = 'officer' ORDER BY officer_id";
$officers = $pdo->query($query)->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="mvp.css">
    <title>Police Traffic Database - Manage Officers</title>
    <style>
        body {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }
        .card {
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .search-input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .button {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .back-link {
            text-decoration: none;
            color: #666;
            margin-bottom: 20px;
            display: inline-block;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Manage Officers</h1>
        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert">
            <?php 
            echo $_SESSION['message'];
            unset($_SESSION['message']);
            ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <h2>Add New Officer</h2>
        <form method="POST" action="manage_officers.php">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" class="search-input" required>
            </div>
            
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="search-input" required>
            </div>
            
            <button type="submit" name="add_officer" class="button">Add Officer</button>
        </form>
    </div>

    <div class="card">
        <h2>Current Officers</h2>
        <table>
            <thead>
                <tr>
                    <th>Officer ID</th>
                    <th>Username</th>
                    <th>Credentials</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($officers as $officer): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($officer['officer_id']); ?></td>
                        <td><?php echo htmlspecialchars($officer['username']); ?></td>
                        <td><?php echo htmlspecialchars($officer['credentials']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>