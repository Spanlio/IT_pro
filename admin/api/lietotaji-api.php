<?php
header("Content-Type: application/json; charset=UTF-8");

require "../../database/db_config.php";

if ($savienojums->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Neizdevās pieslēgties datubāzei"]);
    exit;
}

session_start();
if (!isset($_SESSION["lietotajvards_divisaldie"])) {
    http_response_code(401);
    echo json_encode(["error" => "Nepieciešama autorizācija"]);
    exit;
}

// ===== VALIDĀCIJA =====
function validet_laukus($lietotajs)
{
    $obligati_lauki = ['lietotajvards', 'vards', 'uzvards', 'epasts', 'parole', 'loma', 'regDatums'];

    foreach ($obligati_lauki as $lauks) {
        if (!isset($lietotajs[$lauks]) || trim($lietotajs[$lauks]) === '') {
            http_response_code(400);
            echo json_encode(["error" => "Lauks '$lauks' ir obligāts un nevar būt tukšs"]);
            exit;
        }
    }

    if (!filter_var(trim($lietotajs['epasts']), FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["error" => "Nederīgs e-pasta formāts"]);
        exit;
    }
}

$metode = $_SERVER['REQUEST_METHOD'];

// ===== GET =====
if ($metode === 'GET') {
    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];

        $sql = $savienojums->prepare("SELECT * FROM IT_lietotaji WHERE lietotajs_id=? AND statuss='aktivs'");
        $sql->bind_param("i", $id);
        $sql->execute();

        $rezultats = $sql->get_result();

        if ($lietotajs = $rezultats->fetch_assoc()) {
            echo json_encode($lietotajs);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Lietotājs nav atrasts"]);
        }

        $sql->close();
    } else {
        $rezultats = $savienojums->query("SELECT lietotajs_id, lietotajvards, vards, uzvards, epasts, loma, regDatums, statuss 
        FROM IT_lietotaji 
        WHERE statuss = 'aktivs'
        ORDER BY lietotajs_id DESC");

        $lietotaji = [];
        while ($lietotajs = $rezultats->fetch_assoc()) {
            $lietotaji[] = $lietotajs;
        }

        echo json_encode($lietotaji);
    }
}

// ===== POST =====
elseif ($metode === 'POST') {

    
    $lietotajs = json_decode(file_get_contents("php://input"), true);

    if (!$lietotajs) {
        http_response_code(400);
        echo json_encode(["error" => "Nederīgs JSON"]);
        exit;
    }

    validet_laukus($lietotajs);

    // Hash the password before inserting
    $hashed_password = password_hash($lietotajs['parole'], PASSWORD_DEFAULT);

    $sql = $savienojums->prepare("
    INSERT INTO IT_lietotaji 
    (lietotajvards, vards, uzvards, epasts, parole, loma, regDatums) 
    VALUES (?, ?, ?, ?, ?, ?, ?)
");

    $sql->bind_param(
        "sssssss",
        $lietotajs["lietotajvards"],
        $lietotajs["vards"],
        $lietotajs["uzvards"],
        $lietotajs["epasts"],
        $hashed_password,  // Use the hashed password
        $lietotajs["loma"],
        $lietotajs["regDatums"],
    );

    if ($sql->execute()) {
        http_response_code(201);
        echo json_encode([
            "message" => "Lietotājs pievienots",
            "lietotajs_id" => $sql->insert_id
        ]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Neizdevās pievienot lietotāju"]);
    }

    $sql->close();
}

// ===== PUT =====
elseif ($metode === 'PUT') {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "Nepieciešams ID"]);
        exit;
    }

    $id = (int) $_GET['id'];

    // Pārbaude vai eksistē
    $parbaude = $savienojums->prepare("SELECT lietotajs_id FROM IT_lietotaji WHERE lietotajs_id=? AMD statuss='aktivs'");
    $parbaude->bind_param("i", $id);
    $parbaude->execute();

    if ($parbaude->get_result()->num_rows === 0) {
        http_response_code(404);
        echo json_encode(["error" => "Lietotājs nav atrasts"]);
        exit;
    }
    $parbaude->close();

    $lietotajs = json_decode(file_get_contents("php://input"), true);

    if (!$lietotajs) {
        http_response_code(400);
        echo json_encode(["error" => "Nederīgs JSON"]);
        exit;
    }

    validet_laukus($lietotajs);
// =========================================
    if (!empty($lietotajs['parole'])) {
        $hashed_password = password_hash($lietotajs['parole'], PASSWORD_DEFAULT);

        $sql = $savienojums->prepare("
        UPDATE IT_lietotaji 
        SET lietotajvards=?, vards=?, uzvards=?, epasts=?, loma=?, regDatums=?, parole=?, statuss=?
        WHERE lietotajs_id=?
    ");

        $sql->bind_param(
            "sssssssis",
            $lietotajs["lietotajvards"],
            $lietotajs["vards"],
            $lietotajs["uzvards"],
            $lietotajs["epasts"],
            $lietotajs["loma"],
            $lietotajs["regDatums"],
            $hashed_password,
            $id,
            $lietotajs["statuss"]
        );
    } else {

        $sql = $savienojums->prepare("
        UPDATE IT_lietotaji 
        SET lietotajvards=?, vards=?, uzvards=?, epasts=?, loma=?, regDatums=?, statuss=?
        WHERE lietotajs_id=?
    ");

        $sql->bind_param(
            "ssssssi",
            $lietotajs["lietotajvards"],
            $lietotajs["vards"],
            $lietotajs["uzvards"],
            $lietotajs["epasts"],
            $lietotajs["loma"],
            $lietotajs["regDatums"],
            $id,
            $lietotajs["statuss"]
        );
    }

    // NOTIFICATIONIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIII

    if ($sql->execute()) {
        echo json_encode(["message" => "Lietotājs atjaunots"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Neizdevās atjaunot lietotāju"]);
    }

    $sql->close();
}

// ===== DELETE =====
elseif ($metode === 'DELETE') {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "Nepieciešams ID"]);
        exit;
    }

    $id = (int) $_GET['id'];

    $sql = $savienojums->prepare("UPDATE IT_lietotaji SET statuss = 'dzests' WHERE lietotajs_id=?");
    $sql->bind_param("i", $id);

    if (!$sql->execute()) {
        http_response_code(500);
        echo json_encode(["error" => "Neizdevās dzēst lietotāju"]);
        exit;
    }

    if ($sql->affected_rows === 0) {
        http_response_code(404);
        echo json_encode(["error" => "Lietotājs nav atrasts"]);
        exit;
    }

    echo json_encode(["message" => "Lietotājs veiksmīgi dzēsts"]);

    $sql->close();
}

// ===== ERROR =====
else {
    http_response_code(405);
    echo json_encode(["error" => "Metode nav atbalstīta"]);
}

$savienojums->close();
