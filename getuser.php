<?php
include "connect.php";

// Kiểm tra nếu `id` được gửi từ ứng dụng
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    
    // Sử dụng truy vấn để lấy các thông tin cụ thể theo `id`
    $query = "SELECT id, email, pass, name, sdt, chucvu, avatar FROM `user` WHERE id = '$id'";
    $data = mysqli_query($conn, $query);
    $result = array();
    
    // Lưu kết quả vào mảng `$result`
    if ($row = mysqli_fetch_assoc($data)) {
        $result[] = $row;
    }

    // Kiểm tra nếu có kết quả trả về
    if (!empty($result)) {
        $arr = [
            'success' => true,
            'message' => "thanh cong",
            'result' => $result
        ];
    } else {
        $arr = [
            'success' => false,
            'message' => "khong tim thay nguoi dung voi id = $id",
            'result' => $result
        ];
    }
} else {
    $arr = [
        'success' => false,
        'message' => "id khong duoc gui",
        'result' => []
    ];
}

print_r(json_encode($arr));

?>
