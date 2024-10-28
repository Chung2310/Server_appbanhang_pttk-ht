<?php
    include "connect.php";

    // Lấy dữ liệu từ GET
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    $email = isset($_GET['email']) ? $_GET['email'] : null;

    // Kiểm tra dữ liệu không null
    if (empty($id) || empty($email)) {
        $arr = [
            'success' => false,
            'message' => 'Thiếu thông tin cần thiết'
        ];
    } else {
        // Tránh SQL injection
        $id = mysqli_real_escape_string($conn, $id);
        $email = mysqli_real_escape_string($conn, $email);

        // Kiểm tra email đã tồn tại chưa
        $check_query = "SELECT * FROM `user` WHERE `email` = '$email' AND `id` != '$id'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            // Email đã tồn tại
            $arr = [
                'success' => false,
                'message' => 'Email đã tồn tại'
            ];
        } else {
            // Câu lệnh UPDATE
            $query = "UPDATE `user` 
                      SET `email`='$email'
                      WHERE `id`='$id'";

            $result = mysqli_query($conn, $query);

            // Kiểm tra kết quả của câu lệnh UPDATE
            if ($result) {
                $arr = [
                    'success' => true,
                    'message' => 'Cập nhật email thành công'
                ];
            } else {
                $arr = [
                    'success' => false,
                    'message' => 'Không thể cập nhật email'
                ];
            }
        }
    }

    // Trả về JSON
    echo json_encode($arr);
?>
