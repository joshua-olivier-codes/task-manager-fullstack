<?php
// api/updateTask.php
error_reporting(0);
header("Content-Type: application/json");
require_once __DIR__ . "/../db.php";

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (!isset($data["id"]) || !isset($data["completed"]) || empty($data["user_id"])) {
    echo json_encode(["success" => false, "message" => "Task ID, completed status, and user_id required"]);
    exit;
}

// Update task in database
$stmt = $conn->prepare("UPDATE tasks SET completed = ? WHERE id = ? AND user_id = ?");
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Query error: " . $conn->error]);
    exit;
}

// Bind parameters and execute
$stmt->bind_param("iii", $data["completed"], $data["id"], $data["user_id"]);

// Return success response
if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update task"]);
}
?>