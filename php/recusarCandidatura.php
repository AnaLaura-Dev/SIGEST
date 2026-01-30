<?php
session_start();
require_once "conexao.php";

if (!isset($_SESSION['idEmpresa'])) {
    http_response_code(401);
    echo "Acesso negado";
    exit;
}

$idCandidatura = isset($_POST['idCandidatura']) ? (int) $_POST['idCandidatura'] : 0;
if ($idCandidatura <= 0) {
    http_response_code(400);
    echo "ID inválido"; 
    exit;
}

// verificar que a vaga pertence a empresa logada no site 
$sql = "SELECT v.Empresa_idEmpresa FROM candidatura c JOIN vaga v ON c.Vaga_idVaga = v.idVaga WHERE c.idCandidatura = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idCandidatura);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    http_response_code(404);
    echo "Candidatura não encontrada";
    exit;
}
$row = $res->fetch_assoc();
if ((int)$row['Empresa_idEmpresa'] !== (int)$_SESSION['idEmpresa']) {
    http_response_code(403);
    echo "Permissão negada";
    exit;
}
$stmt->close();

// atualizar a vaga
$sql = "UPDATE candidatura SET status = 'recusada' WHERE idCandidatura = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idCandidatura);
if ($stmt->execute()) {
    echo "Candidatura recusada com sucesso.";
} else {
    http_response_code(500);
    echo "Erro ao recusar: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
