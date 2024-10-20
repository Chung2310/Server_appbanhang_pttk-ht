<?php
    include "connect.php";

    // Lấy dữ liệu từ GET
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    $email = isset($_GET['email']) ? $_GET['email'] : null;
    $pass = isset($_GET['pass']) ? $_GET['pass'] : null; 
    $name = isset($_GET['name']) ? $_GET['name'] : null;
    $sdt = isset($_GET['sdt']) ? $_GET['sdt'] : null;
    $chucvu = isset($_GET['chucvu']) ? $_GET['chucvu'] : null;

    // Kiểm tra dữ liệu không null
    if (empty($id) || empty($email) || empty($name) || empty($sdt) || empty($chucvu) || empty($pass)) {
        $arr = [
            'success' => false,
            'message' => 'Thiếu thông tin cần thiết'
        ];
    } else {
        // Tránh SQL injection
        $id = mysqli_real_escape_string($conn, $id);
        $email = mysqli_real_escape_string($conn, $email);
        $name = mysqli_real_escape_string($conn, $name);
        $sdt = mysqli_real_escape_string($conn, $sdt);
        $chucvu = mysqli_real_escape_string($conn, $chucvu);
        $hashed_pass = password_hash($pass, PASSWORD_DEFAULT); // Mã hóa mật khẩu

        // Câu lệnh UPDATE
        $query = "UPDATE `user` 
                  SET `email`='$email', `name`='$name', `sdt`='$sdt', `chucvu`='$chucvu', `pass`='$hashed_pass' 
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
