<?php
// api/logout.php
header("Content-Type: application/json");
require_once __DIR__ . "/config.php";

// Destroy session to log out user
session_destroy();
echo json_encode(["success" => true]);
?>