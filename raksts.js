const container = document.querySelector("#raksts-container");

// 1. Get ID
const params = new URLSearchParams(window.location.search);
const id = params.get("id");

// 2. Validate
if (!id) {
    container.innerHTML = "Raksts nav atrasts";
} else {
    fetch(`admin/api/aktualitates-lietotajs-api.php?id=${id}`)
        .then(res => res.json())
        .then(post => {
            if (!post || post.error) {
                container.innerHTML = "Raksts nav atrasts";
                return;
            }

            renderPost(post);
        })
        .catch(() => {
            container.innerHTML = "Kļūda ielādējot rakstu";
        });
}

// 3. Render
function renderPost(post) {
    const BASE_URL = "/2023/markovs/it-support-cirkel/";

    container.innerHTML = `
        <div class="raksts-wrapper">
            
            <a href="aktualitates.php" class="back-btn">← Atpakaļ</a>

            <h1 class="raksts-title">${post.virsraksts}</h1>

            <div class="raksts-meta">
                <span>${post.autors}</span>
                <span>${post.izveidots}</span>
            </div>

            <div class="raksts-image">
                <img src="${BASE_URL}uploaded_files/${post.attels}" alt="">
            </div>

            <div class="raksts-content">
                ${post.pilns_apraksts}
            </div>

        </div>
    `;
}