<!DOCTYPE html>
<html>
<head>
    <title>Edit Expense</title>
    <link rel="stylesheet" href="/finance-tracker/public/css/style.css?v=2">
</head>
<body>

<div class="edit-wrapper">
    <div class="edit-card">
        <h2>Edit Expense</h2>

        <form method="POST" action="?action=update">
            <input type="hidden" name="id" value="<?= $expense['id'] ?>">

            <input
                type="text"
                name="title"
                value="<?= htmlspecialchars($expense['title']) ?>"
                placeholder="Expense Title"
                required
            >

            <input
                type="number"
                step="0.01"
                name="amount"
                value="<?= $expense['amount'] ?>"
                placeholder="Amount"
                required
            >

            <div class="edit-actions">
                <button type="submit">Update</button>
                <a href="/finance-tracker/public/">Cancel</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
