
<?php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}

session_start();

// Verification de la session
if (!isset($_SESSION["admin_id"])) {

    http_response_code(401);

    echo json_encode([
        "success" => false,
        "message" => "Admin non connecte"
    ]);

    exit;
}

// Admin connecte
echo json_encode([
    "success" => true,
    "message" => "Admin connecte",
    "admin" => [
        "id" => $_SESSION["admin_id"],
        "username" => $_SESSION["admin_username"]
    ]
]);
