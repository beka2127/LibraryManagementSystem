<?php 
include 'db_helper.php'; 
checkAuth('admin'); 
$books = loadData($bookFile);

// 1. FIXED ADD LOGIC (Prevents Duplication)
if (isset($_POST['add'])) {
    $books[] = ["id" => uniqid(), "title" => $_POST['title'], "desc" => $_POST['desc'], "status" => "available", "user" => null, "due" => null];
    saveData($bookFile, $books);
    header("Location: admin.php"); // Redirect stops double-submitting
    exit;
}

if (isset($_POST['update_desc'])) {
    foreach($books as &$b) { if($b['id'] == $_POST['book_id']) $b['desc'] = $_POST['new_desc']; }
    saveData($bookFile, $books);
    header("Location: admin.php");
    exit;
}

if (isset($_GET['del'])) {
    unset($books[$_GET['del']]);
    saveData($bookFile, $books);
    header("Location: admin.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><link rel="stylesheet" href="style.css"></head>
<body>
    <div class="sidebar">
        <h2>LMS ADMIN</h2>
        <a href="admin.php" class="active">📚 Books</a>
        <a href="admin_users.php">👥 Users</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <div class="main-content">
        <div class="card">
            <h1>Manage Inventory</h1>
            <form method="POST" style="display:flex; gap:10px;">
                <input name="title" placeholder="Book Title" required style="flex:2; padding:10px;">
                <input name="desc" placeholder="Description" required style="flex:3; padding:10px;">
                <button name="add" class="btn btn-blue">Add Book</button>
            </form>
        </div>

       <div class="card">
    <table>
        <thead>
            <tr>
                <th>Book</th>
                <th>Description</th>
                <th>Loaned To</th> <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($books as $i => $b): ?>
            <tr>
                <td>
                    <strong><?= htmlspecialchars($b['title']) ?></strong><br>
                    <small style="color: <?= ($b['status'] == 'available') ? 'green' : 'orange' ?>;">
                        <?= strtoupper($b['status']) ?>
                    </small>
                </td>
                <td>
                    <form method="POST" style="display:flex; gap:5px;">
                        <input type="hidden" name="book_id" value="<?= $b['id'] ?>">
                        <input name="new_desc" value="<?= htmlspecialchars($b['desc'] ?? '') ?>" style="flex:1; padding:5px; border:1px solid #eee;">
                        <button name="update_desc" class="btn btn-blue" style="padding:5px 10px; font-size:12px;">Update</button>
                    </form>
                </td>
                <td>
                    <?php if ($b['status'] == 'loaned'): ?>
                        <span style="font-weight: bold; color: var(--dark-sidebar);">
                            👤 <?= htmlspecialchars($b['user']) ?>
                        </span>
                        <br><small>Due: <?= $b['due'] ?></small>
                    <?php else: ?>
                        <span style="color: #ccc;">—</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="?del=<?= $i ?>" class="btn btn-red" onclick="return confirm('Delete this book?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>\<div class="card">
    <h3>Urgent Notifications</h3>
    <?php 
    $today = date('Y-m-d');
    $tomorrow = date('Y-m-d', strtotime('+1 day'));
    
    foreach($books as $b): 
        if($b['status'] == 'loaned' && !empty($b['due'])):
            $isOverdue = ($b['due'] < $today);
            $isDueSoon = ($b['due'] == $tomorrow);
            
            if($isOverdue || $isDueSoon):
    ?>
        <div class="alert-msg" style="border-left-color: <?= $isOverdue ? '#e74c3c' : '#f39c12' ?>; background: <?= $isOverdue ? '#fdeaea' : '#fff9eb' ?>;">
            <strong><?= $isOverdue ? '⚠️ OVERDUE:' : '⏳ DUE SOON:' ?></strong> 
            "<?= htmlspecialchars($b['title']) ?>" loaned to <strong><?= htmlspecialchars($b['user']) ?></strong>.
            <br><small>Deadline was: <?= $b['due'] ?></small>
            <a href="admin_users.php?warn_overdue=<?= $b['user'] ?>&book=<?= urlencode($b['title']) ?>" class="btn btn-blue" style="font-size:11px; margin-top:5px; background: #2c3e50;">Send Alert</a>
        </div>
    <?php 
            endif;
        endif; 
    endforeach; 
    ?>
</div>
    </div>
</body>
</html>