<h4 class="text-center" style="color: #111111; font-weight: 700; margin-top: 80px">Form Liên Hệ</h4>
<div class="container mb-4">
    <form action="/php2/ASMC/contact/send" method="post" class="p-4 shadow rounded" style="max-width: 500px; margin: auto; background-color: #f8f9fa;">
        <?php if (isset($_SESSION['thongbao'])): ?>
            <div class="alert alert-info text-center">
                <?= $_SESSION['thongbao']; unset($_SESSION['thongbao']); ?>
            </div>
        <?php endif; ?>
        <div class="mb-3">
            <label for="username" class="form-label fw-bold">Họ và tên</label>
            <input type="text" class="form-control" id="username" name="name" required placeholder="Nhập tên của bạn">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label fw-bold">Email</label>
            <input type="email" class="form-control" id="email" name="email" required placeholder="Nhập email của bạn">
        </div>
        <div class="mb-3">
            <label for="message" class="form-label fw-bold">Ghi chú</label>
            <textarea class="form-control" id="message" rows="3" name="message" required placeholder="Nhập nội dung liên hệ"></textarea>
        </div>
        <button type="submit" class="btn w-100 text-white fw-bold" name="send" style="background-color: #6c757e; border-radius: 8px; padding: 10px;">Gửi</button>
    </form>
</div>
