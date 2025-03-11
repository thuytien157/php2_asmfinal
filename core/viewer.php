<?php

namespace core;

include_once './app/Controllers/client/CategoryController.php';

use app\Controllers\client\CategoryController;

class BaseController
{
    protected $headerData = [];
    protected $area;

    public function __construct($area = 'client')
    {
        $this->area = $area;

        if ($this->area == 'admin') {
            if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
                $_SESSION['error'] = "Bạn không có quyền truy cập vào trang admin!";
                header("Location: /php2/ASMC"); 
                exit();
            }
        }


        if ($this->area == 'client') {
            $categoryController = new CategoryController();
            $categoryData = $categoryController->index();
            $this->headerData['ParentsC'] = $categoryData['ParentsC'];
            $this->headerData['ChildrenC'] = $categoryData['ChildrenC'];
        }
    }

    public function render($view, $data = [])
    {
        $data = array_merge($this->headerData, $data);
        extract($data);

        if ($this->area == 'client') {
            include './app/Views/client/header.php';
        } elseif ($this->area == 'admin') {
            include './app/Views/admin/sitebar.php';
        }

        if (isset($_SESSION['error'])) {
            echo '<div class="modal fade" tabindex="-1" id="errorModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Thông báo</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-danger text-danger text-bold">' . $_SESSION['error'] . '</div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            </div>
                        </div>
                    </div>
                </div>';
            unset($_SESSION['error']);
            echo "<script>
                    var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                    errorModal.show();
                </script>";
        } elseif (isset($_SESSION['success'])) {
            echo '<div class="modal fade" tabindex="-1" id="successModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Thông báo</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-success text-success text-bold">' . $_SESSION['success'] . '</div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            </div>
                        </div>
                    </div>
                </div>';
            unset($_SESSION['success']);
            echo "<script>
                    var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();
                </script>";
        }

        include_once './app/Views/' . $this->area . '/' . $view . '.php';

        if ($this->area == 'client') {
            include_once './app/Views/client/footer.php';
        } 
    }
}
