<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $estoque = $_POST['estoque'];
    $categoria_id = $_POST['categoria_id'] ?? null;
    $fornecedor_id = $_POST['fornecedor_id'] ?? null;

    // Use prepared statements para prevenir SQL Injection
    $sql = "INSERT INTO produtos (nome, descricao, preco, quantidade_estoque, categoria_id, fornecedor_id)
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdiis", $nome, $descricao, $preco, $estoque, $categoria_id, $fornecedor_id);

    if ($stmt->execute()) {
        // Redireciona de volta para a tela principal
        header("Location: index.php");
        exit();
    } else {
        echo "Erro ao adicionar produto: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>