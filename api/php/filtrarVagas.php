<?php
require_once "conexao.php";

$curso = $_GET['curso'] ?? '';

$sql = "SELECT 
            v.idVaga,
            v.titulo,
            v.descricao,
            v.curso,
            v.turno,
            e.nome AS nomeEmpresa
        FROM vaga v
        JOIN empresa e ON v.Empresa_idEmpresa = e.idEmpresa
        WHERE v.curso = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $curso);
$stmt->execute();
$result = $stmt->get_result();

$vagas = [];
while ($row = $result->fetch_assoc()) {
    $vagas[] = $row;
}

echo json_encode($vagas);
?>
