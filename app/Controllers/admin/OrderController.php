<?php

namespace app\Controllers\admin;

include_once './core/viewer.php';
include_once './app/Models/OrderModel.php';
require_once './vendor/autoload.php';

use core\BaseController;
use app\Models\OrderModel;
use FPDF;

class OrderController extends BaseController
{
    private $order;

    public function __construct()
    {
        parent::__construct('admin');
        $this->order = new OrderModel();
    }

    public function index()
    {
        $orders = $this->order->getAllOrder();
        $this->render('order', ['orders' => $orders]);
    }

    public function detail($id_order)
    {
        $orders_detail = $this->order->getOrder($id_order);

        foreach ($orders_detail as &$order) {
            $order['name_list'] = explode(',', $order['pname']);
            $order['image_list'] = explode(',', $order['pimage']);
            $order['price_list'] = explode(',', $order['price']);
            $order['quantity_list'] = explode(',', $order['quantity']);
        }

        $this->render('orderDetail', ['orders_detail' => $orders_detail]);
    }

    public function setStatus($id_order)
    {
        $status = $_POST['status'] ?? '';
        $updateSuccess = $this->order->updateStatus($id_order, $status);

        if ($updateSuccess) {
            $_SESSION['success'] = "Cập nhật thành công";
        } else {
            $_SESSION['error'] = "không thành công";
        }
        header('location: /php2/ASMC/admin/order');
        exit;
    }

    public function exportInvoice($id_order)
    {
        $orders_detail = $this->order->getOrder($id_order);
        foreach ($orders_detail as &$order) {
            $order['name_list'] = explode(',', $order['pname']);
            $order['price_list'] = explode(',', $order['price']);
            $order['quantity_list'] = explode(',', $order['quantity']);
        }

        $base_height = 50; // Chiều cao cơ bản (thông tin đơn hàng)
        $item_height = 10; // Chiều cao trung bình mỗi sản phẩm
        $num_items = count($orders_detail[0]['name_list']); // Số lượng sản phẩm
        $page_height = $base_height + ($num_items * $item_height); // Chiều cao động theo nội dung

        $pdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => [80, $page_height],
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 5,
            'margin_bottom' => 5,
        ]);

        $pdf->SetAutoPageBreak(true, 0);

        // CSS cho hóa đơn
        $css = "
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');
    
        body { font-family: 'Roboto', 'Arial', sans-serif; font-size: 12px; text-align: center; }
        
        h2 { font-size: 16px; font-weight: bold; margin-bottom: 5px; text-transform: uppercase; }
        
        p { margin: 2px 0; }
        
        .line { border-bottom: 1px dashed black; margin: 8px 0; }
        
        table { width: 100%; font-size: 12px; border-collapse: collapse; margin-top: 5px; }
        
        th { text-align: left; font-weight: bold; border-bottom: 1px solid black; padding: 4px 0; }
        
        td { padding: 4px 0; text-align: left; vertical-align: top; }
        
        td:last-child { text-align: right; }
        
        .total { font-weight: bold; font-size: 14px; text-align: right; margin-top: 10px; }
        
        .thanks { font-size: 12px; font-weight: bold; margin-top: 10px; text-align: center; }
    ";



        $html = '<h2>HÓA ĐƠN BÁN HÀNG</h2>';
        $html .= '<p><strong>Mã đơn:</strong> #' . $orders_detail[0]['order_code'] . '</p>';
        $html .= '<p><strong>Ngày:</strong> ' . date('d/m/Y', strtotime($orders_detail[0]['orderdate'])) . '</p>';
        $html .= '<div class="line"></div>';

        $html .= '<table>';
        $html .= '<tr><th>Sản phẩm</th><th>SL</th><th>Giá</th></tr>';

        $total = 0;
        foreach ($orders_detail as $order_detail) {
            foreach ($order_detail['name_list'] as $key => $product_name) {
                $quantity = $order_detail['quantity_list'][$key];
                $price = $order_detail['price_list'][$key];
                $subtotal = $quantity * $price;
                $total += $subtotal;

                $html .= '<tr>
                        <td>' . $product_name . '</td>
                        <td>' . $quantity . '</td>
                        <td>' . number_format($subtotal, 0, ',', '.') . ' đ</td>
                      </tr>';
            }
        }

        $html .= '</table>';
        $html .= '<div class="line"></div>';
        $html .= '<p class="total">Tổng cộng: ' . number_format($total, 0, ',', '.') . ' đ</p>';
        $html .= '<div class="line"></div>';
        $html .= '<p class="thanks">Cảm ơn quý khách! Hẹn gặp lại.</p>';


        $pdf->WriteHTML($css, 1);
        $pdf->WriteHTML($html, 2);
        $pdf->Output("HoaDon_$id_order.pdf", 'D');
    }
}
