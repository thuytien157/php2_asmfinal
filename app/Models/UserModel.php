<?php

namespace app\Models;

include_once './core/Connect.php';

use core\ConnectModel;
use DateTime;

class UserModel extends ConnectModel
{
    public function insertUser($username, $password, $email, $fullname, $address, $phone, $role)
    {
        try {
            $sql = "INSERT INTO user (username, password, email, fullname, address, phone, role)
            VALUES(:username, :password, :email, :fullname, :address, :phone, :role);";
            $params = [
                ':username' => $username,
                ':password' => $password,
                ':email' => $email,
                ':fullname' => $fullname,
                ':address' => $address,
                ':phone' => $phone,
                ':role' => $role
            ];
            // $sql = "INSERT INTO users (username, password, email, fullname, address, phone, role) VALUES (?, ?, ?, ?, ?, ?, ?)";
//echo "SQL Query: $sql - Username: $username"; // Debug 

            $results = $this->db->insert($sql, $params);
            return $results;
        } catch (\Exception $th) {
            $_SESSION['error'] = "Lỗi SQL: " . $th->getMessage();
            return false;
        }
    }

    public function selectUser($username)
    {
        $sql = "SELECT * FROM user WHERE username = :username;";
        return $this->db->getOne($sql, ['username' => $username]);
    }

    public function selectUserEmail($email)
    {
        $sql = "SELECT * FROM user WHERE email = :email;";
        return $this->db->getOne($sql, ['email' => $email]);
    }

    public function selectUserPhone($phone)
    {
        $sql = "SELECT * FROM user WHERE phone = :phone;";
        return $this->db->getOne($sql, ['phone' => $phone]);
    }

    public function updateUser($fullname, $phone, $email, $address, $user_id)
    {
        try {
            $sql = "UPDATE user SET fullname = :fullname, phone = :phone, email = :email, address = :address WHERE id = :user_id;";
            $params = [
                ':fullname' => $fullname,
                ':phone' => $phone,
                ':email' => $email,
                ':address' => $address,
                ':user_id' => $user_id
            ];
            $results = $this->db->update($sql, $params);
            return $results;
        } catch (\Exception $th) {
            $_SESSION['error'] = "Lỗi SQL: " . $th->getMessage();
            echo "Lỗi SQL: " . $th->getMessage();
            return false;
        }
    }

    public function ChangePass($password,$id){
        try{
            $sql = "UPDATE user SET password = :password WHERE id = :id;";
            $this->db->update($sql, [':password' => $password,':id' => $id]);
            return true;
        }catch(\Exception $th){
            echo $th->getMessage();
            return false;
        }
        
    }

    public function selectAllUser()
    {
        $sql = "SELECT * FROM user";
        return $this->db->getAll($sql);
    }

    public function checkUserOrder($id)
    {
        $sql = "SELECT COUNT(*) as count FROM orders WHERE id_user = :id;";
        $check = $this->db->getOne($sql, [':id' => $id]);
        return $check['count'] > 0;
    }

    // public function checkUserEmail($email){
    //     $sql = "SELECT COUNT(*) as count FROM users WHERE email = :email;";
    //     $check = $this->db->getOne($sql, [':email' => $email]);
    //     return $check['count'] > 0;
    // }

    public function deleteUser($id)
    {
        try {
            $sql = "DELETE FROM user WHERE id = :id";
            $this->db->delete($sql, [':id' => $id]);
            return true;
        } catch (\Exception $th) {
            echo $th->getMessage();
            return false;
        }
    }

    public function setStatus($id, $status)
    {
        try {
            $sql = "UPDATE user SET status = :status WHERE id = :id";
            $this->db->update($sql, [':id' => $id, ':status' => $status]);
            return true;
        } catch (\Exception $th) {
            echo $th->getMessage();
            return false;
        }
    }

    public function setUser($id, $role)
    {
        try {
            $sql = "UPDATE user SET role = :role WHERE id = :id";
            $this->db->update($sql, [':id' => $id, ':role' => $role]);
            return true;
        } catch (\Exception $th) {
            echo $th->getMessage();
            return false;
        }
    }

    public function getUserByRole($role)
    {
        $sql = "SELECT * FROM user WHERE role = :role;";
        return $this->db->getAll($sql, [':role' => $role]);
    }

    public function searchUserQuery($key)
    {
        $sql = "SELECT * 
                FROM user
                WHERE username LIKE :key";
        return $this->db->getAll($sql, ['key' => '%' . $key . '%']);
    }

    public function updateaCode($reset_code, $reset_expiry, $id)
    {
        try {

            $sql = "UPDATE user SET reset_code = :reset_code, reset_expiry = :reset_expiry WHERE id = :id";
            $params = [
                ':reset_code' => $reset_code,
                ':reset_expiry' => $reset_expiry,
                ':id' => $id
            ];

            $this->db->update($sql, $params);
            return true;
        } catch (\Exception $th) {
            $_SESSION['error'] = $th->getMessage();
            return false;
        }
    }

    public function deleteVerificationCode($email)
    {
        $sql = "UPDATE user SET reset_code = NULL, reset_expiry = NULL WHERE email = :email";
        return $this->db->update($sql, [':email' => $email]);
    }

    public function checkVerificationCode($email, $reset_code)
    {
        $sql = "SELECT reset_expiry FROM user WHERE email = :email AND reset_code = :reset_code";
        $result = $this->db->getAll($sql, [':email' => $email, ':reset_code' => $reset_code]);

        if (!empty($result) && isset($result[0]["reset_expiry"])) {
            $current_time = new DateTime();
            $expiry_time = new DateTime($result[0]["reset_expiry"]);

            if ($current_time > $expiry_time) {
                $this->deleteVerificationCode($email);
                return false;
            }
            return true;
        }
        return false;
    }

    public function updatePassWord($password,$email, $reset_code){
        try{
            $sql = "UPDATE user SET password = :password WHERE email = :email AND reset_code = :reset_code;";
            $this->db->update($sql, [':password' => $password,':email' => $email, ':reset_code' => $reset_code]);
            return true;
        }catch(\Exception $th){
            echo $th->getMessage();
            return false;
        }
        
    }

    public function selectUserById($id){
        $sql = "SELECT * FROM user WHERE id = :id;";
        return $this->db->getAll($sql, [':id' => $id]);
    }



}
