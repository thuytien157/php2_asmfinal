<?php
namespace app\Controllers\client;

include_once 'core/viewer.php';
use core\BaseController;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ContactController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->render('contact');
    }

    public function sendMail()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send'])) {
            $address = $_POST['email'] ?? '';
            $name = $_POST['name'] ?? '';
            $mess = $_POST['message'] ?? '';

            if (empty($address) || empty($name) || empty($mess)) {
                $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin!';
                header('Location: /php2/ASMC/contact');
                exit();
            }

            if ($this->sendEmail($address, $name, $mess)) {
                $_SESSION['success'] = 'Gửi email thành công!';
                
            } else {
                $_SESSION['error'] = 'Lỗi khi gửi email. Vui lòng thử lại!';
            }

            header('Location: /php2/ASMC/contact');
            exit();
        }
    }

    private function sendEmail($address, $name, $mess)
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

            $mail->setFrom("tiennttps39164@gmail.com", 'Form quên mật khẩu');
            $mail->addAddress('tiennttps39163@gmail.com'); 

            $mail->CharSet = 'UTF-8';
            $mail->isHTML(true);
            $mail->Subject = "Liên hệ từ: " . $name . " - " . $address;
            $mail->Body    = $mess;

            $mail->send();
            return true;
        } catch (Exception $e) {
            echo "Lỗi gửi email: {$mail->ErrorInfo}";
            return false;
        }
    }
}
