<?php
include 'config.php';

// Busca as movimentações e os nomes dos produtos relacionados
$sql = "SELECT m.id, p.nome AS nome_produto, m.tipo, m.quantidade, m.data_movimentacao, m.observacao
        FROM movimentacoes m
        JOIN produtos p ON m.produto_id = p.id
        ORDER BY m.data_movimentacao DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Movimentações de Estoque</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h1>Histórico de Movimentações</h1>
    <a href="index.php" class="btn">Voltar para a Lista de Produtos</a>
    <a href="registrar_movimentacao.php" class="btn">Registrar Nova Movimentação</a>

    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Tipo</th>
                <th>Quantidade</th>
                <th>Data</th>
                <th>Observação</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["nome_produto"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["tipo"]) . "</td>";
                echo "<td>" . $row["quantidade"] . "</td>";
                echo "<td>" . $row["data_movimentacao"] . "</td>";
                echo "<td>" . htmlspecialchars($row["observacao"]) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Nenhuma movimentação registrada.</td></tr>";
        }
        $conn->close();
        ?>
        </tbody>
    </table>
</div>
</body>
</html>