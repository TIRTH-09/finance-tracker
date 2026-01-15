<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overview - Finance Tracker</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body>

<div id="toast-container"></div>

<div class="app-container">
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon"></div>
            FinanceTrack
        </div>
        <nav class="menu">
            <a href="index.php" class="menu-item active">
                <ion-icon name="grid-outline" class="menu-icon"></ion-icon>
                Overview
            </a>
            <a href="#" class="menu-item">
                <ion-icon name="receipt-outline" class="menu-icon"></ion-icon>
                Transactions
            </a>
        </nav>
    </aside>

    <main class="main-content">
        <header class="top-bar">
            <div class="search-bar">
                <ion-icon name="search-outline" style="font-size: 1.2rem;"></ion-icon>
                <input type="text" placeholder="Search transactions...">
            </div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <button id="themeToggle" class="theme-toggle" title="Toggle Dark Mode">
                    <ion-icon name="moon-outline"></ion-icon>
                </button>
                <ion-icon name="notifications-outline" style="font-size: 1.5rem; color: var(--text-muted); cursor: pointer;"></ion-icon>
            </div>
        </header>

        <div class="dashboard-grid">
            <div class="card hero-card">
                <span class="hero-label">Total Spending</span>
                <h1 class="hero-amount">₹ <?= number_format($total, 2) ?></h1>
            </div>

            <div class="card">
                 <div class="section-header">
                    <h2 class="section-title">Add New Expense</h2>
                </div>
                <form id="expenseForm">
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <input type="text" name="title" class="form-input" placeholder="e.g. Whole Foods Market" required>
                    </div>
                    <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <label class="form-label">Amount</label>
                            <input type="number" name="amount" step="0.01" min="0.01" class="form-input" placeholder="0.00" required>
                        </div>
                        <div>
                             <label class="form-label">Category</label>
                            <select name="category" class="form-select">
                                <option value="Food">Food & Drinks</option>
                                <option value="Shopping">Shopping</option>
                                <option value="Transport">Transport</option>
                                <option value="Bills">Bills & Utilities</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn-primary">Add Expense</button>
                </form>
            </div>

            <div class="card" style="display: flex; align-items: center; justify-content: center; flex-direction: column; color: var(--text-muted);">
                <ion-icon name="pie-chart" style="font-size: 3rem; opacity: 0.3; margin-bottom: 10px;"></ion-icon>
                <p style="text-align: center; font-weight: 500;">Spending Insights<br>(Coming Soon)</p>
            </div>
        </div>

        <div class="section-header">
            <h2 class="section-title">Recent Transactions</h2>
        </div>

        <div class="card">
            <?php if (empty($expenses)): ?>
                <div style="text-align: center; padding: 40px; color: var(--text-muted);">
                    <ion-icon name="wallet-outline" style="font-size: 3rem; opacity: 0.5; margin-bottom: 10px;"></ion-icon>
                    <p>No transactions yet. Add one above!</p>
                </div>
            <?php else: ?>
                <div class="transaction-list" id="transactionList">
                    <?php 
                    function getCategoryIcon($cat) {
                        switch($cat) {
                            case 'Food': return 'fast-food-outline';
                            case 'Shopping': return 'bag-handle-outline';
                            case 'Transport': return 'car-sport-outline';
                            case 'Bills': return 'flash-outline';
                            default: return 'card-outline';
                        }
                    }
                    ?>
                    <?php foreach ($expenses as $row): ?>
                    <div class="transaction-item" id="row-<?= $row['id'] ?>">
                        <div class="t-left">
                            <div class="t-icon-bg">
                                <ion-icon name="<?= getCategoryIcon($row['category']) ?>"></ion-icon>
                            </div>
                            <div class="t-info">
                                <div style="display: flex; align-items: center;">
                                    <span class="t-title"><?= htmlspecialchars($row['title']) ?></span>
                                    <span class="badge"><?= htmlspecialchars($row['category']) ?></span>
                                </div>
                                <span class="t-meta">Today</span>
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
                </div>
            <?php endif; ?>
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
        <form id="editForm">
            <input type="hidden" name="id" id="edit-id">
            <div class="form-group">
                <label class="form-label">Description</label>
                <input type="text" name="title" id="edit-title" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Amount</label>
                <input type="number" name="amount" id="edit-amount" step="0.01" min="0.01" class="form-input" required>
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