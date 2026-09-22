<h2>Relatórios Recebidos</h2>

<table border="1" width="100%">
    <thead>
        <tr>
            <th>Aluno</th>
            <th>Matrícula</th>
            <th>Relatório</th>
            <th>Data</th>
            <th>Status</th>
            <th>Arquivo</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody id="tabela-relatorios"></tbody>
</table>

<script>
// buscar relatórios daquela instituição
fetch("php/listarRelatoriosInstituicao.php")
    .then(res => res.json())
    .then(lista => {
        const tabela = document.getElementById("tabela-relatorios");

        if (lista.length === 0) {
            tabela.innerHTML = "<tr><td colspan='7'>Nenhum relatório enviado para esta instituição.</td></tr>";
            return;
        }

        lista.forEach(r => {
            const tr = document.createElement("tr");

            tr.innerHTML = `
                <td>${r.nome}</td>
                <td>${r.matricula}</td>
                <td>Relatório ${r.tipo}</td>
                <td>${r.dataEnvio}</td>
                <td>${r.status}</td>
                <td><a href="uploads/${r.caminhoArquivo}" target="_blank">Abrir</a></td>
                <td>
                    <button onclick="atualizarStatus(${r.idRelatorio}, 'APROVADO')">Aprovar</button>
                    <button onclick="atualizarStatus(${r.idRelatorio}, 'REPROVADO')">Reprovar</button>
                </td>
            `;

            tabela.appendChild(tr);
        });
    });

// Função para aprovar ou reprovar
function atualizarStatus(id, novoStatus) {
    fetch("/php/atualizarStatusRelatorio.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ idRelatorio: id, status: novoStatus })
    })
    .then(res => res.text())
    .then(msg => {
        alert(msg);
        location.reload();
    });
}
</script>
