<?php
include "connect.php";

if(isset($_POST['email']) && isset($_POST['password'])) {
  $email = $_POST['email'];
  $new_password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Mã hóa mật khẩu mới

  // Cập nhật mật khẩu mới trong cơ sở dữ liệu
  $stmt = $conn->prepare("UPDATE user SET pass = ?, reset_token = NULL, token_expiry = NULL WHERE email = ?");
  $stmt->bind_param("ss", $new_password, $email);
  $stmt->execute();

  if($stmt->affected_rows > 0) {
    echo "Mật khẩu đã được cập nhật thành công.";
  } else {
    echo "Đã xảy ra lỗi, vui lòng thử lại.";
  }
}
?>
