CREATE DATABASE seu_banco;

USE seu_banco;

CREATE TABLE fornecedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    cnpj VARCHAR(20) NOT NULL,
    telefone VARCHAR(20)
);