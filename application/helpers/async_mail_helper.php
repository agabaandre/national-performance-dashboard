<?php 
use React\EventLoop\Loop;
use React\Promise\Promise;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!function_exists('send_email_async')) {
function send_email_async($to, $subject, $message, $id)
{
return new Promise(function ($resolve, $reject) use ($to, $subject, $message, $id) {
try {
$ci = &get_instance();
$settings = $ci->db->query('SELECT * FROM setting')->row();

// Create a new event loop
$loop = Loop::get();

// Set up the mailer
$mailer = new PHPMailer(true);
$mailer->isSMTP();
$mailer->SMTPDebug = 0; // Set to 0 for production
$mailer->Host = $settings->mail_host;
$mailer->SMTPAuth = true;
$mailer->Username = $settings->mail_username;
$mailer->Password = $settings->password;
$mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use TLS encryption
$mailer->Port = $settings->mail_smtp_port;

// Set email details
$mailer->setFrom($settings->mail_username, $settings->title);
$mailer->addAddress(trim($to));
$mailer->Subject = $subject;
$mailer->Body = $message;
$mailer->isHTML(true); // Ensure the email is sent as HTML

// Add asynchronous timer for sending the email
$loop->addTimer(0.0001, function () use ($mailer, $resolve, $reject, $id, $ci) {
try {
if ($mailer->send()) {
// Log success in the database
$ci->db->set('status', 1)->where('id', $id)->update('email_notifications');
$resolve('Email sent successfully');
} else {
// Log failure in the database with a detailed error message
$errorInfo = $mailer->ErrorInfo;
$ci->db->set('status', -1)->where('id', $id)->update('email_notifications');
$reject('Email sending failed: ' . $errorInfo);
}
} catch (Exception $e) {
// Log the exception message and update status
$errorMessage = $e->getMessage();
$ci->db->set('status', -1)->where('id', $id)->update('email_notifications');
log_message('error', 'Email sending failed: ' . $errorMessage);
$reject('Email sending failed: ' . $errorMessage);
}
});

$loop->run();
} catch (Exception $e) {
// Handle any exceptions outside of the event loop
$reject('Email sending failed: ' . $e->getMessage());
}
});
}
}