<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overview - Finance Tracker</title>
    <link rel="stylesheet" href="css/style.css">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body>

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
             <a href="#" class="menu-item" style="opacity: 0.5; cursor: not-allowed;">
                <ion-icon name="pie-chart-outline" class="menu-icon"></ion-icon>
                Budgets (Soon)
            </a>
        </nav>
    </aside>

    <main class="main-content">
        <header class="top-bar">
            <div class="search-bar">
                <ion-icon name="search-outline" style="font-size: 1.2rem;"></ion-icon>
                <input type="text" placeholder="Search transactions...">
            </div>
            
            <div style="display: flex; align-items: center;">
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
                            <input type="number" name="amount" step="0.01" class="form-input" placeholder="0.00" required>
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
            <a href="#" style="color: var(--primary); text-decoration: none; font-weight: 600; font-size: 0.9rem;">View all</a>
        </div>

        <div class="card">
            <?php if (empty($expenses)): ?>
                <p style="color: var(--text-muted); text-align: center; padding: 20px;">No transactions yet.</p>
            <?php else: ?>
                <div class="transaction-list">
                    <?php foreach ($expenses as $row): ?>
                    <div class="transaction-item">
                        <div class="t-left">
                            <div class="t-icon-bg">
                                <ion-icon name="cart-outline"></ion-icon>
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
                                <a href="index.php?action=edit&id=<?= $row['id'] ?>" class="btn-edit-link">Edit</a>
                                <a href="index.php?action=delete&id=<?= $row['id'] ?>" class="btn-delete-link" onclick="return confirm('Delete this expense?')">Delete</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<script src="js/app.js"></script>
</body>
</html>