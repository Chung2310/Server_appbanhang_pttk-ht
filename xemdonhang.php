<?php
	include "connect.php";
	$iduser = $_GET['iduser'];

	// Kiểm tra kết nối cơ sở dữ liệu
	if (!$conn) {
		echo json_encode(['success' => false, 'message' => 'Kết nối cơ sở dữ liệu thất bại', 'result' => []]);
		exit();
	}

	// Truy vấn đơn hàng của người dùng
	$query = "SELECT * FROM donhang WHERE iduser = ? ORDER BY id DESC";
	$stmt = $conn->prepare($query);

	if (!$stmt) {
		echo json_encode(['success' => false, 'message' => 'Truy vấn đơn hàng thất bại', 'result' => []]);
		exit();
	}

	$stmt->bind_param("i", $iduser); 
	$stmt->execute();
	$data = $stmt->get_result();

	// Kiểm tra nếu không có đơn hàng nào
	if ($data->num_rows == 0) {
		echo json_encode(['success' => false, 'message' => 'Không có đơn hàng', 'result' => []]);
		$stmt->close();
		$conn->close();
		exit();
	}

	// Chuẩn bị mảng kết quả
	$result = array();
	while($row = $data->fetch_assoc()) {
		// Truy vấn chi tiết đơn hàng
		$queryDetail = "SELECT chitietdonhang.*, sanphammoi.* 
		                FROM chitietdonhang 
		                INNER JOIN sanphammoi ON chitietdonhang.idsp = sanphammoi.id 
		                WHERE chitietdonhang.iddonhang = ?";
		$stmtDetail = $conn->prepare($queryDetail);

		if (!$stmtDetail) {
			echo json_encode(['success' => false, 'message' => 'Truy vấn chi tiết đơn hàng thất bại', 'result' => []]);
			$stmt->close();
			$conn->close();
			exit();
		}

		$stmtDetail->bind_param("i", $row['id']);
		$stmtDetail->execute();
		$dataDetail = $stmtDetail->get_result();

		// Lưu chi tiết đơn hàng vào mảng 'item'
		$row['item'] = $dataDetail->fetch_all(MYSQLI_ASSOC);
		$result[] = $row;

		// Đóng statement của truy vấn chi tiết
		$stmtDetail->close();
	}

	// Đóng statement chính
	$stmt->close();

	// Trả về JSON
	$response = [
		'success' => true,
		'message' => 'Thành công',
		'result' => $result
	];

	echo json_encode($response);

	// Đóng kết nối cơ sở dữ liệu
	$conn->close();
?>
