<!-- Form chỉnh sửa sản phẩm -->
<div class="main-content">
    <a href="/php2/ASMC/admin/product" class="btn btn-dark">Quay về</a>
    <form action="/php2/ASMC/admin/product/edit/<?= $pro['product_id'] ?>" method="post" class="fw-semibold" enctype="multipart/form-data">
        <div class="d-flex gap-2 justify-content-around">
            <div class="w-50">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" name="name" value="<?= $pro['name'] ?>">
            </div>
            <div class="w-50">
                <label for="price" class="form-label">Price</label>
                <input type="number" class="form-control" id="price" name="price" value="<?= $pro['price'] ?>">
            </div>
        </div>
        <div class="d-flex gap-2 justify-content-around">
            <div class="w-50">
                <label for="discount" class="form-label">Discount</label>
                <input type="number" class="form-control" id="discount" name="discount" value="<?= $pro['discount'] ?>">
            </div>
            <div class="w-50">
                <label for="category_name" class="form-label">Category</label>
                <select name="category_name" id="category_name" class="form-control">
                    <option value="">Chọn danh mục</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" <?=$category['id'] == $pro['id_category'] ? 'selected' : ''; ?>>
                            <?= $category['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>

            </div>
        </div>

        <label class="form-label fw-bold mt-2">Colors</label>
        <div class="btn-group d-grid gap-2 mb-3"
            style="grid-template-columns: repeat(auto-fill, minmax(30px, 1fr));"
            role="group"
            aria-label="Màu sắc">
            <?php foreach ($listcolor as $color): ?>
                <button type="button" class="btn color-btn1 border border-dark border-1
                    <?= ($color['id'] == $pro['id_colors']) ? 'active' : '' ?>"
                    data-color="<?= $color['id'] ?>"
                    onclick="selectColor(<?= $color['id'] ?>, '<?= $color['hex_code'] ?>')"
                    style="background-color: <?= $color['hex_code'] ?>;
                    width: 30px; 
                    height: 30px; 
                    border-radius: 50%;
                    transition: transform 0.2s ease;
                    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);">
                </button>
            <?php endforeach; ?>

            <input type="hidden" name="colorn" id="colorn" value="<?=$pro['id_colors']?>">
        </div>
        <div class="mb-3">
            <label for="mainImageUpload" class="form-label">Main image</label>
            <input type="file" class="form-control" id="mainImageUpload" name="main_image">
        </div>
        <div id="mainImagePreview" class="mb-3">
            <img src="/php2/ASMC/app/public/img/products/<?= $pro['image'] ?>" alt="Product Image" width="100">
        </div>
        <div class="mb-3">
            <label for="thumbnailUpload" class="form-label">Thumbnail</label>
            <input type="file" name="thumbnails[]" id="thumbnailUpload" class="form-control" multiple>
        </div>
        <div class="d-flex flex-wrap gap-2" id="thumbnailPreview">
            <?php foreach ($imagedetail as $product): ?>
                    <img src="/php2/ASMC/app/public/img/products/<?= $product ?>" alt="Product Image" style="object-fit: cover;" width="100" height="100px">
            <?php endforeach; ?>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="10"><?=$pro['description']?></textarea>
        </div>
        <div class="d-flex justify-content-end gap-2">
            <button type="reset" class="btn btn-secondary">Đặt lại</button>
            <button type="submit" class="btn btn-dark" name="editP">Sửa sản phẩm</button>
        </div>
    </form>
</div>
<script>
    function selectColor(colorId, colorHex) {
        document.getElementById("colorn").value = colorId;

        document.querySelectorAll(".color-btn").forEach(btn => {
            btn.style.transform = "scale(1)";
        });

        let selectedBtn = document.querySelector(`[data-color='${colorId}']`);
        selectedBtn.style.transform = "scale(1.2)";
    }
</script>