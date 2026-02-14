<?php
// api/login.php
error_reporting(0);
header("Content-Type: application/json");
session_start();

// Check if already logged in
require_once __DIR__ . "/../db.php";

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (empty($data["username"]) || empty($data["password"])) {
    echo json_encode(["success" => false, "message" => "Username and password required"]);
    exit;
}

// Fetch user from database
$username = trim($data["username"]);
$password = $data["password"];

// Prepare and execute select statement
$stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($user_id, $hashed_password);

// Check if user exists and verify password
if ($stmt->fetch()) {
    if (password_verify($password, $hashed_password)) {
        $_SESSION["user_id"] = $user_id;
        echo json_encode(["success" => true, "message" => "Login success", "user_id" => $user_id]);
    } else {
        echo json_encode(["success" => false, "message" => "Wrong password"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "User not found"]);
}

// Close database connection
$conn->close();
?>