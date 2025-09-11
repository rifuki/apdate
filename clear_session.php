<?php
// Simple session clearing utility for APDATE
session_start();

// Clear all session data
session_destroy();

// Clear all cookies
if (isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        setcookie($name, '', time()-1000);
        setcookie($name, '', time()-1000, '/');
    }
}

echo "✅ Session dan cookies telah dibersihkan!<br>";
echo "<a href='/login_dashboard'>Login kembali</a>";
?>