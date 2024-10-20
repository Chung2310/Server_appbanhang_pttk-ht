<?php
    include "connect.php";

    // Lấy dữ liệu từ GET
    $id = isset($_GET['id']) ? $_GET['id'] : null;

    // Kiểm tra dữ liệu không null
    if (empty($id)) {
        $arr = [
            'success' => false,
            'message' => 'Thiếu thông tin cần thiết'
        ];
    } else {
        // Tránh SQL injection
        $id = mysqli_real_escape_string($conn, $id);

        // Câu lệnh DELETE
        $query = "DELETE FROM `user` WHERE `id` = '$id'";

        $result = mysqli_query($conn, $query);

        // Kiểm tra kết quả của câu lệnh DELETE
        if ($result) {
            $arr = [
                'success' => true,
                'message' => 'Xóa người dùng thành công'
            ];
        } else {
            $arr = [
                'success' => false,
                'message' => 'Không thể xóa người dùng'
            ];
        }
    }

    // Trả về JSON
    echo json_encode($arr);
?>
