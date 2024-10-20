<?php
include "connect.php";

// Kiểm tra và gán giá trị từ GET thay vì POST
$sdt = isset($_GET['sdt']) ? $_GET['sdt'] : '';
$email = isset($_GET['email']) ? $_GET['email'] : '';
$tongtien = isset($_GET['tongtien']) ? $_GET['tongtien'] : 0;
$iduser = isset($_GET['iduser']) ? $_GET['iduser'] : 0;
$diachi = isset($_GET['diachi']) ? $_GET['diachi'] : '';
$soluong = isset($_GET['soluong']) ? $_GET['soluong'] : 0;
$chitiet = isset($_GET['chitiet']) ? $_GET['chitiet'] : '[]';


// Kiểm tra kết nối cơ sở dữ liệu
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Bắt đầu transaction để xử lý đơn hàng và chi tiết đơn hàng
mysqli_begin_transaction($conn);

try {
    // Thêm thông tin đơn hàng
    $query = 'INSERT INTO donhang (iduser, diachi, sodienthoai, email, soluong, tongtien) 
              VALUES (?, ?, ?, ?, ?, ?)';
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Lỗi chuẩn bị câu lệnh SQL: " . $conn->error);
    }
    $stmt->bind_param("isssii", $iduser, $diachi, $sdt, $email, $soluong, $tongtien);
    $stmt->execute();
    
    // Lấy id của đơn hàng vừa được thêm
    $iddonhang = $conn->insert_id;
    
    if ($iddonhang > 0) {
        // Đọc JSON chi tiết sản phẩm
        $chitiet = json_decode($chitiet, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Lỗi JSON: " . json_last_error_msg());
        }

        // Thêm chi tiết đơn hàng
        $truyvan = 'INSERT INTO chitietdonhang (iddonhang, idsp, soluong, gia) 
                    VALUES (?, ?, ?, ?)';
        $stmt_detail = $conn->prepare($truyvan);
        if (!$stmt_detail) {
            throw new Exception("Lỗi chuẩn bị câu lệnh SQL cho chi tiết đơn hàng: " . $conn->error);
        }
        
        foreach ($chitiet as $value) {
            if (!isset($value["idsp"], $value["soluong"], $value["giasp"])) {
                throw new Exception("Thiếu thông tin chi tiết sản phẩm trong JSON.");
            }
            $stmt_detail->bind_param("iiii", $iddonhang, $value["idsp"], $value["soluong"], $value["giasp"]);
            $stmt_detail->execute();
            
            if ($stmt_detail->affected_rows == 0) {
                throw new Exception("Không thể thêm chi tiết đơn hàng cho sản phẩm ID: " . $value["idsp"]);
            }
        }
        
        // Nếu tất cả đều thành công, commit transaction
        mysqli_commit($conn);
        
        $arr = [
            'success' => true,
            'message' => "Thành công",
            'iddonhang' => $iddonhang
        ];
    } else {
        throw new Exception("Không thể thêm đơn hàng.");
    }
    
} catch (Exception $e) {
    // Nếu có lỗi xảy ra, rollback transaction
    mysqli_rollback($conn);
    $arr = [
        'success' => false,
        'message' => "Lỗi: " . $e->getMessage()
    ];
}

// Đóng statement
if (isset($stmt)) $stmt->close();
if (isset($stmt_detail)) $stmt_detail->close();

// Đóng kết nối
$conn->close();

// In kết quả dưới dạng JSON
echo json_encode($arr);
?>
