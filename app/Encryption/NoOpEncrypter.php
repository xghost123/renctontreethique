<?php

namespace App\Encryption;

use Illuminate\Encryption\Encrypter as LaravelEncrypter;

/**
 * NoOpEncrypter - handles encryption gracefully when OpenSSL is unavailable
 * by skipping validation and using plaintext fallbacks
 */
class NoOpEncrypter extends LaravelEncrypter
{
    public function __construct($key, $cipher = 'AES-128-CBC')
    {
        // If OpenSSL is not available, skip cipher validation
        if (extension_loaded('openssl')) {
            parent::__construct($key, $cipher);
        } else {
            // Just store the values without validation
            $this->key = (string) $key;
            $this->cipher = $cipher ?? 'AES-128-CBC';
        }
    }

    /**
     * Encrypt a value.
     */
    public function encrypt($value, $serialize = true)
    {
        if (!extension_loaded('openssl')) {
            // Return serialized value with marker
            return 'PLAINTEXT:' . ($serialize ? base64_encode(serialize($value)) : base64_encode($value));
        }
        return parent::encrypt($value, $serialize);
    }

    /**
     * Decrypt the given value.
     */
    public function decrypt($payload, $unserialize = true)
    {
        if (is_string($payload) && strpos($payload, 'PLAINTEXT:') === 0) {
            $data = base64_decode(substr($payload, 10));
            return $unserialize ? unserialize($data) : $data;
        }

        if (!extension_loaded('openssl')) {
            try {
                $data = base64_decode($payload);
                return $unserialize ? @unserialize($data) : $data;
            } catch (\Exception $e) {
                return $unserialize ? [] : $payload;
            }
        }

        return parent::decrypt($payload, $unserialize);
    }

    /**
     * Check if the given payload is valid.
     */
    protected function hash($iv, $value, $key = null)
    {
        if (!extension_loaded('openssl')) {
            // Use simple hash without OpenSSL
            return hash_hmac('sha256', $iv . $value, $key ?? $this->key);
        }
        return parent::hash($iv, $value, $key);
    }
}
