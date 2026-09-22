<?php
session_start();
require_once "conexao.php";

if (!isset($_SESSION['idInstituicao'])) {
    echo json_encode(["erro" => "Instituição não identificada."]);
    exit;
}

$idInstituicao = $_SESSION['idInstituicao'];

$sql = "SELECT d.idDocumento,
 d.tipoDoc, d.arquivo, 
 d.status,  d.dataEnvio, 
  a.idAluno,  a.nome
   AS nomeAluno, 
    a.email AS
     emailAluno
        FROM Documento d 
        JOIN Aluno a ON d.Aluno_idAluno = a.idAluno WHERE d.Instituicao_idInstituicao = ?
        ORDER BY d.dataEnvio DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idInstituicao);
$stmt->execute();
$result = $stmt->get_result();

$documentos = [];

while ($row = $result->fetch_assoc()) {

    $row['matriculaAluno'] = preg_replace('/\D/', '', $row['emailAluno']);

    $documentos[] = $row;
}

echo json_encode($documentos, JSON_UNESCAPED_UNICODE);
?>
