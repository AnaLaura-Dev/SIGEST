<?php
require_once "conexao.php";

$idVaga = $_POST['idVaga'] ?? null;
$idAlunoSelecionado = $_POST['idAluno'] ?? null;

if (!$idVaga || !$idAlunoSelecionado) {
    echo "Dados inválidos.";
    exit;
}

// atualiza vaga
$conn->query("UPDATE vaga SET status='preenchida' WHERE idVaga=$idVaga");

// atualiza candidaturas
$conn->query("UPDATE candidatura SET status='aceita' WHERE Vaga_idVaga=$idVaga AND Aluno_idAluno=$idAlunoSelecionado");
$conn->query("UPDATE candidatura SET status='recusada' WHERE Vaga_idVaga=$idVaga AND Aluno_idAluno!=$idAlunoSelecionado");

echo "Vaga encerrada com sucesso!";
?>