<?php
header("Content-Type: application/json; charset=UTF-8");

require "../../database/db_config.php";

$metode = $_SERVER['REQUEST_METHOD'];

// GET
if ($metode === 'GET') {

    // GET ONE
    if (isset($_GET['id'])) {

        $id = intval($_GET['id']);

        $sql = "SELECT * FROM IT_aktualitates WHERE id = $id";
        $rezultats = $savienojums->query($sql);

        echo json_encode($rezultats->fetch_assoc());
        exit;
    }

    // GET ALL
    $sql = "SELECT a.*, u.vards, u.uzvards 
            FROM IT_aktualitates AS a
            LEFT JOIN IT_lietotaji AS u 
            ON a.autors_id = u.lietotajs_id
            WHERE a.dzests != '1'
            ORDER BY a.izveidots DESC";

    $rezultats = $savienojums->query($sql);

    $dati = [];

    while ($rinda = $rezultats->fetch_assoc()) {
        $dati[] = $rinda;
    }

    echo json_encode($dati);
}
// ======= post =========
if ($metode === 'POST') {

    $aktualitate = json_decode(file_get_contents("php://input"), true);

    $id = $aktualitate['id'];

    $virsraksts = $aktualitate['virsraksts'];
    $iss_apraksts = $aktualitate['iss_apraksts'];
    $pilns_apraksts = $aktualitate['pilns_apraksts'];
    $attels = $aktualitate['attels'];
    $statuss = $aktualitate['statuss'];

    // UPDATE
    if (!empty($id)) {

        $sql = "UPDATE IT_aktualitates SET
                virsraksts = '$virsraksts',
                iss_apraksts = '$iss_apraksts',
                pilns_apraksts = '$pilns_apraksts',
                attels = '$attels',
                statuss = '$statuss'
                WHERE id = $id";

        $savienojums->query($sql);

        echo json_encode(["status" => "updated"]);
        exit;
    }

    // INSERT (only if no id)
    $sql = "INSERT INTO IT_aktualitates 
            (virsraksts, iss_apraksts, pilns_apraksts, attels, statuss)
            VALUES ('$virsraksts', '$iss_apraksts', '$pilns_apraksts', '$attels', '$statuss')";

    $savienojums->query($sql);

    echo json_encode(["status" => "inserted"]);
}
// ===== delete =====
if ($metode === 'DELETE') {

    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];

    $sql = "UPDATE IT_aktualitates 
            SET dzests = '1' 
            WHERE id = $id";

    $savienojums->query($sql);

    echo json_encode(["status" => "deleted"]);
}