<?php
include 'config.php';

// Verifica se o ID do produto foi passado na URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID do produto inválido.");
}

$produto_id = $_GET['id'];

// Busca os dados do produto a ser editado
$sql = "SELECT * FROM produtos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $produto_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Produto não encontrado.");
}
$produto = $result->fetch_assoc();

// Busca categorias e fornecedores para os dropdowns
$sql_categorias = "SELECT id, nome FROM categorias";
$result_categorias = $conn->query($sql_categorias);

$sql_fornecedores = "SELECT id, nome FROM fornecedores";
$result_fornecedores = $conn->query($sql_fornecedores);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h1>Editar Produto</h1>
    <a href="index.php" class="btn">Voltar para a Lista</a>
    <form action="salvar_edicao.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
        
        <div class="form-group">
            <label for="nome">Nome do Produto:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($produto['nome']); ?>" required>
        </div>
        <div class="form-group">
            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao"><?php echo htmlspecialchars($produto['descricao']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="preco">Preço:</label>
            <input type="number" id="preco" name="preco" step="0.01" value="<?php echo $produto['preco']; ?>" required>
        </div>
        <div class="form-group">
            <label for="estoque">Quantidade em Estoque:</label>
            <input type="number" id="estoque" name="estoque" value="<?php echo $produto['quantidade_estoque']; ?>" required>
        </div>
        <div class="form-group">
            <label for="categoria">Categoria:</label>
            <select id="categoria" name="categoria_id">
                <option value="">Selecione uma Categoria</option>
                <?php
                if ($result_categorias->num_rows > 0) {
                    while($row = $result_categorias->fetch_assoc()) {
                        $selected = ($row['id'] == $produto['categoria_id']) ? 'selected' : '';
                        echo "<option value='" . $row['id'] . "' " . $selected . ">" . htmlspecialchars($row['nome']) . "</option>";
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
                        $selected = ($row['id'] == $produto['fornecedor_id']) ? 'selected' : '';
                        echo "<option value='" . $row['id'] . "' " . $selected . ">" . htmlspecialchars($row['nome']) . "</option>";
                    }
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn">Salvar Alterações</button>
    </form>
</div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>