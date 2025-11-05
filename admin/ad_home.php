<?php
session_start();

// 1. KIỂM TRA BẢO MẬT
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] != 'admin') {
    header("Location: ../client/login.php");
    exit();
}

// 2. KẾT NỐI DATABASE
$host = "localhost";
$user = "root";
$password = "";
$db = "carshop";

$data = mysqli_connect($host, $user, $password, $db);
if ($data === false) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($data, 'utf8mb4');

// =================================================================
// 3. TRUY VẤN DỮ LIỆU CHO DASHBOARD
// =================================================================

// ... (Phần truy vấn dữ liệu PHP giữ nguyên như trước) ...
// 3.1. Dữ liệu cho các Thẻ Thống Kê
$q_revenue = mysqli_query($data, "SELECT SUM(deposit) AS total_revenue FROM transactions WHERE transaction_status = 'completed'");
$total_revenue = mysqli_fetch_assoc($q_revenue)['total_revenue'] ?? 0;

$q_orders = mysqli_query($data, "SELECT COUNT(transaction_id) AS total_orders FROM transactions");
$total_orders = mysqli_fetch_assoc($q_orders)['total_orders'] ?? 0;

$q_users = mysqli_query($data, "SELECT COUNT(id) AS total_users FROM users WHERE usertype = 'user'");
$total_users = mysqli_fetch_assoc($q_users)['total_users'] ?? 0;

$q_completed = mysqli_query($data, "SELECT COUNT(transaction_id) AS completed_orders FROM transactions WHERE transaction_status = 'completed'");
$completed_orders = mysqli_fetch_assoc($q_completed)['completed_orders'] ?? 0;

// 3.2. Dữ liệu cho Biểu đồ tròn (Trạng thái đơn hàng)
$q_status = mysqli_query($data, "SELECT transaction_status, COUNT(*) AS count FROM transactions GROUP BY transaction_status");
$status_data = ['pending' => 0, 'completed' => 0, 'failed' => 0];
while($row = mysqli_fetch_assoc($q_status)) {
    if (array_key_exists($row['transaction_status'], $status_data)) {
        $status_data[$row['transaction_status']] = $row['count'];
    }
}
$status_labels_json = json_encode(array_keys($status_data));
$status_values_json = json_encode(array_values($status_data));


// 3.3. Dữ liệu cho Biểu đồ cột (Top 5 Sản phẩm theo Doanh thu cọc - đã hoàn thành)
$q_top_products = mysqli_query($data, "
    SELECT p.product_name, SUM(t.deposit) AS total_deposit
    FROM transactions t
    JOIN product p ON t.product_id = p.product_id
    WHERE t.transaction_status = 'completed'
    GROUP BY p.product_id, p.product_name
    ORDER BY total_deposit DESC
    LIMIT 5
");
$product_labels = [];
$product_revenue_data = [];
while($row = mysqli_fetch_assoc($q_top_products)) {
    $product_labels[] = $row['product_name'];
    $product_revenue_data[] = $row['total_deposit'];
}
$product_labels_json = json_encode($product_labels);
$product_revenue_json = json_encode($product_revenue_data);


// =================================================================
// 4. TRUY VẤN DỮ LIỆU CHO CÁC BẢNG QUẢN LÝ
// =================================================================

// 4.1. Truy vấn tất cả Giao dịch
$sql_transactions = "
    SELECT
        t.transaction_id, t.transaction_date, t.deposit, t.transaction_number, t.transaction_status, t.payment_method,
        u.name AS user_name, u.phone AS user_phone, u.pob AS user_pob,
        p.product_id, p.product_name, p.color AS product_color, p.product_price
    FROM transactions t
    JOIN users u ON t.customer_id = u.id
    JOIN product p ON t.product_id = p.product_id
    ORDER BY t.transaction_date DESC
";
$result_transactions = mysqli_query($data, $sql_transactions);

// 4.2. Truy vấn tất cả Sản phẩm (Tồn kho)
$sql_products = "SELECT * FROM product ORDER BY product_name, color";
$result_products = mysqli_query($data, $sql_products);

// 4.3. Truy vấn tất cả Người dùng
$sql_users = "SELECT id, name, email, phone, usertype FROM users ORDER BY name";
$result_users = mysqli_query($data, $sql_users);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Quản Trị Vfast</title>

    <?php include('ad_home_css.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="#">
        <i class="fas fa-tachometer-alt"></i> Vfast Dashboard
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="adminNavbar">
        <ul class="navbar-nav mr-auto">
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <span class="navbar-text mr-3">
                    Chào, <?php echo htmlspecialchars($_SESSION['email']); ?>!
                </span>
            </li>
            <li class="nav-item">
                <a class="btn btn-outline-light" href="../client/logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container-fluid">
    <ul class="nav nav-tabs" id="adminTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="dashboard-tab" data-toggle="tab" href="#dashboard" role="tab" aria-controls="dashboard" aria-selected="true">
                <i class="fas fa-chart-line"></i> Bảng điều khiển
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="transactions-tab" data-toggle="tab" href="#transactions" role="tab" aria-controls="transactions" aria-selected="false">
                <i class="fas fa-list-alt"></i> Quản lý Giao dịch
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="products-tab" data-toggle="tab" href="#products" role="tab" aria-controls="products" aria-selected="false">
                <i class="fas fa-car"></i> Quản lý Sản phẩm
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="users-tab" data-toggle="tab" href="#users" role="tab" aria-controls="users" aria-selected="false">
                <i class="fas fa-users"></i> Quản lý Người dùng
            </a>
        </li>
    </ul>

    <div class="tab-content" id="adminTabContent">
        <div class="tab-pane fade show active" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="dashboard-card">
                        <div class="card-icon icon-revenue">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="card-content">
                            <h5>Doanh thu (Cọc)</h5>
                            <span class="value"><?php echo number_format($total_revenue, 0, ',', '.'); ?>đ</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="dashboard-card">
                        <div class="card-icon icon-orders">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="card-content">
                            <h5>Tổng Đơn Hàng</h5>
                            <span class="value"><?php echo $total_orders; ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="dashboard-card">
                        <div class="card-icon icon-users">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="card-content">
                            <h5>Khách Hàng</h5>
                            <span class="value"><?php echo $total_users; ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="dashboard-card">
                        <div class="card-icon icon-completed">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="card-content">
                            <h5>Đơn Hoàn Thành</h5>
                            <span class="value"><?php echo $completed_orders; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="chart-container">
                        <h5>Trạng thái Đơn hàng</h5>
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="chart-container">
                        <h5>Top 5 Sản phẩm (Theo Doanh thu cọc)</h5>
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="transactions" role="tabpanel" aria-labelledby="transactions-tab">
           <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Quản lý Giao dịch</h2>
                <button class="btn btn-primary" data-toggle="modal" data-target="#insertModal">
                    <i class="fas fa-plus"></i> Thêm Giao dịch
                </button>
            </div>
            <div class="table-responsive bg-white p-3 rounded shadow-sm">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th> <th>Khách hàng</th> <th>Sản phẩm</th> <th>Màu</th>
                            <th>SL</th> <th>Đã cọc</th> <th>Trạng thái</th> <th>Ngày đặt</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        mysqli_data_seek($result_transactions, 0); // Reset pointer
                        if ($result_transactions && mysqli_num_rows($result_transactions) > 0) {
                            while ($row = mysqli_fetch_assoc($result_transactions)) {
                                $status_class = '';
                                if ($row['transaction_status'] == 'pending') $status_class = 'badge-pending';
                                elseif ($row['transaction_status'] == 'completed') $status_class = 'badge-completed';
                                elseif ($row['transaction_status'] == 'failed') $status_class = 'badge-failed';
                        ?>
                                <tr>
                                    <td><?php echo $row['transaction_id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['product_color']); ?></td>
                                    <td><?php echo $row['transaction_number']; ?></td>
                                    <td><?php echo number_format($row['deposit'], 0, ',', '.'); ?>đ</td>
                                    <td><span class="badge <?php echo $status_class; ?>"><?php echo htmlspecialchars($row['transaction_status']); ?></span></td>
                                    <td><?php echo date("d-m-Y", strtotime($row['transaction_date'])); ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal<?php echo $row['transaction_id']; ?>" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="delete.php?transaction_id=<?php echo $row['transaction_id']; ?>"
                                           class="btn btn-danger btn-sm" title="Xóa"
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa giao dịch này? Hành động này sẽ hoàn lại tồn kho.');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editModal<?php echo $row['transaction_id']; ?>" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <form action="update.php" method="POST">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Cập nhật Giao dịch #<?php echo $row['transaction_id']; ?></h5>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="transaction_id" value="<?php echo $row['transaction_id']; ?>">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h5>Thông tin khách hàng</h5>
                                                            <div class="form-group"><label>Tên</label><input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($row['user_name']); ?>"></div>
                                                            <div class="form-group"><label>SĐT</label><input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($row['user_phone']); ?>"></div>
                                                            <div class="form-group"><label>Tỉnh</label><input type="text" class="form-control" name="pob" value="<?php echo htmlspecialchars($row['user_pob']); ?>"></div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h5>Thông tin đơn hàng</h5>
                                                            <div class="form-group"><label>Sản phẩm</label><input type="text" class="form-control" name="product_name" value="<?php echo htmlspecialchars($row['product_name']); ?>"></div>
                                                            <div class="form-group"><label>Màu</label><input type="text" class="form-control" name="color" value="<?php echo htmlspecialchars($row['product_color']); ?>"></div>
                                                            <div class="form-group"><label>Giá xe</label><input type="text" class="form-control" name="price" value="<?php echo number_format($row['product_price'], 0, ',', ''); ?>"></div>
                                                            <div class="form-group"><label>Đã cọc</label><input type="text" class="form-control" name="deposit" value="<?php echo number_format($row['deposit'], 0, ',', ''); ?>"></div>
                                                            <div class="form-group"><label>Số lượng</label><input type="number" class="form-control" name="number" value="<?php echo $row['transaction_number']; ?>"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                                    <button type="submit" class="btn btn-primary">Lưu</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            } // End while
                        } else {
                            echo '<tr><td colspan="9" class="text-center">Không có giao dịch.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-pane fade" id="products" role="tabpanel" aria-labelledby="products-tab">
           <h2>Quản lý Sản phẩm (Tồn kho)</h2>
           <div class="table-responsive bg-white p-3 rounded shadow-sm">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th> <th>Tên</th> <th>Màu</th> <th>Ảnh</th>
                            <th>Giá (VNĐ)</th> <th>Tồn kho</th> <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        mysqli_data_seek($result_products, 0); // Reset pointer
                        if ($result_products && mysqli_num_rows($result_products) > 0) {
                            while ($row = mysqli_fetch_assoc($result_products)) {
                                $stock_status = ($row['product_number'] > 0) ? 'còn' : 'hết';
                                $status_class = ($stock_status == 'còn') ? 'badge-completed' : 'badge-failed';
                        ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['product_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['color']); ?></td>
                                    <td><img src="../<?php echo htmlspecialchars(ltrim($row['image'], '.')); ?>" alt="Ảnh xe" style="width: 100px; height: auto; border-radius: 5px;"></td>
                                    <td><?php echo number_format($row['product_price'], 0, ',', '.'); ?></td>
                                    <td><strong><?php echo $row['product_number']; ?></strong></td>
                                    <td><span class="badge <?php echo $status_class; ?>"><?php echo $stock_status; ?></span></td>
                                </tr>
                        <?php
                            } // End while
                        } else {
                            echo '<tr><td colspan="7" class="text-center">Không có sản phẩm.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-pane fade" id="users" role="tabpanel" aria-labelledby="users-tab">
           <h2>Quản lý Người dùng</h2>
            <div class="table-responsive bg-white p-3 rounded shadow-sm">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th> <th>Tên</th> <th>Email</th> <th>SĐT</th> <th>Loại TK</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        mysqli_data_seek($result_users, 0); // Reset pointer
                        if ($result_users && mysqli_num_rows($result_users) > 0) {
                            while ($row = mysqli_fetch_assoc($result_users)) {
                                $usertype_class = ($row['usertype'] == 'admin') ? 'badge-danger font-weight-bold' : 'badge-secondary';
                        ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                    <td><span class="badge <?php echo $usertype_class; ?>"><?php echo htmlspecialchars($row['usertype']); ?></span></td>
                                </tr>
                        <?php
                            } // End while
                        } else {
                            echo '<tr><td colspan="5" class="text-center">Không có người dùng.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> </div> <div class="modal fade" id="insertModal" tabindex="-1" role="dialog" aria-labelledby="insertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="insert.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="insertModalLabel">Thêm Giao dịch Mới (Thủ công)</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Lưu ý: Tạo người dùng/sản phẩm mới nếu chưa tồn tại.</p>
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Thông tin khách hàng</h5>
                            <div class="form-group"><label>Tên (*)</label><input type="text" class="form-control" name="name" required></div>
                            <div class="form-group"><label>SĐT (*)</label><input type="text" class="form-control" name="phone" required></div>
                            <div class="form-group"><label>Tỉnh (*)</label><input type="text" class="form-control" name="pob" required></div>
                        </div>
                        <div class="col-md-6">
                            <h5>Thông tin đơn hàng</h5>
                            <div class="form-group"><label>ID Giao dịch (*)</label><input type="text" class="form-control" name="transaction_id" required></div>
                            <div class="form-group"><label>Sản phẩm (*)</label><input type="text" class="form-control" name="product_name" required></div>
                            <div class="form-group"><label>Màu (*)</label><input type="text" class="form-control" name="color" required></div>
                            <div class="form-group"><label>Số lượng (*)</label><input type="number" class="form-control" name="number" value="1" required></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
mysqli_close($data); // Close DB connection
?>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script>
    // Hàm format tiền tệ
    function formatCurrency(value) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
    }

    // Biểu đồ tròn
    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    new Chart(ctxStatus, { /* ... Cấu hình biểu đồ tròn như cũ ... */
        type: 'doughnut', data: { labels: <?php echo $status_labels_json; ?>, datasets: [{ data: <?php echo $status_values_json; ?>, backgroundColor: ['rgba(255, 193, 7, 0.8)','rgba(40, 167, 69, 0.8)','rgba(220, 53, 69, 0.8)'], borderColor: ['rgba(255, 193, 7, 1)','rgba(40, 167, 69, 1)','rgba(220, 53, 69, 1)'], borderWidth: 1 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'right', }, tooltip: { callbacks: { label: function(c){ return (c.label||'') + ': ' + (c.parsed||0) + ' đơn'; } } } } }
    });

    // Biểu đồ cột
    const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctxRevenue, { /* ... Cấu hình biểu đồ cột như cũ ... */
        type: 'bar', data: { labels: <?php echo $product_labels_json; ?>, datasets: [{ label: 'Doanh thu (Cọc)', data: <?php echo $product_revenue_json; ?>, backgroundColor: 'rgba(0, 51, 102, 0.7)', borderColor: 'rgba(0, 51, 102, 1)', borderWidth: 1 }] }, options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { callback: function(v){ return formatCurrency(v); } } } }, plugins: { legend: { display: false }, tooltip: { callbacks: { label: function(c){ return (c.dataset.label||'') + ': ' + formatCurrency(c.parsed.y||0); } } } } }
    });

    // Kích hoạt Tab và Lưu trạng thái
    $(document).ready(function(){
        let lastTab = localStorage.getItem('lastAdminTab');
        if (lastTab) {
            $('#adminTab a[href="' + lastTab + '"]').tab('show');
        } else {
            $('#adminTab a:first').tab('show'); // Mặc định tab đầu tiên
        }

        $('#adminTab a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
            localStorage.setItem('lastAdminTab', $(this).attr('href'));
        });
    });
</script>

</body>
</html>