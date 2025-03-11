<?php

namespace app\Controllers\admin;

include_once './core/viewer.php';
include_once './app/Models/CategoryModel.php';

use core\BaseController;
use app\Models\ProductModel;
use app\Models\CategoryModel;

class ProductController extends BaseController
{
    private $product;
    private $cate;

    public function __construct()
    {
        parent::__construct('admin');
        $this->product = new ProductModel();
        $this->cate = new CategoryModel();
    }

    public function index($id_category = null, $key = null, $status = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $id_category = $_POST['selectedCategory'] ?? '';
            $key = $_POST['key'] ?? '';
            $status = $_POST['selectstatus'] ?? '';

            if (!empty($id_category)) {
                $products = $this->product->getProductByCAdmin($id_category);
            } elseif (!empty($key)) {
                $products = $this->product->searchProductsAdmin($key);
            } elseif (!empty($status)) {
                $products = $this->product->getProductsByStatus($status);
            } else {
                $products = $this->product->getProductsAdmin();
            }
        } else {
            $products = $this->product->getProductsAdmin();
        }

        $categories = $this->cate->getAllCategories();
        $listcolor = $this->product->getColor();

        foreach ($products as &$product) {
            $product['detail_list'] = explode(',', $product['detail']);
        }

        $this->render('product', [
            'products' => $products,
            'categories' => $categories,
            'id_category' => $id_category,
            'status' => $status,
            'listcolor' => $listcolor
        ]);
    }


    private function notify($type, $message)
    {
        $_SESSION[$type] = $message;
        header("location: /php2/ASMC/admin/product");
        exit;
    }
    public function insert()
    {
        $categories = $this->cate->getAllCategories();
        $listcolor = $this->product->getColor();

        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['insertP'])) {
            $pname = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? '';
            $discount = $_POST['discount'] ?? '';
            $id_category = $_POST['category_name'] ?? '';
            $description = $_POST['description'] ?? '';
            $colorn = $_POST['colorn'] ?? '';
            if (empty($pname) || empty($price) || empty($id_category)) {
                $this->notify('error', 'Vui lòng nhập đủ thông tin cần thiết');
                return;
            }

            if ((float)$price <= 0) {
                $this->notify('error', 'Giá sản phẩm phải là số lớn hơn 0');
                return;
            }

            if (!empty($discount) && (!is_numeric($discount) || (float)$discount < 0)) {
                $this->notify('error', 'Giảm giá phải là số không âm');
                return;
            }

            $uploadDir = './app/public/img/products/';


            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if ($_FILES['main_image']['size'] > 5 * 1024 * 1024) {
                $this->notify('error', 'Dung lượng ảnh tải lên quá lớn');
                return;
            }

            $main_imageN = '';
            if (!empty($_FILES['main_image']['name'])) {

                //lấy phần đuôi file
                $ext = pathinfo($_FILES['main_image']['name'], PATHINFO_EXTENSION);
                if (in_array($ext, $allowedExtensions)) {
                    $main_imageN = time() . basename($_FILES['main_image']['name']);
                    move_uploaded_file($_FILES['main_image']['tmp_name'], $uploadDir . $main_imageN);
                } else {
                    $this->notify('error', 'Định dạng file ảnh không hợp lệ');
                    return;
                }
            }

            foreach ($_FILES['thumbnails']['size'] as $size) {
                if ($size > 5 * 1024 * 1024) {
                    $this->notify('error', 'Dung lượng ảnh tải lên quá lớn');
                    return;
                }
            }
            if (count($_FILES['thumbnails']['name']) > 5) {
                $this->notify('error', 'Chỉ được tải lên tối đa 5 ảnh thumbnail!');
                return;
            }

            $thumbnails = [];
            if (!empty($_FILES['thumbnails']['name'][0])) {
                foreach ($_FILES['thumbnails']['name'] as $key => $name) {
                    // lấy đuôi file
                    $ext = pathinfo($name, PATHINFO_EXTENSION);
                    if (in_array($ext, $allowedExtensions)) {
                        $fileName = time() . basename($name);
                        move_uploaded_file($_FILES['thumbnails']['tmp_name'][$key], $uploadDir . $fileName);
                        $thumbnails[] = $fileName;
                    } else {
                        $this->notify('error', 'Có ảnh thumbnail không hợp lệ');
                    }
                    unset($_FILES['thumbnails']['tmp_name'][$key]);
                }
            }

            try {
                $this->product->insertProducts($pname, $price, $discount, $id_category, $description, $main_imageN, $thumbnails, $colorn);
                $this->notify('success', 'Thêm sản phẩm thành công');
            } catch (\Exception $th) {
                $this->notify('error', 'Thêm sản phẩm không thành công');
                echo "Lỗi: " . $th->getMessage();
            }
        }
        $this->render('product_insert', ['categories' => $categories, 'listcolor' => $listcolor]);
    }

    public function delete($id)
    {
        $pro = $this->product->getDetailProductQuery($id);
        $checkProduct = $this->product->checkProductsOrder($id);
        if ($checkProduct) {
            $this->notify('error', 'Sản phẩm này có lịch sử đơn hàng không thể xoá! Bạn có thể ẩn sản phẩm này thay vì xoá');
        }
        foreach ($pro as $product) {
            $imagePath = './app/public/img/products/' . $product['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $result = $this->product->deleteProducts($id);
        if ($result) {
            $this->notify('success', 'Xoá thành công');
        } else {
            $this->notify('error', 'Xoá không thành công');
        }
    }

    public function status($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['lock'])) {
            $currenstatus = $_POST['lock'] ?? '';
            $newstatus = $currenstatus == 'active' ? 'inactive' : 'active';
            $setsuccess = $this->product->setStatus($id, $newstatus);
            if ($setsuccess) {
                $this->notify('success', 'Thành công');
            } else {
                $this->notify('error', 'Không thành công vui lòng thử lại');
            }
        }
    }

    public function edit($id)
    {
        $pro = $this->product->getDetailProductQuery($id);
        $listcolor = $this->product->getColor();

        
        $imagedetail = array_slice(array_column($pro, 'image'), 1);
        // echo "<pre>";
        // var_dump($imagedetail); exit;
        // echo 'pre';
        if (!$pro || empty($pro)) {
            $this->notify('error', 'Sản phẩm không tồn tại!');
            return;
        }
    
        $categories = $this->cate->getAllCategories();
        $uploadDir = './app/public/img/products/';
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['editP'])) {
            $pname = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? '';
            $discount = $_POST['discount'] ?? '';
            $id_category = $_POST['category_name'] ?? '';
            $description = $_POST['description'] ?? '';
            $colorn = $_POST['colorn'] ?? '';
    
            if (empty($pname) || empty($price) || empty($id_category)) {
                $this->notify('error', 'Vui lòng nhập đủ thông tin cần thiết');
                return;
            }
    
            if ((float)$price <= 0) {
                $this->notify('error', 'Giá sản phẩm phải là số lớn hơn 0');
                return;
            }
    
            if (!empty($discount) && (!is_numeric($discount) || (float)$discount < 0)) {
                $this->notify('error', 'Giảm giá phải là số không âm');
                return;
            }
    
            // Xử lý ảnh chính (main_image)
            $main_imageN = $pro[0]['image']; // Giữ ảnh cũ mặc định
            if (!empty($_FILES['main_image']['name'])) {
                if ($_FILES['main_image']['size'] > 5 * 1024 * 1024) {
                    $this->notify('error', 'Dung lượng ảnh tải lên quá lớn');
                    return;
                }
    
                $ext = pathinfo($_FILES['main_image']['name'], PATHINFO_EXTENSION);
                if (!in_array($ext, $allowedExtensions)) {
                    $this->notify('error', 'Định dạng file ảnh không hợp lệ');
                    return;
                }
    
                // Xóa ảnh cũ nếu có
                if (!empty($pro[0]['image'])) {
                    $oldImagePath = $uploadDir . $pro[0]['image'];
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
    
                $main_imageN = time() . basename($_FILES['main_image']['name']);
                move_uploaded_file($_FILES['main_image']['tmp_name'], $uploadDir . $main_imageN);
            }
    
            $oldThumbnails = $imagedetail; 
            $newThumbnails = [];
    
            if (!empty($_FILES['thumbnails']['name'][0])) {
                foreach ($_FILES['thumbnails']['name'] as $key => $name) {
                    if ($_FILES['thumbnails']['size'][$key] > 5 * 1024 * 1024) {
                        $this->notify('error', 'Dung lượng ảnh tải lên quá lớn');
                        return;
                    }
    
                    $ext = pathinfo($name, PATHINFO_EXTENSION);
                    if (!in_array($ext, $allowedExtensions)) {
                        $this->notify('error', 'Có ảnh thumbnail không hợp lệ');
                        return;
                    }
    
                    $fileName = time() . basename($name);
                    move_uploaded_file($_FILES['thumbnails']['tmp_name'][$key], $uploadDir . $fileName);
                    $newThumbnails[] = $fileName;
                }
    
                foreach ($oldThumbnails as $oldThumb) {
                    $oldThumbPath = $uploadDir . $oldThumb;
                    if (file_exists($oldThumbPath)) {
                        unlink($oldThumbPath);
                    }
                }
            } else {
                $newThumbnails = $oldThumbnails;
                
            }
            try {
                $this->product->updateProduct($id, $pname, $price, $discount, $id_category, $description, $colorn, $main_imageN, $newThumbnails);
                $this->notify('success', 'Cập nhật sản phẩm thành công');
            } catch (\Exception $th) {
                $this->notify('error', 'Cập nhật sản phẩm không thành công');
                echo "Lỗi: " . $th->getMessage();
            }
        }
    
        $this->render('product_edit', [
            'categories' => $categories,
            'pro' => $pro[0],
            'imagedetail' => $imagedetail,
            'listcolor' => $listcolor
        ]);
    }
    
}
