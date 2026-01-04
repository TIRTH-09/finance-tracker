<!DOCTYPE html>
<html>
<head>
    <title>Edit Expense</title>
    <link rel="stylesheet" href="/finance-tracker/public/css/style.css">
</head>
<body>

<h2>Edit Expense</h2>

<form method="POST" action="?action=update">
    <input type="hidden" name="id" value="<?= $expense['id'] ?>">

    <input type="text" name="title"
           value="<?= htmlspecialchars($expense['title']) ?>" required>

    <input type="number" step="0.01" name="amount"
           value="<?= $expense['amount'] ?>" required>

    <button type="submit">Update</button>
    <a href="/finance-tracker/public/">Cancel</a>
</form>

</body>
</html>
