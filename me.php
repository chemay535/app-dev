<?php
session_start();
require_once 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['authenticated' => false]);
    exit;
}

$userId = (int)$_SESSION['user_id'];

$stmt = $conn->prepare("SELECT id, username, employee_no, role, student_no, firstname, lastname, grade_level, section, contact, profile_photo FROM users WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();

if (!$user) {
    echo json_encode(['authenticated' => false]);
    exit;
}

echo json_encode([
    'authenticated' => true,
    'user' => [
        'id' => (int)$user['id'],
        'username' => $user['username'],
        'role' => $user['role'],
        'student_no' => $user['student_no'],
        'employee_no' => $user['employee_no'],
        'firstname' => $user['firstname'],
        'lastname' => $user['lastname'],
        'grade_level' => $user['grade_level'],
        'section' => $user['section'],
        'contact' => $user['contact'],
        'profile_photo' => $user['profile_photo'] ?: 'uploads/profile/default_profile.png'
    ]
]);
