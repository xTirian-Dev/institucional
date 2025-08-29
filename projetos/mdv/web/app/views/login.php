<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Login - MVP</title>
<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    form { max-width: 300px; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
    input { width: 100%; padding: 10px; margin-bottom: 15px; }
    input[type="submit"] { background-color: #CC092F; color: white; border: none; cursor: pointer; }
    .erro { color: red; text-align: center; margin-bottom: 10px; }
</style>
</head>
<body>
<h2>Logo</h2>
<?php
session_start();
if (isset($_SESSION['erro'])) {
    echo '<p class="erro">'.$_SESSION['erro'].'</p>';
    unset($_SESSION['erro']);
}
?>
<form action="?url=login/autenticar" method="post">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="senha" placeholder="Senha" required>
    <input type="submit" value="Entrar">
</form>
</body>
</html>
