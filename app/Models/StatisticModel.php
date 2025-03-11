<?php
namespace app\Models;

use core\ConnectModel;
class StatisticModel extends ConnectModel {

    public function getTopSellingProducts($startDate, $endDate) {
        $sql = "SELECT p.id as product_id, p.name, SUM(od.quantity) as total_sold, MAX(o.orderdate) as latest_order_date
                FROM orders_detail od
                JOIN product p ON od.id_product = p.id
                JOIN orders o ON od.id_order = o.id
                WHERE o.orderdate BETWEEN :startDate AND :endDate
                GROUP BY p.id, p.name
                ORDER BY total_sold DESC
                LIMIT 5";
        $params = [
            ':startDate' => $startDate,
            ':endDate' => $endDate,
        ];
        return $this->db->getAll($sql, $params);
    }
    

    public function getOrderStatusStatistics($startDate, $endDate) {
        $sql = "SELECT status, COUNT(*) as total FROM orders 
                WHERE orderdate BETWEEN :startDate AND :endDate 
                GROUP BY status";
        $params = [
            ':startDate' => $startDate,
            ':endDate' => $endDate,
        ];
        return $this->db->getAll($sql, $params);
    }
    

    public function getRevenueStatistics($startDate, $endDate, $groupBy) {
        $dateFormat = match ($groupBy) {
            'month' => "DATE_FORMAT(orderdate, '%Y-%m')",
            'year'  => "YEAR(orderdate)",
            default => "DATE(orderdate)"
        };
    
        $sql = "SELECT $dateFormat as date, SUM(totalprice) as revenue
                FROM orders
                WHERE orderdate BETWEEN :startDate AND :endDate
                GROUP BY date
                ORDER BY date ASC";
        $params = [
            ':startDate' => $startDate,
            ':endDate' => $endDate,
        ];
        return $this->db->getAll($sql, $params);
    }
    public function getCategorySellingRatio(){
        $sql = "SELECT c.name, SUM(od.quantity * od.price) AS total_revenue,
                    (SUM(od.quantity * od.price) / (SELECT SUM(quantity * price) FROM orders_detail)) * 100 AS revenue_percentage
                FROM orders_detail od
                JOIN product p ON od.id_product = p.id
                JOIN categories c ON p.id_category = c.id
                GROUP BY c.name
                ORDER BY total_revenue DESC;";
        return $this->db->getAll($sql);
    }
    

    
}


?>