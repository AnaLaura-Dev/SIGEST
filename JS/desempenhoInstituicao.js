async function carregarDesempenhos() {
    try {
        const resposta = await fetch("/php/listarDesempenhoInstituicao.php");
        const dados = await resposta.json();

        if (dados.status !== "ok") {
            alert("Erro: " + dados.mensagem);
            return;
        }

        const tbody = document.querySelector("#tabelaDesempenho tbody");
        tbody.innerHTML = "";

        dados.avaliacoes.forEach(av => {
            const tr = document.createElement("tr");

      tr.innerHTML = `
    <td>${av.nomeAluno}</td>
    <td>${av.emailAluno}</td>
    <td>${av.tituloVaga}</td>
    <td>${av.nomeEmpresa}</td>
    <td>${av.dataAvaliacao}</td>
    <td><button onclick="verDetalhes(${av.idDesempenho})">Abrir</button></td>
`;


            tbody.appendChild(tr);
        });

    } catch (erro) {
        console.error("Erro ao carregar:", erro);
    }
}

function verDetalhes(id) {
    window.location.href = "/Instituição/verDesempenho.html?id=" + id;
}

carregarDesempenhos();
