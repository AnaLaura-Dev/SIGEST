<?php
session_start();
require_once "conexao.php";

if (!isset($_SESSION['idEmpresa'])) {
    http_response_code(401);
    echo json_encode(["erro" => "Acesso negado"]);
    exit;
}

$idAluno = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($idAluno <= 0) {
    http_response_code(400);
    echo json_encode(["erro" => "ID do aluno inválido"]);
    exit;
}

$sql = "SELECT idAluno, nome, email, curso, curriculo, telefone, resumo FROM aluno WHERE idAluno = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idAluno);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["erro" => "Aluno não encontrado"]);
    exit;
}

$aluno = $result->fetch_assoc();

header('Content-Type: application/json; charset=utf-8');
echo json_encode($aluno, JSON_UNESCAPED_UNICODE); //converte o array

$stmt->close();
$conn->close();
?>
