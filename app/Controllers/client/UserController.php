<?php

namespace app\Controllers\client;

include_once './app/Models/UserModel.php';
include_once './core/viewer.php';
require_once './vendor/autoload.php';

use Google\Client as Google_Client;
use Google\Service\Oauth2;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use core\BaseController;
use app\Models\UserModel;

class UserController extends BaseController
{
    private $user;
    private $client;
    private $CLIENT_ID = '68989282790-5n0tbmetov4stiv101lmm8e8cugiltfm.apps.googleusercontent.com';
    private $CLIENT_SECRET = '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';
    private $REDIRECT_URI = 'http://localhost/php2/ASMC/user/google-login-callback';


    public function __construct()
    {
        parent::__construct();
        $this->user = new UserModel();
        $this->client = new Google_Client();
    }

    public function googleLogin()
    {
        $this->client->setClientId($this->CLIENT_ID);
        $this->client->setClientSecret($this->CLIENT_SECRET);
        $this->client->setRedirectUri($this->REDIRECT_URI);
        $this->client->addScope("email");
        $this->client->addScope("profile");

        $this->client->setPrompt('select_account');

        $authUrl = $this->client->createAuthUrl();
        header("Location: $authUrl");
        exit();
    }


    public function googleLoginCallback()
    {
        if (isset($_GET['code'])) {
            $this->client->setClientId($this->CLIENT_ID);
            $this->client->setClientSecret($this->CLIENT_SECRET);
            $this->client->setRedirectUri($this->REDIRECT_URI);

            $token = $this->client->fetchAccessTokenWithAuthCode($_GET['code']);

            if (isset($token['error'])) {
                die('Lỗi OAuth: ' . $token['error_description']);
            }

            $this->client->setAccessToken($token);

            $oauth = new Oauth2($this->client);
            $userInfo = $oauth->userinfo->get();
            $email = $userInfo->email;
            $username = explode('@', $email)[0]; // Lấy toàn bộ phần trước dấu @

            $existingUser = $this->user->selectUserEmail($email);
            $id = $existingUser['id'];

            // echo($existingUser['id']);
            // return;
            if ($existingUser) {
                if ($existingUser['username'] != $username) {
                    $username = $existingUser['username'];
                }

                if ($existingUser['status'] == 'banned') {
                    $_SESSION['error'] = "Tài khoản đã bị khoá";
                    header('location: /php2/ASMC');
                    exit();
                }

                $_SESSION['user'] = [
                    'id' => $id,
                    'username' => $username,
                    'email' => $email,
                ];
            } else {
                $id = $this->user->insertUser(
                    $username,         // username (lấy 8 ký tự đầu từ email)
                    "",                // password (để trống vì dùng Google login)
                    $email,            // email
                    $userInfo->name,   // fullname
                    "",                // address (chưa có thông tin)
                    "",                // phone (chưa có thông tin)
                    "user"             // role mặc định là "user"
                );

                $_SESSION['user'] = [
                    'id' => $id,
                    'username' => $username,
                    'email' => $email,
                ];
            }

            header("Location: /php2/ASMC");
            exit();
        } else {
            die("Lỗi xác thực Google!");
        }
    }




    public function index()
    {
        if (isset($_SESSION['user'])) {
            $username = $_SESSION['user']['username'];
            $infouser = $this->user->selectUser($username);
            $this->render('account', ['infouser' => $infouser]);
            //var_dump($infouser);

        } else {
            $_SESSION['error'] = "Bạn cần phải đăng nhập";
            header('location: /php2/ASMC');
            exit;
        }
    }
    private function isValidEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    private function isValidUsername($username)
    {
        return preg_match('/^[a-zA-Z0-9_]{8,20}$/', $username);
    }

    function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $rePassword = $_POST['repassword'] ?? '';
            $fullname = null;
            $address = null;
            $phone = null;
            $role = 'user';

            if (empty($username) || empty($email) || empty($password) || empty($rePassword)) {
                $_SESSION['error'] = "Vui lòng nhập đủ thông tin";
                header('location: /php2/ASMC');
                exit;
            }

            if (!$this->isValidEmail($email)) {
                $_SESSION['error'] = "Email không đúng định dạng";
                header('location: /php2/ASMC');
                exit;
            }

            if (!$this->isValidUsername($username)) {
                $_SESSION['error'] = "Tên đăng nhập phải từ 8-20 ký tự, chỉ chứa chữ, số và dấu gạch dưới";
                header('location: /php2/ASMC');
                exit;
            }

            if ($password !== $rePassword) {
                $_SESSION['error'] = "Mật khẩu không khớp";
                header('location: /php2/ASMC');
                exit;
            }

            $hashPassword = password_hash($password, PASSWORD_DEFAULT);

            $checkusern = $this->user->selectUser($username);
            $checkusern1 = $this->user->selectUserEmail($email);

            if ($checkusern) {
                $_SESSION['error'] = "Tên đăng nhập đã tồn tại";
                header('location: /php2/ASMC');
                exit;
            }

            if ($checkusern1) {
                $_SESSION['error'] = "Email đã được đăng ký với tài khoản khác";
                header('location: /php2/ASMC');
                exit;
            }

            if ($checkusern1 && $checkusern1['status'] == 'banned') {
                $_SESSION['error'] = "Email này đã được đăng ký với tài khoản bị khóa!";
                header('location: /php2/ASMC');
                exit;
            }
            // $insertSuccess = $this->user->insertUser($username, $hashPassword, $email, $fullname, $address, $phone, $role);

            $insertSuccess = $this->user->insertUser($username, $hashPassword, $email, $fullname, $address, $phone, $role);
            //var_dump($username); die;

            if ($insertSuccess) {
                $_SESSION['success'] = "Đăng ký thành công";
            } else {
                $_SESSION['error'] = "Đăng ký không thành công";
            }
            header('location: /php2/ASMC');
            exit;
        }
    }


    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
        }
        if (!$this->isValidUsername($username)) {
            $_SESSION['error'] = "Tên đăng nhập phải từ 8-20 ký tự, chỉ chứa chữ, số và dấu gạch dưới";
            header('location: /php2/ASMC');
            exit;
        }

        $infouser = $this->user->selectUser($username);
        //var_dump($infouser); die;
        if ($infouser['status'] == 'banned') {
            $_SESSION['error'] = 'Tài khoản này đã bị khoá! Bạn có thể <a href="">liên hệ</a> với chúng tôi để biết thêm thông tin và mở khoá tài khoản';
            header('location: /php2/ASMC');
            exit;
        }

        if (!$infouser) {
            $_SESSION['error'] = "Tên đăng nhập không đúng";
        } else {
            $hashedPassword = password_verify($password, $infouser['password']);

            if ($hashedPassword) {
                $_SESSION['user'] = [
                    'username' => $infouser['username'],
                    'id' => $infouser['id'],
                    'password' => $infouser['password'],
                    'email' => $infouser['email'],
                    'fullname' => $infouser['fullname'],
                    'address' => $infouser['address'],
                    'phone' => $infouser['phone'],
                    'role' => $infouser['role']
                ];
            } else {
                $_SESSION['error'] = "Mật khẩu sai";
                header('location: /php2/ASMC');
                exit();
            }
        }
        if ($infouser['role'] == 'admin') {
            header('location: /php2/ASMC/admin');
            exit;
        } else {
            header('location: /php2/ASMC');
            exit;
        }

        //var_dump($infouser);
    }

    public function logout()
    {
        unset($_SESSION['user']);
        session_destroy();
        header('location: /php2/ASMC');
        exit;
    }

    public function updateAccount()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['edituser'])) {
            $fullname = $_POST['hoten'] ?? '';
            $phone = $_POST['sdt'] ?? '';
            $email = $_POST['email'] ?? '';
            $address = $_POST['address'] ?? '';
            $user_id = $_SESSION['user']['id'] ?? '';

            $updateSuccess = $this->user->updateUser($fullname, $phone, $email, $address, $user_id);
            if ($updateSuccess) {
                $_SESSION['user']['fullname'] = $fullname;
                $_SESSION['user']['phone'] = $phone;
                $_SESSION['user']['email'] = $email;
                $_SESSION['user']['address'] = $address;

                $_SESSION['success'] = "Thành công";
            } else {
                $_SESSION['error'] = "Thất bại";
            }

            header('location: /php2/ASMC/account');
            exit;
        }
    }


    public function resetpass()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? null;
            $code = $_POST['otp'] ?? null;
            $password = $_POST['newPassword'] ?? null;
            $emailPattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";

            header("Content-Type: application/json");
            if (empty($email)) {
                echo json_encode(["status" => "error", "message" => "Email không được để trống!"]);
                exit;
            }

            if (!preg_match($emailPattern, $email)) {
                echo json_encode(["status" => "error", "message" => "Email không đúng định dạng!"]);
                exit;
            }

            if ($email && !$code) {
                $check = $this->user->selectUserEmail($email);
                if ($check) {
                    $otpCode = rand(10000, 99999);
                    $expiry = date('Y-m-d H:i:s', strtotime('+5 minutes'));
                    $id = $check['id'];
                    $updatecode = $this->user->updateaCode($otpCode, $expiry, $id);
                    if ($updatecode) {
                        if ($this->sendEmail($email, $otpCode)) {
                            echo json_encode(["status" => "success", "message" => "Mã xác thực đã được gửi"]);
                            exit;
                        } else {
                            echo json_encode(["status" => "error", "message" => "Gửi thất bại! Xin thử lại"]);
                            exit;
                        }
                    } else {
                        echo json_encode(["status" => "error", "message" => "Cập nhật mã thất bại"]);
                        exit;
                    }
                } else {
                    echo json_encode(["status" => "error", "message" => "Email chưa được đăng ký!"]);
                    exit;
                }
            }

            if ($email && $code) {
                if (empty($password)) {
                    echo json_encode(["status" => "error", "message" => "Mật khẩu mới không được để trống!"]);
                    exit;
                }

                $user = $this->user->selectUserEmail($email);
                if (!$user) {
                    echo json_encode(["status" => "error", "message" => "Email không tồn tại!"]);
                    exit;
                }

                $oldPasswordHash = $user['password'];

                if (password_verify($password, $oldPasswordHash)) {
                    echo json_encode(["status" => "error", "message" => "Mật khẩu mới không được giống với mật khẩu cũ!"]);
                    exit;
                }

                $hashPassword = password_hash($password, PASSWORD_DEFAULT);

                $isValid = $this->user->checkVerificationCode($email, $code);
                if ($isValid) {
                    $setPass = $this->user->updatePassWord($hashPassword, $email, $code);
                    if ($setPass) {
                        echo json_encode(["status" => "success", "message" => "Đổi mật khẩu thành công"]);
                        $this->user->deleteVerificationCode($email);
                        exit;
                    } else {
                        echo json_encode(["status" => "error", "message" => "Đổi mật khẩu không thành công"]);
                        exit;
                    }
                } else {
                    echo json_encode(["status" => "error", "message" => "Mã xác thực không hợp lệ hoặc đã hết hạn!"]);
                    exit;
                }
            }

            echo json_encode(["status" => "error", "message" => "Vui lòng nhập email và mã OTP!"]);
            exit;
        }

        $this->render('resetpass');
    }



    private function sendEmail($email, $code)
    {
        require 'PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
        require 'PHPMailer-master/PHPMailer-master/src/SMTP.php';
        require 'PHPMailer-master/PHPMailer-master/src/Exception.php';

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = "tiennttps39163@gmail.com";
            $mail->Password   = "fubamlrqynmfpdvw";
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            $mail->setFrom("tiennttps39163@gmail.com", "Hỗ trợ khách hàng");
            $mail->addAddress($email);

            $mail->CharSet = 'UTF-8';
            $mail->isHTML(true);
            $mail->Subject = "Xác thực tài khoản của bạn";
            $mail->Body = "
            <div style='font-family: Arial, sans-serif; font-size: 14px; color: #333;'>
                <h2 style='color:rgb(2, 2, 2);'>Mã xác thực tài khoản</h2>
                <p>Xin chào,</p>
                <p>Bạn đã yêu cầu đặt lại mật khẩu cho tài khoản của mình. Dưới đây là mã xác thực của bạn:</p>
                <p style='font-size: 18px; font-weight: bold; color:rgb(2, 2, 2); background: #f8f9fa; padding: 10px; display: inline-block; border-radius: 5px;'>
                    $code
                </p>
                <p><strong>Lưu ý:</strong> Mã này có hiệu lực trong vòng <span style='color:rgb(7, 7, 7);'>5 phút</span>. Vui lòng sử dụng sớm.</p>
                <p>Nếu bạn không yêu cầu đặt lại mật khẩu, hãy bỏ qua email này.</p>
                <p>Trân trọng,</p>
                <p><strong>Đội ngũ hỗ trợ</strong></p>
            </div>
        ";

            $mail->send();
            return true;
        } catch (Exception $e) {
            echo "Lỗi gửi email: {$mail->ErrorInfo}";
            return false;
        }
    }

    public function changepass()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['changepass'])) {
            $oldpass = $_POST['oldpassword'] ?? '';
            $newpassword = $_POST['password'] ?? '';
            $newpassword1 = $_POST['password1'] ?? '';

            if ($newpassword !== $newpassword1) {
                $_SESSION['error'] = "Mật khẩu mới không khớp!";
                header('location: /php2/ASMC/account');
                exit();
            }

            $id = $_SESSION['user']['id'];
            $infouser = $this->user->selectUserById($id);
            $hashedPasswordFromDB = $infouser[0]['password'] ?? '';

            if (!empty($infouser[0]['password'])) {
                if (!password_verify($oldpass, $hashedPasswordFromDB)) {
                    $_SESSION['error'] = "Mật khẩu cũ không đúng!";
                    header('location: /php2/ASMC/account');
                    exit();
                }
            }

            $hashedNewPassword = password_hash($newpassword, PASSWORD_DEFAULT);
            $setsuccess = $this->user->ChangePass($hashedNewPassword, $id);

            if ($setsuccess) {
                $_SESSION['success'] = "Đổi mật khẩu thành công! Vui lòng đăng nhập lại";
                header('location: /php2/ASMC');
                unset($_SESSION['user']);
                exit();
            } else {
                $_SESSION['error'] = "Đổi mật khẩu không thành công! Vui lòng thử lại.";
            }

            header('location: /php2/ASMC/account');
            exit();
        }
    }
}
