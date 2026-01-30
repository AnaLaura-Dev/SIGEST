<?php

session_start();
require_once "conexao.php";

if (!isset($_SESSION['idEmpresa'])) {
    http_response_code(401);
    echo json_encode(["erro" => "Acesso negado"]);
    exit;
}

$idEmpresa = (int) $_SESSION['idEmpresa'];

// consulta as candidaturas nas vagas da empresa
$sql = "SELECT c.idCandidatura, 
c.status AS statusCandidatura, 
c.dataCandidatura, a.idAluno, a.nome 
AS aluno,
 a.curso,  a.email,  v.idVaga, v.titulo
  AS vaga FROM candidatura c 
  JOIN aluno a ON c.Aluno_idAluno = a.idAluno 
  JOIN vaga v ON c.Vaga_idVaga = v.idVaga
        WHERE v.Empresa_idEmpresa = ?  ORDER BY c.idCandidatura DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idEmpresa);
$stmt->execute();
$result = $stmt->get_result();

$candidaturas = [];
while ($row = $result->fetch_assoc()) {
    $candidaturas[] = $row; //array candidatura do aluno
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($candidaturas, JSON_UNESCAPED_UNICODE); //imprime como arquivo json e exibe acentos sem deixar estranho

$stmt->close();
$conn->close();
?>
