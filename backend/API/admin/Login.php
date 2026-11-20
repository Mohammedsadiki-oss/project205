
<?php

// CORS Configuration
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

header("Content-Type: application/json; charset=UTF-8");

// Handle preflight request
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}

session_start();

require_once __DIR__ . "/../../config/data.php";

// Verification de la methode POST

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);

    echo json_encode([
        "success" => false,
        "message" => "Utilisez la methode POST"
    ]);

    exit;
}

// Recuperation des donnees

$data = json_decode(file_get_contents("php://input"), true);

if (
    !isset($data["username"]) ||
    !isset($data["password"]) ||
    trim($data["username"]) === "" ||
    $data["password"] === ""
) {
    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Username et password obligatoires"
    ]);

    exit;
}

try {

    $sql = "SELECT * FROM admins WHERE username = :username";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":username" => trim($data["username"])
    ]);

    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (
        !$admin ||
        !password_verify($data["password"], $admin["password"])
    ) {

        http_response_code(401);

        echo json_encode([
            "success" => false,
            "message" => "Username ou password incorrect"
        ]);

        exit;
    }

    // Connexion reussie

    session_regenerate_id(true);

    $_SESSION["admin_id"] = $admin["id"];
    $_SESSION["admin_username"] = $admin["username"];

    echo json_encode([
        "success" => true,
        "message" => "Connexion reussie",
        "admin" => [
            "id" => $admin["id"],
            "username" => $admin["username"]
        ]
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Erreur serveur"
    ]);

}
