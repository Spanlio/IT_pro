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
    container.innerHTML = `
        <h1>${post.virsraksts}</h1>

        <img src="uploaded_files/${post.attels}" class="raksts-img">

        <div class="meta">
            <span><b>Autors:</b> ${post.autors}</span>
            <span><b>Publicēts:</b> ${post.izveidots}</span>
        </div>

        <div class="content">
            ${post.pilns_apraksts}
        </div>

        <a href="aktualitates.php">← Atpakaļ</a>
    `;
}