<?php
include "connect.php";

$id = isset($_GET['id']) ? $_GET['id'] : null;
$old_pass = isset($_GET['old_pass']) ? $_GET['old_pass'] : null;
$new_pass = isset($_GET['new_pass']) ? $_GET['new_pass'] : null;

// Kiểm tra nếu các giá trị bắt buộc không null
if ($id && $old_pass && $new_pass) {
    // Kiểm tra kết nối cơ sở dữ liệu
    if (!$conn) {
        $arr = [
            'success' => false,
            'message' => "Không thể kết nối tới cơ sở dữ liệu",
        ];
        echo json_encode($arr);
        exit;
    }

    // Kiểm tra id tồn tại trong cơ sở dữ liệu
    $query = "SELECT * FROM `user` WHERE `id` = ?";
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

    mysqli_stmt_bind_param($stmt, "s", $id);
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

        // Kiểm tra mật khẩu cũ
        if (password_verify($old_pass, $user['pass'])) {
            // Mã hóa mật khẩu mới
            $hashed_new_pass = password_hash($new_pass, PASSWORD_DEFAULT);

            // Cập nhật mật khẩu mới trong cơ sở dữ liệu
            $update_query = "UPDATE `user` SET `pass` = ? WHERE `id` = ?";
            $update_stmt = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param($update_stmt, "ss", $hashed_new_pass, $id);
            $update_result = mysqli_stmt_execute($update_stmt);

            if ($update_result) {
                $arr = [
                    'success' => true,
                    'message' => "Thay đổi mật khẩu thành công"
                ];
            } else {
                $arr = [
                    'success' => false,
                    'message' => "Không thể cập nhật mật khẩu"
                ];
            }
        } else {
            // Mật khẩu cũ không đúng
            $arr = [
                'success' => false,
                'message' => "Mật khẩu cũ không đúng",
            ];
        }
    } else {
        // ID không tồn tại
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
