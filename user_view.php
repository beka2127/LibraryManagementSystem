<?php
include 'db_helper.php';
$books = getData('books.json');

if (isset($_GET['loan'])) {
    foreach ($books as &$book) {
        if ($book['id'] == $_GET['loan']) {
            $book['status'] = 'loaned';
            $book['due_date'] = date('Y-m-d', strtotime('+14 days'));
        }
    }
    saveData('books.json', $books);
}
?>
<h2>Available Books</h2>
<?php foreach ($books as $book): ?>
    <?php if ($book['status'] == 'available'): ?>
        <p><?= $book['title'] ?> <a href="?loan=<?= $book['id'] ?>">Loan Book</a></p>
    <?php endif; ?>
<?php endforeach; ?>