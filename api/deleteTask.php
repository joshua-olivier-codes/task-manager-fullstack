<?php
// api/deleteTask.php
error_reporting(0);
header("Content-Type: application/json");
require_once __DIR__ . "/../db.php";

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (!isset($data["id"]) || !isset($data["user_id"])) {
    echo json_encode(["success" => false, "message" => "Task ID and user_id required"]);
    exit;
}

// Delete task from database
$task_id  = intval($data["id"]);
$user_id  = intval($data["user_id"]);

// Prepare and execute delete statement
$stmt = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Query error: " . $conn->error]);
    exit;
}

// Bind parameters and execute
$stmt->bind_param("ii", $task_id, $user_id);

// Return success response
if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to delete task"]);
}
?>