function confirmRoleChange(id) {
    let form = document.getElementById('roleForm' + id);
    let select = form.querySelector('select');
    let selectedRole = select.options[select.selectedIndex].text;

    let confirmChange = confirm("Bạn có chắc chắn muốn đổi quyền thành: " + selectedRole + "?");
    if (confirmChange) {
        form.submit();
    } else {
        // Hoàn tác lựa chọn về giá trị cũ
        select.value = select.getAttribute("data-current");
    }
}

// Lưu lại giá trị ban đầu của select khi tải trang
document.addEventListener("DOMContentLoaded", function() {
    let selects = document.querySelectorAll("select[name='role']");
    selects.forEach(select => {
        select.setAttribute("data-current", select.value);
    });
});