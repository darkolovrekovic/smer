document.getElementById('unos').addEventListener('submit', function (e) {
    e.preventDefault();

    const akcija = e.submitter.getAttribute('formaction');
    const formData = new FormData(this);
    const porukaDiv = document.getElementById('poruka');

    porukaDiv.innerHTML = "";

    fetch(akcija, {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.ok) {
                if (akcija.indexOf('listall') !== -1) {

                    if (!data.data || data.data.length === 0) {
                        porukaDiv.innerHTML = "<p>Baza je prazna.</p>";
                        return;
                    }

                    let tabela = `
                        <table border="1" style="border-collapse: collapse; margin-top: 20px; width: 100%; text-align: left;">
                            <thead>
                                <tr style="background-color: #f2f2f2;">
                                    <th style="padding: 8px;">ID</th>
                                    <th style="padding: 8px;">Ime smera</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                    data.data.forEach(smer => {
                        tabela += `
                            <tr style="cursor: pointer;" onclick="popuniFormu(${smer.ID}, '${smer.NAZIV}')">
                                <td style="padding: 8px;">${smer.ID}</td>
                                <td style="padding: 8px;">${smer.NAZIV}</td>
                            </tr>
                        `;
                    });

                    tabela += `</tbody></table>`;
                    porukaDiv.innerHTML = tabela;

                } else if (akcija.indexOf('find') !== -1) {
                    document.getElementById('id').value = data.data.ID;
                    document.getElementById('naziv_smera').value = data.data.NAZIV;
                    porukaDiv.innerHTML = `
                        <p style="color: rgb(253, 253, 150);">
                            Pronađeno:<br>
                            ID: ${data.data.ID}<br>
                            Ime: ${data.data.NAZIV}
                        </p>`;
                } else {
                    porukaDiv.innerHTML = `<p style="color: rgb(253, 253, 150);">Akcija uspešna!</p>`;
                    if (akcija.indexOf('create') !== -1 || akcija.indexOf('delete') !== -1) {
                        this.reset();
                    }
                }
            } else {
                porukaDiv.innerHTML = `<p style="color: red;">Greška: ${data.error}</p>`;
            }
        })
        .catch(error => {
            console.error('Greška:', error);
            porukaDiv.innerHTML = `<p style="color: red;">Greška u komunikaciji sa serverom.</p>`;
        });
});

function popuniFormu(id, naziv) {
    document.getElementById('id').value = id;
    document.getElementById('naziv_smera').value = naziv;
}