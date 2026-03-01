console.log("idAlunoAvaliado =", sessionStorage.getItem("idAlunoAvaliado"));
console.log("idInstituicaoAvaliada =", sessionStorage.getItem("idInstituicaoAvaliada"));
console.log("idEmpresaLogada =", sessionStorage.getItem("idEmpresaLogada"));

const nome = sessionStorage.getItem("nomeAlunoAvaliado");
const idAluno = sessionStorage.getItem("idAlunoAvaliado");
const idInstituicao = sessionStorage.getItem("idInstituicaoAvaliada");
const idEmpresa = sessionStorage.getItem("idEmpresaLogada");

document.getElementById("nomeAluno").innerText =
    sessionStorage.getItem("nomeAlunoAvaliado") || "NÃO ENCONTRADO";

// ----------- SISTEMA DE ESTRELAS -------------
document.querySelectorAll(".estrelas").forEach(div => {
    for (let i = 1; i <= 5; i++) {
        let star = document.createElement("span");
        star.innerHTML = "★";
        star.classList.add("estrela");
        star.dataset.valor = i;

        star.addEventListener("click", () => marcarEstrelas(div, i));
        div.appendChild(star);
    }
});

function marcarEstrelas(div, nota) {
    div.dataset.nota = nota;
    div.querySelectorAll(".estrela").forEach((s, index) => {
        if (index < nota) s.classList.add("selecionada");
        else s.classList.remove("selecionada");
    });
}


document.getElementById("btnEnviar").addEventListener("click", () => {

    if (!idAluno || !idEmpresa || !idInstituicao) {
        alert("Erro: ID do aluno, empresa ou instituição não carregado.");
        return;
    }

    const conhecimentos = [...document.querySelectorAll("input[name='teorico']:checked")]
        .map(i => i.value);

    const topicos = [...document.querySelectorAll(".estrelas")].map(div => ({
        nomeTopico: div.dataset.topico,
        nota: div.dataset.nota ?? 0,
        comentario: ""
    }));

    const dados = {
        idAluno,
        idInstituicao,
        idEmpresa,
        conhecimentos,
        comentarioSugestao: document.getElementById("comentario_sugestao").value,
        comentarioAtividades: document.getElementById("comentario_atividades").value,
        comentarioGeral: document.getElementById("comentario_geral").value,
        topicos
    };

  fetch("/php/salvardesempenho.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(dados)
})
    .then(async res => {
        const texto = await res.text();
        console.log("Resposta bruta do PHP:", texto);

        try {
            return JSON.parse(texto);
        } catch (e) {
            alert("Erro no servidor. Veja o console.");
            console.error("Não é JSON:", texto);
            throw e;
        }
    })
    .then(resp => {
        alert(resp.mensagem || "Sucesso");
        window.location.href = "/Empresa/Estagiarios.html";
    })
    .catch(err => console.log(err));

});
