<?php
session_start();
require_once "conexao.php";

if (!isset($_SESSION['idAluno'])) {
    echo "<script>alert('Você precisa estar logado para se candidatar.'); window.location.href='/sigest/login/loginaluno.html';</script>";
    exit;
}

$idAluno = $_SESSION['idAluno'];
$idVaga = (int) $_POST['idVaga'];

$sql = "INSERT INTO candidatura (Aluno_idAluno, Vaga_idVaga, dataCandidatura) VALUES (?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $idAluno, $idVaga);

if ($stmt->execute()) {
    echo "<script>alert('Candidatura enviada com sucesso!'); window.location.href='/sigest/AreaDoAluno/vagas.html';</script>";
} else {
    echo "Erro ao candidatar-se: " . $conn->error;
}
?>
