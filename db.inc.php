<?php
define('DB_HOST', 'mariadb');
define('DB_NAME', 'cw2');
define('DB_USER', 'root');
define('DB_PASS', 'rootpwd');

function getConnection() {
    try {
        $conn = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
            DB_USER,
            DB_PASS
        );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

function logAction($action, $details = '') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (isset($_SESSION['user_id'])) {
        try {
            $pdo = getConnection();
            // Create audit log table if it doesn't exist
            $pdo->exec("CREATE TABLE IF NOT EXISTS AuditLog (
                id INT AUTO_INCREMENT PRIMARY KEY,
                timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
                officer_id INT,
                action VARCHAR(100),
                details TEXT,
                FOREIGN KEY (officer_id) REFERENCES Officers(officer_id)
            )");
            
            $query = "INSERT INTO AuditLog (officer_id, action, details) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$_SESSION['user_id'], $action, $details]);
        } catch(PDOException $e) {
            error_log("Audit log error: " . $e->getMessage());
        }
    }
}

function executeQuery($query, $params = [], $action = '', $details = '') {
    try {
        $pdo = getConnection();
        $stmt = $pdo->prepare($query);
        $result = $stmt->execute($params);
        
        // If this was a modification query and we have an action name, log it
        if ($action && (stripos($query, 'INSERT') === 0 || 
                       stripos($query, 'UPDATE') === 0 || 
                       stripos($query, 'DELETE') === 0)) {
            logAction($action, $details);
        }
        
        return $stmt;
    } catch(PDOException $e) {
        error_log("Query error: " . $e->getMessage());
        throw $e;
    }
}

function checkLogin($username, $password) {
    try {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT * FROM Officers WHERE username = ? AND password = ?");
        $stmt->execute([$username, $password]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            logAction('Login', "User $username logged in");
        }
        
        return $user;
    } catch(PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}
?>