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

$pageTitle = "Manage Officers - Police Database";
require_once('header.php');
?>

<div class="tw-container tw-mx-auto tw-px-4 tw-py-8">
    <!-- Header -->
    <div class="tw-flex tw-justify-between tw-items-center tw-mb-8">
        <h1 class="tw-text-2xl tw-font-bold tw-text-gray-900">Manage Officers</h1>
        <a href="dashboard.php" class="tw-text-blue-600 hover:tw-text-blue-700">
            ← Back to Dashboard
        </a>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="tw-bg-green-50 tw-border tw-border-green-200 tw-rounded-md tw-p-4 tw-mb-6 tw-text-green-600">
            <?php 
            echo $_SESSION['message'];
            unset($_SESSION['message']);
            ?>
        </div>
    <?php endif; ?>

    <!-- Add New Officer Form -->
    <div class="card tw-mb-8">
        <h2 class="tw-text-xl tw-font-semibold tw-mb-6">Add New Officer</h2>
        <form method="POST" action="manage_officers.php" class="tw-space-y-4">
            <div>
                <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-2">
                    Username:
                </label>
                <input type="text" id="username" name="username" class="form-input" required>
            </div>
            
            <div>
                <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-2">
                    Password:
                </label>
                <input type="password" id="password" name="password" class="form-input" required>
            </div>
            
            <button type="submit" name="add_officer" class="btn">
                Add Officer
            </button>
        </form>
    </div>

    <!-- Officers List -->
    <div class="card">
        <h2 class="tw-text-xl tw-font-semibold tw-mb-6">Current Officers</h2>
        <div class="tw-overflow-x-auto">
            <table class="data-table">
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
    </div>
</div>

</body>
</html>