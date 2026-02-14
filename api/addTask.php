<?php
// api/addTask.php
error_reporting(0);
header("Content-Type: application/json");
require_once __DIR__ . "/../db.php";

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (empty($data["title"]) || empty($data["user_id"])) {
    echo json_encode(["success" => false, "message" => "Task title and user_id required"]);
    exit;
}

// Insert task into database
$stmt = $conn->prepare("INSERT INTO tasks (user_id, title) VALUES (?, ?)");
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Query error: " . $conn->error]);
    exit;
}

// Bind parameters and execute
$stmt->bind_param("is", $data["user_id"], $data["title"]);

// Return success response with new task ID
if ($stmt->execute()) {
    echo json_encode(["success" => true, "id" => $conn->insert_id]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to add task"]);
}
?>