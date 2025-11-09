<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="../css/modal.css">

<style>
    body {
        background-color: #f4f7f6;
    }
    .navbar-dark {
        background-color: #003366 !important; /* Màu xanh Vinfast đậm */
    }
    .container-fluid {
        padding-top: 25px;
    }

    /* Thẻ thống kê */
    .dashboard-card {
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        margin-bottom: 20px;
        padding: 25px;
        display: flex;
        align-items: center;
        transition: transform 0.2s;
    }
    .dashboard-card:hover {
        transform: translateY(-5px);
    }
    .card-icon {
        font-size: 36px;
        padding: 20px;
        border-radius: 50%;
        margin-right: 20px;
        color: white;
    }
    .icon-revenue { background-color: #28a745; } /* Xanh lá */
    .icon-orders { background-color: #007bff; } /* Xanh dương */
    .icon-users { background-color: #ffc107; } /* Vàng */
    .icon-completed { background-color: #17a2b8; } /* Xanh ngọc */

    .card-content h5 {
        font-size: 16px;
        color: #6c757d;
        text-transform: uppercase;
        margin-bottom: 5px;
    }
    .card-content .value {
        font-size: 28px;
        font-weight: 700;
        color: #343a40;
    }

    /* Biểu đồ */
    .chart-container {
        background: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        margin-bottom: 20px;
        height: 400px;
        display: flex;
        flex-direction: column;
    }
    .chart-container h5 {
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
    }
    .chart-container canvas {
        flex-grow: 1;
        position: relative;
    }


    /* Căn chỉnh Tab */
    .nav-tabs {
        margin-bottom: 20px;
    }
    .nav-tabs .nav-link {
        font-weight: 600;
        color: #495057;
    }
    .nav-tabs .nav-link.active {
        color: #003366;
        border-color: #dee2e6 #dee2e6 #fff;
        border-bottom-width: 3px;
        border-bottom-color: #003366;
    }

    /* CSS cho các trạng thái */
    .badge-pending {
        background-color: #ffc107;
        color: #212529;
    }
    .badge-completed {
        background-color: #28a745;
        color: white;
    }
    .badge-failed {
        background-color: #dc3545;
        color: white;
    }

    /* Có thể thêm CSS từ modal.css vào đây nếu muốn gộp chung */
    /* Hoặc giữ nguyên link tới modal.css */
    .modal-body .form-group label {
        font-weight: 500;
    }
    .modal-title {
        color: #003366;
    }
    
    /* NÚT XÓA SỬA TRẠNG THÁI ĐƠN HÀNG */
    .input-group-append{
        float: right;
    }
</style>