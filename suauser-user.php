<?php
    include "connect.php";

    // Lấy dữ liệu từ GET
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    $name = isset($_GET['name']) ? $_GET['name'] : null;
    $sdt = isset($_GET['sdt']) ? $_GET['sdt'] : null;

    // Kiểm tra dữ liệu không null
    if (empty($id) || empty($name) || empty($sdt)) {
        $arr = [
            'success' => false,
            'message' => 'Thiếu thông tin cần thiết'
        ];
    } else {
        // Tránh SQL injection
        $id = mysqli_real_escape_string($conn, $id);
        $name = mysqli_real_escape_string($conn, $name);
        $sdt = mysqli_real_escape_string($conn, $sdt);

        // Câu lệnh UPDATE
        $query = "UPDATE `user` 
                  SET `name`='$name', `sdt`='$sdt'
                  WHERE `id`='$id'";

        $result = mysqli_query($conn, $query);

        // Kiểm tra kết quả của câu lệnh UPDATE
        if ($result) {
            $arr = [
                'success' => true,
                'message' => 'Cập nhật người dùng thành công'
            ];
        } else {
            $arr = [
                'success' => false,
                'message' => 'Không thể cập nhật người dùng'
            ];
        }
    }

    // Trả về JSON
    echo json_encode($arr);
?>
