console.log("aktualitates script darbojas!");

let aktualitatesTabula = document.querySelector("#aktualitates");

if (!aktualitatesTabula) {
    console.log("aktualitates script skipped");
} else {

    let tbody = aktualitatesTabula.querySelector("tbody");

    let edit = false;
    let deleteId = null;
    let isSubmitting = false;

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
                        <img src="${a.attels ? '../uploaded_files/' + a.attels : '../images/no-image.png'}"
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
            document.querySelector("#statuss").value = aktualitate.statuss;
            // document.querySelector("#pilns_apraksts").value

            showModal(aktualitate.pilns_apraksts);

        } catch (err) {
            console.error(err);
            showNotification("Kļūda atverot aktualitāti!", "error");
        }
    }

    // =====================
    // DELETE
    // =====================
    window.deleteAktualitate = function (id) {
        deleteId = id;

        let modal = document.querySelector("#delete-modal");

        if (!modal) {
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

        tinymce.triggerSave();

        if (isSubmitting) return;
        isSubmitting = true;

        const btn = this.querySelector("button[type='submit']");
        if (btn) btn.disabled = true;

        tinymce.triggerSave();

        let pilns = document.querySelector("#pilns_apraksts").value;

        let aktualitate = new FormData();
        aktualitate.append("id", document.querySelector("#aktualitate_id").value);
        aktualitate.append("virsraksts", document.querySelector("#virsraksts").value);
        aktualitate.append("iss_apraksts", document.querySelector("#iss_apraksts").value);
        aktualitate.append("pilns_apraksts", pilns);
        aktualitate.append("statuss", document.querySelector("#statuss").value);

        let fileInput = document.querySelector("#attels");

        if (fileInput.files[0]) {
            const file = fileInput.files[0];

            // bildes izmera checks (2MB)
            if (file.size > 2 * 1024 * 1024) {
                showNotification("Attēls pārāk liels (max 2MB)", "error");
                isSubmitting = false;
                if (btn) btn.disabled = false;
                return;
            }

            aktualitate.append("attels", file);
        }

        try {
            let res = await fetch("api/aktualitates-api.php", {
                method: "POST",
                body: aktualitate
            });

            let data = await res.json();

            hideModal();
            this.reset();

            showNotification(data.message, data.status);

            await getAktualitates();

        } catch (err) {
            console.error(err);
            showNotification("Kļūda saglabājot!", "error");
        } finally {
            isSubmitting = false;
            if (btn) btn.disabled = false;
        }
    });
    // =====================
    // MODAL +  TINYMCE
    // =====================
    document.querySelector("#new-btn")?.addEventListener("click", () => {
        edit = false;
        document.querySelector("#aktualitatesForma").reset();
        document.querySelector("#aktualitate_id").value = "";
        showModal("");
    });

    document.querySelector(".close-modal")?.addEventListener("click", hideModal);

    function showModal(content = "") {
        let modal = document.querySelector("#aktualitates-modal");

        if (!modal) {
            showNotification("Kļūda ar logu!", "error");
            return;
        }

        modal.style.display = "flex";

        if (tinymce.get("pilns_apraksts")) {
            tinymce.get("pilns_apraksts").remove();
        }

        tinymce.init({
            selector: "#pilns_apraksts",
            height: 300,
            menubar: false,
            plugins: ["lists", "link", "code"],
            toolbar: "undo redo | bold italic | bullist numlist | link",
            setup: function (editor) {
                editor.on("init", function () {
                    editor.setContent(content || "");
                });
            }
        });
    }

    function hideModal() {
        document.querySelector("#aktualitates-modal").style.display = "none";
    }

    // =====================
    // NOTIFICATIONS
    // =====================
    function showNotification(message, type = "success", duration = 4000) {

        const container = document.querySelector("#notification-container");
        if (!container) return;

        const icon = type === "success"
            ? "fa-circle-check"
            : "fa-circle-xmark";

        const notif = document.createElement("div");
        notif.className = `notification ${type}`;

        notif.innerHTML = `
        <i class="fa-solid ${icon}"></i>
        <span>${message}</span>
        `;

        container.appendChild(notif);

        setTimeout(() => notif.classList.add("show"), 10);

        setTimeout(() => {
            notif.classList.remove("show");
            setTimeout(() => notif.remove(), 500);
        }, duration);
    }

    // ========================== create modal function ==============
    document.getElementById("addAktualitateBtn").addEventListener("click", () => {
        openCreateModal();
    });

    function openCreateModal() {
        edit = false; // nosaka edit vai create, saja gadijuma bus creaate
        document.querySelector("#aktualitatesForma").reset();
        document.querySelector("#aktualitate_id").value = "";
        showModal("");
    }

    // =====================
    // INIT
    // =====================
    getAktualitates();
}