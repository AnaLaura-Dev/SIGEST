<?php
session_start();
require_once "conexao.php";

if (!isset($_SESSION['idAluno'])) {
    echo "Acesso negado.";
    exit;
}

$idAluno = $_SESSION['idAluno'];
 //lista vagas em que o aluno se candidatou
$sql = "SELECT v.titulo, v.status AS statusVaga, c.status AS statusCandidatura
        FROM candidatura c
        JOIN vaga v ON c.Vaga_idVaga = v.idVaga
        WHERE c.Aluno_idAluno = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idAluno);
$stmt->execute();
$result = $stmt->get_result();

$candidaturas = [];
while ($row = $result->fetch_assoc()) {
    $candidaturas[] = $row;
}

header('Content-Type: application/json');
echo json_encode($candidaturas);
?>
