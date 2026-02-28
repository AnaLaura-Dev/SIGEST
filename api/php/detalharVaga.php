<?php
include 'conexao.php';
$id = $_GET['id'];

$sql = "SELECT * FROM vaga WHERE idVaga = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id); 
$stmt->execute();
$result = $stmt->get_result();
$dados = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Detalhes da Vaga</title>
  <link rel="stylesheet" href="css/detalharvaga.css">
</head>
<body>
      <header>
    <nav>
      <div class="logo">
        <img src="imagens/logosigest.gif">
      </div>
      <div class="menu">
        <a href="Instituição/inicio.html">Inicio</a>
        <a href="Instituição/propostasDeVagas.html">Validar Estágio</a>
        <a href="Instituição/adicionardesempenho.html">Desempenhos</a>
        </a>
      </div>
    </nav>
  </header>
  <main class="layout">
    <aside class="left-column">
      <div class="left-wrapper">
        <div class="caixa-cinza"></div>
  <div class="dadosvaga"> <h2><?php echo htmlspecialchars($dados['titulo']); ?></h2>
    <p><strong>Curso:</strong> <?php echo htmlspecialchars($dados['curso']); ?></p>
  <p><strong>Descrição:</strong> <?php echo nl2br(htmlspecialchars($dados['descricao'])); ?></p>
    <!--confere caracteres especiais em html e faz com que as quebras de linhas
   sejam mostradas de forma correta no navegador-->
  <p><strong>Turno:</strong> <?php echo htmlspecialchars($dados['turno']); ?></p>
  <p><strong>Quantidade:</strong> <?php echo htmlspecialchars($dados['quantidade']); ?></p>

  <form action="validarVaga.php" method="POST">
    <input type="hidden" name="idVaga" value="<?php echo $dados['idVaga']; ?>">
    <button type="submit" name="acao" value="aprovar">Aprovar</button>
    <button type="submit" name="acao" value="recusar">Recusar</button>
  </form>
</div>
  </div>
    </aside>
</main>
</body>
</html>
