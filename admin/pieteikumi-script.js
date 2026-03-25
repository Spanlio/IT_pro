console.log("JS darbojas!")

let edit = false
let tabulaPiet = document.querySelector("#pieteikumi")


if (!tabulaPiet) {
    console.log("pieteikumi script skipped");
} else {

    document.querySelector('#new-btn').addEventListener('click', showModal)
    document.querySelector('.close-modal').addEventListener('click', hideModal)
    document.addEventListener('click', handleTableClick)
    document.querySelector('#pieteikumaForma').addEventListener('submit', handleFormSubmit)

    function showModal() {
        document.querySelector('.modal').style.display = 'flex'
    }

    function hideModal() {
        document.querySelector('.modal').style.display = 'none'
        document.querySelector('#pieteikumaForma').reset()
        edit = false
    }

    fetchPieteikumi()

    async function fetchPieteikumi() {
        try {
            const response = await fetch('api/pieteikumi-api.php')
            const pieteikumi = await response.json()

            let template = ""
            pieteikumi.forEach(p => {

                const rawDateTime = p.datums; // "2026-03-07 15:25:03"

                if (rawDateTime) {
                    // Split date and time
                    const [datePart, timePart] = rawDateTime.split(" "); // ["2026-03-07", "15:25:03"]

                    // Split date
                    const [year, month, day] = datePart.split("-"); // ["2026","03","07"]

                    // Split time and take only hours and minutes
                    const [hours, minutes] = timePart.split(":"); // ["15","25","03"]

                    // Combine into desired format
                    p.datums = `${day}.${month}.${year} ${hours}:${minutes}`; // "07.03.2026 15:25"
                }

                template += `
                        <tr data-id="${p.pieteikums_id}">
                            <td>${p.pieteikums_id}</td>
                            <td>${p.vards}</td>
                            <td>${p.uzvards}</td>
                            <td>${p.epasts}</td>
                            <td>${p.talrunis}</td>
                            <td>${p.datums}</td>
                            <td>${p.statuss}</td>
                            <td>
                                <a class="pieteikums-item btn-edit"><i class = "fa fa-edit"></i></a>
                                <a class="pieteikums-delete btn-delete"><i class = "fa fa-trash"></i></a>
                            </td>
                        
                        </tr>
            `
            })
            tabulaPiet.innerHTML = template


        } catch (err) {
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
            await editPieteikums(id)
        }

        if (deleteBtn) {
            e.preventDefault()
            const row = deleteBtn.closest('tr')
            const id = row.getAttribute('data-id')
            // console.log(id)

            if (confirm("Vai tiešām vēlies dzēst šo pieteikumu?")) {
                await deletePieteikums(id)
                await fetchPieteikumi()
            }
        }
    }
    async function editPieteikums(id) {
        try {
            const response = await fetch(`api/pieteikumi-api.php?id=${id}`)
            const pieteikums = await response.json()

            document.querySelector('#vards').value = pieteikums.vards
            document.querySelector('#uzvards').value = pieteikums.uzvards
            document.querySelector('#epasts').value = pieteikums.epasts
            document.querySelector('#talrunis').value = pieteikums.talrunis
            document.querySelector('#apraksts').value = pieteikums.apraksts
            document.querySelector('#statuss').value = pieteikums.statuss
            document.querySelector('#piet_ID').value = pieteikums.pieteikums_id

            edit = true
            const ipEl = document.querySelector('#ip');
            const pedejasIzmainasEl = document.querySelector('#pedejasIzmainas');
            // Current date/time
            const now = new Date();

            // Helper to pad numbers
            const pad = (num) => num.toString().padStart(2, "0");

            // Format as "YYYY-MM-DD HH:MM:SS"
            pieteikums.pedejasIzmainas = `${now.getFullYear()}-${pad(now.getMonth() + 1)}-${pad(now.getDate())} ${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;

            // Example output: "2026-03-19 15:47:03"

            const formatLV = (dateStr) => new Date(dateStr).toLocaleString('lv-LV', {
                day: '2-digit', month: '2-digit', year: 'numeric',
                hour: '2-digit', minute: '2-digit', hour12: false
            });

            // Show creation date with IP
            if (pieteikums.datums) {
                ipEl.innerHTML = `Pieteikums izveidots: ${formatLV(pieteikums.datums)} (IP: ${pieteikums.ip})`;
                ipEl.style.display = 'flex';
            }

            // Show last modification
            if (pieteikums.pedejasIzmainas) {
                pedejasIzmainasEl.innerHTML = `Pēdējās izmaiņas: ${formatLV(pieteikums.pedejasIzmainas)}`;
                pedejasIzmainasEl.style.display = 'flex';
            }
            showModal()
        } catch (err) {
            console.error(err)
        }
    }

    async function handleFormSubmit(e) {
        e.preventDefault()

        const formData = {
            vards: document.querySelector('#vards').value,
            uzvards: document.querySelector('#uzvards').value,
            epasts: document.querySelector('#epasts').value,
            talrunis: document.querySelector('#talrunis').value,
            apraksts: document.querySelector('#apraksts').value,
            statuss: document.querySelector('#statuss').value
        }

        const id = document.querySelector('#piet_ID').value
        const method = edit ? 'PUT' : 'POST'
        const url = edit ? `api/pieteikumi-api.php?id=${id}` : 'api/pieteikumi-api.php'

        try {
            await fetch(url, {
                method: method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            })

            hideModal()
            await fetchPieteikumi()
        } catch (err) {
            console.log(err)
        }
    }

    // async function deletePieteikums(id) {
    //     try {
    //         await fetch(`api/pieteikumi-api.php?id=${id}`, {
    //             method: 'DELETE'
    //         })
    //     } catch (err) {
    //         alert('Neizdevās dzēst pieteikumu!')
    //         console.error(er)
    //     }
    // }

    async function deletePieteikums(id) {
        try {
            const res = await fetch(`api/pieteikumi-api.php?id=${id}`, { method: 'DELETE' });
            const data = await res.json();

            // Refresh table after deletion
            await fetchPieteikumi();
        } catch (err) {
            console.error(err);
        }
    }
    // PROOOOOOOOOOOOOOOOOOOOO
}
