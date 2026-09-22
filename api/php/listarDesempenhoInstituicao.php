<?php
header("Content-Type: application/json; charset=utf-8");
session_start();
require_once "conexao.php";

if (!isset($_SESSION["idInstituicao"])) {
    echo json_encode(["status" => "erro", "mensagem" => "Instituição não logada"]);
    exit;
}

$idInstituicao = $_SESSION["idInstituicao"];

$sql = "SELECT 
            d.idDesempenho,
            d.dataAvaliacao,
            a.nome AS nomeAluno,
            a.email AS emailAluno,
            v.titulo AS tituloVaga,
            e.nome AS nomeEmpresa
        FROM Desempenho d
        INNER JOIN Aluno a ON d.idAluno = a.idAluno
        INNER JOIN Empresa e ON d.idEmpresa = e.idEmpresa
        INNER JOIN Vaga v ON v.instituicao_idInstituicao = d.idInstituicao
        WHERE d.idInstituicao = ?
        ORDER BY d.dataAvaliacao DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idInstituicao);
$stmt->execute();

$result = $stmt->get_result();
$avaliacoes = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode([
    "status" => "ok",
    "avaliacoes" => $avaliacoes
]);
?>
