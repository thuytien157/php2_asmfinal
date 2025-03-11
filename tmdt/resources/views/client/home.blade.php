@extends('client.app')
@section('title', 'Trang chủ')
@section('content')
<div class="container-xxl text-center">
    <div id="carouselExampleAutoplaying" class="carousel slide z-0" data-bs-ride="carousel" style="margin-top: 80px;">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ Storage::url('img/banner/mlb_desktop_en_be560d199f054efa9d99ae5826e2cdde.webp')}}" class="d-block w-100 img-fluid" alt="...">
            </div>
            <div class="carousel-item">
                <img src="{{ Storage::url('img/banner/msloyalty_mlb_web.webp')}}" class="d-block w-100 img-fluid" alt="...">
            </div>
            <div class="carousel-item">
                <img src="{{ Storage::url('img/banner/1960x640-mua-ngay.webp')}}" class="d-block w-100 img-fluid" alt="...">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>


    <!-- hàng mới về -->
    <div class="row align-items-center mt-2">
        <div class="d-flex justify-content-between flex-wrap">
            <h2 class="fw-bolder text-start ms-2">Hàng mới về</h2>
        </div>
    </div>

    <div class="hangmoive p-2 col-12">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <div class="position-relative overflow-hidden">
                    <img src="{{ Storage::url('img/banner/hangmoive_main.jpg')}}" class="w-100 img-fluid" alt="">
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50 hover-overlay">
                        <a href="/php2/ASMC/product" type="button" class="btn btn-outline-light btn-sm">Xem tất cả</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="row g-2" id="sanpham_slice">
                    @foreach ($products as $product)
                    <div class="col-12 col-sm-6 col-md-6">
                        <a href="/php2/ASMC/product/detail/{{$product->id_product}}" class="card text-decoration-none">
                            @if (!empty($product->discount))
                            <div class="position-absolute top-0 start-0 badge text-bg-danger m-2">{{$product->discount}}%</div>
                            @endif
                            <img src="{{ Storage::url('img/products/' . $product->image) }}"
                                class="card-img-top small-img img-fluid {{ $product->status == 'inactive' ? 'opacity-50' : '' }}"
                                alt="...">
                            <div class="card-body bg-body">


                                @php
                                $finalPrice = !empty($product->discount)
                                ? round($product->price - ($product->price * ($product->discount / 100)), -3)
                                : round($product->price, -3);

                                @endphp


                                @if ($product->status == 'active')
                                <form action="" method="POST" id="add-to-cart-form">
                                    <input type="hidden" name="id" value="{{$product->id_product}}">
                                    <input type="hidden" name="product_name" value="{{$product->name}}">
                                    <input type="hidden" name="product_price" value="{{$finalPrice}}">
                                    <input type="hidden" name="product_image" value="{{$product->image}}">
                                    <input type="hidden" name="quantity" value="1" min="1" class="form-control w-25" id="quantity">
                                    <button class="position-absolute top-0 end-0 m-2 border boder-0 giohang" name="addToCart" type="submit" id="add-to-cart-btn">
                                        <i class="fa fa-shopping-bag fs-4 gh m-2"></i>
                                    </button>
                                </form>

                                <p class="card-text namep fw-bolder">{{$product->name}}</p>
                                <p class="card-text fw-bold text-danger fs-5">
                                    @if (!empty($product->discount))
                                    {{ number_format($product->price - ($product->price * ($product->discount / 100)), 0, '.', '.') }}đ
                                    <del class="text-dark fs-6">{{ number_format($product->price, 0, '.', '.') }}đ</del>
                                    @else
                                    {{ number_format($product->price, 0, '.', '.') }}đ
                                    @endif

                                </p>
                                @else
                                <div class="card-footer text-dark fw-semibold">
                                    Sản phẩm này hiện tại đang ngừng kinh doanh
                                </div>
                                @endif
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>

    <!-- hàng bán chạy -->
    <div class="row align-items-center mt-5 mb-2">
        <div class="d-flex justify-content-between flex-wrap">
            <h2 class="fw-bolder text-start ms-2">Hàng bán chạy</h2>
        </div>
    </div>

    <div class="hangbanchay">
        <div class="row g-3" id="sanpham_slice1">
            @foreach ($products as $product)
            <div class="col-12 col-md-6 col-lg-3">
                <a href="{{ url('product/detail/' . $product->id_product) }}" class="card text-decoration-none">
                    @if (!empty($product->discount))
                    <div class="position-absolute top-0 start-0 badge text-bg-danger m-2">{{ $product->discount }}%</div>
                    @endif
                    <img src="{{ Storage::url('img/products/' . $product->image) }}"
                        class="card-img-top small-img img-fluid {{ $product->status == 'inactive' ? 'opacity-50' : '' }}"
                        alt="...">
                    <div class="card-body bg-body">

                        @php
                        $finalPrice = !empty($product->discount)
                        ? round($product->price - ($product->price * ($product->discount / 100)), -3)
                        : round($product->price, -3);
                        @endphp

                        @if ($product->status == 'active')
                        <form action="" method="POST" id="add-to-cart-form">
                            @csrf
                            <input type="hidden" name="id" value="{{ $product->id_product }}">
                            <input type="hidden" name="product_name" value="{{ $product->name }}">
                            <input type="hidden" name="product_price" value="{{ $finalPrice }}">
                            <input type="hidden" name="product_image" value="{{ $product->image }}">
                            <input type="hidden" name="quantity" value="1" min="1" class="form-control w-25" id="quantity">
                            <button class="position-absolute top-0 end-0 m-2 border border-0 giohang" name="addToCart" type="submit" id="add-to-cart-btn">
                                <i class="fa fa-shopping-bag fs-4 gh m-2"></i>
                            </button>
                        </form>

                        <p class="card-text namep fw-bolder">{{ $product->name }}</p>
                        <p class="card-text fw-bold text-danger fs-5">
                            @if (!empty($product->discount))
                            {{ number_format($product->price - ($product->price * ($product->discount / 100)), 0, '.', '.') }}đ
                            <del class="text-dark fs-6">{{ number_format($product->price, 0, '.', '.') }}đ</del>
                            @else
                            {{ number_format($product->price, 0, '.', '.') }}đ
                            @endif
                        </p>
                        @else
                        <div class="card-footer text-dark fw-semibold">
                            Sản phẩm này hiện tại đang ngừng kinh doanh
                        </div>
                        @endif
                    </div>
                </a>
            </div>
            @endforeach

        </div>
    </div>

    <div class="row align-items-center mt-5 mb-2">
        <div class="d-flex justify-content-between flex-wrap">
            <h2 class="fw-bolder text-start ms-2">Giảm giá</h2>
        </div>
    </div>

    <div class="hangmoive p-2 col-12">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <div class="row g-2" id="sanpham_slice2">
                    @foreach ($products as $product)
                    <div class="col-12 col-sm-6 col-md-6">
                        <a href="{{ url('/php2/ASMC/product/detail/' . $product->id_product) }}" class="card text-decoration-none">
                            @if (!empty($product->discount))
                            <div class="position-absolute top-0 start-0 badge text-bg-danger m-2">{{ $product->discount }}%</div>
                            @endif
                            <img src="{{ Storage::url('img/products/' . $product->image) }}"
                                class="card-img-top small-img img-fluid {{ $product->status == 'inactive' ? 'opacity-50' : '' }}"
                                alt="...">
                            <div class="card-body bg-body">

                                @php
                                $finalPrice = !empty($product->discount)
                                ? round($product->price - ($product->price * ($product->discount / 100)), -3)
                                : round($product->price, -3);

                                @endphp

                                @if ($product->status == 'active')
                                <form action="" method="POST" id="add-to-cart-form">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $product->id_product }}">
                                    <input type="hidden" name="product_name" value="{{ $product->name }}">
                                    <input type="hidden" name="product_price" value="{{ $finalPrice }}">
                                    <input type="hidden" name="product_image" value="{{ $product->image }}">
                                    <input type="hidden" name="quantity" value="1" min="1" class="form-control w-25" id="quantity">
                                    <button class="position-absolute top-0 end-0 m-2 border border-0 giohang" name="addToCart" type="submit" id="add-to-cart-btn">
                                        <i class="fa fa-shopping-bag fs-4 gh m-2"></i>
                                    </button>
                                </form>

                                <p class="card-text namep fw-bolder">{{ $product->name }}</p>
                                <p class="card-text fw-bold text-danger fs-5">
                                    @if (!empty($product->discount))
                                    {{ number_format($product->price - ($product->price * ($product->discount / 100)), 0, '.', '.') }}đ
                                    <del class="text-dark fs-6">{{ number_format($product->price, 0, '.', '.') }}đ</del>
                                    @else
                                    {{ number_format($product->price, 0, '.', '.') }}đ
                                    @endif
                                </p>
                                @else
                                <div class="card-footer text-dark fw-semibold">
                                    Sản phẩm này hiện tại đang ngừng kinh doanh
                                </div>
                                @endif
                            </div>
                        </a>
                    </div>
                    @endforeach

                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="position-relative overflow-hidden">
                    <img src="{{ Storage::url('img/banner/bst_hip.jfif')}}" class="w-100 img-fluid" alt="">
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50 hover-overlay">
                        <a href="/php2/ASMC/product" type="button" class="btn btn-outline-light btn-sm">Xem tất cả</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- bài viết nổi bật -->
    <div class="row align-items-center mt-5 mb-2">
        <div class="d-flex justify-content-between flex-wrap">
            <h2 class="fw-bolder text-start ms-2">Bài viết nổi bật</h2>
        </div>
    </div>
    <div class="baiviet m-2">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card img-fluid bg-dark">
                    <img class="card-img-top opacity-25" src="{{ Storage::url('img/articles/baiviet1.jfif')}}" alt="Card image" style="width:100%">
                    <div class="card-img-overlay text-light mt-5">
                        <p class="card-text">Bộ đôi túi Varsity Jacquard Square và giày Chunky Liner thu hút cực mạnh. Có hai “trợ thủ” trong tủ đồ thì luôn cập nhật trạng thái cực cool👏💃.</p>
                        <a href="#" class="btn btn-outline-light">Xem thêm</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card img-fluid bg-dark">
                    <img class="card-img-top opacity-25" src="{{ Storage::url('img/articles/baiviet2.jfif')}}" alt="Card image" style="width:100%">
                    <div class="card-img-overlay text-light w-100 mt-5">
                        <p class="card-text">Ngoài phiên bản màu trắng thời thượng, Ribbed Ringer Dress cũng tăng tốc gia nhập đường đua và trở thành chiếc đầm được yêu thích nhất.</p>
                        <a href="#" class="btn btn-outline-light">Xem thêm</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card img-fluid bg-dark">
                    <img class="card-img-top opacity-25" src="{{ Storage::url('img/articles/baiviet3.jfif')}}" alt="Card image" style="width:100%">
                    <div class="card-img-overlay text-light w-100 mt-5">
                        <p class="card-text">Dù có bao nhiêu mẫu giày mới, MLB Chunky Liner vẫn luôn là chân ái vì độ trendy, đế chunky hack dáng cùng các phối màu đẹp mê ly ✨
                        </p>
                        <a href="#" class="btn btn-outline-light">Xem thêm</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card img-fluid bg-dark">
                    <img class="card-img-top opacity-25" src="{{ Storage::url('img/articles/baiviet4.jfif')}}" alt="Card image" style="width:100%">
                    <div class="card-img-overlay text-light w-100 mt-5">
                        <p class="card-text">Nếu bạn đang tìm kiếm một chiếc quần Jean cơ bản dễ phối đồ, nhưng vẫn đủ gây ấn tượng, thì MLB Basic Embroidery để dành cho bạn 🤘
                        </p>
                        <a href="#" class="btn btn-outline-light">Xem thêm</a>
                    </div>
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
</div>