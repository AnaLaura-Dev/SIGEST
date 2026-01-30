<?php
session_start();
require_once "conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "<script>alert('Acesso inválido!'); window.location.href = '/sigest/login/loginempresa.html';</script>";
    exit;
}

$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

if ($email === '' || $senha === '') {
    echo "<script>alert('Preencha todos os campos!'); window.history.back();</script>";
    exit;
}

// procura a empresa pelo email
$sql = "SELECT idEmpresa, nome, email, senha
 FROM empresa WHERE email = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $empresa = $result->fetch_assoc();

    
    if ($empresa['senha'] === $senha) {
        $_SESSION['idEmpresa'] = $empresa['idEmpresa'];
        $_SESSION['nomeEmpresa'] = $empresa['nome'];

        echo "<script>
            alert('Login realizado com sucesso!');
            window.location.href = '/sigest/Empresa/inicio.html';
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
