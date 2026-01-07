<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Expense - Finance Tracker</title>
    <link rel="stylesheet" href="css/style.css">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
</head>
<body class="edit-container-wrapper">

<div class="card edit-card">
    <div class="section-header">
        <h2 class="section-title">Edit Transaction</h2>
        <a href="index.php" style="color: var(--text-muted); text-decoration: none; display: flex; align-items: center;">
            <ion-icon name="close-outline" style="font-size: 1.5rem;"></ion-icon>
        </a>
    </div>

    <?php if ($expense): ?>
    <form method="POST" action="index.php?action=update">
        <input type="hidden" name="id" value="<?= $expense['id'] ?>">

        <div class="form-group">
            <label class="form-label">Description</label>
            <input type="text" name="title" class="form-input" value="<?= htmlspecialchars($expense['title']) ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label">Amount</label>
            <input type="number" name="amount" step="0.01" class="form-input" value="<?= $expense['amount'] ?>" required>
        </div>

        <div class="form-group" style="margin-bottom: 32px;">
            <label class="form-label">Category</label>
            <select name="category" class="form-select">
                <?php
                $categories = ['Food', 'Shopping', 'Transport', 'Bills', 'Other'];
                foreach ($categories as $cat) {
                    $selected = ($expense['category'] == $cat) ? 'selected' : '';
                    echo "<option value='$cat' $selected>$cat</option>";
                }
                ?>
            </select>
        </div>

        <button type="submit" class="btn-primary">Update Transaction</button>
    </form>
    <?php else: ?>
        <p class="text-danger">Expense not found.</p>
        <a href="index.php" class="btn-primary" style="text-align: center; display: block; text-decoration: none; margin-top: 20px;">Go Back</a>
    <?php endif; ?>
</div>

</body>
</html>