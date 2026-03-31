<?php
header("Content-Type: application/json; charset=UTF-8");

require "../../database/db_config.php";

if ($savienojums->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Neizdevās pieslēgties datubāzei"]);
    exit;
}

session_start();


$metode = $_SERVER['REQUEST_METHOD'];

// ================= GET =================
if ($metode === 'GET') {

    // 🔹 ONE aktualitate
    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];

        $sql = $savienojums->prepare("
            SELECT *
            FROM IT_aktualitates
            WHERE aktualitate_id=?");
        $sql->bind_param("i", $id);
        $sql->execute();
        $rezultats = $sql->get_result();

        if ($row = $rezultats->fetch_assoc()) {
            echo json_encode($row);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Aktualitāte nav atrasta"]);
        }

        $sql->close();
    }

    // 🔹 HOMEPAGE (latest 3)
    elseif (isset($_GET['latest'])) {


        $rezultats = $savienojums->query("
            SELECT * FROM IT_aktualitates 
            WHERE statuss='publicets'
            ORDER BY izveidots DESC 
            LIMIT 3
        ");

        $data = [];
        while ($row = $rezultats->fetch_assoc()) {
            $data[] = $row;
        }

        echo json_encode($data);
    }

    // 🔹 PAGINATION (same logic as your style)
    elseif (isset($_GET['page'])) {

        $search = $_GET['search'] ?? '';
        $limit = $_GET['limit'] ?? 12;
        $page = $_GET['page'] ?? 1;
        $offset = ($page - 1) * $limit;

        $sql = "SELECT * FROM IT_aktualitates WHERE statuss = 'publicets'";

        if (!empty($search)) {
            $search = $savienojums->real_escape_string($search);
            $sql .= " AND (
        virsraksts LIKE '%$search%'
        OR iss_apraksts LIKE '%$search%'
        OR pilns_apraksts LIKE '%$search%'
    )";
        }

        $sql .= " ORDER BY izveidots DESC LIMIT $limit OFFSET $offset";

        $rezultats = $savienojums->query($sql);

        $data = [];
        while ($row = $rezultats->fetch_assoc()) {
            $data[] = $row;
        }

        // 🔹 total count (same pattern, no prepare — like your GET all)
        $count_sql = "SELECT COUNT(*) as total FROM IT_aktualitates WHERE statuss='publicets'";

        if (!empty($search)) {
            $count_sql .= " AND (
        virsraksts LIKE '%$search%'
        OR iss_apraksts LIKE '%$search%'
        OR pilns_apraksts LIKE '%$search%'
    )";
        }

        $count_result = $savienojums->query($count_sql);
        $count_row = $count_result->fetch_assoc();

        $total = $count_row['total'];
        $totalPages = ceil($total / $limit);


        echo json_encode([
            "data" => $data,
            "totalPages" => $totalPages,
            "page" => (int)$page
        ]);
    } else {
        $rezultats = $savienojums->query("
            SELECT * FROM IT_aktualitates 
            ORDER BY aktualitate_id DESC
        ");

        $data = [];
        while ($row = $rezultats->fetch_assoc()) {
            $data[] = $row;
        }

        echo json_encode($data);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Metode nav atbalstīta"]);
}

$savienojums->close();
