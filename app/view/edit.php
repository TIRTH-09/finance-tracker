<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Expense</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container" style="max-width: 400px; margin-top: 50px;">
    <div class="dashboard-header">
        <h2>Edit Record</h2>
        <a href="index.php" style="color: #64748b; text-decoration: none; font-size: 14px;">Cancel</a>
    </div>
    <div class="card">
        <form action="index.php?action=update" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
            <input type="hidden" name="id" value="<?= $expense['id'] ?>">
            <div class="form-group">
                <label>Description</label>
                <input type="text" name="title" value="<?= htmlspecialchars($expense['title']) ?>" required>
            </div>
            <div class="form-group">
                <label>Amount</label>
                <input type="number" name="amount" step="0.01" value="<?= $expense['amount'] ?>" required>
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="category">
                    <option value="Food" <?= $expense['category'] == 'Food' ? 'selected' : '' ?>>Food</option>
                    <option value="Travel" <?= $expense['category'] == 'Travel' ? 'selected' : '' ?>>Travel</option>
                    <option value="Bills" <?= $expense['category'] == 'Bills' ? 'selected' : '' ?>>Bills</option>
                    <option value="Shopping" <?= $expense['category'] == 'Shopping' ? 'selected' : '' ?>>Shopping</option>
                </select>
            </div>
            <button type="submit" style="width: 100%;">Save Changes</button>
        </form>
    </div>
</div>
</body>
</html>