<?php
header("Content-Type: application/json; charset=UTF-8");

require "../../database/db_config.php";
session_start();

// =====================
// REQUEST METHOD
// =====================
$metode = $_SERVER['REQUEST_METHOD'];


// =====================
// GET (READ)
// =====================
if ($metode === 'GET') {

    // ===== GET ONE =====
    if (isset($_GET['id'])) {

        $id = intval($_GET['id']);

        $sql = "SELECT a.*, u.vards, u.uzvards 
                FROM IT_aktualitates a
                LEFT JOIN IT_lietotaji u 
                ON a.autors_id = u.lietotajs_id
                WHERE a.id = $id
                AND dzests != 1";

        $rez = $savienojums->query($sql);

        if ($rez && $rez->num_rows > 0) {
            echo json_encode($rez->fetch_assoc());
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Aktualitāte nav atrasta"
            ]);
        }

        exit;
    }

    // ===== GET ALL =====
    $sql = "SELECT a.*, u.vards, u.uzvards 
            FROM IT_aktualitates a
            LEFT JOIN IT_lietotaji u 
            ON a.autors_id = u.lietotajs_id
            WHERE a.dzests != 1
            ORDER BY a.izveidots DESC";

    $rez = $savienojums->query($sql);

    $dati = [];

    while ($rinda = $rez->fetch_assoc()) {
        $dati[] = $rinda;
    }

    echo json_encode($dati);
    exit;
}


// =====================
// POST (CREATE / UPDATE)
// =====================
if ($metode === 'POST') {

    // ===== GET DATA =====
    $data = json_decode(file_get_contents("php://input"), true);

    $id = $data['id'] ?? null;
    $virsraksts = $data['virsraksts'] ?? '';
    $iss = $data['iss_apraksts'] ?? '';
    $pilns = $data['pilns_apraksts'] ?? '';
    $attels = $data['attels'] ?? '';
    $statuss = $data['statuss'] ?? 'melnraksts';

    // ===== VALIDATION =====
    if (empty($virsraksts) || empty($iss)) {
        echo json_encode([
            "status" => "error",
            "message" => "Nav aizpildīti visi lauki"
        ]);
        exit;
    }

    // ===== UPDATE =====
    if (!empty($id)) {

        $sql = "UPDATE IT_aktualitates SET
                virsraksts = '$virsraksts',
                iss_apraksts = '$iss',
                pilns_apraksts = '$pilns',
                attels = '$attels',
                statuss = '$statuss',
                redigets = NOW()
                WHERE id = $id";

        if ($savienojums->query($sql)) {
            echo json_encode([
                "status" => "success",
                "message" => "Aktualitāte veiksmīgi rediģēta"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Kļūda rediģējot"
            ]);
        }

        exit;
    }

    // ===== INSERT =====
    $autors_id = $_SESSION['lietotajs_id'] ?? 0;

    $sql = "INSERT INTO IT_aktualitates 
            (virsraksts, iss_apraksts, pilns_apraksts, attels, statuss, autors_id, izveidots)
            VALUES 
            ('$virsraksts', '$iss', '$pilns', '$attels', '$statuss', '$autors_id', NOW())";

    if ($savienojums->query($sql)) {
        echo json_encode([
            "status" => "success",
            "message" => "Aktualitāte veiksmīgi pievienota"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Kļūda pievienojot"
        ]);
    }

    exit;
}


// =====================
// DELETE (SOFT DELETE)
// =====================
if ($metode === 'DELETE') {

    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'] ?? 0;

    if (!$id) {
        echo json_encode([
            "status" => "error",
            "message" => "Nepareizs ID"
        ]);
        exit;
    }

    $sql = "UPDATE IT_aktualitates 
            SET dzests = '1' 
            WHERE id = $id";

    if ($savienojums->query($sql)) {
        echo json_encode([
            "status" => "success",
            "message" => "Aktualitāte dzēsta"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Kļūda dzēšot"
        ]);
    }

    exit;
}