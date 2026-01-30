<?php
session_start();
require_once "conexao.php";

header("Content-Type: application/json; charset=UTF-8");

if (!isset($_SESSION['idInstituicao'])) {
    echo json_encode([]);
    exit;
}

$idInstituicao = $_SESSION['idInstituicao'];
$sql = "SELECT 
            r.idRelatorio,
            a.nome AS nome,
            r.matricula,
            r.curso,
            r.tipo,
            r.dataEnvio,
            r.status,
            r.caminhoArquivo
        FROM Relatorio r
        INNER JOIN Aluno a ON r.Aluno_idAluno = a.idAluno
        WHERE r.destino = ?
        ORDER BY r.dataEnvio DESC";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idInstituicao);
$stmt->execute();
$result = $stmt->get_result();

$dados = [];

while ($row = $result->fetch_assoc()) {
    // ajustar tipo
    $row['tipo'] = $row['tipo'] . "º Relatório";
    $dados[] = $row;
}

echo json_encode($dados);
?>