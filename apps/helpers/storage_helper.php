<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Storage Helper
 *
 * Upload, download, delete file ke cloud storage (S3-compatible)
 * atau local storage, sesuai konfigurasi STORAGE_DRIVER di .env
 *
 * Driver yang didukung:
 *   local  — simpan ke folder di server
 *   s3     — Amazon S3 / Cloudflare R2 / MinIO / DigitalOcean Spaces
 *
 * Penggunaan:
 *   $this->load->helper('storage');
 *
 *   // Upload file
 *   $result = storage_upload('/path/lokal/file.pdf', 'folder/file.pdf');
 *
 *   // Upload dari string content
 *   $result = storage_upload_content('Isi file', 'folder/file.txt', 'text/plain');
 *
 *   // Dapatkan URL publik
 *   $url = storage_url('folder/file.pdf');
 *
 *   // Hapus file
 *   $result = storage_delete('folder/file.pdf');
 *
 *   // Cek file ada
 *   $ada = storage_exists('folder/file.pdf');
 *
 * Return upload/delete: ['status' => bool, 'message' => string, 'url' => string]
 */

// ─────────────────────────────────────────────
// PUBLIC API
// ─────────────────────────────────────────────

if (!function_exists('storage_upload')) {
    /**
     * Upload file dari path lokal ke storage
     */
    function storage_upload($local_path, $remote_key, $mime = null)
    {
        if (!file_exists($local_path)) {
            return ['status' => false, 'message' => 'File tidak ditemukan: ' . $local_path, 'url' => ''];
        }
        $content = file_get_contents($local_path);
        $mime    = $mime ?: _storage_mime($local_path);
        return storage_upload_content($content, $remote_key, $mime);
    }
}

if (!function_exists('storage_upload_content')) {
    /**
     * Upload dari string/binary content
     */
    function storage_upload_content($content, $remote_key, $mime = 'application/octet-stream')
    {
        $driver = env('STORAGE_DRIVER', 'local');
        if ($driver === 's3') {
            return _s3_put($content, $remote_key, $mime);
        }
        return _local_put($content, $remote_key);
    }
}

if (!function_exists('storage_url')) {
    /**
     * Dapatkan URL publik dari sebuah key
     */
    function storage_url($remote_key)
    {
        $driver = env('STORAGE_DRIVER', 'local');
        if ($driver === 's3') {
            $custom_domain = env('STORAGE_CUSTOM_DOMAIN', '');
            if ($custom_domain) {
                return rtrim($custom_domain, '/') . '/' . ltrim($remote_key, '/');
            }
            $endpoint = env('STORAGE_ENDPOINT', '');
            $bucket   = env('STORAGE_BUCKET', '');
            if ($endpoint) {
                return rtrim($endpoint, '/') . '/' . $bucket . '/' . ltrim($remote_key, '/');
            }
            $region = env('STORAGE_REGION', 'us-east-1');
            return 'https://' . $bucket . '.s3.' . $region . '.amazonaws.com/' . ltrim($remote_key, '/');
        }
        // local
        $base = env('STORAGE_LOCAL_URL', base_url());
        $dir  = env('STORAGE_LOCAL_PATH', 'uploads');
        return rtrim($base, '/') . '/' . trim($dir, '/') . '/' . ltrim($remote_key, '/');
    }
}

if (!function_exists('storage_delete')) {
    /**
     * Hapus file dari storage
     */
    function storage_delete($remote_key)
    {
        $driver = env('STORAGE_DRIVER', 'local');
        if ($driver === 's3') {
            return _s3_delete($remote_key);
        }
        return _local_delete($remote_key);
    }
}

if (!function_exists('storage_exists')) {
    /**
     * Cek apakah file ada di storage
     */
    function storage_exists($remote_key)
    {
        $driver = env('STORAGE_DRIVER', 'local');
        if ($driver === 's3') {
            return _s3_exists($remote_key);
        }
        return _local_exists($remote_key);
    }
}

// ─────────────────────────────────────────────
// LOCAL DRIVER
// ─────────────────────────────────────────────

if (!function_exists('_local_put')) {
    function _local_put($content, $key)
    {
        $base_dir = rtrim(FCPATH, '/') . '/' . trim(env('STORAGE_LOCAL_PATH', 'uploads'), '/');
        $full_path = $base_dir . '/' . ltrim($key, '/');
        $dir = dirname($full_path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        if (file_put_contents($full_path, $content) !== false) {
            return ['status' => true, 'message' => 'Upload berhasil.', 'url' => storage_url($key)];
        }
        return ['status' => false, 'message' => 'Gagal menulis file ke disk.', 'url' => ''];
    }
}

if (!function_exists('_local_delete')) {
    function _local_delete($key)
    {
        $base_dir  = rtrim(FCPATH, '/') . '/' . trim(env('STORAGE_LOCAL_PATH', 'uploads'), '/');
        $full_path = $base_dir . '/' . ltrim($key, '/');
        if (file_exists($full_path) && unlink($full_path)) {
            return ['status' => true, 'message' => 'File berhasil dihapus.'];
        }
        return ['status' => false, 'message' => 'File tidak ditemukan atau gagal dihapus.'];
    }
}

if (!function_exists('_local_exists')) {
    function _local_exists($key)
    {
        $base_dir  = rtrim(FCPATH, '/') . '/' . trim(env('STORAGE_LOCAL_PATH', 'uploads'), '/');
        $full_path = $base_dir . '/' . ltrim($key, '/');
        return file_exists($full_path);
    }
}

// ─────────────────────────────────────────────
// S3 DRIVER (AWS Signature Version 4)
// ─────────────────────────────────────────────

if (!function_exists('_s3_put')) {
    function _s3_put($content, $key, $mime = 'application/octet-stream')
    {
        $bucket   = env('STORAGE_BUCKET', '');
        $region   = env('STORAGE_REGION', 'us-east-1');
        $endpoint = _s3_endpoint($bucket, $region);
        $url      = $endpoint . '/' . ltrim($key, '/');

        $date_val   = gmdate('Ymd');
        $date_full  = gmdate('Ymd\THis\Z');
        $payload_hash = hash('sha256', $content);

        $headers = [
            'Content-Type'         => $mime,
            'Host'                 => parse_url($endpoint, PHP_URL_HOST),
            'x-amz-content-sha256' => $payload_hash,
            'x-amz-date'           => $date_full,
        ];

        $acl = env('STORAGE_ACL', 'public-read');
        if ($acl) $headers['x-amz-acl'] = $acl;

        $auth = _s3_auth_header('PUT', '/' . ltrim($key, '/'), $headers, $content, $bucket, $region, $date_val, $date_full, $payload_hash);
        $headers['Authorization'] = $auth;

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST  => 'PUT',
            CURLOPT_POSTFIELDS     => $content,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => _s3_curl_headers($headers),
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        if ($http_code === 200 || $http_code === 204) {
            return ['status' => true, 'message' => 'Upload berhasil.', 'url' => storage_url($key)];
        }
        $msg = $err ?: _s3_parse_error($response);
        return ['status' => false, 'message' => 'S3 Error [' . $http_code . ']: ' . $msg, 'url' => ''];
    }
}

if (!function_exists('_s3_delete')) {
    function _s3_delete($key)
    {
        $bucket   = env('STORAGE_BUCKET', '');
        $region   = env('STORAGE_REGION', 'us-east-1');
        $endpoint = _s3_endpoint($bucket, $region);
        $url      = $endpoint . '/' . ltrim($key, '/');

        $date_val  = gmdate('Ymd');
        $date_full = gmdate('Ymd\THis\Z');
        $payload_hash = hash('sha256', '');

        $headers = [
            'Host'                 => parse_url($endpoint, PHP_URL_HOST),
            'x-amz-content-sha256' => $payload_hash,
            'x-amz-date'           => $date_full,
        ];

        $auth = _s3_auth_header('DELETE', '/' . ltrim($key, '/'), $headers, '', $bucket, $region, $date_val, $date_full, $payload_hash);
        $headers['Authorization'] = $auth;

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST  => 'DELETE',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => _s3_curl_headers($headers),
        ]);
        $response  = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code === 204 || $http_code === 200) {
            return ['status' => true, 'message' => 'File berhasil dihapus.'];
        }
        return ['status' => false, 'message' => 'S3 Error [' . $http_code . ']: ' . _s3_parse_error($response)];
    }
}

if (!function_exists('_s3_exists')) {
    function _s3_exists($key)
    {
        $bucket   = env('STORAGE_BUCKET', '');
        $region   = env('STORAGE_REGION', 'us-east-1');
        $endpoint = _s3_endpoint($bucket, $region);
        $url      = $endpoint . '/' . ltrim($key, '/');

        $date_val  = gmdate('Ymd');
        $date_full = gmdate('Ymd\THis\Z');
        $payload_hash = hash('sha256', '');

        $headers = [
            'Host'                 => parse_url($endpoint, PHP_URL_HOST),
            'x-amz-content-sha256' => $payload_hash,
            'x-amz-date'           => $date_full,
        ];

        $auth = _s3_auth_header('HEAD', '/' . ltrim($key, '/'), $headers, '', $bucket, $region, $date_val, $date_full, $payload_hash);
        $headers['Authorization'] = $auth;

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST  => 'HEAD',
            CURLOPT_NOBODY         => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => _s3_curl_headers($headers),
        ]);
        curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $http_code === 200;
    }
}

// ─────────────────────────────────────────────
// S3 INTERNAL HELPERS
// ─────────────────────────────────────────────

if (!function_exists('_s3_endpoint')) {
    function _s3_endpoint($bucket, $region)
    {
        $custom = env('STORAGE_ENDPOINT', '');
        if ($custom) {
            // MinIO / R2 / DO Spaces: endpoint sudah termasuk bucket di URL path
            $style = env('STORAGE_PATH_STYLE', '1');
            if ($style === '1') {
                return rtrim($custom, '/') . '/' . $bucket;
            }
            return rtrim($custom, '/');
        }
        // AWS S3 default
        return 'https://' . $bucket . '.s3.' . $region . '.amazonaws.com';
    }
}

if (!function_exists('_s3_auth_header')) {
    function _s3_auth_header($method, $path, $headers, $payload, $bucket, $region, $date_val, $date_full, $payload_hash)
    {
        $access_key = env('STORAGE_KEY', '');
        $secret_key = env('STORAGE_SECRET', '');
        $service    = 's3';

        // Canonical headers — harus lowercase & sorted
        $canonical_headers = '';
        $signed_headers_list = [];
        ksort($headers);
        foreach ($headers as $k => $v) {
            $lk = strtolower($k);
            $canonical_headers .= $lk . ':' . trim($v) . "\n";
            $signed_headers_list[] = $lk;
        }
        $signed_headers = implode(';', $signed_headers_list);

        $canonical_request = implode("\n", [
            $method,
            $path,
            '', // query string
            $canonical_headers,
            $signed_headers,
            $payload_hash,
        ]);

        $credential_scope = $date_val . '/' . $region . '/' . $service . '/aws4_request';
        $string_to_sign   = implode("\n", [
            'AWS4-HMAC-SHA256',
            $date_full,
            $credential_scope,
            hash('sha256', $canonical_request),
        ]);

        $signing_key = hash_hmac('sha256', 'aws4_request',
            hash_hmac('sha256', $service,
                hash_hmac('sha256', $region,
                    hash_hmac('sha256', $date_val, 'AWS4' . $secret_key, true),
                true),
            true),
        true);

        $signature = hash_hmac('sha256', $string_to_sign, $signing_key);

        return 'AWS4-HMAC-SHA256 Credential=' . $access_key . '/' . $credential_scope
             . ', SignedHeaders=' . $signed_headers
             . ', Signature=' . $signature;
    }
}

if (!function_exists('_s3_curl_headers')) {
    function _s3_curl_headers($headers)
    {
        $out = [];
        foreach ($headers as $k => $v) {
            $out[] = $k . ': ' . $v;
        }
        return $out;
    }
}

if (!function_exists('_s3_parse_error')) {
    function _s3_parse_error($xml)
    {
        if (preg_match('/<Message>(.*?)<\/Message>/s', $xml, $m)) {
            return $m[1];
        }
        return 'Unknown error';
    }
}

if (!function_exists('_storage_mime')) {
    function _storage_mime($path)
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $map = [
            'jpg'  => 'image/jpeg',  'jpeg' => 'image/jpeg',
            'png'  => 'image/png',   'gif'  => 'image/gif',
            'webp' => 'image/webp',  'svg'  => 'image/svg+xml',
            'pdf'  => 'application/pdf',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'xls'  => 'application/vnd.ms-excel',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'zip'  => 'application/zip',
            'txt'  => 'text/plain',
            'csv'  => 'text/csv',
            'html' => 'text/html',
            'json' => 'application/json',
            'mp4'  => 'video/mp4',
        ];
        return isset($map[$ext]) ? $map[$ext] : 'application/octet-stream';
    }
}
