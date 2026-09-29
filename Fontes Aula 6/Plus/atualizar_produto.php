<?php
require_once 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $nome = $conn->real_escape_string($_POST['nome']);
    $descricao = $conn->real_escape_string($_POST['descricao']);
    $preco = filter_var($_POST['preco'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $quantidade = filter_var($_POST['quantidade'], FILTER_SANITIZE_NUMBER_INT);

    // Validação básica para garantir que o ID foi fornecido
    if (empty($id)) {
        header("Location: index.php?status=erro");
        exit();
    }

    $sql = "UPDATE produtos SET nome = ?, descricao = ?, preco = ?, quantidade = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssdsi", $nome, $descricao, $preco, $quantidade, $id);
        if ($stmt->execute()) {
            header("Location: index.php?status=sucesso&acao=atualizado");
        } else {
            header("Location: index.php?status=erro&acao=atualizar");
        }
        $stmt->close();
    } else {
        header("Location: index.php?status=erro&acao=atualizar");
    }
} else {
    header("Location: index.php");
}
$conn->close();
?>