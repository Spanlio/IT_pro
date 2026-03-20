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
            <th>E-pasts</th>
            <th>Maksajuma reference</th>
            <th>Laiks</th>
            <th>Termiņš</th>
            <th></th>
        </tr>
        <tbody id="pro_lietotaji">

        </tbody>
    </table>
</div>
</body>

</html>