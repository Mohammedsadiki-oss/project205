
<?php

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

    // Recuperation des informations du client

    $sql = "SELECT nom, prenom, profession, telephone,
                   email, whatsapp, website, adresse
            FROM clients
            WHERE slug = :slug";

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

    // Fonction pour securiser les valeurs VCF

    function escapeVCF($value) {

        $value = (string) $value;

        $value = str_replace("\\", "\\\\", $value);
        $value = str_replace(["\r\n", "\r", "\n"], "\\n", $value);
        $value = str_replace(",", "\\,", $value);
        $value = str_replace(";", "\\;", $value);

        return $value;
    }

    // Creation du fichier VCF

    $vcf = [];

    $vcf[] = "BEGIN:VCARD";
    $vcf[] = "VERSION:3.0";

    $vcf[] = "N:" . escapeVCF($client["nom"]) . ";" .
             escapeVCF($client["prenom"]) . ";;;";

    $vcf[] = "FN:" . escapeVCF(
        $client["prenom"] . " " . $client["nom"]
    );

    if (!empty($client["profession"])) {
        $vcf[] = "TITLE:" . escapeVCF($client["profession"]);
    }

    if (!empty($client["telephone"])) {
        $vcf[] = "TEL;TYPE=CELL:" . escapeVCF($client["telephone"]);
    }

    if (
        !empty($client["whatsapp"]) &&
        $client["whatsapp"] !== $client["telephone"]
    ) {
        $vcf[] = "TEL;TYPE=CELL:" . escapeVCF($client["whatsapp"]);
    }

    if (!empty($client["email"])) {
        $vcf[] = "EMAIL;TYPE=INTERNET:" . escapeVCF($client["email"]);
    }

    if (!empty($client["website"])) {
        $vcf[] = "URL:" . escapeVCF($client["website"]);
    }

    if (!empty($client["adresse"])) {
        $vcf[] = "ADR;TYPE=WORK:;;" .
                 escapeVCF($client["adresse"]) . ";;;;";
    }

    $vcf[] = "END:VCARD";

    // Telechargement du fichier

    header("Content-Type: text/vcard; charset=UTF-8");
    header('Content-Disposition: attachment; filename="contact.vcf"');

    echo implode("\r\n", $vcf) . "\r\n";

} catch (PDOException $e) {

    http_response_code(500);

    header("Content-Type: application/json; charset=UTF-8");

    echo json_encode([
        "success" => false,
        "message" => "Erreur lors de la creation du contact"
    ]);

}
