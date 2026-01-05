<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Finance Tracker</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <div class="dashboard-header">
        <h1>Overview</h1>
        <div class="card" style="padding: 10px 20px;">
            <p style="margin: 0; font-size: 12px; color: #64748b;">Total Spent</p>
            <h2 style="margin: 0; color: var(--primary);">₹<?= number_format($total, 2) ?></h2>
        </div>
    </div>

    <div class="card" style="margin-bottom: 24px;">
        <form id="expenseForm" class="grid-form">
            <div class="form-group">
                <label>Description</label>
                <input type="text" name="title" placeholder="e.g. Coffee" required>
            </div>
            <div class="form-group">
                <label>Amount</label>
                <input type="number" name="amount" step="0.01" placeholder="0.00" required>
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="category">
                    <option value="Food">Food</option>
                    <option value="Travel">Travel</option>
                    <option value="Bills">Bills</option>
                    <option value="Shopping">Shopping</option>
                </select>
            </div>
            <button type="submit">Add</button>
        </form>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr><th>Description</th><th>Category</th><th>Amount</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($expenses as $row): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($row['title']) ?></strong></td>
                    <td><span class="badge"><?= $row['category'] ?></span></td>
                    <td>₹<?= number_format($row['amount'], 2) ?></td>
                    <td>
                        <a href="index.php?action=edit&id=<?= $row['id'] ?>" style="color: var(--primary); text-decoration: none;">Edit</a> |
                        <a href="index.php?action=delete&id=<?= $row['id'] ?>" style="color: #ef4444; text-decoration: none;" onclick="return confirm('Delete?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="js/app.js"></script>
</body>
</html>