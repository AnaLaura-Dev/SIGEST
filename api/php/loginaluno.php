<?php
session_start();
require_once "conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "<script>alert('Acesso inválido!'); window.location.href = '/login/loginaluno.html';</script>";
    exit;
} //se n tiver login

$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

if ($email === '' || $senha === '') {
    echo "<script>alert('Preencha todos os campos!'); window.history.back();</script>";
    exit;
} //condição se caso o usuario nao preencher algum campo

// procura o aluno pelo email
$sql = "SELECT idAluno, nome, email, senha FROM Aluno WHERE email = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

//condição com o resultado da procura
if ($result->num_rows === 1) {
    $aluno = $result->fetch_assoc();

    if ($aluno['senha'] === $senha) {
        $_SESSION['idAluno'] = $aluno['idAluno'];
        $_SESSION['nomeAluno'] = $aluno['nome'];
         $_SESSION['emailAluno'] = $aluno['email'];

        echo "<script>
            alert('Login realizado com sucesso!');
            window.location.href = '/AreaDoAluno/inicio.html';
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
