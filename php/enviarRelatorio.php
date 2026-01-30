<?php
session_start();
require_once "conexao.php";


$idAluno = $_SESSION['idAluno'];


if (!isset($idAluno)) {
    die("Erro: aluno não está logado.");
}


$nome = $_POST['nome'];
$matricula = $_POST['matricula'];
$curso = $_POST['curso'];
$tipo = $_POST['tipo'];         // relatório 1, 2 ou 3
$destino = $_POST['destino'];   // instituição que vai receber


$nomePasta = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $nome);

$pastaBase = "../uploads/";

if (!is_dir($pastaBase)) {
    mkdir($pastaBase, 0777, true);
}


$pastaAluno = $pastaBase . $nomePasta . "/";

if (!is_dir($pastaAluno)) {
    mkdir($pastaAluno, 0777, true);
}


$arquivo = $_FILES['arquivo'];

if ($arquivo['error'] !== UPLOAD_ERR_OK) {
    die("Erro ao enviar arquivo.");
}


$extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
if ($extensao !== "pdf") {
    die("Envie somente arquivos PDF.");
}


$nomeArquivo = time() . "_" . basename($arquivo['name']);

$caminhoCompleto = $pastaAluno . $nomeArquivo;
$caminhoBanco = $nomePasta . "/" . $nomeArquivo;


if (!move_uploaded_file($arquivo['tmp_name'], $caminhoCompleto)) {
    die("Erro ao salvar arquivo.");
}


$sql = "INSERT INTO Relatorio 
(tipo, destino, status, dataEnvio, caminhoArquivo, matricula, curso, Aluno_idAluno)
VALUES (?, ?, 'PENDENTE', CURDATE(), ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Erro no prepare(): " . $conn->error);
}

$stmt->bind_param(
    "iisssi",
    $tipo,          
    $destino,       
    $caminhoBanco,  
    $matricula,     
    $curso,         
    $idAluno        
);


if ($stmt->execute()) {
   echo "<script>
        alert('Relatório enviado com sucesso!');
        window.location.href = '/sigest/AreaDoAluno/relatorios.html';
      </script>";
} else {
    echo "Erro ao enviar relatório: " . $stmt->error;
}
?>
