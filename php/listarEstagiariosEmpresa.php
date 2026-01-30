<?php
header("Content-Type: application/json; charset=UTF-8");
require_once "conexao.php";

$sql = "SELECT 
    ce.idCandidatura,
    a.idAluno,
    a.nome AS nomeAluno,
    a.email AS emailAluno,
    a.celular AS celularAluno,
    a.curso AS cursoAluno,

    v.idVaga,
    v.titulo AS tituloVaga,
    v.Empresa_idEmpresa AS idEmpresa,

    inst.idInstituicao AS idInstituicao,
    inst.nome AS nomeInstituicao

FROM candidatura ce
INNER JOIN aluno a ON a.idAluno = ce.Aluno_idAluno
INNER JOIN vaga v ON v.idVaga = ce.Vaga_idVaga
LEFT JOIN instituicao inst ON inst.idInstituicao = v.instituicao_idInstituicao";

$result = $conn->query($sql);

$dados = [];

while ($row = $result->fetch_assoc()) {
    $dados[] = $row;
}

echo json_encode($dados, JSON_UNESCAPED_UNICODE);
?>