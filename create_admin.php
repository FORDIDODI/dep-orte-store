<?php
// ============================================
// Alternative: Buat admin via PHP (RECOMMENDED)
// Buat file create_admin.php di root folder
// ============================================
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Admin</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #1a1a1a; color: #fff; }
        .box { background: #2a2a2a; padding: 20px; border-radius: 10px; max-width: 600px; }
        button { background: #6366f1; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #4f46e5; }
        .success { background: #10b981; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .error { background: #ef4444; padding: 10px; border-radius: 5px; margin: 10px 0; }
        input { padding: 10px; width: 100%; margin: 10px 0; background: #1a1a1a; border: 2px solid #444; color: white; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="box">
        <h1>🔧 Create Admin User</h1>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            
            // Connect database
            $db = new mysqli('localhost', 'root', '', 'topup_game');
            
            if ($db->connect_error) {
                echo "<div class='error'>❌ Database connection failed: " . $db->connect_error . "</div>";
            } else {
                // Hash password
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                
                // Delete old admin
                $db->query("DELETE FROM admin_users WHERE username = '$username'");
                
                // Insert new admin
                $sql = "INSERT INTO admin_users (username, password, created_at) VALUES (?, ?, NOW())";
                $stmt = $db->prepare($sql);
                $stmt->bind_param('ss', $username, $hashed);
                
                if ($stmt->execute()) {
                    echo "<div class='success'>✅ Admin user created successfully!</div>";
                    echo "<p><strong>Username:</strong> $username</p>";
                    echo "<p><strong>Password:</strong> $password</p>";
                    echo "<p><strong>Hash:</strong> <code>$hashed</code></p>";
                    echo "<p><a href='/admin/login' style='color: #6366f1;'>→ Go to Admin Login</a></p>";
                } else {
                    echo "<div class='error'>❌ Error: " . $stmt->error . "</div>";
                }
                
                $stmt->close();
                $db->close();
            }
        }
        ?>

        <form method="POST">
            <label>Username:</label>
            <input type="text" name="username" value="admin" required>
            
            <label>Password:</label>
            <input type="text" name="password" value="admin123" required>
            
            <button type="submit">Create Admin User</button>
        </form>

        <hr style="margin: 30px 0; border-color: #444;">

        <h3>📋 Test Password Hash</h3>
        <?php
        $testPassword = 'admin123';
        $testHash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
        
        echo "<p>Testing password: <strong>{$testPassword}</strong></p>";
        echo "<p>Against hash: <code>{$testHash}</code></p>";
        
        if (password_verify($testPassword, $testHash)) {
            echo "<div class='success'>✅ Password verification SUCCESS!</div>";
        } else {
            echo "<div class='error'>❌ Password verification FAILED!</div>";
        }
        ?>
    </div>
</body>
</html>