<?php

namespace app\Controllers\client;

include_once './core/viewer.php';
include_once './app/Models/OrderModel.php';
include_once './app/Models/UserModel.php';

use core\BaseController;
use app\Models\OrderModel;
use app\Models\UserModel;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class OrderController extends BaseController
{
    private $order;
    private $user;
    private $vnp_TmnCode = "IPCSTBMI";
    private $vnp_HashSecret = "S1KAOXRO80OZ85L4HV3RO0OH8T4OBTV6";
    private $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    private $vnp_Returnurl = "http://localhost/php2/ASMC/vnpayreturn";

    public function __construct()
    {
        parent::__construct();
        $this->order = new OrderModel();
        $this->user = new UserModel();
    }

    public function orders()
    {

        $totalPrice = 0;
        $totalQuantity = 0;

        foreach ($_SESSION['cart'] as $item) {
            $totalPrice += (int)$item['price'] * (int)$item['quantity'];
            $totalQuantity += (int)$item['quantity'];
        }
        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['order'])) {
            $id_user = $_SESSION['user']['id'] ?? '';
            $payment_method = $_POST['pt_thanhtoan'] ?? '';
            $note = $_POST['ghi_chu'] ?? '';
            $order_code = time();
            $transaction_no = time();
            $bank_code = null;
            $payment_status = "Chưa Thanh toán"; 
            $status = "Chờ xác nhận";
        
            $ho_ten = $_POST['ho_ten'] ?? '';
            $dia_chi = $_POST['dia_chi'] ?? '';
            $sdt = $_POST['sdt'] ?? '';
            $ghi_chu = $_POST['ghi_chu'] ?? null;
        
            $this->user->updateUser($ho_ten, $sdt, $_SESSION['user']['email'], $dia_chi, $id_user);
        
            $this->order->insertOrder($order_code, $totalQuantity, $totalPrice, $payment_method, $note, $_SESSION['cart'], $id_user);

            if ($payment_method === "Thanh toán VNPAY") { 
                $this->createPayment($totalPrice, $order_code);
                exit();
            }else{
                $insertsucess = $this->order->insertPayment($order_code, $transaction_no, $totalPrice, $bank_code, $payment_status);

                $sendmailsuccess = $this->sendInvoiceEmail($order_code, $status);
                unset($_SESSION['cart']);
            } 
            
            
            if ($insertsucess) {
                $_SESSION['success'] = "Đặt hàng thành công! Chúng tôi đã gửi thông tin đơn hàng đến email của bạn!";
                header('location: /php2/ASMC/order/infor');
                exit;
            } else {
                $_SESSION['error'] = "Đặt hàng không thành công!";
                header('location: /php2/ASMC/order/infor');
                exit;
            }
        }
        

    }


    public function createPayment($totalprice, $order_code)
    {
        $vnp_TxnRef = $order_code;
        $vnp_Amount = $totalprice * 100;
        $vnp_OrderInfo = "Thanh toán đơn hàng #$vnp_TxnRef";
        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $this->vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $_SERVER['REMOTE_ADDR'],
            "vnp_Locale" => "vn",
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => "billpayment",
            "vnp_ReturnUrl" => $this->vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            // "vnp_BankCode" => 'NCB'
        ];

        ksort($inputData);
        $query = http_build_query($inputData);
        $secureHash = hash_hmac("sha512", $query, $this->vnp_HashSecret);
        $vnp_PaymentUrl = $this->vnp_Url . "?" . $query . "&vnp_SecureHash=" . $secureHash;
        header("Location: $vnp_PaymentUrl");
        exit();
    }

    public function vnpayReturn()
    {
        $order_code = $_GET["vnp_TxnRef"] ?? null;
        $transaction_no = $_GET["vnp_TransactionNo"] ?? "";
        $amount = isset($_GET["vnp_Amount"]) ? $_GET["vnp_Amount"] / 100 : 0;
        $bank_code = $_GET["vnp_BankCode"] ?? "";
        $is_success = isset($_GET['vnp_ResponseCode']) && $_GET['vnp_ResponseCode'] == "00";
        $payment_status = $is_success ? "Đã thanh toán" : "Chưa thanh toán";
        $status = $is_success ? "Chờ xác nhận" : "Đơn thất bại";
        $insertsucess = $this->order->insertPayment($order_code, $transaction_no, $amount, $bank_code, $payment_status);
        if ($insertsucess) {
            $sendMailSuccess = $this->sendInvoiceEmail($order_code, $status);

            if ($sendMailSuccess) {
                $_SESSION[$is_success ? 'success' : 'error'] = $is_success
                    ? "Đặt hàng thành công! Chúng tôi đã gửi thông tin đơn hàng đến email của bạn!"
                    : "Đặt hàng không thành công! Chúng tôi đã gửi thông tin đơn hàng đến email của bạn!";
            } else {
                $_SESSION['error'] = "Không thể gửi email xác nhận đơn hàng!";
            }
        } else {
            $_SESSION['error'] = "Không thể lưu thông tin thanh toán!";
        }

        unset($_SESSION['cart']);
        header("Location: /php2/ASMC/order/infor");
        exit();
    }


    public function InforOrder()
    {
        $id_user = $_SESSION['user']['id'];
        $this->render('order_Infor', ['inforOrder' => $this->order->getOrders($id_user)]);
    }

    public function updateAddress()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['editaddress'])) {
            $id_user = $_SESSION['user']['id'];
            $updateSuccess = $this->order->updateAddress($id_user, $_POST['address']);
            $_SESSION[$updateSuccess ? 'success' : 'error'] = $updateSuccess ? "Cập nhật thành công" : "Cập nhật không thành công";
        }
        header('Location: /php2/ASMC/order/infor');
        exit();
    }


    public function cancelOrder($id)
    {
        $status = "Đơn thất bại";
        $updateSuccess = $this->order->updateStatusorder_code($id, $status);

        if ($updateSuccess) {
            if($this->sendInvoiceEmail($id, $status)){
                $_SESSION['success'] = "Đã huỷ đơn hàng và gửi thông báo qua email.";
            }
        } else {
            $_SESSION['error'] = "Huỷ không thành công.";
        }

        header('Location: /php2/ASMC/order/infor');
        exit();
    }



    private function sendInvoiceEmail($orderId, $status)
    {

        require 'PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
        require 'PHPMailer-master/PHPMailer-master/src/SMTP.php';
        require 'PHPMailer-master/PHPMailer-master/src/Exception.php';

        $mail = new PHPMailer(true);

        $order = $this->order->getOrderByCode($orderId);
        foreach ($order as &$orders) {
            $orders['name_list'] = explode(',', $orders['pname']);
            $orders['image_list'] = explode(',', $orders['pimage']);
            $orders['price_list'] = explode(',', $orders['price']);
            $orders['quantity_list'] = explode(',', $orders['quantity']);
        }
        // echo "<pre>";
        // var_dump($orders['name_list']);
        // echo "</pre>";

        // echo "<pre>";
        // var_dump($orders['price_list']);
        // echo "</pre>";

        // echo "<pre>";
        // var_dump($orders['quantity_list']);
        // echo "</pre>";

        // echo "<pre>";
        // var_dump($order);
        // echo "</pre>";
        // exit;
        if (!$order) return false;


        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = "tiennttps39163@gmail.com";
            $mail->Password   = "fubamlrqynmfpdvw";
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            $mail->setFrom("tiennttps39163@gmail.com", 'Hoá đơn của bạn');
            $mail->addAddress($_SESSION['user']['email']);

            $mail->CharSet = 'UTF-8';
            $mail->isHTML(true);
            $mail->Subject = "Đơn hàng #{$order[0]['order_code']}";

            $mail->Body = '
            <!DOCTYPE html>
            <html lang="vi">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Thông báo cập nhật đơn hàng</title>
                <style>
                    .container {
                        max-width: 600px;
                        background-color: #ffffff;
                        padding: 20px;
                        border-radius: 8px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        margin: 0 auto;
                    }
                    h3 {
                        color:rgb(0, 0, 0);
                    }
                    p {
                        font-size: 16px;
                        color: #333;
                    }
                    .order-info {
                        background-color: #f9f9f9;
                        padding: 10px;
                        border-left: 4px solid rgb(0, 0, 0);
                        margin: 10px 0;
                        border-radius: 5px;
                    }
                    .product-list {
                        border-collapse: collapse;
                        width: 100%;
                        margin-top: 10px;
                    }
                    .product-list th, .product-list td {
                        border: 1px solid #ddd;
                        padding: 8px;
                        text-align: left;
                    }
                    .product-list th {
                        background-color:rgb(0, 0, 0);
                        color: white;
                    }
                    .footer {
                        margin-top: 20px;
                        font-size: 14px;
                        color: #666;
                        text-align: center;
                    }
                </style>
            </head>
            <body>
        
            <div class="container">
                <h3>Xin chào ' . $order[0]['fullname'] . ' - ' . $order[0]['username'] . ',</h3>
                <p>Đơn hàng của bạn hiện đang ở trạng thái: <strong>' . $order[0]['status'] . '</strong></p>
        
                <div class="order-info">
                <div>
                    <p><strong>Mã đơn hàng:</strong> ' . $order[0]['order_code'] . '</p>
                    <p><strong>Phương thức thanh toán:</strong> ' . $order[0]['payment_method'] . '</p>
                    <p><strong>Ngày đặt hàng:</strong> ' . date('d/m/Y', strtotime($order[0]['orderdate'])) . '</p>
                    <p><strong>Tổng tiền:</strong> <span style="color:rgb(5, 4, 4);">' . number_format($order[0]['totalprice'], 0, '.', '.') . ' VNĐ</span></p>
                </div>
                <div>
                    <p><strong>Địa chỉ nhận hàng:</strong> ' . $order[0]['address'] . '</p>
                    <p><strong>Số điện thoại:</strong>+84' . $order[0]['phone'] . '</p>
                    <p><strong>Trạng thái đơn hàng:</strong> ' . $order[0]['status'] . '</p>
                    <p><strong>Trạng thái thanh toán:</strong> <span style="color:rgb(5, 4, 4);">' . $order[0]['payment_status'] . '</span></p>
                </div>
                </div>
        
                <p><strong>Chi tiết đơn hàng:</strong></p>
                <table class="product-list">
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Giá</th>
                        <th>Tạm tính</th>
                    </tr>';

            foreach ($order as $product) {
                foreach ($product['name_list'] as $key => $product_name) {
                    $price = $product['price_list'][$key];
                    $quantity = $product['quantity_list'][$key];

                    $total_price = $price * $quantity;

                    $mail->Body .= '
                        <tr>
                            <td>' . $product_name . '</td>
                            <td>' . $product['quantity_list'][$key] . '</td>
                            <td>' . number_format($product['price_list'][$key], 0, ',', '.') . ' đ</td>
                            <td>' . number_format($total_price, 0, ',', '.') . 'đ</td>
                        </tr>';
                }
            }

            $mail->Body .= '
                </table>
                <p>Cảm ơn bạn đã mua hàng tại cửa hàng của chúng tôi.</p>
            </div>
        
            </body>
            </html>';

            $mail->send();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
