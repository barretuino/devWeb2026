<?php
require_once 'conexao.php';
verificarAutenticacao(); // Garante o acesso apenas para usuários logados

// Inicializa variáveis para o formulário de edição/criação
$id = '';
$nome = '';
$preco = '';
$quantidade = '';
$editando = false;

// 1. CREATE & UPDATE (Salvar ou Atualizar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $nome = trim($_POST['nome']);
    $preco = str_replace(',', '.', $_POST['preco']); // Converte padrão visual de vírgula para ponto
    $quantidade = (int)$_POST['quantidade'];

    if (!empty($nome) && $preco >= 0 && $quantidade >= 0) {
        if (!empty($id)) {
            // Atualiza registro existente (UPDATE)
            $stmt = $pdo->prepare("UPDATE produtos SET nome = :nome, preco = :preco, quantidade = :quantidade WHERE id = :id");
            $stmt->execute(['nome' => $nome, 'preco' => $preco, 'quantidade' => $quantidade, 'id' => $id]);
        } else {
            // Insere novo registro (CREATE)
            $stmt = $pdo->prepare("INSERT INTO produtos (nome, preco, quantidade) VALUES (:nome, :preco, :quantidade)");
            $stmt->execute(['nome' => $nome, 'preco' => $preco, 'quantidade' => $quantidade]);
        }
        header("Location: produtos.php");
        exit;
    }
}

// 2. DELETE (Exclusão via parâmetros da URL)
if (isset($_GET['acao']) && $_GET['acao'] === 'deletar') {
    $idDeletar = (int)$_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = :id");
    $stmt->execute(['id' => $idDeletar]);
    header("Location: produtos.php");
    exit;
}

// 3. EDIT (Carrega os dados de um item para alteração)
if (isset($_GET['acao']) && $_GET['acao'] === 'editar') {
    $idEditar = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = :id");
    $stmt->execute(['id' => $idEditar]);
    $produtoEditar = $stmt->fetch();

    if ($produtoEditar) {
        $id = $produtoEditar['id'];
        $nome = $produtoEditar['nome'];
        $preco = $produtoEditar['preco'];
        $quantidade = $produtoEditar['quantidade'];
        $editando = true;
    }
}

// 4. READ (Busca todos os registros para exibição)
$stmt = $pdo->query("SELECT * FROM produtos ORDER BY id DESC");
$produtos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Produtos</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <!-- Menu Superior -->
    <nav class="navbar">
        <h2>Gestão de Produtos</h2>
        <div>
            <span>Olá, <strong><?= htmlspecialchars($_SESSION['usuario_nome']) ?></strong></span> | 
            <a href="logout.php">Sair</a>
        </div>
    </nav>

    <div class="container">
        <!-- Form de Cadastro / Edição -->
        <div class="card">
            <h3><?= $editando ? 'Editar Produto' : 'Novo Produto' ?></h3>
            <form method="POST">
                <input type="hidden" name="id" value="<?= $id ?>">
                
                <div class="form-group">
                    <label>Nome do Produto</label>
                    <input type="text" name="nome" value="<?= htmlspecialchars($nome) ?>" required>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Preço (R$)</label>
                        <input type="number" step="0.01" name="preco" value="<?= $preco ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Quantidade</label>
                        <input type="number" name="quantidade" value="<?= $quantidade ?>" required>
                    </div>
                </div>

                <button type="submit" class="btn"><?= $editando ? 'Atualizar' : 'Salvar' ?></button>
                <?php if ($editando): ?>
                    <a href="produtos.php" class="btn btn-danger">Cancelar</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Tabela de Listagem -->
        <div class="card">
            <h3>Produtos Cadastrados</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Preço</th>
                        <th>Quantidade</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($produtos) > 0): ?>
                        <?php foreach ($produtos as $p): ?>
                            <tr>
                                <td><?= $p['id'] ?></td>
                                <td><?= htmlspecialchars($p['nome']) ?></td>
                                <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
                                <td><?= $p['quantidade'] ?></td>
                                <td>
                                    <a href="produtos.php?acao=editar&id=<?= $p['id'] ?>" class="btn btn-warning" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">Editar</a>
                                    <a href="produtos.php?acao=deletar&id=<?= $p['id'] ?>" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;" onclick="return confirm('Excluir este produto?')">Deletar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Nenhum produto cadastrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>