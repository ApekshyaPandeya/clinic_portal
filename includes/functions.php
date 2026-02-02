<?php
// includes/functions.php

/**
 * Escape HTML output for XSS prevention
 */
function h($string)
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Generate CSRF token
 */
function generate_csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verify_csrf_token($token)
{
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed.");
    }
    return true;
}

/**
 * Redirect and exit
 */
function redirect($url)
{
    header("Location: $url");
    exit();
}

/**
 * Flash messages helper
 */
function set_flash_message($message, $type = 'success')
{
    $_SESSION['flash_message'] = ['text' => $message, 'type' => $type];
}

function display_flash_message()
{
    if (isset($_SESSION['flash_message'])) {
        $msg = $_SESSION['flash_message'];
        echo "<div class='flash-msg flash-{$msg['type']}'>{$msg['text']}</div>";
        unset($_SESSION['flash_message']);
    }
}
?>