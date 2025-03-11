function openPopup(element) {
    const popup = document.getElementById("imagePopup");
    const popupImage = document.getElementById("popupImage");
    popupImage.src = element.src;
    popup.style.display = "flex";
}

function closePopup() {
    document.getElementById("imagePopup").style.display = "none";
}

document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const repasswordInput = document.getElementById('repassword');

    if (passwordInput.type === 'password') {
      passwordInput.type = 'text'; // Hiện mật khẩu
      this.textContent = '🙈'; // Đổi icon
    } else {
      passwordInput.type = 'password'; // Ẩn mật khẩu
      this.textContent = '👁️';
    }
  });


  document.getElementById('togglePassword1').addEventListener('click', function() {
    const repasswordInput = document.getElementById('repassword');

    if (repasswordInput.type === 'password') {
      repasswordInput.type = 'text'; // Hiện mật khẩu
      this.textContent = '🙈'; // Đổi icon
    } else {
      repasswordInput.type = 'password'; // Ẩn mật khẩu
      this.textContent = '👁️';
    }
  });