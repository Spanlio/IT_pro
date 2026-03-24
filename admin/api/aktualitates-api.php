<?php
header("Content-Type: application/json; charset=UTF-8");
require "../../database/db_config.php";

// GET ALL
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // GET ONE
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);

        $stmt = $savienojums->prepare("SELECT * FROM IT_aktualitates WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        exit;
    }

    // GET ALL
    $stmt = $savienojums->query("SELECT * FROM IT_aktualitates ORDER BY id DESC");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}