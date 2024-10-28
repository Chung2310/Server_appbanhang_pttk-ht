<?php
include "connect.php";  

$target_dir = "images/";

// Lấy ID lớn nhất từ bảng sanphammoi
$query = "SELECT max(id) as id FROM sanphammoi";
$data = mysqli_query($conn, $query);
$result = array();
while($row = mysqli_fetch_assoc($data)){
    $result[] = $row;  // Thêm từng hàng vào mảng
}

// Lấy ID lớn nhất hoặc đặt về 1 nếu chưa có sản phẩm
if (empty($result[0]['id'])) {
    $name = 1;
} else {
    $name = ++$result[0]['id'];
}
$filename = $name . ".jpg";  // Đặt tên tệp theo ID sản phẩm
$target_file_name = $target_dir . $filename;  // Đường dẫn đến tệp
$response = array();

// Kiểm tra nếu tệp được upload
if (isset($_FILES['file'])) {
    // Di chuyển tệp đến thư mục đích
    if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file_name)) {
        $arr = [
            'success' => true,
            'message' => "Thành Công",
            'name' => $filename  // Thêm tên tệp vào mảng kết quả
        ];
    } else {
        $arr = [
            'success' => false,
            'message' => "Không Thành Công"
        ];
    }
} else {
    $arr = [
        'success' => false,
        'message' => "Lỗi: Không có tệp được tải lên"
    ];
}

// Trả về kết quả dưới dạng JSON
echo json_encode($arr);
?>
