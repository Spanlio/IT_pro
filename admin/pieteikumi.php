<?php
require "header.php"
?>

<div class="admin-top">
    <div>
        <input type="text" placeholder="Meklēšana..." id="searchInput">
        <a class="btn-sm" id="meklesanaButton">Meklēt</a>
    </div>
    <a class="btn-sm" id="new-btn">
        <i class="fa fa-plus"></i> Pievienot jaunu
    </a>
</div>


<div class="admin-main">
    <table>
        <tr>
            <th>ID</th>
            <th>Vārds</th>
            <th>Uzvārds</th>
            <th>E-pasts</th>
            <th>Tālrunis</th>
            <th>Datums</th>
            <th>Statuss</th>
            <th></th>
        </tr>
        <tbody id="pieteikumi">

        </tbody>
    </table>
</div>

 <div class="modal" id="modal-admin">
        <div class="modal-box">
            <div class="close-modal" data-target="#modal-ticket">
                <i class="fa-solid fa-square-xmark"></i>
            </div>
            <h2>Pieteikums</h2>
            <form id="pieteikumaForma">
                <div class="form-elements">
                    <label data-lang-key="label_name">Vārds:</label>
                    <input type="text" id="vards" required>
                    <label data-lang-key="label_surname">Uzvārds:</label>
                    <input type="text" id="uzvards" required>
                    <label data-lang-key="label_email">E-pasts:</label>
                    <input type="email" id="epasts" required>
                    <label data-lang-key="label_phone">Tālr. nr.:</label>
                    <input type="tel" id="talrunis" pattern="[0-9]{8}" required>
                    <label data-lang-key="label_desc">Problēma / veicāmā uzdevuma apraksts:</label>
                    <textarea id="apraksts" rows="4" required></textarea>
                    <label>Statuss:</label>
                    <select id="statuss">
                        <option value="Jauns">Jauns</option>
                        <option value="Atvērts">Atvērts</option>
                        <option value="Gaida">Gaida</option>
                        <option value="Pabeigts">Pabeigts</option>
                    </select>
                    <input type="hidden" id="piet_ID">
                </div>
                <div id="ip-laiks">
                    <div>
                        <p id="ip"></p>
                    </div>
                    <div>
                        <p id="pedejasIzmainas"></p>
                    </div>
                </div>


                <button type="submit" class="btn active" >Saglabāt</button>
            </form>
        </div>
    </div>
</body>

</html>