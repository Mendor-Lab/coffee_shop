<?php
// Bootstrap for pages and API endpoints: session, CSRF, and security headers
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Security headers (safe defaults; allow known CDNs and inline for now)
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: no-referrer-when-downgrade');
header("Permissions-Policy: geolocation=(), microphone=(), camera=()");

$csp = [
    "default-src 'self'",
    "base-uri 'self'",
    "img-src 'self' https: data:",
    "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com",
    "script-src 'self' 'unsafe-inline'",
    "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com",
    "connect-src 'self' https://*.supabase.co",
    "frame-src https://www.google.com"
];
header('Content-Security-Policy: ' . implode('; ', $csp));
