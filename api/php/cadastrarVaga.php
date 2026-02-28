<?php
session_start();
require_once "conexao.php";

if (!isset($_SESSION['idEmpresa'])) {
    echo "<script>alert('Faça login como empresa para cadastrar vagas.'); window.location.href = 'login/loginempresa.html';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Método inválido.";
    exit;
}

// pega os dados do formulário
$titulo = trim($_POST['titulo'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$turno = trim($_POST['turno'] ?? '');
$quantidade = (int) ($_POST['quantidade'] ?? 0);
$curso = trim($_POST['curso'] ?? '');
$instituicao = (int) ($_POST['idInstituicao'] ?? 0);
$idEmpresa = (int) $_SESSION['idEmpresa'];

// campos obrigatórios
if ($titulo === '' || $instituicao <= 0) {
    echo "<script>alert('Preencha o título e selecione uma instituição.'); window.history.back();</script>";
    exit;
}

$status = 'aguardando';


$sql = "INSERT INTO vaga 
        (titulo, descricao, turno, quantidade, curso, status, Empresa_idEmpresa, instituicao_idInstituicao)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Erro na preparação da query: " . $conn->error);
}

$stmt->bind_param(
    "sssissii",
    $titulo,
    $descricao,
    $turno,
    $quantidade,
    $curso,
    $status,
    $idEmpresa,
    $instituicao
);


if ($stmt->execute()) {
    echo "<script>
        alert('Vaga cadastrada e enviada para a instituição com sucesso!');
        window.location.href = 'Empresa/cadastrarvaga.html';
    </script>";
} else {
    echo "Erro ao cadastrar vaga: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
