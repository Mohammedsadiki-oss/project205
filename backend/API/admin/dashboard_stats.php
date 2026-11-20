
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

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "message" => "Utilisez la methode GET"
    ]);
    exit;
}

// Verification de la session Admin
if (!isset($_SESSION["admin_id"])) {
    http_response_code(401);
    echo json_encode([
        "success" => false,
        "message" => "Admin non connecte"
    ]);
    exit;
}

require_once __DIR__ . "/../../config/data.php";

try {
    // Nombre total de clients
    $sqlTotal = "SELECT COUNT(*) FROM clients";
    $totalClients = (int) $pdo->query($sqlTotal)->fetchColumn();

    // Nombre de cartes avec un slug
    $sqlCards = "
        SELECT COUNT(*)
        FROM clients
        WHERE slug IS NOT NULL AND slug != ''
    ";
    $totalCards = (int) $pdo->query($sqlCards)->fetchColumn();

    // Nouveaux clients des 30 derniers jours
    $sqlNew = "
        SELECT COUNT(*)
        FROM clients
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    ";
    $newClients = (int) $pdo->query($sqlNew)->fetchColumn();

    echo json_encode([
        "success" => true,
        "data" => [
            "total_clients" => $totalClients,
            "total_cards" => $totalCards,
            "new_clients" => $newClients
        ]
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Erreur lors du chargement des statistiques"
    ]);
}
