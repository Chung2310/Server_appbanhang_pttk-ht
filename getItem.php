<?php
include "connect.php";

// Truy vấn kết hợp lấy thông tin từ hai bảng chitietdonhang và sanphammoi
$query = "SELECT chitietdonhang.iddonhang AS idsp, sanphammoi.tensp, chitietdonhang.soluong, sanphammoi.hinhanh, sanphammoi.giasp, sanphammoi.mota FROM chitietdonhang INNER JOIN sanphammoi ON chitietdonhang.idsp = sanphammoi.id ORDER BY chitietdonhang.iddonhang DESC;";
$data  = mysqli_query($conn, $query);
$result = array();

// Duyệt qua từng dòng kết quả
while ($row = mysqli_fetch_assoc($data)) {
    $result[] = $row;
}

// Kiểm tra kết quả truy vấn
if (!empty($result)) {
    $arr = [
        'success' => true,
        'message' => "Thành công",
        'result' => $result
    ];
} else {
    $arr = [
        'success' => false,
        'message' => "Không thành công",
        'result' => []
    ];
}

// Trả về JSON
echo json_encode($arr);

?>
