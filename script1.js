document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".add-to-cart").forEach(button => {
        button.addEventListener("click", function () {
            let platId = this.getAttribute("data-plat-id");

            fetch("cart1.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "platId=" + encodeURIComponent(platId) + "&action=add"
            })
            .then(response => response.json()) // Expect JSON response
            .then(data => {
                if (data.success) {
                    alert("Dish added to cart! 🛒");
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
        });
    });
});


document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("confirm-order").addEventListener("click", function () {
        fetch("cart1.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "confirm_order=1"
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload(); // Refresh the page after placing the order
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
    });
});

