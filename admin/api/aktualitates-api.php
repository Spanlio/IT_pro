<?php

header("Content-Type: application/json; charset=UTF-8");

require "../../database/db_config.php";
session_start();

$metode = $_SERVER['REQUEST_METHOD'];


// =====================
// GET
// =====================
if ($metode === 'GET') {

    // ONE
    if (isset($_GET['id'])) {

        $id = intval($_GET['id']);

        $sql = "SELECT a.*, u.vards, u.uzvards 
                FROM IT_aktualitates a
                LEFT JOIN IT_lietotaji u 
                ON a.autors_id = u.lietotajs_id
                WHERE a.id = $id
                AND dzests != '1'";

        $rez = $savienojums->query($sql);

        if ($rez && $rez->num_rows > 0) {
            echo json_encode($rez->fetch_assoc());
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Nav atrasts"
            ]);
        }

        exit;
    }

    // ALL
    $sql = "SELECT a.*, u.vards, u.uzvards 
            FROM IT_aktualitates a
            LEFT JOIN IT_lietotaji u 
            ON a.autors_id = u.lietotajs_id
            WHERE a.dzests != '1'
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

    // ===== DATA =====
    $id = $_POST['id'] ?? null;
    $virsraksts = $_POST['virsraksts'] ?? '';
    $iss = $_POST['iss_apraksts'] ?? '';
    $pilns = $_POST['pilns_apraksts'] ?? '';
    $statuss = $_POST['statuss'] ?? 'melnraksts';

    // ===== VALIDATION =====
    if (empty($virsraksts) || empty($iss)) {
        echo json_encode([
            "status" => "error",
            "message" => "Nav aizpildīti lauki"
        ]);
        exit;
    }

    // ===== IMAGE UPLOAD =====
    $attels = null;

    if (isset($_FILES['attels']) && $_FILES['attels']['error'] === 0) {

        $file = $_FILES['attels'];

        $allowed = ['image/jpeg', 'image/png', 'image/jpg'];

        if (!in_array($file['type'], $allowed)) {
            echo json_encode([
                "status" => "error",
                "message" => "Nepareizs attēla formāts"
            ]);
            exit;
        }

        if ($file['size'] > 2 * 1024 * 1024) {
            echo json_encode([
                "status" => "error",
                "message" => "Attēls par lielu (max 2MB)"
            ]);
            exit;
        }

        $uploadDir = "../../uploaded_files/";
        $filename = time() . "_" . basename($file['name']);

        if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
            $attels = "uploaded_files/" . $filename;
        }
    }

    // ===== UPDATE =====
    if (!empty($id)) {

        $imgSql = $attels ? ", attels = '$attels'" : "";

        $sql = "UPDATE IT_aktualitates SET
                virsraksts = '$virsraksts',
                iss_apraksts = '$iss',
                pilns_apraksts = '$pilns',
                statuss = '$statuss'
                $imgSql,
                redigets = NOW()
                WHERE id = $id";

        if ($savienojums->query($sql)) {
            echo json_encode([
                "status" => "success",
                "message" => "Aktualitāte rediģēta"
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
            "message" => "Aktualitāte pievienota"
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
// DELETE (SOFT)
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