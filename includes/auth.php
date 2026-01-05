<?php
// Authentication and session management

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Require login
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . ADMIN_URL . '/login.php');
        exit;
    }
}

// Login user
function loginUser($username, $password) {
    require_once __DIR__ . '/db.php';
    
    $username = escapeString($username);
    $sql = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
    $user = fetchOne($sql);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['full_name'] = $user['full_name'];
        return true;
    }
    
    return false;
}

// Logout user
function logoutUser() {
    session_unset();
    session_destroy();
}

// Get current user
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    require_once __DIR__ . '/db.php';
    
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM users WHERE id = $user_id LIMIT 1";
    return fetchOne($sql);
}

// Set flash message
function setFlashMessage($message, $type = 'success') {
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
}

// Get and clear flash message
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        $type = $_SESSION['flash_type'] ?? 'success';
        
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
        
        return ['message' => $message, 'type' => $type];
    }
    
    return null;
}
