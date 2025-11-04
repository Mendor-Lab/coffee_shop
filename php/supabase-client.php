<?php

class SupabaseClient {
    private $supabaseUrl;
    private $supabaseKey;

    public function __construct() {
        $envFile = __DIR__ . '/../.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) {
                    continue;
                }
                if (strpos($line, '=') === false) {
                    continue;
                }
                list($key, $value) = explode('=', $line, 2);
                $_ENV[trim($key)] = trim($value);
            }
        }

        // Support both README and legacy env variable names
        $this->supabaseUrl = $_ENV['SUPABASE_URL']
            ?? $_ENV['VITE_SUPABASE_URL']
            ?? '';
        $this->supabaseKey = $_ENV['SUPABASE_KEY']
            ?? $_ENV['VITE_SUPABASE_ANON_KEY']
            ?? '';
    }

    public function insert($table, $data) {
        $url = rtrim($this->supabaseUrl, '/') . "/rest/v1/" . $table;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'apikey: ' . $this->supabaseKey,
            'Authorization: Bearer ' . $this->supabaseKey,
            'Prefer: return=representation'
        ]);
        // Timeouts for reliability
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            return ['success' => false, 'error' => $error, 'http_code' => 0];
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            return ['success' => true, 'data' => json_decode($response, true)];
        } else {
            return ['success' => false, 'error' => $response, 'http_code' => $httpCode];
        }
    }

}
