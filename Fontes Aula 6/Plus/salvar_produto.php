<?php
require_once 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $conn->real_escape_string($_POST['nome']);
    $descricao = $conn->real_escape_string($_POST['descricao']);
    $preco = filter_var($_POST['preco'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $quantidade = filter_var($_POST['quantidade'], FILTER_SANITIZE_NUMBER_INT);

    $sql = "INSERT INTO produtos (nome, descricao, preco, quantidade) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssdi", $nome, $descricao, $preco, $quantidade);
        if ($stmt->execute()) {
            // Redireciona com mensagem de sucesso
            header("Location: cadatrar.php?status=sucesso&acao=cadastrado");
        } else {
            // Redireciona com mensagem de erro
            header("Location: cadastrar.php?status=erro&acao=cadastrar");
        }
        $stmt->close();
    } else {
        header("Location: cadastrar.php?status=erro&acao=cadastrar");
    }
} else {
    header("Location: cadastrar.php");
}
$conn->close();
?>