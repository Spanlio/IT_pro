<?php
header("Content-Type: application/json; charset=UTF-8");

require "../../database/db_config.php"; // adjust the path if needed
session_start();

// Check if user is logged in
if (!isset($_SESSION["lietotajvards_divisaldie"])) {
    http_response_code(401); // Unauthorized
    echo json_encode(["error" => "Nepieciešama autorizācija"]);
    exit;
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $rezultats = $savienojums->query("SELECT * FROM IT_pro_lietotaji ORDER BY id DESC");
    $pieteikumi = [];

    while ($row = $rezultats->fetch_assoc()) {
        $pieteikumi[] = $row;
    }

    echo json_encode($pieteikumi);
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Metode nav atbalstīta"]);
}

$savienojums->close();
?>