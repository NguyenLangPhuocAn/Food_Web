<?php
include 'config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    include '404.php';
    exit();
}

$Stmt = $conn->prepare("SELECT * FROM tintuc WHERE Id = ?");
$Stmt->bind_param("i", $id);
$Stmt->execute();
$TinTuc = $Stmt->get_result()->fetch_assoc();

if (!$TinTuc) {
    include '404.php';
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Chi tiết tin tức - Foody Delight">
    <meta name="keywords" content="Foody Delight, tin tức, ẩm thực">
    <title><?php echo htmlspecialchars($TinTuc['Tieu_De']); ?> - Foody Delight</title>
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
        }
        .news {
            background-color: #e3f2fd;
        }
        .news-detail {
            max-width: 800px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .news-detail img {
            width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .news-detail h2 {
            color: #ff9800;
            margin-bottom: 10px;
            text-align: center;
        }
        .news-detail p {
            color: #555;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .news-detail small {
            color: #888;
            display: block;
            text-align: center;
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
            .news-detail {
                padding: 15px;
            }
            .news-detail img {
                max-height: 300px;
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

    <div class="section news">
        <div class="news-detail">
            <h2><?php echo htmlspecialchars($TinTuc['Tieu_De']); ?></h2>
            <img src="images/<?php echo htmlspecialchars($TinTuc['Image']); ?>" alt="Tin tức <?php echo htmlspecialchars($TinTuc['Tieu_De']); ?>">
            <?php
                echo $TinTuc['Noi_Dung'];
            ?>
            <small>Ngày đăng: <?php echo htmlspecialchars($TinTuc['Ngay_Dang']); ?></small>
            <a href="Main.php#news" class="back-btn">Quay lại</a>
        </div>
    </div>

    <footer>
        © 2025 Foody Delight. All rights reserved.
    </footer>
</body>
</html>
<?php $conn->close(); ?>