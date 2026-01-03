<!DOCTYPE html>
<html>
<head>
    <title>Finance Tracker</title>
</head>
<body>

<h1>💰 Finance Tracker</h1>

<form method="POST" action="">
    <input type="text" name="title" placeholder="Expense Title" required>
    <input type="number" name="amount" step="0.01" placeholder="Amount" required>
    <button type="submit">Add</button>
</form>

<hr>

<h2>Expense List</h2>

<table border="1" cellpadding="5">
<tr>
    <th>Title</th>
    <th>Amount</th>
    <th>Date</th>
</tr>

<?php while ($row = $expenses->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($row["title"]) ?></td>
    <td><?= $row["amount"] ?></td>
    <td><?= $row["created_at"] ?></td>
</tr>
<?php endwhile; ?>

</table>

</body>
</html>
