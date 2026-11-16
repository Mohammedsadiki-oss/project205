
<?php

session_start();

header("Content-Type: application/json; charset=UTF-8");

// Verification de la methode POST

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);

    echo json_encode([
        "success" => false,
        "message" => "Utilisez la methode POST"
    ]);

    exit;
}

// Verification de la session

if (!isset($_SESSION["admin_id"])) {
    http_response_code(401);

    echo json_encode([
        "success" => false,
        "message" => "Aucun admin connecte"
    ]);

    exit;
}

// Suppression des donnees de session

$_SESSION = [];

// Suppression du cookie de session

if (ini_get("session.use_cookies")) {

    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        "",
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destruction de la session

session_destroy();

echo json_encode([
    "success" => true,
    "message" => "Deconnexion reussie"
]);

