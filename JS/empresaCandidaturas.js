
document.addEventListener("DOMContentLoaded", () => { //so roda qaundo a pagina carregar(html)
  const container = document.getElementById('lista-candidaturas');

  //js com php
  async function carregar() {
    container.innerHTML = "<p>Carregando candidaturas...</p>";
    try {
      const res = await fetch('/sigest/php/listarCandidaturas.php'); // faz requisiçaõ
      if (!res.ok) throw new Error("Falha ao buscar candidaturas");
      const dados = await res.json();
      renderLista(dados);
    } catch (err) {
      console.error(err);
      container.innerHTML = "<p>Erro ao carregar candidaturas.</p>";
    }
  }

  //layout das candidaturas
  function renderLista(candidaturas) {
    container.innerHTML = "";
    if (!Array.isArray(candidaturas) || candidaturas.length === 0) { 
      container.innerHTML = "<p>Você ainda não recebeu nenhuma candidatura.</p>";
      return;
    }

    candidaturas.forEach(c => {
      const card = document.createElement('div');
      card.className = "card";

      card.innerHTML = `
        <h3>${escapeHtml(c.aluno)}</h3>
        <p><strong>Curso:</strong> ${escapeHtml(c.curso || '—')}</p>
        <p><strong>Email:</strong> ${escapeHtml(c.email || '—')}</p>
        <p><strong>Vaga:</strong> ${escapeHtml(c.vaga)}</p>
        <p><strong>Status:</strong> ${escapeHtml(c.statusCandidatura || 'pendente')}</p>
        <div class="acoes">
          <button data-aluno="${c.idAluno}" class="btn-ver">Ver Perfil</button>
          <button data-id="${c.idCandidatura}" class="btn-aceitar">Selecionar</button>
          <button data-id="${c.idCandidatura}" class="btn-recusar">Recusar</button>
        </div>
      `;

 card.querySelector('.btn-ver').addEventListener('click', (e) => {
  const idAluno = e.currentTarget.dataset.aluno;
  window.location.href = `/sigest/php/verPerfilAluno.php?idAluno=${idAluno}`;
});

    

      card.querySelector('.btn-aceitar').addEventListener('click', (e) => {
        const id = e.currentTarget.dataset.id;
        if (!confirm("Deseja selecionar este candidato e encerrar a vaga?")) return;
        aceitar(id);
      });

      card.querySelector('.btn-recusar').addEventListener('click', (e) => {
        const id = e.currentTarget.dataset.id;
        if (!confirm("Deseja recusar esta candidatura?")) return;
        recusar(id);
      });

      container.appendChild(card);
    });
  }

  //segurança
  function escapeHtml(str) {
    if (!str) return '';
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;');
  }

  // ver perfil completo do alunno
  async function abrirPerfil(idAluno) {
    try {
      const res = await fetch(`/sigest/php/verPerfilAluno.php?idAluno=${encodeURIComponent(idAluno)}`);
      if (!res.ok) throw new Error("Erro ao buscar perfil");
      const perfil = await res.json();

      if (perfil.erro) {
        alert(perfil.erro);
        return;
      }

      const modal = document.createElement('div');
      modal.className = 'modal-overlay';
      modal.innerHTML = `
        <div class="modal">
          <button class="modal-close">×</button>
          <h2>${escapeHtml(perfil.nome)}</h2>
          <p><strong>Email:</strong> ${escapeHtml(perfil.email || '—')}</p>
          <p><strong>Curso:</strong> ${escapeHtml(perfil.curso || '—')}</p>
          <p><strong>Sobre o aluno:</strong></p>
          <p>${escapeHtml(perfil.sobrevoc || 'Sem informações.')}<p>
          ${perfil.foto ? `<img src="data:image/jpeg;base64,${perfil.foto}" alt="Foto do aluno" style="max-width:150px; border-radius:8px; margin-top:10px;">` : '<p>Sem foto</p>'}
          ${perfil.curriculo ? `<p><a href="data:application/pdf;base64,${perfil.curriculo}" download="curriculo.pdf" target="_blank">📄 Baixar Currículo</a></p>` : '<p>Sem currículo</p>'}
        </div>
      `;

      document.body.appendChild(modal);
      modal.querySelector('.modal-close').addEventListener('click', () => modal.remove());
      modal.addEventListener('click', ev => { if (ev.target === modal) modal.remove(); });

    } catch (err) {
      console.error(err);
      alert("Erro ao abrir perfil do aluno.");
    }
  }

  async function aceitar(idCandidatura) {
    try {
      const form = new URLSearchParams();
      form.append('idCandidatura', idCandidatura);  // pega dados como formulario

      const res = await fetch('/sigest/php/aceitarCandidatura.php', {
        method: 'POST',
        body: form
      });
      const texto = await res.text();
      if (!res.ok) throw new Error(texto || 'Erro');
      alert(texto);
      carregar(); // atualizar lista
    } catch (err) {
      alert("Erro ao selecionar candidato.");
      console.error(err);
    }
  }

  async function recusar(idCandidatura) {
    try {
      const form = new URLSearchParams();
      form.append('idCandidatura', idCandidatura);

      const res = await fetch('/sigest/php/recusarCandidatura.php', {
        method: 'POST',
        body: form
      });
      const texto = await res.text();
      if (!res.ok) throw new Error(texto || 'Erro');
      alert(texto);
      carregar(); // atualizar lista
    } catch (err) {
      alert("Erro ao recusar candidatura.");
      console.error(err);
    }
  }

  // layout
  const style = document.createElement('style');
  style.innerHTML = `
    .card { background:#fff; padding:16px; border-radius:8px; margin:10px auto; width:80%; box-shadow:0 3px 8px rgba(0,0,0,0.08) }
    .card h3 { margin:0 0 8px 0; color:#1ec75c }
    .acoes { margin-top:10px; display:flex; gap:8px }
    .acoes button { padding:8px 12px; border-radius:6px; border:none; cursor:pointer }
    .btn-ver { background:#1976d2; color:#fff }
    .btn-aceitar { background:#43a047; color:#fff }
    .btn-recusar { background:#e53935; color:#fff }
    .modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,0.4); display:flex; align-items:center; justify-content:center; z-index:9999 }
    .modal { position:relative; background:#fff; padding:20px; border-radius:8px; max-width:600px; width:90%; box-shadow:0 6px 30px rgba(0,0,0,0.2) }
    .modal-close { position:absolute; right:16px; top:12px; border:none; background:transparent; font-size:22px; cursor:pointer }
  `;
  document.head.appendChild(style);

  // inicializa
  carregar();
});
