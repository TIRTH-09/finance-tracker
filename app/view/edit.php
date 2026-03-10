<!-- ============================================================
     EDIT EXPENSE PAGE — edit.php
     ============================================================
     1. This is a standalone page for editing a single transaction.
     1.1 Shown as a centered card (no sidebar layout).
     1.2 NOTE: The main app uses an AJAX modal for editing instead.
     1.3 This page serves as a fallback/direct link edit option.
     ============================================================ -->
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- 2. HEAD SECTION -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Expense - Finance Tracker</title>
    <!-- 2.1 Stylesheet (no cache-busting here since this is a rarely-used page) -->
    <link rel="stylesheet" href="css/style.css">
    <!-- 2.2 Ionicons for the close button icon -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
</head>
<!-- 3. BODY: Uses a special wrapper class for centered layout -->
<body class="edit-container-wrapper">

<!-- 4. EDIT CARD: Centered form card -->
<div class="card edit-card">
    <!-- 4.1 Header row: title on left, close (X) button on right -->
    <div class="section-header">
        <h2 class="section-title">Edit Transaction</h2>
        <!-- 4.1.1 Close button navigates back to the dashboard -->
        <a href="index.php" style="color: var(--text-muted); text-decoration: none; display: flex; align-items: center;">
            <ion-icon name="close-outline" style="font-size: 1.5rem;"></ion-icon>
        </a>
    </div>

    <!-- 5. CONDITIONAL: Show form only if expense data exists -->
    <?php if ($expense): ?>
    <!-- 5.1 EDIT FORM: Traditional form submission (not AJAX) -->
    <form method="POST" action="index.php?action=update">
        <!-- 5.1.1 Hidden field: the expense ID being edited -->
        <input type="hidden" name="id" value="<?= $expense['id'] ?>">

        <!-- 5.2 DESCRIPTION FIELD: Pre-filled with current value -->
        <div class="form-group">
            <label class="form-label">Description</label>
            <input type="text" name="title" class="form-input" value="<?= htmlspecialchars($expense['title']) ?>" required>
        </div>

        <!-- 5.3 AMOUNT FIELD: Pre-filled with current value -->
        <div class="form-group">
            <label class="form-label">Amount</label>
            <input type="number" name="amount" step="0.01" class="form-input" value="<?= $expense['amount'] ?>" required>
        </div>

        <!-- 5.4 CATEGORY DROPDOWN: Pre-selects the current category -->
        <div class="form-group" style="margin-bottom: 32px;">
            <label class="form-label">Category</label>
            <select name="category" class="form-select">
                <?php
                // 5.4.1 Loop through category options and mark the current one as selected
                $categories = ['Food', 'Shopping', 'Transport', 'Bills', 'Other'];
                foreach ($categories as $cat) {
                    $selected = ($expense['category'] == $cat) ? 'selected' : '';
                    echo "<option value='$cat' $selected>$cat</option>";
                }
                ?>
            </select>
        </div>

        <!-- 5.5 SUBMIT BUTTON -->
        <button type="submit" class="btn-primary">Update Transaction</button>
    </form>

    <!-- 6. ERROR STATE: Shown if the expense ID was invalid or not found -->
    <?php else: ?>
        <p class="text-danger">Expense not found.</p>
        <!-- 6.1 Link back to dashboard -->
        <a href="index.php" class="btn-primary" style="text-align: center; display: block; text-decoration: none; margin-top: 20px;">Go Back</a>
    <?php endif; ?>
</div>

</body>
</html>