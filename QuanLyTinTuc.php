<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: Login.php");
    exit;
}
include 'config/db.php';

function LayDanhSachTinTuc($conn)
{
    $Search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $Query = "SELECT * FROM tintuc ORDER BY Ngay_Dang DESC";
    
    if (!empty($Search)) {
        $Query = "SELECT * FROM tintuc WHERE Tieu_De LIKE ? ORDER BY Ngay_Dang DESC";
        $SearchParam = "%" . $Search . "%";
        $Stmt = mysqli_prepare($conn, $Query);
        mysqli_stmt_bind_param($Stmt, "s", $SearchParam);
        mysqli_stmt_execute($Stmt);
        $Result = mysqli_stmt_get_result($Stmt);
    } else {
        $Result = mysqli_query($conn, $Query);
    }
    
    $TinTucList = [];
    while ($row = mysqli_fetch_assoc($Result)) {
        $TinTucList[] = $row;
    }
    
    if (!empty($Search)) {
        mysqli_stmt_close($Stmt);
    }
    
    return $TinTucList;
}

function ThemTinTuc($conn)
{
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
        $TieuDe = trim($_POST['Tieu_De']);
        $NoiDung = trim($_POST['Noi_Dung']);
        
        if (empty($TieuDe) || empty($NoiDung)) {
            echo "Vui lòng nhập đầy đủ tiêu đề và nội dung.";
            return;
        }

        $Image = '';
        if ($_FILES['Image']['size'] > 0) {
            $TargetDir = "images/";
            $AllowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            $FileName = basename($_FILES['Image']['name']);
            $FileExt = strtolower(pathinfo($FileName, PATHINFO_EXTENSION));
            
            if (!in_array($FileExt, $AllowedTypes)) {
                echo "Chỉ chấp nhận file ảnh (jpg, jpeg, png, gif).";
                return;
            }
            
            $TargetFile = $TargetDir . uniqid() . '.' . $FileExt;
            if (move_uploaded_file($_FILES['Image']['tmp_name'], $TargetFile)) {
                $Image = basename($TargetFile);
            } else {
                echo "Lỗi khi upload hình ảnh.";
                return;
            }
        } else {
            echo "Vui lòng chọn hình ảnh.";
            return;
        }

        $Query = "INSERT INTO tintuc (Tieu_De, Noi_Dung, Image) VALUES (?, ?, ?)";
        $Stmt = mysqli_prepare($conn, $Query);
        mysqli_stmt_bind_param($Stmt, "sss", $TieuDe, $NoiDung, $Image);
        mysqli_stmt_execute($Stmt);
        header("Location: QuanLyTinTuc.php");
        exit();
    }
}

function SuaTinTuc($conn)
{
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
        $Id = (int)$_POST['Id'];
        $TieuDe = trim($_POST['Tieu_De']);
        $NoiDung = trim($_POST['Noi_Dung']);
        $CurrentImage = $_POST['current_image'];

        if (empty($TieuDe) || empty($NoiDung)) {
            echo "Vui lòng nhập đầy đủ tiêu đề và nội dung.";
            return;
        }

        $Image = $CurrentImage;
        if ($_FILES['Image']['size'] > 0) {
            $TargetDir = "images/";
            $AllowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            $FileName = basename($_FILES['Image']['name']);
            $FileExt = strtolower(pathinfo($FileName, PATHINFO_EXTENSION));
            
            if (!in_array($FileExt, $AllowedTypes)) {
                echo "Chỉ chấp nhận file ảnh (jpg, jpeg, png, gif).";
                return;
            }
            
            $TargetFile = $TargetDir . uniqid() . '.' . $FileExt;
            if (move_uploaded_file($_FILES['Image']['tmp_name'], $TargetFile)) {
                $Image = basename($TargetFile);
                if (file_exists("images/$CurrentImage") && $CurrentImage != $Image) {
                    unlink("images/$CurrentImage");
                }
            } else {
                echo "Lỗi khi upload hình ảnh.";
                return;
            }
        }

        $Query = "UPDATE tintuc SET Tieu_De = ?, Noi_Dung = ?, Image = ? WHERE Id = ?";
        $Stmt = mysqli_prepare($conn, $Query);
        mysqli_stmt_bind_param($Stmt, "sssi", $TieuDe, $NoiDung, $Image, $Id);
        mysqli_stmt_execute($Stmt);
        header("Location: QuanLyTinTuc.php");
        exit();
    }
}

function XoaTinTuc($conn)
{
    if (isset($_GET['delete'])) {
        $Id = (int)$_GET['delete'];

        $Query = "SELECT Image FROM tintuc WHERE Id = ?";
        $Stmt = mysqli_prepare($conn, $Query);
        mysqli_stmt_bind_param($Stmt, "i", $Id);
        mysqli_stmt_execute($Stmt);
        $Result = mysqli_stmt_get_result($Stmt);
        $Row = mysqli_fetch_assoc($Result);
        $Image = $Row['Image'];
        
        $Query = "DELETE FROM tintuc WHERE Id = ?";
        $Stmt = mysqli_prepare($conn, $Query);
        mysqli_stmt_bind_param($Stmt, "i", $Id);
        mysqli_stmt_execute($Stmt);
        
        if (file_exists("images/$Image")) {
            unlink("images/$Image");
        }
        
        header("Location: QuanLyTinTuc.php");
        exit();
    }
}

XoaTinTuc($conn);
ThemTinTuc($conn);
SuaTinTuc($conn);
$DanhSachTinTuc = LayDanhSachTinTuc($conn);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý tin tức</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="assets/script.js"></script>
    <script src="https://cdn.tiny.cloud/1/ywcmi1zc0u8qenqyw6xohk81gquvxqlrhylnqv2esuik85m7/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <style>
        .Title-Table th:nth-child(2) {
            text-align: center !important;
        }

    </style>
</head>
<body>
    <script>
    tinymce.init({
        selector: 'textarea',
        plugins: [
            'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
            'checklist', 'mediaembed', 'casechange', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'editimage', 'advtemplate', 'ai', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown','importword', 'exportword', 'exportpdf'
        ],
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        mergetags_list: [
            { value: 'First.Name', title: 'First Name' },
            { value: 'Email', title: 'Email' },
        ],
        ai_request: (request, respondWith) => respondWith.string(() => Promise.reject('See docs to implement AI Assistant')),
    });

    function openForm(formId) {
        document.getElementById(formId).style.display = 'flex';
    }

    function closeForm(formId) {
        document.getElementById(formId).style.display = 'none';
    }

    function Xoa(id) {
        if (confirm('Bạn có chắc muốn xóa tin tức này?')) {
            window.location.href = 'QuanLyTinTuc.php?delete=' + id;
        }
    }
    </script>

    <!-- Sidebar (Cố định) -->
    <aside class="sidebar">
        <div class="logo">
            <img width="80%" src="images/logo.jpg" alt="Logo">
        </div>
        <ul>
            <li><a href="QuanLyMonAn.php"><i class="fas fa-utensils"></i> Quản lý món ăn</a></li>
            <li><a href="QuanLyLienHeGopY.php"><i class="fas fa-envelope"></i> Quản lý liên hệ góp ý</a></li>
            <li><a href="QuanLyTinTuc.php" class="active"><i class="fas fa-newspaper"></i> Quản lý tin tức</a></li>
            <li><a href="Logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
        </ul>
    </aside>

    <div class="container">
        <div class="content">
            <!-- Form Thêm tin tức -->
            <div class="overlay" id="AddForm" style="display: none;">
                <div class="form-container">
                    <h2>🆕 Thêm Tin Tức</h2>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="text" name="Tieu_De" placeholder="Tiêu đề" required class="form-input">
                        <textarea name="Noi_Dung" placeholder="Nội dung" required class="form-input"></textarea>
                        <input type="file" name="Image" accept="image/*" required class="form-input">
                        <input type="hidden" name="add" value="1">
                        <button type="submit" class="form-button">➕ Thêm</button>
                    </form>
                    <button class="close-btn" onclick="closeForm('AddForm')">❌ Đóng</button>
                </div>
            </div>

            <!-- Bảng danh sách tin tức -->
            <table>
                <tr class="Title-Table">
                    <th style="text-align: left; padding-left: 10px;" colspan="1">
                        <form method="GET" action="" style="display: inline-flex; align-items: center;">
                            <input type="search" name="search" placeholder="Tìm tin tức..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" class="search-input">
                            <button type="submit" class="search-button"><i class="fas fa-search"></i></button>
                        </form>
                    </th>
                    <th style="text-align: center;" colspan="3">Danh Sách Tin Tức</th>
                    <th style="text-align: right; padding-right: 10px;" colspan="1">
                        <button id="openFormBtn" onclick="openForm('AddForm')" class="add-button">+</button>
                    </th>
                </tr>
                <tr>
                    <th>Tiêu đề</th>
                    <th>Nội dung</th>
                    <th>Hình ảnh</th>
                    <th colspan="2">Hành động</th>
                </tr>
                <?php if (empty($DanhSachTinTuc)): ?>
                    <tr>
                        <td colspan="4">Không tìm thấy tin tức nào.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($DanhSachTinTuc as $Tin) : ?>
                        <tr>
                            <td><?= htmlspecialchars($Tin['Tieu_De']) ?></td>
                            <td><?= htmlspecialchars_decode(substr($Tin['Noi_Dung'], 0, 100)) ?>...</td>
                            <td>
                                <?php if (!empty($Tin['Image'])) : ?>
                                    <img src="images/<?= htmlspecialchars($Tin['Image']) ?>" width="80" height="80" alt="Ảnh Tin Tức">
                                <?php else : ?>
                                    Không có ảnh
                                <?php endif; ?>
                            </td>
                            <td colspan="2">
                                <button class="btn-edit" onclick="openForm('EditForm<?= $Tin['Id'] ?>')">✏️ Sửa</button>
                                <button class="btn-delete" onclick="Xoa(<?= $Tin['Id'] ?>)">🗑️ Xóa</button>
                            </td>
                        </tr>

                        <!-- Form sửa tin tức riêng cho từng tin -->
                        <div class="overlay" id="EditForm<?= $Tin['Id'] ?>" style="display: none;">
                            <div class="form-container">
                                <h2>Sửa Tin Tức</h2>
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="Id" value="<?= $Tin['Id'] ?>">
                                    <input type="text" name="Tieu_De" value="<?= htmlspecialchars($Tin['Tieu_De']) ?>" placeholder="Tiêu đề" required class="form-input">
                                    <textarea name="Noi_Dung" placeholder="Nội dung" required class="form-input"><?= htmlspecialchars($Tin['Noi_Dung']) ?></textarea>
                                    <input type="file" name="Image" accept="image/*" class="form-input">
                                    <input type="hidden" name="current_image" value="<?= htmlspecialchars($Tin['Image']) ?>">
                                    <input type="hidden" name="edit" value="1">
                                    <button type="submit" class="form-button">✏️ Cập nhật</button>
                                </form>
                                <button class="close-btn" onclick="closeForm('EditForm<?= $Tin['Id'] ?>')">❌ Đóng</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </div>
    </div>
</body>
</html>