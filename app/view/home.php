<!DOCTYPE html>
<html>
<head>
    <title>Finance Tracker</title>
    <!-- CSS cache fix explained below -->
    <link rel="stylesheet" href="/finance-tracker/public/css/style.css?v=1">
</head>
<body>

<div class="container">
    <h1>💰 Finance Tracker</h1>

    <div class="total">
        Total Expense: ₹ <?= number_format($total, 2) ?>
    </div>

    <form method="POST" action="">
        <input type="text" name="title" placeholder="Expense Title" required>
        <input type="number" step="0.01" name="amount" placeholder="Amount" required>
        <button type="submit">Add Expense</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Amount (₹)</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

        <!-- ✅ CORRECT LOOP -->
        <?php if (!empty($expenses)): ?>
            <?php foreach ($expenses as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= number_format($row['amount'], 2) ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td>
                        <a class="btn-edit" href="?action=edit&id=<?= $row['id'] ?>">Edit</a>
                        <a class="btn-delete"
                           href="?action=delete&id=<?= $row['id'] ?>"
                           onclick="return confirm('Delete this expense?')">
                           Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No expenses found</td>
            </tr>
        <?php endif; ?>

        </tbody>
    </table>
</div>

</body>
</html>
