
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

// Verification des donnees

if (!isset($_POST["id"]) || !isset($_FILES["photo"])) {
    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "ID et photo obligatoires"
    ]);

    exit;
}

$id = intval($_POST["id"]);

if ($id <= 0) {
    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "ID invalide"
    ]);

    exit;
}

// Verification du fichier

if ($_FILES["photo"]["error"] !== UPLOAD_ERR_OK) {
    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Erreur lors de l'envoi de la photo"
    ]);

    exit;
}

// Taille maximale : 5 MB

if ($_FILES["photo"]["size"] > 5 * 1024 * 1024) {
    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "La photo ne doit pas depasser 5 MB"
    ]);

    exit;
}

try {

    // Verification du type de fichier

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $_FILES["photo"]["tmp_name"]);
    finfo_close($finfo);

    $types = [
        "image/jpeg" => "jpg",
        "image/png" => "png",
        "image/webp" => "webp"
    ];

    if (!isset($types[$mime])) {
        http_response_code(400);

        echo json_encode([
            "success" => false,
            "message" => "Format accepte : JPG, PNG ou WEBP"
        ]);

        exit;
    }

    // Verification du client

    $sql = "SELECT id FROM clients WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([":id" => $id]);

    if (!$stmt->fetch()) {
        http_response_code(404);

        echo json_encode([
            "success" => false,
            "message" => "Client introuvable"
        ]);

        exit;
    }

    // Generation d'un nom unique

    $filename = bin2hex(random_bytes(16)) . "." . $types[$mime];

    // Dossier de destination

    $uploadDir = __DIR__ . "/../../uploads/clients/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $destination = $uploadDir . $filename;

    // Deplacement de la photo

    if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $destination)) {
        http_response_code(500);

        echo json_encode([
            "success" => false,
            "message" => "Impossible d'enregistrer la photo"
        ]);

        exit;
    }

    // Enregistrement du chemin dans la base de donnees

    $photoPath = "uploads/clients/" . $filename;

    $sql = "UPDATE clients SET photo = :photo WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":photo" => $photoPath,
        ":id" => $id
    ]);

    http_response_code(200);

    echo json_encode([
        "success" => true,
        "message" => "Photo ajoutee avec succes",
        "photo" => $photoPath,
        "url" => "http://localhost/nfc-business-card/backend/" . $photoPath
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Erreur lors de l'enregistrement de la photo"
    ]);

}
