<?php
require_once "conexao.php";

if (!isset($_GET['idAluno']) || empty($_GET['idAluno'])) {
    echo "<p>Aluno não especificado.</p>";
    exit;
}

$idAluno = intval($_GET['idAluno']);

// busca informações do aluno
$sql = "SELECT a.nome, a.email, p.sobrevoc, p.foto, p.curriculo 
        FROM Aluno a
        LEFT JOIN Perfil p ON a.idAluno = p.Aluno_idAluno
        WHERE a.idAluno = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idAluno);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>Aluno não encontrado.</p>";
    exit;
}

$aluno = $result->fetch_assoc();

// converte foto de perfil pra base64 q transforma binario em texto
$fotoBase64 = '';
if (!empty($aluno['foto'])) {
    $fotoBase64 = 'data:image/jpeg;base64,' . base64_encode($aluno['foto']);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Perfil do Aluno - SIGEST</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap');

body {
  margin: 0;
  padding: 0;
 font-family: Arial, sans-serif;

  height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  cursor: default;
  background-color: #ffffff;
  position: relative;
  z-index: 1;
 
}
header {
  background-color: #D9D9D9;
  padding: 0 20px;
  height: 80px;
  width: 100%;
   overflow: hidden;
  position: fixed;
  top: 0;
  left: 0;
  z-index: 10;
}
nav {
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 100%;
}

.menu {
 display: flex;
  gap: 20px;
}

nav a { 
  gap: 1px;
  margin-right: 40px;
  color: rgb(53, 48, 48);
  text-decoration: none;
  font-weight: bold;
  font-size: 16px;
}

nav a:hover {
padding: auto;
  background-color: #1ec75c3d;
   border-radius: 8px;
   cursor: pointer;
}

.nav {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.logo {
  margin-left: 0;
  padding-left: 0;
}

.logo img {
  display: flex;
  height: 80px;
  margin: 1;
  padding: 0;
  display: block;
}

.logo img {
  display: flex;
  height: 160px;
  margin: 0;
  padding: 0;
  display: block;
}

.perfil-container{
  background: #D9D9D9;
   align-items: center;
   margin-bottom: -50px;
   margin-top: 50px;
  gap: 30px;
    border-radius: 8px;
     width: 700px;
       box-shadow: 0 6px 18px #726f6f;
}

.foto{
   margin-left: 10px;
}

.foto img{
   margin-bottom: -20px;
   margin-top: 20px;
   width: 150px;
      height: 150px;
      border-radius: 50%;
    padding: -15px;
    border: 3px solid #1EC75C;
   
}
  h1{
   margin-left: 10px;
      color: #333;
    }

.info{
    margin-left: 10px;
      color: #333;
}

.info p{
    margin-left: 10px;
      color: #333;
}

.sobre{
   margin-left: 10px;
      color: #333;
}

button {
    margin-top:20px; 
  background-color: #1EC75C;
  border: 2px solid #1EC75C;
  border-radius: 8px;
 color: #fff;
  width: 200px;
  height: 40px;
  margin-left: 10px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-size: 18px;
  transition: background 0.3s;
}
  button:hover{
    text-decoration:underline;
    color: #D9D9D9;
  }



</style>
</head>
<body>
<header>
     <nav>
      <div class="logo">
        <img src="/sigest/imagens/logosigest.gif">
      </div>
      <div class="menu">
         <a href="/sigest/Empresa/inicio.html">Início</a>
        <a href="/sigest/Empresa/adicionardesempenho.html">Desempenho</a>
        <a href="/sigest/Empresa/feedbackempresa.html">Vagas Aprovadas</a>
      </div>
    </nav>
</header>
  <div class="perfil-container">
    <?php if ($fotoBase64): ?>
      <div class="foto">
        <img src="<?php echo $fotoBase64;  ?>" alt="Foto do Aluno">
      </div>
    <?php endif; ?>

    <h1><?php echo htmlspecialchars($aluno['nome']);  //exibe o variavel com o valor mas com segurança ?></h1>

    <div class="info">
      <p><strong>Email:</strong> <?php echo htmlspecialchars($aluno['email']); ?></p>
    </div>

    <div class="sobre">
      <h3>Sobre você:</h3>
      <p><?php echo nl2br(htmlspecialchars($aluno['sobrevoc'] ?? 'Não informado.')); //imprime o sobre voce com quebra de linh\ ?></p>
    </div>

    <?php if (!empty($aluno['curriculo'])): ?>
      <p><a class="curriculo" href="baixarCurriculo.php?idAluno=<?php echo $idAluno; ?>" target="_blank"><button type="submit">Ver currículo</button></a></p>
    <?php endif; ?>
    </div>
  </div>
</body>
</html>
