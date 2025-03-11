document.addEventListener("DOMContentLoaded", function () {
    //console.log("JavaScript đang chạy!");

    const addToCartForms = document.querySelectorAll("#add-to-cart-form");
    const toastLive = document.getElementById("liveToast");
    const toastBody = toastLive.querySelector(".toast-body");

    addToCartForms.forEach(form => {
        form.addEventListener("submit", function (e) {
            e.preventDefault();

            let formData = new FormData(this);
            formData.append("addToCart", true); 

            fetch("/php2/ASMC/addToCart", {
                method: "POST",
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastBody.textContent = data.message;
                    let toast = new bootstrap.Toast(toastLive);
                    toast.show();
                } else {
                    console.error("Lỗi từ server:", data.message);
                }
            })
            .catch(error => console.error("Lỗi:", error));
        });
    });
});