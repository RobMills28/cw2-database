<?php
session_start();
require_once('db.inc.php');

// Handle AJAX login requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
              strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $user = checkLogin($username, $password);
    
    if ($user) {
        $_SESSION['user_id'] = $user['officer_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = ($user['credentials'] == 'admin');
        
        if ($isAjax) {
            echo json_encode([
                'success' => true,
                'user' => [
                    'username' => $user['username'],
                    'isAdmin' => ($user['credentials'] == 'admin')
                ]
            ]);
            exit;
        } else {
            header("Location: dashboard.php");
            exit;
        }
    } else {
        if ($isAjax) {
            echo json_encode([
                'success' => false, 
                'message' => 'Invalid username or password'
            ]);
            exit;
        } else {
            $error_message = "Invalid username or password";
        }
    }
}

// Regular PHP session check for non-AJAX requests
if (isset($_SESSION['user_id']) && 
    (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || 
     strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')) {
    header("Location: dashboard.php");
    exit;
}

$pageTitle = "Police Traffic Database - Login";
require_once('header.php');
?>

<div class="tw-min-h-screen tw-bg-gray-50 tw-flex tw-items-center tw-justify-center tw-px-4 tw-py-12">
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

    <!-- Enhanced fallback content -->
    <div id="legacy-content" class="tw-w-full tw-max-w-md" style="display: none;">
        <div class="card">
            <div class="tw-flex tw-justify-center tw-mb-6">
                <div class="tw-bg-blue-50 tw-p-3 tw-rounded-full">
                    <svg class="tw-h-12 tw-w-12 tw-text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>
            
            <h1 class="tw-text-2xl tw-font-bold tw-text-center tw-mb-6">Police Traffic Database</h1>
            
            <?php if (isset($error_message)): ?>
                <div class="tw-bg-red-50 tw-border tw-border-red-200 tw-rounded-md tw-p-4 tw-mb-6 tw-text-red-600">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" class="tw-space-y-4">
                <div>
                    <label for="username" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">
                        Username
                    </label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           required 
                           class="form-input tw-mt-1"
                           value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                </div>
                
                <div>
                    <label for="password" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">
                        Password
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required
                           class="form-input tw-mt-1">
                </div>
                
                <button type="submit" class="btn tw-w-full">
                    Login
                </button>
                
                <div class="tw-bg-gray-50 tw-border tw-rounded-md tw-p-4 tw-mt-6">
                    <p class="tw-text-sm tw-font-medium tw-text-gray-700 tw-text-center">Available accounts:</p>
                    <div class="tw-mt-2 tw-space-y-2">
                        <div class="tw-bg-white tw-p-2 tw-rounded-md tw-border tw-text-sm tw-text-center">
                            Officer: <code class="tw-text-blue-600">mcnulty/plod123</code>
                        </div>
                        <div class="tw-bg-white tw-p-2 tw-rounded-md tw-border tw-text-sm tw-text-center">
                            Officer: <code class="tw-text-blue-600">moreland/fuzz42</code>
                        </div>
                        <div class="tw-bg-white tw-p-2 tw-rounded-md tw-border tw-text-sm tw-text-center">
                            Admin: <code class="tw-text-blue-600">daniels/copper99</code>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="module" src="/cw2/js/index.js"></script>
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