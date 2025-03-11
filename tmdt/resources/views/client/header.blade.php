  <nav class="navbar navbar-expand-lg bg-body-tertiary menu-1">
    <div class="container-fluid">
      <a class="navbar-brand" href="/php2/ASMC">
        <svg xmlns="http://www.w3.org/2000/svg" width="84" height="24" viewBox="0 0 84 24" fill="black">
          <path d="M27.6027 0L17.7745 10.585L14.1671 0H6.94734V0.005L5.41862 0L6.33686 2.365L1.14528 19.9L0 24H7.24501L10.6203 12.505L13.1177 18.435H17.8199L23.8036 12.505L20.4283 24H27.7742L34.8224 0H27.6027ZM75.8708 7.25C75.5933 8.195 74.67 9.205 72.6519 9.205H68.0758L69.2261 5.295H73.8022C75.8153 5.295 76.1483 6.305 75.8708 7.25ZM73.5499 16.585C73.2573 17.595 72.2583 18.71 70.2402 18.71H65.2908L66.5269 14.495H71.4814C73.4944 14.495 73.8526 15.575 73.555 16.585H73.5499ZM83.1208 7.04C84.3317 2.895 82.031 0 75.8203 0H61.86L62.7884 2.2L57.1831 21.68L54.7714 24H69.4078C74.7356 24 79.5336 23.5 80.8807 18.915C81.8696 15.545 80.8858 12.69 79.8464 12.08C80.916 11.575 82.3186 9.77 83.1208 7.04ZM41.1896 18.74H51.3709H51.376C51.418 18.7175 51.4112 18.7212 51.3897 18.733C51.2824 18.7916 50.8087 19.0503 54.2568 17.225L52.1984 23.995H30.6853L32.9961 21.69L38.7527 2.32L37.7891 0H46.694L41.1896 18.74Z" />
        </svg>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a href="/php2/ASMC/home" class="nav-link me-2 fw-semibold">Trang chủ</a>
          </li>
          <ul class="navbar-nav">
            @foreach ($categories as $category)
            <li class="nav-item dropdown">
              <a href="/php2/ASMC/product/category/{{ $category->id }}" class="nav-link me-2 fw-semibold">
                {{ $category->name }}
              </a>

              @if ($category->children->isNotEmpty())
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown{{ $category->id }}">
                @foreach ($category->children as $child)
                <li>
                  <a class="dropdown-item" href="/php2/ASMC/product/category/{{ $child->id }}">
                    {{ $child->name }}
                  </a>
                </li>
                @endforeach
              </ul>
              @endif
            </li>
            @endforeach
          </ul>


          @if (!isset($_SESSION['user']))
          <li class="nav-item">
            <div class="nav-link me-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#registerModal">Đăng ký</div>
          </li>
          <li class="nav-item">
            <div class="nav-link me-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#loginModal">Đăng nhập</div>
          </li>
          @else: ?>
          <?= '' ?>
          @endif
          <li class="nav-item">
            <a class="nav-link me-2 fw-semibold" href="/php2/ASMC/contact">Liên hệ</a>
          </li>
        </ul>
        <div class="align-items-center gap-3 headerm">
          <form action="/php2/ASMC/product/search" method="post" class="d-flex">
            <div class="search-container">
              <input type="search" class="search" name="key" id="search" placeholder="Tìm kiếm">
              <i class="fa fa-search search-icon"></i>
            </div>
          </form>
          <div class="d-flex gap-3 headeruser">
            <div class="d-flex gap-3 mt-2">
              <a href="/php2/ASMC/cart" class="text-dark text-decoration-none fa fa-shopping-bag fs-4"></a>
              <a class="text-dark text-decoration-none fa fa-heart fs-4"></a>
              <a href="/php2/ASMC/admin" class="text-dark text-decoration-none fa fa-lock fs-4"></a>
            </div>

            @if (isset($_SESSION['user']))
            <div class="dropdown">
              <button class="btn btn-secondary fs-6 fw-semibold dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                @echo $_SESSION['user']['username']
              </button>
              <ul class="dropdown-menu dropdown-menu-dark z-3">
                <li><a class="dropdown-item fs-6" href="/php2/ASMC/account">Tài khoản</a></li>
                <li><a class="dropdown-item fs-6" href="/php2/ASMC/user/logout">Đăng xuất</a></li>
              </ul>
            </div>
            @endif
          </div>

        </div>
      </div>
    </div>
  </nav>

  <div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast align-items-center text-bg-dark border-0" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">
          Sản phẩm đã được thêm vào giỏ hàng!
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>

  <!-- Modal Đăng Ký -->
  <div class="modal fade modal-md" id="registerModal" aria-hidden="true" aria-labelledby="registerLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 fw-bold w-100 text-center" id="registerLabel">Đăng ký</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="post" action='/php2/ASMC/user/register'>
            <div class="mb-3">

              <label for="registerUsername" class="form-label fw-bold">Username <sup><i class="fa fa-asterisk text-danger"></i></sup></label>
              <input type="text" class="form-control" id="registerUsername" placeholder="Nhập username của bạn..." name="username">
            </div>
            <div class="mb-3">
              <label for="registerEmail" class="form-label fw-bold">Email <sup><i class="fa fa-asterisk text-danger"></i></sup></label>
              <input type="email" class="form-control" id="registerEmail" placeholder="Nhập email..." name="email">
            </div>
            <div class="mb-3">
              <label for="registerPassword" class="form-label fw-bold">Mật khẩu <sup><i class="fa fa-asterisk text-danger"></i></sup></label>
              <div class="password-container">
                <input type="password" class="form-control" id="password" placeholder="Nhập mật khẩu..." name="password">
                <span class="togglePassword toggle-icon">👁️</span>
              </div>
              <div class="password-container">
                <input type="password" class="form-control" id="repassword" placeholder="Nhập lại mật khẩu..." name="repassword">
                <span class="togglePassword toggle-icon">👁️</span>
              </div>



              <!-- <input type="password" class="form-control" id="registerPassword" placeholder="Nhập mật khẩu..." name="password">
              <input type="password" class="form-control mt-2" id="registerPassword" placeholder="Nhập lại mật khẩu" name="repassword"> -->
            </div>
        </div>
        <div class="modal-footer d-flex flex-column align-items-center">
          <input type="submit" name="register" class="btn btn-dark ps-5 pe-5"></input>
          <div>Hoặc</div>
          <a href="/php2/ASMC/user/google-login" class="google-login-btn mt-2">
            <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google Logo" class="google-icon">
            Đăng nhập với Google
          </a>
          <p class="text-center fw-bold">Đã có tài khoản?
            <a href="#" class="link-danger link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" data-bs-target="#loginModal" data-bs-toggle="modal">Đăng nhập</a>
          </p>
        </div>
        </form>
      </div>

    </div>
  </div>
  </div>

  <!-- Modal Đăng Nhập -->
  <div class="modal fade" id="loginModal" aria-hidden="true" aria-labelledby="loginLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content p-3">
        <div class="modal-header border-0">
          <h1 class="modal-title fs-4 fw-bold w-100 text-center" id="registerLabel">Đăng nhập</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="post" action="/php2/ASMC/user/login">
            <div class="mb-3">
              <label for="loginUsername" class="form-label">Username <sup><i class="fa fa-asterisk text-danger"></i></sup></label>
              <input type="text" class="form-control" id="loginUsername" placeholder="Nhập username của bạn..." name="username">
            </div>
            <div class="mb-3">
              <label for="loginPassword" class="form-label">Mật khẩu <sup><i class="fa fa-asterisk text-danger"></i></sup></label>
              <div class="password-container">
                <input type="password" class="form-control" id="loginPassword" placeholder="Nhập mật khẩu..." name="password">
                <span class="togglePassword toggle-icon">👁️</span>
              </div>
            </div>
            <div class="modal-footer d-flex flex-column align-items-center border-0">
              <button type="submit" name="login" class="btn btn-dark w-100">Đăng nhập</button>
              <a href="/php2/ASMC/user/reset" class="text-dark mt-2">Quên mật khẩu?</a>
              <div class="mt-2">Hoặc</div>
              <a href="/php2/ASMC/user/google-login" class="google-login-btn mt-2">
                <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google Logo" class="google-icon">
                Đăng nhập với Google
              </a>
              <p class="text-center fw-bold mt-3">Chưa có tài khoản?
                <a href="#" class="link-danger" data-bs-target="#registerModal" data-bs-toggle="modal">Đăng ký</a>
              </p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
