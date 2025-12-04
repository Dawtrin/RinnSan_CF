<?php

namespace Rinnsan\RinnSanWeb\Services;

use Rinnsan\RinnSanWeb\Models\User;
use Rinnsan\RinnSanWeb\Helpers\CacheHelper;
use Rinnsan\RinnSanWeb\Helpers\Logger;

class AuthService extends Service
{
    public function login($identifier, $password)
    {
        $user = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? User::findByEmail($identifier) : User::findByUsername($identifier);
        if (!$user) {
            return null;
        }
        if (!password_verify($password, $user['password'])) {
            return null;
        }
        if (!($user['is_active'] ?? 1)) {
            return null;
        }
        $enable2FA = ($_ENV['AUTH_ENABLE_2FA'] ?? 'false') === 'true';
        if ($enable2FA) {
            $otp = $this->generateOtp($user['id']);
            return ['requires_2fa' => true, 'user_id' => $user['id'], 'otp_sent' => true];
        }
        $token = $this->generateJwt($user);
        return ['token' => $token, 'user' => $this->sanitizeUser($user)];
    }

    public function verifyOtpAndIssueToken($userId, $otp)
    {
        $key = 'otp_' . $userId;
        $cached = CacheHelper::get($key);
        if (!$cached || $cached !== $otp) {
            return null;
        }
        CacheHelper::forget($key);
        $user = User::find($userId);
        if (!$user) {
            return null;
        }
        $token = $this->generateJwt($user);
        return ['token' => $token, 'user' => $this->sanitizeUser($user)];
    }

    public function requestPasswordReset($email)
    {
        $user = User::findByEmail($email);
        if (!$user) {
            return false;
        }
        $token = bin2hex(random_bytes(16));
        $ttl = (int)($_ENV['PASSWORD_RESET_TTL'] ?? 1800);
        CacheHelper::set('password_reset_' . $token, $user['id'], $ttl);
        return ['token' => $token, 'user_id' => $user['id']];
    }

    public function resetPassword($token, $newPassword)
    {
        $key = 'password_reset_' . $token;
        $userId = CacheHelper::get($key);
        if (!$userId) {
            return false;
        }
        CacheHelper::forget($key);
        User::update($userId, ['password' => $newPassword]);
        return true;
    }

    public function verifyToken($token)
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }
        [$h, $p, $s] = $parts;
        $secret = $_ENV['JWT_SECRET'] ?? 'secret';
        $expected = $this->b64url(hash_hmac('sha256', $h . '.' . $p, $secret, true));
        if (!hash_equals($expected, $s)) {
            return null;
        }
        $payload = json_decode($this->b64urlDecode($p), true);
        if (!$payload) {
            return null;
        }
        if (isset($payload['exp']) && time() >= $payload['exp']) {
            return null;
        }
        return $payload;
    }

    private function generateJwt($user)
    {
        $header = ['alg' => 'HS256', 'typ' => 'JWT'];
        $ttl = (int)($_ENV['JWT_TTL'] ?? 3600);
        $payload = [
            'sub' => $user['id'],
            'email' => $user['email'] ?? null,
            'role_id' => $user['role_id'] ?? null,
            'iat' => time(),
            'exp' => time() + $ttl
        ];
        $h = $this->b64url(json_encode($header));
        $p = $this->b64url(json_encode($payload));
        $secret = $_ENV['JWT_SECRET'] ?? 'secret';
        $s = $this->b64url(hash_hmac('sha256', $h . '.' . $p, $secret, true));
        return $h . '.' . $p . '.' . $s;
    }

    private function b64url($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function b64urlDecode($data)
    {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        return base64_decode(strtr($data, '-_', '+/'));
    }

    private function generateOtp($userId)
    {
        $otp = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        CacheHelper::set('otp_' . $userId, $otp, (int)($_ENV['OTP_TTL'] ?? 300));
        return $otp;
    }

    private function sanitizeUser($user)
    {
        unset($user['password']);
        return $user;
    }
}

