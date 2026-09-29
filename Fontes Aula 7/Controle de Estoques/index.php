<?php
include 'config.php';

// Busca os produtos e suas categorias/fornecedores
$sql = "SELECT p.id, p.nome, p.descricao, p.preco, p.quantidade_estoque, c.nome AS categoria, f.nome AS fornecedor 
        FROM produtos p
        LEFT JOIN categorias c ON p.categoria_id = c.id
        LEFT JOIN fornecedores f ON p.fornecedor_id = f.id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Controle de Estoque</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h1>Produtos em Estoque</h1>
    <a href="adicionar_produto.php" class="btn">Adicionar Novo Produto</a>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Preço</th>
                <th>Estoque</th>
                <th>Categoria</th>
                <th>Fornecedor</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["nome"] . "</td>";
                echo "<td>" . $row["descricao"] . "</td>";
                echo "<td>R$ " . number_format($row["preco"], 2, ',', '.') . "</td>";
                echo "<td>" . $row["quantidade_estoque"] . "</td>";
                echo "<td>" . ($row["categoria"] ? $row["categoria"] : 'N/A') . "</td>";
                echo "<td>" . ($row["fornecedor"] ? $row["fornecedor"] : 'N/A') . "</td>";
                echo "<td>";
                echo "<a href='editar_produto.php?id=" . $row["id"] . "' class='btn'>Editar</a> ";
                echo "<a href='#' class='btn btn-danger' onclick='confirmarExclusao(" . $row["id"] . ")'>Excluir</a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>Nenhum produto encontrado.</td></tr>";
        }
        $conn->close();
        ?>
        </tbody>
    </table>
</div>

<script>
    function confirmarExclusao(id) {
        if (confirm("Tem certeza que deseja excluir este produto?")) {
            // Se confirmar, redireciona para um script de exclusão
            window.location.href = 'excluir_produto.php?id=' + id;
        }
    }
</script>

</body>
</html>