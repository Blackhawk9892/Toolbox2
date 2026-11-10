
<?php

require_once __DIR__ . '/config.php';

mysqli_report(MYSQLI_REPORT_OFF);

$conn = new mysqli(
    DB_HOST,
    DB_USER,
    DB_PASS,
    DB_NAME,
    DB_PORT
);

header('Content-Type: application/json; charset=utf-8');

if ($conn->connect_errno) {

    error_log(
        'MySQL connection failed: ' .
        $conn->connect_error
    );

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed. Check database credentials.'
    ]);

    exit;
}

if (!$conn->set_charset('utf8mb4')) {
    error_log('Failed to set MySQL character set.');
}
