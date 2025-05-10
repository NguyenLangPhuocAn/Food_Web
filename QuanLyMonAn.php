<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: Login.php");
    exit;
}

include 'config/db.php';

// Function lấy danh sách món ăn
function LayDanhSachMonAn($conn) {
    $Search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $Query = "SELECT * FROM MonAn";
    
    // Nếu có từ khóa tìm kiếm, thêm điều kiện WHERE
    if (!empty($Search)) {
        $Query .= " WHERE Ten LIKE ?";
        $SearchParam = "%" . $Search . "%";
        $Stmt = mysqli_prepare($conn, $Query);
        mysqli_stmt_bind_param($Stmt, "s", $SearchParam);
        mysqli_stmt_execute($Stmt);
        $Result = mysqli_stmt_get_result($Stmt);
    } else {
        $Result = mysqli_query($conn, $Query);
    }
    
    $MonAnList = [];
    while ($row = mysqli_fetch_assoc($Result)) {
        $MonAnList[] = $row;
    }
    
    if (!empty($Search)) {
        mysqli_stmt_close($Stmt);
    }
    
    return $MonAnList;
}

// Function thêm món ăn
function ThemMonAn($conn) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
        $Ten = trim($_POST['Ten']);
        $NoiDung = trim($_POST['Noi_Dung']);
        $Gia = trim($_POST['Gia']);
        
        if (empty($Ten) || empty($NoiDung) || empty($Gia)) {
            echo "Vui lòng nhập đầy đủ thông tin.";
            return;
        }
        if (!is_numeric($Gia) || $Gia <= 0) {
            echo "Giá phải là số dương.";
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

        $Query = "INSERT INTO MonAn (Ten, Noi_Dung, Gia, Image) VALUES (?, ?, ?, ?)";
        $Stmt = mysqli_prepare($conn, $Query);
        mysqli_stmt_bind_param($Stmt, "ssis", $Ten, $NoiDung, $Gia, $Image);
        if ($Stmt->execute()) {
            header("Location: QuanLyMonAn.php");
            exit();
        } else {
            echo "Lỗi khi thêm món ăn.";
        }
        $Stmt->close();
    }
}

// Function sửa món ăn
function SuaMonAn($conn) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
        $Id = (int)$_POST['Id'];
        $Ten = trim($_POST['Ten']);
        $NoiDung = trim($_POST['Noi_Dung']);
        $Gia = trim($_POST['Gia']);
        $CurrentImage = $_POST['current_image'];

        if (empty($Ten) || empty($NoiDung) || empty($Gia)) {
            echo "Vui lòng nhập đầy đủ thông tin.";
            return;
        }
        if (!is_numeric($Gia) || $Gia <= 0) {
            echo "Giá phải là số dương.";
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

        $Query = "UPDATE MonAn SET Ten = ?, Noi_Dung = ?, Gia = ?, Image = ? WHERE Id = ?";
        $Stmt = mysqli_prepare($conn, $Query);
        mysqli_stmt_bind_param($Stmt, "ssisi", $Ten, $NoiDung, $Gia, $Image, $Id);
        if ($Stmt->execute()) {
            header("Location: QuanLyMonAn.php");
            exit();
        } else {
            echo "Lỗi khi sửa món ăn.";
        }
        $Stmt->close();
    }
}

// Function xóa món ăn
function XoaMonAn($conn) {
    if (isset($_GET['delete'])) {
        $Id = (int)$_GET['delete'];
        $Query = "SELECT Image FROM MonAn WHERE Id = ?";
        $Stmt = mysqli_prepare($conn, $Query);
        mysqli_stmt_bind_param($Stmt, "i", $Id);
        $Stmt->execute();
        $Result = $Stmt->get_result();
        $Row = $Result->fetch_assoc();
        $Image = $Row['Image'];
        
        $Query = "DELETE FROM MonAn WHERE Id = ?";
        $Stmt = mysqli_prepare($conn, $Query);
        mysqli_stmt_bind_param($Stmt, "i", $Id);
        if ($Stmt->execute()) {
            if (file_exists("images/$Image")) {
                unlink("images/$Image");
            }
            header("Location: QuanLyMonAn.php");
            exit();
        } else {
            echo "Lỗi khi xóa món ăn.";
        }
        $Stmt->close();
    }
}

// Gọi các function xử lý
XoaMonAn($conn);
ThemMonAn($conn);
SuaMonAn($conn);
$DanhSachMonAn = LayDanhSachMonAn($conn);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Món Ăn</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="assets/script.js"></script>
</head>
<body>
    <!-- Sidebar (Cố định) -->
    <aside class="sidebar">
        <div class="logo">
            <img width="80%" src="images/logo.jpg" alt="Logo">
        </div>
        <ul>
            <li><a href="QuanLyMonAn.php" class="active"><i class="fas fa-utensils"></i> Quản lý món ăn</a></li>
            <li><a href="QuanLyLienHeGopY.php"><i class="fas fa-envelope"></i> Quản lý liên hệ góp ý</a></li>
            <li><a href="QuanLyTinTuc.php"><i class="fas fa-newspaper"></i> Quản lý tin tức</a></li>
            <li><a href="Logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
        </ul>
    </aside>

    <div class="container">
        <div class="content">
            <!-- Form Thêm Món Ăn -->
            <div class="overlay" id="AddForm" style="display: none;">
                <div class="form-container">
                    <h2>🆕 Thêm Món Ăn</h2>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="text" name="Ten" placeholder="Tên Món Ăn" required class="form-input">
                        <textarea name="Noi_Dung" placeholder="Mô Tả" required class="form-input"></textarea>
                        <input type="number" name="Gia" placeholder="Giá" required class="form-input">
                        <input type="file" name="Image" accept="image/*" required class="form-input">
                        <input type="hidden" name="add" value="1">
                        <button type="submit" class="form-button">➕ Thêm</button>
                    </form>
                    <button class="close-btn" onclick="closeForm('AddForm')">❌ Đóng</button>
                </div>
            </div>

            <!-- Bảng danh sách món ăn -->
            <table>
                <tr class="Title-Table">
                    <th style="text-align: left; padding-left: 10px;" colspan="1">
                        <form method="GET" action="" style="display: inline-flex; align-items: center;">
                            <input type="search" name="search" placeholder="Tìm món ăn..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" class="search-input">
                            <button type="submit" class="search-button"><i class="fas fa-search"></i></button>
                        </form>
                    </th>
                    <th style="text-align: center;" colspan="3">Danh Sách Món Ăn</th>
                    <th style="text-align: right; padding-right: 10px;" colspan="1">
                        <button id="openFormBtn" onclick="openForm('AddForm')" class="add-button">+</button>
                    </th>
                </tr>
                <tr>
                    <th>Tên</th>
                    <th>Mô Tả</th>
                    <th>Giá</th>
                    <th>Hình Ảnh</th>
                    <th>Hành Động</th>
                </tr>
                <?php if (empty($DanhSachMonAn)): ?>
                    <tr>
                        <td colspan="5">Không tìm thấy món ăn nào.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($DanhSachMonAn as $Mon) : ?>
                        <tr>
                            <td><?= htmlspecialchars($Mon['Ten']) ?></td>
                            <td><?= htmlspecialchars_decode(substr($Mon['Noi_Dung'], 0, 100)) ?>...</td>
                            <td><?= number_format($Mon['Gia'], 0, ',', '.') ?>đ</td>
                            <td>
                                <?php if (!empty($Mon['Image'])) : ?>
                                    <img src="images/<?= htmlspecialchars($Mon['Image']) ?>" width="50" alt="Ảnh Món Ăn">
                                <?php else : ?>
                                    Không có ảnh
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn-edit" onclick="openForm('EditForm<?= $Mon['Id'] ?>')">✏️ Sửa</button>
                                <button class="btn-delete" onclick="Xoa(<?= $Mon['Id'] ?>)">🗑️ Xóa</button>
                            </td>
                        </tr>

                        <!-- Form sửa món ăn riêng cho từng tin -->
                        <div class="overlay" id="EditForm<?= $Mon['Id'] ?>" style="display: none;">
                            <div class="form-container">
                                <h2>Sửa Món Ăn</h2>
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="Id" value="<?= $Mon['Id'] ?>">
                                    <input type="text" name="Ten" value="<?= htmlspecialchars($Mon['Ten']) ?>" placeholder="Tên Món Ăn" required class="form-input">
                                    <textarea name="Noi_Dung" placeholder="Mô Tả" required class="form-input"><?= htmlspecialchars($Mon['Noi_Dung']) ?></textarea>
                                    <input type="number" name="Gia" value="<?= htmlspecialchars($Mon['Gia']) ?>" placeholder="Giá" required class="form-input">
                                    <input type="file" name="Image" accept="image/*" class="form-input">
                                    <input type="hidden" name="current_image" value="<?= htmlspecialchars($Mon['Image']) ?>">
                                    <input type="hidden" name="edit" value="1">
                                    <button type="submit" class="form-button">✏️ Cập nhật</button>
                                </form>
                                <button class="close-btn" onclick="closeForm('EditForm<?= $Mon['Id'] ?>')">❌ Đóng</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </div>
    </div>
</body>
</html>