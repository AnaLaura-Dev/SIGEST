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
// Pegar os dados do formulário
$nome = $_POST['nome'] ;
$CNPJ = $_POST['CNPJ'] ;
$CEP = $_POST['CEP'] ;
$logradouro = $_POST['logradouro'] ;
$email = $_POST['email'] ;
$senha = $_POST['senha'] ;
$nomerepre = $_POST['nomerepre'];
$emailrepre = $_POST['emailrepre'];
$celularepre = $_POST['celularepre'];

// Inserir os dados no banco
$sql = "INSERT INTO empresa (nome, CNPJ, CEP, logradouro, email, senha, nomerepre, emailrepre, celularepre)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssss", $nome, $CNPJ, $CEP, $logradouro, $email, $senha, $nomerepre, $emailrepre, $celularepre);

if ($stmt->execute()) {
    echo "<script>
        alert('Você foi cadastrado com sucesso! Bem-vindo ao SIGEST!');
        window.location.href = '/sigest/Empresa/inicio.html';
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
