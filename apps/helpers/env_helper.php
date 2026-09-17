<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ENV Helper
 * Baca dan tulis file .env di root project.
 */

// Path ke file .env
define('ENV_FILE', FCPATH . '.env');

/**
 * Cache parsed .env agar tidak baca file berulang kali
 */
function _env_load() {
    static $cache = null;
    if ($cache !== null) return $cache;

    $cache = [];
    if (!file_exists(ENV_FILE)) return $cache;

    $lines = file(ENV_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        // Skip baris komentar
        if (strpos($line, '#') === 0) continue;
        if (strpos($line, '=') === false) continue;

        list($key, $value) = explode('=', $line, 2);
        $cache[trim($key)] = trim($value);
    }
    return $cache;
}

/**
 * Ambil nilai dari .env
 * @param string $key
 * @param mixed  $default
 */
function env($key, $default = null) {
    $data = _env_load();
    return isset($data[$key]) ? $data[$key] : $default;
}

/**
 * Set atau update nilai di .env
 * @param string $key
 * @param string $value
 */
function set_env($key, $value) {
    $key   = strtoupper(trim($key));
    $value = trim($value);

    $lines   = [];
    $found   = false;
    $newLine = $key . '=' . $value;

    if (file_exists(ENV_FILE)) {
        $lines = file(ENV_FILE, FILE_IGNORE_NEW_LINES);
        foreach ($lines as &$line) {
            if (strpos(trim($line), $key . '=') === 0) {
                $line  = $newLine;
                $found = true;
                break;
            }
        }
        unset($line);
    }

    if (!$found) {
        $lines[] = $newLine;
    }

    file_put_contents(ENV_FILE, implode(PHP_EOL, $lines) . PHP_EOL);

    // Reset cache
    static $cache;
    $cache = null;
    _env_load();
}
