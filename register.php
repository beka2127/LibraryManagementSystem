<?php include 'db_helper.php'; 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $users = loadData($userFile);
    // Check if user exists
    foreach($users as $u) { if($u['username'] == $_POST['user']) { $err = "Username already taken"; break; } }
    
    if(!isset($err)) {
        $users[] = [
            "username" => $_POST['user'], 
            "password" => password_hash($_POST['pass'], PASSWORD_BCRYPT), 
            "role" => "user"
        ];
        saveData($userFile, $users);
        header("Location: index.php?registered=1");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <style>
        body { justify-content: center; align-items: center; }
        .reg-card { width: 400px; margin-top: -100px; }
        .reg-card h2 { text-align: center; color: var(--dark-sidebar); }
    </style>
</head>
<body>
    <div class="card reg-card">
        <h2>Join the Library</h2>
        <p style="text-align: center; color: #7f8c8d; font-size: 0.9rem; margin-bottom: 25px;">Create an account to start loaning books.</p>
        
        <?php if(isset($err)) echo "<div class='alert-box' style='border-left: 6px solid #e74c3c; background: #fdeaea; color: #e74c3c;'>$err</div>"; ?>

        <form method="POST">
            <label>Choose Username</label>
            <input name="user" placeholder="e.g. Alex24" required style="width: 100%; box-sizing: border-box;">
            
            <label>Create Password</label>
            <input type="password" name="pass" placeholder="At least 6 characters" required style="width: 100%; box-sizing: border-box; margin-bottom: 20px;">
            
            <button type="submit" class="btn btn-blue" style="width: 100%; padding: 12px; background: var(--dark-sidebar);">Register Account</button>
        </form>
        
        <a href="index.php" style="text-align: center; display: block; margin-top: 15px; color: #7f8c8d; text-decoration: none; font-size: 0.9rem;">Back to Login</a>
    </div>
</body>
</html>