<body>
    <div class="main-content">

        <div class="card-body table-wrapper-scroll-y my-custom-scrollbar">
            <button href="/php2/ASMC/admin/product/insert" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="btn btn-dark">Thêm màu</button>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Color ID</th>
                        <th>Name</th>
                        <th>Màu</th>
                        <th>Hex code</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($colors as $key => $value): ?>
                        <?php extract($value); ?>
                        <tr>
                            <td><?= $id ?></td>
                            <td><?= $name ?></td>
                            <td>
                                <button type="button" class="btn color-btn border border-dark border-1"
                                    data-color="<?= $id ?>"
                                    onclick="selectColor(<?= $id ?>, '<?= $hex_code ?>')"
                                    style="background-color: <?= $hex_code ?>;
                                width: 30px; 
                                height: 30px; 
                                border-radius: 50%;
                                transition: transform 0.2s ease;
                                box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);">
                                </button>
                            </td>
                            <td><?= $hex_code ?></td>
                            <td>
                                <button class="btn bg-danger text-white" data-bs-toggle="modal" data-bs-target="#staticBackdrop_delete<?= $id ?>"><i class="bi bi-trash3-fill"></i></button>
                                <button class="btn bg-dark text-white" data-bs-toggle="modal" data-bs-target="#staticBackdrop_edit<?= $id ?>"><i class="bi bi-pencil-square"></i></button>
                            </td>
                        </tr>

                        <!-- modal-xoá -->
                        <div class="modal fade" id="staticBackdrop_delete<?= $id ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Thông báo</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Bạn có chắc muốn xoá màu này?
                                    </div>
                                    <div class="modal-footer">
                                        <a href="/php2/ASMC/admin/color/delete/<?= $id ?>" class="btn btn-danger">Xác nhận</a>
                                    </div>
                                </div>
                            </div>
                        </div>


                          <!-- Modal sửa -->
                        <div class="modal fade" id="staticBackdrop_edit<?=$id?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-6" id="staticBackdropLabel">Sửa màu sắc</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="/php2/ASMC/admin/color/edit/<?=$id?>" method="post">
                                            <div class="mb-3">
                                                <label for="category-name" class="form-label">Tên</label>
                                                <input type="text" class="form-control" name="name" value="<?=$name?>" placeholder="Nhập tên danh mục">
                                            </div>
                                            <div class="mb-3">
                                                <label for="parent-category" class="form-label">Mã màu</label>
                                                <input type="color" class="form-control" name="hex_code" id="" value="<?=$hex_code?>">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                <button type="submit" class="btn btn-primary" name="editColor">Sửa</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Modal thêm -->
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-6" id="staticBackdropLabel">Thêm màu sắc</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="/php2/ASMC/admin/color/insert" method="post">
                                <div class="mb-3">
                                    <label for="category-name" class="form-label">Tên</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Nhập tên danh mục">
                                </div>
                                <div class="mb-3">
                                    <label for="parent-category" class="form-label">Mã màu</label>
                                    <input type="color" class="form-control" name="hex_code" id="">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                    <button type="submit" class="btn btn-primary" name="inserColor">Thêm</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>