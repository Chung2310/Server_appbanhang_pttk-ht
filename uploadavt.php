<?php
include "connect.php";  // Kết nối đến database

$target_dir = "imagesavt/";  // Thư mục lưu trữ hình ảnh

// Lấy 'id' từ request
if (isset($_GET['id'])) {
    $id = $_GET['id'];  // Lấy ID từ request POST
} else {
    $response = array(
        'success' => false,
        'message' => "Lỗi: Thiếu ID"
    );
    echo json_encode($response);
    exit();
}

$filename = $id . ".jpg";  // Đặt tên tệp theo ID người dùng
$target_file_name = $target_dir . $filename;  // Đường dẫn đến tệp
$response = array();

// Kiểm tra nếu tệp được upload
if (isset($_FILES['file'])) {
    // Di chuyển tệp đến thư mục đích
    if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file_name)) {
        // Tệp đã tải lên thành công, tiến hành cập nhật cơ sở dữ liệu
        $sql = "UPDATE user SET avatar = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $filename, $id);

        if ($stmt->execute()) {
            $response = array(
                'success' => true,
                'message' => "Thành Công",
                'name' => $filename  // Thêm tên tệp vào mảng kết quả
            );
        } else {
            $response = array(
                'success' => false,
                'message' => "Lỗi: Không thể cập nhật avatar"
            );
        }

        $stmt->close();
    } else {
        $response = array(
            'success' => false,
            'message' => "Không Thành Công"
        );
    }
} else {
    $response = array(
        'success' => false,
        'message' => "Lỗi: Không có tệp được tải lên"
    );
}

// Trả về kết quả dưới dạng JSON
echo json_encode($response);

$conn->close();
?>
