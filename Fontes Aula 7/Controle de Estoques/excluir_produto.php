<?php
include 'config.php';

// Verifica se o ID do produto foi passado na URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID do produto inválido.");
}

$produto_id = $_GET['id'];

// Usa prepared statement para excluir o produto
$sql = "DELETE FROM produtos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $produto_id);

if ($stmt->execute()) {
    // Redireciona de volta para a tela principal
    header("Location: index.php");
    exit();
} else {
    echo "Erro ao excluir produto: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>