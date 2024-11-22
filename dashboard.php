<?php
session_start();
require_once('db.inc.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="mvp.css">
    <title>Police Traffic Database - Dashboard</title>
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
        .header h1 {
            margin: 0;
        }
        .logout {
            text-decoration: none;
            color: purple;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        .card {
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .card h3 {
            margin-top: 0;
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
            width: 100%;
            box-sizing: border-box;
            text-align: center;
        }
        .button:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Police Traffic Database</h1>
        <a href="logout.php" class="logout">Logout</a>
    </div>

    <div class="grid">
        <div class="card">
            <h3>Search People</h3>
            <form action="search_people.php" method="GET">
                <input type="text" 
                       name="search" 
                       class="search-input" 
                       placeholder="Name or License Number">
                <input type="submit" value="Search" class="button">
            </form>
        </div>

        <div class="card">
            <h3>Search Vehicles</h3>
            <form action="search_vehicles.php" method="GET">
                <input type="text" 
                       name="search" 
                       class="search-input" 
                       placeholder="Registration Number">
                <input type="submit" value="Search" class="button">
            </form>
        </div>

        <div class="card">
            <h3>Incident Reports</h3>
            <a href="file_report.php" class="button">File New Report</a>
            <a href="view_reports.php" class="button">View Reports</a>
        </div>

        <?php if ($_SESSION['is_admin'] ?? false): ?>
        <div class="card">
            <h3>Admin Functions</h3>
            <a href="manage_officers.php" class="button">Manage Officers</a>
            <a href="audit_log.php" class="button">View Audit Log</a>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>