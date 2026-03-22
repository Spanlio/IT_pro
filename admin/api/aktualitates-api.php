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
function validet_laukus($aktualitate) {
    // Obligātie ievadlauki:
    $obligati_lauki = ['virsraksts', 'iss_apraksts', 'pilns_apraksts', 'statuss'];
    foreach ($obligati_lauki as $lauks) {
        if (!isset($aktualitate[$lauks]) || trim($aktualitate[$lauks]) === '') {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "Lauks '$lauks' ir obligāts un nevar būt tukšs"]);
            exit;
        }
    }
}

$metode = $_SERVER['REQUEST_METHOD'];
// ================= get =================
if ($metode === 'GET') {
    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];
        $sql = $savienojums->prepare("SELECT * FROM IT_aktualitates WHERE id=?");
        $sql->bind_param("i", $id);
        $sql->execute();
        $rezultats = $sql->get_result();

        if ($aktualitate = $rezultats->fetch_assoc()) {
            echo json_encode($aktualitate);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(["error" => "Aktualitāte nav atrasta"]);
        }
        $sql->close();
    } else {
        $rezultats = $savienojums->query("SELECT * FROM IT_aktualitates WHERE dzests=0 ORDER BY izveidots DESC");
        $aktualitates = [];
        while ($aktualitate = $rezultats->fetch_assoc()) {
            $aktualitates[] = $aktualitate;
        }
        echo json_encode($aktualitates);
    }
}
elseif ($metode === 'POST') {
    $aktualitate = json_decode(file_get_contents("php://input"), true);

    if (!$aktualitate) {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "Nederīgs JSON"]);
        exit;
    }

    validet_laukus($aktualitate);

    $autors_id = $_SESSION["lietotajs_id"]; // assuming you store it
    $izveidots = date("Y-m-d H:i:s");

    $sql = $savienojums->prepare("INSERT INTO IT_aktualitates (virsraksts, iss_apraksts, pilns_apraksts, attels, autors_id, statuss, izveidots) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $sql->bind_param("ssssis", 
        $aktualitate['virsraksts'], 
        $aktualitate['iss_apraksts'], 
        $aktualitate['pilns_apraksts'], 
        $aktualitate['attels'], 
        $autors_id, 
        $aktualitate['statuss'], 
        $izveidots
    );

    if ($sql->execute()) {
        http_response_code(201); // Created
        echo json_encode(["message" => "Aktualitāte pievienota", "id" => $sql->insert_id]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Neizdevās pievienot aktualitāti datubāzē"]);
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
    
    $parbaude = $savienojums->prepare("SELECT id FROM IT_aktualitates WHERE id=?");
    $parbaude->bind_param("i", $id);
    $parbaude->execute();
    if ($parbaude->get_result()->num_rows === 0) {
        http_response_code(404); // Not Found
        echo json_encode(["error" => "Aktualitāte ar norādīto ID nav atrasta"]);
        exit;
    }
    $parbaude->close();

    $aktualitate = json_decode(file_get_contents("php://input"), true);

    if (!$aktualitate) {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "Nederīgs JSON"]);
        exit;
    }

    validet_laukus($aktualitate);

    $redigets = date("Y-m-d H:i:s");

    $sql = $savienojums->prepare("UPDATE IT_aktualitates SET virsraksts=?, iss_apraksts=?, pilns_apraksts=?, attels=?, statuss=?, redigets=? WHERE id=?");
    $sql->bind_param("ssssssi", 
        $aktualitate['virsraksts'], 
        $aktualitate['iss_apraksts'], 
        $aktualitate['pilns_apraksts'], 
        $aktualitate['attels'], 
        $aktualitate['statuss'], 
        $redigets, 
        $id
    );

    if ($sql->execute()) {
        echo json_encode(["message" => "Aktualitāte atjaunota"]);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(["error" => "Neizdevās atjaunot aktualitāti"]);
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

    // SOFT DELETE
    $sql = $savienojums->prepare("UPDATE IT_aktualitates SET dzests=1 WHERE id=?");
    $sql->bind_param("i", $id);

    if (!$sql->execute()) {
        http_response_code(500); // Internal Server Error
        echo json_encode(["error" => "Neizdevās dzēst aktualitāti"]);
        exit;
    }

    if ($sql->affected_rows === 0) {
        http_response_code(404); // Not Found
        echo json_encode(["error" => "Aktualitāte nav atrasta"]);
        exit;
    }

    echo json_encode(["message" => "Aktualitāte veiksmīgi dzēsta"]);
    $sql->close();
} 
else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Metode nav atbalstīta"]);
}

$savienojums->close();