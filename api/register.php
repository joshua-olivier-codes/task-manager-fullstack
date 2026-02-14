<?php
// api/register.php
error_reporting(0);
header("Content-Type: application/json");

// Include database connection
require_once __DIR__ . "/../db.php";

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (empty($data["username"]) || empty($data["password"])) {
    echo json_encode(["success" => false, "message" => "Username and password required"]);
    exit;
}

// Cleaning up the username and password
$username = trim($data["username"]);
$password = $data["password"];

// Check if username already exists
$check = $conn->prepare("SELECT id FROM users WHERE username = ?");
$check->bind_param("s", $username);
$check->execute();
$check->bind_result($existing_id);

// If a record is found, the username is taken
if ($check->fetch()) {
    echo json_encode(["success" => false, "message" => "Username already taken"]);
    exit;
}
$check->close();

// Hash password and insert
$hashed = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $hashed);

// Return success response
if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "User created"]);
} else {
    echo json_encode(["success" => false, "message" => "Registration failed"]);
}

// Close database connection
$conn->close();
?>