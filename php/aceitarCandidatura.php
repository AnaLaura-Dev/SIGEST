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

$conn->begin_transaction();

try {
    // pega a vaga relacionada a essa candidatura e verifica empresa 
    $sql = "SELECT Vaga_idVaga FROM candidatura WHERE idCandidatura = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idCandidatura);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows === 0) throw new Exception("Candidatura não encontrada");
    $row = $res->fetch_assoc();
    $idVaga = (int) $row['Vaga_idVaga'];
    $stmt->close();

    // verifica se a vaga pertence à empresa logada
    $sql = "SELECT Empresa_idEmpresa FROM vaga WHERE idVaga = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idVaga);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    if ((int)$row['Empresa_idEmpresa'] !== (int)$_SESSION['idEmpresa']) {
        throw new Exception("Permissão negada");
    }
    $stmt->close();

    // marca vaga como preenchida
    $sql = "UPDATE vaga SET status = 'preenchida' WHERE idVaga = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idVaga);
    $stmt->execute();
    $stmt->close();

    // marca a candidatura selecionada como 'aceita'
    $sql = "UPDATE candidatura SET status = 'aceita' WHERE idCandidatura = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idCandidatura);
    $stmt->execute();
    $stmt->close();

    // marca as outras candidaturas da mesma vaga como 'recusada'
    $sql = "UPDATE candidatura SET status = 'recusada' WHERE Vaga_idVaga = ? AND idCandidatura != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $idVaga, $idCandidatura);
    $stmt->execute();
    $stmt->close();

    $conn->commit();
    echo "Candidato selecionado e vaga encerrada com sucesso.";
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo "Erro: " . $e->getMessage();
}
$conn->close();
?>
