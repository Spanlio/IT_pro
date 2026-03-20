<?php
    $serveris = "localhost"; // Ar FTP jau esam iekšā serverī
    $lietotajs = "grobina1_markovs";
    $parole = "xnpW9pFmiK!";
    $datubaze = "grobina1_markovs";

    $savienojums = mysqli_connect($serveris, $lietotajs, $parole, $datubaze);

    if(!$savienojums){
        die("Nav izveidots savienojums ar datubāzi!");
    }else{
        // echo "Savienojums izdevies!";
    }
?>