<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

$mail = new PHPMailer();
$mail->isSMTP();
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'ssl';
$mail->Host = 'smtp.gmail.com';
$mail->Port = '465';
$mail->isHTML();
$mail->Username = 'testela2019@gmail.com';
$mail->Password = 'ELAMASTER';
$mail->SetFrom('no-reply@ELA.com');
$mail->Subject = 'Hello World';
$mail->Body = 'Test email 001';
$mail->AddAddress('alexrousak@gmail.com');

if(!$mail->send()) {
  echo 'Notification was not sent.';
  echo 'Mailer error: ' . $mail->ErrorInfo;
//put error in WS error.log
    error_log('Mailer Error: ' . $mail->ErrorInfo,3, 'emailErrors.log');
} else {
  echo 'Notification was sent successfully.';
  error_log('Notification has been sent to alexrousak@gmail.com',3, 'emailSends.log');

}
/* 
function sendEmail($to, $from, $subject, $message, $header) {
	$flagMailSent = mail($to, $subject, $message, $header);
	if($flagMailSent == true) {
		print 'Email sent successfully.';
	} else {
		print "Email sending failed.";
	}
}
$to = 'abc@somedomain.com';
$from = 'xyz@somedomain.com';
$subject = 'Did this actually work???';
$message = 'If you are seeing this, the test was successful.';
$header = "From:$from\r\n";
$header .= "Reply-To: qrs@somedomain.com";
sendEmail($to, $from, $subject, $message, $header);  */
?>
