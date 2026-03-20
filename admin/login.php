<?php
session_start();
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
</head>
<body>
<div class="modal modal-active">
        <div class="modal-box">
            <h2 data-lang-key="modal_ticket_title">Autorizācija administrēšanas panelī</h2>
            <?php
                if(isset($_SESSION['pazinojums'])){
                    echo "<p class='login-notif'>".$_SESSION["pazinojums"]."</p>";
                    unset($_SESSION['pazinojums']);
                }
            ?>
            <form action="login-function.php" method="POST">
                <label data-lang-key="label_name">Lietotājvārds:</label>
                <input type="text" name="lietotajs" required>
                <label data-lang-key="label_surname">Parole:</label>
                <input type="password" name="parole" required>
                <button type="submit" name="ielogoties" class="btn active" data-lang-key="btn_submit_ticket">ielogoties</button>
            </form>
</body>
</html>