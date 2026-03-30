<?php
header("Content-Type: application/json; charset=UTF-8");

require "../../database/db_config.php";

if ($savienojums->connect_error) {
    echo json_encode(["error" => "DB kļūda"]);
    exit;
}

// ===== GET =====
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // ===== SINGLE POST =====
    if (isset($_GET['id'])) {

        $id = (int)$_GET['id'];

        $stmt = $savienojums->prepare("
            SELECT * 
            FROM IT_aktualitates
            WHERE id = ? AND statuss = 'publicets'
        ");

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            echo json_encode($row);
        } else {
            echo json_encode(["error" => "Nav atrasts"]);
        }

        $stmt->close();
        exit;
    }

    // ===== PARAMETERS =====
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 6;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $search = isset($_GET['search']) ? $savienojums->real_escape_string($_GET['search']) : "";

    $offset = ($page - 1) * $limit;

    // ===== QUERY =====
    $sql = "
        SELECT id, virsraksts, iss_apraksts, attels, izveidots
        FROM IT_aktualitates
        WHERE statuss = 'publicets'
    ";

    if ($search !== "") {
        $sql .= "
            AND (
                virsraksts LIKE '%$search%' OR
                iss_apraksts LIKE '%$search%' OR
                pilns_apraksts LIKE '%$search%'
            )
        ";
    }

    $sql .= "
        ORDER BY izveidots DESC
        LIMIT $limit OFFSET $offset
    ";

    $result = $savienojums->query($sql);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
}