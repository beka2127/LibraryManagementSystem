<?php
include 'db_helper.php';
$books = getData('books.json');
$tomorrow = date('Y-m-d', strtotime('+1 day'));

// Adding a book logic
if (isset($_POST['add_book'])) {
    $books[] = ["id" => uniqid(), "title" => $_POST['title'], "status" => "available", "due_date" => null];
    saveData('books.json', $books);
}
?>
<h2>Admin Panel</h2>
<form method="POST">
    <input type="text" name="title" placeholder="Book Title">
    <button name="add_book">Add Book</button>
</form>

<h3>Notifications (Due Tomorrow)</h3>
<?php foreach ($books as $book): ?>
    <?php if ($book['due_date'] == $tomorrow): ?>
        <p>⚠️ <strong><?= $book['title'] ?></strong> is due tomorrow! <button>Send Warning</button></p>
    <?php endif; ?>
<?php endforeach; ?>