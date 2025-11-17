<?php
$dbPath = __DIR__ . '/tarefas.db';
try {
$conn = new PDO("sqlite:$dbPath");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$conn->exec("CREATE TABLE IF NOT EXISTS usuarios (
id INTEGER PRIMARY KEY AUTOINCREMENT,
nome TEXT NOT NULL,
email TEXT NOT NULL UNIQUE,
senha TEXT NOT NULL
);");


$conn->exec("CREATE TABLE IF NOT EXISTS tarefas (
id INTEGER PRIMARY KEY AUTOINCREMENT,
usuario_id INTEGER NOT NULL,
titulo TEXT NOT NULL,
descricao TEXT,
concluida INTEGER DEFAULT 0,
criado_em TEXT DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY(usuario_id) REFERENCES usuarios(id)
);");


echo "Banco criado com sucesso: $dbPath\n";
} catch (Exception $e) {
echo 'Erro: ' . $e->getMessage();
}