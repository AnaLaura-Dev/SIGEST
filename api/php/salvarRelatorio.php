<?php
session_start();
require_once "conexao.php";

$idAluno = $_SESSION['idAluno'];

// Buscar dados do aluno
$sqlAluno = "SELECT nome, matricula FROM Aluno WHERE idAluno = ?";
$stmtA = $conn->prepare($sqlAluno);
$stmtA->bind_param("i", $idAluno);
$stmtA->execute();
$aluno = $stmtA->get_result()->fetch_assoc();

// Buscar relatórios enviados
$sqlRel = "SELECT idRelatorio, dataEnvio, tipoRelatorio, status, caminhoArquivo
           FROM Relatorio
           WHERE Aluno_idAluno = ?
           ORDER BY tipoRelatorio ASC";
$stmtR = $conn->prepare($sqlRel);
$stmtR->bind_param("i", $idAluno);
$stmtR->execute();
$relatorios = $stmtR->get_result();

// Carrega a view
require "enviarRelatorioView.php";
?>
