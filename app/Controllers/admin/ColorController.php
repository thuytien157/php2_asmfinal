<?php

namespace app\Controllers\admin;

include_once './core/viewer.php';
include_once './app/Models/CategoryModel.php';

use core\BaseController;
use app\Models\ProductModel;
use app\Models\CategoryModel;

class ColorController extends BaseController
{
    private $color;

    public function __construct()
    {
        parent::__construct('admin');
        $this->color = new ProductModel();
    }

    public function index()
    {
        $colors = $this->color->getColor();
        $this->render('colors', ['colors' => $colors]);
    }

    public function insert()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['inserColor'])) {
            $name = $_POST['name'];
            $hex_code = $_POST['hex_code'];
            //var_dump($_POST);
            $insertSuccess = $this->color->insertcolors($name, $hex_code);
            if ($insertSuccess) {
                $_SESSION['success'] = "Thêm màu thành công";
            } else {
                $_SESSION['error'] = "Thêm màu không thành công";
            }

            header('location: /php2/ASMC/admin/color');
            exit;
        }
    }


    public function delete($id)
    {
        $deleteSuccess = $this->color->deletecolors($id);
        if ($deleteSuccess) {
            $_SESSION['success'] = "Xoá màu thành công";
        } else {
            $_SESSION['error'] = "Xoá màu không thành công";
        }

        header('location: /php2/ASMC/admin/color');
        exit;
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['editColor'])) {
            $name = trim($_POST['name']);
            $hex_code = trim($_POST['hex_code']);
    
            $currentColor = $this->color->getColorById($id);
            
            if ($currentColor[0]['name'] == $name && $currentColor[0]['hex_code'] == $hex_code) {
                $_SESSION['success'] = "Không có thay đổi nào được thực hiện!";
                header('location: /php2/ASMC/admin/color');
                exit;
            }
    
            $existingColor = $this->color->getColorByHexOrName($name, $hex_code);
            if (!empty($existingColor) && $existingColor['id'] != $id) {
                $_SESSION['error'] = "Màu này đã tồn tại!";
                header('location: /php2/ASMC/admin/color');
                exit;
            }
            // var_dump($currentColor);
            // var_dump($existingColor);
            // exit;
            $updateSuccess = $this->color->editColor($id, $name, $hex_code);
            if ($updateSuccess) {
                $_SESSION['success'] = "Sửa màu thành công!";
            } else {
                $_SESSION['error'] = "Lỗi khi sửa màu!";
            }
    
            header('location: /php2/ASMC/admin/color');
            exit;
        }
    }
    

}
