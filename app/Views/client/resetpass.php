<div class="container" style="margin-top: 80px;">
    <div class="row justify-content-center">
        <div class="col-md-6 border shadow p-3 mb-5 border-light p-3 mb-3 rounded-3">
            <h3 class="text-center">Quên Mật Khẩu</h3>

            <form id="resetForm">
                <p class="text-danger mt-3" id="errorMessage1"></p>

                <!-- Bước 1: Nhập Email -->
                <label class="mb-2">Email của bạn:</label>
                <div class="d-flex gap-2">
                    <input type="email" id="email" name="email" class="form-control" placeholder="Nhập email" required>
                    <button type="button" id="sendCodeBtn" class="btn btn-secondary w-25">Gửi</button>
                </div>

                <!-- Bước 2: Nhập mã xác nhận & mật khẩu -->
                <label class="mb-2 mt-3">Nhập mã xác nhận:</label>
                <div class="d-flex justify-content-center gap-3 w-75 ms-5">
                    <input type="text" class="otp-input form-control text-center" maxlength="1">
                    <input type="text" class="otp-input form-control text-center" maxlength="1">
                    <input type="text" class="otp-input form-control text-center" maxlength="1">
                    <input type="text" class="otp-input form-control text-center" maxlength="1">
                    <input type="text" class="otp-input form-control text-center" maxlength="1">
                </div>
                <p class="text-danger mt-2" id="timer"></p>

                <label class="mb-2">Mật khẩu mới:</label>
                <div class="input-group mb-3">
                    <input type="password" id="newPassword" class="form-control" placeholder="Nhập mật khẩu mới" required>
                    <button class="toggle-pass-btn" type="button" data-target="newPassword">
                        👁
                    </button>
                </div>

                <label class="mb-2">Nhập lại mật khẩu:</label>
                <div class="input-group mb-3">
                    <input type="password" id="newPassword1" class="form-control" placeholder="Nhập mật khẩu mới" required>
                    <button class="toggle-pass-btn" type="button" data-target="newPassword1">
                        👁
                    </button>
                </div>



                <button type="submit" id="resetPasswordBtn" class="btn btn-secondary w-100">Đổi mật khẩu</button>
            </form>

        </div>
    </div>
</div>
<script>
    document.querySelectorAll(".otp-input").forEach((input, index, arr) => {
        input.addEventListener("input", function() {
            if (this.value && index < arr.length - 1) {
                arr[index + 1].focus();
            }
        });
    });

    document.getElementById("sendCodeBtn").addEventListener("click", function(event) {
        event.preventDefault();

        let email = document.getElementById("email").value.trim();
        let messageElement = document.getElementById("errorMessage1");

        if (!email) {
            messageElement.classList.add("text-danger", "fw-bold");
            messageElement.innerText = "Vui lòng nhập email!";
            return;
        }

        let formData = new FormData();
        formData.append("action", "sendCode");
        formData.append("email", email);

        fetch("/php2/ASMC/user/reset", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                messageElement.classList.remove("text-danger", "text-success");
                messageElement.classList.add(data.status === "success" ? "text-success" : "text-danger", "fw-bold");
                messageElement.innerText = data.message;
            })
            .catch(error => console.error("Lỗi:", error));
    });

    document.querySelectorAll(".toggle-pass-btn").forEach(button => {
        button.addEventListener("click", function() {
            let targetInput = document.getElementById(this.getAttribute("data-target"));
            if (targetInput.type === "password") {
                targetInput.type = "text";
                this.textContent = "🙈"; // Đổi icon
            } else {
                targetInput.type = "password";
                this.textContent = "👁️"; // Đổi icon
            }
        });
    });

    document.getElementById("resetForm").addEventListener("submit", function(event) {
        event.preventDefault();

        let otpCode = Array.from(document.querySelectorAll(".otp-input"))
            .map(input => input.value.trim())
            .join("");

        let email = document.getElementById("email").value.trim();
        let newPassword = document.getElementById("newPassword").value;
        let confirmPassword = document.getElementById("newPassword1").value;
        let messageElement = document.getElementById("errorMessage1");



        if (otpCode.length < 5) {
            messageElement.classList.add("text-danger", "fw-bold");
            messageElement.innerText = "Vui lòng nhập đầy đủ mã xác nhận!";
            return;
        }

        if (!newPassword || !confirmPassword) {
            messageElement.classList.add("text-danger", "fw-bold");
            messageElement.innerText = "Vui lòng nhập đầy đủ mật khẩu!";
            return;
        }

        if (newPassword !== confirmPassword) {
            messageElement.classList.add("text-danger", "fw-bold");
            messageElement.innerText = "Mật khẩu không khớp!";
            return;
        }

        let formData = new FormData();
        formData.append("action", "verifyCode");
        formData.append("otp", otpCode);
        formData.append("email", email);
        formData.append("newPassword", newPassword);

        fetch("/php2/ASMC/user/reset", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log("Server Response:", data);
                messageElement.innerHTML = data.status === "success" ?
                    `<div class="alert alert-success" role="alert"> ${data.message} <br>Vui lòng <a data-bs-toggle="modal" data-bs-target="#loginModal" class="alert-link">Đăng nhập</a>.</div>` :
                    `<div class="alert alert-danger" role="alert"> ${data.message}.</div>`;
            })
            .catch(error => console.error("Lỗi:", error));
    });
</script>