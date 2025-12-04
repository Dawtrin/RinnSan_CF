<?php

namespace Rinnsan\RinnSanWeb\Services;

use Rinnsan\RinnSanWeb\Helpers\Logger;

class EmailService extends Service
{
    public function send($to, $subject, $body, $headers = [])
    {
        $sent = false;
        if (function_exists('mail')) {
            $h = [];
            foreach ($headers as $k => $v) {
                $h[] = $k . ': ' . $v;
            }
            $sent = @mail($to, $subject, $body, implode("\r\n", $h));
        }
        Logger::info('email_send', ['to' => $to, 'subject' => $subject, 'sent' => $sent]);
        return $sent;
    }

    public function sendOtp($to, $otp)
    {
        $subject = 'Xac minh dang nhap';
        $body = 'Ma xac minh: ' . $otp;
        return $this->send($to, $subject, $body);
    }

    public function sendPasswordReset($to, $token)
    {
        $baseUrl = rtrim($_ENV['APP_URL'] ?? 'http://localhost:8000', '/');
        $link = $baseUrl . '/reset-password?token=' . urlencode($token);
        $subject = 'Dat lai mat khau';
        $body = 'Lien ket dat lai mat khau: ' . $link;
        return $this->send($to, $subject, $body);
    }

    public function sendOrderConfirmation($to, $orderCode, $total)
    {
        $subject = 'Xac nhan don hang ' . $orderCode;
        $body = 'Tong thanh toan: ' . number_format($total, 0, '.', ',');
        return $this->send($to, $subject, $body);
    }
}

