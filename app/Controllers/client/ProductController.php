<?php

namespace app\Controllers\client;

include_once './core/viewer.php';
include_once './app/Models/ProductModel.php';

use core\BaseController;
use app\Models\ProductModel;


class ProductController extends BaseController
{
    private $product;

    public function __construct()
    {
        parent::__construct();
        $this->product = new ProductModel();
    }

    public function index($categoryid = null)
    {
        if ($categoryid) {
            $listpro = $this->product->getProductByC($categoryid);
        } else {
            $listpro = $this->product->getProduct();
        }
        $listcolor = $this->product->getColor();

        $this->render('product', ['listpro' => $listpro, 'listcolor' => $listcolor]);
        //var_dump($listpro);
    }

    public function detail($id)
    {
        $listprodetail = $this->product->getProductDetail($id);

        if (!$listprodetail) {
            echo "Sản phẩm không tồn tại.";
            exit();
        }

        $listsize = $this->product->getSize();
        //$listcolor = $this->product->getColor();
        $images = array_column($listprodetail, 'image');

        $this->render('product_detail', [
            'listprodetail' => $listprodetail,
            'listsize' => $listsize,
            'images' => $images,
        ]);
        // echo $images[0];
        // echo "<pre>";
        // var_dump($images);
        // echo "<pre>";

    }

    public function search()
    {
        $key = $_POST['key'];
        // print_r($_POST);
        // return;
        $listpro = $this->product->searchProducts($key);
        $this->render('product', ['listpro' => $listpro]);
    }

    public function filter()
    {
        header('Content-Type: application/json');

        $categories = isset($_POST['category']) ? $_POST['category'] : [];
        $min_price = isset($_POST['min_price']) ? $_POST['min_price'] : 0;
        $max_price = isset($_POST['max_price']) ? $_POST['max_price'] : 10000000;
        $color = isset($_POST['color']) ? $_POST['color'] : null;
        // $_SESSION['error'] = var_dump($color);
        // header('location: /php/ASMC');
        // exit;
        $products = $this->product->filterProduct($categories, $color, $min_price, $max_price);

        echo json_encode($products);
    }
}
