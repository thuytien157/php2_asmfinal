<?php

namespace app\Controllers\client;

include_once './core/viewer.php';
include_once './app/Models/UserModel.php';

use core\BaseController;
use app\Models\UserModel;

class CartController extends BaseController
{
    private $users;
    public function __construct()
    {
        parent::__construct();
        $this->users = new UserModel();
    }

    public function index()
    {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        if (isset($_SESSION['user'])) {
            $user = $this->users->selectUserById($_SESSION['user']['id']);

            $this->render('cart', ['cart' => $cart, 'user' => $user]);
        } else {
            $_SESSION['error'] = "Bạn cần phải đăng nhập";
            header('location: /php2/ASMC');
            exit;
        }
    }

    public function addToCart()
    {


        if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                header("Content-Type: application/json");

                if (!isset($_POST['id'], $_POST['product_name'], $_POST['quantity'], $_POST['product_price'], $_POST['product_image'])) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Thiếu dữ liệu sản phẩm!'
                    ]);
                    exit;
                }

                $product_id = (int)$_POST['id'];
                $product_name = htmlspecialchars($_POST['product_name']);
                $quantity = (int)$_POST['quantity'];
                $product_price = (int)$_POST['product_price'];
                $image = htmlspecialchars($_POST['product_image']);

                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }

                $check = false;
                foreach ($_SESSION['cart'] as &$item) {
                    if ((int)$item['id'] === $product_id) {
                        $item['quantity'] += $quantity;
                        $check = true;
                        break;
                    }
                }

                if (!$check) {
                    $_SESSION['cart'][] = [
                        'id' => $product_id,
                        'name' => $product_name,
                        'price' => $product_price,
                        'quantity' => $quantity,
                        'image' => $image
                    ];
                }

                echo json_encode([
                    'success' => true,
                    'message' => 'Sản phẩm đã được thêm vào giỏ hàng!',
                    'cart' => $_SESSION['cart']
                ]);
                exit;
            }

            
        } else {
            echo json_encode([
                'success' => true,
                'message' => 'Bạn cần đăng nhập!',
            ]);
            exit;
        }
    }



    public function removeFromCart($product_id)
    {
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $key => $item) {
                if ($item['id'] == $product_id) {
                    unset($_SESSION['cart'][$key]);
                    header('Location: /php2/ASMC/cart');
                    exit;
                }
            }
        }
    }

    public function increase($product_id)
    {
        $check = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product_id) {
                $item['quantity']++;
                $check = true;
                break;
            }
        }

        if (!$check) {
            $_SESSION['cart'][] = [
                'id' => $product_id,
                'quantity' => 1,
            ];
        }

        header('Location: /php2/ASMC/cart');
        exit;
    }

    public function decrease($product_id)
    {
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] == $product_id && $item['quantity'] > 1) {
                    $item['quantity']--;
                    break;
                }
            }
        }

        header('Location: /php2/ASMC/cart');
        exit;
    }
}
