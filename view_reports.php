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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Incident Reports</title>
    <link rel="stylesheet" href="mvp.css">
    <style>
        .report-card {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            background-color: #fff;
        }
        .report-header {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .error {
            color: red;
            padding: 10px;
            background-color: #fee;
            border: 1px solid #fcc;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Incident Reports</h1>
        
        <nav>
            <a href="dashboard.php">Back to Dashboard</a>
        </nav>

        <?php if ($error_message): ?>
            <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <?php if (empty($incidents)): ?>
            <p>No incident reports found.</p>
        <?php else: ?>
            <?php foreach ($incidents as $incident): ?>
                <div class="report-card">
                    <div class="report-header">
                        <div>
                            <strong>Incident ID:</strong> <?php echo htmlspecialchars($incident['Incident_ID']); ?>
                        </div>
                        <div>
                            <strong>Date:</strong> <?php echo htmlspecialchars($incident['Incident_Date']); ?>
                        </div>
                    </div>
                    
                    <div class="report-details">
                        <p><strong>Report:</strong><br>
                            <?php echo nl2br(htmlspecialchars($incident['Incident_Report'])); ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>