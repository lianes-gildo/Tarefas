<?php
session_start();
header('Content-Type: application/json');
require 'conexao.php';

$input = json_decode(file_get_contents('php://input'), true);

$email = trim($input['email'] ?? '');
$senha = $input['senha'] ?? '';

if (!$email || !$senha) {
    echo json_encode(['error'=>'Preencha todos os campos']);
    exit;
}

$stmt = $conn->prepare('SELECT id,nome,senha FROM usuarios WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($senha, $user['senha'])) {
    echo json_encode(['error'=>'Credenciais inválidas']);
    exit;
}

// Login ok
$_SESSION['usuario_id'] = $user['id'];
$_SESSION['usuario_nome'] = $user['nome'];

echo json_encode(['status'=>'ok','nome'=>$user['nome']]);
