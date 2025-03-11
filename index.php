<?php
ob_start();
session_start();
ini_set('display_errors', 1);  
ini_set('display_startup_errors', 1);  
error_reporting(E_ALL);

$_SESSION['cart'] = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

include_once 'core/router.php';
$route = new Router();
$route->add("/home", ["controller" => "home", "action" => "index"]);
$route->add("/", ["controller" => "home", "action" => "index"]);
$route->add("/product", ["controller" => "product", "action" => "index"]);
$route->add("/product/detail/{id}", ["controller" => "product", "action" => "detail"]);
$route->add("/addToCart", ["controller" => "cart", "action" => "addToCart"]);
$route->add("/cart", ["controller" => "cart", "action" => "index"]);
$route->add("/cartRemove/{product_id}", ["controller" => "cart", "action" => "removeFromCart"]);
$route->add("/cart/updateQuantity/{product_id}/increase", ["controller" => "cart", "action" => "increase"]);
$route->add("/cart/updateQuantity/{product_id}/decrease", ["controller" => "cart", "action" => "decrease"]);
$route->add("/user/register", ["controller" => "user", "action" => "register"]);
$route->add("/user/login", ["controller" => "user", "action" => "login"]);
$route->add("/user/logout", ["controller" => "user", "action" => "logout"]);
$route->add("/user/reset", ["controller" => "user", "action" => "resetpass"]);
$route->add("/user/changepass", ["controller" => "user", "action" => "changepass"]);
// $route->add("/user/sendotp", ["controller" => "user", "action" => "otpcode"]);
$route->add("/product/category/{categoryid}", ["controller" => "product", "action" => "index"]);
$route->add("/account", ["controller" => "user", "action" => "index"]);
$route->add("/account/update", ["controller" => "user", "action" => "updateAccount"]);
$route->add("/order", ["controller" => "order", "action" => "orders"]);
$route->add("/order/infor", ["controller" => "order", "action" => "InforOrder"]);
$route->add("/order/infor/editAddress", ["controller" => "order", "action" => "updateAddress"]);
$route->add("/order/infor/cancel/{id}", ["controller" => "order", "action" => "cancelOrder"]);
$route->add("/product/search", ["controller" => "product", "action" => "search"]);
$route->add("/admin", ["controller" => "statistic", "action" => "index"]);
$route->add("/admin/category", ["controller" => "category", "action" => "index"]);
$route->add("/admin/category/search", ["controller" => "category", "action" => "search"]);
$route->add("/admin/category/insert", ["controller" => "category", "action" => "insert"]);
$route->add("/admin/category/delete/{id}", ["controller" => "category", "action" => "delete"]);
$route->add("/admin/category/edit/{id}", ["controller" => "category", "action" => "edit"]);
$route->add("/admin/category/setdefault/{id}", ["controller" => "category", "action" => "default"]);
$route->add("/admin/users", ["controller" => "user", "action" => "index"]);
$route->add("/admin/users/delete/{id}", ["controller" => "user", "action" => "delete"]);
$route->add("/admin/users/status/{id}", ["controller" => "user", "action" => "status"]);
$route->add("/admin/users/insert", ["controller" => "user", "action" => "insert"]);
$route->add("/admin/users/update/{id}", ["controller" => "user", "action" => "update"]);
$route->add("/admin/order", ["controller" => "order", "action" => "index"]);
$route->add("/admin/order/detail/{id_order}", ["controller" => "order", "action" => "detail"]);
$route->add("/admin/order/status/{id_order}", ["controller" => "order", "action" => "setStatus"]);
$route->add("/admin/product", ["controller" => "product", "action" => "index"]);
$route->add("/admin/product/insert", ["controller" => "product", "action" => "insert"]);
$route->add("/admin/product/delete/{id}", ["controller" => "product", "action" => "delete"]);
$route->add("/admin/product/status/{id}", ["controller" => "product", "action" => "status"]);
$route->add("/admin/product/edit/{id}", ["controller" => "product", "action" => "edit"]);
// $route->add("/vnpay/payment/{totalprice}", ["controller" => "vnpay", "action" => "createPayment"]);
$route->add("/vnpayreturn", ["controller" => "order", "action" => "vnpayReturn"]);
$route->add("/contact", ["controller" => "contact", "action" => "index"]);
$route->add("/contact/send", ["controller" => "contact", "action" => "sendMail"]);
$route->add("/product/filter", ["controller" => "product", "action" => "filter"]);
$route->add("/user/google-login", ["controller" => "user", "action" => "googleLogin"]);
$route->add("/user/google-login-callback", ["controller" => "user", "action" => "googleLoginCallback"]);
$route->add("/admin/order/export/{id_order}", ["controller" => "order", "action" => "exportInvoice"]);
$route->add("/admin/color", ["controller" => "color", "action" => "index"]);
$route->add("/admin/color/insert", ["controller" => "color", "action" => "insert"]);
$route->add("/admin/color/delete/{id}", ["controller" => "color", "action" => "delete"]);
$route->add("/admin/color/edit/{id}", ["controller" => "color", "action" => "edit"]);

// $route->add("/admin", ["controller" => "statistic", "action" => "index"]);



$request_uri = $_SERVER['REQUEST_URI'];
$path = str_replace('/php2/ASMC', '', $request_uri);
//var_dump($path);

$params = $route->match($path);
if ($params === false) {
    exit("Không có cái trang này!");
}
//var_dump($params);
if (strpos($path, '/admin') === 0) {
    // if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    //     exit("Bạn không có quyền truy cập vào khu vực admin!");
    // }
    $ctrlName = "app\\Controllers\\admin\\" . ucfirst($params['controller']) . "Controller";
} else {
    $ctrlName = "app\\Controllers\\client\\" . ucfirst($params['controller']) . "Controller";
}

$action = $params['action'] ?? "index";
unset($params['controller'], $params['action']);
spl_autoload_register(function ($className) {
    $classPath = str_replace('\\', '/', $className);
    $filePath = __DIR__ . '/' . $classPath . '.php';

    if (file_exists($filePath)) {
        include $filePath;
    } else {
        exit("File không tồn tại " . $filePath);
    }
});

$ctrl = new $ctrlName();
$ctrl->$action(...$params);
// var_dump($_SESSION['user']['id']);
// unset($_SESSION['user']);
// echo '<pre>';
// var_dump($_SESSION['cart']);
// echo '</pre>';

// foreach($_SESSION['cart'] as $value){
//     // echo '<pre>';
//     echo($value['quantity']);
//     // echo '</pre>';
// }

?>
