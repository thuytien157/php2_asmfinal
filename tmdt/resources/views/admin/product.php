<div class="main-content">
    <div class="card-body table-wrapper-scroll-y my-custom-scrollbar">
        <div class="d-flex gap-2 w-100">
            <a href="/php2/ASMC/admin/product/insert" class="btn btn-dark">Thêm sản phẩm</a>
            <form class="d-flex gap-4" action="/php2/ASMC/admin/product" method="post" id="selectedCategory">
                <select class="border border-dark rounded" name="selectedCategory" onchange="document.getElementById('selectedCategory').submit();">
                    <option disabled selected value="">Chọn danh mục</option>
                    <?php foreach ($categories as $category): ?>
                        <option <?= (isset($id_category) && $id_category == $category['id']) ? 'selected' : '' ?>
                            value="<?= $category['id'] ?>">
                            <?= $category['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select class="border border-dark rounded" name="selectstatus" onchange="document.getElementById('selectedCategory').submit();">
                    <option value="active" <?= (isset($status) && $status == 'active') ? 'selected' : '' ?>>Sản phẩm đang bán</option>
                    <option value="inactive" <?= (isset($status) && $status == 'inactive') ? 'selected' : '' ?>>Sản phẩm ngừng kinh doanh</option>
                </select>

                <input type="search" placeholder="Tìm kiếm" name="key" onchange="document.getElementById('selectedCategory').submit();">
            </form>

        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Discount</th>
                    <th>Category</th>
                    <th>Image</th>
                    <th>Thumbnails</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <?php extract($product); ?>
                    <tr>
                        <td><?= $id ?></td>
                        <td><?= $name ?></td>
                        <td><?= number_format($price, 0, '.', '.') ?>đ</td>
                        <td><?= $discount ?>%</td>
                        <td><?= $category_name ?></td>
                        <td>
                            <div class="product-container">
                                <img id="mainImage1" src="/php2/ASMC/app/public/img/products/<?= $product['detail_list'][0] ?>" alt="Product Image" class="main-image" onclick="openPopup(this)">
                            </div>
                        </td>
                        <td>
                            <div class="details-container">
                                <?php foreach (array_slice($product['detail_list'], 1) as $image_detail): ?>
                                    <img src="/php2/ASMC/app/public/img/products/<?= $image_detail ?>" alt="Detail 1" class="detail-image" onclick="openPopup(this)">
                                <?php endforeach; ?>

                            </div>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="/php2/ASMC/admin/product/edit/<?= $id ?>" class="btn bg-primary text-white d-flex justify-content-center align-items-center">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button data-bs-toggle="modal" data-bs-target="#staticBackdrop_delete<?= $id ?>" class="btn bg-danger text-white d-flex justify-content-center align-items-center">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                                <button data-bs-toggle="modal" data-bs-target="#staticBackdrop_hidden<?= $id ?>" class="btn <?= $status == 'active' ? 'bg-warning' : 'bg-secondary'; ?> text-white d-flex justify-content-center align-items-center">
                                    <i class="bi bi-ban"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal xoá-->
                    <div class="modal fade" id="staticBackdrop_delete<?= $id ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Thông báo</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Bạn chắc chắn muốn xoá sản phẩm này
                                </div>
                                <div class="modal-footer">
                                    <a href="/php2/ASMC/admin/product/delete/<?= $id ?>" class="btn btn-danger">Xác nhận</a>
                                </div>
                            </div>
                        </div>
                    </div>




                    <!-- Modal ẩn sản phẩm-->
                    <div class="modal fade" id="staticBackdrop_hidden<?= $id ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Thông báo</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Bạn có chắc muốn <?= $status == 'active' ? 'ẩn' : 'hiện'; ?> sản phẩm này?
                                </div>
                                <div class="modal-footer">
                                    <form action="/php2/ASMC/admin/product/status/<?= $id ?>" method="post">
                                        <input type="hidden" name="lock" value="<?= $status ?>">
                                        <button type="submit" name="status" class="btn btn-danger">Xác nhận</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Popup -->
<div class="popup" id="imagePopup" onclick="closePopup()">
    <img id="popupImage" src="" alt="Popup Image">
</div>

