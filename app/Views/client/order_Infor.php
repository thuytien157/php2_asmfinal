<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
.wrap {
  padding: 15px;
  background-color: #f8f9fa;
}

.lichsumuahang {
  background-color: #fff;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
  border-radius: 10px;
  padding: 15px;
  margin-bottom: 15px;
}

.fw-bold {
  font-weight: bold;
  font-size: 16px;
  margin-bottom: 8px;
}

.thongtinls {
  margin-bottom: 15px;
}

/* Thông tin sản phẩm */
.ttdh {
  display: flex;
  justify-content: flex-start;
  align-items: center;
  margin-bottom: 8px;
}

.img-thumbnail {
  width: 40px;
  height: 40px;
  object-fit: cover;
  border-radius: 5px;
}

.ten-sach {
  flex: 1;
  padding-left: 8px;
  font-size: 14px;
}

.sl, .gials {
  margin-left: 10px;
  font-size: 14px;
}

.gials {
  color: #d9534f;
}

/* Trạng thái và tổng tiền */
.trangthai {
  margin-top: 15px;
}

.trangthaivatien {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.payment {
  font-size: 16px;
  font-weight: bold;
}

.dathanhtoan {
  font-size: 14px;
}

.thoigiangiaohang {
  margin-top: 8px;
  font-size: 13px;
}

/* Các nút */
.xemchitiet {
  background-color: #212429;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 5px;
  cursor: pointer;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  margin-top: 8px;
}

.xemchitiet:hover {
  background-color: #1a8c96;
}

/* Modal */
.modal-content {
  border-radius: 10px;
}

.modal-header {
  background-color: #24aeb1;
  color: white;
}

.modal-title {
  font-size: 16px;
  font-weight: bold;
}

.modal-body {
  font-size: 14px;
}

.btn-secondary {
  background-color: #6c757d;
}

.btn-danger {
  background-color: #d9534f;
}

.btn-close {
  color: white;
}

/* Responsive */
@media (max-width: 768px) {
  .ttdh {
      flex-direction: column;
      align-items: flex-start;
  }

  .ten-sach, .sl, .gials {
      margin-top: 5px;
  }
}
</style>
<body>
<main class="wrap container-xl mt-5">
    <?php foreach($inforOrder as $order): ?>
    <div class="lichsumuahang shadow p-3 rounded">
        <div class="fw-bold">Mã đơn hàng: #<?php echo $order['id']; ?></div>
        
        <div class="thongtinls d-flex flex-column">
            <?php
                $names = explode(",", $order['name']);
                $quantities = explode(",", $order['quantity']);
                $prices = explode(",", $order['price']);
                $images = explode(",", $order['image']);
            ?>
            
            <?php for($i = 0; $i < count($names); $i++): ?>
                <div class="ttdh d-flex justify-content-start align-items-center w-100">
                    <img src="/php2/ASMC/app/public/img/products/<?php echo $images[$i]; ?>" class="img-thumbnail img_dh" alt="...">
                    <div class="ten-sach"><?php echo $names[$i]; ?></div>
                    <div class="sl">Số lượng: <?php echo $quantities[$i]; ?></div>
                    <div class="gials">Giá: <?php echo number_format($prices[$i], 0, '.', '.'); ?>đ</div>
                </div>
            <?php endfor; ?>
        </div>
        <div class="trangthai">
            <div class="trangthaivatien">
                <h5 class="payment"><?=$order['status']; ?></h5>
            </div>
            <button class="xemchitiet" data-bs-toggle="modal" data-bs-target="#staticBackdrop<?=$order['id']?>">
                Xem chi tiết
            </button>
            <button class="xemchitiet <?=($order['status'] != "Đang giao hàng" && $order['status'] != "Giao thành công" && $order['status'] != "Đơn thất bại") ? '' : 'd-none'; ?>" data-bs-toggle="modal" data-bs-target="#staticBackdrop_address<?=$order['id']?>">
                Thay đổi địa chỉ nhận hàng
            </button>
            <!-- Modal -->
            <div class="modal fade" id="staticBackdrop_address<?=$order['id']?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Thay đổi địa chỉ nhận hàng</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form action="/php2/ASMC/order/infor/editAddress" method="post">
                    <textarea name="address" id="address" rows="2" cols="60"><?=$order['address']?></textarea>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" name="editaddress" data-bs-dismiss="modal">Xác nhận</button>
                    </div>
                </form>

                </div>
                
                </div>
            </div>
            </div>
            <button class="xemchitiet <?=($order['status'] != "Đang giao hàng" && $order['status'] != "Giao thành công" && $order['status'] != "Đơn thất bại") ? '' : 'd-none'; ?>" data-bs-toggle="modal" data-bs-target="#staticBackdrop_confirmCancel<?=$order['id']?>">
                Hủy 
            </button>
            <!-- Modal -->
            <div class="modal fade" id="staticBackdrop_confirmCancel<?=$order['id']?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div>Bạn chắc chắn muốn huỷ đơn hàng này?</div>
                <div class="modal-footer">
                    <a class="btn btn-danger" href="/php2/ASMC/order/infor/cancel/<?=$order['order_code']?>">Xác nhận</a>
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Đóng</button>
                </div>
                </div>
                
                </div>
            </div>
            </div>


            <div class="modal fade" id="staticBackdrop<?=$order['id']?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Thông tin đơn hàng <?=$order['id']?></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div><b>Tên người mua: </b><?=$order['fullname']?></div>
                    <div><b>Địa chỉ: </b><?=$order['address']?></div>
                    <div><b>Ngày mua hàng: </b><?= date('d/m/Y', strtotime($order['orderdate']))?></div>
                    <div><b>Tổng tiền: </b><?=number_format($order['totalprice'], 0, '.', '.')?></div>
                    <div><b>Trạng thái thanh toán: </b><?= $order['payment_status']?></div>
                    <div><b>Phương thức thanh toán: </b><?= $order['payment_method']?> - <?=$order['bank_code']?></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Đóng</button>
                </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</main>
</body>
</html>
