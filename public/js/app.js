document.addEventListener("DOMContentLoaded", () => {
    
    // Helper: Update Total Amount on Dashboard
    function updateTotalDisplay(newTotal) {
        const heroAmount = document.querySelector(".hero-amount");
        if(heroAmount) {
            // Format number with commas
            const formatted = parseFloat(newTotal).toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            heroAmount.innerText = "₹ " + formatted;
        }
    }

    // Helper: Get Icon Name based on Category
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

    // --- 1. AJAX ADD (NO RELOAD) ---
    const form = document.getElementById("expenseForm");
    if (form) {
        form.addEventListener("submit", (e) => {
            e.preventDefault();
            
            const titleInput = form.querySelector('[name="title"]');
            const amountInput = form.querySelector('[name="amount"]');
            const categoryInput = form.querySelector('[name="category"]');
            
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
                    
                    // 1. Update Total
                    updateTotalDisplay(data.newTotal);

                    // 2. Add Row to List (Dynamic HTML)
                    const list = document.getElementById("transactionList");
                    if(list) {
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
                                    <span class="t-meta">Just now</span>
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
                        // Insert at the top
                        list.insertAdjacentHTML('afterbegin', newRowHTML);
                    }

                    // 3. Clear Form
                    form.reset();
                } else {
                    showToast(data.message || "Error adding expense", "error");
                }
            })
            .catch(err => console.error(err))
            .finally(() => {
                btn.innerText = originalText;
                btn.disabled = false;
            });
        });
    }

    // --- 2. DELETE (NO RELOAD) ---
    const list = document.getElementById("transactionList");
    if (list) {
        list.addEventListener("click", (e) => {
            const delBtn = e.target.closest(".btn-delete-link");
            if (delBtn) {
                e.preventDefault();
                const id = delBtn.dataset.id;
                
                if (confirm("Are you sure?")) {
                    fetch("index.php?action=ajaxDelete", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: `id=${id}`
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showToast("Expense deleted", "success");
                            
                            // 1. Animate & Remove Row
                            const row = document.getElementById(`row-${id}`);
                            if (row) {
                                row.style.transition = "all 0.3s ease";
                                row.style.opacity = "0";
                                row.style.transform = "translateX(20px)";
                                setTimeout(() => row.remove(), 300);
                            }

                            // 2. Update Total
                            updateTotalDisplay(data.newTotal);
                        } else {
                            showToast("Could not delete", "error");
                        }
                    });
                }
            }

            // Edit Click Listener
            const editBtn = e.target.closest(".btn-edit-link");
            if (editBtn) {
                e.preventDefault();
                openEditModal(editBtn.dataset.id);
            }
        });
    }

    // --- 3. EDIT & UPDATE (NO RELOAD) ---
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

                    // 1. Update Total
                    updateTotalDisplay(data.newTotal);

                    // 2. Update Row UI directly
                    const id = formData.get('id');
                    const row = document.getElementById(`row-${id}`);
                    if(row) {
                        const title = formData.get('title');
                        const amount = formData.get('amount');
                        const cat = formData.get('category');

                        row.querySelector('.t-title').innerText = title;
                        row.querySelector('.t-amount').innerText = "-₹" + parseFloat(amount).toFixed(2);
                        row.querySelector('.badge').innerText = cat;
                        // Update Icon
                        const iconWrapper = row.querySelector('.t-icon-bg ion-icon');
                        if(iconWrapper) iconWrapper.setAttribute("name", getIconName(cat));
                    }

                } else {
                    showToast("Update failed", "error");
                }
            });
        });
    }
});