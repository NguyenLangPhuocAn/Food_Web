<?php
session_start();
include 'config/db.php';

if ($conn->connect_error) {
    die("Kết nối cơ sở dữ liệu thất bại: " . $conn->connect_error);
}

$ThongBao = '';
$ForgotMessage = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['login_submit'])) {
        $Username = trim($_POST['username'] ?? '');
        $Password = trim($_POST['password'] ?? '');

        if (empty($Username) || empty($Password)) {
            $ThongBao = "Vui lòng điền đầy đủ thông tin.";
        } else {
            $Stmt = $conn->prepare("SELECT * FROM users WHERE Username = ?");
            if (!$Stmt) {
                $ThongBao = "Lỗi truy vấn: " . $conn->error;
            } else {
                $Stmt->bind_param("s", $Username);
                $Stmt->execute();
                $Result = $Stmt->get_result();

                if ($Result->num_rows === 1) {
                    $User = $Result->fetch_assoc();
                    if ($Password === $User['Password']) {
                        $_SESSION['loggedin'] = true;
                        $_SESSION['username'] = $Username;
                        header("Location: QuanLyMonAn.php");
                        exit;
                    } else {
                        $ThongBao = "Mật khẩu không đúng";
                    }
                } else {
                    $ThongBao = "Tài khoản không tồn tại.";
                }
                $Stmt->close();
            }
        }
    } elseif (isset($_POST['forgot_submit'])) {
        $Username = trim($_POST['forgot_username'] ?? '');
        if (empty($Username)) {
            $ForgotMessage = "Vui lòng nhập tên tài khoản.";
        } else {
            $Stmt = $conn->prepare("SELECT Password FROM users WHERE Username = ?");
            if (!$Stmt) {
                $ForgotMessage = "Lỗi truy vấn: " . $conn->error;
            } else {
                $Stmt->bind_param("s", $Username);
                $Stmt->execute();
                $Result = $Stmt->get_result();
                if ($Result->num_rows === 1) {
                    $User = $Result->fetch_assoc();
                    $ForgotMessage = "Mật khẩu của bạn là: " . $User['Password'];
                    echo "<script>alert('Mật khẩu của bạn là: " . $User['Password'] . "');</script>";
                } else {
                    $ForgotMessage = "Tài khoản không tồn tại.";
                    echo "<script>alert('Tài khoản không tồn tại.');</script>";
                }
                $Stmt->close();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - Foody Delight</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #ff9800, #ff5722);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-container h2 {
            color: #ff9800;
            margin-bottom: 20px;
        }
        .login-container input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
        }
        .login-container button {
            width: 100%;
            padding: 12px;
            background-color: #ff9800;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            box-sizing: border-box;
            margin-top: 10px;
        }
        .login-container button:hover {
            background-color: #f57c00;
        }
        .thongbao {
            color: red;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .forgot-password-link {
            text-align: right;
            margin-top: 10px;
        }
        .forgot-password-link a {
            color: #ff9800;
            text-decoration: none;
            font-size: 14px;
        }
        .forgot-password-link a:hover {
            text-decoration: underline;
        }
        .forgot-form {
            display: none;
            margin-top: 20px;
        }
        .forgot-form.active {
            display: block;
        }
        @media (max-width: 500px) {
            .login-container {
                padding: 20px;
                margin: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Đăng Nhập Quản Lý</h2>
        <?php if (!empty($ThongBao)) : ?>
            <p class="thongbao"><?php echo $ThongBao; ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Tên đăng nhập" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <button type="submit" name="login_submit">Đăng nhập</button>
        </form>
        <div class="forgot-password-link">
            <a href="#" onclick="toggleForgotForm()">Quên mật khẩu?</a>
        </div>
        <div class="forgot-form">
            <h2>Khôi Phục Mật Khẩu</h2>
            <?php if (!empty($ForgotMessage)) : ?>
                <p class="thongbao"><?php echo $ForgotMessage; ?></p>
            <?php endif; ?>
            <form method="POST">
                <input type="text" name="forgot_username" placeholder="Tên tài khoản" required>
                <button type="submit" name="forgot_submit">Xem mật khẩu</button>
            </form>
        </div>
    </div>

    <script>
        function toggleForgotForm() {
            const forgotForm = document.querySelector('.forgot-form');
            forgotForm.classList.toggle('active');
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>