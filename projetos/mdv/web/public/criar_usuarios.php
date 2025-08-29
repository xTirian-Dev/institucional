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
         VALUES ('teste@teste.com', '123456', 'Usuário Teste')";
$db->exec($sql2);

echo "Tabela criada e usuário inserido!";
