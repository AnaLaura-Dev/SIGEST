<?php
session_start();
require_once "conexao.php";

if (!isset($_SESSION['idInstituicao'])) {
    echo json_encode(["erro" => "Instituição não autenticada"]);
    exit;
}

$idInstituicao = $_SESSION['idInstituicao'];

$data = json_decode(file_get_contents("php://input"), true);

$idDocumento = $data['idDocumento'] ?? null;
$novoStatus = $data['status'] ?? null;

if (!$idDocumento || !$novoStatus) {
    echo json_encode(["erro" => "Dados incompletos"]);
    exit;
}

//se o documento pertence a instituição logada
$sqlVerifica = "SELECT Instituicao_idInstituicao FROM Documento WHERE idDocumento = ?";
$stmt = $conn->prepare($sqlVerifica);
$stmt->bind_param("i", $idDocumento);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if (!$result || $result['Instituicao_idInstituicao'] != $idInstituicao) {
    echo json_encode(["erro" => "Acesso negado"]);
    exit;
}

// atualiza status
$sqlUpdate = "UPDATE Documento SET status = ? WHERE idDocumento = ?";
$stmt2 = $conn->prepare($sqlUpdate);
$stmt2->bind_param("si", $novoStatus, $idDocumento);

if ($stmt2->execute()) {
    echo json_encode(["sucesso" => true]);
} else {
    echo json_encode(["erro" => "Falha ao atualizar o status"]);
}
?>
