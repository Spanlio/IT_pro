<?php
    require "header.php"
?>

<table id="aktualitates">
    <thead>
        <tr>
            <th>Virsraksts</th>
            <th>Attēls</th>
            <th>Autors</th>
            <th>Statuss</th>
            <th>Izveidošanas datums</th>
            <th>Darbības</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<div class="modal" id="aktualitates-modal" data-target="#aktualitates-modal">
    <div class="modal-box">
        <div class="close-modal" data-target="#modal-admin">
            <i class="fa-solid fa-square-xmark"></i>
        </div>

        <h2>Aktualitāte</h2>

        <form id="aktualitatesForma">

            <div class="form-elements">

                <label>Virsraksts:</label>
                <input type="text" id="virsraksts" required>

                <label>Īss apraksts:</label>
                <input type="text" id="iss_apraksts" required>

                <label>Pilns apraksts:</label>
                <textarea id="pilns_apraksts" required></textarea>

                <label>Attēls (ceļš):</label>
                <input type="text" id="attels">

                <label>Statuss:</label>
                <select id="statuss">
                    <option value="melnraksts">Melnraksts</option>
                    <option value="publicets">Publicēts</option>
                </select>

                <input type="hidden" id="aktualitate_id">

            </div>

            <button type="submit" class="btn active">Saglabāt</button>

        </form>
    </div>
</div>

<div class="modal" id="delete-modal">
    <div class="modal-box delete-box">

        <div class="delete-icon">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>

        <h3>Dzēst aktualitāti</h3>

        <p id="delete-text">
            Vai tiešām vēlies dzēst šo aktualitāti?
        </p>

        <div class="delete-actions">
            <button id="confirm-delete" class="btn-delete-main">
                <i class="fa-solid fa-trash"></i> Dzēst
            </button>

            <button id="cancel-delete" class="btn-cancel">
                Atcelt
            </button>
        </div>

    </div>
</div>