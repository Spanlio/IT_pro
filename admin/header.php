<?php
session_start();
if(!isset($_SESSION["lietotajvards_divisaldie"])){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="lv" class="admin-dashboard ">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT atbalsts - Administrēšana</title>
    <link rel="stylesheet" href="../style.css?v=0.2">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon">
    <script src="aktualitates-admin.js" defer></script>
    <script src="pieteikumi-script.js?v=0.1" defer></script>
    <script src="lietotaji-script.js?v=0.1" defer></script>
</head>
<body>
    <header>
        <a href="./" class="logo">
            <i class="fa fa-server"></i> IT atbalsts
        </a>
        <nav>
           <a href="sakums.php" class="btn">Sākums</a>
           <a href="pieteikumi.php" class="btn">Pieteikumi</a>
           <a href="pro.php" class="btn">PRO īpašnieki</a>
           <a href="lietotaji.php" class="btn" id="lietotajiButton">Lietotāji</a>
           <a href="logout.php" class="btn"><i class="fa fa-power-off"></i></a>
    </a>
        </nav>
    </header>
    <div id="notification-container">
        <div id="notification"></div>
    </div>
    