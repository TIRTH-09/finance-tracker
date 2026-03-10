<!-- ============================================================
     TRANSACTIONS PAGE — transactions.php
     ============================================================
     1. This page displays ALL transactions with search/filter.
     1.1 Only accessible to logged-in users (checked by HomeController).
     1.2 Includes inline edit modal and AJAX delete functionality.
     ============================================================ -->
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- 2. HEAD SECTION -->
    <!-- 2.1 Character encoding for special symbols like ₹ -->
    <meta charset="UTF-8">
    <!-- 2.2 Responsive viewport for mobile devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- 2.3 Page title for browser tab -->
    <title>All Transactions - FinanceTrack</title>
    <!-- 2.4 Main stylesheet with cache-busting -->
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <!-- 2.5 Ionicons icon library for UI icons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body>

<!-- 3. TOAST NOTIFICATION CONTAINER -->
<!-- 3.1 JS injects success/error toasts into this container -->
<div id="toast-container"></div>

<!-- 4. APP LAYOUT CONTAINER (CSS Grid: sidebar + main) -->
<div class="app-container">

    <!-- ============================
         5. SIDEBAR NAVIGATION
         ============================ -->
    <aside class="sidebar">
        <!-- 5.1 Brand / Logo area -->
        <div class="brand">
            <!-- 5.1.1 Inline SVG logo: ₹ symbol on indigo gradient (same as home.php) -->
            <svg class="brand-logo" width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="logoGrad" x1="0" y1="0" x2="28" y2="28" gradientUnits="userSpaceOnUse">
                        <stop offset="0%" stop-color="#818cf8"/>
                        <stop offset="100%" stop-color="#4338ca"/>
                    </linearGradient>
                </defs>
                <rect width="28" height="28" rx="7" fill="url(#logoGrad)"/>
                <text x="14" y="20" text-anchor="middle" fill="white" font-family="Inter, Arial, sans-serif" font-size="16" font-weight="700">₹</text>
            </svg>
            FinanceTrack
        </div>

        <!-- 5.2 Navigation menu -->
        <nav class="menu" style="flex:1; display:flex; flex-direction:column;">
            <!-- 5.2.1 Overview link (not active on this page) -->
            <a href="index.php" class="menu-item">
                <ion-icon name="grid-outline" class="menu-icon"></ion-icon>Overview
            </a>
            <!-- 5.2.2 Transactions link — marked 'active' since we're on this page -->
            <a href="index.php?action=transactions" class="menu-item active">
                <ion-icon name="receipt-outline" class="menu-icon"></ion-icon>Transactions
            </a>
            
            <!-- 5.2.3 Spacer pushes logout to bottom -->
            <div style="flex-grow:1;"></div>

            <!-- 5.3 User info + logout -->
            <div style="padding-top:20px; border-top:1px solid var(--border-light);">
                <!-- 5.3.1 Show logged-in username -->
                <div style="padding: 0 16px 10px 16px; font-size: 0.85rem; color: var(--text-muted);">
                    Logged in as <strong style="color: var(--text-dark);"><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></strong>
                </div>
                <!-- 5.3.2 Logout action link -->
                <a href="index.php?action=logout" class="menu-item" style="color:var(--danger);">
                    <ion-icon name="log-out-outline" class="menu-icon"></ion-icon>Logout
                </a>
            </div>
        </nav>
    </aside>

    <!-- ============================
         6. MAIN CONTENT AREA
         ============================ -->
    <main class="main-content">
        <!-- 6.1 TOP BAR: page title and search bar -->
        <header class="top-bar">
            <!-- 6.1.1 Page heading -->
            <h2 class="section-title" style="font-size: 1.5rem;">All Transactions</h2>
            <!-- 6.1.2 Search bar — filters transactions in real-time via JS -->
            <div class="search-bar">
                <ion-icon name="search-outline"></ion-icon>
                <input type="text" id="searchInput" placeholder="Search history...">
            </div>
        </header>

        <!-- ============================
             7. TRANSACTIONS LIST CARD
             ============================ -->
        <div class="card">
            <div class="transaction-list" id="transactionList">
                <?php
                // 7.1 Helper function: maps a category string to its Ionicon name
                // 7.1.1 Used to display the correct icon for each transaction
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

                <!-- 7.2 Check if there are any expenses to display -->
                <?php if (empty($expenses)): ?>
                <!-- 7.2.1 Empty state when no transactions exist -->
                <div class="transaction-empty" id="transactionEmpty" style="text-align: center; padding: 40px; color: var(--text-muted);">
                    <ion-icon name="wallet-outline" style="font-size: 3rem; opacity: 0.5; margin-bottom: 10px;"></ion-icon>
                    <p>No transactions yet. Add one from Overview.</p>
                </div>
                <?php else: ?>

                <!-- 7.3 Loop through each expense and render a transaction row -->
                <?php foreach ($expenses as $row): ?>
                <div class="transaction-item" id="row-<?= $row['id'] ?>">
                    <!-- 7.3.1 Left side: category icon, title, badge, date -->
                    <div class="t-left">
                        <div class="t-icon-bg"><ion-icon name="<?= getCategoryIconTx($row['category']) ?>"></ion-icon></div>
                        <div class="t-info">
                            <div style="display:flex; align-items:center;">
                                <!-- 7.3.2 Transaction title (XSS-protected) -->
                                <span class="t-title"><?= htmlspecialchars($row['title']) ?></span>
                                <!-- 7.3.3 Category badge -->
                                <span class="badge"><?= htmlspecialchars($row['category']) ?></span>
                            </div>
                            <!-- 7.3.4 Transaction date — formatted or "Recent" fallback -->
                            <span class="t-meta">
                                <?= isset($row['created_at']) ? date('M d, Y h:i A', strtotime($row['created_at'])) : "Recent" ?>
                            </span>
                        </div>
                    </div>
                    <!-- 7.3.5 Right side: amount and actions -->
                    <div class="t-right">
                        <?php $isIncome = (($row['type'] ?? 'expense') === 'income'); ?>
                        <span class="t-amount <?= $isIncome ? 'income' : 'expense' ?>"><?= $isIncome ? '+' : '-' ?>₹<?= number_format($row['amount'], 2) ?></span>
                        <!-- 7.3.6 Edit/Delete links — handled via AJAX in app.js -->
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
     8. EDIT TRANSACTION MODAL
     8.1 Hidden overlay shown when "Edit" is clicked
     ============================ -->
<div class="modal-overlay" id="editModal">
    <div class="modal-content">
        <!-- 8.2 Modal header with close button -->
        <div class="modal-header" style="display:flex; justify-content:space-between; margin-bottom:20px;">
            <h2 class="section-title">Edit Transaction</h2>
            <button class="close-modal" id="closeModalBtn" style="background:none; border:none; cursor:pointer; font-size:1.5rem;">
                <ion-icon name="close-outline"></ion-icon>
            </button>
        </div>
        <!-- 8.3 Edit form — AJAX submitted by app.js -->
        <form id="editForm" novalidate>
            <!-- 8.3.1 Hidden ID field -->
            <input type="hidden" name="id" id="edit-id">
            <!-- 8.3.2 Description -->
            <div class="form-group">
                <label class="form-label">Description</label>
                <input type="text" name="title" id="edit-title" class="form-input" required>
                <span class="error-text" role="alert"></span>
            </div>
            <!-- 8.3.3 Amount -->
            <div class="form-group">
                <label class="form-label">Amount</label>
                <input type="number" name="amount" id="edit-amount" step="0.01" min="0.01" class="form-input" required>
                <span class="error-text" role="alert"></span>
            </div>
            <!-- 8.3.4 Category -->
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
            <!-- 8.3.5 Save button -->
            <button type="submit" class="btn-primary">Save Changes</button>
        </form>
    </div>
</div>

<!-- 9. JAVASCRIPT -->
<!-- 9.1 Load app.js — handles search filtering, AJAX edit/delete, dark mode, toasts -->
<script src="js/app.js?v=<?php echo time(); ?>"></script>
</body>
</html>