<?php
session_start();
if(!isset($_SESSION['email'])){
    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giới Thiệu Về VinFast | VinFast</title>
    <?php include('home_css.php'); ?>
</head>
<body>
    <?php include('header.php'); ?>
    <div class="home-banner">
        <div class="slider">
            <div class="slides">
                <div class="slide">
                    <img src="https://static-cms-prod.vinfastauto.com/VF5-banner-3060x1406-min_1720268924.png" alt="VinFast Banner" width="100%">
                </div>
                <div class="slide">
                    <img src="https://static-cms-prod.vinfastauto.com/3060x1406-min_1719894616.jpg" alt="VinFast Banner" width="100%">
                </div>
            </div>
            <div class="nav banner-nav">
                <button class="prev"><i class="fa fa-chevron-left" aria-hidden="true"></i></button>
                <button class="next"><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="title_introduction">
            <span>Giới thiệu về</span>
            <h1>Công ty VinFast</h1><br>
            <p>VinFast là công ty thành viên thuộc tập đoàn Vingroup, một trong những Tập đoàn Kinh tế tư nhân đa ngành lớn nhất<br> Châu Á.
            <br><br> Với triết lý “Đặt khách hàng làm trọng tâm”, VinFast không ngừng sáng tạo để tạo ra các sản phẩm đẳng cấp và trải nghiệm xuất sắc cho mọi người.</p>
        </div>
        <div class="background_car">
            <img src="../img/VF9NeptuneGray.jpg" alt="VF9" width="758px">
        </div>
        
    </div>
        <!-- Global Impact Section -->
        <section class="global-section">
            <div class="container">
                <div class="section-header">
                    <h2>Dấu ấn toàn cầu</h2>
                    <p>VinFast đã nhanh chóng thiết lập sự hiện diện toàn cầu, thu hút những tài năng tốt nhất từ khắp nơi trên thế giới và hợp tác với một số thương hiệu mang tính biểu tượng nhất trong ngành Ô tô.</p>
                </div>
                
                <div class="global-grid">
                    <div class="global-card vf8">
                        <div class="card-image">
                            <img src="https://static-cms-prod.vinfastauto.com/dau-chan-vf8_1664352740.png" alt="VF 8">
                            <div class="card-overlay">
                                <div class="overlay-content">
                                    <h3>VinFast VF 8</h3>
                                    <p>SUV điện thông minh hạng D</p>
                                    <a href="oto.php" class="overlay-btn">Khám phá</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="global-card vf9">
                        <div class="card-image">
                            <img src="https://static-cms-prod.vinfastauto.com/dau-chan-vf9_1664352747.png" alt="VF 9">
                            <div class="card-overlay">
                                <div class="overlay-content">
                                    <h3>VinFast VF 9</h3>
                                    <p>SUV điện cao cấp hạng E</p>
                                    <a href="oto.php" class="overlay-btn">Khám phá</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- History Timeline Section -->
        <section class="timeline-section">
            <div class="container">
                <div class="section-header">
                    <h2>Lịch sử thương hiệu</h2>
                    <p>Hành trình phát triển và khẳng định vị thế của VinFast trên thị trường quốc tế</p>
                </div>
                
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-date">
                            <span class="date-number">15</span>
                            <span class="date-month">Tháng 8</span>
                            <span class="date-year">2023</span>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-image">
                                <img src="https://static-cms-prod.vinfastauto.com/vinfast-ipo_1694854769.png" alt="VinFast NASDAQ">
                            </div>
                            <div class="timeline-info">
                                <h3>Niêm yết Nasdaq</h3>
                                <p>VinFast chính thức niêm yết trên Nasdaq Global Select Market, khẳng định vị thế đẳng cấp quốc tế</p>
                                <div class="timeline-badge nasdaq">
                                    <i class="fas fa-chart-line"></i>
                                    <span>Nasdaq</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-date">
                            <span class="date-number">21</span>
                            <span class="date-month">Tháng 4</span>
                            <span class="date-year">2023</span>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-image">
                                <img src="https://static-cms-prod.vinfastauto.com/Ban-giao-VF5_1694854848.png" alt="VF5 Delivery">
                            </div>
                            <div class="timeline-info">
                                <h3>Ra mắt VF 5 Plus</h3>
                                <p>VinFast chính thức bàn giao xe VF 5 Plus cho khách hàng, mở ra kỷ nguyên xe điện phổ thông</p>
                                <div class="timeline-badge delivery">
                                    <i class="fas fa-car"></i>
                                    <span>VF5 Plus</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-date">
                            <span class="date-number">27</span>
                            <span class="date-month">Tháng 3</span>
                            <span class="date-year">2023</span>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-image">
                                <img src="https://static-cms-prod.vinfastauto.com/vf9-full-size_1693250642.jpg" alt="VF9 Delivery">
                            </div>
                            <div class="timeline-info">
                                <h3>Bàn giao VF 9</h3>
                                <p>VinFast chính thức bàn giao xe VF 9 cho khách hàng, khẳng định vị thế trong phân khúc SUV cao cấp</p>
                                <div class="timeline-badge premium">
                                    <i class="fas fa-crown"></i>
                                    <span>VF9 Premium</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php include('footer.php'); ?>
    
    <script src="../js/slick.js"></script>
    <script>
        // Banner Slider Navigation
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelector('.slides');
            const slideElements = document.querySelectorAll('.slide');
            const nextBtn = document.querySelector('.banner-nav .next');
            const prevBtn = document.querySelector('.banner-nav .prev');
            
            if (!slides || slideElements.length === 0) return;
            
            let currentSlide = 0;
            const totalSlides = slideElements.length;
            
            // Auto play interval
            let autoPlayInterval;
            
            function showSlide(index) {
                if (index >= totalSlides) {
                    currentSlide = 0;
                } else if (index < 0) {
                    currentSlide = totalSlides - 1;
                } else {
                    currentSlide = index;
                }
                
                // Update slider position
                if (slides) {
                    slides.style.transform = `translateX(-${currentSlide * 100}%)`;
                }
                
                // Update slide active state
                slideElements.forEach((slide, i) => {
                    slide.classList.toggle('active', i === currentSlide);
                });
            }
            
            function nextSlide() {
                showSlide(currentSlide + 1);
            }
            
            function prevSlide() {
                showSlide(currentSlide - 1);
            }
            
            function startAutoPlay() {
                autoPlayInterval = setInterval(nextSlide, 5000); // 5 seconds
            }
            
            function stopAutoPlay() {
                clearInterval(autoPlayInterval);
            }
            
            // Event listeners
            if (nextBtn) {
                nextBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    nextSlide();
                    stopAutoPlay();
                    setTimeout(startAutoPlay, 3000);
                });
            }
            
            if (prevBtn) {
                prevBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    prevSlide();
                    stopAutoPlay();
                    setTimeout(startAutoPlay, 3000);
                });
            }
            
            // Pause on hover
            const slider = document.querySelector('.slider');
            if (slider) {
                slider.addEventListener('mouseenter', stopAutoPlay);
                slider.addEventListener('mouseleave', startAutoPlay);
            }
            
            // Keyboard navigation
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') {
                    prevSlide();
                    stopAutoPlay();
                    setTimeout(startAutoPlay, 3000);
                } else if (e.key === 'ArrowRight') {
                    nextSlide();
                    stopAutoPlay();
                    setTimeout(startAutoPlay, 3000);
                }
            });
            
            // Initialize
            showSlide(0);
            startAutoPlay();
            
            // Smooth scrolling for CTA buttons
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
            
            // Intersection Observer for animations
            const animateOnScroll = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '50px'
            });
            
            // Apply animation observer to elements
            document.querySelectorAll('.timeline-item, .global-card, .stat-item').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                el.style.transition = 'all 0.8s ease';
                animateOnScroll.observe(el);
            });
        });
    </script>
</body>
</html>
