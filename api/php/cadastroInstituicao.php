<?php
$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$user = getenv('DB_USER');
$pass = getenv('DB_PASSWORD');
$dbname = getenv('DB_NAME');

try {
    // IMPORTANTE: O TiDB exige SSL para funcionar na Vercel
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass,
        [PDO::MYSQL_ATTR_SSL_CA => true] 
    );
    // Se chegar aqui, conectou!
} catch (PDOException $e) {
    die("Erro ao conectar: " . $e->getMessage());
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
        window.location.href = 'Instituição/inicio.html';
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
