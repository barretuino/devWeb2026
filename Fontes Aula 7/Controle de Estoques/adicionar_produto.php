<?php
include 'config.php';

// Busca categorias e fornecedores para os dropdowns
$sql_categorias = "SELECT id, nome FROM categorias";
$result_categorias = $conn->query($sql_categorias);

$sql_fornecedores = "SELECT id, nome FROM fornecedores";
$result_fornecedores = $conn->query($sql_fornecedores);

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Produto</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h1>Adicionar Novo Produto</h1>
    <a href="index.php" class="btn">Voltar para a Lista</a>
    <form action="salvar_produto.php" method="POST">
        <div class="form-group">
            <label for="nome">Nome do Produto:</label>
            <input type="text" id="nome" name="nome" required>
        </div>
        <div class="form-group">
            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao"></textarea>
        </div>
        <div class="form-group">
            <label for="preco">Preço:</label>
            <input type="number" id="preco" name="preco" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="estoque">Quantidade em Estoque:</label>
            <input type="number" id="estoque" name="estoque" required>
        </div>
        <div class="form-group">
            <label for="categoria">Categoria:</label>
            <select id="categoria" name="categoria_id">
                <option value="">Selecione uma Categoria</option>
                <?php
                if ($result_categorias->num_rows > 0) {
                    while($row = $result_categorias->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['nome'] . "</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="fornecedor">Fornecedor:</label>
            <select id="fornecedor" name="fornecedor_id">
                <option value="">Selecione um Fornecedor</option>
                <?php
                if ($result_fornecedores->num_rows > 0) {
                    while($row = $result_fornecedores->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['nome'] . "</option>";
                    }
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn">Salvar Produto</button>
    </form>
</div>
</body>
</html>