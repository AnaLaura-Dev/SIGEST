<?php
require_once "conexao.php";
// Pega as 3 vagas mais recentes
$sql = "SELECT 
            v.idVaga, v.titulo, v.descricao, v.curso, v.turno,
            e.nome AS nomeEmpresa
        FROM vaga v
        LEFT JOIN empresa e ON v.Empresa_idEmpresa = e.idEmpresa
        WHERE v.status = 'aprovada'
        ORDER BY v.idVaga DESC
        LIMIT 3";

$result = $conn->query($sql);

$vagas = [];

while ($row = $result->fetch_assoc()) {
    $vagas[] = $row;
}

echo json_encode($vagas, JSON_UNESCAPED_UNICODE);
