<?php
require "header.php";
require "../database/db_config.php";

$sql = $savienojums->prepare("SELECT COUNT(*) FROM IT_pieteikumi WHERE statuss = 'Jauns'");
$sql->execute();
$result = $sql->get_result();
$row = $result->fetch_row();
$jauni_pieteikumi = $row[0];

$sql = $savienojums->prepare("SELECT COUNT(*) FROM IT_pieteikumi WHERE statuss = 'Atvērts'");
$sql->execute();
$result = $sql->get_result();
$row = $result->fetch_row();
$atverti_pieteikumi = $row[0];

$sql = $savienojums->prepare("SELECT COUNT(*) FROM IT_pieteikumi WHERE statuss = 'Gaida'");
$sql->execute();
$result = $sql->get_result();
$row = $result->fetch_row();
$gaida_pieteikumi = $row[0];
$sql = $savienojums->prepare("SELECT COUNT(*) FROM IT_pieteikumi");
$sql->execute();
$result = $sql->get_result();
$row = $result->fetch_row();
$kopa_pieteikumi = $row[0];
?>

<div class="container">
    <div class="stats-container">
        <div class="stats" id="admin-vards">
            <div class="teksts">
                <h2>Sveicināti, <?= $_SESSION["lietotajvards_divisaldie"] ?>!</h2>
            </div>
            <div class="teksts">
                <p>Tava loma sistēmā: <?= $_SESSION["lietotajs_loma"] ?></p>
            </div>
        </div>
        <div class="stats">
            <div class="icon"><i class="fa-solid fa-pen-to-square"></i></div>
            <div class="stats-text">
                <h2><?= $jauni_pieteikumi ?></h2>
                <p>Jauni pieteikumi</p>
            </div>
        </div>
        <div class="stats">
            <div class="icon"><i class="fa-solid fa-laptop"></i></div>
            <div class="stats-text">
                <h2><?= $atverti_pieteikumi ?></h2>
                <p>Atvērti pieteikumi</p>
            </div>
        </div>
        <div class="stats">
            <div class="icon"><i class="fa-solid fa-spinner"></i></div>
            <div class="stats-text">
                <h2><?= $gaida_pieteikumi ?></h2>
                <p>Gaida pieteikumi</p>
            </div>
        </div>
        <div class="stats">
            <div class="icon"><i class="fa-solid fa-list-check"></i></div>
            <div class="stats-text">
                <h2><?= $kopa_pieteikumi ?></h2>
                <p>Kopā pieteikumi</p>
            </div>
        </div>
    </div>
    <div class="stats2">
        <div class="table">
            <div class="virsraksts">
                <h3>JAUNĀKIE PIETEIKUMI</h3>
            </div>
            <table>
                <tr>
                    <th>Vārds, Uzvārds</th>
                    <th>Datums</th>
                    <th>Statuss</th>

                </tr>
                <?php
                $sql = $savienojums->prepare("SELECT vards, uzvards, datums, statuss FROM IT_pieteikumi ORDER BY datums DESC LIMIT 7");
                $sql->execute();
                $lietotaji = $sql->get_result();

                while ($lietotajs = $lietotaji->fetch_assoc()):
                ?>

                    <tr>
                        <td><?= $lietotajs["vards"]; ?> <?= $lietotajs["uzvards"]; ?></td>
                        <td><?= $lietotajs["datums"]; ?></td>
                        <td><?= $lietotajs["statuss"]; ?></td>
                    </tr>

                <?php endwhile; ?>
            </table>
        </div>
        <div class="table">
            <div class="virsraksts">
                <h3>PIETEIKUMU SKAITS</h3>
            </div>
            <?php

            $sql = $savienojums->prepare("
                                        SELECT DATE(datums) as diena, COUNT(*) as skaits
                                        FROM IT_pieteikumi
                                        WHERE datums >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                                        GROUP BY DATE(datums)
                                        ORDER BY diena
                                        ");

            $sql->execute();
            $result = $sql->get_result();

            $labels = [];
            $data = [];

            while ($row = $result->fetch_assoc()) {
                $labels[] = $row['diena'];
                $data[] = $row['skaits'];
            }
            ?>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <canvas id="myChart" height="200"></canvas>

            <script>
                const xValues = <?php echo json_encode($labels); ?>;
                const yValues = <?php echo json_encode($data); ?>;

                new Chart("myChart", {
                    type: "line",
                    data: {
                        labels: xValues,
                        datasets: [{
                            label: "Pieteikumi pa dienām",
                            backgroundColor: "#e8907a",
                            borderColor: "#dc5c3e",
                            pointBackgroundColor: "#dc5c3e",
                            data: yValues,
                            fill: true,
                            tension: 0.3,
                            pointRadius: 5,
                            pointBackgroundColor: "blue"
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            </script>
        </div>
    </div>
</div>