<?php
session_start();
require_once "conexao.php"; 

if (!isset($_SESSION['idAluno'])) {
    echo "Erro: Aluno não identificado. Faça login novamente.";
    exit;
}

$idAluno = $_SESSION['idAluno'];
$nomeAluno = $_POST['nome'] ?? '';
$matricula = $_POST['matricula'] ?? '';
$idInstituicao = $_POST['idInstituicao'] ?? ''; 
if (empty($idInstituicao)) {
    echo "Selecione uma instituição.";
    exit;
}

// cria nome da pasta
$nomePasta = preg_replace('/[^a-zA-Z0-9_-]/', '_', $nomeAluno . '_' . $matricula);

// cria a pasta uploads e a subpasta do aluno
$uploadDir = __DIR__ . "/../uploads/$nomePasta/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// verifica se os arquivos foram enviados
if (isset($_FILES['tco']) && isset($_FILES['tce'])) {

    $arquivos = [
        'tco' => $_FILES['tco'],
        'tce' => $_FILES['tce']
    ];

    foreach ($arquivos as $tipo => $file) {
        if ($file['error'] === UPLOAD_ERR_OK) {
            $nomeOriginal = basename($file['name']);
            $extensao = pathinfo($nomeOriginal, PATHINFO_EXTENSION);
            $novoNome = uniqid($tipo . '_') . '.' . $extensao;
            $destino = $uploadDir . $novoNome;

            if (move_uploaded_file($file['tmp_name'], $destino)) {
                $caminhoBanco = "uploads/$nomePasta/$novoNome";

                $sql = "INSERT INTO Documento 
                        (tipoDoc, arquivo, status, dataEnvio, Aluno_idAluno, Instituicao_idInstituicao)
                        VALUES (?, ?, 'pendente', NOW(), ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssii", $tipo, $caminhoBanco, $idAluno, $idInstituicao);
                $stmt->execute();
                $stmt->close();
            } else {
                echo "Erro ao mover o arquivo $nomeOriginal.<br>";
            }
        } else {
            echo "Erro no upload de $tipo.<br>";
        }
    }

    echo "Documentos enviados com sucesso para a instituição!";
} else {
    echo "Envie os dois documentos (Termo e Plano).";
}
?>
