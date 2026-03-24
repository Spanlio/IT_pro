
let tabula = document.querySelector("#aktualitates-table")

console.log(tabula)

if (!tabula) {
    console.log("aktualitates script skipped");
} else {

    const API_URL = "api/aktualitates-api.php";

    let data = [];

    // LOAD
    fetch(API_URL)
        .then(res => res.json())
        .then(res => {
            data = res;
            renderTable();
        })
        .catch(err => console.error(err));

    // RENDER TABLE
    function renderTable() {
        let html = "";

        data.forEach(a => {
            html += `
        <tr>
            <td>${a.id}</td>
            <td>${a.virsraksts}</td>
            <td>${a.statuss}</td>
            <td>
                <button onclick="edit(${a.id})">Edit</button>
                <button onclick="removeItem(${a.id})">Delete</button>
            </td>
        </tr>
        `;
        });

        document.getElementById("table-body").innerHTML = html;
    }

    function edit(id) {
        fetch(API_URL + "?id=" + id)
            .then(res => res.json())
            .then(a => {
                console.log("EDIT:", a);

                // later → open modal and fill form
            });
    }

    function removeItem(id) {
        if (!confirm("Dzēst šo ierakstu?")) return;

        fetch(API_URL, {
            method: "DELETE",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id: id })
        })
            .then(res => res.json())
            .then(() => {
                data = data.filter(a => a.id !== id);
                renderTable();
            });
    }
}
