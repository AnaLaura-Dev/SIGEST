<?php
header("Content-Type: application/json; charset=utf-8");
require_once "conexao.php";

if (!isset($_GET["id"])) {
    echo json_encode(["status" => "erro", "mensagem" => "ID inválido"]);
    exit;
}

$id = intval($_GET["id"]);

$sql = "SELECT 
            d.idDesempenho,
            d.dataAvaliacao,
            a.nome AS nomeAluno,
            a.email AS emailAluno,
            e.nome AS nomeEmpresa,
            t.nomeTopico,
            t.nota,
        FROM Desempenho d
        LEFT JOIN DesempenhoTopicos t ON t.idDesempenho = d.idDesempenho
        INNER JOIN Aluno a ON d.idAluno = a.idAluno
        INNER JOIN Empresa e ON d.idEmpresa = e.idEmpresa
        WHERE d.idDesempenho = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo json_encode(["status" => "erro", "mensagem" => "Desempenho não encontrado"]);
    exit;
}

$dados = null;
$topicos = [];

while ($row = $res->fetch_assoc()) {

    if ($dados === null) {
        $dados = [
            "idDesempenho" => $row["idDesempenho"],
            "dataAvaliacao" => $row["dataAvaliacao"],
            "nomeAluno" => $row["nomeAluno"],
            "emailAluno" => $row["emailAluno"],
            "nomeEmpresa" => $row["nomeEmpresa"]
        ];
    }

    if ($row["nomeTopico"] !== null) {
        $topicos[] = [
            "nomeTopico" => $row["nomeTopico"],
            "nota" => $row["nota"],
            "comentario" => $row["comentario"]
        ];
    }
}

echo json_encode([
    "status" => "ok",
    "dados" => $dados,
    "topicos" => $topicos
]);
?>
