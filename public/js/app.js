/**
 * ============================================================
 * MAIN APPLICATION JAVASCRIPT — app.js
 * ============================================================
 * 1. This file handles ALL client-side logic for the Finance Tracker:
 *    1.1 Form validation (show/clear errors with shake animation)
 *    1.2 Toast notification system (success/error messages)
 *    1.3 Dark mode toggle with localStorage persistence
 *    1.4 AJAX expense management (add, delete, edit via fetch API)
 *    1.5 Edit modal (open, populate, submit)
 *    1.6 Search bar filtering on the transactions page
 *    1.7 Login form client-side validation
 * ============================================================
 */

// ===================================================================
// 2. GLOBAL VALIDATION HELPERS
// 2.1 These functions are defined globally (outside DOMContentLoaded)
//     so they can be used by both the main app and the login form.
// ===================================================================

/**
 * 2.2 showError(input, message)
 * 2.2.1 Adds red border, shake animation, and error text to an input field.
 * 2.2.2 Works with both direct inputs and icon-wrapped inputs (.input-icon-wrap).
 */
function showError(input, message) {
    if (!input) return;

    // 2.2.3 Add error styling to the input itself
    input.classList.add("input-error", "shake");

    // 2.2.4 Also style the icon wrapper if present (login/register pages)
    var wrapper = input.closest(".input-icon-wrap");
    if (wrapper) wrapper.classList.add("input-error", "shake");

    // 2.2.5 Find and display the error text element below the input
    var formGroup = input.closest(".form-group");
    var errorEl = formGroup ? formGroup.querySelector(".error-text") : null;
    if (errorEl) {
        errorEl.textContent = message;
        errorEl.classList.add("visible");
        errorEl.style.display = "block";
    }

    // 2.2.6 Remove shake animation after 500ms (it's a one-shot effect)
    setTimeout(function () {
        input.classList.remove("shake");
        if (wrapper) wrapper.classList.remove("shake");
    }, 500);
}

/**
 * 2.3 clearError(input)
 * 2.3.1 Removes all error styling and hides the error text.
 */
function clearError(input) {
    if (!input) return;

    // 2.3.2 Remove error classes from input and wrapper
    input.classList.remove("input-error");
    var wrapper = input.closest(".input-icon-wrap");
    if (wrapper) wrapper.classList.remove("input-error", "shake");

    // 2.3.3 Hide the error text element
    var formGroup = input.closest(".form-group");
    var errorEl = formGroup ? formGroup.querySelector(".error-text") : null;
    if (errorEl) {
        errorEl.textContent = "";
        errorEl.classList.remove("visible");
        errorEl.style.display = "none";
    }
}

// ===================================================================
// 3. MAIN APPLICATION LOGIC (runs after DOM is fully loaded)
// ===================================================================
document.addEventListener("DOMContentLoaded", function () {

    // ---------------------------------------------------------------
    // 3.1 TOTAL DISPLAY UPDATER
    // 3.1.1 Updates the hero card with net balance, income, and expense totals.
    // ---------------------------------------------------------------
    function updateTotalDisplay(totals) {
        const heroAmount = document.querySelector(".hero-amount");
        const heroIncome = document.getElementById("heroIncome");
        const heroExpense = document.getElementById("heroExpense");
        
        if (heroAmount && totals) {
            let net = parseFloat(totals.netBalance);
            if (isNaN(net)) net = 0;
            const formatted = Math.abs(net).toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            heroAmount.innerText = "₹ " + formatted;
            heroAmount.style.color = net >= 0 ? '#16a34a' : '#ef4444';
        }
        if (heroIncome && totals) {
            let inc = parseFloat(totals.incomeTotal);
            if (isNaN(inc)) inc = 0;
            heroIncome.innerText = "₹ " + inc.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
        if (heroExpense && totals) {
            let exp = parseFloat(totals.expenseTotal);
            if (isNaN(exp)) exp = 0;
            heroExpense.innerText = "₹ " + exp.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    }

    // ---------------------------------------------------------------
    // 3.2 CATEGORY ICON MAPPER
    // 3.2.1 Maps a category string to its Ionicon icon name.
    // 3.2.2 Used when dynamically creating transaction rows via AJAX.
    // ---------------------------------------------------------------
    function getIconName(category) {
        switch (category) {
            case 'Food': return 'fast-food-outline';
            case 'Shopping': return 'bag-handle-outline';
            case 'Transport': return 'car-sport-outline';
            case 'Bills': return 'flash-outline';
            default: return 'card-outline';
        }
    }

    // ---------------------------------------------------------------
    // 3.3 HTML ESCAPE UTILITY
    // 3.3.1 Prevents XSS by converting special chars to HTML entities.
    // 3.3.2 Uses a temporary div element to safely escape text.
    // ---------------------------------------------------------------
    function escapeHtml(text) {
        var div = document.createElement("div");
        div.textContent = text;
        return div.innerHTML;
    }

    // ---------------------------------------------------------------
    // 4. TOAST NOTIFICATION SYSTEM
    // 4.1 Shows a slide-in notification at the top-right of the screen.
    // 4.2 Supports 'success' (green) and 'error' (red) types.
    // 4.3 Auto-dismisses after 3 seconds with fade-out animation.
    // ---------------------------------------------------------------
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        if (!container) return;

        // 4.4 Create the toast DOM element
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;

        // 4.5 Set the icon based on toast type
        const iconName = type === 'success' ? 'checkmark-circle' : 'alert-circle';
        const iconColor = type === 'success' ? '#166534' : '#ef4444';

        // 4.6 Build the toast HTML with icon and message
        toast.innerHTML = "<ion-icon name=\"" + iconName + "\" style=\"font-size: 1.5rem; color: " + iconColor + ";\"></ion-icon><span class=\"toast-message\"></span>";
        toast.querySelector(".toast-message").textContent = message;

        // 4.7 Add to container (slideIn animation plays automatically via CSS)
        container.appendChild(toast);

        // 4.8 Auto-remove after 3 seconds
        setTimeout(() => {
            toast.style.animation = "fadeOut 0.3s forwards";
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // ---------------------------------------------------------------
    // 5. DARK MODE TOGGLE
    // 5.1 Reads theme preference from localStorage on page load.
    // 5.2 Toggles 'dark-mode' class on body element.
    // 5.3 Persists choice to localStorage for future visits.
    // ---------------------------------------------------------------
    const themeToggle = document.getElementById("themeToggle");
    const body = document.body;

    // 5.4 Apply saved theme on page load
    if (localStorage.getItem("theme") === "dark") {
        body.classList.add("dark-mode");
        updateIcon(true);
    }

    // 5.5 Toggle theme on button click
    if (themeToggle) {
        themeToggle.addEventListener("click", () => {
            body.classList.toggle("dark-mode");
            const isDark = body.classList.contains("dark-mode");
            // 5.5.1 Save preference to localStorage
            localStorage.setItem("theme", isDark ? "dark" : "light");
            // 5.5.2 Update the button icon (moon ↔ sun)
            updateIcon(isDark);
        });
    }

    /**
     * 5.6 updateIcon(isDark)
     * 5.6.1 Switches the toggle button between moon (light) and sun (dark) icons.
     */
    function updateIcon(isDark) {
        const icon = themeToggle ? themeToggle.querySelector("ion-icon") : null;
        if (icon) icon.setAttribute("name", isDark ? "sunny-outline" : "moon-outline");
    }

    // ---------------------------------------------------------------
    // ADD FORM TYPE TOGGLE
    // ---------------------------------------------------------------
    var btnTypeExpense = document.getElementById('btnTypeExpense');
    var btnTypeIncome = document.getElementById('btnTypeIncome');
    var transactionTypeInput = document.getElementById('transactionType');
    var formTitle = document.getElementById('formTitle');
    var submitBtn = document.getElementById('submitBtn');

    function setFormType(type) {
        if (!transactionTypeInput) return;
        transactionTypeInput.value = type;
        if (btnTypeExpense && btnTypeIncome) {
            btnTypeExpense.classList.toggle('active', type === 'expense');
            btnTypeIncome.classList.toggle('active', type === 'income');
        }
        if (formTitle) formTitle.textContent = type === 'income' ? 'Add New Income' : 'Add New Expense';
        if (submitBtn) submitBtn.textContent = type === 'income' ? 'Add Income' : 'Add Expense';
    }

    if (btnTypeExpense) btnTypeExpense.addEventListener('click', function() { setFormType('expense'); });
    if (btnTypeIncome) btnTypeIncome.addEventListener('click', function() { setFormType('income'); });

    // ---------------------------------------------------------------
    // 6. AJAX ADD EXPENSE/INCOME
    // ---------------------------------------------------------------
    var form = document.getElementById("expenseForm");
    if (form) {
        var titleInput = form.querySelector('[name="title"]');
        var amountInput = form.querySelector('[name="amount"]');

        form.addEventListener("submit", function (e) {
            e.preventDefault();

            if (!titleInput || !amountInput) return;

            clearError(titleInput);
            clearError(amountInput);

            var valid = true;

            if (!titleInput.value.trim()) {
                showError(titleInput, "Description is required");
                valid = false;
            }

            var amt = parseFloat(amountInput.value);
            if (amountInput.value.trim() === "" || isNaN(amt) || amt <= 0) {
                showError(amountInput, "Enter a valid amount (greater than 0)");
                valid = false;
            }

            if (!valid) return;

            var formData = new FormData(form);
            var btn = form.querySelector("button[type='submit']");
            var originalText = btn.innerText;

            btn.innerText = "Adding...";
            btn.disabled = true;

            fetch("index.php?action=ajaxAdd", {
                method: "POST",
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        var txType = data.expense.type || 'expense';
                        var label = txType === 'income' ? 'Income' : 'Expense';
                        showToast(label + " added successfully!", "success");

                        updateTotalDisplay(data.totals);

                        var list = document.getElementById("transactionList");
                        if (list) {
                            var emptyEl = document.getElementById("transactionEmpty");
                            if (emptyEl) emptyEl.remove();

                            var dateDisplay = data.expense.formatted_date || "Just now";
                            var amountPrefix = txType === 'income' ? '+' : '-';
                            var amountClass = txType === 'income' ? 'income' : 'expense';
                            var newRowHTML = "<div class=\"transaction-item\" id=\"row-" + data.expense.id + "\">" +
                                "<div class=\"t-left\"><div class=\"t-icon-bg\"><ion-icon name=\"" + getIconName(data.expense.category) + "\"></ion-icon></div>" +
                                "<div class=\"t-info\"><div style=\"display: flex; align-items: center;\">" +
                                "<span class=\"t-title\">" + escapeHtml(data.expense.title) + "</span>" +
                                "<span class=\"badge\">" + escapeHtml(data.expense.category) + "</span></div>" +
                                "<span class=\"t-meta\">" + dateDisplay + "</span></div></div>" +
                                "<div class=\"t-right\"><span class=\"t-amount " + amountClass + "\">" + amountPrefix + "₹" + parseFloat(data.expense.amount).toFixed(2) + "</span>" +
                                "<div class=\"t-actions\"><a href=\"#\" class=\"btn-edit-link\" data-id=\"" + data.expense.id + "\">Edit</a> " +
                                "<a href=\"#\" class=\"btn-delete-link\" data-id=\"" + data.expense.id + "\">Delete</a></div></div></div>";

                            list.insertAdjacentHTML("afterbegin", newRowHTML);
                        }

                        form.reset();
                        setFormType('expense');
                    } else {
                        showToast(data.message || "Error adding transaction", "error");
                    }
                })
                .catch(err => {
                    console.error(err);
                    showToast("Connection Error", "error");
                })
                .finally(function () {
                    btn.innerText = originalText;
                    btn.disabled = false;
                });
        });

        if (titleInput) titleInput.addEventListener("input", function () { clearError(titleInput); });
        if (amountInput) amountInput.addEventListener("input", function () { clearError(amountInput); });
    }

    // ---------------------------------------------------------------
    // 7. DELETE & EDIT CLICK HANDLERS
    // 7.1 Uses event delegation on document.body so it works even
    //     for dynamically added rows (inserted after page load).
    // ---------------------------------------------------------------
    document.body.addEventListener("click", function (e) {

        // 7.2 DELETE HANDLER
        var delBtn = e.target.closest(".btn-delete-link");
        if (delBtn) {
            e.preventDefault();

            if (confirm("Are you sure you want to delete this transaction?")) {
                var id = delBtn.getAttribute("data-id");

                fetch("index.php?action=ajaxDelete", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "id=" + encodeURIComponent(id)
                })
                    .then(function (res) { return res.json(); })
                    .then(function (data) {
                        if (data.success) {
                            showToast("Transaction deleted", "success");

                            var row = document.getElementById("row-" + id);
                            if (row) {
                                row.style.opacity = "0";
                                setTimeout(function () { row.remove(); }, 300);
                            }

                            var list = document.getElementById("transactionList");
                            if (list && list.children.length === 0) {
                                var emptyHtml = "<div class=\"transaction-empty\" id=\"transactionEmpty\" style=\"text-align: center; padding: 40px; color: var(--text-muted);\"><ion-icon name=\"wallet-outline\" style=\"font-size: 3rem; opacity: 0.5; margin-bottom: 10px;\"></ion-icon><p>No transactions yet. Add one above!</p></div>";
                                list.insertAdjacentHTML("beforeend", emptyHtml);
                            }

                            updateTotalDisplay(data.totals);
                        } else {
                            showToast(data.message || "Could not delete", "error");
                        }
                    })
                    .catch(function () { showToast("Connection error", "error"); });
            }
        }

        // 7.3 EDIT HANDLER — opens the edit modal
        var editBtn = e.target.closest(".btn-edit-link");
        if (editBtn) {
            e.preventDefault();
            openEditModal(editBtn.getAttribute("data-id"));
        }
    });

    // ---------------------------------------------------------------
    // 8. EDIT MODAL
    // 8.1 Fetches expense data by ID, populates the modal form,
    //     and handles the update submission via AJAX.
    // ---------------------------------------------------------------
    const modal = document.getElementById("editModal");
    const editForm = document.getElementById("editForm");

    // Edit modal type toggle
    var editBtnTypeExpense = document.getElementById('editBtnTypeExpense');
    var editBtnTypeIncome = document.getElementById('editBtnTypeIncome');
    var editTypeInput = document.getElementById('edit-type');

    function setEditType(type) {
        if (!editTypeInput) return;
        editTypeInput.value = type;
        if (editBtnTypeExpense && editBtnTypeIncome) {
            editBtnTypeExpense.classList.toggle('active', type === 'expense');
            editBtnTypeIncome.classList.toggle('active', type === 'income');
        }
    }

    if (editBtnTypeExpense) editBtnTypeExpense.addEventListener('click', function() { setEditType('expense'); });
    if (editBtnTypeIncome) editBtnTypeIncome.addEventListener('click', function() { setEditType('income'); });

    function openEditModal(id) {
        var editTitle = document.getElementById("edit-title");
        var editAmount = document.getElementById("edit-amount");
        if (editTitle) clearError(editTitle);
        if (editAmount) clearError(editAmount);

        fetch("index.php?action=ajaxGetExpense&id=" + encodeURIComponent(id))
            .then(function (res) { return res.json(); })
            .then(function (resp) {
                if (resp.success && resp.data) {
                    var data = resp.data;
                    document.getElementById("edit-id").value = data.id;
                    document.getElementById("edit-title").value = data.title || "";
                    document.getElementById("edit-amount").value = data.amount != null ? data.amount : "";
                    document.getElementById("edit-category").value = data.category || "Other";
                    setEditType(data.type || 'expense');

                    modal.classList.add("active");
                } else {
                    showToast("Could not load transaction", "error");
                }
            })
            .catch(function () { showToast("Connection error", "error"); });
    }

    if (document.getElementById("closeModalBtn")) {
        document.getElementById("closeModalBtn").onclick = () => modal.classList.remove("active");
    }

    if (editForm) {
        editForm.addEventListener("submit", function (e) {
            e.preventDefault();

            var editTitle = document.getElementById("edit-title");
            var editAmount = document.getElementById("edit-amount");
            if (!editTitle || !editAmount) return;

            clearError(editTitle);
            clearError(editAmount);

            var valid = true;
            if (!editTitle.value.trim()) {
                showError(editTitle, "Description is required");
                valid = false;
            }
            var amt = parseFloat(editAmount.value);
            if (editAmount.value.trim() === "" || isNaN(amt) || amt <= 0) {
                showError(editAmount, "Enter a valid amount (greater than 0)");
                valid = false;
            }
            if (!valid) return;

            var formData = new FormData(editForm);
            fetch("index.php?action=ajaxUpdate", {
                method: "POST",
                body: formData
            })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    if (data.success) {
                        showToast("Updated successfully!", "success");
                        modal.classList.remove("active");
                        updateTotalDisplay(data.totals);

                        var id = formData.get("id");
                        var txType = formData.get("type") || 'expense';
                        var row = document.getElementById("row-" + id);
                        if (row) {
                            row.querySelector(".t-title").textContent = formData.get("title");
                            var amountEl = row.querySelector(".t-amount");
                            var prefix = txType === 'income' ? '+' : '-';
                            amountEl.textContent = prefix + "₹" + parseFloat(formData.get("amount")).toFixed(2);
                            amountEl.className = 't-amount ' + txType;
                            row.querySelector(".badge").textContent = formData.get("category");
                            var iconWrapper = row.querySelector(".t-icon-bg ion-icon");
                            if (iconWrapper) iconWrapper.setAttribute("name", getIconName(formData.get("category")));
                        }
                    } else {
                        showToast(data.message || "Update failed", "error");
                    }
                })
                .catch(function () { showToast("Connection error", "error"); });
        });

        ["edit-title", "edit-amount"].forEach(function (id) {
            var el = document.getElementById(id);
            if (el) el.addEventListener("input", function () { clearError(el); });
        });
    }
});

// ===================================================================
// 9. SEARCH BAR: Filter transaction list by title or category
// 9.1 Works on the Transactions page (searchInput) to filter visible rows.
// 9.2 Filters in real-time as the user types.
// ===================================================================

/**
 * 9.3 initSearchBar(searchInputId, listContainerId)
 * 9.3.1 Initializes a search bar to filter transaction items.
 * 9.3.2 Hides items that don't match the query, shows items that do.
 */
function initSearchBar(searchInputId, listContainerId) {
    var searchInput = document.getElementById(searchInputId);
    var listEl = document.getElementById(listContainerId);
    if (!searchInput || !listEl) return;

    // 9.4 Listen for input changes (real-time filtering)
    searchInput.addEventListener("input", function () {
        var q = (searchInput.value || "").trim().toLowerCase();
        var items = listEl.querySelectorAll(".transaction-item");
        var emptyEl = document.getElementById("transactionEmpty");

        // 9.5 Loop through each transaction item
        for (var i = 0; i < items.length; i++) {
            var item = items[i];
            // 9.5.1 Skip the empty state div
            if (item.id === "transactionEmpty") continue;

            // 9.5.2 Get the title and category badge text for matching
            var titleEl = item.querySelector(".t-title");
            var badgeEl = item.querySelector(".badge");
            var title = (titleEl ? titleEl.textContent : "") + " " + (badgeEl ? badgeEl.textContent : "");

            // 9.5.3 Show/hide based on whether query matches
            var show = q === "" || title.toLowerCase().indexOf(q) !== -1;
            item.style.display = show ? "" : "none";
        }

        // 9.6 Hide empty state while searching
        if (emptyEl) emptyEl.style.display = q === "" ? "" : "none";
    });
}

// 9.7 Initialize search bars on DOM ready
// 9.7.1 "searchInput" is used on the Transactions page
document.addEventListener("DOMContentLoaded", function () {
    initSearchBar("searchTransactions", "transactionList");
    initSearchBar("searchInput", "transactionList");
});

// ===================================================================
// 10. LOGIN FORM VALIDATION
// 10.1 Client-side validation for the login page.
// 10.2 Prevents form submission if username or password is empty.
// 10.3 Runs outside DOMContentLoaded since it uses getElementById.
// ===================================================================
var loginForm = document.getElementById("loginForm");
if (loginForm) {
    // 10.4 Handle login form submission
    loginForm.addEventListener("submit", function (e) {
        var valid = true;
        var username = document.getElementById("username");
        var password = document.getElementById("password");
        if (!username || !password) return;

        // 10.5 Clear previous errors
        clearError(username);
        clearError(password);

        // 10.6 Validate username is not empty
        if (!username.value.trim()) {
            showError(username, "Username is required");
            valid = false;
        }

        // 10.7 Validate password is not empty
        if (!password.value.trim()) {
            showError(password, "Password is required");
            valid = false;
        }

        // 10.8 Prevent submission if invalid
        if (!valid) e.preventDefault();
    });

    // 10.9 Clear errors as user types
    ["username", "password"].forEach(function (id) {
        var el = document.getElementById(id);
        if (el) el.addEventListener("input", function () { clearError(el); });
    });
}