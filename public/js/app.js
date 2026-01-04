document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("expenseForm");

    if (!form) return;

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch("index.php?action=ajaxAdd", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload(); // safest approach for now
            } else {
                alert("Something went wrong");
            }
        })
        .catch(err => {
            console.error(err);
            alert("AJAX error");
        });
    });
});
