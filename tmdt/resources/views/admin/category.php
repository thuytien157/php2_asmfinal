<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý danh mục</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="main-content">
        <h2 class="mb-3">Quản lý danh mục</h2>
        <div class="mb-4">

            <div class="d-flex gap-5">
                <button type="button" class="btn btn-dark h-25" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    Thêm danh mục
                </button>
                <div>
                    <form action="/php2/ASMC/admin/category/search" method="post">
                        <input type="text" name="key" class="timkiem" placeholder="Tìm kiếm">
                    </form>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-6" id="staticBackdropLabel">Thêm danh mục</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="/php2/ASMC/admin/category/insert" method="post">
                                <div class="mb-3">
                                    <label for="category-name" class="form-label">Tên danh mục</label>
                                    <input type="text" class="form-control" id="category-name" name="nameCate" placeholder="Nhập tên danh mục">
                                </div>
                                <div class="mb-3">
                                    <label for="parent-category" class="form-label">Danh mục cha</label>
                                    <select class="form-select" id="parent-category" name="parentCate">
                                        <option selected value="0">Chọn danh mục cha (không bắt buộc)</option>
                                        <?php foreach ($listcate as $category): ?>
                                            <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="parent-category" class="form-label">Đặt làm danh mục mặc định</label>
                                    <select class="form-select" id="parent-category" name="is_default">
                                        <option value="0">Không</option>
                                        <option value="1">Có</option>
                                    </select>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                    <button type="submit" class="btn btn-primary" name="inserC">Thêm</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">

            </div>
        </div>

        <div class="row">
            <?php foreach ($listcate as $category): ?>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= $category['name'] ?></h5>
                            <!-- Kiểm tra và hiển thị danh mục cha -->
                            <?php if ($category['id_parent'] == 0 && $category['is_default'] != 1): ?>
                                <p class="card-text">Danh mục cha</p>
                            <?php elseif ($category['is_default'] == 1): ?>
                                <p class="card-text"><b>Danh mục mặc định</b></p>
                            <?php else: ?>
                                <p class="card-text">Danh mục con của: <b><?= $categoryNames[$category['id_parent']] ?? '' ?></b></p>
                            <?php endif; ?>


                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop_edit<?= $category['id'] ?>">Sửa</button>
                            <!-- Modal -->
                            <div class="modal fade" id="staticBackdrop_edit<?= $category['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Chỉnh sửa</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="/php2/ASMC/admin/category/edit/<?= $category['id'] ?>" method="post">
                                                <div class="mb-3">
                                                    <label for="category-name" class="form-label">Tên danh mục</label>
                                                    <input type="text" class="form-control" id="category-name" name="nameCate" value="<?= $category['name'] ?>" placeholder="Nhập tên danh mục">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="parent-category">Chọn danh mục cha (không bắt buộc)</label>
                                                    <select class="form-select" id="parent-category" name="parentCate">
                                                        <option value="0" <?= $category['id_parent'] == 0 ? 'selected' : '' ?>>Danh mục này không thuộc danh mục nào</option>
                                                        <?php foreach ($listcate as $editcategory): ?>
                                                            <option value="<?= $editcategory['id'] ?>" <?= $editcategory['id'] == $category['id_parent'] ? 'selected' : '' ?>>
                                                                <?= $editcategory['name'] ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>


                                                <div class="mb-3">
                                                    <label for="parent-category" class="form-label">Đặt làm danh mục mặc định</label>
                                                    <select class="form-select" id="parent-category" name="is_default">
                                                        <option value="0">Không</option>
                                                        <option <?= $category['is_default'] == 1 ? 'selected' : '' ?> value="1">Có</option>
                                                    </select>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-danger" name="editC">Sửa</button>
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <?php if($category['is_default'] == 0): ?>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop_delete<?= $category['id'] ?>">Xóa</button>
                            <?php endif; ?>
                            <!-- Modal -->
                            <div class="modal fade" id="staticBackdrop_delete<?= $category['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Thông báo</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Bạn có chắc muốn xoá danh mục này?
                                        </div>
                                        <div class="modal-footer">
                                            <a href="/php2/ASMC/admin/category/delete/<?= $category['id'] ?>" class="btn btn-danger">Xác nhận</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <!-- <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tiểu Thuyết</h5>
                        <p class="card-text">Danh mục cha: Sách Văn Học</p>
                        <button class="btn btn-primary btn-sm">Sửa</button>
                        <button class="btn btn-danger btn-sm">Xóa</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Khoa Học</h5>
                        <p class="card-text">Danh mục cha: </p>
                        <button class="btn btn-primary btn-sm">Sửa</button>
                        <button class="btn btn-danger btn-sm">Xóa</button>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</body>

</html>