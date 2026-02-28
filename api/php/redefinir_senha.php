<?php
include("conexao.php");

$token = $_GET['token'] ?? $_POST['token'] ?? '';
$erro = '';

if (!$token) {
    die("Token inválido.");
}

// Busca token válido
$stmt = $conn->prepare("SELECT email 
                        FROM password_resets
                        WHERE token = ?
                        AND expires_at > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows !== 1) {
    die("Token inválido ou expirado. <a href='redefinir_senha_request.php'>Solicitar novo</a>");
}

$email = $res->fetch_assoc()['email'];

// Envio do formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $senha = $_POST['senha'];
    $confirma = $_POST['senha_confirm'];

    if ($senha !== $confirma) {
        $erro = "As senhas não coincidem.";
    } elseif (strlen($senha) < 6) {
        $erro = "A senha deve ter no mínimo 6 caracteres.";
    } else {

        $novaSenha = password_hash($senha, PASSWORD_DEFAULT);

        // Atualiza senha na tabela correta
        $tabelas = ['aluno', 'empresa', 'instituicao'];

        foreach ($tabelas as $tabela) {
            $stmt = $conn->prepare("UPDATE $tabela SET senha = ? WHERE email = ?");
            $stmt->bind_param("ss", $novaSenha, $email);
            $stmt->execute();

            if ($stmt->affected_rows > 0) break;
        }

        // Apaga token
        $del = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
        $del->bind_param("s", $token);
        $del->execute();

        header("Location: login.php?reset=ok");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
    
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistema de Gerenciamento de Estágios</title>
 <link rel="stylesheet" href="css/esqueceuasenha.css">

</head>
<body>

<h2>Nova senha</h2>

<?php if ($erro): ?>
<p style="color:red"><?=$erro?></p>
<?php endif; ?>

<form method="POST">
    <input type="hidden" name="token" value="<?=$token?>">
   
  <div class="container">
    <label>Nova senha</label>
    <input type="password" name="senha" required>

    <label>Confirmar senha</label>
    <input type="password" name="senha_confirm" required>

    <button>Salvar</button>
</form>
 </div>
</body>
</html>
