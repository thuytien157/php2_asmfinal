<?php

namespace App\Http\Controllers\client;

use App\Models\ProductBaseModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $productModel;

    public function __construct()
    {
        $this->productModel = new ProductBaseModel();
    }

    public function index(){
        $products = $this->productModel->getProductByDate();

        return view('client.home', compact('products'));
    }
}
