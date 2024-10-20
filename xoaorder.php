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

        // Bắt đầu transaction
        mysqli_begin_transaction($conn);

        try {
            // Xóa chi tiết đơn hàng trước
            $queryDetail = "DELETE FROM `chitietdonhang` WHERE `iddonhang` = '$id'";
            $resultDetail = mysqli_query($conn, $queryDetail);

            if (!$resultDetail) {
                throw new Exception('Không thể xóa chi tiết đơn hàng');
            }

            // Xóa đơn hàng
            $queryOrder = "DELETE FROM `donhang` WHERE `id` = '$id'";
            $resultOrder = mysqli_query($conn, $queryOrder);

            if (!$resultOrder) {
                throw new Exception('Không thể xóa đơn hàng');
            }

            // Commit transaction nếu thành công
            mysqli_commit($conn);
            $arr = [
                'success' => true,
                'message' => 'Xóa đơn hàng và chi tiết thành công'
            ];
        } catch (Exception $e) {
            // Rollback transaction nếu có lỗi
            mysqli_rollback($conn);
            $arr = [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    // Trả về JSON
    echo json_encode($arr);
?>
