<?php include 'db_helper.php'; 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $users = loadData($userFile);
    foreach ($users as $u) {
        if ($u['username'] == $_POST['user'] && password_verify($_POST['pass'], $u['password'])) {
            $_SESSION['user'] = $u;
            header("Location: " . ($u['role'] == 'admin' ? 'admin.php' : 'user.php'));
            exit;
        }
    }
    $err = "Invalid username or password";
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Specific override for centering the login card */
        body { justify-content: center; align-items: center; }
        .login-card { width: 400px; margin-top: -100px; }
        .login-card h2 { text-align: center; color: var(--dark-sidebar); margin-bottom: 25px; }
        .footer-link { text-align: center; margin-top: 15px; display: block; font-size: 0.9rem; color: #7f8c8d; text-decoration: none; }
        .footer-link:hover { color: var(--accent-blue); }
    </style>
</head>
<body>
    <div class="card login-card">
        <h2>Library Login</h2>
        <?php if(isset($err)) echo "<div class='alert-box' style='border-left: 6px solid #e74c3c; background: #fdeaea; color: #e74c3c;'>$err</div>"; ?>
        
        <form method="POST">
            <label>Username</label>
            <input name="user" placeholder="Enter your username" required style="width: 100%; box-sizing: border-box;">
            
            <label>Password</label>
            <input type="password" name="pass" placeholder="Enter your password" required style="width: 100%; box-sizing: border-box; margin-bottom: 20px;">
            
            <button type="submit" class="btn btn-blue" style="width: 100%; padding: 12px;">Sign In</button>
        </form>
        
        <a href="register.php" class="footer-link">Don't have an account? Create one</a>
    </div>
</body>
</html>