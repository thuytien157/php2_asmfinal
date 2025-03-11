<div class="main-content mt-5">
    <div class="card">
        <div class="card-body">
            <div class="mb-3 d-flex justify-content-around">
                <div>
                    <p><strong>Mã đơn hàng:</strong> #<?= $orders_detail[0]['id_order'] ?></p>
                    <p><strong>Khách hàng:</strong> <?= $orders_detail[0]['fullname'] ?> - <?= $orders_detail[0]['username'] ?></p>
                    <p><strong>Ngày đặt:</strong> <?= date('d/m/Y',strtotime($orders_detail[0]['orderdate'])) ?></p>
                    <p><strong>Trạng thái thanh toán:</strong> <?= $orders_detail[0]['payment_status'] ?></p>
                </div>
                <div>
                    <p><strong>Địa chỉ nhận hàng:</strong> <?= $orders_detail[0]['address'] ?></p>
                    <p><strong>Số điện thoại:</strong> <?= $orders_detail[0]['phone'] ?></p>
                    <p><strong>Trạng thái:</strong> <?= $orders_detail[0]['status'] ?></p>
                    <p><strong>Phương thức thanh toán:</strong> <?= $orders_detail[0]['payment_method'] ?> - <?= $orders_detail[0]['bank_code'] ?></p>
                    </div>
            </div>
            <h3 class="mb-3">Danh sách sản phẩm</h3>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Giá</th>
                        <th>Ảnh</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders_detail as $order_detail): ?>
                        <?php foreach ($order_detail['name_list'] as $key => $product_name): ?>
                            <tr>
                                <td><?= $product_name ?></td>
                                <td><?= $order_detail['quantity_list'][$key] ?></td>
                                <td><?= number_format($order_detail['price_list'][$key], 0, ',', '.') ?>đ</td>
                                <td>
                                    <img class="anh" src="/php2/ASMC/app/public/img/products/<?=$order_detail['image_list'][$key]?>" alt="">
                                </td>
                                <td><?= number_format($order_detail['price_list'][$key] * $order_detail['quantity_list'][$key], 0, ',', '.') ?>đ</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tbody>

            </table>
            <p class="fw-bold">Tổng cộng: <?=number_format($orders_detail[0]['totalprice'], 0, '.', '.')?></p>
            <a href="/php2/ASMC/admin/order" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>
</div>