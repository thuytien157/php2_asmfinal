document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("mainImageUpload").addEventListener("change", function (event) {
    let previewContainer = document.getElementById("mainImagePreview");
    previewContainer.innerHTML = ""; // Xóa ảnh cũ

    let file = event.target.files[0];
    if (file) {
        let reader = new FileReader();
        reader.onload = function (e) {
            let img = document.createElement("img");
            img.src = e.target.result;
            img.style.width = "100px";
            img.style.height = "100px";
            img.style.objectFit = "cover";
            img.style.borderRadius = "8px";
            previewContainer.appendChild(img);
        };
        reader.readAsDataURL(file);
    }
});

document.getElementById("thumbnailUpload").addEventListener("change", function (event) {
    let previewContainer = document.getElementById("thumbnailPreview");
    previewContainer.innerHTML = ""; 

    let files = event.target.files;
    Array.from(files).forEach(file => {
        let reader = new FileReader();
        reader.onload = function (e) {
            let img = document.createElement("img");
            img.src = e.target.result;
            img.style.width = "100px";
            img.style.height = "100px";
            img.style.objectFit = "cover";
            img.style.margin = "5px";
            img.style.borderRadius = "8px";
            previewContainer.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
});
});
