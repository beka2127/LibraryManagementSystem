<?php
include 'db_helper.php';
$admin = [
    ["username" => "admin", "password" => password_hash("123", PASSWORD_BCRYPT), "role" => "admin"],
    ["username" => "user1", "password" => password_hash("123", PASSWORD_BCRYPT), "role" => "user"]
];
saveData($userFile, $admin);
echo "Accounts created! Login with 'admin' or 'user1' (Pass: 123)";
?>