
<?php

session_start();

header("Content-Type: application/json; charset=UTF-8");

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

