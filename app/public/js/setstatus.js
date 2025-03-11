document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("select").forEach(select => {
        let currentStatus = select.value;
        disableOldStatuses(select, currentStatus);
    });
});

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("select").forEach(select => {
        let currentStatus = select.value;
        disableOldStatuses(select, currentStatus);
    });
});

function disableOldStatuses(selectElement, currentStatus) {
    const statusOrder = [
        "Chờ xác nhận",
        "Đang chuẩn bị hàng",
        "Đang giao hàng",
        "Giao thành công",
        "Đơn thất bại"
    ];

    let currentIndex = statusOrder.indexOf(currentStatus);
    if (currentStatus === "Giao thành công" || currentStatus === "Đơn thất bại") {
        selectElement.disabled = true;
    }
    Array.from(selectElement.options).forEach((option) => {
        let optionIndex = statusOrder.indexOf(option.value);

        if (option.value === "Đơn thất bại") {
            option.disabled = false;
        } else {
            option.disabled = optionIndex < currentIndex || optionIndex > currentIndex + 1;
        }
    });
}


function checkStatus(event, id, currentStatus) {
    let selectElement = event.target;
    let selectedStatus = selectElement.value;

    const statusOrder = [
        "Chờ xác nhận",
        "Đang chuẩn bị hàng",
        "Đang giao hàng",
        "Giao thành công",
        "Đơn thất bại"
    ];

    let currentIndex = statusOrder.indexOf(currentStatus);
    let selectedIndex = statusOrder.indexOf(selectedStatus);



    let confirmChange = confirm(`Bạn có chắc chắn muốn chuyển trạng thái thành "${selectedStatus}" không?`);
    if (confirmChange) {
        document.getElementById("statusForm" + id).submit();
    } else {
        event.target.value = currentStatus;
    }
}
