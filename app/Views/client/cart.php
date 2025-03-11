<div class="container-xxl mt-5">
  <div class="row">
    <div class="col-12 col-lg-8">
      <div class="list-group mt-5" id="cart">
        <?php foreach ($cart as $item): ?>
          <div class="list-group-item d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
              <input class="form-check-input me-3" type="checkbox" checked>
              <img src="/php2/ASMC/app/public/img/products/<?= $item['image'] ?>" alt="Sản phẩm" class="img-thumbnail" style="width: 100px;">
              <div class="ms-3">
                <p class="mb-1 fw-bold"><?= $item['name'] ?></p>
                <p class="text-muted">Số lượng: <?= $item['quantity'] ?></p>
              </div>
            </div>
            <div class="text-center">
              <p class="fw-bold"><?= number_format($item['price'], 0, '.', '.') ?><sup class="fw-bold">đ</sup></p>
              <div class="input-group input-group-sm">
                <button class="btn btn-outline-secondary btn-decrease" type="button" data-id="<?= $item['id'] ?>">-</button>
                <input type="number" id="quantity-<?= $item['id'] ?>" class="form-control text-center quantity-input" value="<?= $item['quantity'] ?>" min="1" readonly>
                <button class="btn btn-outline-secondary btn-increase" type="button" data-id="<?= $item['id'] ?>">+</button>
              </div>
              <a class="btn btn-danger btn-sm mt-2" href="/php2/ASMC/cartRemove/<?= $item['id'] ?>">Xoá</a>
            </div>
          </div>
        <?php endforeach ?>
      </div>

    </div>


    <!-- Thanh toán -->
    <?php
    $totalPrice = 0;
    $totalQuantity = 0;
    foreach ($_SESSION['cart'] as $item) {
      $totalPrice += $item['price'] * $item['quantity'];
      $totalQuantity += $item['quantity'];
    }
    ?>
    <div class="col-12 col-lg-4 mt-5 mt-lg-0">
      <div class="card mt-5">
        <div class="card-body" id="tomtat">
          <h5 class="card-title fw-bold">Tóm tắt đơn hàng</h5>
          <div class="d-flex justify-content-between">
            <p>Tổng sản phẩm:</p>
            <p><?= $totalQuantity ?></p>
          </div>
          <div class="d-flex justify-content-between">
            <p>Tổng tiền:</p>
            <p class="fw-bold"><?= number_format($totalPrice, 0, '.', '.') ?> VND</p>
          </div>
          <button data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-dark w-100 mt-3">Tiến hành thanh toán</button>

          <!-- modal -->
          <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content ttnh">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="exampleModalLabel">Thông tin nhận hàng</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form action="/php2/ASMC/order" method="post">
                    <div class="mb-3">
                      <label for="ho_ten" class="form-label fw-bold">Họ và tên <sup><i class="fa fa-asterisk text-danger"></i></sup></label>
                      <input type="text" class="form-control" id="ho_ten" name="ho_ten" required value="<?= $user[0]['fullname'] ?? '' ?>">
                    </div>
                    <div class="mb-3">
                      <label for="dia_chi" class="form-label fw-bold">Địa chỉ <sup><i class="fa fa-asterisk text-danger"></i></sup></label>
                      <input type="text" class="form-control" id="dia_chi" name="dia_chi" required value="<?= $user[0]['address'] ?? '' ?>">
                    </div>
                    <div class="mb-3">
                      <label for="sdt" class="form-label fw-bold">Số điện thoại <sup><i class="fa fa-asterisk text-danger"></i></sup></label>
                      <input type="text" class="form-control" id="sdt" name="sdt" required value="<?= $user[0]['phone'] ?? '' ?>">
                    </div>
                    <div class="mb-3">
                      <label for="ghi_chu" class="form-label fw-bold">Ghi chú</label>
                      <textarea class="form-control" id="ghi_chu" rows="2" name="ghi_chu"></textarea>
                    </div>
                    <div class="mb-3">
                      <label for="pt_thanhtoan" class="form-label fw-bold">Phương thức thanh toán</label>
                      <select name="pt_thanhtoan" class="form-select">
                        <option value="Thanh toán tiền mặt">Thanh toán tiền mặt</option>
                        <option value="Thanh toán VNPAY">Thanh toán VNPAY</option>
                      </select>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Đóng</button>
                      <button type="submit" class="btn btn-dark" name="order">Xác nhận</button>
                    </div>
                  </form>
                </div>

              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<div class="text-center p-5 pb-2 bg-body-secondary">
  <div class="card-body">
    <h2 class="card-title fw-bold mb-2">ĐĂNG KÝ BẢN TIN CỦA CHÚNG TÔI</h2>
    <p class="card-text">Hãy cập nhật các tin tức thời trang về sản phẩm, BST sắp ra mắt, chương trình khuyến mãi đặc biệt và xu hướng thời trang mới nhất hàng tuần của chúng tôi.</p>
    <div class="input-group mb-3 ip_emmail border border-black rounded">
      <input type="text" class="form-control" placeholder="Nhập email đăng ký nhận tin" aria-label="Recipient's user[0]name" aria-describedby="button-addon2">
      <button class="btn btn-dark text-light fw-bold" type="button" id="button-addon2">ĐĂNG KÝ</button>
    </div>
  </div>
</div>
<!-- <script src="/php2/ASMC/app/public/js/updateQuantity.js"></script> -->