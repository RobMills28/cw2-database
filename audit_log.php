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

// Get audit log entries with officer details
$query = "SELECT AuditLog.*, Officers.username as officer_username 
          FROM AuditLog 
          LEFT JOIN Officers ON AuditLog.officer_id = Officers.officer_id 
          ORDER BY timestamp DESC";
$logs = $pdo->query($query)->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="mvp.css">
    <title>Police Traffic Database - Audit Log</title>
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
        .back-link {
            text-decoration: none;
            color: #666;
        }
        .card {
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .search-input {
            width: 100%;
            padding: 8px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background-color: #4a90e2;
            color: white;
            padding: 12px;
            text-align: left;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .timestamp {
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>System Audit Log</h1>
        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
    </div>

    <div class="card">
        <input type="text" 
               id="searchInput" 
               class="search-input" 
               placeholder="Search logs..."
               onkeyup="filterLogs()">

        <table>
            <thead>
                <tr>
                    <th class="timestamp">Timestamp</th>
                    <th>Officer</th>
                    <th>Action</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody id="logTableBody">
                <?php if ($logs): ?>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td class="timestamp">
                                <?php echo date('Y-m-d H:i:s', strtotime($log['timestamp'])); ?>
                            </td>
                            <td><?php echo htmlspecialchars($log['officer_username']); ?></td>
                            <td><?php echo htmlspecialchars($log['action']); ?></td>
                            <td><?php echo htmlspecialchars($log['details']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">No audit logs found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
    function filterLogs() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase();
        const tbody = document.getElementById('logTableBody');
        const rows = tbody.getElementsByTagName('tr');

        for (let row of rows) {
            const text = row.textContent || row.innerText;
            row.style.display = text.toLowerCase().includes(filter) ? '' : 'none';
        }
    }
    </script>
</body>
</html>