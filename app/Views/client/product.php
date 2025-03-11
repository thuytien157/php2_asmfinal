<div class="container-xxl" style="margin-top: 60px;">
    <!-- Filter Modal -->
    <div id="filter-modal" class="filter-modal">
        <div class="filter-modal-content">
            <button id="close-filter-modal" class="btn-close btn-dark" aria-label="Close"></button>
            <label class="form-label fw-bold fs-5 text-center">Lọc sản phẩm</label>

            <form action="/php2/ASMC/product/filter" method="post">
                <h6 class="fw-bold">Danh mục</h6>
                <div class="d-flex flex-column mb-2">
                    <?php foreach ($ParentsC as $category): ?>
                        <div class="form-check">
                            <label class="form-check-label fw-semibold" for="cat<?= $category['id'] ?>">
                                <?= $category['name'] ?>
                            </label>
                        </div>
                        <?php if (!empty($ChildrenC[$category['id']])): ?>
                            <?php foreach ($ChildrenC[$category['id']] as $subCat): ?>
                                <div class="form-check ms-5">
                                    <input class="form-check-input" type="checkbox" name="category[]" value="<?= $subCat['id'] ?>" id="subCat<?= $subCat['id'] ?>">
                                    <label class="form-check-label" for="subCat<?= $subCat['id'] ?>">
                                        <?= $subCat['name'] ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div class="d-flex flex-column mb-2">
                    <label for="priceRange" class="form-label fw-bold mt-2">Giá (VND)</label>
                    <input type="range" class="form-range" min="0" max="2000000" step="50000" name="min_price" id="min_price" value="0">
                    <input type="range" class="form-range" min="0" max="2000000" step="50000" name="max_price" id="max_price" value="2000000">
                    <div class="d-flex justify-content-between">
                        <span id="price_min">0VND</span> - <span id="price_max">VNĐ</span>
                    </div>
                </div>

                <div class="d-flex flex-column mb-2">
                    <label for="color" class="form-label fw-bold mt-2">Màu sắc</label>
                    <div class="btn-group d-grid gap-2" style="grid-template-columns: repeat(auto-fill, minmax(30px, 1fr)); max-width: 300px;" role="group" aria-label="Màu sắc">
                        <?php foreach ($listcolor as $color): ?>

                            <button type="button" class="btn color-btn color-btn1 border rounded-circle border-dark border-2"
                                data-color="<?= $color['id'] ?>"
                                style="background-color: <?= $color['hex_code'] ?>; width: 30px; height: 30px;">
                            </button>
                        <?php endforeach; ?>
                        <input type="hidden" name="color" id="selected_color">
                    </div>
                </div>
                <button type="reset" class="btn btn-secondary">Đặt lại</button>
            </form>
        </div>
    </div>

    <div>
        <div class="link d-flex justify-content-between flex-wrap">
            <div class="dieuhuong mt-3">
                <a class="icon-link text-dark text-decoration-none" href="#">
                    TRANG CHỦ <i class="fa fa-chevron-right opacity-25"></i>
                </a>
                <a class="icon-link text-dark fw-bold text-decoration-none" href="#">
                    SẢN PHẨM
                </a>
            </div>
            <button id="open-filter-modal" class="btn btn-dark">Lọc sản phẩm</button>

        </div>


        <div class="sanpham m-4 mt-2">
            <div class="hangbanchay mb-2">
                <div class="row g-3" id="dssp">
                    <?php foreach ($listpro as $product): ?>
                        <div class="col-12 col-md-6 col-lg-3">
                            <a href="/php2/ASMC/product/detail/<?= $product['id_product'] ?>" class="card text-decoration-none">
                                <?php if (!empty($product['discount'])): ?>
                                    <div class="position-absolute top-0 start-0 badge text-bg-danger m-2"><?= $product['discount'] ?>%</div>
                                <?php endif; ?>
                                <img src="/php2/ASMC/app/public/img/products/<?= $product['image'] ?>" class="card-img-top small-img img-fluid <?= $product['status'] == 'inactive' ? 'opacity-50' : ''; ?>" alt="...">
                                <div class="card-body bg-body">
                                    <?php
                                    $finalPrice = !empty($product['discount'])
                                        ? round($product['price'] - ($product['price'] * ($product['discount'] / 100)), -3)
                                        : round($product['price'], -3);
                                    ?>
                                    <?php if ($product['status'] == 'active'): ?>
                                        <form action="/php2/ASMC/addToCart" method="POST" id="add-to-cart-form">
                                            <input type="hidden" name="id" value="<?= $product['id_product'] ?>">
                                            <input type="hidden" name="product_name" value="<?= $product['name'] ?>">
                                            <input type="hidden" name="product_price" value="<?= $finalPrice ?>">
                                            <input type="hidden" name="product_image" value="<?= $product['image'] ?>">
                                            <input type="hidden" name="quantity" value="1" min="1" class="form-control w-25" id="quantity">
                                            <button class="position-absolute top-0 end-0 m-2 border boder-0 giohang" name="addToCart" type="submit" id="add-to-cart-btn">
                                                <i class="fa fa-shopping-bag fs-4 gh m-2"></i>
                                            </button>
                                        </form>
                                        <p class="card-text namep fw-bolder"><?= $product['name'] ?></p>
                                        <p class="card-text fw-bold text-danger fs-5">
                                            <?php if (!empty($product['discount'])): ?>
                                                <?= number_format($product['price'] - ($product['price'] * ($product['discount'] / 100)), 0, '.', '.') ?>đ
                                                <del class="text-dark fs-6"><?= number_format($product['price'], 0, '.', '.') ?>đ</del>
                                            <?php else: ?>
                                                <?= number_format($product['price'], 0, '.', '.') ?>đ
                                            <?php endif; ?>
                                        </p>
                                    <?php else: ?>
                                        <div class="card-footer text-dark fw-semibold">
                                            Sản phẩm này hiện tại đang ngừng kinh doanh
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div>
    <div class="text-center p-5 pb-2 bg-body-secondary">
        <div class="card-body">
            <h2 class="card-title fw-bold mb-2">ĐĂNG KÝ BẢN TIN CỦA CHÚNG TÔI</h2>
            <p class="card-text">Hãy cập nhật các tin tức thời trang về sản phẩm, BST sắp ra mắt, chương trình khuyến mãi đặc biệt và xu hướng thời trang mới nhất hàng tuần của chúng tôi.</p>
            <div class="input-group mb-3 ip_emmail border border-black rounded">
                <input type="text" class="form-control" placeholder="Nhập email đăng ký nhận tin" aria-label="Recipient's username" aria-describedby="button-addon2">
                <button class="btn btn-dark text-light fw-bold" type="button" id="button-addon2">ĐĂNG KÝ</button>
            </div>
        </div>
    </div>

