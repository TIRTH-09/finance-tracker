<!-- ============================================================
     HOME PAGE (Overview Dashboard) — home.php
     ============================================================
     1. This is the main dashboard view for the Finance Tracker app.
     1.1 It shows total spending, an expense form, and recent transactions.
     1.2 Only accessible to logged-in users (checked by HomeController).
     ============================================================ -->
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- 2. HEAD SECTION: Meta tags, page title, stylesheet, and icon library -->
    <!-- 2.1 Set character encoding to UTF-8 for special characters (₹, etc.) -->
    <meta charset="UTF-8">
    <!-- 2.2 Make the page responsive on mobile devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- 2.3 Page title shown in the browser tab -->
    <title>Overview - Finance Tracker</title>
    <!-- 2.4 Link the main stylesheet with cache-busting timestamp -->
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <!-- 2.5 Load Ionicons icon library (used for menu icons, action buttons, etc.) -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body>

<!-- 3. TOAST NOTIFICATION CONTAINER -->
<!-- 3.1 Empty container where JavaScript injects success/error toast messages -->
<div id="toast-container"></div>

<!-- 4. APP LAYOUT CONTAINER -->
<!-- 4.1 CSS Grid: sidebar on the left, main content on the right -->
<div class="app-container">

    <!-- ============================
         5. SIDEBAR NAVIGATION
         ============================ -->
    <aside class="sidebar">
        <!-- 5.1 Brand / Logo area at top of sidebar -->
        <div class="brand">
            <!-- 5.1.1 Inline SVG logo: a wallet icon with indigo gradient -->
            <svg class="brand-logo" width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <!-- 5.1.2 Define a gradient that goes from light indigo to dark indigo -->
                    <linearGradient id="logoGrad" x1="0" y1="0" x2="28" y2="28" gradientUnits="userSpaceOnUse">
                        <stop offset="0%" stop-color="#818cf8"/>
                        <stop offset="100%" stop-color="#4338ca"/>
                    </linearGradient>
                </defs>
                <!-- 5.1.3 Rounded rectangle background -->
                <rect width="28" height="28" rx="7" fill="url(#logoGrad)"/>
                <!-- 5.1.4 White "₹" rupee symbol inside the logo -->
                <text x="14" y="20" text-anchor="middle" fill="white" font-family="Inter, Arial, sans-serif" font-size="16" font-weight="700">₹</text>
            </svg>
            <!-- 5.1.5 App name displayed next to the logo -->
            FinanceTrack
        </div>

        <!-- 5.2 Navigation menu links -->
        <nav class="menu" style="flex: 1; display: flex; flex-direction: column;">
            <!-- 5.2.1 "Overview" link — marked as 'active' since we're on the home page -->
            <a href="index.php" class="menu-item active">
                <ion-icon name="grid-outline" class="menu-icon"></ion-icon>
                Overview
            </a>
            <!-- 5.2.2 "Transactions" link — navigates to the transactions page -->
            <a href="index.php?action=transactions" class="menu-item">
                <ion-icon name="receipt-outline" class="menu-icon"></ion-icon>
                Transactions
            </a>
            
            <!-- 5.2.3 Spacer pushes the logout section to the bottom -->
            <div style="flex-grow: 1;"></div>

            <!-- 5.3 User info and logout section at the bottom of sidebar -->
            <div style="padding-top: 20px; border-top: 1px solid var(--border-light);">
                <!-- 5.3.1 Display the logged-in username from session -->
                <div style="padding: 0 16px 10px 16px; font-size: 0.85rem; color: var(--text-muted);">
                    Logged in as <strong><?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></strong>
                </div>
                <!-- 5.3.2 Logout button — triggers the logout action -->
                <a href="index.php?action=logout" class="menu-item" style="color: var(--danger);">
                    <ion-icon name="log-out-outline" class="menu-icon"></ion-icon>
                    Logout
                </a>
            </div>
        </nav>
    </aside>

    <!-- ============================
         6. MAIN CONTENT AREA
         ============================ -->
    <main class="main-content">

        <!-- 6.1 TOP BAR: Theme toggle -->
        <header class="top-bar">
            <div></div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <button id="themeToggle" class="theme-toggle" title="Toggle Dark Mode">
                    <ion-icon name="moon-outline"></ion-icon>
                </button>
            </div>
        </header>

        <!-- ============================
             7. DASHBOARD GRID
             7.1 Uses CSS Grid: hero card spans full width,
                  expense form and insights card sit side by side.
             ============================ -->
        <div class="dashboard-grid">

            <!-- 7.2 HERO CARD: Shows net balance with income/expense breakdown -->
            <div class="card hero-card">
                <span class="hero-label">Net Balance</span>
                <h1 class="hero-amount" style="color: <?= $netBalance >= 0 ? '#16a34a' : '#ef4444' ?>">₹ <?= number_format(abs($netBalance), 2) ?></h1>
                <div class="hero-subtotals">
                    <div class="hero-subtotal">
                        <ion-icon name="arrow-up-circle" style="color: #16a34a; font-size: 1.2rem;"></ion-icon>
                        <div>
                            <span class="subtotal-label">Income</span>
                            <span class="subtotal-amount income" id="heroIncome">₹ <?= number_format($incomeTotal, 2) ?></span>
                        </div>
                    </div>
                    <div class="hero-subtotal">
                        <ion-icon name="arrow-down-circle" style="color: #ef4444; font-size: 1.2rem;"></ion-icon>
                        <div>
                            <span class="subtotal-label">Expenses</span>
                            <span class="subtotal-amount expense" id="heroExpense">₹ <?= number_format($expenseTotal, 2) ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================
                 7.3 ADD NEW TRANSACTION FORM CARD
                 ============================ -->
            <div class="card">
                 <div class="section-header">
                    <h2 class="section-title" id="formTitle">Add New Expense</h2>
                </div>
                <!-- 7.3.1 Toggle buttons for expense/income -->
                <div class="type-toggle" style="margin-bottom: 20px;">
                    <button type="button" class="type-btn active" data-type="expense" id="btnTypeExpense">
                        <ion-icon name="arrow-down-circle-outline"></ion-icon> Expense
                    </button>
                    <button type="button" class="type-btn" data-type="income" id="btnTypeIncome">
                        <ion-icon name="arrow-up-circle-outline"></ion-icon> Income
                    </button>
                </div>
                <form id="expenseForm" novalidate>
                    <!-- Hidden field for type -->
                    <input type="hidden" name="type" id="transactionType" value="expense">
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <input type="text" name="title" id="title" class="form-input" placeholder="e.g. Whole Foods Market" required>
                        <span class="error-text" role="alert"></span>
                    </div>
                    <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div class="form-group">
                            <label class="form-label">Amount</label>
                            <input type="number" name="amount" id="amount" step="0.01" min="0.01" class="form-input" placeholder="0.00" required>
                            <span class="error-text" role="alert"></span>
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
                    <button type="submit" class="btn-primary" id="submitBtn">Add Expense</button>
                </form>
            </div>

            <!-- 7.4 SPENDING INSIGHTS PLACEHOLDER CARD -->
            <!-- 7.4.1 This card is a placeholder for future chart/analytics features -->
            <div class="card" style="display: flex; align-items: center; justify-content: center; flex-direction: column; color: var(--text-muted);">
                <ion-icon name="pie-chart" style="font-size: 3rem; opacity: 0.3; margin-bottom: 10px;"></ion-icon>
                <p style="text-align: center; font-weight: 500;">Spending Insights<br>(Coming Soon)</p>
            </div>
        </div>

        <!-- ============================
             8. RECENT TRANSACTIONS LIST
             ============================ -->
        <div class="section-header">
            <h2 class="section-title">Recent Transactions</h2>
        </div>

       <!-- 8.1 Card containing the transaction list -->
       <div class="card">
                <!-- 8.1.1 Transaction list container — also used by JS to inject new rows -->
                <div class="transaction-list" id="transactionList">
            <!-- 8.2 Check if there are any expenses to display -->
            <?php if (empty($expenses)): ?>
                <!-- 8.2.1 Empty state: shown when no transactions exist yet -->
                <div class="transaction-empty" id="transactionEmpty" style="text-align: center; padding: 40px; color: var(--text-muted);">
                    <ion-icon name="wallet-outline" style="font-size: 3rem; opacity: 0.5; margin-bottom: 10px;"></ion-icon>
                    <p>No transactions yet. Add one above!</p>
                </div>
            <?php else:
                    // 8.3 Helper function: maps category name to an Ionicon icon name
                    // 8.3.1 'Food' → fast-food-outline, 'Shopping' → bag-handle-outline, etc.
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
                    <!-- 8.4 Loop through each expense and render a transaction row -->
                    <?php foreach ($expenses as $row): ?>
                    <div class="transaction-item" id="row-<?= $row['id'] ?>">
                        <!-- 8.4.1 Left side: icon, title, badge, and date -->
                        <div class="t-left">
                            <!-- 8.4.2 Category icon inside a rounded container -->
                            <div class="t-icon-bg">
                                <ion-icon name="<?= getCategoryIcon($row['category']) ?>"></ion-icon>
                            </div>
                            <div class="t-info">
                                <div style="display: flex; align-items: center;">
                                    <!-- 8.4.3 Transaction title (XSS-safe via htmlspecialchars) -->
                                    <span class="t-title"><?= htmlspecialchars($row['title']) ?></span>
                                    <!-- 8.4.4 Category badge -->
                                    <span class="badge"><?= htmlspecialchars($row['category']) ?></span>
                                </div>
                                <!-- 8.4.5 Date/time of the transaction -->
                                <span class="t-meta">
                                    <?php 
                                        // 8.4.6 Format the creation timestamp, or show "Recent" as fallback
                                        if(isset($row['created_at'])) {
                                            echo date('M d, h:i A', strtotime($row['created_at']));
                                        } else {
                                            echo "Recent";
                                        }
                                    ?>
                                </span>
                            </div>
                        </div>
                        <!-- 8.4.7 Right side: amount and action buttons -->
                        <div class="t-right">
                            <!-- 8.4.8 Amount with color coding: green for income, red for expense -->
                            <?php $isIncome = (($row['type'] ?? 'expense') === 'income'); ?>
                            <span class="t-amount <?= $isIncome ? 'income' : 'expense' ?>"><?= $isIncome ? '+' : '-' ?>₹<?= number_format($row['amount'], 2) ?></span>
                            <!-- 8.4.9 Edit and Delete action links — handled by JS via AJAX -->
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

<!-- ============================
     9. EDIT TRANSACTION MODAL
     9.1 Hidden by default. Shown when user clicks "Edit" on a transaction.
     9.2 Uses an overlay + centered card pattern.
     ============================ -->
<div class="modal-overlay" id="editModal">
    <div class="modal-content">
        <!-- 9.3 Modal header with title and close button -->
        <div class="modal-header" style="display:flex; justify-content:space-between; margin-bottom:20px;">
            <h2 class="section-title">Edit Transaction</h2>
            <button class="close-modal" id="closeModalBtn" style="background:none; border:none; cursor:pointer; font-size:1.5rem;">
                <ion-icon name="close-outline"></ion-icon>
            </button>
        </div>
        <!-- 9.4 Edit form — submitted via AJAX (app.js handles this) -->
        <form id="editForm" novalidate>
            <input type="hidden" name="id" id="edit-id">
            <input type="hidden" name="type" id="edit-type" value="expense">
            <!-- Type toggle for edit modal -->
            <div class="type-toggle" style="margin-bottom: 16px;">
                <button type="button" class="type-btn active" data-type="expense" id="editBtnTypeExpense">
                    <ion-icon name="arrow-down-circle-outline"></ion-icon> Expense
                </button>
                <button type="button" class="type-btn" data-type="income" id="editBtnTypeIncome">
                    <ion-icon name="arrow-up-circle-outline"></ion-icon> Income
                </button>
            </div>
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

<!-- 10. JAVASCRIPT -->
<!-- 10.1 Load app.js with cache-busting timestamp — handles all AJAX, validation, and UI logic -->
<script src="js/app.js?v=<?php echo time(); ?>"></script>
</body>
</html>