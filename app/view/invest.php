<!-- ============================================================
     INVESTMENT SUGGESTIONS PAGE — invest.php
     ============================================================
     1. This page displays curated investment suggestions for the user.
     1.1 Only accessible to logged-in users (checked by HomeController).
     1.2 Shows cards for different investment categories.
     ============================================================ -->
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- 2. HEAD SECTION -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Investment Suggestions - FinanceTrack</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body>

<!-- 3. TOAST NOTIFICATION CONTAINER -->
<div id="toast-container"></div>

<!-- 4. APP LAYOUT CONTAINER -->
<div class="app-container">

    <!-- ============================
         5. SIDEBAR NAVIGATION
         ============================ -->
    <aside class="sidebar">
        <!-- 5.1 Brand / Logo area -->
        <div class="brand">
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
            <!-- 5.2.1 Overview link -->
            <a href="index.php" class="menu-item">
                <ion-icon name="grid-outline" class="menu-icon"></ion-icon>Overview
            </a>
            <!-- 5.2.2 Transactions link -->
            <a href="index.php?action=transactions" class="menu-item">
                <ion-icon name="receipt-outline" class="menu-icon"></ion-icon>Transactions
            </a>
            <!-- 5.2.3 Invest link — marked 'active' since we're on this page -->
            <a href="index.php?action=invest" class="menu-item active">
                <ion-icon name="trending-up-outline" class="menu-icon"></ion-icon>Invest
            </a>

            <!-- 5.2.4 Spacer pushes logout to bottom -->
            <div style="flex-grow:1;"></div>

            <!-- 5.3 User info + logout -->
            <div style="padding-top:20px; border-top:1px solid var(--border-light);">
                <div style="padding: 0 16px 10px 16px; font-size: 0.85rem; color: var(--text-muted);">
                    Logged in as <strong style="color: var(--text-dark);"><?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></strong>
                </div>
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
        <!-- 6.1 TOP BAR -->
        <header class="top-bar">
            <h2 class="section-title" style="font-size: 1.5rem;">Investment Suggestions</h2>
            <div style="display: flex; align-items: center; gap: 10px;">
                <button id="themeToggle" class="theme-toggle" title="Toggle Dark Mode">
                    <ion-icon name="moon-outline"></ion-icon>
                </button>
            </div>
        </header>

        <!-- 6.2 INTRO BANNER -->
        <div class="invest-banner">
            <div class="invest-banner-icon">
                <ion-icon name="bulb-outline"></ion-icon>
            </div>
            <div>
                <h3 class="invest-banner-title">Smart Investment Ideas</h3>
                <p class="invest-banner-text">Based on popular Indian investment options. Explore these avenues to grow your savings. Always consult a financial advisor before investing.</p>
            </div>
        </div>

        <!-- ============================
             7. INVESTMENT SUGGESTION CARDS GRID
             ============================ -->
        <div class="invest-grid">

            <!-- 7.1 Mutual Funds (SIP) -->
            <div class="invest-card">
                <div class="invest-card-header">
                    <div class="invest-card-icon" style="background: linear-gradient(135deg, #818cf8, #4338ca);">
                        <ion-icon name="bar-chart-outline"></ion-icon>
                    </div>
                    <div>
                        <h3 class="invest-card-title">Mutual Funds (SIP)</h3>
                        <span class="invest-risk-badge risk-moderate">Moderate Risk</span>
                    </div>
                </div>
                <p class="invest-card-desc">Start a Systematic Investment Plan with as little as ₹500/month. Great for long-term wealth creation with professional fund management.</p>
                <div class="invest-card-meta">
                    <div class="invest-meta-item">
                        <ion-icon name="trending-up-outline"></ion-icon>
                        <span>10-15% avg. returns</span>
                    </div>
                    <div class="invest-meta-item">
                        <ion-icon name="time-outline"></ion-icon>
                        <span>3-5+ years horizon</span>
                    </div>
                </div>
                <a href="https://www.amfiindia.com/investor-corner/knowledge-center/what-are-mutual-funds" target="_blank" rel="noopener noreferrer" class="invest-card-link">
                    Learn More <ion-icon name="arrow-forward-outline"></ion-icon>
                </a>
            </div>

            <!-- 7.2 Fixed Deposits -->
            <div class="invest-card">
                <div class="invest-card-header">
                    <div class="invest-card-icon" style="background: linear-gradient(135deg, #34d399, #059669);">
                        <ion-icon name="shield-checkmark-outline"></ion-icon>
                    </div>
                    <div>
                        <h3 class="invest-card-title">Fixed Deposits</h3>
                        <span class="invest-risk-badge risk-low">Low Risk</span>
                    </div>
                </div>
                <p class="invest-card-desc">Park your money safely with guaranteed returns. FDs from banks and NBFCs offer stability and predictable income.</p>
                <div class="invest-card-meta">
                    <div class="invest-meta-item">
                        <ion-icon name="trending-up-outline"></ion-icon>
                        <span>6-8% guaranteed</span>
                    </div>
                    <div class="invest-meta-item">
                        <ion-icon name="time-outline"></ion-icon>
                        <span>1-5 years horizon</span>
                    </div>
                </div>
                <a href="https://www.rbi.org.in/commonperson/English/Scripts/FAQs.aspx?Id=711" target="_blank" rel="noopener noreferrer" class="invest-card-link">
                    Learn More <ion-icon name="arrow-forward-outline"></ion-icon>
                </a>
            </div>

            <!-- 7.3 Gold / SGBs -->
            <div class="invest-card">
                <div class="invest-card-header">
                    <div class="invest-card-icon" style="background: linear-gradient(135deg, #fbbf24, #d97706);">
                        <ion-icon name="diamond-outline"></ion-icon>
                    </div>
                    <div>
                        <h3 class="invest-card-title">Gold & SGBs</h3>
                        <span class="invest-risk-badge risk-moderate">Moderate Risk</span>
                    </div>
                </div>
                <p class="invest-card-desc">Sovereign Gold Bonds offer the best way to invest in gold digitally — with 2.5% annual interest plus capital gains. Great hedge against inflation.</p>
                <div class="invest-card-meta">
                    <div class="invest-meta-item">
                        <ion-icon name="trending-up-outline"></ion-icon>
                        <span>8-12% historical</span>
                    </div>
                    <div class="invest-meta-item">
                        <ion-icon name="time-outline"></ion-icon>
                        <span>5-8 years horizon</span>
                    </div>
                </div>
                <a href="https://www.rbi.org.in/Scripts/FAQView.aspx?Id=109" target="_blank" rel="noopener noreferrer" class="invest-card-link">
                    Learn More <ion-icon name="arrow-forward-outline"></ion-icon>
                </a>
            </div>

            <!-- 7.4 PPF -->
            <div class="invest-card">
                <div class="invest-card-header">
                    <div class="invest-card-icon" style="background: linear-gradient(135deg, #60a5fa, #2563eb);">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                    </div>
                    <div>
                        <h3 class="invest-card-title">Public Provident Fund</h3>
                        <span class="invest-risk-badge risk-low">Low Risk</span>
                    </div>
                </div>
                <p class="invest-card-desc">Government-backed savings scheme with tax-free returns under Section 80C. One of the safest long-term investment vehicles in India.</p>
                <div class="invest-card-meta">
                    <div class="invest-meta-item">
                        <ion-icon name="trending-up-outline"></ion-icon>
                        <span>7.1% (current rate)</span>
                    </div>
                    <div class="invest-meta-item">
                        <ion-icon name="time-outline"></ion-icon>
                        <span>15 years lock-in</span>
                    </div>
                </div>
                <a href="https://www.nsiindia.gov.in/InternalPage.aspx?Id_Pk=89" target="_blank" rel="noopener noreferrer" class="invest-card-link">
                    Learn More <ion-icon name="arrow-forward-outline"></ion-icon>
                </a>
            </div>

            <!-- 7.5 Stocks & Index Funds -->
            <div class="invest-card">
                <div class="invest-card-header">
                    <div class="invest-card-icon" style="background: linear-gradient(135deg, #f472b6, #db2777);">
                        <ion-icon name="stats-chart-outline"></ion-icon>
                    </div>
                    <div>
                        <h3 class="invest-card-title">Stocks & Index Funds</h3>
                        <span class="invest-risk-badge risk-high">High Risk</span>
                    </div>
                </div>
                <p class="invest-card-desc">Invest directly in NSE/BSE stocks or via index funds tracking Nifty 50 & Sensex. High growth potential, best for experienced investors.</p>
                <div class="invest-card-meta">
                    <div class="invest-meta-item">
                        <ion-icon name="trending-up-outline"></ion-icon>
                        <span>12-18% potential</span>
                    </div>
                    <div class="invest-meta-item">
                        <ion-icon name="time-outline"></ion-icon>
                        <span>5+ years horizon</span>
                    </div>
                </div>
                <a href="https://www.nseindia.com/learn/content/beginners-module" target="_blank" rel="noopener noreferrer" class="invest-card-link">
                    Learn More <ion-icon name="arrow-forward-outline"></ion-icon>
                </a>
            </div>

            <!-- 7.6 NPS -->
            <div class="invest-card">
                <div class="invest-card-header">
                    <div class="invest-card-icon" style="background: linear-gradient(135deg, #a78bfa, #7c3aed);">
                        <ion-icon name="umbrella-outline"></ion-icon>
                    </div>
                    <div>
                        <h3 class="invest-card-title">National Pension System</h3>
                        <span class="invest-risk-badge risk-low">Low Risk</span>
                    </div>
                </div>
                <p class="invest-card-desc">Government-backed retirement plan with extra ₹50,000 tax deduction under 80CCD(1B). Mix of equity, debt & govt securities for steady growth.</p>
                <div class="invest-card-meta">
                    <div class="invest-meta-item">
                        <ion-icon name="trending-up-outline"></ion-icon>
                        <span>9-12% historical</span>
                    </div>
                    <div class="invest-meta-item">
                        <ion-icon name="time-outline"></ion-icon>
                        <span>Till retirement</span>
                    </div>
                </div>
                <a href="https://www.npscra.nsdl.co.in/nps-702.php" target="_blank" rel="noopener noreferrer" class="invest-card-link">
                    Learn More <ion-icon name="arrow-forward-outline"></ion-icon>
                </a>
            </div>

        </div>

        <!-- 7.7 DISCLAIMER -->
        <div class="invest-disclaimer">
            <ion-icon name="information-circle-outline"></ion-icon>
            <p>These are general suggestions for informational purposes only. Past returns do not guarantee future performance. Please consult a certified financial advisor before making investment decisions.</p>
        </div>

    </main>
</div>

<!-- 8. JAVASCRIPT -->
<script src="js/app.js?v=<?php echo time(); ?>"></script>
</body>
</html>
