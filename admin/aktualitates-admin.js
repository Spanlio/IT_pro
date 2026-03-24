console.log("aktualitates script darbojas!");

let aktualitatesTabula = document.querySelector("#aktualitates");

if (!aktualitatesTabula) {
    console.log("aktualitates script skipped");
} else {

    let tbody = aktualitatesTabula.querySelector("tbody");

    let edit = false;

    // =====================
    // LOAD DATA (like your other scripts)
    // =====================
    async function getAktualitates() {
        let res = await fetch("api/aktualitates-api.php");
        let data = await res.json();

        let html = "";

        data.forEach(a => {
            html += `
            <tr>
                <td>${a.virsraksts}</td>
                
                <td>
                    <img src="${a.attels ? a.attels : '../images/no-image.png'}"
                         style="width:80px; height:50px; object-fit:cover;">
                </td>

                <td>${a.vards ? a.vards + " " + a.uzvards : "Nav autora"}</td>

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

        tbody.innerHTML = html;
    }

    // =====================
    // DATE
    // =====================
    function formatDate(date) {
        let d = new Date(date);
        return d.toLocaleString("lv-LV");
    }

    // =====================
    // EDIT
    // =====================
    window.editAktualitate = async function (id) {
        let res = await fetch("api/aktualitates-api.php?id=" + id);
        let aktualitate = await res.json();

        edit = true;

        document.querySelector("#aktualitate_id").value = aktualitate.id;
        document.querySelector("#virsraksts").value = aktualitate.virsraksts;
        document.querySelector("#iss_apraksts").value = aktualitate.iss_apraksts;
        document.querySelector("#pilns_apraksts").value = aktualitate.pilns_apraksts;
        document.querySelector("#attels").value = aktualitate.attels;
        document.querySelector("#statuss").value = aktualitate.statuss;

        showModal();
    }

    // =====================
    // DELETE
    // =====================
    window.deleteAktualitate = async function (id) {
        let deleteId = null;

        // open modal
        window.deleteAktualitate = function (id) {
            deleteId = id;

            document.querySelector("#delete-modal").style.display = "flex";
        };

        await fetch("api/aktualitates-api.php", {
            method: "DELETE",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id })
        });

        getAktualitates(); // 🔥 like your scripts (refresh table only)
    }

    // delete accept

    document.querySelector("#confirm-delete").addEventListener("click", async () => {

        if (!deleteId) return;

        await fetch("api/aktualitates-api.php", {
            method: "DELETE",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id: deleteId })
        });

        document.querySelector("#delete-modal").style.display = "none";

        deleteId = null;

        getAktualitates(); // refresh table
    });


    document.querySelector("#cancel-delete").addEventListener("click", () => {
        document.querySelector("#delete-modal").style.display = "none";
        deleteId = null;
    });

    // =====================
    // SAVE
    // =====================
    document.querySelector("#aktualitatesForma").addEventListener("submit", async function (e) {
        e.preventDefault();

        let aktualitate = {
            id: document.querySelector("#aktualitate_id").value,
            virsraksts: document.querySelector("#virsraksts").value,
            iss_apraksts: document.querySelector("#iss_apraksts").value,
            pilns_apraksts: document.querySelector("#pilns_apraksts").value,
            attels: document.querySelector("#attels").value,
            statuss: document.querySelector("#statuss").value
        };

        await fetch("api/aktualitates-api.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(aktualitate)
        });

        hideModal();
        this.reset();

        getAktualitates(); // 🔥 same pattern
    });

    // =====================
    // MODAL
    // =====================
    document.querySelector("#new-btn")?.addEventListener("click", () => {
        edit = false;
        document.querySelector("#aktualitatesForma").reset();
        document.querySelector("#aktualitate_id").value = "";
        showModal();
    });

    document.querySelector(".close-modal")?.addEventListener("click", hideModal);

    function showModal() {
        let modal = document.querySelector("#aktualitates-modal");

        if (!modal) {
            console.error("Modalais edit aktualitates logs not found!");
            return;
        }

        modal.style.display = "flex";
    }

    function hideModal() {
        document.querySelector("#aktualitates-modal").style.display = "none";
    }

    // =====================
    // INIT
    // =====================
    getAktualitates();
}