
// let tabula = document.querySelector("#aktualitates-table")

// console.log(tabula)

// if (!tabula) {
//     console.log("aktualitates script skipped");
// } else {




    // sis ir prieks bilzu path db - ../images/imagename.png, ja izmanto jau esošo folderi

    let tabula = document.querySelector("#aktualitates tbody");

fetch("api/aktualitates-api.php")
    .then(res => res.json())
    .then(data => {
        renderTable(data);
    });

function renderTable(data) {
    let html = "";

    data.forEach(a => {
        html += `
        <tr>
            <td>${a.virsraksts}</td>
            
            <td>
                <img src="${a.attels}" style="width:80px; height:50px; object-fit:cover;">
            </td>

            <td>${a.vards} ${a.uzvards}</td>

            <td>
                <span class="${a.statuss === 'publicets' ? 'status-green' : 'status-gray'}">
                    ${a.statuss}
                </span>
            </td>

            <td>${formatDate(a.izveidots)}</td>

            <td>
                <button class="fa fa-edit btn-edit" onclick="edit(${a.id})"></button>
                <button class="fa fa-trash btn-delete" onclick="removeItem(${a.id})"></button>
            </td>
        </tr>
        `;
    });

    tabula.innerHTML = html;
}

function formatDate(date) {
    let d = new Date(date);
    return d.toLocaleString("lv-LV");
}