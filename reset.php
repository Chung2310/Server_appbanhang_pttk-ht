<?php
include "connect.php";

$email = $_GET['email'];
$query = "SELECT * FROM `user` WHERE `email` = '".$email."'"; // Sửa dấu nháy đơn

$data = mysqli_query($conn, $query);
$result = array();

while ($row = mysqli_fetch_assoc($data)) {
    $result[] = $row;
}

$response = array();

if (empty($result)) {
    $response = [
        'success' => false,
        'message' => "Email không chính xác",
        'result' => $result
    ];
} else {
    $emailHash = md5($row['email']);
    $passHash = md5($row['password']);

    $link = "<a href='www.samplewebsite.com/reset.php?key=".$emailHash."&reset=".$passHash."'>Click To Reset password</a>";
    
    require_once('phpmail/PHPMailerAutoload.php');
    $mail = new PHPMailer();
    $mail->CharSet = "utf-8";
    $mail->IsSMTP();
    // enable SMTP authentication
    $mail->SMTPAuth = true;                  
    // GMAIL username
    $mail->Username = "chunga00d3@gmail.com";
    // GMAIL password
    $mail->Password = "Abc2003@";
    $mail->SMTPSecure = "ssl";  
    // sets GMAIL as the SMTP server
    $mail->Host = "smtp.gmail.com";
    // set the SMTP port for the GMAIL server
    $mail->Port = "465";
    $mail->From = 'chunga00d3@gmail.com';
    $mail->FromName = 'Chung xanh la';
    $mail->AddAddress('reciever_email_id', 'reciever_name');
    $mail->Subject = 'Reset Password';
    $mail->IsHTML(true);
    $mail->Body = 'Click On This Link to Reset Password '.$link; // Sửa lại để hiển thị liên kết reset

    if ($mail->Send()) {
        $response = [
            'success' => true,
            'message' => "Kiểm tra email của bạn và nhấp vào liên kết đã gửi đến email của bạn.",
            'result' => $result
        ];
    } else {
        $response = [
            'success' => false,
            'message' => "Lỗi gửi mail - >" . $mail->ErrorInfo,
            'result' => $result
        ];
    }
}

// Trả về dữ liệu dưới định dạng JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
