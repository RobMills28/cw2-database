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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Police Traffic Database</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="mvp.css">
    <style>
        /* Fallback styles with improved design */
        .legacy-content {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .legacy-content form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .error-message {
            color: #dc2626;
            background: #fee2e2;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .login-help {
            margin-top: 20px;
            padding: 15px;
            background: #f3f4f6;
            border-radius: 4px;
        }
        .login-help ul {
            margin-top: 10px;
            list-style: none;
            padding-left: 0;
        }
        .login-help li {
            margin: 5px 0;
            font-family: monospace;
        }
    </style>
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
        <div class="flex items-center justify-center mb-4">
            <!-- Shield icon as SVG -->
            <svg class="h-12 w-12 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016zM12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        
        <h1 class="text-2xl font-bold text-center mb-6">Police Traffic Database</h1>
        
        <?php if (isset($error_message)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="" class="space-y-4">
            <div>
                <label for="username" class="text-sm font-medium">Username:</label>
                <input type="text" id="username" name="username" required 
                       class="w-full px-3 py-2 border rounded-md mt-1"
                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
            </div>
            
            <div>
                <label for="password" class="text-sm font-medium">Password:</label>
                <input type="password" id="password" name="password" required
                       class="w-full px-3 py-2 border rounded-md mt-1">
            </div>
            
            <button type="submit" 
                    class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition-colors">
                Login
            </button>
            
            <div class="login-help mt-4 text-center text-sm text-gray-600">
                <p class="font-medium">Available accounts:</p>
                <ul class="mt-2 space-y-1">
                    <li>Officer: <code class="text-blue-600">mcnulty/plod123</code></li>
                    <li>Officer: <code class="text-blue-600">moreland/fuzz42</code></li>
                    <li>Admin: <code class="text-blue-600">daniels/copper99</code></li>
                </ul>
            </div>
        </form>
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