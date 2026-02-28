<?php
include("conexao.php");

$email = trim($_POST['email'] ?? '');
$tempo = 60 * 60; // 1h
$mensagem = "Clique no link para redefinir sua senha! ";

if ($email) {

    // Verifica se o email existe em alguma tabela
    function emailExiste($conn, $email) {
        $tabelas = ['aluno', 'empresa', 'instituicao'];
        foreach ($tabelas as $tabela) {
            $stmt = $conn->prepare("SELECT 1 FROM $tabela WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                return true;
            }
        }
        return false;
    }

    if (emailExiste($conn, $email)) {

        // Apaga tokens antigos
        $del = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
        $del->bind_param("s", $email);
        $del->execute();

        // Cria token
        $token = bin2hex(random_bytes(32));
        $expira = date("Y-m-d H:i:s", time() + $tempo);

        // Salva token
        $stmt = $conn->prepare("
            INSERT INTO password_resets (email, token, expires_at) 
            VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $token, $expira);
        $stmt->execute();

        // LINK
        $link = "http://".$_SERVER['HTTP_HOST']."/php/redefinir_senha.php?token=".$token;
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
<style>
body {
    min-height: 100vh;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: Arial, sans-serif;
}

.form-container {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.form-box {
    background: #ffffff;
    width: 100%;
    max-width: 420px;
    padding: 35px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 12px 30px rgba(0,0,0,0.25);
    animation: fadeIn 0.6s ease;
}

.form-box h2 {
    margin-bottom: 10px;
}

.msg {
    margin-top: 15px;
    padding: 12px;
    border-radius: 6px;
    font-size: 14px;
}
    </style>
</head>
<body>

<p><?=$mensagem?></p>

<?php if (!empty($link)): ?>
<p><b> Link:</b> <a href="<?=$link?>" target="_blank">Redefinir senha</a></p>
<?php endif; ?>

</body>
</html>
