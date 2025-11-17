<?php
session_start();
header('Content-Type: application/json');
require 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['error'=>'Não autenticado']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$id = intval($input['id'] ?? 0);
$titulo = trim($input['titulo'] ?? '');
$descricao = trim($input['descricao'] ?? '');

if (!$id || !$titulo) {
    echo json_encode(['error'=>'Dados inválidos']);
    exit;
}

$stmt = $conn->prepare('UPDATE tarefas SET titulo=?,descricao=? WHERE id=? AND usuario_id=?');
$stmt->execute([$titulo,$descricao,$id,$_SESSION['usuario_id']]);

echo json_encode(['status'=>'ok']);
