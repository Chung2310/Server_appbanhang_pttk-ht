<?php
include "connect.php";

$email = isset($_GET['email']) ? $_GET['email'] : null;
$pass = isset($_GET['pass']) ? $_GET['pass'] : null;

// Kiểm tra nếu các giá trị bắt buộc không null
if ($email && $pass) {
    // Kiểm tra kết nối cơ sở dữ liệu
    if (!$conn) {
        $arr = [
            'success' => false,
            'message' => "Không thể kết nối tới cơ sở dữ liệu",
        ];
        echo json_encode($arr);
        exit;
    }

    // Kiểm tra email tồn tại trong cơ sở dữ liệu
    $query = "SELECT * FROM `user` WHERE `email` = ?";
    $stmt = mysqli_prepare($conn, $query);
    
    if (!$stmt) {
        // In ra lỗi khi không chuẩn bị được câu truy vấn
        $arr = [
            'success' => false,
            'message' => "Lỗi khi chuẩn bị truy vấn: " . mysqli_error($conn),
        ];
        echo json_encode($arr);
        exit;
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    
    $result = mysqli_stmt_get_result($stmt);
    
    if (!$result) {
        // In ra lỗi khi thực thi câu truy vấn
        $arr = [
            'success' => false,
            'message' => "Lỗi khi thực thi truy vấn: " . mysqli_error($conn),
        ];
        echo json_encode($arr);
        exit;
    }

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Kiểm tra mật khẩu
        if (password_verify($pass, $user['pass'])) {
            // Đăng nhập thành công
            $arr = [
                'success' => true,
                'message' => "Đăng nhập thành công",
                'result' => [
                    'id' => $user['id'],  
                    'email' => $user['email'],
                    'name' => isset($user['name']) ? $user['name'] : 'Unknown',
                    'sdt' => isset($user['sdt']) ? $user['sdt'] : 'Chưa cập nhật', // Số điện thoại
                    'chucvu' => isset($user['chucvu']) ? $user['chucvu'] : 'Không xác định' // Chức vụ
                ]
            ];
        } else {
            // Mật khẩu không đúng
            $arr = [
                'success' => false,
                'message' => "Mật khẩu không đúng",
            ];
        }
    } else {
        // Email không tồn tại
        $arr = [
            'success' => false,
            'message' => "User không tồn tại",
        ];
    }
} else {
    // Trả về thông báo nếu dữ liệu GET thiếu
    $arr = [
        'success' => false,
        'message' => "Thiếu thông tin yêu cầu",
    ];
}

// Trả về phản hồi dưới dạng JSON
echo json_encode($arr);
?>
