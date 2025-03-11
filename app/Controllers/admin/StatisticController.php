<?php

namespace app\Controllers\admin;

include_once './core/viewer.php';
include_once './app/Models/StatisticModel.php';

use core\BaseController;
use app\Models\StatisticModel;

class StatisticController extends BaseController
{
    private $statistic;

    public function __construct()
    {
        parent::__construct('admin');
        $this->statistic = new StatisticModel();
    }

    public function index()
    {
        $startDate = $_POST['date_from'] ?? date('Y-m-01'); // Mặc định từ đầu tháng
        $endDate = $_POST['date_to'] ?? date('Y-m-d'); // Mặc định đến hôm nay
        $group_by = $_POST['group_by'] ?? null;


        $topSellingProducts = $this->statistic->getTopSellingProducts($startDate, $endDate);
        $orderStatusStats = $this->statistic->getOrderStatusStatistics($startDate, $endDate);
        $topSellingCategory = $this->statistic->getCategorySellingRatio();

        $data = $this->statistic->getRevenueStatistics($startDate, $endDate, $group_by);
        // echo json_encode($data);
        // exit; 

        $this->render('dashboard', [
            'topSellingProducts' => $topSellingProducts,
            'orderStatusStats' => $orderStatusStats,
            'data' => $data,
            'topSellingCategory' => $topSellingCategory
        ]);
    }
}
