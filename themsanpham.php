<?php
    include "connect.php";

    // Lấy dữ liệu từ GET
    $tensp = isset($_GET['tensp']) ? $_GET['tensp'] : null;
    $gia = isset($_GET['gia']) ? $_GET['gia'] : null;
    $hinhanh = isset($_GET['hinhanh']) ? $_GET['hinhanh'] : null;
    $mota = isset($_GET['mota']) ? $_GET['mota'] : null;
    $loai = isset($_GET['loai']) ? $_GET['loai'] : null;

    // Kiểm tra dữ liệu không null
    if (empty($tensp) || empty($gia) || empty($hinhanh) || empty($mota) || empty($loai)) {
        $arr = [
            'success' => false,
            'message' => 'Thiếu thông tin cần thiết'
        ];
    } else {
        // Tránh SQL injection
        $tensp = mysqli_real_escape_string($conn, $tensp);
        $gia = mysqli_real_escape_string($conn, $gia);
        $hinhanh = mysqli_real_escape_string($conn, $hinhanh);
        $mota = mysqli_real_escape_string($conn, $mota);
        $loai = mysqli_real_escape_string($conn, $loai);

        // Câu lệnh INSERT
        $query = "INSERT INTO `sanphammoi` (`tensp`, `giasp`, `hinhanh`, `mota`, `loai`) 
                  VALUES ('$tensp', '$gia', '$hinhanh', '$mota', '$loai')";

        $result = mysqli_query($conn, $query);

        // Kiểm tra kết quả của câu lệnh INSERT
        if ($result) {
            $arr = [
                'success' => true,
                'message' => 'Thêm sản phẩm thành công'
            ];
        } else {
            $arr = [
                'success' => false,
                'message' => 'Không thể thêm sản phẩm'
            ];
        }
    }

    // Trả về JSON
    echo json_encode($arr);
?>