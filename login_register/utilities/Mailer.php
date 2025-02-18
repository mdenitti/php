<?php
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dompdf\Dompdf;
use Dompdf\Options;

class Mailer {
    private $mail;
    private $logo_path;

    public function __construct() {
        $this->mail = new PHPMailer(true);
        $this->logo_path = __DIR__ . '/../assets/images/logosmall.png';
        
        // Server settings with Mailtrap configuration
        $this->mail->isSMTP();
        $this->mail->Host = 'sandbox.smtp.mailtrap.io';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = '10eb2b0c19851d';
        $this->mail->Password = '5999c460149f8a';
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port = 587;

        // Enable debug output
        $this->mail->SMTPDebug = 2; // 2 = client and server messages

        $this->mail->Debugoutput = function($str, $level) {
            error_log("PHPMailer Debug: $str");
            // send to custom log file
            file_put_contents('phpmailer.log', "$str\n", FILE_APPEND);
        };
    }

    public function sendWelcomeEmail($user) {
        try {
            // Email settings
            $this->mail->setFrom('no-reply@usermanagement.com', 'User Management System');
            $this->mail->addAddress($user['email'], $user['name']);
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Welcome to Our SyntraPXL System!';

            // Create welcome PDF
            $pdf = $this->generateWelcomePDF($user);
            if (!$pdf) {
                throw new Exception("Failed to generate welcome PDF");
            }
            $this->mail->addStringAttachment($pdf, 'welcome.pdf');

            // Add logo as embedded image
            if (file_exists($this->logo_path)) {
                $logo_data = file_get_contents($this->logo_path);
                $logo_base64 = base64_encode($logo_data);
            } else {
                error_log("Logo file not found at: " . $this->logo_path);
                $logo_base64 = ''; // Use empty string if logo not found
            }

            // Email body
            $this->mail->Body = $this->getWelcomeEmailTemplate($user, $logo_base64);
            $this->mail->AltBody = 'Welcome to our system, ' . $user['name'] . '!';

            $result = $this->mail->send();
            error_log("Email sent successfully to: " . $user['email']);
            return $result;
        } catch (Exception $e) {
            error_log("Email Error: " . $e->getMessage());
            error_log("Mailer Error: " . $this->mail->ErrorInfo);
            return false;
        }
    }

    private function generateWelcomePDF($user) {
        $dompdf = new Dompdf();

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Helvetica, sans-serif; }
                .welcome { color: #2c3e50; }
                .info { color: #7f8c8d; }
            </style>
        </head>
        <body>
            <h1 class="welcome">Welcome to Our SyntraPXL System!</h1>
            <p class="info">Dear ' . htmlspecialchars($user['name']) . ',</p>
            <p>Thank you for joining our platform. This document serves as your registration confirmation.</p>
            <p>Registration Date: ' . date('Y-m-d H:i:s') . '</p>
        </body>
        </html>';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return $dompdf->output();
    }

    private function getWelcomeEmailTemplate($user, $logo_base64) {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; }
                .header { text-align: left; margin-bottom: 20px; margin-top: 10px; }
                .content { margin: 20px; }
                .footer { text-align: center; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="header">
                <img src="data:image/jpeg;base64,' . $logo_base64 . '" alt="Logo" style="max-width: 200px;">
            </div>
            <div class="content">
                <h2>Welcome, ' . htmlspecialchars($user['name']) . '!</h2>
                <p>Thank you for registering with our system.</p>
                <p>We\'ve attached a welcome document for your records.</p>
                <p>Your account details:</p>
                <ul>
                    <li>Email: ' . htmlspecialchars($user['email']) . '</li>
                    <li>Registration Date: ' . $user['date'] . '</li>
                </ul>
            </div>
            <div class="footer">
                <p>This is an automated message, please do not reply.</p>
            </div>
        </body>
        </html>';
    }
}