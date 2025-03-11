<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Models\CategoryModel;
use App\Models\ProductBaseModel;

class HomeController extends Controller
{
    private $categoryModel;
    private $productModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->productModel = new ProductBaseModel();
    }

    public function index()
    {
        $categories = CategoryModel::where('id_parent', 0)
            ->where('is_default', 0)
            ->with('children')
            ->get();
        $products = $this->productModel->getProductByDate();

        return view('client.home', compact('categories', 'products'));
    }
}
