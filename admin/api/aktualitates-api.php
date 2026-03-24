<?php
header("Content-Type: application/json; charset=UTF-8");

require  "../../database/db_config.php";

$metode = $_SERVER['REQUEST_METHOD'];

// GET
if ($metode === 'GET') {

    $sql = "SELECT a.*, u.vards, u.uzvards 
        FROM IT_aktualitates a
        LEFT JOIN IT_lietotaji u 
        ON a.autors_id = u.lietotajs_id
        ORDER BY a.izveidots DESC";

    $rezultats = $savienojums->query($sql);

    $dati = [];

    while ($rinda = $rezultats->fetch_assoc()) {
        $dati[] = $rinda;
    }

    echo json_encode($dati);
}