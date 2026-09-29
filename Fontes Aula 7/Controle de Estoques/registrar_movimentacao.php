<?php
include 'config.php';

// Busca todos os produtos para o dropdown
$sql_produtos = "SELECT id, nome FROM produtos ORDER BY nome";
$result_produtos = $conn->query($sql_produtos);

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Registrar Movimentação</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h1>Registrar Movimentação de Estoque</h1>
    <a href="movimentacoes.php" class="btn">Ver Histórico de Movimentações</a>
    <form action="processar_movimentacao.php" method="POST">
        <div class="form-group">
            <label for="produto">Produto:</label>
            <select id="produto" name="produto_id" required>
                <option value="">Selecione um Produto</option>
                <?php
                if ($result_produtos->num_rows > 0) {
                    while($row = $result_produtos->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['nome']) . "</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="tipo">Tipo de Movimentação:</label>
            <select id="tipo" name="tipo" required>
                <option value="entrada">Entrada</option>
                <option value="saida">Saída</option>
            </select>
        </div>
        <div class="form-group">
            <label for="quantidade">Quantidade:</label>
            <input type="number" id="quantidade" name="quantidade" min="1" required>
        </div>
        <div class="form-group">
            <label for="observacao">Observação:</label>
            <textarea id="observacao" name="observacao"></textarea>
        </div>
        <button type="submit" class="btn">Registrar Movimentação</button>
    </form>
</div>
</body>
</html>