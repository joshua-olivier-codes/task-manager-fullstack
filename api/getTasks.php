<?php
// api/getTasks.php
error_reporting(0);
header("Content-Type: application/json");
require_once __DIR__ . "/../db.php";

// Validate user_id parameter
if (empty($_GET["user_id"])) {
    echo json_encode(["success" => false, "message" => "Not logged in"]);
    exit;
}

// Fetch tasks for the given user_id
$user_id = intval($_GET["user_id"]);

// Prepare and execute select statement
$stmt = $conn->prepare("SELECT id, user_id, title, completed FROM tasks WHERE user_id = ?");
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Query error: " . $conn->error]);
    exit;
}

// Bind parameters and execute
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($id, $uid, $title, $completed);

// Collect tasks into an array
$tasks = [];
while ($stmt->fetch()) {
    $tasks[] = [
        "id"        => $id,
        "user_id"   => $uid,
        "title"     => $title,
        "completed" => $completed
    ];
}

// Return tasks as JSON
echo json_encode($tasks);
?>