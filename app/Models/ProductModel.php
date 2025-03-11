<?php
namespace app\Models;
include_once './core/ProductBaseModel.php';
use core\ProductBaseModel;

class ProductModel extends ProductBaseModel{
    public function getProduct() {
        return $this->getALLProductsQuery();
        // $t= $this->getALLProductsQuery();
        // print_r($t);
    
    }

    

    public function getProductDetail($id) {
        return $this->getDetailProductQuery($id);
    }

    public function getColor() {
        return $this->getColors();
    }

    public function getSize() {
        return $this->getSizes();
    }

    public function getProductByC($categoryid) {
        return $this->getProductByCategoryQuery($categoryid);
    }

    public function getProductByCAdmin($categoryid) {
        return $this->getProductByCategoryAdmin($categoryid);
    }


    public function searchProducts($key) {
        return $this->searchProductQuery($key);
    }

    public function getallProductAdmin(){
        return $this->getProductsAdmin();
    }

    public function insertProducts($name, $price, $discount, $id_category, $description, $main_image, $thumbnails, $colorn){
        return $this->insertProduct($name, $price, $discount, $id_category, $description, $main_image, $thumbnails, $colorn);
    }

    public function deleteProducts($id){
        return $this->deleteProduct($id);
    }

    public function setStatus($id, $status){
        return $this->updateStatus($id, $status);
    }

    public function checkProductsOrder($id){
        return $this->checkProductOrder($id);
    }

    public function updateProducts($id_product, $name, $price, $discount, $id_category, $description, $colorn, $main_image, $thumbnails){
        return $this->updateProduct($id_product, $name, $price, $discount, $id_category, $description, $colorn, $main_image, $thumbnails);
    }
    
    public function searchProductsAdmin($key) {
        return $this->searchProductAdmin($key);
    }

    public function getProductsByStatus($status) {
        return $this->getProductByStatus($status);
    }

    public function filterProduct($categoryId = null, $color = null, $minPrice = null, $maxPrice = null) {
        return $this->filterProducts($categoryId, $color, $minPrice, $maxPrice);
    }

    public function insertcolors($name, $hex_code) {
        return $this->insertcolor($name, $hex_code);
    }

    public function deletecolors($id) {
        return $this->deletecolor($id);
    }

    public function getColorById($id) {
        return $this->getColorsById($id);
    }

    public function getColorsByHexOrName($name, $hex_code) {
        return $this->getColorByHexOrName($name, $hex_code);
    }
    
    public function editColors($id, $name, $hex_code) {
        return $this->editColor($id, $name, $hex_code);
    }

}
?>
