<?php
header("Content-Type: application/json; charset=UTF-8");

require "../../database/db_config.php";

if ($savienojums->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Neizdevās pieslēgties datubāzei"]);
    exit;
}

$metode = $_SERVER['REQUEST_METHOD'];

// ===== GET =====
if ($metode === 'GET') {

    // ===== GET SINGLE POST =====
    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];

        $sql = $savienojums->prepare("
            SELECT a.*, u.vards, u.uzvards 
            FROM IT_aktualitates a
            JOIN IT_lietotaji u ON a.autors = u.lietotajs_id
            WHERE a.id = ? AND a.statuss = 'publicets'
        ");

        $sql->bind_param("i", $id);
        $sql->execute();

        $rezultats = $sql->get_result();

        if ($raksts = $rezultats->fetch_assoc()) {
            echo json_encode($raksts);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Raksts nav atrasts"]);
        }

        $sql->close();
    }

    // ===== GET LIST (pagination + search + limit) =====
    else {

        $lapa = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limits = isset($_GET['limit']) ? (int)$_GET['limit'] : 12;
        $meklet = isset($_GET['search']) ? trim($_GET['search']) : "";

        $offset = ($lapa - 1) * $limits;

        // ===== BASE QUERY =====
        $where = "WHERE statuss = 'publicets'";

        if (!empty($meklet)) {
            $where = " AND (
                virsraksts LIKE '%$meklet%' OR
                iss_apraksts LIKE '%$meklet%' OR
                pilns_apraksts LIKE '%$meklet%'
            )";
        }

        // ===== COUNT =====
        $count_sql = "SELECT COUNT(*) as total FROM IT_aktualitates $where";
        $count_result = $savienojums->query($count_sql);
        $total = $count_result->fetch_assoc()['total'];
        $total_lapas = ceil($total / $limits);

        // ===== DATA =====
        $sql = "
            SELECT id, virsraksts, iss_apraksts, attels, izveidots
            FROM IT_aktualitates
            $where
            ORDER BY izveidots DESC
            LIMIT $limits OFFSET $offset
        ";

        $rezultats = $savienojums->query($sql);

        $raksti = [];
        while ($rinda = $rezultats->fetch_assoc()) {
            $raksti[] = $rinda;
        }

        echo json_encode([
            "posts" => $raksti,
            "total_pages" => $total_lapas
        ]);
    }
}

// ===== ERROR =====
else {
    http_response_code(405);
    echo json_encode(["error" => "Metode nav atbalstīta"]);
}

$savienojums->close();
