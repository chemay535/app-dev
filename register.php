<?php
require_once 'db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['firstName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';
    $studentNumber = trim($_POST['studentNumber'] ?? '');
    $employeeNumber = trim($_POST['employeeNumber'] ?? '');
    // Accept 'course' (new name) while keeping DB column 'grade_level'
    $gradeLevel = trim($_POST['course'] ?? ($_POST['gradeLevel'] ?? ''));
    $section = trim($_POST['section'] ?? '');
    $mobileNumber = trim($_POST['mobileNumber'] ?? '');
    $profilePhoto = 'uploads/profile/default_profile.png'; // Default, can be updated later

    // Basic validation
    if (!$firstName || !$lastName || !$email || !$password || !$role || !$gradeLevel || !$section || !$mobileNumber || ($role === 'student' && !$studentNumber) || ($role === 'admin' && !$employeeNumber)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
        exit;
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL
    $stmt = $conn->prepare("INSERT INTO users (username, employee_no, password, role, student_no, firstname, lastname, grade_level, section, contact, created_at, profile_photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)");
    $username = $email;
    $stmt->bind_param(
        "sssssssssss",
        $username,
        $employeeNumber,
        $hashedPassword,
        $role,
        $studentNumber,
        $firstName,
        $lastName,
        $gradeLevel,
        $section,
        $mobileNumber,
        $profilePhoto
    );

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Registration successful!']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Registration failed: ' . $stmt->error]);
    }
    $stmt->close();
    $conn->close();
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
