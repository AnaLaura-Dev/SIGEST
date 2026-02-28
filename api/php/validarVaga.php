<?php
session_start();
require_once "conexao.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idVaga = (int) $_POST['idVaga'];
    $acao = $_POST['acao'];

    $novoStatus = ($acao === 'aprovar') ? 'aprovada' : 'recusada';

    $sql = "UPDATE vaga SET status = ? WHERE idVaga = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $novoStatus, $idVaga);
    $stmt->execute();

    if ($acao === 'aprovar') {
        echo "<script>alert('Vaga aprovada! Agora os alunos poderão vê-la.'); window.location.href='instituicao/vagaspendentes.php';</script>";
    } else {
        echo "<script>alert('Vaga recusada. A empresa será notificada.'); window.location.href='instituicao/vagaspendentes.php';</script>";
    }
}
?>
