<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Email Helper
 * 
 * Kirim email menggunakan PHPMailer dengan konfigurasi dari .env
 * 
 * Penggunaan:
 *   $this->load->helper('email');
 * 
 *   // Kirim ke satu penerima
 *   $result = send_email('tujuan@mail.com', 'Nama Tujuan', 'Subject', '<p>Body HTML</p>');
 * 
 *   // Kirim ke beberapa penerima
 *   $to = [
 *     ['email' => 'a@mail.com', 'name' => 'User A'],
 *     ['email' => 'b@mail.com', 'name' => 'User B'],
 *   ];
 *   $result = send_email($to, null, 'Subject', '<p>Body HTML</p>');
 * 
 *   // Dengan attachment
 *   $result = send_email('a@mail.com', 'User A', 'Subject', '<p>Body</p>', [
 *     '/path/to/file.pdf',
 *     '/path/to/file2.xlsx',
 *   ]);
 * 
 * Return: ['status' => true/false, 'message' => '...']
 */

if (!function_exists('send_email')) {

    /**
     * @param  string|array $to      Email tujuan (string) atau array [['email'=>'','name'=>''], ...]
     * @param  string|null  $toName  Nama tujuan (digunakan jika $to adalah string)
     * @param  string       $subject Subject email
     * @param  string       $body    Body HTML email
     * @param  array        $attachments Array path file attachment (opsional)
     * @param  string|null  $altBody Plain text fallback (opsional)
     * @return array ['status' => bool, 'message' => string]
     */
    function send_email($to, $toName = null, $subject = '', $body = '', $attachments = [], $altBody = null)
    {
        require_once APPPATH . 'libraries/PHPMailer/class.phpmailer.php';

        // Baca konfigurasi dari .env
        $host       = env('MAIL_HOST',       'localhost');
        $username   = env('MAIL_USERNAME',   '');
        $password   = env('MAIL_PASSWORD',   '');
        $port       = (int) env('MAIL_PORT', 587);
        $encryption = env('MAIL_ENCRYPTION', 'tls');  // tls / ssl / ''
        $from_email = env('MAIL_FROM_EMAIL', $username);
        $from_name  = env('MAIL_FROM_NAME',  env('APP_NAME', 'No Reply'));

        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host        = $host;
            $mail->SMTPAuth    = true;
            $mail->Username    = $username;
            $mail->Password    = $password;
            $mail->SMTPSecure  = $encryption;
            $mail->Port        = $port;
            $mail->CharSet     = 'UTF-8';
            $mail->SMTPKeepAlive = true;

            // From
            $mail->setFrom($from_email, $from_name);

            // To — support single string atau array of recipients
            if (is_array($to)) {
                foreach ($to as $recipient) {
                    $mail->addAddress($recipient['email'], isset($recipient['name']) ? $recipient['name'] : '');
                }
            } else {
                $mail->addAddress($to, $toName ?? '');
            }

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = $altBody ?? strip_tags($body);

            // Attachments
            if (!empty($attachments)) {
                foreach ($attachments as $file) {
                    if (file_exists($file)) {
                        $mail->addAttachment($file);
                    }
                }
            }

            $mail->send();
            return ['status' => true, 'message' => 'Email berhasil dikirim.'];

        } catch (Exception $e) {
            return ['status' => false, 'message' => $mail->ErrorInfo];
        }
    }
}
