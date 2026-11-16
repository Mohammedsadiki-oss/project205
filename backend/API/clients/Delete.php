
<?php

header("Content-Type: application/json; charset=UTF-8");
require_once __DIR__ . "/../admin/verify_admin.php";

require_once __DIR__ . "/../../config/data.php";

// Verification de la methode DELETE

if ($_SERVER["REQUEST_METHOD"] !== "DELETE") {
    http_response_code(405);

    echo json_encode([
        "success" => false,
        "message" => "Utilisez la methode DELETE"
    ]);

    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

// Verification des donnees

if (
    !is_array($data) ||
    !isset($data["id"]) ||
    !filter_var($data["id"], FILTER_VALIDATE_INT) ||
    (int)$data["id"] <= 0
) {
    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "ID manquant ou invalide"
    ]);

    exit;
}

try {

    // Verification de l'existence du client

    $check = $pdo->prepare(
        "SELECT id FROM clients WHERE id = :id"
    );

    $check->execute([
        ":id" => $data["id"]
    ]);

    if (!$check->fetch()) {
        http_response_code(404);

        echo json_encode([
            "success" => false,
            "message" => "Client introuvable"
        ]);

        exit;
    }

    // Suppression du client

    $sql = "DELETE FROM clients WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":id" => $data["id"]
    ]);

    http_response_code(200);

    echo json_encode([
        "success" => true,
        "message" => "Client supprime avec succes"
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Erreur lors de la suppression du client"
    ]);

}
