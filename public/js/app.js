document.addEventListener("DOMContentLoaded", () => {
    
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

    // --- TOAST SYSTEM ---
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        const iconName = type === 'success' ? 'checkmark-circle' : 'alert-circle';
        const iconColor = type === 'success' ? '#166534' : '#ef4444';

        toast.innerHTML = `
            <ion-icon name="${iconName}" style="font-size: 1.5rem; color: ${iconColor};"></ion-icon>
            <span class="toast-message">${message}</span>
        `;
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
    const form = document.getElementById("expenseForm");
    if (form) {
        form.addEventListener("submit", (e) => {
            e.preventDefault();
            
            const titleInput = form.querySelector('[name="title"]');
            const amountInput = form.querySelector('[name="amount"]');
            
            if(titleInput.value.trim() === "" || parseFloat(amountInput.value) <= 0) {
                showToast("Please check your input", "error");
                return;
            }

            const formData = new FormData(form);
            const btn = form.querySelector("button");
            const originalText = btn.innerText;
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

                    // Add Row to List
                    const list = document.getElementById("transactionList");
                    if(list) {
                        // Use the formatted date from server
                        const dateDisplay = data.expense.formatted_date || "Just now";
                        
                        const newRowHTML = `
                        <div class="transaction-item" id="row-${data.expense.id}">
                            <div class="t-left">
                                <div class="t-icon-bg">
                                    <ion-icon name="${getIconName(data.expense.category)}"></ion-icon>
                                </div>
                                <div class="t-info">
                                    <div style="display: flex; align-items: center;">
                                        <span class="t-title">${data.expense.title}</span>
                                        <span class="badge">${data.expense.category}</span>
                                    </div>
                                    <span class="t-meta">${dateDisplay}</span>
                                </div>
                            </div>
                            <div class="t-right">
                                <span class="t-amount">-₹${parseFloat(data.expense.amount).toFixed(2)}</span>
                                <div class="t-actions">
                                    <a href="#" class="btn-edit-link" data-id="${data.expense.id}">Edit</a>
                                    <a href="#" class="btn-delete-link" data-id="${data.expense.id}">Delete</a>
                                </div>
                            </div>
                        </div>
                        `;
                        list.insertAdjacentHTML('afterbegin', newRowHTML);
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
            .finally(() => {
                btn.innerText = originalText;
                btn.disabled = false;
            });
        });
    }

    // --- 2. DELETE & EDIT LISTENER ---
    const list = document.getElementById("transactionList");
    if (list) {
        list.addEventListener("click", (e) => {
            const delBtn = e.target.closest(".btn-delete-link");
            if (delBtn) {
                e.preventDefault();
                if (confirm("Are you sure?")) {
                    const id = delBtn.dataset.id;
                    fetch("index.php?action=ajaxDelete", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: `id=${id}`
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showToast("Expense deleted", "success");
                            const row = document.getElementById(`row-${id}`);
                            if (row) {
                                row.style.opacity = "0";
                                setTimeout(() => row.remove(), 300);
                            }
                            updateTotalDisplay(data.newTotal);
                        } else {
                            showToast("Could not delete", "error");
                        }
                    });
                }
            }

            const editBtn = e.target.closest(".btn-edit-link");
            if (editBtn) {
                e.preventDefault();
                openEditModal(editBtn.dataset.id);
            }
        });
    }

    // --- 3. EDIT MODAL ---
    const modal = document.getElementById("editModal");
    const editForm = document.getElementById("editForm");

    function openEditModal(id) {
        fetch(`index.php?action=ajaxGetExpense&id=${id}`)
            .then(res => res.json())
            .then(resp => {
                if (resp.success) {
                    const data = resp.data;
                    document.getElementById("edit-id").value = data.id;
                    document.getElementById("edit-title").value = data.title;
                    document.getElementById("edit-amount").value = data.amount;
                    document.getElementById("edit-category").value = data.category;
                    modal.classList.add("active");
                }
            });
    }

    if(document.getElementById("closeModalBtn")) {
        document.getElementById("closeModalBtn").onclick = () => modal.classList.remove("active");
    }

    if (editForm) {
        editForm.addEventListener("submit", (e) => {
            e.preventDefault();
            const formData = new FormData(editForm);

            fetch("index.php?action=ajaxUpdate", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast("Updated successfully!", "success");
                    modal.classList.remove("active");
                    updateTotalDisplay(data.newTotal);

                    const id = formData.get('id');
                    const row = document.getElementById(`row-${id}`);
                    if(row) {
                        row.querySelector('.t-title').innerText = formData.get('title');
                        row.querySelector('.t-amount').innerText = "-₹" + parseFloat(formData.get('amount')).toFixed(2);
                        row.querySelector('.badge').innerText = formData.get('category');
                        const iconWrapper = row.querySelector('.t-icon-bg ion-icon');
                        if(iconWrapper) iconWrapper.setAttribute("name", getIconName(formData.get('category')));
                    }
                } else {
                    showToast("Update failed", "error");
                }
            });
        });
    }
});