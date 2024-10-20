<?php
	include "connect.php";
	$search = $_GET['search'];

	if(empty($search)){
		$arr = [
			'success' => false,
			'message' => 'Không thành công'
		];
	} else {
		// Tránh lỗi SQL injection
		$search = mysqli_real_escape_string($conn, $search);
		$query = "SELECT * FROM sanphammoi WHERE tensp LIKE '%$search%'";

		$data = mysqli_query($conn, $query);
		$result = array();
		while($row = mysqli_fetch_assoc($data)){
			$result[] = $row;
		}

		if(!empty($result)){
			$arr = [
				'success' => true,
				'message' => 'Thành công',
				'result' => $result
			];
		} else {
			$arr = [
				'success' => false,
				'message' => 'Không thành công',
				'result' => $result
			];
		}
	}
	
	// Trả về JSON
	echo json_encode($arr);
?>
