
<?php

header("Content-Type: application/json; charset=UTF-8");
require_once __DIR__ . "/../admin/verify_admin.php";

require_once __DIR__ . "/../../config/data.php";

// Verification de la methode PUT

if ($_SERVER["REQUEST_METHOD"] !== "PUT") {
    http_response_code(405);

    echo json_encode([
        "success" => false,
        "message" => "Utilisez la methode PUT"
    ]);

    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

// Verification des donnees

if (
    !is_array($data) ||
    !isset(
        $data["id"],
        $data["nom"],
        $data["prenom"],
        $data["telephone"]
    ) ||
    !filter_var($data["id"], FILTER_VALIDATE_INT) ||
    (int)$data["id"] <= 0 ||
    trim($data["nom"]) === "" ||
    trim($data["prenom"]) === "" ||
    trim($data["telephone"]) === ""
) {
    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Donnees manquantes ou invalides"
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

    // Modification du client

    $sql = "UPDATE clients SET
        nom = :nom,
        prenom = :prenom,
        profession = :profession,
        photo = :photo,
        telephone = :telephone,
        email = :email,
        whatsapp = :whatsapp,
        instagram = :instagram,
        facebook = :facebook,
        linkedin = :linkedin,
        website = :website,
        adresse = :adresse,
        description = :description
        WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
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
        ":description" => $data["description"] ?? null,
        ":id" => $data["id"]
    ]);

    http_response_code(200);

    echo json_encode([
        "success" => true,
        "message" => "Client modifie avec succes"
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Erreur lors de la modification du client"
    ]);

}
