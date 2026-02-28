fetch('php/listarCandidaturasAlunos.php')
  .then(response => response.json())
  .then(candidaturas => {
    const container = document.getElementById('lista-candidaturas');

    if (candidaturas.length === 0) {
      container.innerHTML = "<p>Você ainda não se candidatou a nenhuma vaga.</p>";
      return;
    }

    candidaturas.forEach(c => {
      const card = document.createElement('div');
      card.classList.add('card');

      let statusTexto = "";
      let classe = "";

      switch (c.statusCandidatura) {
        case 'pendente':
          statusTexto = "Aguardando resposta da empresa";
          classe = "pendente";
          break;
        case 'aceita':
          statusTexto = "Você foi selecionado!";
          classe = "aceita";
          break;
        case 'recusada':
          statusTexto = "Vaga preenchida por outro candidato";
          classe = "recusada";
          break;
      }

      card.innerHTML = `
        <h2>${c.titulo}</h2>
        <p><strong>Status:</strong> <span class="${classe}">${statusTexto}</span></p>
      `;

      container.appendChild(card);
    });
  });
