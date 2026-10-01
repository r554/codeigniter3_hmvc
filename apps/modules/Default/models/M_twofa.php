<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_twofa extends CI_Model {
    
    /**
     * Check if 2FA is enabled for a user
     * @param int $user_id User ID
     * @return bool True if 2FA is enabled
     */
    public function is_2fa_enabled($user_id) {
        $this->db->select('two_factor_enabled');
        $this->db->from('admin');
        $this->db->where('id', $user_id);
        $result = $this->db->get()->row();
        
        return $result && $result->two_factor_enabled == 1;
    }
    
    /**
     * Get user's 2FA secret
     * @param int $user_id User ID
     * @return string|null Secret key or null if not set
     */
    public function get_secret($user_id) {
        $this->db->select('two_factor_secret');
        $this->db->from('admin');
        $this->db->where('id', $user_id);
        $result = $this->db->get()->row();
        
        return $result ? $result->two_factor_secret : null;
    }
    
    /**
     * Enable 2FA for a user
     * @param int $user_id User ID
     * @param string $secret TOTP secret key
     * @param array $backup_codes Array of backup codes
     * @return bool Success
     */
    public function enable_2fa($user_id, $secret, $backup_codes) {
        // Format backup codes as array of objects with 'code' and 'used' properties
        $formatted_codes = [];
        foreach ($backup_codes as $code) {
            $formatted_codes[] = [
                'code' => $code,
                'used' => false
            ];
        }
        
        $data = [
            'two_factor_enabled' => 1,
            'two_factor_secret' => $secret,
            'two_factor_backup_codes' => json_encode($formatted_codes)
        ];
        
        $this->db->where('id', $user_id);
        return $this->db->update('admin', $data);
    }
    
    /**
     * Disable 2FA for a user
     * @param int $user_id User ID
     * @return bool Success
     */
    public function disable_2fa($user_id) {
        $data = [
            'two_factor_enabled' => 0,
            'two_factor_secret' => null,
            'two_factor_backup_codes' => null
        ];
        
        $this->db->where('id', $user_id);
        return $this->db->update('admin', $data);
    }
    
    /**
     * Verify and consume a backup code
     * @param int $user_id User ID
     * @param string $code Backup code to verify
     * @return bool True if code is valid and consumed
     */
    public function verify_backup_code($user_id, $code) {
        // Get current backup codes
        $this->db->select('two_factor_backup_codes');
        $this->db->from('admin');
        $this->db->where('id', $user_id);
        $result = $this->db->get()->row();
        
        if (!$result || !$result->two_factor_backup_codes) {
            return false;
        }
        
        $backup_codes = json_decode($result->two_factor_backup_codes, true);
        
        // Check if code exists
        $code = strtoupper(trim($code));
        $key = array_search($code, $backup_codes);
        
        if ($key === false) {
            return false;
        }
        
        // Remove used code
        unset($backup_codes[$key]);
        
        // Update database
        $data = [
            'two_factor_backup_codes' => json_encode(array_values($backup_codes))
        ];
        
        $this->db->where('id', $user_id);
        $this->db->update('admin', $data);
        
        return true;
    }
    
    /**
     * Get remaining backup codes count
     * @param int $user_id User ID
     * @return int Number of remaining backup codes
     */
    public function get_backup_codes_count($user_id) {
        $this->db->select('two_factor_backup_codes');
        $this->db->from('admin');
        $this->db->where('id', $user_id);
        $result = $this->db->get()->row();
        
        if (!$result || !$result->two_factor_backup_codes) {
            return 0;
        }
        
        $backup_codes = json_decode($result->two_factor_backup_codes, true);
        return count($backup_codes);
    }
    
    /**
     * Get user's 2FA data
     * @param int $user_id User ID
     * @return object|null User 2FA data
     */
    public function get_2fa_data($user_id) {
        $this->db->select('two_factor_enabled, two_factor_secret, two_factor_backup_codes');
        $this->db->from('admin');
        $this->db->where('id', $user_id);
        return $this->db->get()->row();
    }
    
    /**
     * Get user's 2FA status (for Profile controller)
     * @param int $user_id User ID
     * @return array|null User 2FA data as array
     */
    public function get_user_2fa_status($user_id) {
        $this->db->select('two_factor_enabled, two_factor_secret, two_factor_backup_codes');
        $this->db->from('admin');
        $this->db->where('id', $user_id);
        return $this->db->get()->row_array();
    }
}
