
<?php

header("Content-Type: application/json; charset=UTF-8");
require_once __DIR__ . "/../admin/verify_admin.php";

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

$data = json_decode(file_get_contents("php://input"), true);

// Verification des donnees

if (
    !is_array($data) ||
    !isset($data["nom"], $data["prenom"], $data["telephone"]) ||
    trim($data["nom"]) === "" ||
    trim($data["prenom"]) === "" ||
    trim($data["telephone"]) === ""
) {
    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Donnees obligatoires manquantes"
    ]);

    exit;
}

try {

    // Generation d'un slug unique

    $slug = bin2hex(random_bytes(8));

    $sql = "INSERT INTO clients
    (
        slug,
        nom,
        prenom,
        profession,
        photo,
        telephone,
        email,
        whatsapp,
        instagram,
        facebook,
        linkedin,
        website,
        adresse,
        description
    )
    VALUES
    (
        :slug,
        :nom,
        :prenom,
        :profession,
        :photo,
        :telephone,
        :email,
        :whatsapp,
        :instagram,
        :facebook,
        :linkedin,
        :website,
        :adresse,
        :description
    )";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":slug" => $slug,
        ":nom" => trim($data["nom"]),
        ":prenom" => trim($data["prenom"]),
        ":profession" => $data["profession"] ?? null,
        ":photo" => $data["photo"] ?? null,
        ":telephone" => trim($data["telephone"]),
        ":email" => $data["email"] ?? null,
        ":whatsapp" => $data["whatsapp"] ?? null,
        ":instagram" => $data["instagram"] ?? null,
        ":facebook" => $data["facebook"] ?? null,
        ":linkedin" => $data["linkedin"] ?? null,
        ":website" => $data["website"] ?? null,
        ":adresse" => $data["adresse"] ?? null,
        ":description" => $data["description"] ?? null
    ]);

    http_response_code(201);

    echo json_encode([
        "success" => true,
        "message" => "Client ajoute avec succes",
        "id" => $pdo->lastInsertId(),
        "slug" => $slug
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Erreur lors de l'ajout du client"
    ]);

}
