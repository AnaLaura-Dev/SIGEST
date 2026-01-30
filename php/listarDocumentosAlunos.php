<?php
session_start();
require_once "conexao.php";

if (!isset($_SESSION['idAluno'])) {
    echo json_encode([]);
    exit;
}

$idAluno = $_SESSION['idAluno'];

$sql = "SELECT tipoDoc, arquivo, status, 
        DATE_FORMAT(dataEnvio, '%d/%m/%Y %H:%i') AS dataEnvio
        FROM Documento 
        WHERE Aluno_idAluno = ? 
        ORDER BY dataEnvio DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idAluno);
$stmt->execute();
$result = $stmt->get_result();

$docs = [];
while ($row = $result->fetch_assoc()) {
    $docs[] = $row;
}
echo json_encode($docs);
?>
