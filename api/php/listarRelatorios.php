<?php
session_start();
require_once "conexao.php";

$idAluno = $_SESSION['idAluno'];

$sql = "SELECT idRelatorio AS id,
               dataEnvio,
               status,
               tipo,
               destino,
               caminhoArquivo
        FROM Relatorio
        WHERE Aluno_idAluno = ?
        ORDER BY idRelatorio DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idAluno);
$stmt->execute();
$result = $stmt->get_result();

$lista = [];

while ($row = $result->fetch_assoc()) {
    // Ajustar nome do tipo
    $row['tipo'] = $row['tipo'] . "º Relatório Mensal";
    $lista[] = $row;
}

echo json_encode($lista);
?>
