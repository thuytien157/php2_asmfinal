<main class="wrap container-xxl mt-5 py-4">
<div class="row">
    <div class="col-md-4 col-lg-3">
        <div class="list-group">
            <a href="#" class="list-group-item list-group-item-dark active">
                Thông tin tài khoản
            </a>
            <a href="/php2/ASMC/order/infor" class="list-group-item list-group-item-action">
                Lịch sử mua hàng
            </a>
            <a href="" class="list-group-item list-group-item-action">
                Yêu thích
            </a>
            <a href="#" class="list-group-item list-group-item-action">
                Mã giảm giá
            </a>
            <a href="#" class="list-group-item list-group-item-action">
                Thông báo
            </a>
            <a href="index.php?act=logout" class="list-group-item list-group-item-action">
                Đăng xuất
            </a>

            <div class="card mt-4">
                <div class="card-header">
                    <h5>Bảo mật</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <a data-bs-toggle="modal" data-bs-target="#changePassModal" class="btn btn-link">Cập nhật</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8 col-lg-9">
        <div class="card">
            <div class="card-header">
                <h5>Hồ sơ của tôi</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="account/update">

                    <div class="mb-3">
                        <label for="ho_ten" class="form-label">Họ tên</label>
                        <input type="text" id="ho_ten" name="hoten" class="form-control" value="<?=$infouser['fullname']?>">
                    </div>

                    <div class="mb-3">
                        <label for="sdt" class="form-label">Số điện thoại</label>
                        <input type="text" id="sdt" name="sdt" class="form-control" value="<?=$infouser['phone']?>">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?=$infouser['email']?>">
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Địa chỉ</label>
                        <input type="text" id="address" name="address" class="form-control" value="<?=$infouser['address']?>">
                    </div>

                    <button type="submit" name="edituser" class="btn btn-dark">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
</div>

  <!-- Modal đổi mật khẩu -->
  <div class="modal fade" id="changePassModal" aria-hidden="true" aria-labelledby="loginLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 fw-bold w-100 text-center" id="registerLabel">Đổi mật khẩu</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="post" action="/php2/ASMC/user/changepass">
            <?php if(!empty($infouser['password'])): ?>
            <div class="mb-3">
              <label for="loginUsername" class="form-label">Mật khẩu cũ</label>
              <input type="password" class="form-control" id="loginUsername" placeholder="Nhập username của bạn..." name="oldpassword">
            </div>
            <?php endif; ?>
            <div class="mb-3">
              <label for="loginPassword" class="form-label">Mật khẩu mới</label>
              <input type="password" class="form-control" id="loginPassword" placeholder="Nhập mật khẩu..." name="password">
            </div>
            <div class="mb-3">
              <label for="loginPassword" class="form-label">Nhập lại mật khẩu mới</label>
              <input type="password" class="form-control" id="loginPassword" placeholder="Nhập mật khẩu..." name="password1">
            </div>
            <div class="modal-footer d-flex flex-column align-items-center">
              <button type="submit" class="btn btn-dark ps-5 pe-5" name="changepass">Cập nhật mật khẩu</button>
              <a href="/php2/ASMC/user/reset" class="text-danger">Quên mật khẩu?</a>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>


</main>