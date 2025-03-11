<?php
namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategoryModel;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = CategoryModel::where('id_parent', 0)
        ->where('is_default', 0 )
        ->with('children')->get();
        $ChildrenC = []; // Khởi tạo mảng tránh lỗi

        foreach ($categories as $category) {
            $ChildrenC[$category->id] = CategoryModel::where('id_parent', $category->id)->get();
        }


        return view('client.header', compact('categories', 'ChildrenC'));
    }
}
?>
