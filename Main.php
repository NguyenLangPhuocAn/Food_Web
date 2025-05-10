<?php
include 'config/db.php';

$ThongTinLienHe = [
    'Sdt' => '0907563107',
    'Email' => 'nlpan14112004@gmail.com',
    'DiaChi' => '111/41 Huỳnh Văn Bánh, P17, Q.Phú Nhuận, TP.HCM'
];

$ThongBao = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $Ten = trim($_POST['ten'] ?? '');
    $Sdt = trim($_POST['sdt'] ?? '');
    $GopY = trim($_POST['gopy'] ?? '');

    if (empty($Ten) || empty($Sdt) || empty($GopY)) {
        $ThongBao = "Vui lòng điền đầy đủ thông tin.";
    } elseif (!preg_match("/^0\d{9}$/", $Sdt)) {
        $ThongBao = "Số điện thoại không hợp lệ (phải có 10 số, bắt đầu bằng 0).";
    } elseif (strlen($GopY) > 500) {
        $ThongBao = "Góp ý không được vượt quá 500 ký tự.";
    } else {
        $Stmt = $conn->prepare("INSERT INTO lienhe (Ten, Sdt, GopY) VALUES (?, ?, ?)");
        $Stmt->bind_param("sss", $Ten, $Sdt, $GopY);
        if ($Stmt->execute()) {
            $ThongBao = "Cảm ơn bạn đã góp ý!";
        } else {
            $ThongBao = "Lỗi khi gửi góp ý. Vui lòng thử lại.";
        }
        $Stmt->close();
    }
}

$FoodsQuery = $conn->query("SELECT * FROM MonAn"); 
if (!$FoodsQuery) {
    $ThongBao = "Lỗi truy vấn món ăn: " . $conn->error;
}

$TinTucsQuery = $conn->query("SELECT * FROM tintuc ORDER BY Ngay_Dang DESC LIMIT 3");
if (!$TinTucsQuery) {
    $ThongBao = "Lỗi truy vấn tin tức: " . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Foody Delight - Khám phá ẩm thực, tin tức và gửi góp ý tại TP.HCM">
    <meta name="keywords" content="Foody Delight, ẩm thực, món ăn, tin tức, góp ý">
    <title>Foody Delight</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/style.css">
    <style>
        html {
            scroll-behavior: smooth;
        }
        body {
            font-family: 'Roboto', sans-serif;
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
            width: 100%;
            box-sizing: border-box; /* Đảm bảo padding không làm vượt khung */
        }
        .intro {
            background-color: #fff8e1;
        }
        .foods {
            background-color: #fff3e0;
        }
        .news {
            background-color: #e3f2fd;
        }
        .pho-info {
            background-color: #f3e5f5;
        }
        .highlights {
            background-color: #e8f5e9;
        }
        .media {
            background-color: #f5f5f5;
            width: 100%;
            max-width: 100%; /* Đảm bảo không vượt khung */
            overflow: hidden;
            box-sizing: border-box;
        }
        .contact {
            background-color: #f1f8e9;
        }
        .info {
            background-color: #eceff1;
        }
        .grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            align-items: stretch;
            max-width: 1280px;
        }
        .highlight-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
            max-width: 1280px;
            width: 100%;
        }
        .highlight-item {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 20px;
            text-align: center;
            flex: 1 1 calc(33.33% - 20px);
            min-width: 250px;
        }
        .highlight-item i {
            font-size: 40px;
            color: #ff9800;
            margin-bottom: 15px;
        }
        .highlight-item h2 {
            color: #ff9800;
            margin-bottom: 10px;
        }
        .highlight-item p {
            color: #555;
            line-height: 1.6;
        }
        .card {
            background: white;
            width: 100%;
            max-width: 300px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            overflow: hidden;
            text-align: center;
            flex: 1 1 calc(33.33% - 20px);
            display: flex;
            flex-direction: column;
        }
        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .card-content {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        .card-content h2 {
            color: #ff9800;
            margin-bottom: 10px;
        }
        .card-content p.description {
            color: #555;
            flex: 1;
            margin-bottom: 15px;
        }
        .card-content p.price {
            color: #e65100;
            font-weight: bold;
            margin: 0;
        }
        .thongbao {
            text-align: center;
            color: green;
            font-weight: bold;
            margin-bottom: 20px;
        }
        footer {
            text-align: center;
            padding: 20px;
            color: #888;
        }
        iframe {
            width: 100%;
            max-width: 600px;
            height: 300px;
            border: none;
            margin: 20px auto;
            display: block;
        }
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            margin: 0 auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        .form-container h2 {
            margin-top: 0;
            color: #2e7d32;
        }
        .form-container form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .form-container input,
        .form-container textarea {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            width: 80%;
            margin: 0 auto;
            box-sizing: border-box;
            text-align: left;
            font-family: 'Roboto', sans-serif;
        }
        .form-container textarea {
            height: 100px;
            resize: vertical;
        }
        .form-container button {
            padding: 10px 20px;
            background-color: #2e7d32;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 80%;
            margin: 0 auto;
            font-family: 'Roboto', sans-serif;
        }
        .form-container button:hover {
            background-color: #1b5e20;
        }
        .info-content {
            display: flex;
            justify-content: flex-start;
            gap: 20px;
            width: 100%;
            max-width: 1200px;
        }
        .info-text {
            flex: 1;
            padding: 20px;
            min-height: 300px;
        }
        .info-text div {
            text-align: left;
        }
        .info-text p {
            display: flex;
            align-items: flex-start;
            margin: 0;
            padding: 5px 0;
        }
        .info-text p span.label {
            flex: 0 0 100px;
            font-weight: bold;
            margin-right: 10px;
        }
        .info-text p span.value {
            flex: 1;
            word-break: break-word;
        }
        .info-map {
            flex: 1;
            min-height: 300px;
        }
        .social-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }
        .social-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: #ff9800;
            color: white;
            border-radius: 50%;
            text-decoration: none;
            font-size: 20px;
            transition: background-color 0.3s;
            font-family: 'Roboto', sans-serif;
        }
        .social-btn:hover {
            background-color: #e65100;
        }
        .operation-hours {
            margin-top: 10px;
            color: #37474f;
            font-size: 18px;
            line-height: 1.6;
        }
        .operation-hours h3 {
            margin-bottom: 5px;
            font-weight: bold;
        }
        .operation-hours p {
            margin: 0;
        }
        .intro-content {
            max-width: 800px;
            text-align: center;
            color: #555;
            line-height: 1.6;
        }
        .intro-content h2 {
            color: #ff9800;
            margin-bottom: 15px;
        }
        .intro-content p {
            margin-bottom: 15px;
        }
        .pho-content {
            max-width: 800px;
            text-align: left;
            color: #555;
            line-height: 1.6;
        }
        .pho-content h2 {
            color: #8e24aa;
            margin-bottom: 15px;
            text-align: center;
        }
        .pho-content h3 {
            color: #6a1b9a;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .pho-content p {
            margin-bottom: 15px;
        }
        .pho-content img {
            width: 250px;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin: 15px;
        }
        .pho-content img.left {
            float: left;
            margin-right: 20px;
        }
        .pho-content img.right {
            float: right;
            margin-left: 20px;
        }
        .pho-content::after {
            content: "";
            display: block;
            clear: both;
        }
        /* CSS cho Slideshow Media */
        .slideshow-container {
            position: relative;
            width: 100%;
            max-width: 1200px;
            height: 400px;
            margin: 0 auto;
            overflow: hidden;
        }
        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }
        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .slide.active {
            opacity: 1;
        }
        .slide-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s ease;
        }
        .slide-overlay:hover {
            background: rgba(0, 0, 0, 0.5);
        }
        .slide-text {
            color: white;
            font-size: 24px;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            text-align: center;
            padding: 20px;
        }
        .prev, .next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 30px;
            color: white;
            background: rgba(0, 0, 0, 0.5);
            padding: 10px;
            cursor: pointer;
            user-select: none;
            transition: background 0.3s;
        }
        .prev:hover, .next:hover {
            background: rgba(0, 0, 0, 0.8);
        }
        .prev {
            left: 10px;
        }
        .next {
            right: 10px;
        }
        .dots {
            position: absolute;
            bottom: 20px;
            width: 100%;
            text-align: center;
        }
        .dot {
            display: inline-block;
            width: 12px;
            height: 12px;
            margin: 0 5px;
            background: #bbb;
            border-radius: 50%;
            cursor: pointer;
            transition: background 0.3s;
        }
        .dot.active {
            background: #ff9800;
        }
        @media (max-height: 600px) {
            .slideshow-container {
                height: 250px;
            }
            .slide-text {
                font-size: 18px;
            }
        }
        @media (max-width: 900px) {
            .grid, .highlight-grid {
                flex-direction: column;
            }
            .card, .highlight-item {
                max-width: 100%;
            }
            .form-container {
                width: 90%;
            }
            .form-container input,
            .form-container textarea,
            .form-container button {
                width: 90%;
            }
            .info-content {
                flex-direction: column;
            }
            .info-text, .info-map {
                min-height: auto;
            }
            .info-text p span.label {
                flex: 0 0 80px;
            }
            .social-buttons {
                flex-direction: column;
                align-items: center;
            }
            .social-btn {
                width: 35px;
                height: 35px;
            }
            .intro-content, .pho-content {
                padding: 0 15px;
            }
            .pho-content img {
                float: none;
                width: 100%;
                max-width: 300px;
                margin: 15px auto;
                display: block;
            }
            .slideshow-container {
                height: 250px;
            }
            .slide-text {
                font-size: 16px;
                padding: 10px;
            }
            .prev, .next {
                font-size: 20px;
                padding: 5px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Foody Delight</h1>
        <p class="subtitle">Khám phá ẩm thực, tin tức & góp ý từ cộng đồng</p>
        <nav>
            <a href="#foods">Món Ăn</a>
            <a href="#news">Tin Tức</a>
            <a href="#pho">Về Phở</a>
            <a href="#contact">Góp Ý</a>
            <a href="#info">Liên Hệ</a>
        </nav>
    </header>

    <div class="section intro" id="intro">
        <div class="intro-content">
            <h2>Chào Mừng Đến Với Foody Delight</h2>
            <p>Tại Foody Delight, chúng tôi tự hào mang đến một <strong>menu đa dạng</strong> với các món ăn được chế biến từ <strong>nguyên liệu tươi ngon</strong>, đảm bảo chất lượng và <strong>tốt cho sức khỏe</strong> của bạn.</p>
            <p>Hãy khám phá những hương vị tuyệt vời, từ các món truyền thống đến hiện đại, được chuẩn bị bởi đội ngũ đầu bếp giàu kinh nghiệm. Foody Delight không chỉ là nơi thưởng thức ẩm thực mà còn là nơi gắn kết yêu thương qua từng bữa ăn.</p>
        </div>
    </div>

    <div class="section foods" id="foods">
        <h2 style="text-align:center; color:#e65100;">🍽 Danh Sách Món Ăn</h2>
        <?php if ($ThongBao && strpos($ThongBao, "Lỗi truy vấn món ăn") !== false) : ?>
            <p class="thongbao" style="color: red;"><?php echo $ThongBao; ?></p>
        <?php elseif (!$FoodsQuery || $FoodsQuery->num_rows == 0) : ?>
            <p class="thongbao">Đang cập nhật món ăn</p>
        <?php else : ?>
            <div class="grid">
                <?php while ($Row = $FoodsQuery->fetch_assoc()) : ?>
                    <div class="card">
                        <img src="images/<?php echo htmlspecialchars($Row['Image']); ?>" alt="Món <?php echo htmlspecialchars($Row['Ten']); ?> tại Foody Delight">
                        <div class="card-content">
                            <h2><?php echo htmlspecialchars($Row['Ten']); ?></h2>
                            <p class="description"><?php echo htmlspecialchars($Row['Noi_Dung']); ?></p>
                            <p class="price"><?php echo number_format($Row['Gia'], 0, ',', '.'); ?>đ</p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="section news" id="news">
        <h2 style="text-align:center; color:#1565c0;">📰 Tin Tức Mới Nhất</h2>
        <?php if ($ThongBao && strpos($ThongBao, "Lỗi truy vấn tin tức") !== false) : ?>
            <p class="thongbao" style="color: red;"><?php echo $ThongBao; ?></p>
        <?php elseif (!$TinTucsQuery || $TinTucsQuery->num_rows == 0) : ?>
            <p class="thongbao">Đang cập nhật tin tức</p>
        <?php else : ?>
            <div class="grid">
                <?php while ($Tt = $TinTucsQuery->fetch_assoc()) : ?>
                    <div class="card">
                        <img src="images/<?php echo htmlspecialchars($Tt['Image']); ?>" alt="Tin tức <?php echo htmlspecialchars($Tt['Tieu_De']); ?> tại Foody Delight">
                        <div class="card-content">
                            <h2><a href="ChiTietTinTuc.php?id=<?php echo $Tt['Id']; ?>" style="color: #ff9800; text-decoration: none;"><?php echo htmlspecialchars($Tt['Tieu_De']); ?></a></h2>
                            <small>Ngày đăng: <?php echo htmlspecialchars($Tt['Ngay_Dang']); ?></small>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="section highlights" id="highlights">
        <h2 style="text-align:center; color:#2e7d32;">🌟 Điểm Nổi Bật</h2>
        <div class="highlight-grid">
            <div class="highlight-item">
                <i class="fas fa-utensils"></i>
                <h2>Menu Đa Dạng</h2>
                <p>Nhiều món ăn ngon, mang đậm phong vị Việt Nam, đảm bảo an toàn thực phẩm và xuất xứ nguồn gốc rõ ràng.</p>
            </div>
            <div class="highlight-item">
                <i class="fas fa-leaf"></i>
                <h2>Nguyên liệu tươi sống</h2>
                <p>Vì sức khoẻ người tiêu dùng, đảm bảo thực phẩm luôn tươi sống, được kiểm định kỹ càng.</p>
            </div>
            <div class="highlight-item">
                <i class="fas fa-heart"></i>
                <h2>Tốt Cho Sức Khoẻ</h2>
                <p>Phở không chỉ ngon miệng mà còn rất giàu dinh dưỡng, nhiều vitamin và khoáng chất thiết yếu cho cơ thể.</p>
            </div>
        </div>
    </div>

    <div class="section pho-info" id="pho">
        <div class="pho-content">
            <h2>Khám Phá Về Phở</h2>
            <h3>Nguồn Gốc Của Phở</h3>
            <img src="images/vepho1.jpg" alt="Hình ảnh minh họa nguồn gốc phở tại Foody Delight" class="left">
            <p>Phở là một món ăn truyền thống của Việt Nam, có nguồn gốc từ đầu thế kỷ 20 tại miền Bắc, đặc biệt là Hà Nội và Nam Định. Ban đầu, phở được bán bởi những người bán hàng rong, với thành phần chính là bánh phở mềm, nước dùng thơm ngon từ xương bò hoặc gà, và các loại gia vị đặc trưng như quế, hồi, gừng, và hành.</p>
            <p>Theo thời gian, phở đã trở thành biểu tượng ẩm thực Việt Nam, lan tỏa khắp thế giới. Từ những gánh phở giản dị, món ăn này đã được biến tấu với nhiều phong cách khác nhau nhưng vẫn giữ được hương vị đậm đà, tinh tế.</p>
            <h3>Lợi Ích Của Phở</h3>
            <img src="images/vepho2.jpg" alt="Hình ảnh minh họa lợi ích của phở tại Foody Delight" class="right">
            <p>Phở không chỉ ngon mà còn mang lại nhiều lợi ích cho sức khỏe. Nước dùng phở, thường được ninh từ xương trong nhiều giờ, chứa nhiều collagen và khoáng chất tốt cho xương khớp. Các loại rau thơm và gia vị như gừng, hành giúp tăng cường hệ miễn dịch và hỗ trợ tiêu hóa.</p>
            <p>Ngoài ra, phở là một món ăn cân bằng dinh dưỡng với sự kết hợp của tinh bột (bánh phở), đạm (thịt bò hoặc gà), và rau xanh. Đây là lựa chọn lý tưởng cho một bữa ăn nhẹ nhàng nhưng vẫn đầy đủ năng lượng.</p>
        </div>
    </div>

    <div class="section media" id="media">
        <h2 style="text-align:center; color:#37474f;">📸 Media</h2>
        <div class="slideshow-container">
            <div class="slide">
                <img src="media/anh1.jpg" alt="Hình ảnh ẩm thực tại Foody Delight">
                <div class="slide-overlay">
                    <div class="slide-text">Hình ảnh ẩm thực tại Foody Delight</div>
                </div>
            </div>
            <div class="slide">
                <img src="media/anh2.jpg" alt="Hương vị tuyệt vời tại Foody Delight">
                <div class="slide-overlay">
                    <div class="slide-text">Hương vị tuyệt vời tại Foody Delight</div>
                </div>
            </div>
            <div class="slide">
                <img src="media/anh3.jpg" alt="Khám phá Foody Delight">
                <div class="slide-overlay">
                    <div class="slide-text">Khám phá Foody Delight</div>
                </div>
            </div>
            <a class="prev" onclick="plusSlides(-1)">❮</a>
            <a class="next" onclick="plusSlides(1)">❯</a>
            <div class="dots">
                <span class="dot" onclick="currentSlide(1)"></span>
                <span class="dot" onclick="currentSlide(2)"></span>
                <span class="dot" onclick="currentSlide(3)"></span>
            </div>
        </div>
    </div>

    <div class="section contact" id="contact">
        <h2 style="text-align:center; color:#2e7d32;">💬 Gửi Góp Ý</h2>
        <?php if (!empty($ThongBao)) : ?>
            <p class="thongbao" style="color: <?php echo strpos($ThongBao, "Lỗi") !== false ? 'red' : 'green'; ?>;">
                <?php echo $ThongBao; ?>
            </p>
        <?php endif; ?>
        <div class="form-container">
            <form method="POST">
                <input type="text" name="ten" placeholder="Họ và Tên" required>
                <input type="text" name="sdt" placeholder="Số điện thoại" pattern="0[0-9]{9}" title="Số điện thoại phải có 10 số, bắt đầu bằng 0" required>
                <textarea name="gopy" rows="5" placeholder="Góp ý của bạn..." maxlength="500" required></textarea>
                <button type="submit">Gửi góp ý</button>
            </form>
        </div>
    </div>

    <div class="section info" id="info">
        <h2 style="text-align:center; color:#37474f;">📍 Thông Tin Liên Hệ</h2>
        <div class="info-content">
            <div class="info-text">
                <div style="font-size: 18px; line-height: 1.8; margin-top: 20px;">
                    <p><span class="label">📞 SĐT:</span><span class="value"><?php echo htmlspecialchars($ThongTinLienHe['Sdt']); ?></span></p>
                    <p><span class="label">✉️ Email:</span><span class="value"><?php echo htmlspecialchars($ThongTinLienHe['Email']); ?></span></p>
                    <p><span class="label">🏠 Địa chỉ:</span><span class="value"><?php echo htmlspecialchars($ThongTinLienHe['DiaChi']); ?></span></p>
                    <div class="operation-hours">
                        <h3>HOẠT ĐỘNG</h3>
                        <p>Thứ 2 – CN: 11:00 – 23:00</p>
                    </div>
                </div>
            </div>
            <div class="info-map">
                <iframe
                    src="https://www.google.com/maps?q=111%2F41%20Huynh%20Van%20Banh%2C%20Phu%20Nhuan%2C%20HCM&output=embed"
                    allowfullscreen="">
                </iframe>
            </div>
        </div>
        <div class="social-buttons">
            <a href="https://www.facebook.com" target="_blank" class="social-btn"><i class="fab fa-facebook-f"></i></a>
            <a href="https://www.youtube.com" target="_blank" class="social-btn"><i class="fab fa-youtube"></i></a>
            <a href="https://www.tiktok.com" target="_blank" class="social-btn"><i class="fab fa-tiktok"></i></a>
            <a href="https://www.instagram.com" target="_blank" class="social-btn"><i class="fab fa-instagram"></i></a>
        </div>
    </div>

    <footer>
        © 2025 Foody Delight. All rights reserved.
    </footer>

    <script>
        let slideIndex = 1;
        showSlides(slideIndex);

        function plusSlides(n) {
            showSlides(slideIndex += n);
        }

        function currentSlide(n) {
            showSlides(slideIndex = n);
        }

        function showSlides(n) {
            let slides = document.getElementsByClassName("slide");
            let dots = document.getElementsByClassName("dot");
            if (n > slides.length) { slideIndex = 1 }
            if (n < 1) { slideIndex = slides.length }
            for (let i = 0; i < slides.length; i++) {
                slides[i].classList.remove("active");
            }
            for (let i = 0; i < dots.length; i++) {
                dots[i].classList.remove("active");
            }
            slides[slideIndex - 1].classList.add("active");
            dots[slideIndex - 1].classList.add("active");
        }

        setInterval(() => {
            plusSlides(1);
        }, 5000);
    </script>
</body>
</html>
<?php $conn->close(); ?>