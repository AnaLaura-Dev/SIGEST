document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector(".form-perfil");
  const inputs = form.querySelectorAll("input, textarea");
  const btnEditar = form.querySelector("button:nth-of-type(2)");
  const btnSalvar = form.querySelector("button:nth-of-type(1)");
  const imgPreview = form.querySelector(".foto-label img");
  const curriculoContainer = document.createElement("div");
  form.appendChild(curriculoContainer);

  // desativa todos os campos no início
  inputs.forEach((input) => {
    if (input.type !== "file") {
      input.disabled = true;
    }
  });

  btnSalvar.style.display = "none"; // so aparece depois de clicar em Editar

  // carregar dados do perfil 
  fetch("php/carregarPerfil.php")
    .then((res) => res.json())
    .then((dados) => {
      if (dados.erro) {
        console.warn(dados.erro);
        return;
      }

      // preenche campos
      form.nome.value = dados.nome || "";
      form.email.value = dados.email || "";
      form.sobrevoce.value = dados.sobrevoc || ""; 

      // exibe imagem de perfil
      if (dados.foto) {
        imgPreview.src = dados.foto.startsWith("data:")
          ? dados.foto
          : `data:image/jpeg;base64,${dados.foto}`;
      }

      // curriculo
      if (dados.curriculo) {
        curriculoContainer.innerHTML = "";
        const link = document.createElement("a");
        link.href = dados.curriculo.startsWith("data:")
          ? dados.curriculo
          : `data:application/pdf;base64,${dados.curriculo}`;
        link.textContent = "📄 Ver currículo salvo";
        link.target = "_blank";
        link.style.display = "block";
        link.style.marginTop = "8px";
        curriculoContainer.appendChild(link);
      }
    })
    .catch((err) => {
      console.error("Erro ao carregar perfil:", err);
    });

  // habilita edição
  btnEditar.addEventListener("click", (e) => {
    e.preventDefault();

    inputs.forEach((input) => {
      input.disabled = false;
    });

    btnEditar.style.display = "none";
    btnSalvar.style.display = "inline-block";
  });

  // salva perfil 
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(form);

    const resposta = await fetch("/php/salvarperfil.php", {
      method: "POST",
      body: formData,
    });

    const resultado = await resposta.text();
    alert(resultado);

    // bloqueia os campos
    inputs.forEach((input) => {
      if (input.type !== "file") {
        input.disabled = true;
      }
    });

    btnEditar.style.display = "inline-block";
    btnSalvar.style.display = "none";
  });

  //mostra o perfil atualizado
  const inputFoto = form.querySelector("#foto");
  inputFoto.addEventListener("change", (e) => {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = (event) => {
        imgPreview.src = event.target.result;
      };
      reader.readAsDataURL(file);
    }
  });
});
