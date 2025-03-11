<?php

namespace app\Controllers\client;

include_once './core/viewer.php';
include_once './app/Models/CategoryModel.php';

use core\BaseController;
use app\Models\CategoryModel;

class CategoryController
{
    private $cate;

    public function __construct()
    {
        $this->cate = new CategoryModel();
    }

    public function index()
    {
        $ParentsC = $this->cate->getParentCategories();
        $ChildrenC = [];

        if (!empty($ParentsC)) {
            foreach ($ParentsC as $category) {
                $ChildrenC[$category['id']] = $this->cate->getChildCategories($category['id']);
            }
        }
        $data['ParentsC'] = $ParentsC;
        $data['ChildrenC'] = $ChildrenC;
        //var_dump($ParentsC);
        return [
            'ParentsC' => $ParentsC,
            'ChildrenC' => $ChildrenC
        ];
    }
}
