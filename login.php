<?php
session_start();
require_once 'db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$role = trim($_POST['role'] ?? '');

if ($username === '' || $password === '' || $role === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing credentials.']);
    exit;
}

// Try to find user by username OR student/employee number depending on role
$sql = "SELECT id, username, employee_no, password, role, student_no, firstname, lastname, grade_level, section, contact, profile_photo
        FROM users
        WHERE role = ? AND (username = ? OR student_no = ? OR employee_no = ?)
        LIMIT 1";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error preparing statement.']);
    exit;
}

$stmt->bind_param('ssss', $role, $username, $username, $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user || !password_verify($password, $user['password'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Invalid username or password.']);
    exit;
}

// Create session
$_SESSION['user_id'] = (int)$user['id'];
$_SESSION['role'] = $user['role'];

// Build response without password
$responseUser = [
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
];

echo json_encode(['success' => true, 'message' => 'Login successful', 'user' => $responseUser]);
$conn->close();
