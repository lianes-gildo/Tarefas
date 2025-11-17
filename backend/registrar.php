<?php
session_start();
header('Content-Type: application/json');
require 'conexao.php';

$input = json_decode(file_get_contents('php://input'), true);

$nome = trim($input['nome'] ?? '');
$email = trim($input['email'] ?? '');
$senha = $input['senha'] ?? '';

if (!$nome || !$email || !$senha) {
    echo json_encode(['error' => 'Preencha todos os campos']);
    exit;
}

// Verifica se o email já existe
$stmt = $conn->prepare('SELECT id FROM usuarios WHERE email = ?');
$stmt->execute([$email]);
if ($stmt->fetch()) {
    echo json_encode(['error' => 'Email já cadastrado']);
    exit;
}

// Cria hash da senha
$hash = password_hash($senha, PASSWORD_DEFAULT);

// Insere usuário
$stmt = $conn->prepare('INSERT INTO usuarios (nome,email,senha) VALUES (?,?,?)');
$stmt->execute([$nome,$email,$hash]);

echo json_encode(['status' => 'ok']);
