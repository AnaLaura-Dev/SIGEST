<?php
header("Content-Type: application/json; charset=utf-8");
include "conexao.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1);

$input = json_decode(file_get_contents("php://input"), true);

// pega oq ta vindo do JS
$idAluno = $input["idAluno"] ?? null;
$idEmpresa = $input["idEmpresa"] ?? null;
$idInstituicao = $input["idInstituicao"] ?? null;
$topicos = $input["topicos"] ?? [];

if (!$idAluno || !$idEmpresa || !$idInstituicao) {
    echo json_encode(["status" => "erro", "mensagem" => "Dados incompletos"]);
    exit;
}


$sql = "INSERT INTO Desempenho 
(idAluno, idEmpresa, idInstituicao, comentarioSugestao, comentarioAtividades, comentarioGeral)
VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "iiisss", 
    $idAluno, 
    $idEmpresa, 
    $idInstituicao,
    $input["comentarioSugestao"],
    $input["comentarioAtividades"],
    $input["comentarioGeral"]
);


$stmt->execute();
$idDesempenho = $conn->insert_id;

if (!$idDesempenho) {
    echo json_encode(["status" => "erro", "mensagem" => "Erro ao criar registro de desempenho"]);
    exit;
}
// insere  topico avaliado
$sqlTopico = "INSERT INTO DesempenhoTopicos (idDesempenho, nomeTopico, nota, comentario) 
              VALUES (?, ?, ?, ?)";
$stmtTopico = $conn->prepare($sqlTopico);

foreach ($topicos as $t) {


    $nome = $t["nomeTopico"] ?? "";
    $nota = $t["nota"] ?? 0;
    $comentario = $t["comentario"] ?? "";

    $stmtTopico->bind_param("isis", $idDesempenho, $nome, $nota, $comentario);
    $stmtTopico->execute();
}


echo json_encode([
    "status" => "ok",
    "mensagem" => "Desempenho salvo com sucesso!"
]);
?>
