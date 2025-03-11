<?php

namespace app\Controllers\client;

include_once './app/Models/HomeModel.php';
include_once 'core/viewer.php';

use core\BaseController;
use app\Models\HomeModel;


class HomeController extends BaseController
{
    private $home;

    public function __construct()
    {
        parent::__construct();
        $this->home = new HomeModel();
    }

    public function index()
    {
        $listpro_new = $this->home->getProductN();
        $listpro_sell = $this->home->getProductS();
        $listpro_sale = $this->home->getProductByDiscount();
        $listpro = $this->home->getProduct();

        $this->render('home', ['listpro' => $listpro, 'listpro_new' => $listpro_new, 'listpro_sell' => $listpro_sell, 'listpro_sale' => $listpro_sale]);
    }
}
