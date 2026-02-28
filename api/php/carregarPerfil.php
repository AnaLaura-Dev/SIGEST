<?php
session_start();
require_once "conexao.php";

// Verifica se o aluno está logado
if (!isset($_SESSION['idAluno'])) {
    echo json_encode(["erro" => "Usuário não autenticado"]);
    exit;
}

$idAluno = (int) $_SESSION['idAluno'];


$sql = "SELECT  a.nome,  a.email,   p.sobrevoc,  p.foto,  p.curriculo FROM Aluno a
 LEFT JOIN Perfil p ON p.Aluno_idAluno = a.idAluno WHERE a.idAluno = ? LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idAluno);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["erro" => "Perfil não encontrado"]);
    exit;
}

$perfil = $result->fetch_assoc();

// converte imagem e currículo em Base64, se existirem
if (!empty($perfil['foto'])) {
    $perfil['foto'] = "data:image/jpeg;base64," . base64_encode($perfil['foto']);
}

if (!empty($perfil['curriculo'])) {
    $perfil['curriculo'] = "data:application/pdf;base64," . base64_encode($perfil['curriculo']);
}

// retorna tudo como JSON
header('Content-Type: application/json; charset=utf-8');
echo json_encode($perfil, JSON_UNESCAPED_UNICODE);

$stmt->close();
$conn->close();
?>
