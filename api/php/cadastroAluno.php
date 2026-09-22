<?php
// Conexão com o banco de dados
include('conexao.php');
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}


// Pegar os dados do formulário
$nome = $_POST['nome'];
$CPF = $_POST['CPF'];
$celular = $_POST['celular'];
$CEP = $_POST['CEP'];
$endereco = $_POST['endereco'];
$email = $_POST['email'];
$senha = $_POST['senha'];
$curso = $_POST['curso'];


// Inserir os dados no banco
$sql = "INSERT INTO aluno (nome, CPF, celular, CEP, endereco, email, senha, curso)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssss", $nome, $CPF, $celular, $CEP, $endereco, $email, $senha, $curso);

if ($stmt->execute()) {
    echo "<script>
        alert('Você foi cadastrado com sucesso! Bem-vindo ao SIGEST!');
        window.location.href = '/AreaDoAluno/inicio.html';
    </script>";
} else {
    echo "<script>
        alert('Erro ao cadastrar: " . addslashes($stmt->error) . "');
        window.history.back();
    </script>";
}

$stmt->close();
$conn->close();
?>
