<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Transactions - FinanceTrack</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body>

<div id="toast-container"></div>

<div class="app-container">
    <aside class="sidebar">
        <div class="brand"><div class="brand-icon"></div>FinanceTrack</div>
        <nav class="menu" style="flex:1; display:flex; flex-direction:column;">
            <a href="index.php" class="menu-item">
                <ion-icon name="grid-outline" class="menu-icon"></ion-icon>Overview
            </a>
            <a href="index.php?action=transactions" class="menu-item active">
                <ion-icon name="receipt-outline" class="menu-icon"></ion-icon>Transactions
            </a>
            
            <div style="flex-grow:1;"></div>

            <div style="padding-top:20px; border-top:1px solid var(--border-light);">
                <div style="padding: 0 16px 10px 16px; font-size: 0.85rem; color: var(--text-muted);">
                    Logged in as <strong style="color: var(--text-dark);"><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></strong>
                </div>
                <a href="index.php?action=logout" class="menu-item" style="color:var(--danger);">
                    <ion-icon name="log-out-outline" class="menu-icon"></ion-icon>Logout
                </a>
            </div>
        </nav>
    </aside>

    <main class="main-content">
        <header class="top-bar">
            <h2 class="section-title" style="font-size: 1.5rem;">All Transactions</h2>
            <div class="search-bar">
                <ion-icon name="search-outline"></ion-icon>
                <input type="text" id="searchInput" placeholder="Search history...">
            </div>
        </header>

        <div class="card">
            <div class="transaction-list" id="transactionList">
                <?php
                function getCategoryIconTx($cat) {
                    switch($cat) {
                        case 'Food': return 'fast-food-outline';
                        case 'Shopping': return 'bag-handle-outline';
                        case 'Transport': return 'car-sport-outline';
                        case 'Bills': return 'flash-outline';
                        default: return 'card-outline';
                    }
                }
                ?>
                <?php if (empty($expenses)): ?>
                <div class="transaction-empty" id="transactionEmpty" style="text-align: center; padding: 40px; color: var(--text-muted);">
                    <ion-icon name="wallet-outline" style="font-size: 3rem; opacity: 0.5; margin-bottom: 10px;"></ion-icon>
                    <p>No transactions yet. Add one from Overview.</p>
                </div>
                <?php else: ?>
                <?php foreach ($expenses as $row): ?>
                <div class="transaction-item" id="row-<?= $row['id'] ?>">
                    <div class="t-left">
                        <div class="t-icon-bg"><ion-icon name="<?= getCategoryIconTx($row['category']) ?>"></ion-icon></div>
                        <div class="t-info">
                            <div style="display:flex; align-items:center;">
                                <span class="t-title"><?= htmlspecialchars($row['title']) ?></span>
                                <span class="badge"><?= htmlspecialchars($row['category']) ?></span>
                            </div>
                            <span class="t-meta">
                                <?= isset($row['created_at']) ? date('M d, Y h:i A', strtotime($row['created_at'])) : "Recent" ?>
                            </span>
                        </div>
                    </div>
                    <div class="t-right">
                        <span class="t-amount">-₹<?= number_format($row['amount'], 2) ?></span>
                        <div class="t-actions">
                            <a href="#" class="btn-edit-link" data-id="<?= $row['id'] ?>">Edit</a>
                            <a href="#" class="btn-delete-link" data-id="<?= $row['id'] ?>">Delete</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<div class="modal-overlay" id="editModal">
    <div class="modal-content">
        <div class="modal-header" style="display:flex; justify-content:space-between; margin-bottom:20px;">
            <h2 class="section-title">Edit Transaction</h2>
            <button class="close-modal" id="closeModalBtn" style="background:none; border:none; cursor:pointer; font-size:1.5rem;">
                <ion-icon name="close-outline"></ion-icon>
            </button>
        </div>
        <form id="editForm" novalidate>
            <input type="hidden" name="id" id="edit-id">
            <div class="form-group">
                <label class="form-label">Description</label>
                <input type="text" name="title" id="edit-title" class="form-input" required>
                <span class="error-text" role="alert"></span>
            </div>
            <div class="form-group">
                <label class="form-label">Amount</label>
                <input type="number" name="amount" id="edit-amount" step="0.01" min="0.01" class="form-input" required>
                <span class="error-text" role="alert"></span>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label">Category</label>
                <select name="category" id="edit-category" class="form-select">
                    <option value="Food">Food & Drinks</option>
                    <option value="Shopping">Shopping</option>
                    <option value="Transport">Transport</option>
                    <option value="Bills">Bills & Utilities</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <button type="submit" class="btn-primary">Save Changes</button>
        </form>
    </div>
</div>

<script src="js/app.js?v=<?php echo time(); ?>"></script>
</body>
</html>