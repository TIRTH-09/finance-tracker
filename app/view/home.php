<!DOCTYPE html>
<html>
<head>
    <title>Finance Tracker</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="container">

    <h1>💰 Finance Tracker</h1>

    <div class="total-box">
        Total Expense: <span>₹ <?= $total ?></span>
    </div>

    <form method="POST" action="?action=store" class="expense-form">
        <input type="text" name="title" placeholder="Expense Title" required>
        <input type="number" name="amount" step="0.01" placeholder="Amount" required>
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
        <?php while ($row = $expenses->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= $row['amount'] ?></td>
                <td><?= $row['created_at'] ?></td>
                <td>
                    <a class="btn-edit" href="?action=edit&id=<?= $row['id'] ?>">Edit</a>
                    <a class="btn-delete" href="?action=delete&id=<?= $row['id'] ?>"
                       onclick="return confirm('Delete this expense?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

</div>

</body>
</html>
