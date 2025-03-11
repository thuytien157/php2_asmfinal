<?php
namespace app\Models;
include_once './core/Connect.php';
use core\ConnectModel;

class CategoryModel extends ConnectModel{
    public function getParentCategories(){
        $sql = "SELECT id, name FROM categories
                WHERE id_parent = 0 AND is_default = 0;";
        return $this->db->getAll($sql);
    }

    public function getChildCategories($parentId) {
        $sql = "SELECT id, name FROM categories 
                WHERE id_parent = :parentId;";
        return $this->db->getAll($sql, ['parentId' => $parentId]);
    }

    public function getAllCategories(){
        $sql = "SELECT * FROM categories order by id_parent ASC , is_default DESC;";
        return $this->db->getALL($sql);
    }

    public function searchCategories($key){
        $sql = "SELECT * FROM categories
                WHERE name LIKE :key";
        return $this->db->getAll($sql, ['key' => '%' . $key . '%']);
    
    }

    public function checkname($name){
        $sqlCheck = "SELECT COUNT(*) as count FROM categories WHERE name = :name;";
        $check = $this->db->getOne($sqlCheck, [':name' => $name]);
        if ($check['count'] > 0) {
            $_SESSION['error'] = "Tên danh mục đã tồn tại!";
            header('location: /php2/ASMC/admin/category');
            exit;
        }
        return $check['count'] > 0;

    }

    private function updateProducts($newDefaultId){
        $sqlCurrentDefault = "SELECT id FROM categories WHERE is_default = 1;";
        $currentDefault = $this->db->getOne($sqlCurrentDefault);

        if ($currentDefault) {
            $currentDefaultId = $currentDefault['id'];
            $sqlUpdateProducts = "UPDATE product SET id_category = :new_id WHERE id_category = :old_id;";
            $sqlUpdateSubcategories = "UPDATE categories SET id_parent = :new_id WHERE id_parent = :old_id;";
            $this->db->update($sqlUpdateProducts, [':new_id' => $newDefaultId, ':old_id' => $currentDefaultId]);
            $this->db->update($sqlUpdateSubcategories, [':new_id' => $newDefaultId, ':old_id' => $currentDefaultId]);
        }

        $sqlResetDefault = "UPDATE categories SET is_default = 0;";
        $this->db->update($sqlResetDefault);

        $sqlSetNewDefault = "UPDATE categories SET is_default = 1 WHERE id = :id;";
        $this->db->update($sqlSetNewDefault, [':id' => $newDefaultId]);
    }

    public function insertCategories($name, $id_parent, $is_default){
        try {
            $this->db->beginTransaction();
    
            if ($is_default == 1 && $id_parent != 0) {
                $_SESSION['error'] = "Danh mục mặc định không được phép có danh mục cha!";
                header('location: /php2/ASMC/admin/category');
                exit;
            }
    
            $this->checkname($name);
    
            $sqlInsert = "INSERT INTO categories (name, id_parent, is_default) VALUES (:name, :id_parent, :is_default);";
            $params = [
                ':name' => $name,
                ':id_parent' => $id_parent,
                ':is_default' => $is_default
            ];
            $new_id = $this->db->insert($sqlInsert, $params);
    
            if ($is_default == 1) {
                $this->updateProducts($new_id);
            }    
            $this->db->commit();
            return true;
    
        } catch (\Exception $th) {
            $this->db->rollBack();
            echo $th->getMessage();
            return false;
        }
    }
    

    public function editCategory($id, $name, $id_parent, $is_default){
        try {
            $this->db->beginTransaction();
            if ($is_default == 1 && $id_parent != 0) {
                $_SESSION['error'] = "Danh mục mặc định không được phép có danh mục cha!";
                header('location: /php2/ASMC/admin/category');
                exit;
            }
    
            $sqlUpdate = "UPDATE categories 
                          SET name = :name, id_parent = :id_parent, is_default = :is_default 
                          WHERE id = :id;";
            $params = [
                ':name' => $name,
                ':id_parent' => $id_parent,
                ':is_default' => $is_default,
                ':id' => $id,
            ];
            $this->db->update($sqlUpdate, $params);
    
            if ($is_default == 1) {
                $this->updateProducts($id);
            }
    
            $this->db->commit();
            return true;
        } catch (\Exception $th) {
            $this->db->rollBack();
            $_SESSION['error'] = $th->getMessage();
            return false;
        }
    }
    public function deleteCategory($id) {
        try {
            $this->db->beginTransaction();
    
            $sqlID = "SELECT id FROM categories WHERE is_default = 1;";
            $default_id = $this->db->getOne($sqlID);
    
            if ($id == $default_id['id']) {
                $_SESSION['error'] = "Không thể xoá danh mục mặc định";
                header('location: /php2/ASMC/admin/category');
                exit;
            }
    
            $sqlCheck = "SELECT COUNT(*) as count FROM categories WHERE id_parent = :id;";
            $check = $this->db->getOne($sqlCheck, [':id' => $id]);
    
            if ($check['count'] > 0){
                $sqlUpdate = "UPDATE categories SET id_parent = :default_id WHERE id_parent = :id;";
                $params = [
                    ':default_id' => $default_id['id'],
                    ':id' => $id,
                ];
                $this->db->update($sqlUpdate, $params);
            }
    
            $sqlCheckProduct = "SELECT COUNT(*) as count FROM product WHERE id_category = :id;";
            $checkProduct = $this->db->getOne($sqlCheckProduct, [':id' => $id]);
    
            if ($checkProduct['count'] > 0){
                $sqlUpdateProducts = "UPDATE product SET id_category = :default_id WHERE id_category = :id;";
                $this->db->update($sqlUpdateProducts, [
                    ':default_id' => $default_id['id'],
                    ':id' => $id,
                ]);
            }
    
            $sqlDelete = "DELETE FROM categories WHERE id = :id;";
            $this->db->delete($sqlDelete, [':id' => $id]);
    
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            $_SESSION['error'] = $e->getMessage();
            return false;
        }
    }
    
    public function getCategoryById($id){
        $sql = "SELECT * FROM categories WHERE id = :id";
        return $this->db->getOne($sql, [':id'=>$id]);
    }

}
?>