<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/php2/ASMC/app/public/css/css.css">
    <script src="/php2/ASMC/app/public/js/setstatus.js"></script>
    <script src="/php2/ASMC/app/public/js/openpopup.js"></script>
    <script src="/php2/ASMC/app/public/js/showpreview.js"></script>
    <script src="/php2/ASMC/app/public/js/setrole.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>
    <div class="header">
        <button class="menu-btn" id="menuToggle">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <h2><?= $_SESSION['user']['username'] ?? 'Admin'; ?></h2>
        </div>
        <ul class="sidebar-menu mt-0">
            <li><a href="/php2/ASMC/admin"><i class="fas fa-tachometer-alt"></i> Tổng quan</a></li>
            <li><a href="/php2/ASMC/admin/users"><i class="fas fa-users"></i> Người dùng</a></li>
            <li><a href="/php2/ASMC/admin/category"><i class="fas fa-list"></i> Danh mục</a></li>

            <li>
                <div class="d-flex">
                    <a href="/php2/ASMC/admin/product" class="d-flex align-items-center flex-grow-1">
                        <i class="fas fa-box"></i> Sản phẩm
                    </a>
                    <a href="#submenuProduct" data-bs-toggle="collapse">
                        <i class="fas fa-chevron-down"></i>
                    </a>
                </div>
                <ul id="submenuProduct" class="collapse list-unstyled ps-3">
                    <li><a href="/php2/ASMC/admin/color"><i class="fas fa-palette"></i> Màu sắc</a></li>
                </ul>
            </li>

            <li><a href="/php2/ASMC/admin/order"><i class="fas fa-shopping-cart"></i> Đơn hàng</a></li>
        </ul>




    </div>
</body>

</html>
<script>
    document.getElementById("menuToggle").addEventListener("click", function() {
        document.getElementById("sidebar").classList.toggle("active");
    });
</script>