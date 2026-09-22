<?php
session_start();
require_once "conexao.php";

$idEmpresa = $_SESSION['idEmpresa'];

// consulta ao banco de dados
$sql = "SELECT titulo, status FROM vaga WHERE Empresa_idEmpresa = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $idEmpresa);
$stmt->execute();
$result = $stmt->get_result();

// cria um array com as vagas
$vagas = [];
while ($row = $result->fetch_assoc()) {
    $vagas[] = $row;
}

$stmt->close();
$conn->close();

// Envia os dados em formato JSON para o js 
header('Content-Type: application/json');
echo json_encode($vagas);
