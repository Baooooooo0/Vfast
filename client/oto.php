<?php
session_start();
$host = "localhost";
$user = "root";
$password = "";
$db = "carshop";
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
$data = mysqli_connect($host, $user, $password, $db);

if (!$data) {
    die("Connection failed: " . mysqli_connect_error());
}
$sql_places = "SELECT DISTINCT pob FROM users";

$result_places = mysqli_query($data, $sql_places);

if (!$result_places) {
    die("Query failed: " . mysqli_error($data));
}

$places = [];
while ($row = mysqli_fetch_assoc($result_places)) {
    $places[] = $row['pob'];
}

$sql_products = "SELECT DISTINCT product_name from product";
$result_products = mysqli_query($data, $sql_products);
$products = [];
while ($row1 = mysqli_fetch_assoc($result_products)) {
    $products[] = $row1['product_name'];
}

$sql_infor_product = "SELECT product_id,product_name,product_price,color,image,product_number,status FROM product";

$result_infor_product = mysqli_query($data, $sql_infor_product);


mysqli_close($data);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ô tô VinFast | VinFast</title>
    <?php include('home_css.php'); ?>

</head>

<body>
    <?php include('header.php'); ?>
    <div class="main-content">
        <div class="container">
            <div class="vehicle-car">
                <div class="brand">
                    <svg width="92" height="24" viewBox="0 0 92 24" fill="none" xmlns="http://www.w3.org/2000/svg" id="VF3">
                        <g clip-path="url(#clip0_9370_7115)" class="change-title-color">

                            <path
                                d="M28.6131 0.0125771L12.6961 19.012L9.29276 3.54176C8.83961 1.48084 7.01284 0.0125771 4.89971 0.0125771H0L5.27733 24H13.7944L33.8889 0.0125771H28.6115H28.6131Z">
                            </path>
                            <path
                                d="M49.8375 2.73688H64.3368L65.0826 0.0125771L46.2233 0.0188651C38.5213 0.0220092 31.8106 5.26469 29.9523 12.7333L27.1484 24H35.5774L38.4961 14.1152H54.6176L55.3792 11.3908H39.3002L39.5975 10.3848C40.9365 5.84948 45.1046 2.73688 49.8359 2.73688H49.8375Z">
                            </path>
                            <path
                                d="M61.8824 19.0702C61.906 19.0529 61.6197 19.6612 61.67 20.3183C61.9422 23.2313 65.0624 23.9135 73.1373 23.9135C84.1954 23.9135 86.7004 22.8288 88.4721 17.2812C89.7277 13.3229 89.3076 11.9835 85.5313 10.8281C88.7521 9.90686 89.7434 8.77029 90.6749 5.83376C92.2735 0.955787 90.5884 -0.00157166 80.265 -0.00157166C69.9416 -0.00157166 67.6082 1.08312 65.794 6.69522H74.4763C75.5132 3.47416 76.2905 2.83749 79.2281 2.83749C82.943 2.83749 83.5047 3.41128 82.5103 6.34466C81.862 8.48104 81.0847 8.89605 77.716 8.89605H74.2607L73.3371 11.7005H75.9286C80.1187 11.7005 81.1996 12.4268 80.3358 15.1055C79.2989 18.2936 78.6931 21.4989 73.9744 21.4989C70.6339 21.4989 70.0612 20.8151 71.0572 17.4195H62.5637L61.884 19.0717">
                            </path>

                        </g>
                    </svg>

                    <svg width="128" height="24" viewBox="0 0 128 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        id="VF5">
                        <g clip-path="url(#clip0_9060_7885)" class="change-title-color">

                            <path
                                d="M92.2244 2.86726L93.126 0H67.3517L64.1167 10.9912H76.8447H77.4811C81.1935 10.9912 81.6708 11.8407 80.2919 16.0885C79.0191 20.2832 77.9054 21.5044 74.4582 21.5044C71.9656 21.5044 71.0641 20.6018 71.3292 18.2124H62.1545C61.3059 23.4159 64.0106 23.9469 73.6627 23.9469C84.9058 23.9469 87.5044 22.6726 89.6258 16.1416C91.6411 9.76991 90.2092 8.28319 80.504 8.17699L73.8749 8.12389L75.4128 2.81416L92.2244 2.86726Z">
                            </path>
                            <path
                                d="M28.585 0L12.675 19.0088L9.28086 3.55752C8.85659 1.48673 7.00042 0 4.93211 0H0L5.25031 24H13.7357L33.8353 0H28.585Z">
                            </path>
                            <path
                                d="M49.7999 2.70796H64.278L65.0205 0H46.1936C38.5038 0 31.7685 5.25664 29.9123 12.7434L27.1016 24H35.5339L38.4507 14.1239H54.5729L55.3684 11.4159H39.2462L39.5644 10.4071C40.8903 5.84071 45.0269 2.76106 49.7999 2.70796Z">
                            </path>
                            <path
                                d="M96.2042 22.6726H93.2344L96.7346 11.3097H102.038C103.258 11.3097 104.159 11.6283 104.69 12.2124C105.22 12.8496 105.432 13.7522 105.167 14.5487C104.955 15.6106 104.371 16.5664 103.47 17.2566C102.515 17.9469 101.295 18.3186 100.129 18.2655H97.2649L97.9013 16.0354H100.341C100.818 16.0354 101.295 15.9292 101.72 15.7168C102.038 15.5044 102.25 15.1858 102.356 14.7611C102.462 14.4425 102.409 14.1239 102.197 13.9115C101.879 13.6991 101.508 13.5929 101.136 13.646H99.0681L96.2042 22.6726Z">
                            </path>
                            <path d="M103.945 22.6726L107.605 10.9381H110.468L106.809 22.6726H103.945Z"></path>
                            <path
                                d="M111.423 22.8319C110.468 22.8319 109.832 22.5133 109.514 21.9292C109.195 21.3451 109.195 20.4425 109.567 19.3274L110.999 14.6018H113.915L112.484 19.1681C112.324 19.4867 112.324 19.9115 112.43 20.2301C112.59 20.4956 112.855 20.6549 113.173 20.6018C113.597 20.6018 113.968 20.4425 114.234 20.177C114.552 19.8584 114.764 19.4867 114.87 19.0088L116.249 14.6018H119.166L116.673 22.6726H113.862L114.34 21.0796L114.499 21.3982C114.128 21.823 113.65 22.1947 113.173 22.4071C112.59 22.6726 112.006 22.8319 111.423 22.8319Z">
                            </path>
                            <path
                                d="M121.763 22.8319C121.074 22.8319 120.437 22.7788 119.748 22.6195C119.164 22.4602 118.581 22.1947 118.051 21.8761L119.43 19.9115C119.801 20.2301 120.278 20.4425 120.755 20.6018C121.233 20.7611 121.763 20.8673 122.293 20.8673C122.559 20.8673 122.877 20.8142 123.142 20.708C123.301 20.6549 123.407 20.5487 123.46 20.3894C123.513 20.2301 123.407 20.0177 123.248 19.9646C123.036 19.8584 122.771 19.7522 122.506 19.6991C122.187 19.5929 121.869 19.5398 121.498 19.3805C121.18 19.2743 120.862 19.115 120.543 18.9558C120.278 18.7965 120.066 18.531 119.907 18.2124C119.801 17.8407 119.801 17.4159 119.907 17.0442C120.066 16.5133 120.331 16.0354 120.702 15.6637C121.127 15.2389 121.657 14.9204 122.187 14.7611C122.824 14.5487 123.513 14.4425 124.15 14.4425C124.733 14.4425 125.369 14.5487 125.9 14.708C126.377 14.8673 126.854 15.0796 127.279 15.3982L125.953 17.3097C125.263 16.7257 124.468 16.4602 123.566 16.4602C123.301 16.4602 122.983 16.5133 122.718 16.6195C122.559 16.6726 122.453 16.7788 122.4 16.9381C122.346 17.0973 122.453 17.3097 122.612 17.3628C122.824 17.5221 123.089 17.5752 123.354 17.6814C123.672 17.7876 123.991 17.8938 124.309 18C124.627 18.1062 124.945 18.2655 125.263 18.4248C125.528 18.5841 125.741 18.8496 125.9 19.115C126.059 19.4867 126.059 19.8584 125.953 20.2301C125.794 21.0265 125.263 21.7699 124.521 22.1947C123.725 22.5664 122.771 22.8319 121.763 22.8319Z">
                            </path>
                        </g>
                    </svg>

                    <svg width="92" height="31" viewBox="0 0 92 31" fill="none" xmlns="http://www.w3.org/2000/svg" id="VF6">
                        <g clip-path="url(#clip0_8158_6100)" class="change-title-color">
                            <path
                                d="M87.75 16.0925C85.6853 22.6405 83.1316 23.9168 72.1017 23.9168C59.795 23.9168 59.0343 23.0012 62.3759 12.2358C65.6088 1.99769 67.2931 0 79.6813 0H91.2002L90.3037 2.83006C89.7875 2.83006 79.1652 2.83006 78.7848 2.83006C75.1444 2.83006 74.0577 3.69017 72.5092 8.15723H76.9103C77.1276 8.15723 77.3721 8.15723 77.6166 8.15723C78.0241 8.15723 78.4317 8.15723 78.812 8.15723H78.8392C88.2933 8.26821 89.7332 9.73873 87.75 16.0925ZM75.8779 11.015C75.6606 11.015 75.4704 11.015 75.2803 10.9873H71.6127C69.0318 19.311 68.8145 21.5029 72.8896 21.5029C76.3126 21.5029 77.3721 20.2821 78.6218 16.1202C79.9802 11.8197 79.5183 11.015 75.8779 11.015Z">
                            </path>
                            <path
                                d="M27.9821 0L12.4154 19.0058L9.10098 3.55145C8.63914 1.47052 6.87328 0 4.78141 0H0L5.16175 24H8.3403H13.5021L33.1439 0H27.9821Z">
                            </path>
                            <path
                                d="M48.7658 2.74682H62.9471L63.6806 0.0277443H45.2341C37.7088 0.0277443 31.1344 5.27168 29.3142 12.7353L26.5703 24H34.8291L37.6817 14.1225H53.4658L54.1993 11.4035H38.4695L38.7683 10.4046C40.0452 5.85433 44.1203 2.74682 48.7658 2.74682Z">
                            </path>
                        </g>
                        <defs>
                            <clipPath id="clip0_8158_6100">
                                <rect width="91.2" height="24" fill="white"></rect>
                            </clipPath>
                        </defs>
                    </svg>
                    <svg width="123" height="31" viewBox="0 0 123 31" fill="none" xmlns="http://www.w3.org/2000/svg"
                        id="VFe34">
                        <g clip-path="url(#clip1_7952_5899)" class="change-title-color">
                            <path
                                d="M123.058 14.8168L122.214 17.9281H116.747L115.197 23.6372H107.755L109.316 17.9281H98.7578L99.498 15.2326L115.975 -0.418606H121.752L117.591 14.8168H123.058ZM112.431 6.53222L103.699 14.8168H110.166L112.431 6.53222Z">
                            </path>
                            <path
                                d="M24.5244 -0.404659L10.8818 18.7038L7.96615 3.1447C7.57582 1.07145 6.00972 -0.404659 4.20085 -0.404659H0L4.52455 23.7209H7.30211H11.8243L29.049 -0.404659H24.5244Z">
                            </path>
                            <path
                                d="M42.7157 2.33549H55.1421L55.7824 -0.404659L39.6168 -0.399079C33.0145 -0.396288 27.2618 4.87752 25.6695 12.3892L23.2656 23.7209H30.4892L32.9907 13.7788H46.8094L47.4616 11.0387H33.6785L33.9332 10.0258C35.0875 5.46629 38.66 2.33549 42.7157 2.33549Z">
                            </path>
                            <path d="M98.9336 5.44955C100.305 0.544073 98.8598 -0.418606 90.0107 -0.418606C81.1615 -0.418606 79.1622 0.672431 77.608 6.31736H85.0506C85.9383 3.07773 86.6048 2.43595 89.1229 2.43595C92.3075 2.43595 92.7882 3.01356 91.9362 5.96298C91.3816 8.11157 90.7152 8.52734 87.8258 8.52734H84.8649L84.0747 11.3484H86.2954C89.8869 11.3484 90.8128 12.0795 90.0726 14.7722C89.1848 17.9783 88.6635 21.2012 84.6198 21.2012C81.7565 
                21.2012 81.2662 20.512 82.1207 17.0994H74.84H74.3735C71.4936 
                19.6888 68.3852 20.4841 65.1816 20.2609C61.79 20.0265 61.1117 16.9013 61.1117 16.9013C71.515 15.1349 73.7523 12.4646 
                74.5425 11.6358C75.3898 10.7457 76.6536 8.12273 74.9828 6.76661C73.3263 5.42444 71.4722 5.51373 68.6756 5.82346C66.3621 
                6.08018 61.0379 8.18133 57.9723 11.5967C56.6942 13.0198 56.0611 14.5239 55.7541 15.7963L50.3608 16.4241L49.6016 17.557L55.5161 
                17.2473C55.5066 17.3505 55.4994 17.4482 55.4947 17.543C55.4471 18.4332 55.5708 19.3373 55.9136 20.1325C57.463 23.7321 62.2374
                 23.9804 66.8405 23.2605C70.6011 22.6718 73.0359 20.9138 74.076 20.0097C74.3092 22.9396 76.9845 23.6261 83.9058 23.6261C93.3833 
                 23.6261 95.5325 22.535 97.0486 16.9543C98.1244 12.9724 97.765 11.6274 94.5281 10.4639C97.2866 9.54861 98.1363 8.40456 98.9336 
                 5.44955ZM64.6032 9.54024C67.1166 7.41956 68.9921 7.88834 69.4015 8.33201C69.9037 8.87613 70.1869 10.4025 67.6164 12.0153C65.1983 
                 13.5333 64.5247 13.6365 61.5615 14.4011C61.5615 14.4011 62.0851 11.6665 64.6032 9.54024Z"></path>
                        </g>

                    </svg>
                    <svg width="92" height="24" viewBox="0 0 92 24" fill="none" xmlns="http://www.w3.org/2000/svg" id="VF7">
                        <g clip-path="url(#clip0_8834_7504)" class="change-title-color">
                            <path
                                d="M28.578 0L12.6798 19.0058L9.2948 3.5237C8.82312 1.47052 7.01965 0 4.88324 0H0L5.27168 24H8.51792H13.7896L33.8497 0H28.578Z">
                            </path>
                            <path
                                d="M49.8049 2.71908H64.2882L65.0373 0H46.198C38.5124 0 31.798 5.24393 29.939 12.7075L27.1367 24H35.5714L38.4847 14.1225H54.6049L55.3541 11.4035H39.2893L39.5945 10.4046C40.8986 5.82659 45.0604 2.71908 49.8049 2.71908Z">
                            </path>
                            <path
                                d="M91.2019 0.0832367H83.0169H66.1476L65.3984 2.88555H80.9915L65.8424 23.9723H71.7245C73.1672 23.9723 74.5268 23.2786 75.3869 22.0855L89.1765 2.8578L91.2019 0.0832367Z">
                            </path>
                        </g>

                    </svg>
                    <svg width="92" height="31" viewBox="0 0 92 31" fill="none" xmlns="http://www.w3.org/2000/svg" id="VF8">
                        <g clip-path="url(#clip1_7952_5908)" class="change-title-color">
                            <path
                                d="M29.2082 -0.138729L12.957 18.8587L9.47464 3.39068C9.03542 1.33442 7.15304 -0.138729 4.9883 -0.138729H0L5.39615 23.8306H8.69031H14.0865L34.6044 -0.138729H29.2082Z">
                            </path>
                            <path
                                d="M50.8878 2.59273H65.6958L66.4487 -0.138729H47.1857C39.3111 -0.138729 32.4718 5.10935 30.5581 12.5672L27.7031 23.8306H36.2993L39.2797 13.9482H55.7506L56.5349 11.2168H40.1268L40.4405 10.204C41.7896 5.69247 46.0563 2.59273 50.8878 2.59273Z">
                            </path>
                            <path
                                d="M90.7298 5.75386C91.8906 1.42649 91.6082 -0.138729 80.8787 -0.138729C71.2158 -0.138729 68.8315 -0.108038 67.2314 5.66178C66.353 8.63877 66.3216 10.2347 68.7373 10.9712C65.1922 11.6157 64.1569 12.8434 62.902 17.447C61.3961 22.8792 61.1137 23.892 73.5374 23.892C84.2983 23.892 86.4944 23.6771 88.4082 17.4776C89.569 13.5799 89.9141 11.7999 86.2121 11.0326C88.6906 10.3881 89.7572 9.52879 90.7298 5.75386ZM80.6905 14.3472L79.4042 19.0429C79.0277 20.4546 77.6786 21.4367 76.11 21.4367H72.2511C71.2472 21.4367 70.5256 20.5467 70.7766 19.6567L72.157 14.9303C72.5649 13.5493 73.9139 12.5978 75.4512 12.5978H79.2159C80.2199 12.5978 80.9414 13.4572 80.6905 14.3472ZM83.2944 4.46485L82.5415 7.16562C82.165 8.54669 80.7846 9.52879 79.2473 9.52879H75.5139C74.51 9.52879 73.757 8.63877 74.0394 7.74874L74.8237 5.04797C75.2316 3.6669 76.5806 2.71549 78.1179 2.71549H81.8199C82.8238 2.71549 83.5454 3.57483 83.2944 4.46485Z">
                            </path>
                        </g>

                    </svg>
                    <svg width="92" height="31" viewBox="0 0 92 31" fill="none" xmlns="http://www.w3.org/2000/svg" id="VF9">
                        <g clip-path="url(#clip1_7952_5911)" class="change-title-color">
                            <path
                                d="M29.0733 0L12.8995 19.0058L9.45589 3.5237C8.97604 1.47052 7.14132 0 4.96787 0H0L5.36304 24H8.66555H14.0286L34.4364 0H29.0733Z">
                            </path>
                            <path
                                d="M50.6666 2.71908H65.4008L66.1629 0H46.9971C39.1784 0 32.3475 5.24393 30.4564 12.7075L27.6055 24H36.1863L39.1501 14.1225H55.5498L56.3119 11.4035H39.9687L40.2792 10.4046C41.6058 5.82659 45.8398 2.71908 50.6666 2.71908Z">
                            </path>
                            <path d="M65.1759 7.79653C67.1518 1.2763 69.6075 0 80.2207 0C92.0476 0 92.7815 0.915607 89.5636 11.6532C86.4869 21.8913 84.8498 23.889 72.9382 23.889H60.8008L61.6476 21.059C62.1557 21.059 73.4181 21.059 73.8132 21.059C77.3133 21.059 78.3577 20.1988 79.8537 15.7318H75.6197C75.3939 15.7318 75.1681 15.7318 74.9423 15.7318C74.5471 15.7318 74.1519 15.7318 
                73.785 15.7318H73.7568C64.6396 15.6208 63.2565 14.178 65.1759 7.79653ZM76.3536 12.9017C76.5512 
                12.9017 80.7005 12.9295 80.7005 12.9295C83.4385 4.24509 84.5675 2.94104 79.3456 2.94104C76.0431 
                2.94104 74.8294 3.57919 73.5874 7.74104C72.289 11.9861 72.8535 12.7908 76.3536 12.9017Z"></path>
                        </g>

                    </svg>
                </div>
                <div class="slider">
                    <div class="slides">
                        <div class="slide">
                            <img src="../img/vf-3.jpg" alt="VF 3" class="centered-image">
                            <div class="infor-car">
                                <div class="line-car">
                                    <span class="title">Dòng xe: </span>
                                    <span class="title-desc">MiniCar</span>
                                </div>
                                <div class="seating-car">
                                    <span class="title">Số chỗ ngồi: </span>
                                    <span class="title-desc">4 chỗ</span>
                                </div>
                                <div class="distance">
                                    <span class="title">Quảng đường lên tới: </span>
                                    <span class="title-desc">~ 210 km (NEDC)</span>
                                </div>
                                <div class="price-car">
                                    <span class="title">Giá từ: </span>
                                    <span class="title-desc">320.000.000 VNĐ</span>
                                </div>

                            </div>

                        </div>
                        <div class="slide">
                            <img src="../img/vf-5.jpg" alt="VF 5">
                            <div class="infor-car">
                                <div class="line-car">
                                    <span class="title">Dòng xe: </span>
                                    <span class="title-desc">A-SUV</span>
                                </div>
                                <div class="seating-car">
                                    <span class="title">Số chỗ ngồi: </span>
                                    <span class="title-desc">5 chỗ</span>
                                </div>
                                <div class="distance">
                                    <span class="title">Quảng đường lên tới: </span>
                                    <span class="title-desc">326,4km(NEDC)</span>
                                </div>
                                <div class="price-car">
                                    <span class="title">Giá từ: </span>
                                    <span class="title-desc">538.000.000 VNĐ</span>
                                </div>

                            </div>
                        </div>
                        <div class="slide">
                            <img src="../img/vf-6.jpg" alt="VF 6">
                            <div class="infor-car">
                                <div class="line-car">
                                    <span class="title">Dòng xe: </span>
                                    <span class="title-desc">B-SUV</span>
                                </div>
                                <div class="seating-car">
                                    <span class="title">Số chỗ ngồi: </span>
                                    <span class="title-desc">5 chỗ</span>
                                </div>
                                <div class="distance">
                                    <span class="title">Quảng đường lên tới: </span>
                                    <span class="title-desc">381km (WLTP)</span>
                                </div>
                                <div class="price-car">
                                    <span class="title">Giá từ: </span>
                                    <span class="title-desc">690.000.000 VNĐ</span>
                                </div>

                            </div>
                        </div>
                        <div class="slide">
                            <img src="../img/vf-e34.jpg" alt="VF e34">
                            <div class="infor-car">
                                <div class="line-car">
                                    <span class="title">Dòng xe: </span>
                                    <span class="title-desc">C-SUV</span>
                                </div>
                                <div class="seating-car">
                                    <span class="title">Số chỗ ngồi: </span>
                                    <span class="title-desc">5 chỗ</span>
                                </div>
                                <div class="distance">
                                    <span class="title">Quảng đường lên tới: </span>
                                    <span class="title-desc">318,6km (NEDC)</span>
                                </div>
                                <div class="price-car">
                                    <span class="title">Giá từ: </span>
                                    <span class="title-desc">850.000.000 VNĐ</span>
                                </div>

                            </div>
                        </div>
                        <div class="slide">
                            <img src="../img/vf-7.jpg" alt="VF 7">
                            <div class="infor-car">
                                <div class="line-car">
                                    <span class="title">Dòng xe: </span>
                                    <span class="title-desc">C-SUV</span>
                                </div>
                                <div class="seating-car">
                                    <span class="title">Số chỗ ngồi: </span>
                                    <span class="title-desc">5 chỗ</span>
                                </div>
                                <div class="distance">
                                    <span class="title">Quảng đường lên tới: </span>
                                    <span class="title-desc">431km </span>
                                </div>
                                <div class="price-car">
                                    <span class="title">Giá từ: </span>
                                    <span class="title-desc">1.100.000.000 VNĐ</span>
                                </div>

                            </div>
                        </div>
                        <div class="slide">
                            <img src="../img/vf-8.jpg" alt="VF 8">
                            <div class="infor-car">
                                <div class="line-car">
                                    <span class="title">Dòng xe: </span>
                                    <span class="title-desc">D-SUV</span>
                                </div>
                                <div class="seating-car">
                                    <span class="title">Số chỗ ngồi: </span>
                                    <span class="title-desc">5 chỗ</span>
                                </div>
                                <div class="distance">
                                    <span class="title">Quảng đường lên tới: </span>
                                    <span class="title-desc">471km </span>
                                </div>
                                <div class="price-car">
                                    <span class="title">Giá từ: </span>
                                    <span class="title-desc">1.500.000.000 VNĐ</span>
                                </div>

                            </div>
                        </div>
                        <div class="slide">
                            <img src="../img/vf-9.jpg" alt="VF 9">
                            <div class="infor-car">
                                <div class="line-car">
                                    <span class="title">Dòng xe: </span>
                                    <span class="title-desc">E-SUV</span>
                                </div>
                                <div class="seating-car">
                                    <span class="title">Số chỗ ngồi: </span>
                                    <span class="title-desc">6-7 chỗ</span>
                                </div>
                                <div class="distance">
                                    <span class="title">Quảng đường lên tới: </span>
                                    <span class="title-desc">626km </span>
                                </div>
                                <div class="price-car">
                                    <span class="title">Giá từ: </span>
                                    <span class="title-desc">1.513.000.000 VNĐ</span>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="nav banner-nav">
                        <button class="prev"><i class="fa fa-chevron-left" aria-hidden="true"></i></button>
                        <button class="next"><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
                    </div>
                </div>


                <div class="title-list-car">
                    <h2>Danh sách tất cả các xe </h2>
                </div>
                <section class="oto-list">
                    <?php while ($row = $result_infor_product->fetch_assoc()): ?>
                        <div class="col-5">
                            <div class="small_card">
                                <i class="fas fa-heart like_car"
                                    data-id="<?php echo htmlspecialchars($row['product_id']); ?>"
                                    data-image="<?php echo htmlspecialchars($row['image']); ?>"
                                    data-name="<?php echo htmlspecialchars($row['product_name']); ?>"
                                    data-color="<?php echo htmlspecialchars($row['color']); ?>"
                                    data-stock="<?php echo htmlspecialchars($row['product_number']); ?>"></i>
                            </div>
                            <?php if ($row['product_number'] == 0): ?>
                                <div class="sold-out"></div>
                            <?php endif; ?>
                            <div class="oto-item">
                                <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>">
                                <h3><?php echo htmlspecialchars($row['product_name']); ?></h3>
                                <p>Màu: <?php echo htmlspecialchars($row['color']); ?></p>
                                <p>Số lượng tồn kho: <?php echo htmlspecialchars($row['product_number']); ?></p>
                                <button class="details"><a href="../client/detail.php?product_id=<?php echo htmlspecialchars($row['product_id']) ?>">Xem chi tiết</a></button>
                            </div>
                        </div>
                    <?php endwhile; ?>

                </section>




                <div class="flex">
                    <div class="box-item-sac">
                        <h3>3,5 km - Khoảng cách nhỏ cho mục tiêu lớn</h3><br>
                        <p class="sub-title">Định hình tiên phong thúc đẩy ngành công nghiệp xe điện, hướng tới một tương lai Xanh và Thông Minh, VinFast đã đầu tư hàng trăm triệu USD phát triển hạ tầng, từng bước "phủ rộng" trạm sạc xe điện:</p>
                        <br>
                        <ul>
                            <li>Hệ thống trạm sạc xe điện VinFast trải dài 63 tỉnh và thành phố.</li>
                            <li>106 tuyến quốc lộ quan trọng đều có trạm sạc.</li>
                            <li>80/85 thành phố đã được lắp đặt hệ thống trạm sạc.</li>
                            <li>Khoảng cách ngắn 3,5 km giữa 2 trạm sạc trong thành phố.</li>
                        </ul>
                        <br>
                        <p class="sub-title border-left-blue">VinFast cam kết nỗ lực mang đến nhiều tiện ích, giúp hành trình lái xe điện của người Việt thật dễ dàng!</p>
                    </div>
                    <div class="image-container">
                        <img style="height:500px" src="https://shop.vinfastauto.com/on/demandware.static/-/Sites-app_vinfast_vn-Library/default/dwafcac90d/reserves/VF3/map.jpg" alt="Map">
                    </div>
                </div>


                <div class="modal fade" id="myModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <i class="modal-icon fas fa-pen"></i>
                                <h4 class="modal-title">Thông tin chủ xe</h4>
                                <button type="button" class="close" data-dismiss="modal">X</button>
                            </div>

                            <div class="modal-body">
                                <form action="../client/process_cart.php" id="combinedForm" method="POST">
                                    <div class="warningMessage">
                                        <span id="contetnt-warning"></span>
                                    </div>
                                    <div class="form-section active">
                                        <div class="form-group">
                                            <i class="fa fa-user"></i>
                                            <input type="text" class="form-control" placeholder="Họ và tên" name="name" required>
                                        </div>
                                        <div class="form-group">
                                            <i class="fa fa-phone"></i>
                                            <input type="text" class="form-control" placeholder="Số điện thoại" name="phone" required>
                                        </div>
                                        <div class="form-group">
                                            <i class="fa fa-envelope"></i>
                                            <input type="email" class="form-control" placeholder="Email" name="email" required>
                                        </div>
                                        <div class="form-group">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M15.8333 12.5H14.1667V10.8333H15.8333V12.5ZM15.8333 15.8333H14.1667V14.1666H15.8333V15.8333ZM10.8333 5.83329H9.16667V4.16663H10.8333V5.83329ZM10.8333 9.16663H9.16667V7.49996H10.8333V9.16663ZM10.8333 12.5H9.16667V10.8333H10.8333V12.5ZM10.8333 15.8333H9.16667V14.1666H10.8333V15.8333ZM5.83333 9.16663H4.16667V7.49996H5.83333V9.16663ZM5.83333 12.5H4.16667V10.8333H5.83333V12.5ZM5.83333 15.8333H4.16667V14.1666H5.83333V15.8333ZM12.5 9.16663V4.16663L10 1.66663L7.5 4.16663V5.83329H2.5V17.5H17.5V9.16663H12.5Z" fill="#8a8a8a"></path>
                                            </svg>
                                            <select name="place" id="placeSelect" class="form-control" required>
                                                <option value="" disabled selected hidden>Tỉnh thành</option>
                                                <?php foreach ($places as $place): ?>
                                                    <option value="<?php echo htmlspecialchars($place); ?>"><?php echo htmlspecialchars($place); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M10.0002 1.66663C12.7585 1.66663 15.0002 3.88329 15.0002 6.62496C15.0002 10.3416 10.0002 15.8333 10.0002 15.8333C10.0002 15.8333 5.00016 10.3416 5.00016 6.62496C5.00016 3.88329 7.24183 1.66663 10.0002 1.66663ZM10.0002 4.99996C9.55814 4.99996 9.13421 5.17555 8.82165 5.48811C8.50909 5.80067 8.3335 6.2246 8.3335 6.66663C8.3335 7.10865 8.50909 7.53258 8.82165 7.84514C9.13421 8.1577 9.55814 8.33329 10.0002 8.33329C10.4422 8.33329 10.8661 8.1577 11.1787 7.84514C11.4912 7.53258 11.6668 7.10865 11.6668 6.66663C11.6668 6.2246 11.4912 5.80067 11.1787 5.48811C10.8661 5.17555 10.4422 4.99996 10.0002 4.99996ZM16.6668 15.8333C16.6668 17.675 13.6835 19.1666 10.0002 19.1666C6.31683 19.1666 3.3335 17.675 3.3335 15.8333C3.3335 14.7583 4.35016 13.8 5.92516 13.1916L6.4585 13.95C5.5585 14.325 5.00016 14.8416 5.00016 15.4166C5.00016 16.5666 7.24183 17.5 10.0002 17.5C12.7585 17.5 15.0002 16.5666 15.0002 15.4166C15.0002 14.8416 14.4418 14.325 13.5418 13.95L14.0752 13.1916C15.6502 13.8 16.6668 14.7583 16.6668 15.8333Z" fill="#8a8a8a"></path>
                                            </svg>
                                            <select name="showroom" id="showroomSelect" class="form-control" required>
                                                <option value="" disabled selected hidden>Showroom nhận xe</option>
                                            </select>
                                        </div>
                                        <div class="form-navigation1">
                                            <button type="button" class="btn btn-primary next-btn">Bước kế tiếp</button>
                                        </div>
                                    </div>

                                    <div class="warningMessage">
                                        <span id="contetnt-warning"></span>
                                    </div>
                                    <div class="form-section">
                                        <div class="form-group">
                                            <select name="product_name" id="Select_product" class="form-control" required>
                                                <option value="" disabled selected hidden>Tên sản phẩm</option>
                                                <?php foreach ($products as $product): ?>
                                                    <option value="<?php echo htmlspecialchars($product); ?>"><?php echo htmlspecialchars($product); ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <select name="color" id="showColor" class="form-control" required>
                                                <option value="" disabled selected hidden>Màu xe</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <input type="number" placeholder="Số lượng mua xe" name="transaction_number" min="1" max="10">
                                        </div>
                                        <div class="form-group">
                                            <b>Giá xe : </b>&nbsp;<span id="price-display"></span>
                                        </div>
                                        <div class="form-navigation">
                                            <button type="button" class="btn btn-primary prev-btn">Quay lại</button>
                                            <button type="button" class="btn btn-primary next-btn">Bước kế tiếp</button>
                                        </div>
                                    </div>

                                    <div class="warningMessage">
                                        <span id="content-warning"></span>
                                    </div>
                                    <div class="form-section">
                                        <div class="form-group">
                                            <span>Đặt cọc trước <b>10%</b>&nbsp;&rarr;&nbsp;<b>15,000,000 VND</b></span>
                                        </div>
                                        <div class="form-group">
                                            <select name="payment_method" class="form-control" required>
                                                <option value="" disabled selected hidden>Phương thức thanh toán</option>
                                                <option value="Tiền mặt">Tiền mặt</option>
                                                <option value="Chuyển khoản">Chuyển khoản</option>
                                                <option value="Trả góp">Trả góp</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <span>Quét mã QR tại đây để thanh toán: </span>
                                        </div>
                                        <div class="form-group">
                                            <img src="../img/qr_code.png" alt="qr_code">
                                        </div>

                                        <div class="form-navigation">
                                            <button type="button" class="btn btn-primary prev-btn">Quay lại</button>
                                            <button type="submit" class="btn btn-primary submit-btn">Gửi</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- Close main-content -->

            <?php include('../client/footer.php') ?>
</body>


<script src="../js/slick.js"></script>

<script>
    document.querySelector('.submit-btn').addEventListener('click', function() {
        var combinedForm = document.createElement('form');
        combinedForm.method = 'POST';
        combinedForm.action = '../client/process_cart.php';


        var forms = [document.getElementById('inforUser'), document.getElementById('inforProduct'), document.getElementById('inforShipping')];

        forms.forEach(function(form) {
            var inputs = form.querySelectorAll('input, select');
            inputs.forEach(function(input) {
                if (input.name && input.value) {
                    var hiddenField = document.createElement('input');
                    hiddenField.type = 'hidden';
                    hiddenField.name = input.name;
                    hiddenField.value = input.value;
                    combinedForm.appendChild(hiddenField);
                }
            });
        });


        document.body.appendChild(combinedForm);
        combinedForm.submit();
    });


    //Xử lý price khi chọn product
    document.getElementById("Select_product").onchange = function() {
        var productName = this.value;
        fetch("get_productname.php?product_name=" + productName)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log(data);

                document.getElementById("price-display").innerText = data.price ? data.price + " VND" : "Không có giá";

                const showColor = document.getElementById('showColor');
                showColor.innerHTML = '<option value="" disabled selected hidden>Màu</option>';

                data.colors.forEach(color => {
                    const option = document.createElement('option');
                    option.value = color.color;
                    option.textContent = color.color;

                    // Kiểm tra xem màu có trong kho hay không
                    if (!color.in_stock) {
                        option.disabled = true;
                        option.title = "Dự kiến hàng sẽ có vào ngày 20/9/2024";
                    }

                    showColor.appendChild(option);
                });

                showColor.disabled = !data.inStock;

            })
            .catch(error => {
                console.error('Fetch error:', error);
                document.getElementById("price-display").innerText = "Lỗi khi nhận giá";
            });
    };



    //Xử lý Showroom theo location
    document.getElementById('placeSelect').addEventListener('change', function() {
        const place = this.value;

        if (place) {
            fetch('get_showrooms.php?place=' + place)
                .then(response => response.json())
                .then(data => {
                    const showroomSelect = document.getElementById('showroomSelect');
                    showroomSelect.innerHTML = '<option value="" disabled selected hidden>Showroom nhận xe</option>';
                    data.forEach(showroom => {
                        const option = document.createElement('option');
                        option.value = showroom;
                        option.textContent = showroom;
                        showroomSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching showrooms:', error));
        }
    });



    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý like sản phẩm
        const likeButtons = document.querySelectorAll('.like_car');
        likeButtons.forEach(initLikeButton);

        // Hàm khởi tạo nút like
        function initLikeButton(likeButton) {
            const product = {
                product_id: likeButton.getAttribute('data-id'),
                image: likeButton.getAttribute('data-image'),
                name: likeButton.getAttribute('data-name'),
                color: likeButton.getAttribute('data-color'),
                stock: likeButton.getAttribute('data-stock')
            };

            // Kiểm tra nếu sản phẩm đã được like và cập nhật màu sắc nút
            if (isProductLiked(product)) {
                likeButton.style.color = 'red';
                likeButton.title = 'Bỏ yêu thích';
            } else {
                likeButton.style.color = '#ccc';
                likeButton.title = 'Thêm vào yêu thích';
            }

            // Thêm sự kiện click cho nút like
            likeButton.addEventListener('click', function() {
                toggleLike(product, likeButton);
            });
        }

        // Hàm kiểm tra sản phẩm đã được like hay chưa
        function isProductLiked(product) {
            const currentUserId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '0'; ?>;
            const storageKey = 'likeCars_' + currentUserId;
            const likeCars = JSON.parse(localStorage.getItem(storageKey)) || [];
            return likeCars.some(p => p.name === product.name && p.color === product.color);
        }

        // Hàm thêm hoặc xóa sản phẩm khỏi danh sách yêu thích
        function toggleLike(product, likeButton) {
            const currentUserId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '0'; ?>;
            const storageKey = 'likeCars_' + currentUserId;
            let likeCars = JSON.parse(localStorage.getItem(storageKey)) || [];
            const productIndex = likeCars.findIndex(p => p.name === product.name && p.color === product.color);

            if (productIndex !== -1) {
                // Nếu sản phẩm đã tồn tại, xóa khỏi danh sách yêu thích
                likeCars.splice(productIndex, 1);
                likeButton.style.color = '#ccc'; // Màu xám cho chưa yêu thích
                likeButton.title = 'Thêm vào yêu thích';
                showNotification('Đã bỏ xe khỏi danh sách yêu thích', 'warning');
            } else {
                // Nếu chưa tồn tại, thêm sản phẩm vào danh sách yêu thích
                likeCars.push(product);
                likeButton.style.color = 'red'; // Đặt màu đỏ cho nút đã yêu thích
                likeButton.title = 'Bỏ yêu thích';
                showNotification('Đã thêm xe vào danh sách yêu thích', 'success');
            }

            // Cập nhật localStorage
            localStorage.setItem(storageKey, JSON.stringify(likeCars));

            // Cập nhật số lượng
            updateCountHeart();

            // Gửi dữ liệu sang server
            sendLikedCars();
        }

        function showNotification(message, type = 'success') {
            // Xóa thông báo cũ nếu có
            const existingNotification = document.querySelector('.like-notification');
            if (existingNotification) {
                existingNotification.remove();
            }

            const notification = document.createElement('div');
            notification.className = 'like-notification';
            notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#28a745' : type === 'warning' ? '#ffc107' : '#dc3545'};
            color: ${type === 'warning' ? '#333' : 'white'};
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1000;
            font-size: 14px;
            max-width: 300px;
            animation: slideIn 0.3s ease-out;
        `;

            // Thêm animation CSS nếu chưa có
            if (!document.querySelector('#notification-styles')) {
                const style = document.createElement('style');
                style.id = 'notification-styles';
                style.textContent = `
                @keyframes slideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                @keyframes slideOut {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(100%); opacity: 0; }
                }
            `;
                document.head.appendChild(style);
            }

            notification.textContent = message;
            document.body.appendChild(notification);

            // Tự động xóa sau 3 giây
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease-in';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }



        // Hàm cập nhật số lượng sản phẩm yêu thích
        function updateCountHeart() {
            const currentUserId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '0'; ?>;
            const storageKey = 'likeCars_' + currentUserId;
            const likeProducts = JSON.parse(localStorage.getItem(storageKey)) || [];
            const countElement = document.querySelector('.count-heart');
            if (countElement) {
                if (likeProducts.length > 0) {
                    countElement.textContent = likeProducts.length;
                    countElement.style.display = 'flex';
                    countElement.style.visibility = 'visible';
                } else {
                    countElement.textContent = '';
                    countElement.style.display = 'none';
                    countElement.style.visibility = 'hidden';
                }
            }

            // Trigger event để notify header
            window.dispatchEvent(new CustomEvent('likeCountChanged', {
                detail: {
                    count: likeProducts.length
                }
            }));
        }

        // Hàm gửi dữ liệu localStorage sang liked_car.php
        function sendLikedCars() {
            const currentUserId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '0'; ?>;
            const storageKey = 'likeCars_' + currentUserId;
            const likeCars = JSON.parse(localStorage.getItem(storageKey)) || [];
            fetch('liked_car.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(likeCars),
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data.message); // Thông báo từ server
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            console.log(likeCars);
        }

        // Cập nhật số lượng yêu thích khi tải trang
        updateCountHeart();
    });


    //Xóa localStorage
    // localStorage.clear(); 



    // Modal và điều kiện
    document.addEventListener('DOMContentLoaded', function() {
        function initForm() {
            const formSections = document.querySelectorAll(".form-section");
            const nextBtns = document.querySelectorAll(".next-btn");
            const prevBtns = document.querySelectorAll(".prev-btn");
            let currentForm = 0;

            function showForm() {
                formSections.forEach((section, index) => {
                    section.style.display = 'none';
                });
                formSections[currentForm].style.display = 'block';
            }

            function validateCurrentForm() {
                const currentSectionInputs = formSections[currentForm].querySelectorAll('input, select');
                // console.log("Validating Form Section: ", currentForm);
                let isValid = true;

                currentSectionInputs.forEach(function(input) {
                    // console.log("Input Value: ", input.value.trim());
                    if (input.value.trim() === "") {
                        isValid = false;
                    }
                });

                return isValid;
            }

            function NotifyWarning() {
                const messageWarning = "Vui lòng điền đầy đủ thông tin trước khi qua bước kế tiếp!";
                document.getElementById("content-warning").innerHTML = messageWarning;
                document.getElementById("content-warning").style.display = 'block';
            }

            function ClearWarning() {
                document.getElementById("content-warning").innerHTML = "";
                document.getElementById("content-warning").style.display = 'none';
            }

            nextBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    if (validateCurrentForm()) {
                        ClearWarning();
                        if (currentForm < formSections.length - 1) {
                            currentForm++;
                            showForm();

                        }
                    } else {
                        NotifyWarning();
                    }
                });
            });
            prevBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    if (currentForm > 0) {
                        currentForm--;
                        showForm();
                    }
                });
            });

            showForm();
        }

        initForm();
    });

    // Vehicle Slider Navigation
    document.addEventListener('DOMContentLoaded', function() {
        const vehicleSlider = document.querySelector('.vehicle-car .slider');
        const vehicleSlides = document.querySelector('.vehicle-car .slides');
        const vehicleSlideElements = document.querySelectorAll('.vehicle-car .slide');
        const vehicleNextBtn = document.querySelector('.vehicle-car .banner-nav .next');
        const vehiclePrevBtn = document.querySelector('.vehicle-car .banner-nav .prev');

        if (!vehicleSlides || vehicleSlideElements.length === 0) return;

        let currentVehicleSlide = 0;
        const totalVehicleSlides = vehicleSlideElements.length;

        // Auto play interval
        let vehicleAutoPlayInterval;

        function showVehicleSlide(index) {
            if (index >= totalVehicleSlides) {
                currentVehicleSlide = 0;
            } else if (index < 0) {
                currentVehicleSlide = totalVehicleSlides - 1;
            } else {
                currentVehicleSlide = index;
            }

            // Update slider position
            if (vehicleSlides) {
                vehicleSlides.style.transform = `translateX(-${currentVehicleSlide * 100}%)`;
            }

            // Update slide active state
            vehicleSlideElements.forEach((slide, i) => {
                slide.classList.toggle('active', i === currentVehicleSlide);
            });

            // Update brand titles
            updateActiveBrand();
        }

        function updateActiveBrand() {
            const brandElements = document.querySelectorAll('.brand svg');
            brandElements.forEach((brand, i) => {
                if (i === currentVehicleSlide) {
                    brand.style.opacity = '1';
                    brand.style.transform = 'scale(1.1)';
                } else {
                    brand.style.opacity = '0.5';
                    brand.style.transform = 'scale(1)';
                }
            });
        }

        function nextVehicleSlide() {
            showVehicleSlide(currentVehicleSlide + 1);
        }

        function prevVehicleSlide() {
            showVehicleSlide(currentVehicleSlide - 1);
        }

        function startVehicleAutoPlay() {
            vehicleAutoPlayInterval = setInterval(nextVehicleSlide, 6000); // 6 seconds
        }

        function stopVehicleAutoPlay() {
            clearInterval(vehicleAutoPlayInterval);
        }

        // Event listeners
        if (vehicleNextBtn) {
            vehicleNextBtn.addEventListener('click', (e) => {
                e.preventDefault();
                nextVehicleSlide();
                stopVehicleAutoPlay();
                setTimeout(startVehicleAutoPlay, 4000);
            });
        }

        if (vehiclePrevBtn) {
            vehiclePrevBtn.addEventListener('click', (e) => {
                e.preventDefault();
                prevVehicleSlide();
                stopVehicleAutoPlay();
                setTimeout(startVehicleAutoPlay, 4000);
            });
        }

        // Brand logo clicks
        const brandLogos = document.querySelectorAll('.brand svg');
        brandLogos.forEach((logo, index) => {
            logo.style.cursor = 'pointer';
            logo.style.transition = 'all 0.3s ease';
            logo.addEventListener('click', () => {
                showVehicleSlide(index);
                stopVehicleAutoPlay();
                setTimeout(startVehicleAutoPlay, 4000);
            });
        });

        // Pause on hover
        if (vehicleSlider) {
            vehicleSlider.addEventListener('mouseenter', stopVehicleAutoPlay);
            vehicleSlider.addEventListener('mouseleave', startVehicleAutoPlay);
        }

        // Keyboard navigation for vehicle slider
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft' && e.ctrlKey) {
                prevVehicleSlide();
                stopVehicleAutoPlay();
                setTimeout(startVehicleAutoPlay, 4000);
            } else if (e.key === 'ArrowRight' && e.ctrlKey) {
                nextVehicleSlide();
                stopVehicleAutoPlay();
                setTimeout(startVehicleAutoPlay, 4000);
            }
        });

        // Initialize
        showVehicleSlide(0);
        startVehicleAutoPlay();
    });
</script>

</html>