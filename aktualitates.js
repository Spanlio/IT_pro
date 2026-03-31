console.log("JS aktualitates lietotajs ieladets");

let searchInput = document.querySelector("#search");

if (searchInput) {
    // sis search ir tikai aktualitates.php, tapec so vajag lai indexa nerada kludu
    searchInput.addEventListener("input", () => {
        loadPosts({
            containerSelector: "#aktualitates-container",
            limit: 12,
            page: 1,
            search: searchInput.value
        });
    });
}

async function loadPosts({
    containerSelector,
    page = 1,
    limit = 12,
    search = "",
    latest = false
} = {}) {

      console.log("loadPosts called");

    const container = document.querySelector(containerSelector);
    console.log("Selector:", containerSelector);
    if (!container) return;

    let url = "admin/api/aktualitates-lietotajs-api.php";

    if (latest) {
        url += "?latest=1";
    } else {
        url += `?page=${page}&limit=${limit}`;
        if (search) {
            url += `&search=${encodeURIComponent(search)}`;
        }
    }
    console.log("URL:", url);
    try {
        const response = await fetch(url);
        const data = await response.json();

        // console.log(data);

        container.innerHTML = "";

        // works for both: latest (array) and pagination ({data: []})
        const posts = Array.isArray(data) ? data : data.data;

        if (!Array.isArray(data)) {
            renderPagination(data.totalPages, data.page);
        }

        if (!posts || posts.length === 0) {
            container.innerHTML = "<p>Nav atrastas aktualitātes</p>";
            return;
        }

        posts.forEach(post => {
            const div = document.createElement("div");
            div.classList.add("blog-card");

            div.innerHTML = `
                <img src="${post.attels}">
                <div class="blog-overlay">
                    <h2>${post.virsraksts}</h2>
                    <p>${post.iss_apraksts}</p>
                    <a href="aktualitate.php?id=${post.aktualitate_id}" class="btn">
                        Lasīt vairāk
                    </a>
                </div>
            `;

            container.appendChild(div);
        });

    } catch (error) {
        console.error(error);
    }
}

function renderPagination(totalPages, currentPage) {
    let container = document.querySelector("#pagination");
    container.innerHTML = "";

    for (let i = 1; i <= totalPages; i++) {
        let btn = document.createElement("button");
        btn.textContent = i;

        if (i === currentPage) {
            btn.classList.add("active");
        }

        btn.addEventListener("click", () => {
            loadPosts({
                containerSelector: "#aktualitates-container",
                page: i,
                limit: 12,
                search: document.querySelector("#search")?.value || ""
            });
        });

        container.appendChild(btn);
    }
}

loadPosts({
    containerSelector: "#aktualitates-container-home",
    latest: true
});

loadPosts({
    containerSelector: "#aktualitates-container",
    page: 1,
    limit: 12
});

