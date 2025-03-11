<?php

namespace app\Controllers\admin;

include_once './core/viewer.php';
include_once './app/Models/CategoryModel.php';

use core\BaseController;
use app\Models\CategoryModel;

class CategoryController extends BaseController
{
    private $cate;

    public function __construct()
    {
        parent::__construct('admin');
        $this->cate = new CategoryModel();
    }

    public function index()
    {
        $listcate = $this->cate->getAllCategories();
        // var_dump($listcate);
        // return;
        $categoryNames = [];
        foreach ($listcate as $cat) {
            $categoryNames[$cat['id']] = $cat['name'];
        }
        $this->render('category', ['listcate' => $listcate, 'categoryNames' => $categoryNames]);
    }

    public function search()
    {
        $key = $_POST['key'];
        $listcate = $this->cate->searchCategories($key);
        $categoryNames = [];
        foreach ($listcate as $cat) {
            $categoryNames[$cat['id']] = $cat['name'];
        }
        $this->render('category', ['listcate' => $listcate, 'categoryNames' => $categoryNames]);
    }

    private function notify($type, $message)
    {
        $_SESSION[$type] = $message;
        header("location: /php2/ASMC/admin/category");
        exit;
    }


    public function insert()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['inserC'])) {
            $name = $_POST['nameCate'];
            $id_parent = $_POST['parentCate'];
            $is_default = (int)$_POST['is_default'];
            //var_dump($_POST);
            $insertSuccess = $this->cate->insertCategories($name, $id_parent, $is_default);
            if ($insertSuccess) {
                $this->notify('success', 'Thêm danh mục thành công');
            } else {
                $this->notify('error', 'Thêm danh mục thất bại vui lòng thử lại');
            }
        }
    }

    public function delete($id)
    {
        $deletesuccess = $this->cate->deleteCategory($id);
        if ($deletesuccess) {
            $this->notify('success', 'Xoá danh mục thành công');
        } else {
            $this->notify('error', 'Xoá danh mục thất bại vui lòng thử lại');
        }
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['editC'])) {
            $newName = $_POST['nameCate'];
            $is_default = $_POST['is_default'];
            $id_parent = $_POST['parentCate'];

            $currentcate = $this->cate->getCategoryById($id);
            $nameChanged = $currentcate['name'] !== $_POST['nameCate'];

            if ($nameChanged && $this->cate->checkname($newName)) {
                $this->notify('error', 'Tên danh mục đã tồn tại!');
            }
            $updatesuccess = $this->cate->editcategory($id, $newName, $id_parent, $is_default);

            if ($updatesuccess) {
                $this->notify('success', 'Sửa danh mục thành công');
            } else {
                $this->notify('error', 'Sửa danh mục thất bại vui lòng thử lại');
            }
        }
    }
}
