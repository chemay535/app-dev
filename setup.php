<?php
require_once 'db.php';
header('Content-Type: text/plain');

$sql = file_get_contents(__DIR__ . '/schema.sql');
if ($sql === false) {
    http_response_code(500);
    echo "Failed to read schema.sql";
    exit;
}

// Split on semicolons while ignoring inside definitions is fine here
$queries = array_filter(array_map('trim', explode(';', $sql)));
$errors = [];
foreach ($queries as $q) {
    if ($q === '' || strpos($q, '--') === 0) continue;
    if (!$conn->query($q)) {
        $errors[] = $conn->error;
    }
}

if (empty($errors)) {
    echo "Setup completed successfully";
} else {
    echo "Setup finished with errors:\n" . implode("\n", $errors);
}
