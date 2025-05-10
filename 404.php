<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Lỗi 404 - Không tìm thấy trang - Foody Delight">
    <meta name="keywords" content="Foody Delight, lỗi 404, không tìm thấy">
    <title>404 - Không tìm thấy - Foody Delight</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        html {
            scroll-behavior: smooth;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
        }
        header {
            background-color: #ff9800;
            color: white;
            padding: 20px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        nav {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 10px;
        }
        nav a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        nav a:hover {
            text-decoration: underline;
        }
        h1 {
            text-align: center;
            margin: 0;
        }
        p.subtitle {
            text-align: center;
            margin-top: 5px;
        }
        .section {
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #e3f2fd;
        }
        .error-container {
            max-width: 800px;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
        }
        .error-container h2 {
            color: #ff9800;
            margin-bottom: 20px;
        }
        .error-container p {
            color: #555;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff9800;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            text-align: center;
        }
        .back-btn:hover {
            background-color: #e65100;
        }
        footer {
            text-align: center;
            padding: 20px;
            color: #888;
        }
        @media (max-width: 900px) {
            .error-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Foody Delight</h1>
        <p class="subtitle">Khám phá ẩm thực, tin tức & góp ý từ cộng đồng</p>
        <nav>
            <a href="Main.php#foods">Món Ăn</a>
            <a href="Main.php#news">Tin Tức</a>
            <a href="Main.php#contact">Góp Ý</a>
            <a href="Main.php#info">Liên Hệ</a>
        </nav>
    </header>

    <div class="section">
        <div class="error-container">
            <h2>404 - Không tìm thấy trang</h2>
            <p>Xin lỗi, trang bạn tìm kiếm không tồn tại hoặc đã bị xóa. Vui lòng kiểm tra lại URL hoặc quay lại trang chủ.</p>
            <a href="Main.php" class="back-btn">Quay lại trang chủ</a>
        </div>
    </div>

    <footer>
        © 2025 Foody Delight. All rights reserved.
    </footer>
</body>
</html>