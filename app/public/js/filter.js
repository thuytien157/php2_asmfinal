document.addEventListener("DOMContentLoaded", function () {
  // console.log('jsnnnn');
  // alert("Script đã chạy!");

  var openModalBtn = document.getElementById("open-filter-modal");
  var closeModalBtn = document.getElementById("close-filter-modal");
  var modal = document.getElementById("filter-modal");

  if (openModalBtn && closeModalBtn && modal) {
    openModalBtn.addEventListener("click", function () {
      modal.classList.add("open");
    });

    closeModalBtn.addEventListener("click", function () {
      modal.classList.remove("open");
    });

    modal.addEventListener("click", function (e) {
      if (e.target === modal) {
        modal.classList.remove("open");
      }
    });
  } else {
    console.log("Không tìm thấy phần tử modal hoặc button.");
  }

  const minPrice = document.getElementById("min_price");
  const maxPrice = document.getElementById("max_price");
  const priceMinText = document.getElementById("price_min");
  const priceMaxText = document.getElementById("price_max");

  minPrice.addEventListener("input", function () {
    const formattedMinPrice = new Intl.NumberFormat("vi-VN").format(
      minPrice.value
    );
    priceMinText.textContent = formattedMinPrice + " VND";
    applyFilters();
  });

  maxPrice.addEventListener("input", function () {
    const formattedMaxPrice = new Intl.NumberFormat("vi-VN").format(
      maxPrice.value
    );
    priceMaxText.textContent = formattedMaxPrice + " VND";
    applyFilters();
  });

  document.querySelectorAll(".color-btn1").forEach((button) => {
    button.addEventListener("click", function () {
      document
        .querySelectorAll(".color-btn1")
        .forEach((btn) => btn.classList.remove("active"));

      this.classList.add("active");

      document.getElementById("selected_color").value =
        this.getAttribute("data-color");

      applyFilters();
    });
  });

  document.querySelectorAll(".filter-input").forEach((filter) => {
    filter.addEventListener("change", function () {
      applyFilters();
    });
  });

  function applyFilters() {
    let formData = new FormData(document.querySelector("#filter-modal form"));
    fetch("/php2/ASMC/product/filter", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((products) => {
        if (!Array.isArray(products)) {
          console.error("❌ API không trả về một mảng!");
          return;
        }

        let productContainer = document.getElementById("dssp");
        productContainer.innerHTML = renderProducts(products);
      })
      .catch((error) => console.error("❗ Lỗi khi lọc sản phẩm:", error));
  }

  document.querySelectorAll("#filter-modal input").forEach((input) => {
    input.addEventListener("change", applyFilters);
  });

  function renderProducts(products) {
    return products
      .map((product) => {
        let finalPrice = product.discount
          ? Math.round(product.price - product.price * (product.discount / 100))
          : Math.round(product.price);

        let formattedFinalPrice =
          new Intl.NumberFormat("vi-VN").format(finalPrice) + "đ";
        let formattedOriginalPrice =
          new Intl.NumberFormat("vi-VN").format(product.price) + "đ";

        return `
                <div class="col-12 col-md-6 col-lg-3">
                    <a href="/php2/ASMC/product/detail/${
                      product.id_product
                    }" class="card text-decoration-none">
                        ${
                          product.discount
                            ? `<div class="position-absolute top-0 start-0 badge text-bg-danger m-2">${product.discount}%</div>`
                            : ""
                        }
                        <img src="/php2/ASMC/app/public/img/products/${
                          product.image
                        }" class="card-img-top small-img img-fluid ${
          product.status === "inactive" ? "opacity-50" : ""
        }" alt="${product.name}">
                        <div class="card-body bg-body">
                            ${
                              product.status === "active"
                                ? `
                                <form action="/php2/ASMC/addToCart" method="POST">
                                    <input type="hidden" name="id" value="${
                                      product.id_product
                                    }">
                                    <input type="hidden" name="product_name" value="${
                                      product.name
                                    }">
                                    <input type="hidden" name="product_price" value="${finalPrice}">
                                    <input type="hidden" name="product_image" value="${
                                      product.image
                                    }">
                                    <input type="hidden" name="quantity" value="1" min="1" class="form-control w-25">
                                    <button class="position-absolute top-0 end-0 m-2 border border-0 giohang" name="addToCart" type="submit">
                                        <i class="fa fa-shopping-bag fs-4 gh m-2"></i>
                                    </button>
                                </form>
                                <p class="card-text fw-bolder">${
                                  product.name
                                }</p>
                                <p class="card-text fw-bold text-danger fs-5">
                                    ${
                                      product.discount
                                        ? `
                                        ${formattedFinalPrice}
                                        <del class="text-dark fs-6">${formattedOriginalPrice}</del>
                                    `
                                        : formattedOriginalPrice
                                    }
                                </p>
                            `
                                : `<div class="card-footer text-dark fw-semibold">Sản phẩm này hiện tại đang ngừng kinh doanh</div>`
                            }
                        </div>
                    </a>
                </div>
            `;
      })
      .join("");
  }
});
