document.addEventListener("DOMContentLoaded", () => {
    
    /* ===========================
       DARK MODE LOGIC
    =========================== */
    const themeToggle = document.getElementById("themeToggle");
    const body = document.body;
    
    // Check local storage on load
    const savedTheme = localStorage.getItem("theme");
    if (savedTheme === "dark") {
        body.classList.add("dark-mode");
        updateIcon(true);
    }

    if (themeToggle) {
        themeToggle.addEventListener("click", () => {
            body.classList.toggle("dark-mode");
            const isDark = body.classList.contains("dark-mode");
            
            // Save preference
            localStorage.setItem("theme", isDark ? "dark" : "light");
            updateIcon(isDark);
        });
    }

    function updateIcon(isDark) {
        // Find icon inside the button
        const icon = themeToggle ? themeToggle.querySelector("ion-icon") : null;
        if (icon) {
            icon.setAttribute("name", isDark ? "sunny-outline" : "moon-outline");
        }
    }

    /* ===========================
       AJAX FORM SUBMISSION
    =========================== */
    const form = document.getElementById("expenseForm");
    
    if (form) {
        form.addEventListener("submit", (e) => {
            e.preventDefault(); // Stop page reload

            const formData = new FormData(form);
            const submitBtn = form.querySelector("button[type='submit']");
            const originalText = submitBtn.innerText;
            
            // Show loading state
            submitBtn.innerText = "Adding...";
            submitBtn.disabled = true;

            fetch("index.php?action=ajaxAdd", {
                method: "POST",
                body: formData
            })
            .then(res => res.text()) // Get text first to debug if JSON fails
            .then(text => {
                try {
                    const data = JSON.parse(text);
                    if (data.success) {
                        // Reload to show new data
                        window.location.reload();
                    } else {
                        alert("Error: Database could not save the record.");
                        submitBtn.innerText = originalText;
                        submitBtn.disabled = false;
                    }
                } catch (error) {
                    console.error("Invalid JSON:", text);
                    alert("System Error: Server returned invalid data. Check console.");
                    submitBtn.innerText = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(err => {
                console.error(err);
                alert("Network connection error.");
                submitBtn.innerText = originalText;
                submitBtn.disabled = false;
            });
        });
    }
});