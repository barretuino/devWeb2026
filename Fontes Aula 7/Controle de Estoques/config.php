<?php
$servername = "localhost";
$username = "seu_usuario"; // Substitua pelo seu usuário do MySQL
$password = "sua_senha";   // Substitua pela sua senha
$dbname = "controle_estoque";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checa a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>