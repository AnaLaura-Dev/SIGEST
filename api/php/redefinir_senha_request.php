<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistema de Gerenciamento de Estágios</title>
<link rel="stylesheet" href="/css/esqueceuasenha.css">

</head>

<body>
  <header>
    <nav>
      <div class="logo">
        <img src="/imagens/logosigest.gif">
      </div>
      <div class="menu">
        <a href="/documentos/Manual do usuário -SIGEST.pdf">Suporte</a>
        <a href="/entrar como/entrar.html">Entrar</a>
        <a href="/cadastro/cadastrarcomo.html">Cadastre-se</a>
      </nav>
    </div>
  </header>
<body>

<div class="background-box"></div>
  <div class="container">
<h2>Redefinir senha</h2>
<p>Digite seu e-mail cadastrado.</p>

<form method="POST" action="redefinir_senha_send.php">
    <label>E-mail</label>
    <input type="email" name="email" required>
    <button class="btn-enviar">Enviar</button>
</form>

<a href="login.php"><button class="btn-voltar">Voltar</button></a>

</div>
</div>
</body>
</html>
