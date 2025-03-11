document.addEventListener('DOMContentLoaded', function () {
    const increaseButtons = document.querySelectorAll('.btn-increase');
    const decreaseButtons = document.querySelectorAll('.btn-decrease');

    increaseButtons.forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.dataset.id;
            //console.log(`Tăng số lượng sản phẩm ID: ${productId}`); // Debug log
            window.location.href = `/php2/ASMC/cart/updateQuantity/${productId}/increase`;
        });
    });

    decreaseButtons.forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.dataset.id;
            //console.log(`Giảm số lượng sản phẩm ID: ${productId}`); // Debug log
            window.location.href = `/php2/ASMC/cart/updateQuantity/${productId}/decrease`;
        });
    });

    document.querySelectorAll('.togglePassword').forEach(toggle => {
        toggle.addEventListener('click', function () {
            const passwordInput = this.previousElementSibling; // Lấy input kế trước icon

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.textContent = '🙈'; // Đổi icon
            } else {
                passwordInput.type = 'password';
                this.textContent = '👁️';
            }
        });
    });
});
