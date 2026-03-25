

let editLietotaju = false
let tabula = document.querySelector("#lietotajs")


console.log(tabula)

if (!tabula) {
    console.log("lietotaji script skipped");
} else {
    console.log("lietotaji JS darbojas!")
    document.querySelector('#new-btn').addEventListener('click', showModal)
    document.querySelector('.close-modal').addEventListener('click', hideModal)
    document.addEventListener('click', handleTableClick)
    document.querySelector('#pieteikumaFormaLietotajs').addEventListener('submit', handleFormSubmit)

    // parole hide
    const parolesLogs = document.querySelector('#parole');
    document.querySelector('#paraditParoli').addEventListener('click', showParole);

    function showParole() {
        if (parolesLogs) {
            // check current display style
            if (parolesLogs.style.display === 'none' || parolesLogs.style.display === '') {
                // show it
                parolesLogs.style.display = 'block'; // or 'flex' if you really want
            } else {
                // hide it
                parolesLogs.style.display = 'none';
            }
        }
    }

        function showModal() {
            document.querySelector('.modal').style.display = 'flex';

            // Only set the date for new users, not when editing
            if (!editLietotaju) {
                const today = new Date().toISOString().split('T')[0]; // format: YYYY-MM-DD
                document.querySelector('#regDatums').value = today;
            }
        }

    function hideModal() {
        document.querySelector('.modal').style.display = 'none'
        document.querySelector('#pieteikumaFormaLietotajs').reset()
        editLietotaju = false
    }

    fetchLietotaji()

    async function fetchLietotaji() {
        try {
            const response = await fetch('api/lietotaji-api.php')
            const lietotaji = await response.json()

            let template = ""
            lietotaji.forEach(p => {
                // Convert from YYYY-MM-DD to DD.MM.YYYY
                const rawDate = p.regDatums; // "2026-03-18"
                if (rawDate) {
                    const parts = rawDate.split("-"); // ["2026","03","18"]
                    p.regDatums = `${parts[2]}.${parts[1]}.${parts[0]}`; // "18.03.2026"
                }

                template += `
                        <tr data-id="${p.lietotajs_id}">
                        <td>${p.lietotajs_id}</td>
                        <td>${p.lietotajvards}</td>
                        <td>${p.vards}</td>
                        <td>${p.uzvards}</td>
                        <td>${p.epasts}</td>
                        <td>${p.loma}</td>
                        <td>${p.regDatums}</td>
                            <td>
                                <a class="pieteikums-item btn-edit"><i class = "fa fa-edit"></i></a>
                                <a class="pieteikums-delete btn-delete"><i class = "fa fa-trash"></i></a>
                            </td>
                        
                        </tr>
            `
            })

            tabula.innerHTML = template
        } catch (err) {
            alert("Neizdevās ielādēt datus. Mēģiniet vēlreiz!")
            console.error(err)
        }
    }

    async function handleTableClick(e) {
        const editBtn = e.target.closest('.pieteikums-item')
        const deleteBtn = e.target.closest('.pieteikums-delete')

        if (editBtn) {
            e.preventDefault()
            const row = editBtn.closest('tr')
            const id = row.getAttribute('data-id')
            // console.log(id)
            await editLietotajs(id)
        }

        if (deleteBtn) {
            e.preventDefault()
            const row = deleteBtn.closest('tr')
            const id = row.getAttribute('data-id')
            // console.log(id)

            if (confirm("Vai tiešām vēlies dzēst šo pieteikumu?")) {
                await deleteLietotajs(id)
                await fetchLietotaji()
            }
        }
    }
    async function editLietotajs(id) {
        try {
            const response = await fetch(`api/lietotaji-api.php?id=${id}`)
            const pieteikums = await response.json()

            document.querySelector('#lietotajvards').value = pieteikums.lietotajvards
            document.querySelector('#vards').value = pieteikums.vards
            document.querySelector('#uzvards').value = pieteikums.uzvards
            document.querySelector('#epasts').value = pieteikums.epasts
            document.querySelector('#parole').value = ""
            document.querySelector('#loma').value = pieteikums.loma
            document.querySelector('#regDatums').value = pieteikums.regDatums
            document.querySelector('#lietotajs_id').value = pieteikums.lietotajs_id

            editLietotaju = true
            showModal()
        } catch (err) {
            alert("Neizdevās ielādēt pieteikumu!")
            console.error(err)
        }
    }

    async function handleFormSubmit(e) {
        e.preventDefault()

        const formData = {
            lietotajvards: document.querySelector('#lietotajvards').value,
            vards: document.querySelector('#vards').value,
            uzvards: document.querySelector('#uzvards').value,
            epasts: document.querySelector('#epasts').value,
            parole: document.querySelector('#parole').value,
            loma: document.querySelector('#loma').value,
            regDatums: document.querySelector('#regDatums').value,
            lietotajs_id: document.querySelector('#lietotajs_id').value

        }

        const id = document.querySelector('#lietotajs_id').value
        const method = editLietotaju ? 'PUT' : 'POST'
        const url = editLietotaju ? `api/lietotaji-api.php?id=${id}` : 'api/lietotaji-api.php'

        try {
            await fetch(url, {
                method: method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            })

            hideModal()
            await fetchLietotaji()
        } catch (err) {
            alert('Neizdevās saglabāt pieteikumu!')
            console.log(err)
        }
    }

    async function deleteLietotajs(id) {
        try {
            await fetch(`api/lietotaji-api.php?id=${id}`, {
                method: 'DELETE'
            })
        } catch (err) {
            alert('Neizdevās dzēst pieteikumu!')
            console.error(err)
        }
    }

    // pro-script.js






document.addEventListener("DOMContentLoaded", () => {
    loadProUsers();
});

async function loadProUsers() {
    const tbody = document.getElementById("pieteikumi");
    if (!tbody) return;

    try {
        const response = await fetch("pro-api.php");
        if (!response.ok) throw new Error("Neizdevās ielādēt datus");

        const data = await response.json();

        // Clear existing rows
        tbody.innerHTML = "";

        data.forEach(user => {
            const tr = document.createElement("tr");

            tr.innerHTML = `
                <td>${user.id}</td>
                <td>${user.epasts}</td>
                <td>${user.maksajuma_reference}</td>
                <td>${user.laiks}</td>
                <td>${user.termins}</td>
            `;

            tbody.appendChild(tr);
        });

    } catch (error) {
        console.error(error);
    }
}

}

