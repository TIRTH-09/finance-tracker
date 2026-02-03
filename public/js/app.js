// ===== GLOBAL VALIDATION (must be first so add/edit forms can use them) =====
function showError(input, message) {
    if (!input) return;
    input.classList.add("input-error", "shake");
    var wrapper = input.closest(".input-icon-wrap");
    if (wrapper) wrapper.classList.add("input-error", "shake");

    var formGroup = input.closest(".form-group");
    var errorEl = formGroup ? formGroup.querySelector(".error-text") : null;
    if (errorEl) {
        errorEl.textContent = message;
        errorEl.classList.add("visible");
        errorEl.style.display = "block";
    }

    setTimeout(function () {
        input.classList.remove("shake");
        if (wrapper) wrapper.classList.remove("shake");
    }, 500);
}

function clearError(input) {
    if (!input) return;
    input.classList.remove("input-error");
    var wrapper = input.closest(".input-icon-wrap");
    if (wrapper) wrapper.classList.remove("input-error", "shake");

    var formGroup = input.closest(".form-group");
    var errorEl = formGroup ? formGroup.querySelector(".error-text") : null;
    if (errorEl) {
        errorEl.textContent = "";
        errorEl.classList.remove("visible");
        errorEl.style.display = "none";
    }
}

document.addEventListener("DOMContentLoaded", function () {
    
    // --- FIX: Safer Total Calculation ---
    function updateTotalDisplay(newTotal) {
        const heroAmount = document.querySelector(".hero-amount");
        if(heroAmount) {
            // Convert to number, default to 0 if NaN
            let amount = parseFloat(newTotal);
            if (isNaN(amount)) amount = 0;

            const formatted = amount.toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            heroAmount.innerText = "₹ " + formatted;
        }
    }

    function getIconName(category) {
        switch(category) {
            case 'Food': return 'fast-food-outline';
            case 'Shopping': return 'bag-handle-outline';
            case 'Transport': return 'car-sport-outline';
            case 'Bills': return 'flash-outline';
            default: return 'card-outline';
        }
    }
    function escapeHtml(text) {
        var div = document.createElement("div");
        div.textContent = text;
        return div.innerHTML;
    }

    // --- TOAST SYSTEM ---
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        const iconName = type === 'success' ? 'checkmark-circle' : 'alert-circle';
        const iconColor = type === 'success' ? '#166534' : '#ef4444';

        toast.innerHTML = "<ion-icon name=\"" + iconName + "\" style=\"font-size: 1.5rem; color: " + iconColor + ";\"></ion-icon><span class=\"toast-message\"></span>";
        toast.querySelector(".toast-message").textContent = message;
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.animation = "fadeOut 0.3s forwards";
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // --- DARK MODE ---
    const themeToggle = document.getElementById("themeToggle");
    const body = document.body;
    if(localStorage.getItem("theme") === "dark") {
        body.classList.add("dark-mode");
        updateIcon(true);
    }
    if (themeToggle) {
        themeToggle.addEventListener("click", () => {
            body.classList.toggle("dark-mode");
            const isDark = body.classList.contains("dark-mode");
            localStorage.setItem("theme", isDark ? "dark" : "light");
            updateIcon(isDark);
        });
    }
    function updateIcon(isDark) {
        const icon = themeToggle ? themeToggle.querySelector("ion-icon") : null;
        if (icon) icon.setAttribute("name", isDark ? "sunny-outline" : "moon-outline");
    }

    // --- 1. AJAX ADD EXPENSE ---
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
            var btn = form.querySelector("button");
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
                    showToast("Expense added successfully!", "success");
                    
                    // Update Total safely
                    updateTotalDisplay(data.newTotal);

                    // Add Row to List (transactionList always exists now)
                    var list = document.getElementById("transactionList");
                    if (list) {
                        var emptyEl = document.getElementById("transactionEmpty");
                        if (emptyEl) emptyEl.remove();
                        var dateDisplay = data.expense.formatted_date || "Just now";
                        var newRowHTML = "<div class=\"transaction-item\" id=\"row-" + data.expense.id + "\">" +
                            "<div class=\"t-left\"><div class=\"t-icon-bg\"><ion-icon name=\"" + getIconName(data.expense.category) + "\"></ion-icon></div>" +
                            "<div class=\"t-info\"><div style=\"display: flex; align-items: center;\">" +
                            "<span class=\"t-title\">" + escapeHtml(data.expense.title) + "</span>" +
                            "<span class=\"badge\">" + escapeHtml(data.expense.category) + "</span></div>" +
                            "<span class=\"t-meta\">" + dateDisplay + "</span></div></div>" +
                            "<div class=\"t-right\"><span class=\"t-amount\">-₹" + parseFloat(data.expense.amount).toFixed(2) + "</span>" +
                            "<div class=\"t-actions\"><a href=\"#\" class=\"btn-edit-link\" data-id=\"" + data.expense.id + "\">Edit</a> " +
                            "<a href=\"#\" class=\"btn-delete-link\" data-id=\"" + data.expense.id + "\">Delete</a></div></div></div>";
                        list.insertAdjacentHTML("afterbegin", newRowHTML);
                    }
                    form.reset();
                } else {
                    showToast(data.message || "Error adding expense", "error");
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

    // --- 2. DELETE & EDIT LISTENER (delegate on body so it works when list was empty) ---
    document.body.addEventListener("click", function (e) {
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
                        showToast("Expense deleted", "success");
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
                        updateTotalDisplay(data.newTotal);
                    } else {
                        showToast(data.message || "Could not delete", "error");
                    }
                })
                .catch(function () { showToast("Connection error", "error"); });
            }
        }

        var editBtn = e.target.closest(".btn-edit-link");
        if (editBtn) {
            e.preventDefault();
            openEditModal(editBtn.getAttribute("data-id"));
        }
    });

    // --- 3. EDIT MODAL ---
    const modal = document.getElementById("editModal");
    const editForm = document.getElementById("editForm");

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
                    modal.classList.add("active");
                } else {
                    showToast("Could not load transaction", "error");
                }
            })
            .catch(function () { showToast("Connection error", "error"); });
    }

    if(document.getElementById("closeModalBtn")) {
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
                    updateTotalDisplay(data.newTotal);

                    var id = formData.get("id");
                    var row = document.getElementById("row-" + id);
                    if (row) {
                        row.querySelector(".t-title").textContent = formData.get("title");
                        row.querySelector(".t-amount").textContent = "-₹" + parseFloat(formData.get("amount")).toFixed(2);
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
        [ "edit-title", "edit-amount" ].forEach(function (id) {
            var el = document.getElementById(id);
            if (el) el.addEventListener("input", function () { clearError(el); });
        });
    }
});

// ===== SEARCH BAR: filter transaction list =====
function initSearchBar(searchInputId, listContainerId) {
    var searchInput = document.getElementById(searchInputId);
    var listEl = document.getElementById(listContainerId);
    if (!searchInput || !listEl) return;
    searchInput.addEventListener("input", function () {
        var q = (searchInput.value || "").trim().toLowerCase();
        var items = listEl.querySelectorAll(".transaction-item");
        var emptyEl = document.getElementById("transactionEmpty");
        for (var i = 0; i < items.length; i++) {
            var item = items[i];
            if (item.id === "transactionEmpty") continue;
            var titleEl = item.querySelector(".t-title");
            var badgeEl = item.querySelector(".badge");
            var title = (titleEl ? titleEl.textContent : "") + " " + (badgeEl ? badgeEl.textContent : "");
            var show = q === "" || title.toLowerCase().indexOf(q) !== -1;
            item.style.display = show ? "" : "none";
        }
        if (emptyEl) emptyEl.style.display = q === "" ? "" : "none";
    });
}
document.addEventListener("DOMContentLoaded", function () {
    initSearchBar("searchTransactions", "transactionList");
    initSearchBar("searchInput", "transactionList");
});

// ===== LOGIN VALIDATION =====
var loginForm = document.getElementById("loginForm");
if (loginForm) {
    loginForm.addEventListener("submit", function (e) {
        var valid = true;
        var username = document.getElementById("username");
        var password = document.getElementById("password");
        if (!username || !password) return;

        clearError(username);
        clearError(password);

        if (!username.value.trim()) {
            showError(username, "Username is required");
            valid = false;
        }
        if (!password.value.trim()) {
            showError(password, "Password is required");
            valid = false;
        }
        if (!valid) e.preventDefault();
    });
    // Clear error on input (optional UX)
    ["username", "password"].forEach(function (id) {
        var el = document.getElementById(id);
        if (el) el.addEventListener("input", function () { clearError(el); });
    });
}