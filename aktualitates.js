console.log("JS aktualitates lietotajs ieladets");

let searchInput = document.querySelector("#search");

if (searchInput) {
    // sis search ir tikai aktualitates.php, tapec so vajag lai indexa nerada kludu
    searchInput.addEventListener("input", () => {
        loadPosts({
            containerSelector: "#aktualitates-container",
            limit: 12,
            page: 1
        });
    });
}

async function loadPosts({
    containerSelector,
    limit = 6,
    page = 1
} = {}) {

    const container = document.querySelector(containerSelector);
    if (!container) return;

    try {
        const response = await fetch(
            `admin/api/aktualitates-api.php?limit=${limit}&page=${page}`
        );

        const data = await response.json();

        container.innerHTML = "";

        data.forEach(post => {

            const div = document.createElement("div");
            div.classList.add("blog-card");

            div.innerHTML = `
                <img src="${post.attels}">
    
                <div class="blog-overlay">
                    <h2>${post.virsraksts}</h2>
                    <p>${post.iss_apraksts}</p>
                    <a href="aktualitate.php?id=${post.id}" class="btn">
                        Lasīt vairāk
                    </a>
                </div>
            `;

            container.appendChild(div);
        });

    } catch (error) {
        console.error("Kļūda:", error);
    }
}
//  render posts function


function renderPosts(posts) {
    let container = document.querySelector("#aktualitates-container");

    if (!container) return;

    container.innerHTML = "";



    if (posts.length === 0) {
        container.innerHTML = "<p>Nekas netika atrasts</p>";
        return;
    }

    posts.forEach(item => {
        container.innerHTML += `
            <div class="blog-card">
                <img src="${item.attels}">
                <h2>${item.virsraksts}</h2>
                <p>${item.iss_apraksts}</p>
                <a href="aktualitate.php?id=${item.id}" class="btn">
                    Lasīt vairāk
                </a>
            </div>
        `;
    });
}

function renderPagination(total, current) {
    let container = document.querySelector("#pagination");

    if (!container) return;

    container.innerHTML = "";

    for (let i = 1; i <= total; i++) {
        container.innerHTML += `
            <button onclick="loadPosts({
                containerSelector: '#aktualitates-container',
                limit: 12,
                page: ${i}
            })"> 
                class="${i === current ? 'active' : ''}">
                ${i}
            </button>
        `;
    }
    // console.log("TOTAL PAGES:", total);
}


let postsContainerHome = document.querySelector("#aktualitates-container-home");

if (postsContainerHome) {
    // console.log("ieladeti posti: " + postsContainer)
    loadPosts({
        containerSelector: "#aktualitates-container-home",
        limit: 3
    });
}

let postsContainer = document.querySelector("#aktualitates-container");

if (postsContainer) {
    // console.log("ieladeti posti: " + postsContainer)
    loadPosts({
        containerSelector: "#aktualitates-container",
        limit: 12,
        page: 1
    });
}