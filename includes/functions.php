<?php

/**
 * Sanitize input data.
 *
 * @param string $data The input data.
 * @return string The sanitized data.
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Get the base URL of the application.
 *
 * @return string The base URL.
 */
function getBaseUrl() {
    // Check if HTTPS is used, otherwise default to HTTP
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    
    // Get the server name (e.g., www.example.com)
    $serverName = $_SERVER['SERVER_NAME'];
    
    // Get the port number
    $port = $_SERVER['SERVER_PORT'];

    // If the port is not standard, include it in the URL
    if (($protocol === "https://" && $port != 443) || ($protocol === "http://" && $port != 80)) {
        $serverName .= ":" . $port;
    }
    
    // Get the web root path (if your application is in a subdirectory e.g., /myapp)
    $webRoot = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    
    // Construct the base URL
    return $protocol . $serverName . $webRoot . '/';
}

/**
 * Validate an email address.
 * Consider using filter_var() if you only need a simple check.
 *
 * @param string $email The email address to validate.
 * @return bool True if the email address is valid, false otherwise.
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}


