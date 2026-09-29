<?php
require_once 'conexao.php';

// Destrói todas as variáveis de sessão ativas
session_destroy();

// Redireciona de volta para a tela de login
header("Location: index.php");
exit;
?>