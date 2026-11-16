
<?php

header("Content-Type: application/json; charset=UTF-8");

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
    trim($data["password"]) === ""
) {
    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Username et password obligatoires"
    ]);

    exit;
}

try {

    // Hash du mot de passe

    $password = password_hash($data["password"], PASSWORD_DEFAULT);

    // Insertion de l'admin

    $sql = "INSERT INTO admins (username, password)
            VALUES (:username, :password)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":username" => trim($data["username"]),
        ":password" => $password
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Admin ajoute avec succes"
    ]);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Erreur lors de l'ajout de l'admin"
    ]);

}
