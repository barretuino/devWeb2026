<?php
require_once 'conexao.php';
verificarAutenticacao(); // Garante o acesso apenas para usuários autenticados

$mensagemSucesso = '';
$mensagemErro = '';

// PROCESSAMENTO DE EXCLUSÃO (DELETE)
if (isset($_GET['acao']) && $_GET['acao'] === 'deletar') {
    $idDeletar = (int)$_GET['id'];

    // REGRA DE SEGURANÇA: O usuário logado não pode deletar a si mesmo!
    if ($idDeletar === (int)$_SESSION['usuario_id']) {
        $mensagemErro = "Você não pode excluir sua própria conta enquanto está conectado!";
    } else {
        // Exclusão do registro via prepared statement
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
        if ($stmt->execute(['id' => $idDeletar])) {
            $mensagemSucesso = "Usuário removido com sucesso!";
        } else {
            $mensagemErro = "Erro ao tentar remover o usuário.";
        }
    }
}

// BUSCA DE REGISTROS (READ)
$stmt = $pdo->query("SELECT id, nome, email FROM usuarios ORDER BY id DESC");
$usuarios = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gestão de Usuários - Sistema de Estoque</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <!-- Menu Superior -->
    <nav class="navbar">
        <h2>Gestão de Usuários</h2>
        <div>
            <a href="produtos.php" style="color: var(--primary); margin-right: 15px; font-weight: 600;">Produtos</a>
            <a href="cadastrar_usuario.php" style="color: var(--primary); margin-right: 15px; font-weight: 600;">+ Novo Usuário</a>
            <span>Olá, <strong><?= htmlspecialchars($_SESSION['usuario_nome']) ?></strong></span> | 
            <a href="logout.php">Sair</a>
        </div>
    </nav>

    <div class="container">
        <!-- Mensagens de Feedback -->
        <?php if ($mensagemSucesso): ?>
            <div class="card" style="border-left: 5px solid green; padding: 1rem; margin-bottom: 1rem;">
                <p style="color: green; font-weight: bold;"><?= $mensagemSucesso ?></p>
            </div>
        <?php endif; ?>

        <?php if ($mensagemErro): ?>
            <div class="card" style="border-left: 5px solid red; padding: 1rem; margin-bottom: 1rem;">
                <p style="color: red; font-weight: bold;"><?= $mensagemErro ?></p>
            </div>
        <?php endif; ?>

        <!-- Tabela de Listagem de Usuários -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3>Usuários Cadastrados</h3>
                <a href="cadastrar_usuario.php" class="btn">+ Adicionar Usuário</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($usuarios) > 0): ?>
                        <?php foreach ($usuarios as $u): ?>
                            <tr>
                                <td><?= $u['id'] ?></td>
                                <td>
                                    <?= htmlspecialchars($u['nome']) ?>
                                    <?php if ($u['id'] == $_SESSION['usuario_id']): ?>
                                        <span style="font-size: 0.75rem; background: #e0e7ff; color: #3730a3; padding: 2px 6px; border-radius: 4px; margin-left: 5px;">Você</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td>
                                    <?php if ($u['id'] != $_SESSION['usuario_id']): ?>
                                        <a href="usuarios.php?acao=deletar&id=<?= $u['id'] ?>" 
                                           class="btn btn-danger" 
                                           style="padding: 0.25rem 0.5rem; font-size: 0.8rem;"
                                           onclick="return confirm('Tem certeza que deseja remover o usuário <?= htmlspecialchars($u['nome']) ?>?')">
                                            Excluir
                                        </a>
                                    <?php else: ?>
                                        <span style="color: #9ca3af; font-size: 0.85rem; font-style: italic;">Ativo no momento</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">Nenhum usuário cadastrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>