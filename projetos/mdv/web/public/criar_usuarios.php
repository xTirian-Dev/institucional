<?php
require_once __DIR__ . '/../app/core/Database.php';

$db = Database::getConnection();

// Criar tabela se não existir
$sql = "CREATE TABLE IF NOT EXISTS usuarios (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT NOT NULL UNIQUE,
    senha TEXT NOT NULL,
    nome TEXT
)";
$db->exec($sql);

// Inserir usuário de teste
$sql2 = "INSERT OR IGNORE INTO usuarios (email, senha, nome) 
         VALUES ('victor.mdvconsorcios@gmail.com', 'Duda2509#', 'Victor'),
            ('stephanydefreitas@mdvconsorcios.com.br', '123456', 'Stephany de Freitas'),
            ('vivianepontes@mdvconsorcios.com.br', '123456', 'Viviane Pontes'),
            ('victorsouza@mdvconsorcios.com.br', '123456', 'Victor Souza');";
$db->exec($sql2);

echo "Tabela criada e usuário inserido!";
