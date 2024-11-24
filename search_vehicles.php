<?php
session_start();
require_once('db.inc.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$results = [];
$message = '';

if (isset($_GET['search']) && !empty($_GET['search'])) {
    try {
        $conn = getConnection();
        $stmt = $conn->prepare("
            SELECT v.*, p.People_name as owner_name, p.People_licence
            FROM Vehicle v
            LEFT JOIN Ownership o ON v.Vehicle_ID = o.Vehicle_ID
            LEFT JOIN People p ON o.People_ID = p.People_ID
            WHERE v.Vehicle_plate LIKE ?
        ");
        $searchTerm = "%" . $_GET['search'] . "%";
        $stmt->execute([$searchTerm]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($results)) {
            $message = "No vehicles found matching your search.";
        }
    } catch(PDOException $e) {
        $message = "Error performing search: " . $e->getMessage();
    }
}

$pageTitle = "Search Vehicles - Police Database";
require_once('header.php');
?>

<div class="tw-container tw-mx-auto tw-px-4 tw-py-8">
    <!-- Header -->
    <div class="tw-flex tw-justify-between tw-items-center tw-mb-8">
        <h1 class="tw-text-2xl tw-font-bold tw-text-gray-900">Vehicle Search</h1>
        <div class="tw-space-x-4">
            <a href="dashboard.php" class="tw-text-blue-600 hover:tw-text-blue-700">Dashboard</a>
            <a href="logout.php" class="tw-text-blue-600 hover:tw-text-blue-700">Logout</a>
        </div>
    </div>

    <!-- Search Form -->
    <div class="card tw-mb-8">
        <form method="GET" class="tw-space-y-4">
            <div>
                <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-2">
                    Registration Number:
                </label>
                <div class="tw-flex tw-gap-4">
                    <input type="text" 
                           name="search" 
                           value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>"
                           placeholder="Enter registration number"
                           class="form-input">
                    <button type="submit" class="btn">
                        Search
                    </button>
                </div>
            </div>
        </form>
    </div>

    <?php if ($message): ?>
        <div class="tw-bg-blue-50 tw-border tw-border-blue-200 tw-rounded-md tw-p-4 tw-mb-6 tw-text-blue-600">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($results)): ?>
        <div class="tw-overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Registration</th>
                        <th>Type</th>
                        <th>Color</th>
                        <th>Owner</th>
                        <th>Owner License</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $vehicle): ?>
                        <tr>
                            <td class="tw-font-medium"><?php echo htmlspecialchars($vehicle['Vehicle_plate']); ?></td>
                            <td><?php echo htmlspecialchars($vehicle['Vehicle_type']); ?></td>
                            <td><?php echo htmlspecialchars($vehicle['Vehicle_colour']); ?></td>
                            <td><?php echo htmlspecialchars($vehicle['owner_name'] ?? 'Unknown'); ?></td>
                            <td class="tw-font-mono"><?php echo htmlspecialchars($vehicle['People_licence'] ?? 'N/A'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>