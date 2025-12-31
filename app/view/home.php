<!DOCTYPE html>
<html>
<head>
    <title>Finance Tracker</title>
</head>
<body>

<h1>💰 Finance Tracker</h1>

<form method="POST" action="?action=add">
    <input type="text" name="title" placeholder="Expense title" required>
    <input type="number" step="0.01" name="amount" placeholder="Amount" required>
    <button type="submit">Add Expense</button>
</form>

<hr>

<h3>Expense List</h3>
<ul>
<?php while ($row = $expenses->fetch_assoc()) : ?>
    <li>
        <?php echo $row['title']; ?> - ₹<?php echo $row['amount']; ?>
    </li>
<?php endwhile; ?>
</ul>

</body>
</html>
