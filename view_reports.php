<?php
session_start();
require_once('db.inc.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Initialize variables
$incidents = [];
$error_message = '';

try {
    // Get database connection
    $conn = getConnection();
    
    // Simple query to get incident data
    $query = "SELECT * FROM Incident";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $incidents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $error_message = "Database error: " . $e->getMessage();
}

$pageTitle = "View Incident Reports - Police Database";
require_once('header.php');
?>

<div class="tw-container tw-mx-auto tw-px-4 tw-py-8">
    <!-- Header -->
    <div class="tw-flex tw-justify-between tw-items-center tw-mb-8">
        <h1 class="tw-text-2xl tw-font-bold tw-text-gray-900">Incident Reports</h1>
        <a href="dashboard.php" class="tw-text-blue-600 hover:tw-text-blue-700">
            ← Back to Dashboard
        </a>
    </div>

    <?php if ($error_message): ?>
        <div class="tw-bg-red-50 tw-border tw-border-red-200 tw-rounded-md tw-p-4 tw-mb-6 tw-text-red-600">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <?php if (empty($incidents)): ?>
        <div class="tw-bg-gray-50 tw-border tw-border-gray-200 tw-rounded-md tw-p-8 tw-text-center tw-text-gray-500">
            No incident reports found.
        </div>
    <?php else: ?>
        <div class="tw-space-y-6">
            <?php foreach ($incidents as $incident): ?>
                <div class="card">
                    <div class="tw-flex tw-justify-between tw-items-start tw-border-b tw-border-gray-200 tw-pb-4 tw-mb-4">
                        <div class="tw-space-y-1">
                            <div class="tw-text-sm tw-font-medium tw-text-gray-900">
                                Incident ID: <?php echo htmlspecialchars($incident['Incident_ID']); ?>
                            </div>
                            <div class="tw-text-sm tw-text-gray-500">
                                Date: <?php echo htmlspecialchars($incident['Incident_Date']); ?>
                            </div>
                        </div>
                        <?php if ($_SESSION['is_admin']): ?>
                            <a href="edit_report.php?id=<?php echo $incident['Incident_ID']; ?>" 
                               class="tw-text-blue-600 hover:tw-text-blue-700 tw-text-sm">
                                Edit Report
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="tw-prose tw-max-w-none">
                        <h3 class="tw-text-sm tw-font-medium tw-text-gray-900 tw-mb-2">Report:</h3>
                        <p class="tw-text-gray-700 tw-whitespace-pre-wrap">
                            <?php echo nl2br(htmlspecialchars($incident['Incident_Report'])); ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>