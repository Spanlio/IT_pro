<?php
session_start();
?>

<!DOCTYPE html>
<html lang="lv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT atbalsts</title>
    <link rel="stylesheet" href="style.css?v=0.1">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">
    <script src="aktualitates.js?v=0.1" defer></script>
</head>

<body>
    <header>
        <a href="index.php" class="logo">
            <i class="fa fa-server"></i> IT atbalsts
        </a>
        <nav>
           <a href="index.php" class="btn">Mājas</a>
        </nav>
    </header>

    <body>

        <section class="blog">
            <h1>Visas <span>aktualitātes</span></h1>

            <!-- SEARCH -->
            <input type="text" id="search" placeholder="Meklēt...">

            <!-- POSTS -->
            <div class="blog-container" id="aktualitates-container"></div>

            <!-- PAGINATION -->
            <div id="pagination"></div>
        </section>

    </body>

</html>