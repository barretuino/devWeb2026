<?php
require_once 'conexao.php';
verificarAutenticacao(); // Restringe o cadastro apenas para usuários já logados

$mensagemSucesso = '';
$mensagemErro = '';

// Processa o envio do formulário de cadastro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    // Validações básicas de preenchimento
    if (!empty($nome) && !empty($email) && !empty($senha)) {
        
        // 1. Verifica se o e-mail já existe no banco de dados
        $stmtCheck = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email");
        $stmtCheck->execute(['email' => $email]);

        if ($stmtCheck->rowCount() > 0) {
            $mensagemErro = "Este e-mail já está cadastrado no sistema!";
        } else {
            // 2. GERAÇÃO DO HASH BCRYPT
            // PASSWORD_DEFAULT utiliza o algoritmo BCRYPT padrão e atualizado do PHP
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            // 3. Inserção do usuário com a senha criptografada
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
            
            if ($stmt->execute(['nome' => $nome, 'email' => $email, 'senha' => $senhaHash])) {
                $mensagemSucesso = "Usuário cadastrado com sucesso!";
            } else {
                $mensagemErro = "Erro ao cadastrar o usuário no banco de dados.";
            }
        }
    } else {
        $mensagemErro = "Por favor, preencha todos os campos do formulário.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Usuário - Sistema de Estoque</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <!-- Menu Superior -->
    <nav class="navbar">
        <h2>Gestão de Estoque</h2>
        <div>
            <a href="produtos.php" style="color: var(--primary); margin-right: 15px;">Produtos</a>
            <span>Olá, <strong><?= htmlspecialchars($_SESSION['usuario_nome']) ?></strong></span> | 
            <a href="logout.php">Sair</a>
        </div>
    </nav>

    <div class="container">
        <div class="card" style="max-width: 500px; margin: 2rem auto;">
            <h3>Novo Usuário</h3>
            <br>

            <!-- Feedback para o usuário -->
            <?php if ($mensagemSucesso): ?>
                <p style="color: green; font-weight: bold; margin-bottom: 1rem;"><?= $mensagemSucesso ?></p>
            <?php endif; ?>

            <?php if ($mensagemErro): ?>
                <p style="color: red; font-weight: bold; margin-bottom: 1rem;"><?= $mensagemErro ?></p>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Nome Completo</label>
                    <input type="text" name="nome" required placeholder="Ex: Maria Silva">
                </div>

                <div class="form-group">
                    <label>E-mail de Acesso</label>
                    <input type="email" name="email" required placeholder="maria@email.com">
                </div>

                <div class="form-group">
                    <label>Senha</label>
                    <input type="password" name="senha" required minlength="4" placeholder="Sua senha">
                </div>

                <button type="submit" class="btn">Cadastrar Usuário</button>
                <a href="produtos.php" class="btn btn-warning" style="margin-left: 10px;">Voltar</a>
            </form>
        </div>
    </div>
</body>
</html>