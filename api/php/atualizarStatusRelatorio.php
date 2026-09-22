<?php
require_once "conexao.php";

$data = json_decode(file_get_contents("php://input"), true);

$idRelatorio = $data["idRelatorio"];
$status = $data["status"];

$sql = "UPDATE Relatorio SET status = ? WHERE idRelatorio = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $idRelatorio);

if ($stmt->execute()) {
    echo "Status atualizado com sucesso!";
} else {
    echo "Erro ao atualizar status.";
}
?>
