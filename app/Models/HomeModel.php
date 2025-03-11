<?php
namespace app\Models;
use core\ProductBaseModel;

class HomeModel extends ProductBaseModel { 
    public function getProduct() {
        return $this->getALLProductsQuery();
    }
    public function getProductN() {
        return $this->getProductByDate();
        // $t= $this->getALLProductsQuery();
        // print_r($t);
    
    }

    public function getProductS() {
        return $this->getProductSell();
        // $t= $this->getALLProductsQuery();
        // print_r($t);
    
    }

    public function getProductSale() {
        return $this->getProductByDiscount();
        // $t= $this->getALLProductsQuery();
        // print_r($t);
    
    }
}

?>