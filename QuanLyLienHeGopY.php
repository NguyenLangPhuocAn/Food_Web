<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: Login.php");
    exit;
}
include 'config/db.php';

function LayDanhSachLienHe($conn)
{
    $Search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $Query = "SELECT * FROM lienhe ORDER BY Id DESC";
    
    if (!empty($Search)) {
        $Query = "SELECT * FROM lienhe WHERE Sdt LIKE ? ORDER BY Id DESC";
        $SearchParam = "%" . $Search . "%";
        $Stmt = mysqli_prepare($conn, $Query);
        mysqli_stmt_bind_param($Stmt, "s", $SearchParam);
        mysqli_stmt_execute($Stmt);
        $Result = mysqli_stmt_get_result($Stmt);
    } else {
        $Result = mysqli_query($conn, $Query);
    }
    
    $LienHeList = [];
    while ($row = mysqli_fetch_assoc($Result)) {
        $LienHeList[] = $row;
    }
    
    if (!empty($Search)) {
        mysqli_stmt_close($Stmt);
    }
    
    return $LienHeList;
}

function XoaLienHe($conn)
{
    if (isset($_GET['delete'])) {
        $Id = (int)$_GET['delete'];
        $Query = "DELETE FROM lienhe WHERE Id = ?";
        $Stmt = mysqli_prepare($conn, $Query);
        mysqli_stmt_bind_param($Stmt, "i", $Id);
        mysqli_stmt_execute($Stmt);
        header("Location: QuanLyLienHeGopY.php");
        exit();
    }
}

XoaLienHe($conn);
$DanhSachLienHe = LayDanhSachLienHe($conn);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý liên hệ góp ý</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="assets/script.js"></script>
    <style>
        .Title-Table th:nth-child(2) {
            text-align: left !important;
        }
        td:nth-child(4) {
            padding-right: 20px !important;
            text-align: leftleft !important;
        }
        td:nth-child(4) button {
            margin-left: 5px !important;
        }
    </style>
</head>
<body>
    <script>
    function Xoa(id) {
        if (confirm('Bạn có chắc muốn xóa liên hệ này?')) {
            window.location.href = 'QuanLyLienHeGopY.php?delete=' + id;
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
            <li><a href="QuanLyLienHeGopY.php" class="active"><i class="fas fa-envelope"></i> Quản lý liên hệ góp ý</a></li>
            <li><a href="QuanLyTinTuc.php"><i class="fas fa-newspaper"></i> Quản lý tin tức</a></li>
            <li><a href="Logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
        </ul>
    </aside>

    <div class="container">
        <div class="content">
            <!-- Bảng danh sách liên hệ -->
            <table>
                <tr class="Title-Table">
                    <th style="text-align: left; padding-left: 10px;" colspan="1">
                        <form method="GET" action="" style="display: inline-flex; align-items: center;">
                            <input type="search" name="search" placeholder="Tìm theo số điện thoại..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" class="search-input">
                            <button type="submit" class="search-button"><i class="fas fa-search"></i></button>
                        </form>
                    </th>
                    <th style="text-align: left;" colspan="4">Danh Sách Liên Hệ Góp Ý</th>
                </tr>
                <tr>
                    <th>Họ và Tên</th>
                    <th>Số Điện Thoại</th>
                    <th>Góp Ý</th>
                    <th>Hành động</th>
                </tr>
                <?php if (empty($DanhSachLienHe)): ?>
                    <tr>
                        <td colspan="4">Không tìm thấy liên hệ nào.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($DanhSachLienHe as $LienHe) : ?>
                        <tr>
                            <td><?= htmlspecialchars($LienHe['Ten']) ?></td>
                            <td><?= htmlspecialchars($LienHe['Sdt']) ?></td>
                            <td><?= htmlspecialchars_decode(substr($LienHe['GopY'], 0, 50)) ?>...</td>
                            <td>
                                <button class="btn-delete" onclick="Xoa(<?= $LienHe['Id'] ?>)">🗑️ Xóa</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>