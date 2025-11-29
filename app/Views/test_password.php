<?php

// ============================================
// STEP 1: Test Password Hash
// Buat file test_password.php di root folder
// ============================================
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Password</title>
</head>
<body>
    <h1>Password Hash Test</h1>
    <?php
    $password = 'admin123';
    $hash_from_db = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
    
    echo "<h3>Testing Password Verification:</h3>";
    echo "Password: <strong>" . $password . "</strong><br>";
    echo "Hash: <code>" . $hash_from_db . "</code><br><br>";
    
    if (password_verify($password, $hash_from_db)) {
        echo "✅ <span style='color: green; font-weight: bold;'>Password BENAR!</span>";
    } else {
        echo "❌ <span style='color: red; font-weight: bold;'>Password SALAH!</span>";
    }
    
    echo "<hr>";
    echo "<h3>Generate New Hash:</h3>";
    $new_hash = password_hash('admin123', PASSWORD_DEFAULT);
    echo "New hash for 'admin123':<br>";
    echo "<code>" . $new_hash . "</code><br><br>";
    echo "Copy hash ini dan update di database jika perlu.";
    ?>
</body>
</html>