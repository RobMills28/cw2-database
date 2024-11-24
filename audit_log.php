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

$pageTitle = "Police Traffic Database - Audit Log";
require_once('header.php');
?>

<div class="tw-container tw-mx-auto tw-px-4 tw-py-8">
    <!-- Header -->
    <div class="tw-flex tw-justify-between tw-items-center tw-mb-8">
        <h1 class="tw-text-2xl tw-font-bold tw-text-gray-900">System Audit Log</h1>
        <a href="dashboard.php" class="tw-text-blue-600 hover:tw-text-blue-700 tw-flex tw-items-center">
            <span>← Back to Dashboard</span>
        </a>
    </div>

    <!-- Search and Table Card -->
    <div class="card">
        <div class="tw-mb-6">
            <input type="text" 
                   id="searchInput" 
                   class="form-input"
                   placeholder="Search logs..."
                   onkeyup="filterLogs()">
        </div>

        <div class="tw-overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="tw-whitespace-nowrap">Timestamp</th>
                        <th>Officer</th>
                        <th>Action</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody id="logTableBody">
                    <?php if ($logs): ?>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td class="tw-whitespace-nowrap tw-font-medium">
                                    <?php echo date('Y-m-d H:i:s', strtotime($log['timestamp'])); ?>
                                </td>
                                <td class="tw-font-medium">
                                    <?php echo htmlspecialchars($log['officer_username']); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($log['action']); ?>
                                </td>
                                <td class="tw-max-w-xl">
                                    <?php echo htmlspecialchars($log['details']); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="tw-text-center tw-text-gray-500 tw-py-8">
                                No audit logs found
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
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