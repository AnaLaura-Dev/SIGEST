<?php
session_start();
require_once "conexao.php";

if (!isset($_SESSION['idAluno'])) {
    echo "<script>alert('Faça login para se candidatar a uma vaga.'); window.location.href = '/login/loginaluno.html';</script>";
    exit;
}

$idAluno = $_SESSION['idAluno'];
$idVaga = $_POST['idVaga'] ?? null;

if (!$idVaga) {
    echo "ID da vaga inválido.";
    exit;
}

// verifica se o aluno já se candidatou
$sqlCheck = "SELECT * FROM candidatura WHERE Aluno_idAluno = ? AND Vaga_idVaga = ?";
$stmt = $conn->prepare($sqlCheck);
$stmt->bind_param("ii", $idAluno, $idVaga);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('Você já se candidatou a esta vaga.'); window.history.back();</script>";
    exit;
}

// faz candidatura
$sql = "INSERT INTO candidatura (Aluno_idAluno, Vaga_idVaga, dataCandidatura, status) VALUES (?, ?, NOW(), 'pendente')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $idAluno, $idVaga);

if ($stmt->execute()) {
    echo "<script>alert('Candidatura enviada com sucesso!'); window.location.href = '/AreaDoAluno/vagas.html';</script>";
} else {
    echo "Erro ao enviar candidatura: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
