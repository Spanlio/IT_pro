console.log("aktualitates script darbojas!");

let aktualitatesTabula = document.querySelector("#aktualitates");

if (!aktualitatesTabula) {
    console.log("aktualitates script skipped");
} else {

    let tbody = aktualitatesTabula.querySelector("tbody");

    let edit = false;

    // =====================
    // LOAD DATA
    // =====================
    async function getAktualitates() {
        try {
            let res = await fetch("api/aktualitates-api.php");

            if (!res.ok) throw new Error("Neizdevās ielādēt datus");

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

        } catch (err) {
            console.error(err);
            showNotification("Kļūda ielādējot datus!", "error");
        }
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
        try {
            let res = await fetch("api/aktualitates-api.php?id=" + id);

            if (!res.ok) throw new Error("Kļūda ielādējot ierakstu");

            let aktualitate = await res.json();

            edit = true;

            document.querySelector("#aktualitate_id").value = aktualitate.id;
            document.querySelector("#virsraksts").value = aktualitate.virsraksts;
            document.querySelector("#iss_apraksts").value = aktualitate.iss_apraksts;
            document.querySelector("#pilns_apraksts").value = aktualitate.pilns_apraksts;
            document.querySelector("#attels").value = aktualitate.attels;
            document.querySelector("#statuss").value = aktualitate.statuss;

            showModal();

        } catch (err) {
            console.error(err);
            showNotification("Kļūda atverot aktualitāti!", "error");
        }
    }

    // =====================
    // DELETE
    // =====================
    let deleteId = null;

    window.deleteAktualitate = function (id) {
        deleteId = id;

        let modal = document.querySelector("#delete-modal");

        if (!modal) {
            console.error("Delete modal not found!");
            showNotification("Kļūda ar dzēšanas logu!", "error");
            return;
        }

        modal.style.display = "flex";
    };

    document.querySelector("#confirm-delete").addEventListener("click", async () => {

        if (!deleteId) {
            showNotification("Kļūda! Mēģini vēlreiz.", "error");
            return;
        }

        try {
            let res = await fetch("api/aktualitates-api.php", {
                method: "DELETE",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ id: deleteId })
            });

            if (!res.ok) throw new Error("Delete failed");

            document.querySelector("#delete-modal").style.display = "none";

            showNotification("Aktualitāte dzēsta!", "success");

            deleteId = null;

            getAktualitates();

        } catch (err) {
            console.error(err);
            showNotification("Kļūda dzēšot!", "error");
        }
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

        try {
            let res = await fetch("api/aktualitates-api.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(aktualitate)
            });

            if (!res.ok) throw new Error("Save failed");

            hideModal();
            this.reset();

            showNotification("Saglabāts veiksmīgi!", "success");

            getAktualitates();

        } catch (err) {
            console.error(err);
            showNotification("Kļūda saglabājot!", "error");
        }
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
            console.error("Modal not found!");
            showNotification("Kļūda ar logu!", "error");
            return;
        }

        modal.style.display = "flex";
    }

    function hideModal() {
        document.querySelector("#aktualitates-modal").style.display = "none";
    }

    // =====================
    // NOTIFICATIONS
    // =====================
    function showNotification(message, type = "success", duration = 3000) {

        let container = document.querySelector("#notification-container");

        if (!container) {
            console.error("Notification container missing!");
            return;
        }

        let icon = type === "success"
            ? "fa-circle-check"
            : "fa-circle-xmark";

        let notif = document.createElement("div");
        notif.className = `notification ${type}`;

        notif.innerHTML = `
        <i class="fa-solid ${icon}"></i>
        <span>${message}</span>
    `;

        container.appendChild(notif);

        setTimeout(() => {
            notif.style.opacity = "0";
            notif.style.transform = "translateX(20px)";
            setTimeout(() => notif.remove(), 300);
        }, duration);
    }

    // =====================
    // INIT
    // =====================
    getAktualitates();
}