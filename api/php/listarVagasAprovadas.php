<?php
require_once "conexao.php";

// vagas com status aprovada pro aluno visualizar
$sql = "SELECT v.idVaga, v.titulo, v.descricao, v.curso, v.turno, e.nome AS nomeEmpresa
        FROM vaga v
        JOIN empresa e ON v.Empresa_idEmpresa = e.idEmpresa
        WHERE v.status = 'aprovada'";

$result = $conn->query($sql);

$vagas = [];
while ($row = $result->fetch_assoc()) {
    $vagas[] = $row; //resultado do array com as informaçõe das vagas
}

header('Content-Type: application/json');
echo json_encode($vagas); //transforma o array  em texto JSON
?>
