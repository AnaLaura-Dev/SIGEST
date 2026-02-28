<?php
session_start();
require_once "conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "<script>alert('Acesso inválido!'); window.location.href = 'login/logininstituicao.html';</script>";
    exit;
}

$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

if ($email === '' || $senha === '') {
    echo "<script>alert('Preencha todos os campos!'); window.history.back();</script>";
    exit;
}

// busca instituição
$sql = "SELECT idInstituicao, nome, email, senha FROM instituicao WHERE email = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {

    $instituicao = $result->fetch_assoc(); // <--- AQUI ESTAVA O ERRO!

    if ($instituicao['senha'] === $senha) {

        // salva corretamente na sessão
        $_SESSION['idInstituicao'] = $instituicao['idInstituicao'];
        $_SESSION['nomeInstituicao'] = $instituicao['nome'];

        echo "<script>
                alert('Login realizado com sucesso!');
                window.location.href = 'Instituição/inicio.html';
              </script>";
        exit;
    } else {
        echo "<script>alert('Senha incorreta!'); window.history.back();</script>";
        exit;
    }
} else {
    echo "<script>alert('Email não encontrado!'); window.history.back();</script>";
    exit;
}

$stmt->close();
$conn->close();
?>
