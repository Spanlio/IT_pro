
// let tabula = document.querySelector("#aktualitates-table")

// console.log(tabula)

// if (!tabula) {
//     console.log("aktualitates script skipped");
// } else {




// sis ir prieks bilzu path db - ../images/imagename.png, ja izmanto jau esošo folderi


if (document.querySelector("#aktualitates")) {



    let tabulaAktualitates = document.querySelector("#aktualitates tbody");

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
                <img src="${a.attels ? a.attels : '../images/no-image.png'}"
                style="width:80px; height:50px; object-fit:cover;">
            </td>

            <td>${a.vards} ${a.uzvards}</td>

            <td>
                <span class="${a.statuss === 'publicets' ? 'status-green' : 'status-gray'}">
                    ${a.statuss}
                </span>
            </td>

            <td>${formatDate(a.izveidots)}</td>

            <td>
                <button class="fa fa-edit btn-edit" onclick="editAktualitate(${a.id})"></button>
                <button class="fa fa-trash btn-delete" onclick="deleteAktualitate(${a.id})"></button>
            </td>
        </tr>
        `;
        });

        tabulaAktualitates.innerHTML = html;
    }

    function formatDate(date) {
        let d = new Date(date);
        return d.toLocaleString("lv-LV");
    }


    function editAktualitate(id) {
        fetch("api/aktualitates-api.php?id=" + id)
            .then(res => res.json())
            .then(aktualitate => {

                document.querySelector("#aktualitate_id").value = aktualitate.id;

                document.querySelector("#virsraksts").value = aktualitate.virsraksts;
                document.querySelector("#iss_apraksts").value = aktualitate.iss_apraksts;
                document.querySelector("#pilns_apraksts").value = aktualitate.pilns_apraksts;
                document.querySelector("#attels").value = aktualitate.attels;
                document.querySelector("#statuss").value = aktualitate.statuss;

                showModal();
            });
    }
    // aktualitates dzesana
    function deleteAktualitate(id) {
        if (!confirm("Vai tiešām dzēst šo aktualitāti?")) return;

        fetch("api/aktualitates-api.php", {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ id: id })
        })
            .then(res => res.json())
            .then(res => {
                console.log(res);
                location.reload();
            });
    }

    let editMode = false;
    let currentId = null;

    // modal show
    document.querySelector("#new-btn")?.addEventListener("click", showModal);
    // modal hide
    document.querySelector(".close-modal")?.addEventListener("click", hideModal);

    function showModal() {
        document.querySelector(".modal").style.display = "flex";
    }

    function hideModal() {
        document.querySelector(".modal").style.display = "none";
    }

    // aktualitates redigesana
    document.querySelector("#aktualitatesForma").addEventListener("submit", function (e) {
        e.preventDefault();

        let aktualitate = {
            id: document.querySelector("#aktualitate_id").value,
            virsraksts: document.querySelector("#virsraksts").value,
            iss_apraksts: document.querySelector("#iss_apraksts").value,
            pilns_apraksts: document.querySelector("#pilns_apraksts").value,
            attels: document.querySelector("#attels").value,
            statuss: document.querySelector("#statuss").value
        };

        console.log("SAVING:", aktualitate);

        fetch("api/aktualitates-api.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(aktualitate)
        })
            .then(res => res.json())
            .then(res => {
                console.log("RESPONSE:", res);
                location.reload();
            });
    });
}

