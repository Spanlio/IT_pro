<?php
require "header.php";
if (!isset($_SESSION["lietotajvards_divisaldie"])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION["lietotajs_loma"] !== "admin") {
    http_response_code(403); // Forbidden
    echo "Tev nav piekļuves šai lapai!";
    exit;
}
?>

<div class="admin-top">
    <div>
        <input type="text" pl aceholder="Meklēšana...">
        <a class="btn-sm">Meklēt</a>
    </div>
    <a class="btn-sm" id="new-btn">
        <i class="fa fa-plus"></i> Pievienot jaunu
    </a>
</div>

<div class="admin-main">
    <table>
        <tr>
            <th>ID</th>
            <th>Lietotājvārds</th>
            <th>Vārds</th>
            <th>Uzvārds</th>
            <th>E-pasts</th>
            <th>Loma</th>
            <th>Reģ. datums</th>
            <th></th>
        </tr>
        <tbody id="lietotajs">

        </tbody>
    </table>
</div>

<div class="modal" id="modal-admin">
    <div class="modal-box">
        <div class="close-modal" data-target="#modal-ticket">
            <i class="fa-solid fa-square-xmark"></i>
        </div>
        <h2>Lietotājs</h2>
        <form id="pieteikumaFormaLietotajs">
            <div class="form-elements">
                <!-- lietotajvards -->
                <label data-lang-key="label_username">Lietotājāvārds:</label>
                <input type="text" id="lietotajvards" required>
                <!-- vards -->
                <label data-lang-key="label_name">Vārds:</label>
                <input type="text" id="vards" required>
                <!-- uzvards -->
                <label data-lang-key="label_surname">Uzvārds:</label>
                <input type="text" id="uzvards" required>
                <!-- epasts -->
                <label data-lang-key="label_email">E-pasts:</label>
                <input type="email" id="epasts" required>
                <!-- parole -->
                <label data-lang-key="label_email">Parole:</label>
                <button type="button" id="paraditParoli">
                    Izveidot jaunu paroli <i class="fa-solid fa-arrow-turn-down"></i>
                </button>
                <input type="password" id="parole" placeholder="****" required>
                <!-- loma -->
                <label data-lang-key="label_loma">Loma:</label>
                <select id="loma">
                    <option value="admin">Admin</option>
                    <option value="moder">Moder</option>
                </select>
                <!-- datums -->
                <input type="hidden" id="lietotajs_id">
                <input type="hidden" id="regDatums">
            </div>


            <button type="submit" class="btn active">Iesniegt</button>
        </form>
    </div>
</div>
</body>

</html>