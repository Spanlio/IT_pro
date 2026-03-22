const API_URL = "admin/api/aktualitates-api.php";


let data = [];
let currentPage = 1;
const perPage = 12;

// LOAD DATA
fetch(API_URL)
    .then(res => res.json())
    .then(res => {
        console.log("API DATA:", res); // DEBUG
        data = res.filter(a => a.statuss === "publicets");
        render();
    })
    .catch(err => console.error("Fetch error:", err));

// RENDER
function render() {
    let search = document.getElementById("search").value.toLowerCase();

    let filtered = data.filter(a =>
        a.virsraksts.toLowerCase().includes(search) ||
        a.pilns_apraksts.toLowerCase().includes(search)
    );

    let start = (currentPage - 1) * perPage;
    let items = filtered.slice(start, start + perPage);

    let html = "";

    items.forEach(a => {
        html += `
            <div class="card">
                <img src="${a.attels}" alt="">
                <h3>${a.virsraksts}</h3>
                <p>${a.iss_apraksts}</p>
                <button onclick="readMore(${a.id})">Lasīt vairāk</button>
            </div>
        `;
    });

    document.getElementById("container").innerHTML = html;

    renderPagination(filtered.length);
}

// SEARCH
document.getElementById("search").addEventListener("input", () => {
    currentPage = 1;
    render();
});

// PAGINATION
function renderPagination(total) {
    let pages = Math.ceil(total / perPage);
    let html = "";

    for (let i = 1; i <= pages; i++) {
        html += `<button onclick="goPage(${i})">${i}</button>`;
    }

    document.getElementById("pagination").innerHTML = html;
}

function goPage(p) {
    currentPage = p;
    render();
}

// READ MORE
function readMore(id) {
    fetch(API_URL + "?id=" + id)
        .then(res => res.json())
        .then(a => {
            alert(a.virsraksts + "\n\n" + a.pilns_apraksts);
        });
}