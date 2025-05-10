function openForm(formId) {
    document.getElementById(formId).style.display = "flex";
}

function closeForm(formId) {
    document.getElementById(formId).style.display = "none";
}

function Xoa(id) {
    if (confirm("Bạn có chắc muốn xóa?")) {
        window.location.href = "?delete="+id;
    }
}