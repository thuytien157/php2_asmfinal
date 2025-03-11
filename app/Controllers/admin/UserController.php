<?php

namespace app\Controllers\admin;

include_once './core/viewer.php';
include_once './app/Models/UserModel.php';

use core\BaseController;
use app\Models\UserModel;

class UserController extends BaseController
{
    private $user;

    public function __construct()
    {
        parent::__construct('admin');
        $this->user = new UserModel();
    }

    public function index($role = null, $key = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $role = $_POST['selectrole'] ?? '';
            $key = $_POST['key'] ?? '';
            if (!empty($role)) {
                $users = $this->user->getUserByRole($role);
            } elseif (!empty($key)) {
                $users = $this->user->searchUserQuery($key);
            } else {
                $users = $this->user->selectAllUser();

            }
        } else {
            $users = $this->user->selectAllUser();
        }

        $this->render('user', ['users' => $users, 'selectedRole' => $role]);
    }


    private function notify($type, $message)
    {
        $_SESSION[$type] = $message;
        header("location: /php2/ASMC/admin/users");
        exit;
    }
    public function delete($id)
    {
        $checkUser = $this->user->checkUserOrder($id);
        // var_dump($checkUser);
        if ($checkUser) {
            $this->notify('error', 'Người dùng này có lịch sử mua hàng không thể xoá! Bạn có thể khoá tài khoản người dùng này thay vì xoá');
        }
        $deletesucess = $this->user->deleteUser($id);
        if ($deletesucess) {
            $this->notify('success', 'Xoá thành công');
        } else {
            $this->notify('error', 'Xoá không thành công vui long thử lại');
        }
        // $this->render('user', ['checkUser' => $checkUser]);

    }

    public function status($id)
    {
        // var_dump($_POST);

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['lock'])) {
            $currenstatus = $_POST['status'] ?? '';
            $newstatus = $currenstatus == 'active' ? 'banned' : 'active';
            $setsuccess = $this->user->setStatus($id, $newstatus);
            if ($setsuccess) {
                $this->notify('success', 'Thành công');
            } else {
                $this->notify('error', 'Không thành công vui lòng thử lại');
            }
        }
    }

    public function insert()
    {
        // var_dump($_POST);
        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['insertU'])) {
            $username = $_POST['username'] ?? '';
            $fullname = $_POST['fullname'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $address = $_POST['address'] ?? '';
            $role = 'admin';
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            // var_dump($password_hashed);
            $checkusern = $this->user->selectUser($username);
            $checkusern1 = $this->user->selectUserEmail($email);
            $checkusern2 = $this->user->selectUserPhone($phone);

            if ($checkusern) {
                $this->notify('error', "Tên đăng nhập đã tồn tại");
            }
            if ($checkusern1) {
                $this->notify('error', "Email đã được đăng ký với tài khoản khác");
            }

            if ($checkusern2) {
                $this->notify('error', "Số điện thoại đã được đăng ký với tài khoản khác");
            }

            if ($checkusern1 && $checkusern1['status'] == 'banned') {
                $this->notify('error', "Email này đã được đăng ký với tài khoản bị khóa!");
            }

            $insertSuccess = $this->user->insertUser($username, $password_hashed, $email, $fullname, $address, $phone, $role);
            if ($insertSuccess) {
                $this->notify('success', "Thêm thành công");
            } else {
                $this->notify('error', "Thêm không thành công");
            }
        }
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $role = $_POST['role'] ?? '';

            $updatesucess = $this->user->setUser($id, $role);
            if ($updatesucess) {
                $this->notify('success', "Thành công");
            } else {
                $this->notify('error', "Không thành công");
            }
        }
    }
}
