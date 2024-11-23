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
<html lang="en">
<head>
    <title>Police Traffic Database - Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="mvp.css">
</head>
<body class="bg-gray-50">
    <!-- React root -->
    <div id="root"></div>

    <!-- PHP Session Data -->
    <script>
        window.USER_DATA = <?php echo json_encode([
            'userId' => $_SESSION['user_id'] ?? null,
            'username' => $_SESSION['username'] ?? null,
            'isAdmin' => $_SESSION['is_admin'] ?? false,
        ]); ?>;
    </script>

    <!-- React Build -->
    <script type="module" src="js/index.js"></script>

    <!-- Enhanced fallback content -->
    <div id="legacy-content" style="display: none;">
        <div class="header">
            <h1>Police Traffic Database</h1>
            <div>
                Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>
                <a href="logout.php" class="logout">Logout</a>
            </div>
        </div>

        <div class="grid">
            <div class="card">
                <h3>Search People</h3>
                <form action="search_people.php" method="GET">
                    <input type="text" name="search" class="search-input" 
                           placeholder="Name or License Number">
                    <button type="submit" class="button">Search</button>
                </form>
            </div>

            <div class="card">
                <h3>Search Vehicles</h3>
                <form action="search_vehicles.php" method="GET">
                    <input type="text" name="search" class="search-input" 
                           placeholder="Registration Number">
                    <button type="submit" class="button">Search</button>
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
    </div>

    <script>
        // Show fallback content if React fails to load
        window.addEventListener('load', function() {
            setTimeout(function() {
                if (!document.querySelector('#root').children.length) {
                    document.querySelector('#legacy-content').style.display = 'block';
                }
            }, 1000);
        });
    </script>
</body>
</html>