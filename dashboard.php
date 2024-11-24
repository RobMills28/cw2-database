<?php
session_start();
require_once('db.inc.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$pageTitle = "Police Traffic Database - Dashboard";
require_once('header.php');
?>

<div class="tw-min-h-screen tw-bg-gray-50">
    <!-- React root element -->
    <div id="dashboard-root"></div>

    <!-- Pass PHP session data to React -->
    <script>
        window.USER_DATA = <?php echo json_encode([
            'userId' => $_SESSION['user_id'] ?? null,
            'username' => $_SESSION['username'] ?? null,
            'isAdmin' => $_SESSION['is_admin'] ?? false,
        ]); ?>;
    </script>

    <!-- Legacy fallback content -->
    <div id="legacy-content" class="tw-p-6 tw-max-w-7xl tw-mx-auto" style="display: none;">
        <!-- Header Section -->
        <div class="tw-flex tw-justify-between tw-items-center tw-mb-8">
            <div class="tw-flex tw-items-center tw-space-x-3">
                <svg class="tw-h-8 tw-w-8 tw-text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <h1 class="tw-text-2xl tw-font-bold tw-text-gray-900">Police Traffic Database</h1>
            </div>
            <div class="tw-flex tw-items-center tw-space-x-4">
                <span class="tw-text-gray-600">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="logout.php" class="btn">Logout</a>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-3 tw-gap-6">
            <!-- Search People -->
            <div class="card">
                <h2 class="tw-text-xl tw-font-semibold tw-text-gray-900 tw-mb-4">Search People</h2>
                <form action="search_people.php" method="GET" class="tw-space-y-4">
                    <input type="text" 
                           name="search" 
                           placeholder="Name or License Number"
                           class="form-input">
                    <button type="submit" class="btn tw-w-full">
                        Search
                    </button>
                </form>
            </div>

            <!-- Search Vehicles -->
            <div class="card">
                <h2 class="tw-text-xl tw-font-semibold tw-text-gray-900 tw-mb-4">Search Vehicles</h2>
                <form action="search_vehicles.php" method="GET" class="tw-space-y-4">
                    <input type="text" 
                           name="search" 
                           placeholder="Registration Number"
                           class="form-input">
                    <button type="submit" class="btn tw-w-full">
                        Search
                    </button>
                </form>
            </div>

            <!-- Incident Reports -->
            <div class="card">
                <h2 class="tw-text-xl tw-font-semibold tw-text-gray-900 tw-mb-4">Incident Reports</h2>
                <div class="tw-space-y-4">
                    <a href="file_report.php" class="btn tw-w-full tw-block tw-text-center">
                        File New Report
                    </a>
                    <a href="view_reports.php" class="tw-w-full tw-block tw-text-center tw-bg-blue-500 tw-text-white tw-px-4 tw-py-2 tw-rounded-md hover:tw-bg-blue-600 tw-transition-colors">
                        View Reports
                    </a>
                </div>
            </div>

            <?php if ($_SESSION['is_admin'] ?? false): ?>
            <!-- Admin Functions -->
            <div class="card">
                <h2 class="tw-text-xl tw-font-semibold tw-text-gray-900 tw-mb-4">Admin Functions</h2>
                <div class="tw-space-y-4">
                    <a href="manage_officers.php" class="tw-w-full tw-block tw-text-center tw-bg-gray-600 tw-text-white tw-px-4 tw-py-2 tw-rounded-md hover:tw-bg-gray-700 tw-transition-colors">
                        Manage Officers
                    </a>
                    <a href="audit_log.php" class="tw-w-full tw-block tw-text-center tw-bg-gray-600 tw-text-white tw-px-4 tw-py-2 tw-rounded-md hover:tw-bg-gray-700 tw-transition-colors">
                        View Audit Log
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Include React build -->
    <script type="module" src="js/index.js"></script>

    <script>
        // Show fallback content if React fails to load
        window.addEventListener('load', function() {
            setTimeout(function() {
                if (!document.querySelector('#dashboard-root').children.length) {
                    document.querySelector('#legacy-content').style.display = 'block';
                }
            }, 1000);
        });
    </script>
</div>
</body>
</html>