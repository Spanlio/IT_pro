<?php
session_start();
// Šis fails pārbauda vai ir lietotājs datubāzē vai nav...
require '../database/db_config.php';

if (isset($_POST['ielogoties'])) {
    $lietotajvards = $_POST['lietotajs'];
    $parole = $_POST['parole'];

    $sql = $savienojums->prepare("SELECT lietotajvards, parole, loma, statuss FROM IT_lietotaji WHERE lietotajvards = ? LIMIT 1");
    $sql->bind_param("s", $lietotajvards);
    $sql->execute();

    $rezultats = $sql->get_result();
    if ($rezultats->num_rows === 1) { // pacheko vai ir tads lietotajvards, un tur nav vairak par 1 rindu
        $lietotajs = $rezultats->fetch_assoc(); // assoc masivs
        if (password_verify($parole, $lietotajs["parole"])) { // pacheko paroli
            if($lietotajs["statuss"] == 'aktivs'){
                $_SESSION["lietotajvards_divisaldie"] = $lietotajs["lietotajvards"];
                $_SESSION["lietotajs_loma"] = $lietotajs["loma"];
                header("Location: sakums.php"); //pārvada lietotāju uz mājaslapu, ja viss sakrīt
                exit;
            }else{
                $_SESSION["pazinojums"] =  "Šis lietotājs vairs nav aktīvs";
                header("Location: login.php");
            }
        }else{
            $_SESSION["pazinojums"] =  "Nepareizs lietotājvārds vai parole";
            header("Location: login.php");
        }
    }else{
        $_SESSION["pazinojums"] =  "Nepareizs lietotājvārds vai parole!";
        header("Location: login.php");
    }
}

?>
