<?php
// Configurações do servidor MySQL do XAMPP
$host = "localhost";
$usuario = "root";
$senha = ""; // Por padrão no XAMPP, a senha é vazia

try {
    // Instancia o PDO para testar a conexão com o servidor de banco de dados
    $pdo = new PDO("mysql:host=$host;charset=utf8", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2 style='color: green;'>Conexão realizada com sucesso!</h2>";
    echo "<p>O servidor MySQL está rodando e pronto para receber comandos.</p>";
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>Erro na conexão:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>