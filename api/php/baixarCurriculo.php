<?php
require_once "conexao.php";

if (!isset($_GET['idAluno'])) {
    echo "Aluno não especificado.";
    exit;
}

$idAluno = intval($_GET['idAluno']);

$sql = "SELECT curriculo FROM Perfil WHERE Aluno_idAluno = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idAluno);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Currículo não encontrado.";
    exit;
}

$row = $result->fetch_assoc();
if (empty($row['curriculo'])) {
    echo "Este aluno não enviou um currículo.";
    exit;
}

header("Content-Type: application/pdf");
header("Content-Disposition: inline; filename=curriculo_aluno.pdf");
echo $row['curriculo'];
