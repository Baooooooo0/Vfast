document.addEventListener('DOMContentLoaded', function() {
    function initSlider(sliderContainer) {
        const slides = sliderContainer.querySelector('.slides');
        const slide = sliderContainer.querySelectorAll('.slide');
        const next = sliderContainer.querySelector('.next');
        const prev = sliderContainer.querySelector('.prev');
        const brandHeaders = document.querySelectorAll('.change-title-color');
        const brands = [
            document.getElementById('VF3'),
            document.getElementById('VF5'),
            document.getElementById('VF6'),
            document.getElementById('VFe34'),
            document.getElementById('VF7'),
            document.getElementById('VF8'),
            document.getElementById('VF9')
        ];

        let currentIndex = 0;

        function showSlide(index) {
            if (index >= slide.length) {
                currentIndex = 0;
            } else if (index < 0) {
                currentIndex = slide.length - 1;
            } else {
                currentIndex = index;
            }
            slides.style.transform = 'translateX(' + (-currentIndex * 100) + '%)';

            brandHeaders.forEach((header, i) => {
                header.style.fill = i === currentIndex ? 'blue' : '#3C3C3C';
            });
        }

        next.addEventListener('click', function() {
            showSlide(currentIndex + 1);
        });

        prev.addEventListener('click', function() {
            showSlide(currentIndex - 1);
        });

        brands.forEach((brand, index) => {
            brand.addEventListener('click', function() {
                showSlide(index);
            });
        });

        setInterval(function() {
            showSlide(currentIndex + 1);
        }, 60000);

        // Hiển thị slide đầu tiên và đặt màu tiêu đề tương ứng
        showSlide(currentIndex);
    }

    const sliders = document.querySelectorAll('.slider');
    sliders.forEach(slider => {
        initSlider(slider);
    });
});
