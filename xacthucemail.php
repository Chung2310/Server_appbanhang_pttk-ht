<?php
header('Content-Type: application/json');

function jValidateEmailUsingSMTP($sToEmail, $sFromDomain = "gmail.com", $sFromEmail = "email@gmail.com", $bIsDebug = false) {
    $bIsValid = true;
    $aEmailParts = explode("@", $sToEmail);
    getmxrr($aEmailParts[1], $aMatches);

    if (sizeof($aMatches) == 0) {
        return false;
    }

    foreach ($aMatches as $oValue) {
        if ($bIsValid && !isset($sResponseCode)) {
            $oConnection = @fsockopen($oValue, 25, $errno, $errstr, 30);
            $oResponse = @fgets($oConnection);

            if (!$oConnection) {
                $bIsValid = false;
            } else {
                $bIsValid = true;
            }

            if (!$bIsValid) {
                return false;
            }

            fputs($oConnection, "HELO $sFromDomain\r\n");
            $oResponse = fgets($oConnection);
            fputs($oConnection, "MAIL FROM: <$sFromEmail>\r\n");
            $oResponse = fgets($oConnection);
            fputs($oConnection, "RCPT TO: <$sToEmail>\r\n");
            $oResponse = fgets($oConnection);
            $sResponseCode = substr($oResponse, 0, 3);

            fputs($oConnection,"QUIT\r\n");
            $oResponse = fgets($oConnection);
            @fclose($oConnection);

            if (substr($sResponseCode, 0, 1) == "5") {
                $bIsValid = false;
            }
        }
    }

    return $bIsValid;
}

$email = $_GET['email'];
$validDomains = [
    'com', 'net', 'org', 'edu', 'info', 'io', 'biz', 'co', 'us', 
    'gov', 'mil', 'me', 'tv', 'xyz', 'app', 'online', 'store', 
    'name', 'pro', 'aero', 'asia', 'cat', 'jobs', 'mobi', 
    'museum', 'tel', 'travel', 'int', 'coop', 'local', 'design', 
    'money', 'site', 'world', 'party', 'technology', 'events',
    'photo', 'news', 'shop', 'email', 'top', 'click', 'live',
    'love', 'email', 'vip', 'today', 'community','tnut','vn'
];

$emailParts = explode('@', $email);
$domainParts = explode('.', $emailParts[1]);
$lastDomain = end($domainParts);

if (!in_array($lastDomain, $validDomains)) {
    echo json_encode(['success' => false, 'message' => 'Đuôi email không hợp lệ.']);
    exit();
}

$bIsEmailValid = jValidateEmailUsingSMTP($email, "gmail.com", "email@gmail.com");

if ($bIsEmailValid) {
    echo json_encode(['success' => true, 'message' => 'Email hợp lệ!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Email không tồn tại!']);
}
?>