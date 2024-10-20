<?php
include "connect.php";

if(isset($_GET['token'])) {
  $token = $_GET['token'];

  // Sử dụng prepared statements để kiểm tra token
  $stmt = $conn->prepare("SELECT email, token_expiry FROM user WHERE reset_token = ?");
  $stmt->bind_param("s", $token);
  $stmt->execute();
  $result = $stmt->get_result();

  // Kiểm tra nếu token tồn tại và còn thời hạn
  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $expiry_time = $user['token_expiry'];

    // Kiểm tra nếu token đã hết hạn
    if (strtotime($expiry_time) > time()) {
      $email = $user['email'];
      ?>
      <form method="post" action="submit_new.php">
        <input type="hidden" name="email" value="<?php echo $email; ?>">
        <p>Nhập mật khẩu mới</p>
        <input type="password" name="password" required>
        <input type="submit" name="submit_password" value="Đặt lại mật khẩu">
      </form>
      <?php
    } else {
      echo "Token đã hết hạn. Vui lòng yêu cầu đặt lại mật khẩu mới.";
    }
  } else {
    echo "Liên kết không hợp lệ.";
  }
} else {
  echo "Không có token nào được cung cấp.";
}
?>
