<!-- php/login-form.php -->
<main class="legacy-content">
    <h1>Police Traffic Database</h1>
    
    <?php if (isset($message)): ?>
        <p style="color: <?php echo strpos($message, 'Invalid') !== false ? 'red' : 'green'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>
    
    <form method="POST">
        <div>
            <label>Username:</label>
            <input type="text" name="username" required>
        </div>
        <div>
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
</main>