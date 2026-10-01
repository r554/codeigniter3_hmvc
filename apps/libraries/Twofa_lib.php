<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Two-Factor Authentication Library
 * Wrapper for Google Authenticator functionality
 */

// Load Composer autoloader
require_once FCPATH . 'vendor/autoload.php';

use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

class Twofa_lib {
    
    private $CI;
    private $ga;
    
    public function __construct() {
        $this->CI =& get_instance();
        $this->ga = new GoogleAuthenticator();
    }
    
    /**
     * Generate a new secret key for TOTP
     * @return string 16-character secret key
     */
    public function generate_secret() {
        return $this->ga->generateSecret();
    }
    
    /**
     * Generate QR code URL for scanning with authenticator apps
     * @param string $username Username to display in authenticator app
     * @param string $secret TOTP secret key
     * @param string $issuer Application name (default: from .env)
     * @return string QR code URL
     */
    public function get_qr_code_url($username, $secret, $issuer = null) {
        if ($issuer === null) {
            $issuer = env('APP_NAME', 'AdminBRO');
        }
        return GoogleQrUrl::generate($username, $secret, $issuer);
    }
    
    /**
     * Verify a TOTP code against the secret
     * @param string $secret TOTP secret key
     * @param string $code 6-digit code from authenticator app
     * @return bool True if code is valid
     */
    public function verify_code($secret, $code) {
        // Check rate limiting
        if (!$this->check_rate_limit()) {
            return false;
        }
        
        $isValid = $this->ga->checkCode($secret, $code);
        
        // Track failed attempt if invalid
        if (!$isValid) {
            $this->record_failed_attempt();
        } else {
            $this->clear_failed_attempts();
        }
        
        return $isValid;
    }
    
    /**
     * Generate backup codes
     * @param int $count Number of backup codes to generate (default: 8)
     * @return array Array of backup codes
     */
    public function generate_backup_codes($count = 8) {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            // Generate 8-character alphanumeric code
            $codes[] = strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
        }
        return $codes;
    }
    
    /**
     * Check if rate limit has been exceeded
     * @return bool True if under limit, false if exceeded
     */
    private function check_rate_limit() {
        $attempts = $this->CI->session->userdata('2fa_attempts') ?? 0;
        $lockout_time = $this->CI->session->userdata('2fa_lockout_time') ?? 0;
        
        // Check if currently locked out
        if ($lockout_time > time()) {
            return false;
        }
        
        // Check if attempts exceeded
        if ($attempts >= 5) {
            // Set lockout for 5 minutes
            $this->CI->session->set_userdata('2fa_lockout_time', time() + 300);
            return false;
        }
        
        return true;
    }
    
    /**
     * Record a failed verification attempt
     */
    private function record_failed_attempt() {
        $attempts = $this->CI->session->userdata('2fa_attempts') ?? 0;
        $this->CI->session->set_userdata('2fa_attempts', $attempts + 1);
    }
    
    /**
     * Clear failed attempt counter
     */
    private function clear_failed_attempts() {
        $this->CI->session->unset_userdata('2fa_attempts');
        $this->CI->session->unset_userdata('2fa_lockout_time');
    }
    
    /**
     * Get remaining attempts before lockout
     * @return int Number of remaining attempts
     */
    public function get_remaining_attempts() {
        $attempts = $this->CI->session->userdata('2fa_attempts') ?? 0;
        return max(0, 5 - $attempts);
    }
    
    /**
     * Check if currently locked out
     * @return bool True if locked out
     */
    public function is_locked_out() {
        $lockout_time = $this->CI->session->userdata('2fa_lockout_time') ?? 0;
        return $lockout_time > time();
    }
    
    /**
     * Get lockout time remaining in seconds
     * @return int Seconds remaining in lockout
     */
    public function get_lockout_time_remaining() {
        $lockout_time = $this->CI->session->userdata('2fa_lockout_time') ?? 0;
        return max(0, $lockout_time - time());
    }
}
