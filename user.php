<?php 
include 'db_helper.php'; 
checkAuth('user'); 
$books = loadData($bookFile);
$warnings = loadData($warningFile);
$me = $_SESSION['user']['username'];

// FIXED LOAN LOGIC
if (isset($_GET['loan'])) {
    foreach($books as &$b) {
        if($b['id'] == $_GET['loan'] && $b['status'] == 'available') {
            $b['status'] = 'loaned'; 
            $b['user'] = $me; 
            $b['due'] = date('Y-m-d', strtotime('+7 days'));
            break; // Stop after finding the specific book
        }
    }
    saveData($bookFile, $books);
    header("Location: user.php");
    exit;
}

if (isset($_GET['ret'])) {
    foreach($books as &$b) {
        if($b['id'] == $_GET['ret'] && $b['user'] == $me) {
            $b['status'] = 'available'; 
            $b['user'] = null; 
            $b['due'] = null;
            break;
        }
    }
    saveData($bookFile, $books);
    header("Location: user.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><link rel="stylesheet" href="style.css"></head>
<body>
    <div class="sidebar">
        <h2>MY LIBRARY</h2>
        <a href="user.php" class="active">📖 Catalog</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

   <div class="main-content">
    <?php 
    $today = date('Y-m-d');
    $hasOverdue = false;

    // Check for overdue books to show a priority warning
    foreach($books as $b) {
        if($b['user'] == $me && !empty($b['due']) && $b['due'] < $today) {
            $hasOverdue = true;
            break;
        }
    }

    if($hasOverdue): ?>
        <div class="alert-msg" style="background: #e74c3c; color: white; border: none;">
            <strong>🚨 ATTENTION:</strong> You have one or more overdue books! Please return them immediately to avoid penalties.
        </div>
    <?php endif; ?>

    <?php foreach($warnings as $w): if($w['to'] == $me): ?>
        <div class="alert-msg">⚠️ <strong>MESSAGE FROM ADMIN:</strong> <?= htmlspecialchars($w['msg']) ?></div>
    <?php endif; endforeach; ?>

    <div class="card">
        <h2>Your Loans</h2>
        <table>
            <?php foreach($books as $b): if($b['user'] == $me): 
                $overdue = (!empty($b['due']) && $b['due'] < $today);
            ?>
            <tr style="<?= $overdue ? 'background: #fff5f5;' : '' ?>">
                <td>
                    <strong><?= htmlspecialchars($b['title']) ?></strong><br>
                    <small style="color: <?= $overdue ? '#e74c3c' : '#7f8c8d' ?>; font-weight: <?= $overdue ? 'bold' : 'normal' ?>;">
                        Due: <?= $b['due'] ?> <?= $overdue ? '(OVERDUE)' : '' ?>
                    </small>
                </td>
                <td><a href="?ret=<?= $b['id'] ?>" class="btn btn-red">Return</a></td>
            </tr>
            <?php endif; endforeach; ?>
        </table>
    </div>
    
    

        <div class="card">
           <div class="card">
    <h2>Available Books</h2>
    <form method="GET" style="display:flex; gap:10px; margin-bottom: 20px;">
        <input name="q" placeholder="Search by book title..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" style="flex:1; padding:10px; border:1px solid #ddd; border-radius:5px;">
        <button type="submit" class="btn btn-blue">Search</button>
        <?php if(isset($_GET['q'])): ?>
            <a href="user.php" class="btn btn-red" style="padding-top:10px;">Clear</a>
        <?php endif; ?>
    </form>

    <table>
        <tr><th>Title</th><th>Description</th><th>Action</th></tr>
        <?php 
        $search = $_GET['q'] ?? '';
        foreach($books as $b): 
            if($b['status'] == 'available' && (empty($search) || stripos($b['title'], $search) !== false)): 
        ?>
        <tr>
            <td><strong><?= htmlspecialchars($b['title']) ?></strong></td>
            <td><?= htmlspecialchars($b['desc'] ?? 'No description.') ?></td>
            <td><a href="?loan=<?= $b['id'] ?>" class="btn btn-blue">Loan</a></td>
        </tr>
        <?php endif; endforeach; ?>
    </table>
</div>
</div>
    </div>
</body>
</html>