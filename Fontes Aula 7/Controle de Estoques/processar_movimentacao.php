<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $produto_id = $_POST['produto_id'];
    $tipo = $_POST['tipo'];
    $quantidade = $_POST['quantidade'];
    $observacao = $_POST['observacao'];

    $conn->begin_transaction();

    try {
        // 1. Insere o registro na tabela de movimentações
        $sql_insert = "INSERT INTO movimentacoes (produto_id, tipo, quantidade, observacao) VALUES (?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("isis", $produto_id, $tipo, $quantidade, $observacao);
        if (!$stmt_insert->execute()) {
            throw new Exception($stmt_insert->error);
        }
        $stmt_insert->close();

        // 2. Atualiza a quantidade em estoque na tabela de produtos
        if ($tipo == 'entrada') {
            $sql_update = "UPDATE produtos SET quantidade_estoque = quantidade_estoque + ? WHERE id = ?";
        } else {
            $sql_update = "UPDATE produtos SET quantidade_estoque = quantidade_estoque - ? WHERE id = ?";
        }
        
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ii", $quantidade, $produto_id);
        if (!$stmt_update->execute()) {
            throw new Exception($stmt_update->error);
        }
        $stmt_update->close();

        $conn->commit();
        header("Location: movimentacoes.php");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        echo "Erro ao registrar movimentação: " . $e->getMessage();
    }

    $conn->close();
}
?>