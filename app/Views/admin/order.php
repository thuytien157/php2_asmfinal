<body>
    <div class="main-content">
        <div class="card-body table-wrapper-scroll-y my-custom-scrollbar">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>T.Products</th>
                        <th>T.Payment</th>
                        <th>Buyer</th>
                        <th>P.method</th>
                        <th>P.status</th>
                        <th>Status</th>
                        <th>Detail</th>
                        <th>Export</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $key => $order): ?>
                        <?php extract($order); ?>
                        <tr>
                            <td><?= $id ?></td>
                            <td><?= date('d/m/Y',strtotime($orderdate)) ?></td>
                            <td><?= $totalquantity ?></td>
                            <td><?= number_format($totalprice, 0, '.', '.') ?>đ</td>
                            <td><?= $username ?></td>
                            <td><?= substr($payment_method, 12, 12) ?></td>
                            <td><?= $payment_status ?></td>
                            <td>
                                <form method="post" action="/php2/ASMC/admin/order/status/<?= $id ?>" id="statusForm<?= $id ?>">
                                    <select name="status" id="statusSelect<?= $id ?>" onchange="checkStatus(event, <?= $id ?>, '<?= $status ?>')">
                                        <option value="Chờ xác nhận" <?= $status == 'Chờ xác nhận' ? 'selected' : ''; ?>>Chờ xác nhận</option>
                                        <option value="Đang chuẩn bị hàng" <?= $status == 'Đang chuẩn bị hàng' ? 'selected' : ''; ?>>Đang chuẩn bị hàng</option>
                                        <option value="Đang giao hàng" <?= $status == 'Đang giao hàng' ? 'selected' : ''; ?>>Đang giao hàng</option>
                                        <option value="Giao thành công" <?= $status == 'Giao thành công' ? 'selected' : ''; ?>>Giao thành công</option>
                                        <option value="Đơn thất bại" <?= $status == 'Đơn thất bại' ? 'selected' : ''; ?>>Đơn thất bại</option>
                                    </select>
                                </form>

                            </td>
                            <td class="d-flex justify-content-center">
                                <a href="/php2/ASMC/admin/order/detail/<?= $id ?>" class="btn bg-dark text-white"><i class="bi bi-arrow-right-square"></i></a>
                            </td>
                            <td><a href="/php2/ASMC/admin/order/export/<?=$id?>" class="btn btn-success text-white">Export</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
