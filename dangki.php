<?php
include "connect.php";

// Kiểm tra nếu các giá trị POST có tồn tại
$email = isset($_GET['email']) ? $_GET['email'] : null;
$pass = isset($_GET['pass']) ? $_GET['pass'] : null;
$name = isset($_GET['name']) ? $_GET['name'] : null;
$sdt = isset($_GET['sdt']) ? $_GET['sdt'] : null;

// Kiểm tra nếu các giá trị bắt buộc không null
if ($email && $pass && $name && $sdt) {
    // Kiểm tra kết nối cơ sở dữ liệu
    if (!$conn) {
        $arr = [
            'success' => false,
            'message' => "Khong the ket noi co so du lieu",
        ];
        echo json_encode($arr);
        exit;
    }

    // Kiểm tra dữ liệu email đã tồn tại
    $query = "SELECT * FROM `user` WHERE `email` = '$email'";
    $data = mysqli_query($conn, $query);
    $numrow = mysqli_num_rows($data);

    if ($numrow > 0) {
        $arr = [
            'success' => false,
            'message' => "Email đa ton tai"
        ];
    } else {
        // Mã hóa mật khẩu
        $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

        // Sử dụng prepared statement để tránh SQL injection
        $query = "INSERT INTO `user`(`email`, `pass`, `name`, `sdt`, `chucvu`) VALUES (?, ?, ?, ?, 'user')";

        // Chuẩn bị câu lệnh
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt === false) {
            // Xử lý lỗi khi chuẩn bị câu lệnh
            $arr = [
                'success' => false,
                'message' => "Chuan bi cau lenh that bai",
            ];
            echo json_encode($arr);
            exit;
        }

        // Gán các tham số cho câu truy vấn SQL
        mysqli_stmt_bind_param($stmt, "ssss", $email, $hashed_pass, $name, $sdt);

        // Thực thi câu lệnh
        $result = mysqli_stmt_execute($stmt);

        $arr = [];
        if ($result) {
            $arr = [
                'success' => true,
                'message' => "Thanh cong",
            ];
        } else {
            $arr = [
                'success' => false,
                'message' => "Khong thanh cong",
            ];
        }
    }
} else {
    // Trả về thông báo nếu dữ liệu POST thiếu
    $arr = [
        'success' => false,
        'message' => "Thieu thong tin yeu cau",
    ];
}

// Trả về phản hồi dưới dạng JSON
echo json_encode($arr);

?>
