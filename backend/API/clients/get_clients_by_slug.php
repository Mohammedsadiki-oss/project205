
<?php

header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/../../config/data.php";

// Verification de la methode GET

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);

    echo json_encode([
        "success" => false,
        "message" => "Utilisez la methode GET"
    ]);

    exit;
}

// Recuperation du slug

$slug = $_GET["slug"] ?? "";

if (trim($slug) === "") {
    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Slug obligatoire"
    ]);

    exit;
}

try {

    $sql = "SELECT * FROM clients WHERE slug = :slug";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":slug" => trim($slug)
    ]);

    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$client) {
        http_response_code(404);

        echo json_encode([
            "success" => false,
            "message" => "Client introuvable"
        ]);

        exit;
    }

    http_response_code(200);

    echo json_encode([
        "success" => true,
        "message" => "Client recupere avec succes",
        "data" => $client
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Erreur lors de la recuperation du client"
    ]);

}
