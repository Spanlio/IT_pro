<?php
session_start();
if(isset($_POST["nosutit"])){


    require 'database/db_config.php';

    $vards = $_POST['vards'];
    $uzvards = $_POST['uzvards'];
    $epasts = $_POST['epasts'];
    $talrunis = $_POST['talrunis'];
    $apraksts = $_POST['apraksts'];

    if(!empty($vards) &&
       !empty($uzvards) &&
       !empty($epasts) &&
       !empty($talrunis) &&
       !empty($apraksts)){
        $sql = $savienojums->prepare("INSERT INTO IT_pieteikumi (vards, uzvards, epasts, talrunis, apraksts) VALUES (?, ?, ?, ?, ?)");
        $sql->bind_param("sssis", $vards, $uzvards, $epasts, $talrunis, $apraksts);

        if($sql->execute()){
            $_SESSION["pazinojums"] = "Pieteikums veiksmīgi nosūtīts! Sazināsimies ar jums pavisam drīz! ;P";
        }else{
            $_SESSION["pazinojums"] = "Radās kļūda nosūtot pieteikumu! Sazinies ar mums pa tāleruni! :(";
        }

        $sql->close();
        
    }else{
        $_SESSION["pazinojums"] = "Visi ievades lauki nav aizpildīti :(";
    }
}

    header("Location: ./");
?>