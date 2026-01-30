<?php
require_once "conexao.php";

$id = $_GET['id'];

$sql = "SELECT arquivo FROM Relatorio WHERE idRelatorio = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($arquivo);
$stmt->fetch();

header("Content-Type: application/pdf");
header("Content-Disposition: attachment; filename=relatorio.pdf");
echo $arquivo;
?>
