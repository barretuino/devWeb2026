<?php
require_once 'conexao.php';

$erro = '';

// Processa a tentativa de login via método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    if (!empty($email) && !empty($senha)) {
        // Busca o usuário no banco pelo email
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $usuario = $stmt->fetch();

        // Valida a senha usando o hash BCRYPT
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            header("Location: produtos.php");
            exit;
        } else {
            $erro = "E-mail ou senha inválidos.";
        }
    } else {
        $erro = "Preencha todos os campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistema de Estoque</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body class="login-body">
    <div class="card login-card">
        <h2>Entrar no Sistema</h2>
        <?php if ($erro): ?>
            <p style="color: red; margin: 1rem 0;"><?= $erro ?></p>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>E-mail</label>
                <input type="email" name="email" required placeholder="admin@teste.com">
            </div>
            <div class="form-group">
                <label>Senha</label>
                <input type="password" name="senha" required placeholder="123">
            </div>
            <button type="submit" class="btn" style="width: 100%;">Acessar</button>
        </form>
    </div>
</body>
</html>