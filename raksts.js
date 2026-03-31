// 1. Get ID from URL
const params = new URLSearchParams(window.location.search);
const id = params.get("id");

// 2. Validate
if (!id) {
    document.querySelector("#raksts-container").innerHTML = "Raksts nav atrasts";
} else {
    loadPost(id);
}

// 3. Fetch post
function loadPost(id) {
    fetch(`/admin/api/aktualitates-lietotajs-api.php?id=${id}`)
        .then(res => res.json())
        .then(data => {
            if (!data || data.error) {
                showError();
            } else {
                renderPost(data);
            }
        })
        .catch(() => showError());
}

// 4. Render
function renderPost(post) {
    const container = document.querySelector("#raksts-container");

    container.innerHTML = `
        <h1>${post.virsraksts}</h1>

        <img src="${post.attels}" class="raksts-img">

        <div class="meta">
            <span>Autors: ${post.autors}</span>
            <span>Publicēts: ${post.izveidots}</span>
        </div>

        <div class="content">
            ${post.pilns_apraksts}
        </div>

        <a href="aktualitates.php" class="back-btn">
            ← Atpakaļ uz aktualitātēm
        </a>
    `;
}

// 5. Error UI
function showError() {
    document.querySelector("#raksts-container").innerHTML = "Raksts nav atrasts";
}