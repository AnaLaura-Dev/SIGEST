function verDesempenho(id) {
    fetch(`verDesempenho.php?id=${id}`)
        .then(res => res.json())
        .then(data => {
            if (data.status === "erro") {
                alert(data.mensagem);
                return;
            }

            document.getElementById("nomeAluno").innerText = data.dados.nomeAluno;
            document.getElementById("emailAluno").innerText = data.dados.emailAluno;
            document.getElementById("empresa").innerText = data.dados.nomeEmpresa;
            document.getElementById("dataAvaliacao").innerText = data.dados.dataAvaliacao;

            let lista = document.getElementById("listaTopicos");
            lista.innerHTML = "";

            data.topicos.forEach(t => {
                lista.innerHTML += `
                    <tr>
                        <td>${t.nomeTopico}</td>
                        <td>${t.nota}</td>
                        <td>${t.comentario}</td>
                    </tr>
                `;
            });

            document.getElementById("modalDesempenho").style.display = "block";
        });
}
