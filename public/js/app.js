document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("expenseForm");
    if (form) {
        form.addEventListener("submit", (e) => {
            e.preventDefault();
            fetch("index.php?action=ajaxAdd", {
                method: "POST",
                body: new FormData(form)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) window.location.reload();
                else alert("Error saving data.");
            });
        });
    }
});