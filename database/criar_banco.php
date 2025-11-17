<?php
// Caminho do banco de dados
$dbPath = __DIR__ . '/database/tarefas.db';

// Criar pasta "database" se não existir
if (!is_dir(__DIR__ . '/database')) {
    mkdir(__DIR__ . '/database', 0755, true);
}

try {
    // Conectar ao SQLite
    $conn = new PDO("sqlite:$dbPath");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Criar tabela de usuários
    $conn->exec("
        CREATE TABLE IF NOT EXISTS usuarios (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nome TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE,
            senha TEXT NOT NULL
        );
    ");

    // Criar tabela de tarefas
    $conn->exec("
        CREATE TABLE IF NOT EXISTS tarefas (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            usuario_id INTEGER NOT NULL,
            titulo TEXT NOT NULL,
            descricao TEXT,
            concluida INTEGER DEFAULT 0,
            criado_em TEXT DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(usuario_id) REFERENCES usuarios(id)
        );
    ");

    echo "Banco de dados criado com sucesso em: $dbPath\n";

} catch (Exception $e) {
    echo "Erro ao criar banco de dados: " . $e->getMessage() . "\n";
}
