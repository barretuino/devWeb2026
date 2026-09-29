<?php
// Inclui o arquivo de conexão com o banco de dados
require_once 'conexao.php';

// SQL para selecionar todos os produtos
$sql = "SELECT id, nome, preco, quantidade FROM produtos ORDER BY id DESC";
$resultado = $conn->query($sql);

$produtos = [];
if ($resultado->num_rows > 0) {
    while($row = $resultado->fetch_assoc()) {
        $produtos[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciamento de Produtos</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { max-width: 900px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #333; }
        .btn-adicionar {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .btn-adicionar:hover { background-color: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .acoes a { margin-right: 5px; text-decoration: none; }
        .btn-editar { color: #28a745; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Lista de Produtos</h2>
            <a href="cadastrar.php" class="btn-adicionar">Adicionar Novo Produto</a>
        </div>
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
                    <?php foreach ($produtos as $produto): ?>
                        <tr>
                            <td><?= htmlspecialchars($produto['id']) ?></td>
                            <td><?= htmlspecialchars($produto['nome']) ?></td>
                            <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                            <td><?= htmlspecialchars($produto['quantidade']) ?></td>
                            <td class="acoes">
                                <a href="editar.php?id=<?= htmlspecialchars($produto['id']) ?>" class="btn-editar">Editar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Nenhum produto encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>