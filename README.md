codigos do Mysql

-- Criação do banco de dados
CREATE DATABASE sistema;
USE sistema;

-- Tabela de usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    passwd VARCHAR(255) NOT NULL
);

-- Tabela de livros
CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    current_page INT DEFAULT 0,
    total_pages INT NOT NULL,
    rating INT CHECK (rating >= 0 AND rating <= 5),
    review TEXT,
    cover_url VARCHAR(500),
    FOREIGN KEY (user_id) REFERENCES usuarios(id) ON DELETE CASCADE
);
