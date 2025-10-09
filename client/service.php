<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dịch vụ hậu mãi</title>

    <style>
            
        body, h1, p, ul, li {
            margin: 0;
            padding: 0;
        }

        
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
        }

        .container {
            margin: 0 auto;
            background-color: #fff;
            padding-top: 0px!important;
        }

       
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #0071bc;
        }

        
        .grid-container {
            margin: 50px 15px 80px 15px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        /* Grid items */
        .grid-item {
            background-color: #f2f2f2;
            padding: 20px;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
        }

        /* Style the images */
        .grid-item img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 15px;
          
        }

        /* Style the span */
        .grid-item span {
            font-weight: bold;
            display: block;
            margin-bottom: 10px;
            font-size: 1.1em;
            color: #0071bc;
        }

        /* Style the unordered lists */
        ul {
            margin-top: 10px;
            padding-left: 20px;
        }

        /* Style the list items */
        ul li {
            margin-bottom: 5px;
            list-style: none;
        }

        /* Paragraph styling */
        .grid-item p {
            margin-top: 10px;
            font-size: 0.9em;
            line-height: 1.5;
            color: #555;
        }

    </style>
</head>
<?php include('header.php') ?>
<body>
    <div class="container">
        <img src="https://static-cms-prod.vinfastauto.com/baohanh_1656867400_1658395630.png" alt="">
        <div class="grid-container">
            <div class="grid-item">
                <img src="https://static-cms-prod.vinfastauto.com/hang-muc-khong-thuoc-pham-vi-bao-hanh_1675929374.jpg" alt="">
                <span class="grid-item--time ">Thời hạn bảo hành ô tô</span>
                <ul>
                    <li>Thời hạn bảo hành 10 năm hoặc 200.000 km tùy điều kiện nào đến trước: VF e34, VF 7, VF 8, VF 9.</li>
                    <li>Thời hạn bảo hành 7 năm hoặc 160.000 km tùy điều kiện nào đến trước: VF 5, VF 6.</li>
                </ul>
            </div>

            <div class="grid-item">
                <img src="https://static-cms-prod.vinfastauto.com/pham-vi-bao-hanh_1675929408.jpg" alt="">
                <span>Phạm vi bảo hành</span>
                <p>Bảo hành áp dụng cho các hư hỏng do lỗi phần mềm, lỗi chất lượng của linh kiện hoặc lỗi lắp ráp của VinFast với điều kiện sản phẩm được sử dụng và bảo dưỡng đúng cách, ngoại trừ các hạng mục không thuộc phạm vi bảo hành. Phụ tùng thay thế trong bảo hành là những chi tiết, linh kiện chính hãng nhỏ nhất được VinFast cung cấp.</p>
            </div>

            <div class="grid-item">
                <img src="https://static-cms-prod.vinfastauto.com/bao-hanh-phu-tung_1675929346.jpg" alt="">
                <span>Bảo hành phụ tùng</span>
                <p>Phụ tùng thay thế cho xe của khách hàng trong quá trình sửa chữa tại XDV/NPP của VinFast do khách hàng chịu chi phí, sẽ có thời hạn bảo hành như sau: Ô tô điện: bao gồm VF 5, VF e34, VF 6, VF 7, VF 8, VF 9: 2 năm từ ngày mua phụ tùng (không giới hạn quãng đường sử dụng).</p>
            </div>

            <div class="grid-item">
                <img src="https://static-cms-prod.vinfastauto.com/chi-tiet-gioi-han-bao-hanh_1675929360.jpg" alt="">
                <span>Các chi tiết bảo hành giới hạn</span>
                <ul>
                    <li>Ắc quy 12V: Ô tô điện: 1 năm (không giới hạn quãng đường sử dụng).</li>
                    <li>Lốp được trang bị theo xe: Bảo hành bởi nhà sản xuất lốp xe.</li>
                    <li>Những hạng mục, hư hỏng không thuộc bảo hành lốp: Hư hỏng do lốp bị phá hoại, tai nạn hoặc va chạm...</li>
                </ul>
            </div>
        </div>
    </div>
    <?php include('../client/footer.php') ?>
</body>
</html>
