<?php
use React\EventLoop\Loop;
use React\Promise\Promise;
use PHPMailer\PHPMailer\PHPMailer;

if (!function_exists('send_email_async')) {
    function send_email_async($to, $subject, $message,$id)
    {
        return new Promise(function ($resolve, $reject) use ($to, $subject, $message,$id) {
            try {
                $ci = &get_instance();
                $settings = $ci->db->query('SELECT * FROM setting')->row();

                // Create a new event loop
                $loop = Loop::get();

                // Server settings
                $mailer = new PHPMailer();
                $mailer->isSMTP();
				$mailer->SMTPDebug = 2;
                $mailer->Host = $settings->mail_host;
                $mailer->SMTPAuth = true;
                $mailer->Username = $settings->mail_username;
				$mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;    
                $mailer->Password = $settings->password;
                //$mailer->SMTPSecure = $settings->mail_encryption;
                $mailer->Port = $settings->mail_smtp_port;

                // Set email details
                $mailer->setFrom($settings->mail_username, $settings->title);
                $mailer->addAddress(trim($to));
                //$mail->addAddress('recipient@example.com');

               // dd($mailer);

                $mailer->Subject = $subject;
                $mailer->Body = $message;
             //  dd($mailer);
                // Send the email asynchronously
                $loop->addTimer(0.0001, function () use ($mailer, $resolve, $reject,  $id) {
                    $mailer->send();
                    if ($mailer->send()) {
                        // Log success in the database
                        // logEmailStatus(1,$id);
                        $resolve('Email sent successfully');
                    } else {
                        // Log failure in the database
                        // logEmailStatus(0, $id);
                        $reject('Email sending failed: ' . $mailer->ErrorInfo);
                    }
                });

                $loop->run();
            } catch (Exception $e) {
                // Handle any exceptions here
                $reject('Email sending failed: ' . $e->getMessage());
            }
        });
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
