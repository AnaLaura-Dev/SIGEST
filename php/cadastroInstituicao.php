<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "sigest";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// paga os dados do formulário
$nome = $_POST['nome'] ;
$CNPJ = $_POST['CNPJ'] ;
$CEP = $_POST['CEP'] ;
$logradouro = $_POST['logradouro'] ;
$email = $_POST['email'] ;
$senha = $_POST['senha'] ;




// inserir os dados no banco
$sql = "INSERT INTO instituicao (nome, CNPJ, CEP, logradouro, email, senha)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $nome, $CNPJ, $CEP, $logradouro, $email, $senha);
if ($stmt->execute()) {
    echo "<script>
        alert('Você foi cadastrado com sucesso! Bem-vindo ao SIGEST!');
        window.location.href = '/sigest/Instituição/inicio.html';
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
