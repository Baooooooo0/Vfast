<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dịch vụ hậu mãi - VinFast</title>
    <?php include('home_css.php'); ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #2c3e50;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background-color: #fff;
            padding-top: 100px;
            flex: 1;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        }

        /* Hero Section */
        .hero-section {
            position: relative;
            overflow: hidden;
            margin-bottom: 80px;
            border-radius: 0 0 30px 30px;
        }

        .hero-section img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            filter: brightness(0.7);
            transform: scale(1.02);
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(0, 113, 188, 0.8), rgba(52, 152, 219, 0.7));
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-content {
            text-align: center;
            color: white;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.2rem;
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            letter-spacing: -0.5px;
        }

        .hero-subtitle {
            font-size: 1.3rem;
            font-weight: 300;
            opacity: 0.95;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Main Content */
        .main-content {
            padding: 0 40px 80px 40px;
        }

        .section-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 600;
            color: #0071bc;
            margin-bottom: 15px;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #0071bc, #3498db);
            border-radius: 2px;
        }

        .section-description {
            font-size: 1.1rem;
            color: #7f8c8d;
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.7;
        }

        /* Grid Layout */
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 35px;
            margin-top: 50px;
            padding: 0 20px;
        }

        .grid-item {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(0, 113, 188, 0.08);
            position: relative;
            transform: translateY(0);
        }

        .grid-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(135deg, #0071bc, #3498db, #2ecc71);
        }

        .grid-item:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 113, 188, 0.15);
            border-color: rgba(0, 113, 188, 0.2);
        }

        .grid-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .grid-item:hover img {
            transform: scale(1.05);
        }

        .grid-content {
            padding: 30px;
        }

        .grid-item span {
            font-weight: 700;
            font-size: 1.35rem;
            color: #0071bc;
            display: block;
            margin-bottom: 20px;
            line-height: 1.4;
            letter-spacing: -0.3px;
        }

        .grid-item ul {
            list-style: none;
            padding: 0;
            margin: 15px 0;
        }

        .grid-item ul li {
            position: relative;
            padding: 8px 0 8px 25px;
            margin-bottom: 8px;
            color: #555;
            line-height: 1.6;
            font-size: 0.95rem;
        }

        .grid-item ul li::before {
            content: '✓';
            position: absolute;
            left: 0;
            top: 8px;
            color: #27ae60;
            font-weight: bold;
            font-size: 14px;
        }

        .grid-item p {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.7;
            margin-top: 15px;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .grid-container {
                grid-template-columns: repeat(2, 1fr);
            }

            .main-content {
                padding: 0 20px 60px 20px;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding-top: 85px;
            }

            .hero-section {
                margin-bottom: 50px;
                border-radius: 0 0 20px 20px;
            }

            .hero-section img {
                height: 300px;
            }

            .hero-title {
                font-size: 2.2rem;
            }

            .hero-subtitle {
                font-size: 1rem;
                padding: 0 20px;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .grid-container {
                grid-template-columns: 1fr;
                gap: 25px;
                padding: 0 10px;
            }

            .main-content {
                padding: 0 10px 40px 10px;
            }

            .grid-content {
                padding: 25px;
            }

            .benefits-section,
            .contact-section,
            .process-section,
            .faq-section {
                padding: 60px 20px;
            }

            .contact-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 2rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .section-title {
                font-size: 1.7rem;
            }
        }

        /* Animation */
        .grid-item {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease forwards;
        }

        .grid-item:nth-child(1) {
            animation-delay: 0.1s;
        }

        .grid-item:nth-child(2) {
            animation-delay: 0.2s;
        }

        .grid-item:nth-child(3) {
            animation-delay: 0.3s;
        }

        .grid-item:nth-child(4) {
            animation-delay: 0.4s;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Service Benefits Section */
        .benefits-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 80px 40px;
            margin: 60px 0;
            color: white;
        }

        .benefits-container {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        .benefits-title {
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 50px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-top: 40px;
        }

        .benefit-item {
            text-align: center;
            padding: 20px;
        }

        .benefit-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px auto;
            font-size: 2rem;
            backdrop-filter: blur(10px);
        }

        .benefit-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .benefit-desc {
            font-size: 1rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        /* Contact Section */
        .contact-section {
            background: #f8f9fa;
            padding: 80px 40px;
            margin-top: 60px;
            margin-bottom: 0;
        }

        .contact-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            margin-top: 40px;
        }

        .contact-info {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .contact-info h3 {
            color: #0071bc;
            font-size: 1.5rem;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .contact-item:hover {
            background: #e3f2fd;
            transform: translateX(5px);
        }

        .contact-icon {
            width: 40px;
            height: 40px;
            background: #0071bc;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.2rem;
        }

        .contact-text {
            flex: 1;
        }

        .contact-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .contact-value {
            color: #666;
            font-size: 0.95rem;
        }

        /* Service Process Section */
        .process-section {
            padding: 80px 40px;
            background: white;
        }

        .process-timeline {
            max-width: 1000px;
            margin: 40px auto 0;
            position: relative;
        }

        .process-timeline::before {
            content: '';
            position: absolute;
            left: 50%;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(180deg, #0071bc, #3498db);
            transform: translateX(-50%);
        }

        .timeline-item {
            display: flex;
            margin-bottom: 40px;
            position: relative;
        }

        .timeline-item:nth-child(even) {
            flex-direction: row-reverse;
        }

        .timeline-content {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.08);
            width: 45%;
            position: relative;
            border: 1px solid rgba(0, 113, 188, 0.08);
        }

        @media (max-width: 768px) {
            .process-timeline::before {
                left: 30px;
            }

            .timeline-item {
                flex-direction: row !important;
            }

            .timeline-item:nth-child(even) {
                flex-direction: row !important;
            }

            .timeline-content {
                width: calc(100% - 80px);
                margin-left: 80px !important;
                margin-right: 0 !important;
            }

            .timeline-number {
                left: 30px;
                top: 30px;
                transform: translateX(-20%);
            }
        }

        .timeline-item:nth-child(even) .timeline-content {
            margin-right: 10%;
        }

        .timeline-item:nth-child(odd) .timeline-content {
            margin-left: 10%;
        }

        .timeline-number {
            position: absolute;
            left: 50%;
            top: 60px;
            transform: translateX(-50%);
            width: 50px;
            height: 50px;
            background: #0071bc;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            z-index: 2;
        }

        .process-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #0071bc;
            margin-bottom: 15px;
            margin-right: 10px;
        }

        .process-desc {
            color: #666;
            line-height: 1.6;
        }

        /* FAQ Section */
        .faq-section {
            background: #f8f9fa;
            padding: 80px 40px;
            margin-bottom: 0;
        }

        .faq-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .faq-item {
            background: white;
            margin-bottom: 20px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .faq-question {
            padding: 25px;
            background: white;
            border: none;
            width: 100%;
            text-align: left;
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }

        .faq-question:hover {
            background: #f8f9fa;
        }

        .faq-answer {
            padding: 0 25px 25px;
            color: #666;
            line-height: 1.6;
            display: none;
        }

        .faq-answer.show {
            display: block;
        }

        .faq-icon {
            transition: transform 0.3s ease;
        }

        .faq-item.active .faq-icon {
            transform: rotate(180deg);
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(45deg, #0071bc, #3498db);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(45deg, #005a94, #2980b9);
        }
    </style>
</head>

<body>
    <?php include('header.php'); ?>
    <div class="container">
        <!-- Hero Section -->
        <div class="hero-section">
            <img src="https://static-cms-prod.vinfastauto.com/baohanh_1656867400_1658395630.png" alt="VinFast Service">
            <div class="hero-overlay">
                <div class="hero-content">
                    <h1 class="hero-title">Dịch Vụ Hậu Mãi</h1>
                    <p class="hero-subtitle">Cam kết chất lượng và sự hài lòng của khách hàng với dịch vụ bảo hành toàn diện</p>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="section-header">
                <h2 class="section-title">Chính Sách Bảo Hành</h2>
                <p class="section-description">
                    VinFast cung cấp chính sách bảo hành toàn diện và dịch vụ hậu mãi chuyên nghiệp,
                    đảm bảo trải nghiệm tốt nhất cho khách hàng trong suốt quá trình sử dụng xe.
                </p>
            </div>

            <div class="grid-container">
                <div class="grid-item">
                    <img src="https://static-cms-prod.vinfastauto.com/hang-muc-khong-thuoc-pham-vi-bao-hanh_1675929374.jpg" alt="Thời hạn bảo hành">
                    <div class="grid-content">
                        <span class="grid-item--time">Thời Hạn Bảo Hành Ô Tô</span>
                        <ul>
                            <li>Thời hạn bảo hành 10 năm hoặc 200.000 km tùy điều kiện nào đến trước: VF e34, VF 7, VF 8, VF 9.</li>
                            <li>Thời hạn bảo hành 7 năm hoặc 160.000 km tùy điều kiện nào đến trước: VF 5, VF 6.</li>
                        </ul>
                    </div>
                </div>

                <div class="grid-item">
                    <img src="https://static-cms-prod.vinfastauto.com/pham-vi-bao-hanh_1675929408.jpg" alt="Phạm vi bảo hành">
                    <div class="grid-content">
                        <span>Phạm Vi Bảo Hành</span>
                        <p>Bảo hành áp dụng cho các hư hỏng do lỗi phần mềm, lỗi chất lượng của linh kiện hoặc lỗi lắp ráp của VinFast với điều kiện sản phẩm được sử dụng và bảo dưỡng đúng cách, ngoại trừ các hạng mục không thuộc phạm vi bảo hành.</p>
                    </div>
                </div>

                <div class="grid-item">
                    <img src="https://static-cms-prod.vinfastauto.com/bao-hanh-phu-tung_1675929346.jpg" alt="Bảo hành phụ tùng">
                    <div class="grid-content">
                        <span>Bảo Hành Phụ Tùng</span>
                        <p>Phụ tùng thay thế cho xe của khách hàng trong quá trình sửa chữa tại XDV/NPP của VinFast do khách hàng chịu chi phí, sẽ có thời hạn bảo hành 2 năm từ ngày mua phụ tùng (không giới hạn quãng đường sử dụng) cho các dòng xe điện.</p>
                    </div>
                </div>

                <div class="grid-item">
                    <img src="https://static-cms-prod.vinfastauto.com/chi-tiet-gioi-han-bao-hanh_1675929360.jpg" alt="Chi tiết bảo hành giới hạn">
                    <div class="grid-content">
                        <span>Các Chi Tiết Bảo Hành Giới Hạn</span>
                        <ul>
                            <li>Ắc quy 12V: Ô tô điện bảo hành 1 năm (không giới hạn quãng đường sử dụng).</li>
                            <li>Lốp được trang bị theo xe: Bảo hành bởi nhà sản xuất lốp xe.</li>
                            <li>Những hạng mục hư hỏng không thuộc bảo hành: Do phá hoại, tai nạn hoặc va chạm.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Benefits Section -->
        <div class="benefits-section">
            <div class="benefits-container">
                <h2 class="benefits-title">Tại Sao Chọn Dịch Vụ VinFast?</h2>
                <div class="benefits-grid">
                    <div class="benefit-item">
                        <div class="benefit-icon">🔧</div>
                        <h3 class="benefit-title">Kỹ Thuật Chuyên Nghiệp</h3>
                        <p class="benefit-desc">Đội ngũ kỹ thuật viên được đào tạo chuyên sâu, am hiểu từng chi tiết của xe VinFast</p>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">⚡</div>
                        <h3 class="benefit-title">Phụ Tùng Chính Hãng</h3>
                        <p class="benefit-desc">100% phụ tùng chính hãng, đảm bảo chất lượng và tuổi thọ tối ưu cho xe</p>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">🏆</div>
                        <h3 class="benefit-title">Bảo Hành Dài Hạn</h3>
                        <p class="benefit-desc">Chính sách bảo hành lên đến 10 năm, dẫn đầu trong ngành ô tô</p>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">📱</div>
                        <h3 class="benefit-title">Hỗ Trợ 24/7</h3>
                        <p class="benefit-desc">Đường dây nóng hỗ trợ khách hàng 24/7 mọi lúc mọi nơi</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Process Section -->
        <div class="process-section">
            <div class="section-header">
                <h2 class="section-title">Quy Trình Dịch Vụ</h2>
                <p class="section-description">
                    Quy trình dịch vụ bảo hành và bảo dưỡng chuyên nghiệp, đảm bảo trải nghiệm tốt nhất cho khách hàng
                </p>
            </div>
            <div class="process-timeline">
                <div class="timeline-item">
                    <div class="timeline-content">
                        <h3 class="process-title">Đặt Lịch Hẹn</h3>
                        <p class="process-desc">Khách hàng có thể đặt lịch hẹn qua hotline, website hoặc ứng dụng VinFast. Hệ thống sẽ xác nhận và gửi thông tin chi tiết về lịch hẹn.</p>
                    </div>
                    <div class="timeline-number">1</div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-content">
                        <h3 class="process-title">Tiếp Nhận & Kiểm Tra</h3>
                        <p class="process-desc">Kỹ thuật viên tiếp nhận xe, thực hiện kiểm tra tổng thể và tư vấn chi tiết về các dịch vụ cần thiết cho khách hàng.</p>
                    </div>
                    <div class="timeline-number">2</div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-content">
                        <h3 class="process-title">Thực Hiện Dịch Vụ</h3>
                        <p class="process-desc">Tiến hành bảo dưỡng, sửa chữa theo quy trình chuẩn của VinFast với phụ tùng chính hãng và công nghệ hiện đại.</p>
                    </div>
                    <div class="timeline-number">3</div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-content">
                        <h3 class="process-title">Kiểm Tra & Giao Xe</h3>
                        <p class="process-desc">Kiểm tra chất lượng sau dịch vụ, giải thích công việc đã thực hiện và giao xe cùng với báo cáo chi tiết cho khách hàng.</p>
                    </div>
                    <div class="timeline-number">4</div>
                </div>
            </div>
        </div>

        <!-- Contact Section -->
        <div class="contact-section">
            <div class="contact-container">
                <div class="section-header">
                    <h2 class="section-title">Liên Hệ Dịch Vụ</h2>
                    <p class="section-description">
                        Đội ngũ chăm sóc khách hàng VinFast sẵn sàng hỗ trợ bạn 24/7
                    </p>
                </div>
                <div class="contact-grid">
                    <div class="contact-info">
                        <h3>Thông Tin Liên Hệ</h3>
                        <div class="contact-item">
                            <div class="contact-icon">📞</div>
                            <div class="contact-text">
                                <div class="contact-label">Hotline</div>
                                <div class="contact-value">1900 23 23 89</div>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">✉️</div>
                            <div class="contact-text">
                                <div class="contact-label">Email</div>
                                <div class="contact-value">support@vinfastauto.com</div>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">📍</div>
                            <div class="contact-text">
                                <div class="contact-label">Địa chỉ</div>
                                <div class="contact-value">Tầng 24, Tòa Keangnam, Hà Nội</div>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">🕐</div>
                            <div class="contact-text">
                                <div class="contact-label">Giờ làm việc</div>
                                <div class="contact-value">8:00 - 18:00 (T2-CN)</div>
                            </div>
                        </div>
                    </div>
                    <div class="contact-info">
                        <h3>Dịch Vụ Khẩn Cấp</h3>
                        <div class="contact-item">
                            <div class="contact-icon">🚗</div>
                            <div class="contact-text">
                                <div class="contact-label">Cứu hộ 24/7</div>
                                <div class="contact-value">1800 1909</div>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">🔋</div>
                            <div class="contact-text">
                                <div class="contact-label">Hỗ trợ sạc xe</div>
                                <div class="contact-value">1900 96 96 89</div>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">📱</div>
                            <div class="contact-text">
                                <div class="contact-label">App VinFast</div>
                                <div class="contact-value">Tải trên App Store & CH Play</div>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">🌐</div>
                            <div class="contact-text">
                                <div class="contact-label">Website</div>
                                <div class="contact-value">vinfastauto.com</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="faq-section">
            <div class="faq-container">
                <div class="section-header">
                    <h2 class="section-title">Câu Hỏi Thường Gặp</h2>
                    <p class="section-description">
                        Các câu hỏi phổ biến về dịch vụ bảo hành và bảo dưỡng xe VinFast
                    </p>
                </div>
                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFAQ(this)">
                        Làm thế nào để đặt lịch bảo dưỡng xe?
                        <span class="faq-icon">▼</span>
                    </button>
                    <div class="faq-answer">
                        Bạn có thể đặt lịch bảo dưỡng qua hotline 1900 23 23 89, website vinfastauto.com hoặc ứng dụng VinFast trên điện thoại. Hệ thống sẽ tự động xác nhận và gửi thông tin chi tiết về lịch hẹn.
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFAQ(this)">
                        Xe VinFast có được bảo hành toàn cầu không?
                        <span class="faq-icon">▼</span>
                    </button>
                    <div class="faq-answer">
                        Có, xe VinFast được bảo hành toàn cầu tại các đại lý chính thức của VinFast trên thế giới. Khách hàng có thể liên hệ với đại lý VinFast gần nhất để được hỗ trợ dịch vụ bảo hành.
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFAQ(this)">
                        Tần suất bảo dưỡng định kỳ cho xe điện VinFast là bao lâu?
                        <span class="faq-icon">▼</span>
                    </button>
                    <div class="faq-answer">
                        Xe điện VinFast nên được bảo dưỡng định kỳ mỗi 12 tháng hoặc 20.000km, tùy điều kiện nào đến trước. Việc bảo dưỡng định kỳ giúp đảm bảo hiệu suất và tuổi thọ tối ưu của xe.
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFAQ(this)">
                        Chi phí bảo dưỡng xe VinFast như thế nào?
                        <span class="faq-icon">▼</span>
                    </button>
                    <div class="faq-answer">
                        Chi phí bảo dưỡng xe VinFast phụ thuộc vào loại xe và các hạng mục cần thực hiện. Bạn có thể tham khảo bảng giá dịch vụ tại đại lý hoặc liên hệ hotline để được tư vấn chi tiết.
                    </div>
                </div>
            </div>
        </div>

        <script>
            function toggleFAQ(element) {
                const faqItem = element.parentElement;
                const faqAnswer = faqItem.querySelector('.faq-answer');
                const isActive = faqItem.classList.contains('active');

                // Close all FAQ items
                document.querySelectorAll('.faq-item').forEach(item => {
                    item.classList.remove('active');
                    item.querySelector('.faq-answer').classList.remove('show');
                });

                // Toggle current item
                if (!isActive) {
                    faqItem.classList.add('active');
                    faqAnswer.classList.add('show');
                }
            }
        </script>
    </div>
    <?php include('../client/footer.php') ?>

</body>

</html>