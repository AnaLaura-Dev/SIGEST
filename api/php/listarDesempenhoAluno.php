<?php
header("Content-Type: application/json; charset=utf-8");
session_start();
require_once "conexao.php";

if (!isset($_SESSION["idAluno"])) {
    echo json_encode(["status" => "erro", "mensagem" => "Aluno não logado"]);
    exit;
}

$idAluno = $_SESSION["idAluno"];


$sql = "SELECT 
            d.idDesempenho,
            d.dataAvaliacao,
            d.comentarioSugestao,
            d.comentarioAtividades,
            d.comentarioGeral,
            e.nome AS nomeEmpresa
        FROM Desempenho d
        INNER JOIN Empresa e ON e.idEmpresa = d.idEmpresa
        WHERE d.idAluno = ?
        ORDER BY d.dataAvaliacao DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idAluno);
$stmt->execute();
$result = $stmt->get_result();

$avaliacoes = [];

while ($row = $result->fetch_assoc()) {

  
    $sqlTop = "SELECT nomeTopico, nota, comentario
               FROM DesempenhoTopicos
               WHERE idDesempenho = ?";

    $stmtTop = $conn->prepare($sqlTop);
    $stmtTop->bind_param("i", $row["idDesempenho"]);
    $stmtTop->execute();

    $topicos = $stmtTop->get_result()->fetch_all(MYSQLI_ASSOC);


    $avaliacoes[] = [
        "idDesempenho" => $row["idDesempenho"],
        "dataAvaliacao" => $row["dataAvaliacao"],
        "nomeEmpresa" => $row["nomeEmpresa"],
        "comentarioSugestao" => $row["comentarioSugestao"],
        "comentarioAtividades" => $row["comentarioAtividades"],
        "comentarioGeral" => $row["comentarioGeral"],
        "topicos" => $topicos
    ];
}

echo json_encode([
    "status" => "ok",
    "avaliacoes" => $avaliacoes
]);
?>
