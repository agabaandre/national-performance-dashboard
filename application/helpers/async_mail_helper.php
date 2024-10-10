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
                $mailer->SMTPDebug = 0; // Set to 0 for production to avoid verbose output
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
                $mailer->isHTML(true); // Ensure the email is sent as HTML, remove if not needed

                // Send the email asynchronously
                $loop->addTimer(0.0001, function () use ($mailer, $resolve, $reject, $id, $ci) {
                    try {
                        if ($mailer->send()) {
                            // Update the status in the database (log success)
                            $ci->db->set('status', 1)->where('id', $id)->update('email_notifications');
                            $resolve('Email sent successfully');
                        } else {
                            // Update the status in the database (log failure)
                            $ci->db->set('status', -1)->where('id', $id)->update('email_notifications');
                            $reject('Email sending failed: ' . $mailer->ErrorInfo);
                        }
                    } catch (Exception $e) {
                        // Log the failure and update the status in the database
                        $ci->db->set('status', -1)->where('id', $id)->update('email_notifications');
                        $reject('Email sending failed: ' . $e->getMessage());
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


    function logEmailStatus($status, $id)
    {
        try {
         
            $ci = &get_instance();
            
            $data = [
            
                'status' => $status,
               
            ];
            //dd($data);
            $ci->db->where('id', $id);
            $ci->db->update('email_notifications', $data);
        } catch (Exception $e) {
            
        }
    }


}
