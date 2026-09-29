<?php
$servidor = "localhost"; // Geralmente é 'localhost'
$usuario = "root";       // Substitua pelo seu usuário do MySQL
$senha = "";             // Substitua pela sua senha do MySQL
$banco = "seubanco";     // Substitua pelo nome do seu banco de dados

// Cria a conexão
$conn = new mysqli($servidor, $usuario, $senha, $banco);

// Checa a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>