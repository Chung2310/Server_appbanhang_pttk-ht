<?php
include "connect.php";

// Kiểm tra xem tham số "page" và "loai" có được truyền không
$page = isset($_GET['page']) ? (int)$_GET['page'] : null;
$loai = isset($_GET['loai']) ? (int)$_GET['loai'] : null;

// Nếu thiếu tham số, trả về lỗi
if ($page === null || $loai === null) {
    echo json_encode([
        'success' => false,
        'message' => "Thieu tham so page hoac loai",
    ]);
    exit();
}

$total = 5;
$pos = ($page - 1) * $total;

// Tạo truy vấn SQL
$query = "SELECT * FROM `sanphammoi` WHERE `loai` = $loai LIMIT $pos, $total";

// Thực thi truy vấn
$result = mysqli_query($conn, $query);

// Kiểm tra xem truy vấn có thành công không
if ($result) {
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    if (!empty($data)) {
        echo json_encode([
            'success' => true,
            'message' => "Thành công",
            'result' => $data,
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => "Không có sản phẩm",
            'result' => [],
        ]);
    }
} else {
    // Thông báo lỗi SQL
    echo json_encode([
        'success' => false,
        'message' => "Lỗi truy vấn SQL: " . mysqli_error($conn),
    ]);
}

mysqli_close($conn);
?>
