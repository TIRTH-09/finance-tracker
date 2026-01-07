document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("expenseForm");
    if (form) {
        form.addEventListener("submit", (e) => {
            e.preventDefault();
            const formData = new FormData(form);

            fetch("index.php?action=ajaxAdd", {
                method: "POST",
                body: formData
            })
            .then(async res => {
                const text = await res.text();
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error("Server sent invalid JSON:", text);
                    throw new Error("Invalid server response");
                }
            })
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert("Error: Database could not save the record.");
                }
            })
            .catch(err => {
                alert("Connection Error: Check the Console (F12) for details.");
                console.error(err);
            });
        });
    }
});