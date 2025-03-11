<?php

namespace app\Models;

use core\ConnectModel;

class OrderModel extends ConnectModel
{

    public function insertOrder($order_code, $totalquantity, $totalprice, $payment_method, $note, $orderDetails, $id_user)
    {
        try {
            $this->db->beginTransaction();

            $sqlOrder = "INSERT INTO orders (order_code, id_user, totalquantity, totalprice, payment_method, note) 
                         VALUES (:order_code, :id_user, :totalquantity, :totalprice, :payment_method, :note);";
            $paramsOrder = [
                ':order_code' => $order_code,
                ':id_user' => $id_user,
                ':totalquantity' => $totalquantity,
                ':totalprice' => $totalprice,
                ':payment_method' => $payment_method,
                ':note' => $note
            ];
            $orderId = $this->db->insert($sqlOrder, $paramsOrder);

            foreach ($orderDetails as $item) {
                $sqlOrderDetail = "INSERT INTO orders_detail (id_order, id_product, price, quantity) 
                                   VALUES (:id_order, :id_product, :price, :quantity);";
                $paramsOrderDetail = [
                    ':id_order' => $orderId,
                    ':id_product' => $item['id'],
                    ':price' => $item['price'],
                    ':quantity' => $item['quantity']
                ];

                $this->db->insert($sqlOrderDetail, $paramsOrderDetail);
            }

            $this->db->commit();
            return [
                true,
                $orderId
            ];
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->db->rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function getOrders($id_user)
    {
        $sql = "SELECT o.id, o.orderdate, o.totalquantity, o.totalprice, o.status, o.payment_method, o.order_code,
                    GROUP_CONCAT(p.name) as name,
                    GROUP_CONCAT(i.image) as image,
                    GROUP_CONCAT(od.price) as price,
                    GROUP_CONCAT(od.quantity) as quantity,
                    u.fullname,
                    u.address,
                    u.phone,
                    u.email,
                    pa.bank_code,
                    pa.payment_status
                FROM orders o
                INNER JOIN orders_detail od ON o.id = od.id_order
                INNER JOIN user u ON u.id = o.id_user
                INNER JOIN product p ON p.id = od.id_product
                INNER JOIN image_detail i ON p.id = i.id_product
                INNER JOIN payments pa ON pa.order_code = o.order_code
                WHERE o.id_user = :id_user AND i.is_main = 1
                GROUP BY o.id
                ORDER BY o.orderdate DESC;
                ";

        return $this->db->getALL($sql, ['id_user' => $id_user]);
    }

    public function updateAddress($id_user, $address)
    {
        try {
            $sql = "UPDATE user SET address = :address WHERE id = :id_user;";
            $params = [
                ':address' => $address,
                ':id_user' => $id_user
            ];
            $results = $this->db->update($sql, $params);
            return $results;
        } catch (\Exception $th) {
            $_SESSION['error'] = "Lỗi SQL: " . $th->getMessage();
            echo "Lỗi SQL: " . $th->getMessage();
            return false;
        }
    }

    public function updateStatus($id, $status)
    {
        try {
            $sql = "UPDATE orders SET status = :status WHERE id = :id;";
            $params = ['id' => $id, ':status' => $status];

            $this->db->update($sql, $params);

            $orderQuery = "SELECT order_code FROM orders WHERE id = :id";
            $orderCode = $this->db->getOne($orderQuery, ['id' => $id]);
            // var_dump($orderCode);
            // return;
            if ($orderCode) {
                if ($status === "Giao thành công") {
                    $sqlPayment = "UPDATE payments SET payment_status = 'Đã thanh toán' WHERE order_code = :order_code;";
                    $this->db->update($sqlPayment, ['order_code' => $orderCode['order_code']]);
                }else if($status === "Đơn thất bại"){
                    $sqlPayment = "UPDATE payments SET payment_status = 'Chưa thanh toán' WHERE order_code = :order_code;";
                    $this->db->update($sqlPayment, ['order_code' => $orderCode['order_code']]);
                }
            }
        return true;
        } catch (\Exception $th) {
            $_SESSION['error'] = "Lỗi SQL: " . $th->getMessage();
            echo "Lỗi SQL: " . $th->getMessage();
            return false;
        }
    }

    public function updateStatusorder_code($order_code, $status)
    {
        try {
            $sql = "UPDATE orders SET status = :status WHERE order_code = :order_code;";
            $results = $this->db->update($sql, ['order_code' => $order_code, ':status' => $status]);
            return $results;
        } catch (\Exception $th) {
            $_SESSION['error'] = "Lỗi SQL: " . $th->getMessage();
            echo "Lỗi SQL: " . $th->getMessage();
            return false;
        }
    }

    public function getAllOrder()
    {
        $sql = "SELECT o.*, u.username, pa.payment_status
                FROM orders o
                INNER JOIN user u
                ON u.id = o.id_user
                INNER JOIN payments pa
                ON pa.order_code = o.order_code
                ORDER BY o.id DESC;";
        return $this->db->getAll($sql);
    }

    public function getOrder($id_order)
    {
        $sql = "SELECT o.*, od.*, o.id as id_order, u.username, u.address, u.fullname, u.phone, pa.payment_status, pa.bank_code, o.payment_method, u.email,
                GROUP_CONCAT(p.name) AS pname, 
                GROUP_CONCAT(i.image) AS pimage,
                GROUP_CONCAT(od.price) AS price,
                GROUP_CONCAT(od.quantity) AS quantity
                FROM orders o
                INNER JOIN orders_detail od ON o.id = od.id_order
                INNER JOIN user u ON u.id = o.id_user
                INNER JOIN product p ON p.id = od.id_product
                INNER JOIN payments pa ON pa.order_code = o.order_code
                INNER JOIN image_detail i ON p.id = i.id_product AND i.is_main = 1
                WHERE o.id = :id_order
                GROUP BY o.id;";
        return $this->db->getAll($sql, ['id_order' => $id_order]);
    }

    public function insertPayment($order_code, $transaction_no, $amount, $bank_code, $payment_status)
{
    try {
        $sql = "INSERT INTO payments (order_code, transaction_no, amount, bank_code, payment_status) 
                VALUES (:order_code, :transaction_no, :amount, :bank_code, :payment_status)";

        $params = [
            ':order_code' => $order_code,
            ':transaction_no' => $transaction_no,
            ':amount' => $amount,
            ':bank_code' => $bank_code,
            ':payment_status' => $payment_status
        ];

        $this->db->insert($sql, $params);

        // Nếu thanh toán thành công hoặc là thanh toán tiền mặt, cập nhật trạng thái là "Chờ xác nhận"
        if ($payment_status === "Đã thanh toán" || $payment_status === "Chưa Thanh toán") {
            $this->updateStatusorder_code($order_code, "Chờ xác nhận");
        } else {
            $this->updateStatusorder_code($order_code, "Đơn thất bại");
        }
        
        return true;
    } catch (\Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        return false;
    }
}


    public function getOrderByCode($order_code){
        $sql = "SELECT o.*, od.*, o.id as id_order, u.username, u.address, u.fullname, u.phone, pa.payment_status, pa.bank_code, o.payment_method, u.email,
                GROUP_CONCAT(p.name) AS pname, 
                GROUP_CONCAT(i.image) AS pimage,
                GROUP_CONCAT(od.price) AS price,
                GROUP_CONCAT(od.quantity) AS quantity
                FROM orders o
                INNER JOIN orders_detail od ON o.id = od.id_order
                INNER JOIN user u ON u.id = o.id_user
                INNER JOIN product p ON p.id = od.id_product
                INNER JOIN payments pa ON pa.order_code = o.order_code
                INNER JOIN image_detail i ON p.id = i.id_product AND i.is_main = 1
                WHERE o.order_code = :order_code
                GROUP BY o.id;";

        return $this->db->getAll($sql, ['order_code' => $order_code]);

    }
}
