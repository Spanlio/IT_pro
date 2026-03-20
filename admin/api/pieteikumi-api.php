<?php
header("Content-Type: application/json; charset=UTF-8");

require "../../database/db_config.php";

if ($savienojums->connect_error) {
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => "Neizdevās pieslēgties datubāzei"]); 
    exit;
}

session_start();
if (!isset($_SESSION["lietotajvards_divisaldie"])) {
    http_response_code(401); // Unauthorized
    echo json_encode(["error" => "Nepieciešama autorizācija"]);
    exit;
}

// Palīgfunkcija datu validācijai:
function validet_laukus($pieteikums) {
    // Obligātie ievadlauki:
    $obligati_lauki = ['vards', 'uzvards', 'epasts', 'talrunis', 'apraksts', 'statuss'];
    foreach ($obligati_lauki as $lauks) {
        if (!isset($pieteikums[$lauks]) || trim($pieteikums[$lauks]) === '') {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "Lauks '$lauks' ir obligāts un nevar būt tukšs"]);
            exit;
        }
    }

    // E-pasta validācija:
    if (!filter_var(trim($pieteikums['epasts']), FILTER_VALIDATE_EMAIL)) {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "Nederīgs e-pasta formāts"]);
        exit;
    }

    // Tālruņa validācija (tikai cipari, 8 simboli):
    if (!preg_match('/^[0-9]{8}$/', trim($pieteikums['talrunis']))) {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "Tālruņa numuram jāsastāv tieši no 8 cipariem"]);
        exit;
    }
}

$metode = $_SERVER['REQUEST_METHOD'];
// ================= get =================
if ($metode === 'GET') {
    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];
        $sql = $savienojums->prepare("SELECT * FROM IT_pieteikumi WHERE pieteikums_id=?");
        $sql->bind_param("i", $id);
        $sql->execute();
        $rezultats = $sql->get_result();

        if ($pieteikums = $rezultats->fetch_assoc()) {
            echo json_encode($pieteikums);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(["error" => "Pieteikums nav atrasts"]);
        }
        $sql->close();
    } else {
        $rezultats = $savienojums->query("SELECT * FROM IT_pieteikumi ORDER BY pieteikums_id DESC");
        $pieteikumi = [];
        while ($pieteikums = $rezultats->fetch_assoc()) {
            $pieteikumi[] = $pieteikums;
        }
        echo json_encode($pieteikumi);
    }
}
elseif ($metode === 'POST') {
    $pieteikums = json_decode(file_get_contents("php://input"), true);

    if (!$pieteikums) {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "Nederīgs JSON"]);
        exit;
    }

    validet_laukus($pieteikums);

    // Set pedejasIzmainas (current datetime) and IP (from server)
    $pedejasIzmainas = date("Y-m-d H:i:s");
    $ip = $_SERVER['REMOTE_ADDR'];

    $sql = $savienojums->prepare("INSERT INTO IT_pieteikumi (vards, uzvards, epasts, talrunis, apraksts, statuss, pedejasIzmainas, ip) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $sql->bind_param("ssssssss", $pieteikums['vards'], $pieteikums['uzvards'], $pieteikums['epasts'], $pieteikums['talrunis'], $pieteikums['apraksts'], $pieteikums['statuss'], $pedejasIzmainas, $ip);

    if ($sql->execute()) {
        http_response_code(201); // Created
        echo json_encode(["message" => "Pieteikums pievienots", "pieteikums_id" => $sql->insert_id]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Neizdevās pievienot pieteikumu datubāzē"]);
    }
    $sql->close();
}
// =================== PUT =====================
elseif ($metode === 'PUT') {
    if (!isset($_GET['id'])) {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "Nepieciešams ID"]);
        exit;
    }
    $id = (int) $_GET['id'];
    
    $parbaude = $savienojums->prepare("SELECT pieteikums_id FROM IT_pieteikumi WHERE pieteikums_id=?");
    $parbaude->bind_param("i", $id);
    $parbaude->execute();
    if ($parbaude->get_result()->num_rows === 0) {
        http_response_code(404); // Not Found
        echo json_encode(["error" => "Pieteikums ar norādīto ID nav atrasts"]);
        exit;
    }
    $parbaude->close();

    $pieteikums = json_decode(file_get_contents("php://input"), true);

    if (!$pieteikums) {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "Nederīgs JSON"]);
        exit;
    }

    validet_laukus($pieteikums);

    $sql = $savienojums->prepare("UPDATE IT_pieteikumi SET vards=?, uzvards=?, epasts=?, talrunis=?, apraksts=?, statuss=?, pedejasIzmainas=? WHERE pieteikums_id=?");
    $sql->bind_param("sssssssi", $pieteikums['vards'], $pieteikums['uzvards'], $pieteikums['epasts'], $pieteikums['talrunis'], $pieteikums['apraksts'], $pieteikums['statuss'], $pedejasIzmainas, $id);

    if ($sql->execute()) {
        echo json_encode(["message" => "Pieteikums atjaunots"]);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(["error" => "Neizdevās atjaunot pieteikumu"]);
    }
    $sql->close();
} 
elseif ($metode === 'DELETE') {
    // notifications
    if (!isset($_GET['id'])) {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "Nepieciešams ID"]);
        exit;
    }
    $id = (int) $_GET['id'];
    $sql = $savienojums->prepare("DELETE FROM IT_pieteikumi WHERE pieteikums_id=?");
    $sql->bind_param("i", $id);

    if (!$sql->execute()) {
        http_response_code(500); // Internal Server Error
        echo json_encode(["error" => "Neizdevās dzēst pieteikumu"]);
        exit;
    }

    if ($sql->affected_rows === 0) {
        http_response_code(404); // Not Found
        echo json_encode(["error" => "Pieteikums nav atrasts"]);
        exit;
    }

    echo json_encode(["message" => "Pieteikums veiksmīgi dzēsts"]);
    $sql->close();
} 
else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Metode nav atbalstīta"]);
}

$savienojums->close();