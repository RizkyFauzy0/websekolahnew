<?php
// Database connection and helper functions

// Include config
if (file_exists(__DIR__ . '/../config.php')) {
    require_once __DIR__ . '/../config.php';
} else {
    die('Configuration file not found. Please copy config.example.php to config.php and configure it.');
}

// Create database connection
function getDBConnection() {
    static $conn = null;
    
    if ($conn === null) {
        try {
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            $conn->set_charset("utf8mb4");
        } catch (Exception $e) {
            die("Database connection error: " . $e->getMessage());
        }
    }
    
    return $conn;
}

// Escape string for SQL
function escapeString($str) {
    $conn = getDBConnection();
    return $conn->real_escape_string($str);
}

// Execute query
function query($sql) {
    $conn = getDBConnection();
    $result = $conn->query($sql);
    
    if (!$result) {
        error_log("Query error: " . $conn->error . " | SQL: " . $sql);
        return false;
    }
    
    return $result;
}

// Fetch single row
function fetchOne($sql) {
    $result = query($sql);
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

// Fetch all rows
function fetchAll($sql) {
    $result = query($sql);
    $rows = [];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }
    
    return $rows;
}

// Get last insert ID
function getLastInsertId() {
    $conn = getDBConnection();
    return $conn->insert_id;
}

// Close connection
function closeConnection() {
    $conn = getDBConnection();
    if ($conn) {
        $conn->close();
    }
}

// Generate slug from string
function generateSlug($string) {
    $string = strtolower(trim($string));
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    return trim($string, '-');
}

// Format date for display
function formatDate($date, $format = 'd M Y') {
    if (!$date) return '';
    return date($format, strtotime($date));
}

// Sanitize output
function sanitize($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// Upload file
function uploadFile($file, $directory, $allowed_types = ['jpg', 'jpeg', 'png', 'gif']) {
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
        return ['success' => false, 'message' => 'No file uploaded'];
    }
    
    $upload_dir = UPLOAD_PATH . '/' . $directory;
    
    // Create directory if not exists
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Get file extension
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    // Check file type
    if (!in_array($file_ext, $allowed_types)) {
        return ['success' => false, 'message' => 'File type not allowed'];
    }
    
    // Generate unique filename
    $filename = uniqid() . '_' . time() . '.' . $file_ext;
    $filepath = $upload_dir . '/' . $filename;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return [
            'success' => true, 
            'filename' => $filename,
            'filepath' => $directory . '/' . $filename
        ];
    }
    
    return ['success' => false, 'message' => 'Failed to upload file'];
}

// Delete file
function deleteFile($filepath) {
    $full_path = UPLOAD_PATH . '/' . $filepath;
    if (file_exists($full_path)) {
        return unlink($full_path);
    }
    return false;
}
