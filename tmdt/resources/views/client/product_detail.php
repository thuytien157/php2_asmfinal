<div class="container-xxl pt-3 mt-5">
    <div class="card mb-3 border-0 ms-3">
        <div class="row g-4" id="product-detail">
            <!-- Cột hình ảnh -->
            <div class="col-md-5 mt-5">
                <div class="d-flex">
                    <div class="d-flex flex-column gap-2 me-3 w-50" style="max-height: 500px; overflow-y: auto;" id="img_detail">
                        <?php foreach ($images as $product): ?>
                            <img src="/php2/ASMC/app/public/img/products/<?= $product ?>" class="img-thumbnail thumb-img" alt="Thumbnail 1" <?= empty($product) ? 'style="display:none;"' : '' ?> style="max-width: 100%; height: auto;">
                        <?php endforeach; ?>
                    </div>
                    <?php
                    $product = $listprodetail[0];
                    $finalPrice = !empty($product['discount'])
                        ? round($product['price'] - ($product['price'] * ($product['discount'] / 100)), -3)
                        : round($product['price'], -3);
                    ?>
                    <div class="flex-grow-1">
                        <img src="/php2/ASMC/app/public/img/products/<?= $images[0] ?>" class="img-fluid rounded" alt="Main Image" style="max-width: 100%; height: auto;">
                    </div>
                </div>
            </div>
            <div class="col-md-7 mt-5">
                <div class="card-body">
                    <h5 class="card-title fs-3 fw-bold"><?= $product['name'] ?></h5>
                    <p class="card-text text-muted">Mã sản phẩm <?= $product['product_id'] ?>.</p>
                    <p class="card-text text-danger fw-bold fs-4">
                        <?php if (!empty($product['discount'])): ?>
                            <?= number_format($finalPrice, 0, '.', '.') ?> VNĐ
                            <del class="text-dark fs-6"><?= number_format(round($product['price'], -3), 0, '.', '.') ?> VNĐ</del>
                        <?php else: ?>
                            <?= number_format(round($product['price'], -3), 0, '.', '.') ?> VNĐ
                        <?php endif; ?>
                    </p>
                    <?php if ($product['status'] == 'active'): ?>

                        <p class="card-text fw-medium">Kích thước</p>
                        <div class="d-flex gap-2">
                            <?php foreach ($listsize as $size): ?>
                                <button type="button"
                                    class="btn btn-outline-dark ps-5 pe-5 rounded-pill fw-bold size-btn active">
                                    <?= $size['type'] ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                        <p class="card-text fw-medium mt-3">Màu sắc</p>
                        <div class="d-flex gap-2">
                                <button type="button"
                                    class="btn btn-outline-dark rounded-circle border-1 border-dark mb-5 color-btn"
                                    style="background-color: <?= $product['hex_code'] ?>; width: 40px; height: 40px;">
                                </button>
                        </div>
                        <form action="/php2/ASMC/addToCart" method="POST" id="add-to-cart-form">
                            <input type="hidden" name="id" value="<?= $product['product_id'] ?>">
                            <input type="hidden" name="product_name" value="<?= $product['name'] ?>">
                            <input type="hidden" name="product_price" value="<?= $finalPrice ?>">
                            <input type="hidden" name="product_image" value="<?= $images[0] ?>?>">
                            <input type="number" name="quantity" value="1" min="1" class="form-control w-25" id="quantity">
                            <div class="d-flex gap-2 mt-3">
                                <button class="btn btn-dark fs-6 fw-bold pe-5 ps-5 pt-3 pb-3" name="addToCart" type="submit" id="add-to-cart-btn">Thêm vào giỏ hàng</button>
                                <button class="btn btn-danger fs-6 fw-bold pe-5 ps-5 pt-3 pb-3" type="button" onclick="window.location.href='/php2/ASM/checkout'">Mua ngay</button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="card-footer text-dark fw-semibold">
                            Sản phẩm này hiện tại đang ngừng kinh doanh. Bạn có thể <a href="/php2/ASMC/contact">Liên hệ</a> để biết thêm chi tiết
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="card-title">MLB Chào bạn mới</h5>
                        <p class="card-text">Nhận ngay ưu đãi 5% khi đăng ký thành viên và mua đơn hàng nguyên giá đầu tiên tại website*</p>
                        <p class="card-text">Nhập mã: <strong>MLBWELCOME</strong></p>
                        <p class="card-text">Ưu đãi không áp dụng cùng với các CTKM khác</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Phần chính sách -->
<div class="chinhsach">
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs justify-content-center" id="policyTabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#" data-bs-toggle="tab" data-bs-target="#product-info">Thông tin sản phẩm</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="tab" data-bs-target="#return-policy">Chính sách đổi trả hàng</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="tab" data-bs-target="#strong_intruction">Hướng dẫn bảo quản</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="product-info">
                    <div class="row">
                        <div class="col">
                            <?= $product['description'] ?>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="return-policy">
                    <p>Áp dụng cho toàn bộ sản phẩm quần áo nguyên giá.</p>
                    <p><strong>Đối tượng khách hàng:</strong> Tất cả khách hàng sử dụng dịch vụ tại website của chúng tôi</p>
                    <p><strong>Thời gian đổi/ trả hàng:</strong></p>
                    <li><strong>Đổi hàng:</strong> Trong vòng <strong>30 ngày</strong> kể từ ngày khách hàng nhận được sản phẩm.</li>
                    <li><strong>Trả hàng:</strong> Trong vòng <strong>03 ngày</strong> ngày kể từ ngày khách hàng nhận được sản phẩm.</li><br>
                    <p><i>Lưu ý: Không áp dụng cho các sản phẩm giảm giá từ 30% trở lên và các sản phẩm mua trực tiếp tại hệ thống cửa hàng của Maison.</i></p>
                    <p><u>Ghi chú: </u>Thời hạn đổi/trả hàng được tính từ ngày khách hàng nhận hàng cho đến ngày khách hàng gửi hàng đổi/trả cho đơn vị vận chuyển.</p>
                </div>
                <div class="tab-pane fade" id="strong_intruction">
                    <p>Giặt khô.</p>
                    <p>Không sử dụng hóa chất mạnh</p>
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
            <input type="text" class="form-control" placeholder="Nhập email đăng ký nhận tin" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-dark text-light fw-bold" type="button" id="button-addon2">Đăng ký</button>
        </div>
    </div>
</div>
</div>
<script>
    // JavaScript để xử lý việc gửi form khi không chọn số lượng
    document.getElementById('add-to-cart-form').addEventListener('submit', function(event) {
        // Kiểm tra nếu người dùng chưa nhập số lượng
        if (document.getElementById('quantity').value <= 0) {
            event.preventDefault(); // Ngừng gửi form
            document.getElementById('error-message').style.display = 'block'; // Hiển thị lỗi
        } else {
            document.getElementById('error-message').style.display = 'none'; // Ẩn thông báo lỗi
        }
    });
</script>
<!-- <script src="/php2/ASMC/app/public/js/selectVariants.js"></script> -->