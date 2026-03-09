<?php
include 'db_helper.php';
if (!isset($_SESSION['user'])) header("Location: index.php");

$role = $_SESSION['user']['role'];
echo "<h1>Welcome, " . $_SESSION['user']['username'] . "</h1>";

if ($role == 'admin') {
    echo '<a href="admin_manage.php">Manage Books & Loans</a>';
} else {
    echo '<a href="user_view.php">Search & Loan Books</a>';
}
?>
<br><a href="logout.php">Logout</a>